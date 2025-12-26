<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Check if user can access audit trail
     */
    protected function canAccessAuditTrail()
    {
        $user = auth()->user();
        return $user && ($user->isSuperAdmin() || $user->isBusinessOwner());
    }

    /**
     * Get store IDs the user can access
     */
    protected function getAccessibleStoreIds()
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return null; // Can access all
        }
        
        if ($user->isBusinessOwner()) {
            return Store::where('created_by', $user->id)->pluck('id')->toArray();
        }
        
        return [];
    }

    /**
     * Get user IDs the user can access
     */
    protected function getAccessibleUserIds()
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return null; // Can access all
        }
        
        if ($user->isBusinessOwner()) {
            $userIds = User::where('account_owner_id', $user->id)->pluck('id')->toArray();
            $userIds[] = $user->id; // Include owner's own activities
            return $userIds;
        }
        
        return [$user->id]; // Only own activities
    }

    /**
     * Main audit trail view
     */
    public function index(Request $request)
    {
        if (!$this->canAccessAuditTrail()) {
            abort(403, 'Unauthorized. Only Super Admin and Business Owners can access Audit Trail.');
        }

        $user = auth()->user();
        $query = ActivityLog::with(['user', 'store']);

        // Apply access restrictions
        if ($user->isBusinessOwner()) {
            $storeIds = $this->getAccessibleStoreIds();
            $userIds = $this->getAccessibleUserIds();
            $query->where(function($q) use ($storeIds, $userIds) {
                $q->whereIn('store_id', $storeIds)
                  ->orWhereIn('user_id', $userIds);
            });
        }

        // Filters
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('store_id')) {
            // Check if user can access this store
            $storeIds = $this->getAccessibleStoreIds();
            if ($storeIds === null || in_array($request->store_id, $storeIds)) {
                $query->where('store_id', $request->store_id);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('model_identifier', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%");
            });
        }

        // Get data
        $logs = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get filter options
        $actions = [
            'create' => 'Created',
            'read' => 'Viewed',
            'update' => 'Updated',
            'delete' => 'Deleted',
            'login' => 'Login',
            'logout' => 'Logout',
            'export' => 'Export',
            'import' => 'Import',
        ];

        $modules = ActivityLog::distinct()
            ->whereNotNull('module')
            ->pluck('module')
            ->mapWithKeys(function ($item) {
                return [$item => ucfirst($item)];
            })->toArray();

        // Get users and stores for filters
        if ($user->isSuperAdmin()) {
            $users = User::orderBy('name')->get(['id', 'name']);
            $stores = Store::orderBy('name')->get(['id', 'name']);
        } else {
            $userIds = $this->getAccessibleUserIds();
            $storeIds = $this->getAccessibleStoreIds();
            $users = User::whereIn('id', $userIds)->orderBy('name')->get(['id', 'name']);
            $stores = Store::whereIn('id', $storeIds)->orderBy('name')->get(['id', 'name']);
        }

        // Stats
        $totalActivities = ActivityLog::count();
        $totalLogs = ActivityLog::count(); // Same as total activities
        $activeUsers = ActivityLog::distinct('user_id')->whereNotNull('user_id')->count('user_id');
        // Online users: users with activity in last 5 minutes
        $onlineUsers = ActivityLog::where('created_at', '>=', now()->subMinutes(5))
            ->distinct('user_id')
            ->whereNotNull('user_id')
            ->count('user_id');

        return view('audit-trail.index', compact(
            'logs', 'actions', 'modules', 'users', 'stores', 'totalActivities', 'totalLogs', 'activeUsers', 'onlineUsers'
        ));
    }

    /**
     * View activity details
     */
    public function show($id)
    {
        if (!$this->canAccessAuditTrail()) {
            abort(403, 'Unauthorized');
        }

        $user = auth()->user();
        $query = ActivityLog::with(['user', 'store']);

        // Apply access restrictions for business owner
        if ($user->isBusinessOwner()) {
            $storeIds = $this->getAccessibleStoreIds();
            $userIds = $this->getAccessibleUserIds();
            $query->where(function($q) use ($storeIds, $userIds) {
                $q->whereIn('store_id', $storeIds)
                  ->orWhereIn('user_id', $userIds);
            });
        }

        $log = $query->findOrFail($id);

        return response()->json($log);
    }

    /**
     * Get activity data for DataTables (AJAX)
     */
    public function getData(Request $request)
    {
        if (!$this->canAccessAuditTrail()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = auth()->user();
        $query = ActivityLog::with(['user', 'store']);

        // Apply access restrictions
        if ($user->isBusinessOwner()) {
            $storeIds = $this->getAccessibleStoreIds();
            $userIds = $this->getAccessibleUserIds();
            $query->where(function($q) use ($storeIds, $userIds) {
                $q->whereIn('store_id', $storeIds)
                  ->orWhereIn('user_id', $userIds);
            });
        }

        return datatables()->of($query)
            ->addColumn('action_badge', function($row) {
                return $row->getActionBadge();
            })
            ->addColumn('user_display', function($row) {
                return $row->user_name . '<br><small class="text-muted">' . $row->user_role . '</small>';
            })
            ->addColumn('subject_display', function($row) {
                $text = $row->model_name ?? '-';
                if ($row->model_identifier) {
                    $text .= '<br><small class="text-muted">' . $row->model_identifier . '</small>';
                }
                return $text;
            })
            ->addColumn('store_display', function($row) {
                return $row->store_name ?? '<span class="text-muted">-</span>';
            })
            ->addColumn('time_display', function($row) {
                return $row->created_at->format('d M Y H:i:s') . '<br><small class="text-muted">' . $row->created_at->diffForHumans() . '</small>';
            })
            ->addColumn('details_btn', function($row) {
                return '<button class="btn btn-sm btn-outline-primary" onclick="viewDetails(' . $row->id . ')"><i data-feather="eye"></i></button>';
            })
            ->rawColumns(['action_badge', 'user_display', 'subject_display', 'store_display', 'time_display', 'details_btn'])
            ->orderColumn('created_at', function ($query, $order) {
                $query->orderBy('created_at', $order);
            })
            ->make(true);
    }

    /**
     * User activity summary
     */
    public function userActivity(Request $request, $userId = null)
    {
        if (!$this->canAccessAuditTrail()) {
            abort(403, 'Unauthorized');
        }

        $user = auth()->user();
        $targetUserId = $userId ?? $user->id;

        // Check access
        if ($user->isBusinessOwner()) {
            $accessibleUserIds = $this->getAccessibleUserIds();
            if (!in_array($targetUserId, $accessibleUserIds)) {
                abort(403, 'Unauthorized to view this user\'s activities');
            }
        }

        $targetUser = User::findOrFail($targetUserId);

        $activities = ActivityLog::where('user_id', $targetUserId)
            ->orderBy('created_at', 'desc')
            ->paginate(30);

        // Activity summary
        $summary = ActivityLog::where('user_id', $targetUserId)
            ->selectRaw('action, COUNT(*) as count')
            ->groupBy('action')
            ->pluck('count', 'action')
            ->toArray();

        // Recent logins
        $recentLogins = ActivityLog::where('user_id', $targetUserId)
            ->where('action', 'login')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('audit-trail.user-activity', compact('targetUser', 'activities', 'summary', 'recentLogins'));
    }

    /**
     * Model history (specific record changes)
     */
    public function modelHistory(Request $request, $modelType, $modelId)
    {
        if (!$this->canAccessAuditTrail()) {
            abort(403, 'Unauthorized');
        }

        $logs = ActivityLog::where('model_type', 'like', '%' . $modelType)
            ->where('model_id', $modelId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($logs);
    }

    /**
     * Export audit logs
     */
    public function export(Request $request)
    {
        if (!$this->canAccessAuditTrail()) {
            abort(403, 'Unauthorized');
        }

        $user = auth()->user();
        $query = ActivityLog::with(['user', 'store']);

        // Apply access restrictions
        if ($user->isBusinessOwner()) {
            $storeIds = $this->getAccessibleStoreIds();
            $userIds = $this->getAccessibleUserIds();
            $query->where(function($q) use ($storeIds, $userIds) {
                $q->whereIn('store_id', $storeIds)
                  ->orWhereIn('user_id', $userIds);
            });
        }

        // Apply date filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        // Log the export
        \App\Services\ActivityLogger::logExport('Audit Trail', 'audit', [
            'records_count' => $logs->count(),
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ]);

        // Generate CSV
        $filename = 'audit_trail_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Date/Time', 'User', 'Role', 'Store', 'Action', 'Module', 
                'Subject', 'Identifier', 'Description', 'IP Address', 'Browser', 'Platform'
            ]);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->user_name,
                    $log->user_role,
                    $log->store_name ?? '-',
                    $log->action_label,
                    $log->module ?? '-',
                    $log->model_name ?? '-',
                    $log->model_identifier ?? '-',
                    $log->description,
                    $log->ip_address ?? '-',
                    $log->browser ?? '-',
                    $log->platform ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Dashboard stats for audit
     */
    public function stats()
    {
        if (!$this->canAccessAuditTrail()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = auth()->user();
        $query = ActivityLog::query();

        // Apply access restrictions
        if ($user->isBusinessOwner()) {
            $storeIds = $this->getAccessibleStoreIds();
            $userIds = $this->getAccessibleUserIds();
            $query->where(function($q) use ($storeIds, $userIds) {
                $q->whereIn('store_id', $storeIds)
                  ->orWhereIn('user_id', $userIds);
            });
        }

        // Today's stats
        $today = (clone $query)->whereDate('created_at', today());
        
        return response()->json([
            'today_total' => $today->count(),
            'today_creates' => (clone $today)->where('action', 'create')->count(),
            'today_updates' => (clone $today)->where('action', 'update')->count(),
            'today_deletes' => (clone $today)->where('action', 'delete')->count(),
            'today_logins' => (clone $today)->where('action', 'login')->count(),
            'week_total' => (clone $query)->whereBetween('created_at', [now()->startOfWeek(), now()])->count(),
            'month_total' => (clone $query)->whereMonth('created_at', now()->month)->count(),
        ]);
    }
}



