<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Source;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'agent')->with('assignedSources');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)->whereNull('suspended_at');
            } elseif ($request->status === 'suspended') {
                $query->where(function ($q) {
                    $q->where('is_active', false)->orWhereNotNull('suspended_at');
                });
            }
        }

        $agents = $query->latest()->paginate(20);

        return view('admin.agents.index', compact('agents'));
    }

    public function create()
    {
        $sources = Source::where('is_active', true)->orderBy('name')->get();
        return view('admin.agents.create', compact('sources'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', function ($attribute, $value, $fail) {
                $exists = User::whereRaw('LOWER(email) = ?', [strtolower($value)])->exists();
                if ($exists) {
                    $fail('The email has already been taken.');
                }
            }],
            'password' => ['required', 'confirmed', Password::min(8)],
            'sources' => 'required|array|min:1',
            'sources.*' => 'exists:sources,id',
        ]);

        $agent = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'agent',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $agent->assignedSources()->sync($validated['sources']);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent created successfully.');
    }

    public function edit(User $agent)
    {
        if ($agent->role !== 'agent') {
            abort(404);
        }

        $sources = Source::where('is_active', true)->orderBy('name')->get();
        $assignedSourceIds = $agent->assignedSources()->pluck('sources.id')->toArray();

        return view('admin.agents.edit', compact('agent', 'sources', 'assignedSourceIds'));
    }

    public function update(Request $request, User $agent)
    {
        if ($agent->role !== 'agent') {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', function ($attribute, $value, $fail) use ($agent) {
                $exists = User::whereRaw('LOWER(email) = ?', [strtolower($value)])
                    ->where('id', '!=', $agent->id)
                    ->exists();
                if ($exists) {
                    $fail('The email has already been taken.');
                }
            }],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'sources' => 'required|array|min:1',
            'sources.*' => 'exists:sources,id',
            'is_active' => 'nullable',
        ]);

        $agent->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $request->has('is_active'),
        ]);

        if (!empty($validated['password'])) {
            $agent->update(['password' => Hash::make($validated['password'])]);
        }

        $agent->assignedSources()->sync($validated['sources']);

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent updated successfully.');
    }

    public function destroy(User $agent)
    {
        if ($agent->role !== 'agent') {
            abort(404);
        }

        $agent->assignedSources()->detach();
        $agent->delete();

        return redirect()->route('admin.agents.index')
            ->with('success', 'Agent deleted successfully.');
    }
}
