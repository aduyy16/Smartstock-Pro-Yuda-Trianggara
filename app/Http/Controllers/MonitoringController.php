<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use App\Models\MonitoringLog;
use App\Models\Report;
use App\Services\MonitoringDataGenerator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MonitoringController extends Controller
{
    /**
     * Display the monitoring dashboard view.
     */
    public function index(Request $request): View
    {
        // 1. Ensure logs are seeded initially for demonstration
        MonitoringDataGenerator::seedLogsIfEmpty(10);

        // 2. Fetch basic counts for cards
        $totalUsers = User::count();
        $totalTransactions = StockTransaction::count();
        $totalProducts = Product::count();
        $totalErrors = MonitoringLog::count();

        // 3. Process filter & search parameters
        $query = MonitoringLog::query();
        
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('message', 'like', '%' . $request->search . '%')
                  ->orWhere('component', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->latest()->paginate(10)->withQueryString();

        // 4. Generate historical metrics data for Chart.js initialization (15 points)
        $historyData = MonitoringDataGenerator::generateHistory(15);
        
        // 5. Get reports history for queue logs
        $reports = Report::with('user')->latest()->take(10)->get();

        return view('monitoring.index', compact(
            'totalUsers',
            'totalTransactions',
            'totalProducts',
            'totalErrors',
            'logs',
            'historyData',
            'reports'
        ));
    }

    /**
     * JSON endpoint to fetch real-time simulated performance metrics.
     */
    public function apiMetrics(): JsonResponse
    {
        $metrics = MonitoringDataGenerator::generateMetrics();
        return response()->json($metrics);
    }

    /**
     * Dispatch multiple random dummy errors to populate database logs.
     */
    public function generateDummy(Request $request)
    {
        $count = $request->input('count', 3);
        
        for ($i = 0; $i < $count; $i++) {
            MonitoringDataGenerator::generateRandomLog();
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Successfully generated {$count} simulated logs.",
                'latest_logs' => MonitoringLog::latest()->take(5)->get()
            ]);
        }

        return redirect()->route('monitoring.index')->with('success', "Dispatched {$count} simulated events successfully.");
    }

    /**
     * Inject a custom simulated log entry into the monitoring database.
     */
    public function storeSimulated(Request $request)
    {
        $validated = $request->validate([
            'level' => 'required|in:critical,warning,info',
            'component' => 'required|string|max:50',
            'message' => 'required|string|max:255',
        ]);

        $log = MonitoringLog::create([
            'level' => $validated['level'],
            'component' => $validated['component'],
            'message' => $validated['message'] . ' [Manual Simulation]',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Custom error simulated successfully.',
                'log' => $log
            ]);
        }

        return redirect()->route('monitoring.index')->with('success', 'Custom error simulated successfully.');
    }
}
