<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Models\MachineUsageLog;
use App\Models\LaundryOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MachineController extends Controller
{
    protected function getStoreId()
    {
        return session('store_id');
    }

    /**
     * Machine list
     */
    public function index()
    {
        $storeId = $this->getStoreId();
        
        $machines = Machine::where('store_id', $storeId)
            ->with('currentUsage')
            ->get();

        $washers = $machines->where('type', 'washer');
        $dryers = $machines->where('type', 'dryer');

        return view('laundry.machines', compact('machines', 'washers', 'dryers'));
    }

    /**
     * Store new machine
     */
    public function store(Request $request)
    {
        $storeId = $this->getStoreId();

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:washer,dryer',
            'capacity_kg' => 'required|numeric|min:0',
        ]);

        Machine::create([
            'store_id' => $storeId,
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'brand' => $request->brand,
            'model' => $request->model,
            'capacity_kg' => $request->capacity_kg,
            'default_cycle_minutes' => $request->default_cycle_minutes ?? 30,
            'status' => Machine::STATUS_AVAILABLE,
            'notes' => $request->notes,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Machine added successfully']);
    }

    /**
     * Update machine
     */
    public function update(Request $request, $id)
    {
        $storeId = $this->getStoreId();
        $machine = Machine::where('store_id', $storeId)->findOrFail($id);

        $machine->update($request->only([
            'name', 'code', 'type', 'brand', 'model', 
            'capacity_kg', 'default_cycle_minutes', 'status', 'notes', 'is_active'
        ]));

        return response()->json(['success' => true, 'message' => 'Machine updated']);
    }

    /**
     * Delete machine
     */
    public function destroy($id)
    {
        $storeId = $this->getStoreId();
        Machine::where('store_id', $storeId)->findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Machine deleted']);
    }

    /**
     * Start machine usage
     */
    public function startUsage(Request $request)
    {
        $storeId = $this->getStoreId();
        $userId = auth()->id();

        $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'load_weight_kg' => 'nullable|numeric|min:0',
            'items_count' => 'nullable|integer|min:0',
        ]);

        $machine = Machine::where('store_id', $storeId)->findOrFail($request->machine_id);

        if (!$machine->isAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Machine is not available'
            ], 400);
        }

        // Check for overload
        $overloadWarning = false;
        if ($request->load_weight_kg && $request->load_weight_kg > $machine->capacity_kg) {
            $overloadWarning = true;
        }

        try {
            DB::beginTransaction();

            // Create usage log
            $usageLog = MachineUsageLog::create([
                'machine_id' => $machine->id,
                'laundry_order_id' => $request->laundry_order_id,
                'user_id' => $userId,
                'store_id' => $storeId,
                'load_weight_kg' => $request->load_weight_kg,
                'items_count' => $request->items_count ?? 0,
                'overload_warning' => $overloadWarning,
                'started_at' => now(),
                'set_duration_minutes' => $request->duration_minutes ?? $machine->default_cycle_minutes,
                'wash_type' => $request->wash_type,
                'temperature' => $request->temperature,
                'spin_speed' => $request->spin_speed,
                'status' => 'running',
                'notes' => $request->notes,
            ]);

            // Update machine status
            $machine->update(['status' => Machine::STATUS_IN_USE]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $overloadWarning 
                    ? 'Machine started with OVERLOAD WARNING! Load exceeds capacity.'
                    : 'Machine started successfully',
                'overload_warning' => $overloadWarning,
                'usage_log' => $usageLog
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error starting machine: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * End machine usage
     */
    public function endUsage(Request $request, $usageLogId)
    {
        $storeId = $this->getStoreId();
        
        $usageLog = MachineUsageLog::where('store_id', $storeId)->findOrFail($usageLogId);
        $machine = $usageLog->machine;

        try {
            DB::beginTransaction();

            $endedAt = now();
            $durationMinutes = $usageLog->started_at->diffInMinutes($endedAt);

            $usageLog->update([
                'ended_at' => $endedAt,
                'duration_minutes' => $durationMinutes,
                'status' => $request->status ?? 'completed',
                'issues' => $request->issues,
            ]);

            // Update machine status
            $machine->update(['status' => Machine::STATUS_AVAILABLE]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Machine cycle completed',
                'duration_minutes' => $durationMinutes,
                'usage_log' => $usageLog->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error ending machine usage: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Machine usage history
     */
    public function usageHistory(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $query = MachineUsageLog::where('store_id', $storeId)
            ->with(['machine', 'laundryOrder', 'user']);

        if ($request->machine_id) {
            $query->where('machine_id', $request->machine_id);
        }

        if ($request->date) {
            $query->whereDate('started_at', $request->date);
        }

        $logs = $query->orderBy('started_at', 'desc')->paginate(20);

        $machines = Machine::where('store_id', $storeId)->get();

        return view('laundry.machine-usage', compact('logs', 'machines'));
    }

    /**
     * Get machine status for dashboard
     */
    public function getStatus()
    {
        $storeId = $this->getStoreId();
        
        $machines = Machine::where('store_id', $storeId)
            ->where('is_active', true)
            ->with('currentUsage')
            ->get();

        return response()->json([
            'machines' => $machines,
            'available_washers' => $machines->where('type', 'washer')->where('status', 'available')->count(),
            'available_dryers' => $machines->where('type', 'dryer')->where('status', 'available')->count(),
        ]);
    }
}

