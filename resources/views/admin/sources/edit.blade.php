@extends('layouts.admin')

@section('title', 'Edit Source')

@section('content')
<div class="space-y-6">
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-xl border shadow p-6 max-w-4xl">
        <h1 class="text-2xl font-bold mb-4">Edit Source</h1>
        <form method="POST" action="{{ route('admin.sources.update', $source) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Basic Information -->
            <div class="border-b pb-4">
                <h2 class="text-lg font-semibold mb-4">Basic Information</h2>
                <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $source->name) }}" class="w-full px-3 py-2 border rounded" required>
                <p class="text-xs text-gray-500 mt-1">Used in checkout URL as ?source=NAME</p>
                @error('name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Return URL</label>
                <input type="url" name="return_url" value="{{ old('return_url', $source->return_url) }}" class="w-full px-3 py-2 border rounded" required>
                <p class="text-xs text-gray-500 mt-1">Where "Back to Home" should point after checkout</p>
                @error('return_url')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Renewal URL (Optional)</label>
                <input type="url" name="renewal_url" value="{{ old('renewal_url', $source->renewal_url) }}" class="w-full px-3 py-2 border rounded" placeholder="https://example.com/renew">
                <p class="text-xs text-gray-500 mt-1">Custom renewal URL for renewal reminder emails. If not set, will automatically use Return URL + /renew. If Return URL is also not set, will use default renewal route.</p>
                @error('renewal_url')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="flex items-center space-x-2">
                <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded" {{ old('is_active', $source->is_active) ? 'checked' : '' }}>
                <label for="is_active" class="text-sm">Active</label>
            </div>
                </div>
            </div>

            <!-- SMTP Configuration -->
            <div class="border-b pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">SMTP Configuration</h2>
                    @if($source->smtp_host && $source->smtp_from_address)
                    <button type="button" onclick="document.getElementById('testSmtpModal').classList.remove('hidden')" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Test SMTP
                    </button>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Mailer Type</label>
                        <select name="smtp_mailer" class="w-full px-3 py-2 border rounded">
                            <option value="smtp" {{ old('smtp_mailer', $source->smtp_mailer ?? 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="mailgun" {{ old('smtp_mailer', $source->smtp_mailer) == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ old('smtp_mailer', $source->smtp_mailer) == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            <option value="postmark" {{ old('smtp_mailer', $source->smtp_mailer) == 'postmark' ? 'selected' : '' }}>Postmark</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">SMTP Host</label>
                        <input type="text" name="smtp_host" value="{{ old('smtp_host', $source->smtp_host) }}" class="w-full px-3 py-2 border rounded" placeholder="smtp.gmail.com">
                        @error('smtp_host')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">SMTP Port</label>
                        <input type="number" name="smtp_port" value="{{ old('smtp_port', $source->smtp_port) }}" class="w-full px-3 py-2 border rounded" placeholder="587" min="1" max="65535">
                        @error('smtp_port')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Encryption</label>
                        <select name="smtp_encryption" class="w-full px-3 py-2 border rounded">
                            <option value="">None</option>
                            <option value="tls" {{ old('smtp_encryption', $source->smtp_encryption) == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('smtp_encryption', $source->smtp_encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">SMTP Username</label>
                        <input type="text" name="smtp_username" value="{{ old('smtp_username', $source->smtp_username) }}" class="w-full px-3 py-2 border rounded">
                        @error('smtp_username')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">SMTP Password</label>
                        <input type="password" name="smtp_password" value="" class="w-full px-3 py-2 border rounded" placeholder="Leave blank to keep current">
                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password</p>
                        @error('smtp_password')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">From Address</label>
                        <input type="email" name="smtp_from_address" value="{{ old('smtp_from_address', $source->smtp_from_address) }}" class="w-full px-3 py-2 border rounded" placeholder="noreply@example.com">
                        @error('smtp_from_address')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">From Name</label>
                        <input type="text" name="smtp_from_name" value="{{ old('smtp_from_name', $source->smtp_from_name) }}" class="w-full px-3 py-2 border rounded" placeholder="Company Name">
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
                        <input type="text" name="company_name" value="{{ old('company_name', $source->company_name) }}" class="w-full px-3 py-2 border rounded" placeholder="Your Company Name">
                        @error('company_name')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Contact Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $source->contact_email) }}" class="w-full px-3 py-2 border rounded" placeholder="contact@example.com">
                        @error('contact_email')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Website</label>
                        <input type="url" name="website" value="{{ old('website', $source->website) }}" class="w-full px-3 py-2 border rounded" placeholder="https://example.com">
                        @error('website')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Phone Number</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $source->phone_number) }}" class="w-full px-3 py-2 border rounded" placeholder="+1 (555) 123-4567">
                        @error('phone_number')<div class="text-sm text-red-600 mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium mb-1">Team Name</label>
                        <input type="text" name="team_name" value="{{ old('team_name', $source->team_name) }}" class="w-full px-3 py-2 border rounded" placeholder="Support Team">
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

<!-- Test SMTP Modal -->
<div id="testSmtpModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Send Test Email</h3>
            <button type="button" onclick="document.getElementById('testSmtpModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.sources.test-smtp', $source) }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email Address</label>
                <input type="email" name="test_email" class="w-full px-3 py-2 border rounded" placeholder="test@example.com" required>
                <p class="text-xs text-gray-500 mt-1">Enter the email address to send a test email to.</p>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="document.getElementById('testSmtpModal').classList.add('hidden')" class="px-4 py-2 border rounded hover:bg-gray-50">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Send Test</button>
            </div>
        </form>
    </div>
</div>
@endsection
