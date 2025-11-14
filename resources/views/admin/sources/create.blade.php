@extends('layouts.admin')

@section('title', 'Add Source')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-xl border shadow p-6 max-w-4xl">
        <h1 class="text-2xl font-bold mb-4">Add Source</h1>
        <form method="POST" action="{{ route('admin.sources.store') }}" class="space-y-6">
            @csrf
            
            <!-- Basic Information -->
            <div class="border-b pb-4">
                <h2 class="text-lg font-semibold mb-4">Basic Information</h2>
                <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border rounded" required>
                <p class="text-xs text-gray-500 mt-1">Used in checkout URL as ?source=NAME</p>
                @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Return URL</label>
                <input type="url" name="return_url" value="{{ old('return_url') }}" class="w-full px-3 py-2 border rounded" required>
                <p class="text-xs text-gray-500 mt-1">Where "Back to Home" should point after checkout</p>
                @error('return_url')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Renewal URL (Optional)</label>
                <input type="url" name="renewal_url" value="{{ old('renewal_url') }}" class="w-full px-3 py-2 border rounded" placeholder="https://example.com/renew">
                <p class="text-xs text-gray-500 mt-1">Custom renewal URL for renewal reminder emails. If not set, will use default renewal route.</p>
                @error('renewal_url')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="flex items-center space-x-2">
                <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded" checked>
                <label for="is_active" class="text-sm">Active</label>
            </div>
                </div>
            </div>

            <!-- SMTP Configuration -->
            <div class="border-b pb-4">
                <h2 class="text-lg font-semibold mb-4">SMTP Configuration</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Mailer Type</label>
                        <select name="smtp_mailer" class="w-full px-3 py-2 border rounded">
                            <option value="smtp" {{ old('smtp_mailer', 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="mailgun" {{ old('smtp_mailer') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ old('smtp_mailer') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            <option value="postmark" {{ old('smtp_mailer') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">SMTP Host</label>
                        <input type="text" name="smtp_host" value="{{ old('smtp_host') }}" class="w-full px-3 py-2 border rounded" placeholder="smtp.gmail.com">
                        @error('smtp_host')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">SMTP Port</label>
                        <input type="number" name="smtp_port" value="{{ old('smtp_port') }}" class="w-full px-3 py-2 border rounded" placeholder="587" min="1" max="65535">
                        @error('smtp_port')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Encryption</label>
                        <select name="smtp_encryption" class="w-full px-3 py-2 border rounded">
                            <option value="">None</option>
                            <option value="tls" {{ old('smtp_encryption', 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('smtp_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">SMTP Username</label>
                        <input type="text" name="smtp_username" value="{{ old('smtp_username') }}" class="w-full px-3 py-2 border rounded">
                        @error('smtp_username')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">SMTP Password</label>
                        <input type="password" name="smtp_password" value="{{ old('smtp_password') }}" class="w-full px-3 py-2 border rounded">
                        @error('smtp_password')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">From Address</label>
                        <input type="email" name="smtp_from_address" value="{{ old('smtp_from_address') }}" class="w-full px-3 py-2 border rounded" placeholder="noreply@example.com">
                        @error('smtp_from_address')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">From Name</label>
                        <input type="text" name="smtp_from_name" value="{{ old('smtp_from_name') }}" class="w-full px-3 py-2 border rounded" placeholder="Company Name">
                        @error('smtp_from_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <!-- Email Template Variables -->
            <div class="border-b pb-4">
                <h2 class="text-lg font-semibold mb-4">Email Template Variables</h2>
                <p class="text-sm text-gray-600 mb-4">These variables will be used in email templates sent for orders from this source.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Company Name</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" class="w-full px-3 py-2 border rounded" placeholder="Your Company Name">
                        @error('company_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email') }}" class="w-full px-3 py-2 border rounded" placeholder="contact@example.com">
                        @error('contact_email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Website</label>
                        <input type="url" name="website" value="{{ old('website') }}" class="w-full px-3 py-2 border rounded" placeholder="https://example.com">
                        @error('website')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full px-3 py-2 border rounded" placeholder="+1 (555) 123-4567">
                        @error('phone_number')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Team Name</label>
                        <input type="text" name="team_name" value="{{ old('team_name') }}" class="w-full px-3 py-2 border rounded" placeholder="Support Team">
                        @error('team_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="px-4 py-2 bg-[#D63613] text-white rounded hover:bg-[#b42f11]">Save</button>
                <a href="{{ route('admin.sources.index') }}" class="ml-2 px-4 py-2 border rounded">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
