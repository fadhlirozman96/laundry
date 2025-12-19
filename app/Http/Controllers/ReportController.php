<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected function getStoreId()
    {
        return session('store_id');
    }

    /**
     * Sales Report - Shows product sales performance
     */
    public function salesReport(Request $request)
    {
        $storeId = $this->getStoreId();
        
        // Get date filters
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Get products with sales data
        $salesData = OrderItem::select(
                'products.id',
                'products.name as product_name',
                'products.sku',
                'products.image',
                'products.quantity as in_stock',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as sold_qty'),
                DB::raw('SUM(order_items.subtotal) as sold_amount')
            )
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.store_id', $storeId)
            ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.image', 'products.quantity', 'categories.name')
            ->orderByDesc('sold_amount')
            ->get();

        // Get summary stats
        $totalSales = $salesData->sum('sold_amount');
        $totalQuantity = $salesData->sum('sold_qty');
        $topProduct = $salesData->first();

        // Get products dropdown
        $products = Product::where('store_id', $storeId)->where('is_active', true)->get();
        
        return view('sales-report', compact('salesData', 'products', 'startDate', 'endDate', 'totalSales', 'totalQuantity', 'topProduct'));
    }

    /**
     * Get sales report data for AJAX DataTable
     */
    public function getSalesReportData(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $query = OrderItem::select(
                'products.id',
                'products.name as product_name',
                'products.sku',
                'products.image',
                'products.quantity as in_stock',
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as sold_qty'),
                DB::raw('SUM(order_items.subtotal) as sold_amount')
            )
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.store_id', $storeId)
            ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.image', 'products.quantity', 'categories.name');

        return datatables()->of($query)
            ->addColumn('checkbox', function($row) {
                return '<label class="checkboxs"><input type="checkbox"><span class="checkmarks"></span></label>';
            })
            ->addColumn('product_display', function($row) {
                $image = $row->image ? asset('storage/' . $row->image) : asset('build/img/products/default.png');
                return '<div class="productimgname">
                    <div class="view-product me-2">
                        <img src="'.$image.'" alt="product" style="width:40px;height:40px;object-fit:cover;">
                    </div>
                    <a href="javascript:void(0);">'.$row->product_name.'</a>
                </div>';
            })
            ->addColumn('sold_amount_display', function($row) {
                return 'MYR ' . number_format($row->sold_amount, 2);
            })
            ->rawColumns(['checkbox', 'product_display'])
            ->make(true);
    }

    /**
     * Purchase Report - Shows product purchase/cost data
     */
    public function purchaseReport(Request $request)
    {
        $storeId = $this->getStoreId();
        
        // Get products with cost data (simulating purchase based on product cost)
        $purchaseData = Product::select(
                'products.id',
                'products.name as product_name',
                'products.sku',
                'products.image',
                'products.quantity as in_stock',
                'products.cost',
                'categories.name as category_name',
                DB::raw('(products.cost * products.quantity) as purchase_amount')
            )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.store_id', $storeId)
            ->where('products.is_active', true)
            ->get();

        $totalPurchaseAmount = $purchaseData->sum('purchase_amount');
        $totalQuantity = $purchaseData->sum('in_stock');
        
        $products = Product::where('store_id', $storeId)->where('is_active', true)->get();
        
        return view('purchase-report', compact('purchaseData', 'products', 'totalPurchaseAmount', 'totalQuantity'));
    }

    /**
     * Inventory Report - Shows current stock levels
     */
    public function inventoryReport(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $inventoryData = Product::select(
                'products.*',
                'categories.name as category_name',
                'units.name as unit_name'
            )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('units', 'products.unit_id', '=', 'units.id')
            ->where('products.store_id', $storeId)
            ->where('products.is_active', true)
            ->get();

        // Summary stats
        $totalProducts = $inventoryData->count();
        $totalStock = $inventoryData->sum('quantity');
        $lowStockProducts = $inventoryData->filter(function($p) {
            return $p->track_quantity && $p->quantity <= $p->alert_quantity;
        })->count();
        $outOfStock = $inventoryData->where('quantity', 0)->count();

        $categories = \App\Models\Category::where('store_id', $storeId)->get();
        $products = Product::where('store_id', $storeId)->where('is_active', true)->get();
        
        return view('inventory-report', compact('inventoryData', 'categories', 'products', 'totalProducts', 'totalStock', 'lowStockProducts', 'outOfStock'));
    }

    /**
     * Supplier Report - Not applicable (no suppliers in system)
     * Show a notice that supplier management is not available
     */
    public function supplierReport(Request $request)
    {
        $storeId = $this->getStoreId();
        
        // Since there's no supplier model, show product sources/categories as alternative
        $purchaseData = Product::select(
                'products.id',
                'products.name as product_name',
                'products.sku',
                'products.image',
                'products.quantity',
                'products.cost',
                'products.price',
                'products.created_at',
                'categories.name as category_name',
                DB::raw('(products.cost * products.quantity) as total_value')
            )
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.store_id', $storeId)
            ->where('products.is_active', true)
            ->orderBy('products.created_at', 'desc')
            ->get();

        $totalValue = $purchaseData->sum('total_value');
        
        return view('supplier-report', compact('purchaseData', 'totalValue'));
    }

    /**
     * Customer Report - Shows customer purchase history
     */
    public function customerReport(Request $request)
    {
        $storeId = $this->getStoreId();
        
        // Get customers with their order stats (matching by email or phone)
        $customerData = Customer::select(
                'customers.id',
                'customers.name',
                'customers.email',
                'customers.phone',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('COALESCE(SUM(orders.total), 0) as total_amount'),
                DB::raw('COALESCE(SUM(CASE WHEN orders.payment_status = "paid" THEN orders.total ELSE 0 END), 0) as paid_amount'),
                DB::raw('COALESCE(SUM(CASE WHEN orders.payment_status != "paid" THEN orders.total ELSE 0 END), 0) as due_amount')
            )
            ->leftJoin('orders', function($join) {
                $join->on('customers.email', '=', 'orders.customer_email')
                     ->orOn('customers.phone', '=', 'orders.customer_phone');
            })
            ->where('customers.store_id', $storeId)
            ->groupBy('customers.id', 'customers.name', 'customers.email', 'customers.phone')
            ->orderByDesc('total_amount')
            ->get();

        // Summary stats
        $totalCustomers = $customerData->count();
        $totalRevenue = $customerData->sum('total_amount');
        $totalPaid = $customerData->sum('paid_amount');
        $totalDue = $customerData->sum('due_amount');
        
        return view('customer-report', compact('customerData', 'totalCustomers', 'totalRevenue', 'totalPaid', 'totalDue'));
    }

    /**
     * Income Report - Shows income from sales
     */
    public function incomeReport(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Get income from orders (paid)
        $incomeData = Order::select(
                'orders.*',
                'users.name as user_name'
            )
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.store_id', $storeId)
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('orders.created_at', 'desc')
            ->get();

        // Summary stats
        $totalIncome = $incomeData->sum('total');
        $totalOrders = $incomeData->count();
        $avgOrderValue = $totalOrders > 0 ? $totalIncome / $totalOrders : 0;
        
        // Income by payment method
        $byPaymentMethod = Order::where('store_id', $storeId)
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->groupBy('payment_method')
            ->select('payment_method', DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as count'))
            ->get();

        return view('income-report', compact('incomeData', 'startDate', 'endDate', 'totalIncome', 'totalOrders', 'avgOrderValue', 'byPaymentMethod'));
    }

    /**
     * Tax Report - Shows tax collected from sales
     */
    public function taxReport(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $startDate = $request->get('start_date', Carbon::now()->startOfYear()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfYear()->format('Y-m-d'));
        
        // Sales Tax Data (from orders)
        $salesTaxData = Order::select(
                'orders.id',
                'orders.order_number',
                'orders.customer_name',
                'orders.total',
                'orders.tax',
                'orders.discount',
                'orders.payment_method',
                'orders.created_at',
                'users.name as user_name'
            )
            ->leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->where('orders.store_id', $storeId)
            ->where('orders.tax', '>', 0)
            ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->orderBy('orders.created_at', 'desc')
            ->get();

        // Summary stats
        $totalSalesTax = $salesTaxData->sum('tax');
        $totalSalesAmount = $salesTaxData->sum('total');
        
        // Monthly tax summary
        $monthlyTax = Order::where('store_id', $storeId)
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, SUM(tax) as total_tax, SUM(total) as total_sales')
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        return view('tax-reports', compact('salesTaxData', 'startDate', 'endDate', 'totalSalesTax', 'totalSalesAmount', 'monthlyTax'));
    }

    /**
     * Profit & Loss Report - Shows profit/loss calculation
     */
    public function profitLossReport(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Revenue from orders
        $revenue = Order::where('store_id', $storeId)
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->sum('total');

        // Cost of goods sold (from order items)
        $costOfGoodsSold = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.store_id', $storeId)
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate, $endDate . ' 23:59:59'])
            ->selectRaw('SUM(order_items.quantity * products.cost) as total_cost')
            ->value('total_cost') ?? 0;

        // Gross profit
        $grossProfit = $revenue - $costOfGoodsSold;

        // Operating expenses
        $operatingExpenses = Expense::where('store_id', $storeId)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        // Net profit
        $netProfit = $grossProfit - $operatingExpenses;

        // Monthly breakdown
        $monthlyData = [];
        $currentDate = Carbon::parse($startDate)->startOfMonth();
        $endDateCarbon = Carbon::parse($endDate)->endOfMonth();

        while ($currentDate <= $endDateCarbon) {
            $monthStart = $currentDate->copy()->startOfMonth()->format('Y-m-d');
            $monthEnd = $currentDate->copy()->endOfMonth()->format('Y-m-d');
            
            $monthRevenue = Order::where('store_id', $storeId)
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$monthStart, $monthEnd . ' 23:59:59'])
                ->sum('total');

            $monthCogs = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->where('orders.store_id', $storeId)
                ->where('orders.payment_status', 'paid')
                ->whereBetween('orders.created_at', [$monthStart, $monthEnd . ' 23:59:59'])
                ->selectRaw('COALESCE(SUM(order_items.quantity * products.cost), 0) as total_cost')
                ->value('total_cost') ?? 0;

            $monthExpenses = Expense::where('store_id', $storeId)
                ->whereBetween('expense_date', [$monthStart, $monthEnd])
                ->sum('amount');

            $monthlyData[] = [
                'month' => $currentDate->format('F Y'),
                'revenue' => $monthRevenue,
                'cogs' => $monthCogs,
                'gross_profit' => $monthRevenue - $monthCogs,
                'expenses' => $monthExpenses,
                'net_profit' => ($monthRevenue - $monthCogs) - $monthExpenses
            ];

            $currentDate->addMonth();
        }

        // Expenses by category
        $expensesByCategory = Expense::select(
                'expense_categories.name as category_name',
                DB::raw('SUM(expenses.amount) as total')
            )
            ->leftJoin('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->where('expenses.store_id', $storeId)
            ->whereBetween('expenses.expense_date', [$startDate, $endDate])
            ->groupBy('expense_categories.name')
            ->get();

        return view('profit-loss', compact(
            'startDate', 'endDate', 'revenue', 'costOfGoodsSold', 'grossProfit', 
            'operatingExpenses', 'netProfit', 'monthlyData', 'expensesByCategory'
        ));
    }
}

