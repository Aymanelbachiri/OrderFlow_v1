<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrialRequest;
use App\Models\Source;
use App\Mail\TrialCredentialsMail;
use App\Services\SourceMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TrialRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = TrialRequest::query()->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('request_id', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        $trialRequests = $query->paginate(20)->withQueryString();

        $stats = [
            'total' => TrialRequest::count(),
            'pending' => TrialRequest::where('status', 'pending')->count(),
            'approved' => TrialRequest::where('status', 'approved')->count(),
            'rejected' => TrialRequest::where('status', 'rejected')->count(),
        ];

        // Get sources for the approval modal
        $sources = Source::where('is_active', true)->get();

        return view('admin.trial-requests.index', compact('trialRequests', 'stats', 'sources'));
    }

    public function show(TrialRequest $trialRequest)
    {
        $sources = Source::where('is_active', true)->get();
        return view('admin.trial-requests.show', compact('trialRequest', 'sources'));
    }

    public function approve(Request $request, TrialRequest $trialRequest)
    {
        $validated = $request->validate([
            'trial_username' => 'required|string|max:255',
            'trial_password' => 'required|string|max:255',
            'trial_url' => 'required|url|max:2048',
            'smtp_source' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        // Find the source for SMTP
        $source = Source::where('name', $validated['smtp_source'])->first();
        
        if (!$source) {
            return back()->with('error', 'Selected source not found.');
        }

        if (empty($source->smtp_host) || empty($source->smtp_from_address)) {
            return back()->with('error', 'SMTP is not configured for the selected source.');
        }

        // Calculate trial expiry based on trial_duration
        $trialExpiresAt = $this->calculateTrialExpiry($trialRequest->trial_duration);

        // Update trial request
        $trialRequest->update([
            'status' => 'approved',
            'trial_username' => $validated['trial_username'],
            'trial_password' => $validated['trial_password'],
            'trial_url' => $validated['trial_url'],
            'trial_expires_at' => $trialExpiresAt,
            'notes' => $validated['notes'],
            'processed_at' => now(),
            'processed_by' => auth()->user()->name ?? 'Admin',
        ]);

        // Send email with credentials using template
        try {
            $sourceMailService = new SourceMailService();
            $mailerName = $sourceMailService->configureMailForSource($source);

            if ($mailerName) {
                Mail::mailer($mailerName)->to($trialRequest->email)->send(
                    new TrialCredentialsMail($trialRequest, $source)
                );
            } else {
                Mail::to($trialRequest->email)->send(
                    new TrialCredentialsMail($trialRequest, $source)
                );
            }
            
            $trialRequest->update([
                'credentials_sent' => true,
                'credentials_sent_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Trial approved and credentials sent to ' . $trialRequest->email);
        } catch (\Exception $e) {
            \Log::error('Trial credentials email failed', [
                'trial_id' => $trialRequest->id,
                'email' => $trialRequest->email,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Trial approved but failed to send email: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, TrialRequest $trialRequest)
    {
        $trialRequest->update([
            'status' => 'rejected',
            'notes' => $request->notes,
            'processed_at' => now(),
            'processed_by' => auth()->user()->name ?? 'Admin',
        ]);

        return redirect()->back()->with('success', 'Trial request rejected.');
    }

    public function destroy(TrialRequest $trialRequest)
    {
        $trialRequest->delete();
        return redirect()->route('admin.trial-requests.index')->with('success', 'Trial request deleted.');
    }

    /**
     * Calculate trial expiry based on duration string
     */
    private function calculateTrialExpiry(?string $duration): ?\DateTime
    {
        if (!$duration) {
            return now()->addHours(24);
        }

        $duration = strtolower($duration);

        // Parse common formats
        if (preg_match('/(\d+)\s*hour/i', $duration, $matches)) {
            return now()->addHours((int) $matches[1]);
        }

        if (preg_match('/(\d+)\s*day/i', $duration, $matches)) {
            return now()->addDays((int) $matches[1]);
        }

        if (preg_match('/(\d+)\s*week/i', $duration, $matches)) {
            return now()->addWeeks((int) $matches[1]);
        }

        // Default to 24 hours
        return now()->addHours(24);
    }
}
