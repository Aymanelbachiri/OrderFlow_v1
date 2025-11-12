<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['client', 'reseller'])->with(['orders']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)->whereNull('suspended_at');
            } elseif ($request->status === 'suspended') {
                $query->where('is_active', false)->orWhereNotNull('suspended_at');
            }
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $clients = $query->latest()->paginate(20);

        return view('admin.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $client = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => isset($validated['password']) && $validated['password']
                ? Hash::make($validated['password'])
                : null,
            'role' => 'client',
            'email_verified_at' => now(),
        ]);

        $client->assignRole('client');

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $client)
    {
        $client->load(['orders.pricingPlan', 'payments']);

        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:client,reseller',
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'boolean',
            'suspension_reason' => 'nullable|string',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'is_active' => $request->boolean('is_active'),
        ];

        // Handle role change
        if ($validated['role'] != $client->role) {
            // Log the role change
            Log::info('Client role changed', [
                'client_id' => $client->id,
                'client_email' => $client->email,
                'old_role' => $client->role,
                'new_role' => $validated['role'],
                'changed_by' => auth()->user()->email,
            ]);
        }

        if ($validated['password']) {
            $updateData['password'] = isset($validated['password']) && $validated['password']
                ? Hash::make($validated['password'])
                : null;
        }

        if (!$request->boolean('is_active')) {
            $updateData['suspended_at'] = now();
            $updateData['suspension_reason'] = $validated['suspension_reason'];
        } else {
            $updateData['suspended_at'] = null;
            $updateData['suspension_reason'] = null;
        }

        $client->update($updateData);

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $client)
    {
        $client->delete();

        return redirect()->route('admin.clients.index')
            ->with('success', 'Client deleted successfully.');
    }

    /**
     * Suspend a client account
     */
    public function suspend(Request $request, User $client)
    {
        $validated = $request->validate([
            'suspension_reason' => 'required|string|max:500',
        ]);

        $client->update([
            'is_active' => false,
            'suspended_at' => now(),
            'suspension_reason' => $validated['suspension_reason'],
        ]);

        return redirect()->back()
            ->with('success', 'Client account suspended successfully.');
    }

    /**
     * Reactivate a client account
     */
    public function reactivate(User $client)
    {
        $client->update([
            'is_active' => true,
            'suspended_at' => null,
            'suspension_reason' => null,
        ]);

        return redirect()->back()
            ->with('success', 'Client account reactivated successfully.');
    }

    /**
     * Toggle client account status
     */
    public function toggleStatus(User $client)
    {
        $client->update([
            'is_active' => !$client->is_active,
        ]);

        $status = $client->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Client account {$status} successfully.");
    }

    /**
     * Verify client email
     */
    public function verifyEmail(User $client)
    {
        $client->update([
            'email_verified_at' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Client email verified successfully.');
    }

    /**
     * Send password reset email to client
     */
    public function sendPasswordReset(User $client)
    {
        // Generate password reset token
        $token = \Illuminate\Support\Str::random(64);

        // Store the token
        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $client->email],
            [
                'email' => $client->email,
                'token' => \Illuminate\Support\Facades\Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Send password reset email
        \Illuminate\Support\Facades\Mail::to($client->email)->send(
            new \App\Mail\PasswordResetMail($client, $token)
        );

        return redirect()->back()
            ->with('success', 'Password reset email sent successfully.');
    }
}
