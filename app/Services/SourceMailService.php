<?php

namespace App\Services;

use App\Models\Source;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class SourceMailService
{
    /**
     * Get source by name or from order
     */
    public function getSource(?string $sourceName = null, ?Order $order = null): ?Source
    {
        if ($order && $order->source) {
            return Source::where('name', $order->source)->first();
        }

        if ($sourceName) {
            return Source::where('name', $sourceName)->first();
        }

        return null;
    }

    /**
     * Configure mail settings for a specific source
     * Returns the mailer name to use, or null to use default
     */
    public function configureMailForSource(?Source $source): ?string
    {
        if (!$source) {
            return null;
        }

        // Only configure if source has SMTP settings
        if (!$source->smtp_host || !$source->smtp_from_address) {
            return null;
        }

        try {
            $mailerName = 'source_' . $source->id;
            
            // Create a custom mailer for this source
            Config::set("mail.mailers.{$mailerName}", [
                'transport' => $source->smtp_mailer ?? 'smtp',
                'host' => $source->smtp_host,
                'port' => $source->smtp_port ?? 587,
                'encryption' => $source->smtp_encryption ?? 'tls',
                'username' => $source->smtp_username,
                'password' => $source->smtp_password,
                'timeout' => env('MAIL_TIMEOUT', 60),
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ]);

            return $mailerName;
        } catch (\Exception $e) {
            Log::error('Failed to configure mail for source: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get email template variables from source
     */
    public function getEmailVariables(?Source $source): array
    {
        if (!$source) {
            return [
                'company_name' => config('app.name'),
                'contact_email' => config('mail.from.address', ''),
                'website' => config('app.url', ''),
                'phone_number' => '',
                'team_name' => config('app.name') . ' Team',
            ];
        }

        return [
            'company_name' => $source->company_name ?? config('app.name'),
            'contact_email' => $source->contact_email ?? $source->smtp_from_address ?? config('mail.from.address', ''),
            'website' => $source->website ?? config('app.url', ''),
            'phone_number' => $source->phone_number ?? '',
            'team_name' => $source->team_name ?? ($source->company_name ?? config('app.name')) . ' Team',
        ];
    }

    /**
     * Send email using source-specific configuration
     */
    public function sendEmailWithSource(
        string $to,
        string $subject,
        string $view,
        array $data = [],
        ?string $name = null,
        ?Source $source = null
    ): void {
        // Configure mail for source
        $this->configureMailForSource($source);

        // Merge source email variables into data
        $emailVariables = $this->getEmailVariables($source);
        $data = array_merge($emailVariables, $data);

        try {
            Mail::send($view, $data, function ($message) use ($to, $subject, $name, $source) {
                $message->to($to, $name)->subject($subject);
                
                // Set from address if source is configured
                if ($source && $source->smtp_from_address) {
                    $message->from(
                        $source->smtp_from_address,
                        $source->smtp_from_name ?? $source->company_name ?? config('app.name')
                    );
                }
            });
        } catch (\Exception $e) {
            Log::error("Failed to send email to {$to} using source configuration: " . $e->getMessage());
        }
    }

    /**
     * Send email using order's source
     */
    public function sendEmailWithOrder(
        Order $order,
        string $to,
        string $subject,
        string $view,
        array $data = [],
        ?string $name = null
    ): void {
        $source = $this->getSource(null, $order);
        $this->sendEmailWithSource($to, $subject, $view, $data, $name, $source);
    }

    /**
     * Replace variables in email content
     * Supports source variables, order variables, and client variables
     */
    public function replaceEmailVariables(string $content, ?Order $order = null, ?Source $source = null): string
    {
        $source = $source ?? ($order ? $this->getSource(null, $order) : null);
        $sourceVars = $this->getEmailVariables($source);
        
        // Get order variables
        $orderVars = [];
        if ($order) {
            $orderVars = [
                'order_number' => $order->order_number,
                'order_id' => $order->id,
                'order_amount' => number_format((float)($order->amount ?? 0), 2),
                'order_date' => $order->created_at->format('M d, Y'),
                'order_status' => ucfirst($order->status),
                'payment_method' => ucfirst(str_replace('_', ' ', $order->payment_method ?? '')),
            ];
            
            // Add product-specific variables
            if ($order->customProduct) {
                $orderVars['product_name'] = $order->customProduct->name;
                $orderVars['product_type'] = ucfirst($order->customProduct->product_type);
                $orderVars['product_price'] = number_format((float)($order->customProduct->price ?? 0), 2);
            }
            
            if ($order->pricingPlan) {
                $orderVars['plan_name'] = $order->pricingPlan->display_name;
                $orderVars['plan_duration'] = $order->pricingPlan->duration . ' month(s)';
                $orderVars['device_count'] = $order->pricingPlan->device_count;
            }
        }
        
        // Get client variables
        $clientVars = [];
        if ($order && $order->user) {
            $clientVars = [
                'customer_name' => $order->user->name,
                'customer_email' => $order->user->email,
                'customer_phone' => $order->user->phone ?? '',
            ];
        }
        
        // Merge all variables
        $allVars = array_merge($sourceVars, $orderVars, $clientVars);
        
        // Replace variables in format {{variable_name}}
        foreach ($allVars as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value ?? '', $content);
            $content = str_replace('{{ ' . $key . ' }}', $value ?? '', $content);
        }
        
        return $content;
    }

    /**
     * Send custom composed email using source SMTP
     */
    public function sendCustomComposedEmail(
        Order $order,
        string $subject,
        string $htmlContent,
        bool $includeFooter = true
    ): bool {
        try {
            $source = $this->getSource(null, $order);
            $sourceVars = $this->getEmailVariables($source);
            
            // Replace variables in subject and content
            $subject = $this->replaceEmailVariables($subject, $order, $source);
            $htmlContent = $this->replaceEmailVariables($htmlContent, $order, $source);
            
            // Convert plain text to HTML if needed (preserve line breaks)
            if (!preg_match('/<[^>]+>/', $htmlContent)) {
                // It's plain text, convert to HTML
                $htmlContent = nl2br(htmlspecialchars($htmlContent));
            }
            
            // Wrap content in proper HTML email structure
            $htmlContent = $this->wrapEmailContent($htmlContent, $sourceVars, $includeFooter);
            
            // Configure mailer
            $mailerName = $this->configureMailForSource($source);
            
            // Send email
            if ($mailerName) {
                Mail::mailer($mailerName)->to($order->user->email)->send(
                    new \App\Mail\CustomComposedMail(
                        $order,
                        $subject,
                        $htmlContent,
                        $source
                    )
                );
            } else {
                Mail::to($order->user->email)->send(
                    new \App\Mail\CustomComposedMail(
                        $order,
                        $subject,
                        $htmlContent,
                        $source
                    )
                );
            }
            
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send custom composed email: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * Wrap email content in proper HTML structure
     */
    protected function wrapEmailContent(string $content, array $sourceVars, bool $includeFooter): string
    {
        $companyName = $sourceVars['company_name'] ?? config('app.name');
        
        $html = '<!DOCTYPE html>';
        $html .= '<html lang="en">';
        $html .= '<head>';
        $html .= '<meta charset="UTF-8">';
        $html .= '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $html .= '<title>Order Update</title>';
        $html .= '</head>';
        $html .= '<body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: Arial, sans-serif;">';
        $html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f4; padding: 20px 0;">';
        $html .= '<tr><td align="center">';
        $html .= '<table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">';
        
        // Header
        $html .= '<tr>';
        $html .= '<td style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 30px; text-align: center;">';
        $html .= '<h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold;">Order Update</h1>';
        $html .= '<p style="margin: 10px 0 0 0; color: #ffffff; font-size: 14px; opacity: 0.9;">' . htmlspecialchars($companyName) . '</p>';
        $html .= '</td>';
        $html .= '</tr>';
        
        // Content
        $html .= '<tr>';
        $html .= '<td style="padding: 30px;">';
        $html .= '<div style="color: #333333; font-size: 16px; line-height: 1.6;">';
        $html .= $content;
        $html .= '</div>';
        $html .= '</td>';
        $html .= '</tr>';
        
        // Footer
        if ($includeFooter) {
            $html .= $this->generateEmailFooter($sourceVars);
        }
        
        $html .= '</table>';
        $html .= '</td></tr>';
        $html .= '</table>';
        $html .= '</body>';
        $html .= '</html>';
        
        return $html;
    }

    /**
     * Generate email footer with source variables
     */
    protected function generateEmailFooter(array $sourceVars): string
    {
        $companyName = $sourceVars['company_name'] ?? config('app.name');
        $contactEmail = $sourceVars['contact_email'] ?? '';
        $website = $sourceVars['website'] ?? '';
        $phoneNumber = $sourceVars['phone_number'] ?? '';
        
        $footer = '<tr>';
        $footer .= '<td style="padding: 20px 30px; text-align: center; color: #999999; font-size: 12px; line-height: 1.5; border-top: 1px solid #e0e0e0;">';
        $footer .= '<p style="margin: 0 0 5px 0;">© ' . date('Y') . ' ' . htmlspecialchars($companyName) . '. All rights reserved.</p>';
        
        if ($website) {
            $footer .= '<p style="margin: 5px 0;"><a href="' . htmlspecialchars($website) . '" style="color: #999999; text-decoration: none;">' . htmlspecialchars($website) . '</a></p>';
        }
        
        if ($contactEmail) {
            $footer .= '<p style="margin: 5px 0;">Contact: <a href="mailto:' . htmlspecialchars($contactEmail) . '" style="color: #999999; text-decoration: none;">' . htmlspecialchars($contactEmail) . '</a></p>';
        }
        
        if ($phoneNumber) {
            $footer .= '<p style="margin: 5px 0;">Phone: ' . htmlspecialchars($phoneNumber) . '</p>';
        }
        
        $footer .= '<p style="margin: 5px 0 0 0;">This is an automated email. Please do not reply to this message.</p>';
        $footer .= '</td>';
        $footer .= '</tr>';
        
        return $footer;
    }
}

