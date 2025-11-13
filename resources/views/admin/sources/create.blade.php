@extends('layouts.admin')

@section('title', 'Add Source')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-[#201E1F] mb-2">Add Source</h1>
                <p class="text-[#201E1F]/60">Create a new source with SMTP and email configuration</p>
            </div>
            <a href="{{ route('admin.sources.index') }}" 
               class="text-[#201E1F]/80 hover:text-[#201E1F] border border-gray-200 px-6 py-3 rounded-lg text-sm font-semibold transition-all duration-300">
                Back to Sources
            </a>
        </div>
    </div>

    <!-- Main Form -->
    <div class="bg-[#F5F5F5] rounded-xl border border-[#D63613]/10 shadow-md p-6">
        <form method="POST" action="{{ route('admin.sources.store') }}" class="space-y-8">
            @csrf

            <!-- Basic Information -->
            <div>
                <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Basic Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent @error('name') border-red-500 @enderror"
                               required>
                        <p class="text-xs text-[#201E1F]/60 mt-1">Used in checkout URL as ?source=NAME</p>
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Return URL *</label>
                        <input type="url" name="return_url" value="{{ old('return_url') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613] focus:border-transparent @error('return_url') border-red-500 @enderror"
                               required>
                        <p class="text-xs text-[#201E1F]/60 mt-1">Where "Back to Home" should point after checkout</p>
                        @error('return_url')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-2">
                        <input id="is_active" type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-[#D63613] focus:ring-[#D63613]" checked>
                        <label for="is_active" class="text-sm text-[#201E1F]">Active</label>
                    </div>
                </div>
            </div>

            <!-- SMTP Configuration -->
            <div>
                <h2 class="text-xl font-semibold text-[#201E1F] mb-4">SMTP Configuration (Optional)</h2>
                <p class="text-sm text-[#201E1F]/60 mb-4">Configure SMTP settings for this source. If not set, will use admin or default SMTP.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Mailer Type</label>
                        <select name="smtp_mailer" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]">
                            <option value="smtp" {{ old('smtp_mailer') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="mailgun" {{ old('smtp_mailer') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ old('smtp_mailer') == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            <option value="postmark" {{ old('smtp_mailer') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">SMTP Host</label>
                        <input type="text" name="smtp_host" value="{{ old('smtp_host') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="smtp.gmail.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">SMTP Port</label>
                        <input type="number" name="smtp_port" value="{{ old('smtp_port', 587) }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="587" min="1" max="65535">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Encryption</label>
                        <select name="smtp_encryption" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]">
                            <option value="tls" {{ old('smtp_encryption', 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('smtp_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">SMTP Username</label>
                        <input type="text" name="smtp_username" value="{{ old('smtp_username') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="your-email@gmail.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">SMTP Password</label>
                        <input type="password" name="smtp_password" value="{{ old('smtp_password') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="Your SMTP password">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">From Email Address</label>
                        <input type="email" name="smtp_from_address" value="{{ old('smtp_from_address') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="noreply@example.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">From Name</label>
                        <input type="text" name="smtp_from_name" value="{{ old('smtp_from_name') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="Company Name">
                    </div>
                </div>
            </div>

            <!-- Email Template Variables -->
            <div>
                <h2 class="text-xl font-semibold text-[#201E1F] mb-4">Email Template Variables (Optional)</h2>
                <p class="text-sm text-[#201E1F]/60 mb-4">These variables will be used in email templates. If not set, will use default values.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Company Name</label>
                        <input type="text" name="email_company_name" value="{{ old('email_company_name') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="{{ config('app.name') }}">
                        <p class="text-xs text-[#201E1F]/60 mt-1">Used in email headers and footers</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Website URL</label>
                        <input type="url" name="email_website_url" value="{{ old('email_website_url') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="{{ config('app.url') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Support Email</label>
                        <input type="email" name="email_support_email" value="{{ old('email_support_email') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="support@example.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Contact Email</label>
                        <input type="email" name="email_contact_email" value="{{ old('email_contact_email') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="contact@example.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Team Name</label>
                        <input type="text" name="email_team_name" value="{{ old('email_team_name') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="Support Team">
                        <p class="text-xs text-[#201E1F]/60 mt-1">Used in email signatures (e.g., "The Support Team")</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Contact Phone</label>
                        <input type="text" name="email_contact_phone" value="{{ old('email_contact_phone') }}" 
                               class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                               placeholder="+1 (555) 123-4567">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-[#201E1F]/60 mb-2">Contact Address</label>
                        <textarea name="email_contact_address" rows="2" 
                                  class="w-full px-4 py-3 bg-white border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#D63613]"
                                  placeholder="123 Main St, City, State 12345">{{ old('email_contact_address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.sources.index') }}" 
                   class="px-6 py-3 border border-gray-200 rounded-lg text-[#201E1F] hover:bg-gray-50 transition-all duration-300">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-[#D63613] to-[#D63613]/80 hover:from-[#D63613]/90 hover:to-[#D63613] text-white rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    Create Source
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
