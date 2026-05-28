<?php

namespace App\Services;

use App\Models\MonitoringLog;
use Carbon\Carbon;

class MonitoringDataGenerator
{
    /**
     * Generate a list of highly realistic dummy error logs.
     */
    public static function getDummyLogs(): array
    {
        return [
            [
                'level' => 'critical',
                'component' => 'Database',
                'message' => 'PDOException: Connection refused (SQLSTATE[HY000] [2002]) in Connection.php:75. Failed to establish connection to primary db host.',
            ],
            [
                'level' => 'critical',
                'component' => 'System',
                'message' => 'Fatal error: Allowed memory size of 268435456 bytes exhausted (tried to allocate 32768 bytes) in ImageProcessor.php:124.',
            ],
            [
                'level' => 'critical',
                'component' => 'Security',
                'message' => 'BruteForceAlert: Brute force login attempt detected on admin account from IP 198.51.100.42. Account locked for 15 minutes.',
            ],
            [
                'level' => 'warning',
                'component' => 'Storage',
                'message' => 'DiskSpaceWarning: Storage disk /dev/sda1 capacity is at 88%. Threshold is 85%. Cleanup recommended.',
            ],
            [
                'level' => 'warning',
                'component' => 'API',
                'message' => 'MailgunApiException: API response time exceeded 5000ms in MailServiceProvider.php:82. Email notifications delayed.',
            ],
            [
                'level' => 'warning',
                'component' => 'Cache',
                'message' => 'RedisConnectionException: Lost connection to Redis server, falling back to database cache driver dynamically.',
            ],
            [
                'level' => 'info',
                'component' => 'Backup',
                'message' => 'BackupManager: Daily SQLite database backup completed successfully in 1.45 seconds. Saved as backup_2026-05-28.zip.',
            ],
            [
                'level' => 'info',
                'component' => 'Cache',
                'message' => 'CacheStore: Application cache key prefix "inventory_prod" flushed by admin@inventory-system.com.',
            ],
            [
                'level' => 'info',
                'component' => 'Auth',
                'message' => 'OAuthService: Google API credentials token refreshed successfully. Next refresh in 3600 seconds.',
            ],
            [
                'level' => 'info',
                'component' => 'Queue',
                'message' => 'QueueWorker: LowStockNotificationJob executed successfully in the background. 4 notifications dispatched to staff.',
            ],
        ];
    }

    /**
     * Seeds the monitoring logs table with dummy data if empty.
     */
    public static function seedLogsIfEmpty(int $count = 8): void
    {
        if (MonitoringLog::count() > 0) {
            return;
        }

        $dummies = self::getDummyLogs();
        shuffle($dummies);

        for ($i = 0; $i < min($count, count($dummies)); $i++) {
            MonitoringLog::create([
                'level' => $dummies[$i]['level'],
                'component' => $dummies[$i]['component'],
                'message' => $dummies[$i]['message'],
                'created_at' => Carbon::now()->subMinutes(rand(1, 60)),
            ]);
        }
    }

    /**
     * Generate a single random error log.
     */
    public static function generateRandomLog(): MonitoringLog
    {
        $dummies = self::getDummyLogs();
        $random = $dummies[array_rand($dummies)];
        
        return MonitoringLog::create([
            'level' => $random['level'],
            'component' => $random['component'],
            'message' => $random['message'] . ' [Simulated Event]',
        ]);
    }

    /**
     * Generate a random metrics packet (simulating CPU, RAM, Response time).
     */
    public static function generateMetrics(): array
    {
        return [
            'cpu' => rand(15, 85), // CPU usage percentage
            'memory' => rand(48, 92), // Memory usage percentage
            'response_time' => rand(38, 420), // response time in ms
            'timestamp' => Carbon::now()->format('H:i:s'),
        ];
    }

    /**
     * Generate historical metrics points (e.g., for initial Chart.js load).
     */
    public static function generateHistory(int $points = 12): array
    {
        $history = [];
        $time = Carbon::now()->subSeconds($points * 5);

        for ($i = 0; $i < $points; $i++) {
            $history[] = [
                'cpu' => rand(20, 60),
                'memory' => rand(50, 75),
                'response_time' => rand(50, 250),
                'timestamp' => $time->addSeconds(5)->format('H:i:s'),
            ];
        }

        return $history;
    }
}
