<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use App\Models\User;

class SecurityService
{
    /**
     * Check if password meets security requirements
     */
    public static function isPasswordSecure(string $password): array
    {
        $issues = [];
        $score = 0;

        // Length check
        if (strlen($password) >= 8) {
            $score += 20;
        } else {
            $issues[] = 'Password must be at least 8 characters long';
        }

        // Uppercase check
        if (preg_match('/[A-Z]/', $password)) {
            $score += 20;
        } else {
            $issues[] = 'Password must contain at least one uppercase letter';
        }

        // Lowercase check
        if (preg_match('/[a-z]/', $password)) {
            $score += 20;
        } else {
            $issues[] = 'Password must contain at least one lowercase letter';
        }

        // Number check
        if (preg_match('/[0-9]/', $password)) {
            $score += 20;
        } else {
            $issues[] = 'Password must contain at least one number';
        }

        // Special character check
        if (preg_match('/[^A-Za-z0-9]/', $password)) {
            $score += 20;
        } else {
            $issues[] = 'Password must contain at least one special character';
        }

        // Common password check
        $commonPasswords = [
            'password', '123456', '123456789', 'qwerty', 'abc123',
            'password123', 'admin', 'letmein', 'welcome', 'monkey'
        ];

        if (in_array(strtolower($password), $commonPasswords)) {
            $score -= 30;
            $issues[] = 'Password is too common';
        }

        return [
            'score' => max(0, $score),
            'issues' => $issues,
            'is_secure' => $score >= 80 && empty($issues),
        ];
    }

    /**
     * Log security event
     */
    public static function logSecurityEvent(string $event, array $data = [], string $level = 'info'): void
    {
        $logData = array_merge([
            'event' => $event,
            'timestamp' => now()->toISOString(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id(),
        ], $data);

        Log::channel('security')->{$level}($event, $logData);
    }

    /**
     * Check for suspicious login attempts
     */
    public static function checkSuspiciousLogin(Request $request, User $user = null): bool
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        
        // Check for too many failed attempts from this IP
        $failedAttempts = Cache::get("failed_login_attempts:{$ip}", 0);
        if ($failedAttempts >= 5) {
            self::logSecurityEvent('suspicious_login_blocked', [
                'reason' => 'too_many_failed_attempts',
                'failed_attempts' => $failedAttempts,
            ], 'warning');
            return true;
        }

        // Check for login from new location (if user exists)
        if ($user) {
            $lastLoginIp = Cache::get("user_last_ip:{$user->id}");
            if ($lastLoginIp && $lastLoginIp !== $ip) {
                self::logSecurityEvent('login_from_new_ip', [
                    'user_id' => $user->id,
                    'previous_ip' => $lastLoginIp,
                    'new_ip' => $ip,
                ], 'info');
            }
        }

        return false;
    }

    /**
     * Record failed login attempt
     */
    public static function recordFailedLogin(Request $request, string $email): void
    {
        $ip = $request->ip();
        $key = "failed_login_attempts:{$ip}";
        
        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, now()->addMinutes(15));

        self::logSecurityEvent('failed_login_attempt', [
            'email' => $email,
            'attempts' => $attempts,
        ], 'warning');

        // Block IP after 10 failed attempts
        if ($attempts >= 10) {
            Cache::put("blocked_ip:{$ip}", true, now()->addHours(1));
            self::logSecurityEvent('ip_blocked', [
                'reason' => 'too_many_failed_logins',
                'attempts' => $attempts,
            ], 'error');
        }
    }

    /**
     * Record successful login
     */
    public static function recordSuccessfulLogin(Request $request, User $user): void
    {
        $ip = $request->ip();
        
        // Clear failed attempts
        Cache::forget("failed_login_attempts:{$ip}");
        
        // Store last login IP
        Cache::put("user_last_ip:{$user->id}", $ip, now()->addDays(30));
        
        // Update user's last login
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
        ]);

        self::logSecurityEvent('successful_login', [
            'user_id' => $user->id,
        ]);
    }

    /**
     * Check if IP is blocked
     */
    public static function isIpBlocked(string $ip): bool
    {
        return Cache::has("blocked_ip:{$ip}");
    }

    /**
     * Sanitize user input
     */
    public static function sanitizeInput(string $input): string
    {
        // Remove potentially dangerous characters
        $input = strip_tags($input);
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        
        // Remove SQL injection patterns
        $sqlPatterns = [
            '/(\s*(union|select|insert|update|delete|drop|create|alter|exec|execute)\s+)/i',
            '/(\s*(or|and)\s+\d+\s*=\s*\d+)/i',
            '/(\s*;\s*)/i',
        ];
        
        foreach ($sqlPatterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }
        
        return trim($input);
    }

    /**
     * Generate secure random token
     */
    public static function generateSecureToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Validate file upload security
     */
    public static function validateFileUpload($file): array
    {
        $issues = [];
        
        if (!$file) {
            $issues[] = 'No file provided';
            return ['valid' => false, 'issues' => $issues];
        }

        // Check file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            $issues[] = 'File size exceeds 10MB limit';
        }

        // Check file extension
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!in_array($extension, $allowedExtensions)) {
            $issues[] = 'File type not allowed';
        }

        // Check MIME type
        $allowedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif',
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        
        if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
            $issues[] = 'Invalid file type';
        }

        // Check for executable files
        $dangerousExtensions = ['exe', 'bat', 'cmd', 'com', 'pif', 'scr', 'vbs', 'js', 'jar', 'php', 'asp'];
        if (in_array($extension, $dangerousExtensions)) {
            $issues[] = 'Executable files are not allowed';
        }

        return [
            'valid' => empty($issues),
            'issues' => $issues,
        ];
    }

    /**
     * Check for XSS attempts
     */
    public static function detectXSS(string $input): bool
    {
        $xssPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/on\w+\s*=/i',
            '/<iframe/i',
            '/<object/i',
            '/<embed/i',
            '/<link/i',
            '/<meta/i',
        ];

        foreach ($xssPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                self::logSecurityEvent('xss_attempt_detected', [
                    'input' => substr($input, 0, 200),
                    'pattern' => $pattern,
                ], 'warning');
                return true;
            }
        }

        return false;
    }

    /**
     * Encrypt sensitive data
     */
    public static function encryptSensitiveData(string $data): string
    {
        return encrypt($data);
    }

    /**
     * Decrypt sensitive data
     */
    public static function decryptSensitiveData(string $encryptedData): string
    {
        try {
            return decrypt($encryptedData);
        } catch (\Exception $e) {
            self::logSecurityEvent('decryption_failed', [
                'error' => $e->getMessage(),
            ], 'error');
            throw $e;
        }
    }
}
