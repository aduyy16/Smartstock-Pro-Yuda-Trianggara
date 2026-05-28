<?php

namespace App\Http\Controllers;

use App\Jobs\LowStockNotificationJob;
use App\Jobs\ProductReportJob;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QueueTriggerController extends Controller
{
    /**
     * Dispatch background compilation of the Products Excel catalog report.
     */
    public function generateReport(Request $request)
    {
        $timestamp = Carbon::now()->format('Ymd_His');
        $filename = "products_report_{$timestamp}.xlsx";

        // 1. Create a Report record to track status in the UI
        $report = Report::create([
            'user_id' => auth()->id(),
            'filename' => $filename,
            'status' => 'pending'
        ]);

        // 2. Dispatch the background job to the queue
        ProductReportJob::dispatch($report);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Laporan produk berhasil dimasukkan ke antrean background.',
                'report' => $report
            ]);
        }

        return redirect()->back()->with('success', 'Laporan produk berhasil dimasukkan ke antrean background. Laporan akan siap dalam beberapa saat!');
    }

    /**
     * Dispatch background scans for low stock products and notify corresponding roles.
     */
    public function runLowStockCheck(Request $request)
    {
        // Dispatch low stock check job to the queue
        LowStockNotificationJob::dispatch();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pemindaian stok rendah background berhasil dimulai.'
            ]);
        }

        return redirect()->back()->with('success', 'Pemindaian stok rendah di background berhasil dijalankan. Notifikasi akan segera muncul!');
    }
}
