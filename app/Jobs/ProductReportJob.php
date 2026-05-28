<?php

namespace App\Jobs;

use App\Exports\ProductsExport;
use App\Models\Report;
use App\Notifications\ReportGeneratedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ProductReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $report;

    /**
     * Create a new job instance.
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->report->update(['status' => 'processing']);

            // Compile product report and store to public disk inside reports directory
            Excel::store(new ProductsExport, 'reports/' . $this->report->filename, 'public');

            // Save completed status and accessible file path URL
            $this->report->update([
                'status' => 'completed',
                'file_path' => '/storage/reports/' . $this->report->filename
            ]);

            // Dispatch database notification to user
            $user = $this->report->user;
            if ($user) {
                $user->notify(new ReportGeneratedNotification($this->report));
            }
        } catch (\Exception $e) {
            Log::error('Error generating product report in background job: ' . $e->getMessage());
            $this->report->update(['status' => 'failed']);
        }
    }
}
