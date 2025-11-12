<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SmtpSettingController extends Controller
{
    /**
     * Show the form for editing SMTP settings.
     */
    public function edit()
    {
        $smtpSetting = SmtpSetting::getFirst();
        
        // If no settings exist, create default values
        if (!$smtpSetting) {
            $smtpSetting = new SmtpSetting([
                'mailer' => 'smtp',
                'host' => '',
                'port' => 587,
                'username' => '',
                'password' => '',
                'encryption' => 'tls',
                'from_address' => '',
                'from_name' => '',
            ]);
        }

        return view('admin.smtp.edit', compact('smtpSetting'));
    }

    /**
     * Update the SMTP settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'mailer' => 'required|string|max:255',
            'host' => 'required|string|max:255',
            'port' => 'required|integer|min:1|max:65535',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|max:255',
            'encryption' => 'nullable|string|max:255',
            'from_address' => 'required|email|max:255',
            'from_name' => 'required|string|max:255',
        ]);

        try {
            // Update or create SMTP settings
            $smtpSetting = SmtpSetting::updateOrCreate($validated);

            // Test email configuration if test_email is requested
            if ($request->has('test_email') && $request->filled('test_email')) {
                $this->sendTestEmail($request->test_email, $smtpSetting);
                return redirect()->route('admin.smtp.edit')
                    ->with('success', 'SMTP settings updated and test email sent successfully!');
            }

            return redirect()->route('admin.smtp.edit')
                ->with('success', 'SMTP settings updated successfully!');

        } catch (\Exception $e) {
            Log::error('Failed to update SMTP settings: ' . $e->getMessage());
            return redirect()->route('admin.smtp.edit')
                ->with('error', 'Failed to update SMTP settings: ' . $e->getMessage());
        }
    }

    /**
     * Send a test email to verify SMTP configuration
     */
    private function sendTestEmail(string $email, SmtpSetting $smtpSetting)
    {
        try {
            // Temporarily update config for test email
            config([
                'mail.mailers.smtp.transport' => $smtpSetting->mailer,
                'mail.mailers.smtp.host' => $smtpSetting->host,
                'mail.mailers.smtp.port' => $smtpSetting->port,
                'mail.mailers.smtp.encryption' => $smtpSetting->encryption,
                'mail.mailers.smtp.username' => $smtpSetting->username,
                'mail.mailers.smtp.password' => $smtpSetting->password,
                'mail.from.address' => $smtpSetting->from_address,
                'mail.from.name' => $smtpSetting->from_name,
                'mail.default' => $smtpSetting->mailer,
            ]);

            $subject = 'SMTP Configuration Test - ' . config('app.name');
            $body = "
                <h2>SMTP Test Email</h2>
                <p>This is a test email to verify that your SMTP configuration is working correctly.</p>
                <p><strong>Configuration Details:</strong></p>
                <ul>
                    <li><strong>Mailer:</strong> {$smtpSetting->mailer}</li>
                    <li><strong>Host:</strong> {$smtpSetting->host}</li>
                    <li><strong>Port:</strong> {$smtpSetting->port}</li>
                    <li><strong>Encryption:</strong> {$smtpSetting->encryption}</li>
                    <li><strong>From Address:</strong> {$smtpSetting->from_address}</li>
                    <li><strong>From Name:</strong> {$smtpSetting->from_name}</li>
                </ul>
                <p>If you received this email, your SMTP configuration is working correctly!</p>
                <p>Best regards,<br>" . config('app.name') . " System</p>
            ";

            Mail::html($body, function ($message) use ($email, $subject) {
                $message->to($email)
                       ->subject($subject);
            });

        } catch (\Exception $e) {
            Log::error('Failed to send test email: ' . $e->getMessage());
            throw new \Exception('Failed to send test email: ' . $e->getMessage());
        }
    }
}
