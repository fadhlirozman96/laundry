<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Redirect superadmin to their own dashboard
        if ($user->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }
        
        // Get accessible store IDs
        $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
        
        // Check if user has "All Stores View" feature
        $hasAllStoresView = $user->hasAllStoresView() && count($accessibleStoreIds) > 1;
        
        // Check if user wants to view all stores
        $viewAllStores = $hasAllStoresView && session('view_all_stores', false);
        
        // Get selected store from session, or use first accessible store
        $selectedStoreId = session('selected_store_id');
        
        // If viewing all stores, use all accessible store IDs
        if ($viewAllStores) {
            $storeIdsToQuery = $accessibleStoreIds;
            $selectedStoreId = 'all'; // Indicator for UI
        } else {
            // Single store mode
            // If no store selected or selected store is not accessible, use first accessible store
            if (!$selectedStoreId || !in_array($selectedStoreId, $accessibleStoreIds)) {
                if (!empty($accessibleStoreIds)) {
                    $selectedStoreId = $accessibleStoreIds[0];
                    session(['selected_store_id' => $selectedStoreId]);
                }
            }
            $storeIdsToQuery = [$selectedStoreId];
        }
        
        // Get selected year for chart (default to current year)
        $currentYear = Carbon::now()->year;
        $selectedYear = (int) $request->get('year', $currentYear);
        
        // Validate selected year is within range
        $minYear = $currentYear - 5;
        $maxYear = $currentYear + 5;
        if ($selectedYear < $minYear || $selectedYear > $maxYear) {
            $selectedYear = $currentYear;
        }
        
        // Initialize statistics
        $totalSalesToday = 0;
        $totalOrderToday = 0;
        $totalSalesThisYear = 0;
        $totalSalesOverall = 0;
        $customerCount = 0;
        $purchaseInvoiceCount = 0;
        $salesInvoiceCount = 0;
        $topServices = collect([]);
        $monthlySalesData = collect([]);
        
        if (!empty($storeIdsToQuery)) {
            $today = Carbon::today();
            $startOfYear = Carbon::now()->startOfYear();
            
            // Total Sales Today
            $totalSalesToday = Order::whereIn('store_id', $storeIdsToQuery)
                ->whereDate('created_at', $today)
                ->sum('total');
            
            // Total Order Today
            $totalOrderToday = Order::whereIn('store_id', $storeIdsToQuery)
                ->whereDate('created_at', $today)
                ->count();
            
            // Total Sales This Year
            $totalSalesThisYear = Order::whereIn('store_id', $storeIdsToQuery)
                ->where('created_at', '>=', $startOfYear)
                ->sum('total');
            
            // Total Sales Overall
            $totalSalesOverall = Order::whereIn('store_id', $storeIdsToQuery)
                ->sum('total');
            
            // Customer Count (unique customers)
            $customerCount = Order::whereIn('store_id', $storeIdsToQuery)
                ->whereNotNull('customer_email')
                ->distinct('customer_email')
                ->count('customer_email');
            
            // If no emails, count by customer_name
            if ($customerCount == 0) {
                $customerCount = Order::whereIn('store_id', $storeIdsToQuery)
                    ->whereNotNull('customer_name')
                    ->distinct('customer_name')
                    ->count('customer_name');
            }
            
            // Purchase Invoice Count (total orders)
            $purchaseInvoiceCount = Order::whereIn('store_id', $storeIdsToQuery)
                ->count();
            
            // Sales Invoice Count (total orders - same as purchase for now)
            $salesInvoiceCount = Order::whereIn('store_id', $storeIdsToQuery)
                ->count();
            
            // Top 4 Most Popular Services (based on order items quantity)
            // Build dynamic query based on number of stores
            $storeIdPlaceholders = implode(',', array_fill(0, count($storeIdsToQuery), '?'));
            $queryParams = array_merge($storeIdsToQuery, $storeIdsToQuery);
            
            $topServices = DB::select("
                SELECT 
                    oi.product_name,
                    SUM(oi.quantity) as total_quantity,
                    SUM(oi.subtotal) as total_revenue,
                    (
                        SELECT COUNT(DISTINCT o.id)
                        FROM orders o
                        INNER JOIN order_items oi2 ON o.id = oi2.order_id
                        WHERE o.store_id IN ($storeIdPlaceholders)
                        AND oi2.product_name = oi.product_name
                    ) as order_count
                FROM order_items oi
                INNER JOIN orders o ON oi.order_id = o.id
                WHERE o.store_id IN ($storeIdPlaceholders)
                GROUP BY oi.product_name
                ORDER BY total_quantity DESC
                LIMIT 4
            ", $queryParams);
            
            // Convert to collection of objects
            $topServices = collect($topServices)->map(function($item) {
                return (object) [
                    'product_name' => $item->product_name,
                    'total_quantity' => (float) $item->total_quantity,
                    'total_revenue' => (float) $item->total_revenue,
                    'order_count' => (int) ($item->order_count ?? 0)
                ];
            });
            
            // Monthly Sales Data for Chart (filtered by selected year)
            $startOfSelectedYear = Carbon::create($selectedYear, 1, 1)->startOfYear();
            $endOfSelectedYear = Carbon::create($selectedYear, 12, 31)->endOfYear();
            
            $monthlySalesData = Order::whereIn('store_id', $storeIdsToQuery)
                ->whereBetween('created_at', [$startOfSelectedYear, $endOfSelectedYear])
                ->where('total', '>', 0) // Only positive sales values
                ->select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('SUM(total) as total')
                )
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->orderBy('month', 'asc')
                ->get();
            
            // Get latest orders for the selected store(s)
            $latestOrders = Order::whereIn('store_id', $storeIdsToQuery)
                ->with(['items', 'store'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();
        } else {
            $latestOrders = collect([]);
        }
        
        // Format monthly data for chart (for selected year, show all 12 months)
        $chartData = $this->formatMonthlyDataForChartByYear($monthlySalesData, $selectedYear);
        
        // Generate available years (5 years before and 5 years after current year)
        $currentYear = Carbon::now()->year;
        $availableYears = [];
        for ($year = $currentYear - 5; $year <= $currentYear + 5; $year++) {
            $availableYears[] = $year;
        }
        // Reverse to show newest first
        $availableYears = array_reverse($availableYears);
        
        // If AJAX request, return only chart data as JSON
        if ($request->ajax()) {
            return response()->json([
                'chartData' => $chartData,
                'selectedYear' => $selectedYear
            ]);
        }
        
        return view('index', compact(
            'latestOrders',
            'totalSalesToday',
            'totalOrderToday',
            'totalSalesThisYear',
            'totalSalesOverall',
            'customerCount',
            'purchaseInvoiceCount',
            'salesInvoiceCount',
            'topServices',
            'chartData',
            'selectedYear',
            'availableYears',
            'hasAllStoresView',
            'viewAllStores'
        ));
    }
    
    private function formatMonthlyDataForChart($monthlyData)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // Create a map for quick lookup: 'Y-m' => total
        $monthlyMap = [];
        foreach ($monthlyData as $item) {
            $key = $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            $monthlyMap[$key] = (float) $item->total;
        }
        
        // Get the last 12 months
        $chartMonths = [];
        $chartData = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $targetDate = Carbon::now()->subMonths($i);
            $monthKey = $targetDate->format('Y-m');
            $monthName = $months[$targetDate->month - 1];
            
            $value = isset($monthlyMap[$monthKey]) ? $monthlyMap[$monthKey] : 0;
            
            $chartMonths[] = $monthName;
            $chartData[] = $value;
        }
        
        return [
            'months' => $chartMonths,
            'data' => $chartData
        ];
    }
    
    private function formatMonthlyDataForChartByYear($monthlyData, $year)
    {
        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // Create a map for quick lookup: month => total
        $monthlyMap = [];
        foreach ($monthlyData as $item) {
            if ($item->year == $year) {
                // Ensure only positive values
                $value = max(0, (float) $item->total);
                $monthlyMap[$item->month] = $value;
            }
        }
        
        // Get all 12 months for the selected year (always return 12 months)
        $chartMonths = [];
        $chartData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $value = isset($monthlyMap[$month]) ? max(0, $monthlyMap[$month]) : 0; // Ensure positive
            $chartMonths[] = $months[$month - 1];
            $chartData[] = $value;
        }
        
        return [
            'months' => $chartMonths,
            'data' => $chartData
        ];
    }
}

