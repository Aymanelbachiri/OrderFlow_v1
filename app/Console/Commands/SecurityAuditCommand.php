<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SecurityService;
use App\Services\PerformanceMonitoringService;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SecurityAuditCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:audit {--fix : Attempt to fix security issues automatically}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run a comprehensive security audit of the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting security audit...');
        $this->newLine();

        $issues = [];
        $fixMode = $this->option('fix');

        // Check for weak passwords
        $this->info('Checking for weak passwords...');
        $weakPasswords = $this->checkWeakPasswords($fixMode);
        if (!empty($weakPasswords)) {
            $issues = array_merge($issues, $weakPasswords);
        }

        // Check for inactive admin accounts
        $this->info('Checking for inactive admin accounts...');
        $inactiveAdmins = $this->checkInactiveAdmins($fixMode);
        if (!empty($inactiveAdmins)) {
            $issues = array_merge($issues, $inactiveAdmins);
        }

        // Check file permissions
        $this->info('Checking file permissions...');
        $permissionIssues = $this->checkFilePermissions($fixMode);
        if (!empty($permissionIssues)) {
            $issues = array_merge($issues, $permissionIssues);
        }

        // Check environment configuration
        $this->info('Checking environment configuration...');
        $envIssues = $this->checkEnvironmentConfig();
        if (!empty($envIssues)) {
            $issues = array_merge($issues, $envIssues);
        }

        // Check for outdated dependencies
        $this->info('Checking for security updates...');
        $dependencyIssues = $this->checkDependencies();
        if (!empty($dependencyIssues)) {
            $issues = array_merge($issues, $dependencyIssues);
        }

        // Generate report
        $this->newLine();
        $this->displayAuditResults($issues);

        // Log security audit
        SecurityService::logSecurityEvent('security_audit_completed', [
            'issues_found' => count($issues),
            'fix_mode' => $fixMode,
        ]);

        return empty($issues) ? 0 : 1;
    }

    /**
     * Check for users with weak passwords
     */
    private function checkWeakPasswords(bool $fix = false): array
    {
        $issues = [];
        
        // Check for default passwords (this is a simplified check)
        $users = User::where('role', 'admin')->get();
        
        foreach ($users as $user) {
            // Check if password might be weak (this is a basic check)
            if (Hash::check('password', $user->password) || 
                Hash::check('admin', $user->password) || 
                Hash::check('123456', $user->password)) {
                
                $issues[] = "User '{$user->email}' has a weak/default password";
                
                if ($fix) {
                    // Generate a secure temporary password
                    $tempPassword = SecurityService::generateSecureToken(12);
                    $user->update(['password' => Hash::make($tempPassword)]);
                    $this->warn("Generated new password for {$user->email}: {$tempPassword}");
                }
            }
        }

        return $issues;
    }

    /**
     * Check for inactive admin accounts
     */
    private function checkInactiveAdmins(bool $fix = false): array
    {
        $issues = [];
        
        $inactiveAdmins = User::where('role', 'admin')
            ->where('is_active', true)
            ->where(function($query) {
                $query->whereNull('last_login_at')
                      ->orWhere('last_login_at', '<', now()->subMonths(6));
            })
            ->get();

        foreach ($inactiveAdmins as $admin) {
            $lastLogin = $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'never';
            $issues[] = "Admin account '{$admin->email}' hasn't logged in for a long time (last login: {$lastLogin})";
            
            if ($fix) {
                $admin->update(['is_active' => false]);
                $this->warn("Deactivated inactive admin account: {$admin->email}");
            }
        }

        return $issues;
    }

    /**
     * Check file permissions
     */
    private function checkFilePermissions(bool $fix = false): array
    {
        $issues = [];
        
        $criticalPaths = [
            '.env' => '600',
            'storage' => '755',
            'bootstrap/cache' => '755',
        ];

        foreach ($criticalPaths as $path => $expectedPerms) {
            $fullPath = base_path($path);
            
            if (file_exists($fullPath)) {
                $currentPerms = substr(sprintf('%o', fileperms($fullPath)), -3);
                
                if ($currentPerms !== $expectedPerms) {
                    $issues[] = "File/directory '{$path}' has permissions {$currentPerms}, should be {$expectedPerms}";
                    
                    if ($fix) {
                        chmod($fullPath, octdec($expectedPerms));
                        $this->info("Fixed permissions for {$path}");
                    }
                }
            }
        }

        return $issues;
    }

    /**
     * Check environment configuration
     */
    private function checkEnvironmentConfig(): array
    {
        $issues = [];

        // Check if app is in debug mode in production
        if (config('app.env') === 'production' && config('app.debug') === true) {
            $issues[] = 'Application is in debug mode in production environment';
        }

        // Check if app key is set
        if (empty(config('app.key'))) {
            $issues[] = 'Application key is not set';
        }

        // Check HTTPS configuration
        if (config('app.env') === 'production' && !config('app.url') || !str_starts_with(config('app.url'), 'https://')) {
            $issues[] = 'Application URL should use HTTPS in production';
        }

        // Check session configuration
        if (config('session.secure') === false && config('app.env') === 'production') {
            $issues[] = 'Session cookies should be secure in production';
        }

        // Check database configuration
        if (config('database.default') === 'sqlite' && config('app.env') === 'production') {
            $issues[] = 'SQLite should not be used in production';
        }

        return $issues;
    }

    /**
     * Check for outdated dependencies
     */
    private function checkDependencies(): array
    {
        $issues = [];

        // Check PHP version
        if (version_compare(PHP_VERSION, '8.2.0', '<')) {
            $issues[] = 'PHP version is outdated. Current: ' . PHP_VERSION . ', Recommended: 8.2+';
        }

        // Check Laravel version (basic check)
        $laravelVersion = app()->version();
        if (version_compare($laravelVersion, '11.0', '<')) {
            $issues[] = 'Laravel version might be outdated. Current: ' . $laravelVersion;
        }

        return $issues;
    }

    /**
     * Display audit results
     */
    private function displayAuditResults(array $issues): void
    {
        if (empty($issues)) {
            $this->info('✅ Security audit completed successfully. No issues found.');
            return;
        }

        $this->error('❌ Security audit found ' . count($issues) . ' issue(s):');
        $this->newLine();

        foreach ($issues as $index => $issue) {
            $this->line(($index + 1) . '. ' . $issue);
        }

        $this->newLine();
        $this->warn('Run with --fix option to attempt automatic fixes where possible.');
        
        // System health check
        $this->newLine();
        $this->info('System Health Check:');
        $health = PerformanceMonitoringService::checkSystemHealth();
        
        $statusColor = match($health['status']) {
            'healthy' => 'info',
            'warning' => 'warn',
            'critical' => 'error',
        };
        
        $this->{$statusColor}("Status: {$health['status']} (Score: {$health['score']}/100)");
        
        if (!empty($health['issues'])) {
            foreach ($health['issues'] as $issue) {
                $this->line("  - {$issue}");
            }
        }
    }
}
