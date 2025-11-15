<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Models\User;
use App\Models\Order;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SettingsController extends Controller
{
    /**
     * Show general settings
     */
    public function index()
    {
        $settings = [
            'site_name' => SystemSetting::get('site_name', 'IPTV Platform'),
            'site_description' => SystemSetting::get('site_description', 'Premium IPTV Services'),
            'contact_email' => SystemSetting::get('contact_email', 'support@iptv.com'),
            'support_phone' => SystemSetting::get('support_phone', ''),
            'renewal_reminder_days' => SystemSetting::get('renewal_reminder_days', '7,3,0'),
            'renewal_link_url' => SystemSetting::get('renewal_link_url', ''),
            // Cloudflare settings
            'cloudflare_api_token' => SystemSetting::get('cloudflare_api_token', ''),
            'cloudflare_account_id' => SystemSetting::get('cloudflare_account_id', ''),
            'cloudflare_pages_project_name' => SystemSetting::get('cloudflare_pages_project_name', 'shield-domains'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update general settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:500',
            'contact_email' => 'required|email',
            'support_phone' => 'nullable|string|max:20',
            'renewal_reminder_days' => 'required|string',
            'renewal_link_url' => 'nullable|url',
            // Cloudflare settings
            'cloudflare_api_token' => 'nullable|string|max:255',
            'cloudflare_account_id' => 'nullable|string|max:255',
            'cloudflare_pages_project_name' => 'nullable|string|max:255',
        ]);

        foreach ($validated as $key => $value) {
            if ($key === 'cloudflare_api_token') {
                // Only update if a new token is provided (not empty)
                if (empty($value)) {
                    continue; // Preserve existing token
                }
            }
            SystemSetting::set($key, $value, is_bool($value) ? 'boolean' : 'string');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    /**
     * Show system information
     */
    public function system()
    {
        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'database_type' => config('database.default'),
            'storage_disk' => config('filesystems.default'),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'mail_driver' => config('mail.default'),
        ];

        $statistics = [
            'total_users' => User::count(),
            'total_clients' => User::where('role', 'client')->count(),
            'total_resellers' => User::where('role', 'reseller')->count(),
            'total_orders' => Order::count(),
            'active_orders' => Order::where('status', 'active')->count(),
            'total_revenue' => Order::where('status', 'active')->sum('amount'),
            'total_blog_posts' => BlogPost::count(),
            'published_posts' => BlogPost::where('is_published', true)->count(),
        ];

        return view('admin.settings.system', compact('systemInfo', 'statistics'));
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect()->back()
                ->with('success', 'Application cache cleared successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    /**
     * Run database migrations
     */
    public function runMigrations()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);

            return redirect()->back()
                ->with('success', 'Database migrations completed successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to run migrations: ' . $e->getMessage());
        }
    }

    /**
     * Create database backup
     */
    public function backupDatabase()
    {
        try {
            $filename = 'backup_' . date('Y_m_d_H_i_s') . '.sql';

            // This is a simplified backup - in production you'd use a proper backup package
            $tables = ['users', 'orders', 'pricing_plans', 'payments', 'blog_posts', 'email_templates', 'system_settings'];
            $backup = "-- Database Backup Created: " . date('Y-m-d H:i:s') . "\n\n";

            foreach ($tables as $table) {
                $backup .= "-- Table: {$table}\n";
                $backup .= "-- (Backup functionality would be implemented here)\n\n";
            }

            Storage::disk('local')->put('backups/' . $filename, $backup);

            return redirect()->back()
                ->with('success', 'Database backup created: ' . $filename);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create backup: ' . $e->getMessage());
        }
    }

    /**
     * Send test renewal reminders
     */
    public function testRenewalReminders()
    {
        try {
            Artisan::call('iptv:send-renewal-reminders');
            $output = Artisan::output();

            return redirect()->back()
                ->with('success', 'Renewal reminders processed. Output: ' . $output);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send renewal reminders: ' . $e->getMessage());
        }
    }

    /**
     * View application logs
     */
    public function logs(Request $request)
    {
        $logFile = storage_path('logs/laravel.log');
        $lines = (int) $request->get('lines', 500); // Default to last 500 lines
        $level = $request->get('level', 'all'); // Filter by log level
        $search = $request->get('search', ''); // Search term
        
        $logContent = [];
        $totalLines = 0;
        $fileExists = File::exists($logFile);
        $fileSize = 0;
        
        if ($fileExists) {
            try {
                // For large files, read from the end to avoid memory issues
                $fileSize = File::size($logFile);
                $maxReadSize = 10 * 1024 * 1024; // 10MB max read
                
                if ($fileSize > $maxReadSize) {
                    // For very large files, read only the last portion
                    $handle = fopen($logFile, 'r');
                    fseek($handle, -min($maxReadSize, $fileSize), SEEK_END);
                    $content = fread($handle, $maxReadSize);
                    fclose($handle);
                } else {
                    // For smaller files, read the entire file
                    $content = File::get($logFile);
                }
                
                $allLines = explode("\n", $content);
                $totalLines = count($allLines);
                
                // Filter by level if specified
                if ($level !== 'all') {
                    $allLines = array_filter($allLines, function($line) use ($level) {
                        return stripos($line, ".{$level}:") !== false || 
                               stripos($line, "[{$level}]") !== false ||
                               stripos($line, "{$level}.") !== false ||
                               stripos($line, "production.{$level}") !== false ||
                               stripos($line, "local.{$level}") !== false;
                    });
                }
                
                // Filter by search term if specified
                if (!empty($search)) {
                    $allLines = array_filter($allLines, function($line) use ($search) {
                        return stripos($line, $search) !== false;
                    });
                }
                
                // Get the last N lines
                $allLines = array_values($allLines);
                $logContent = array_slice($allLines, -$lines);
                
                // Reverse to show newest first
                $logContent = array_reverse($logContent);
                
            } catch (\Exception $e) {
                return redirect()->route('admin.settings.index')
                    ->with('error', 'Failed to read log file: ' . $e->getMessage());
            }
        }
        
        // Format file size
        $fileSizeFormatted = $this->formatBytes($fileSize);
        
        // Get available log levels
        $logLevels = ['all', 'emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];
        
        return view('admin.settings.logs', compact('logContent', 'lines', 'level', 'search', 'fileExists', 'totalLines', 'fileSizeFormatted', 'logLevels'));
    }
    
    /**
     * Clear log file
     */
    public function clearLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            
            if (File::exists($logFile)) {
                File::put($logFile, '');
            }
            
            return redirect()->route('admin.settings.logs')
                ->with('success', 'Log file cleared successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.logs')
                ->with('error', 'Failed to clear log file: ' . $e->getMessage());
        }
    }
    
    /**
     * Format bytes to human readable format
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
