<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{
    /**
     * Generate receipt PDF for an order
     */
    public function generate($id)
    {
        $order = Order::findOrFail($id);
        
        // Only generate receipt for paid orders
        if ($order->payment_status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Receipt can only be generated for paid orders'
            ], 400);
        }

        // If receipt already exists, return it
        if ($order->receipt_path && Storage::disk('public')->exists($order->receipt_path)) {
            return response()->json([
                'success' => true,
                'receipt_path' => $order->receipt_path,
                'message' => 'Receipt already exists'
            ]);
        }

        try {
            // Ensure receipts directory exists
            if (!Storage::disk('public')->exists('receipts')) {
                Storage::disk('public')->makeDirectory('receipts');
            }

            // Generate receipt HTML (temporary until DomPDF is installed)
            $html = view('pdf.receipt', [
                'order' => $order->load(['items.product', 'store', 'user'])
            ])->render();

            // Save receipt as HTML
            $receiptPath = 'receipts/' . $order->order_number . '.html';
            Storage::disk('public')->put($receiptPath, $html);

            // Update order with receipt path
            $order->update(['receipt_path' => $receiptPath]);

            return response()->json([
                'success' => true,
                'receipt_path' => $receiptPath,
                'message' => 'Receipt generated successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Receipt generation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate receipt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download receipt
     */
    public function download($id)
    {
        try {
            $order = Order::with(['items.product', 'store', 'user'])->findOrFail($id);
            
            // Check if order is paid
            if ($order->payment_status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Receipt can only be downloaded for paid orders. Current status: ' . $order->payment_status
                ], 400);
            }
            
            // Generate receipt if it doesn't exist
            if (!$order->receipt_path || !Storage::disk('public')->exists($order->receipt_path)) {
                $generateResponse = $this->generate($order->id);
                $order->refresh();
                
                // If generation failed, return error
                if ($generateResponse->status() !== 200) {
                    return $generateResponse;
                }
            }

            if (!$order->receipt_path || !Storage::disk('public')->exists($order->receipt_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Receipt file not found after generation'
                ], 404);
            }

            $filePath = Storage::disk('public')->path($order->receipt_path);
            
            return response()->download($filePath, 'Receipt-' . $order->order_number . '.html', [
                'Content-Type' => 'text/html',
            ]);
        } catch (\Exception $e) {
            \Log::error('Receipt download error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error downloading receipt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View receipt in browser
     */
    public function view($id)
    {
        $order = Order::findOrFail($id);
        
        // Generate receipt if it doesn't exist
        if (!$order->receipt_path || !Storage::disk('public')->exists($order->receipt_path)) {
            $this->generate($order->id);
            $order->refresh();
        }

        if (!$order->receipt_path || !Storage::disk('public')->exists($order->receipt_path)) {
            abort(404, 'Receipt not found');
        }

        $html = Storage::disk('public')->get($order->receipt_path);
        
        return response($html)->header('Content-Type', 'text/html');
    }

    /**
     * Generate thermal receipt for an order
     */
    public function generateThermal($id)
    {
        $order = Order::findOrFail($id);
        
        // Only generate receipt for paid orders
        if ($order->payment_status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Thermal receipt can only be generated for paid orders'
            ], 400);
        }

        // If thermal receipt already exists, return it
        if ($order->thermal_receipt_path && Storage::disk('public')->exists($order->thermal_receipt_path)) {
            return response()->json([
                'success' => true,
                'receipt_path' => $order->thermal_receipt_path,
                'message' => 'Thermal receipt already exists'
            ]);
        }

        try {
            // Ensure receipts directory exists
            if (!Storage::disk('public')->exists('receipts')) {
                Storage::disk('public')->makeDirectory('receipts');
            }

            // Generate thermal receipt HTML
            $html = view('pdf.thermal-receipt', [
                'order' => $order->load(['items.product', 'store', 'user'])
            ])->render();

            // Save thermal receipt HTML
            $receiptPath = 'receipts/' . $order->order_number . '-thermal.html';
            Storage::disk('public')->put($receiptPath, $html);

            // Update order with thermal receipt path
            $order->update(['thermal_receipt_path' => $receiptPath]);

            return response()->json([
                'success' => true,
                'receipt_path' => $receiptPath,
                'message' => 'Thermal receipt generated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate thermal receipt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download thermal receipt
     */
    public function downloadThermal($id)
    {
        try {
            $order = Order::with(['items.product', 'store', 'user'])->findOrFail($id);
            
            // Check if order is paid
            if ($order->payment_status !== 'paid') {
                return response()->json([
                    'success' => false,
                    'message' => 'Thermal receipt can only be downloaded for paid orders. Current status: ' . $order->payment_status
                ], 400);
            }
            
            // Generate thermal receipt if it doesn't exist
            if (!$order->thermal_receipt_path || !Storage::disk('public')->exists($order->thermal_receipt_path)) {
                $generateResponse = $this->generateThermal($order->id);
                $order->refresh();
                
                // If generation failed, return error
                if ($generateResponse->status() !== 200) {
                    return $generateResponse;
                }
            }

            if (!$order->thermal_receipt_path || !Storage::disk('public')->exists($order->thermal_receipt_path)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thermal receipt file not found after generation'
                ], 404);
            }

            $filePath = Storage::disk('public')->path($order->thermal_receipt_path);
            
            return response()->download($filePath, 'Thermal-Receipt-' . $order->order_number . '.html', [
                'Content-Type' => 'text/html',
            ]);
        } catch (\Exception $e) {
            \Log::error('Thermal receipt download error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error downloading thermal receipt: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View thermal receipt in browser
     */
    public function viewThermal($id)
    {
        $order = Order::findOrFail($id);
        
        // Generate thermal receipt if it doesn't exist
        if (!$order->thermal_receipt_path || !Storage::disk('public')->exists($order->thermal_receipt_path)) {
            $this->generateThermal($order->id);
            $order->refresh();
        }

        if (!$order->thermal_receipt_path || !Storage::disk('public')->exists($order->thermal_receipt_path)) {
            abort(404, 'Thermal receipt not found');
        }

        $html = Storage::disk('public')->get($order->thermal_receipt_path);
        
        return response($html)->header('Content-Type', 'text/html');
    }
}

