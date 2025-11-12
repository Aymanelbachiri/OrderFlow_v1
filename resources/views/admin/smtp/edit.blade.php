@extends('layouts.admin')

@section('title', 'SMTP Settings')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">SMTP Settings</h2>
        <p class="text-gray-600 dark:text-gray-400">Configure your email server settings for sending emails from the application.</p>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6 flex items-start">
            <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <div class="flex-1">
                <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
            </div>
            <button type="button" class="text-green-500 hover:text-green-700" onclick="this.parentElement.remove()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6 flex items-start">
            <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <div class="flex-1">
                <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
            </div>
            <button type="button" class="text-red-500 hover:text-red-700" onclick="this.parentElement.remove()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    <!-- SMTP Settings Form -->
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="p-6">
            <form action="{{ route('admin.smtp.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Mailer Type -->
                    <div>
                        <label for="mailer" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Mailer Type <span class="text-red-500">*</span>
                        </label>
                        <select name="mailer" id="mailer" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('mailer') border-red-500 @enderror" required>
                            <option value="smtp" {{ old('mailer', $smtpSetting->mailer) == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="mailgun" {{ old('mailer', $smtpSetting->mailer) == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ old('mailer', $smtpSetting->mailer) == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            <option value="postmark" {{ old('mailer', $smtpSetting->mailer) == 'postmark' ? 'selected' : '' }}>Postmark</option>
                        </select>
                        @error('mailer')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SMTP Host -->
                    <div>
                        <label for="host" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            SMTP Host <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="host" id="host" 
                               value="{{ old('host', $smtpSetting->host) }}" 
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('host') border-red-500 @enderror" 
                               placeholder="smtp.gmail.com" required>
                        @error('host')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SMTP Port -->
                    <div>
                        <label for="port" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            SMTP Port <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="port" id="port" 
                               value="{{ old('port', $smtpSetting->port) }}" 
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('port') border-red-500 @enderror" 
                               placeholder="587" min="1" max="65535" required>
                        @error('port')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Common ports: 25, 465 (SSL), 587 (TLS)</p>
                    </div>

                    <!-- Encryption -->
                    <div>
                        <label for="encryption" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Encryption
                        </label>
                        <select name="encryption" id="encryption" class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('encryption') border-red-500 @enderror">
                            <option value="">None</option>
                            <option value="tls" {{ old('encryption', $smtpSetting->encryption) == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('encryption', $smtpSetting->encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                        @error('encryption')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Username
                        </label>
                        <input type="text" name="username" id="username" 
                               value="{{ old('username', $smtpSetting->username) }}" 
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('username') border-red-500 @enderror" 
                               placeholder="your-email@gmail.com">
                        @error('username')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input type="password" name="password" id="password" 
                                   value="{{ old('password', $smtpSetting->password) }}" 
                                   class="w-full px-4 py-2.5 pr-12 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror" 
                                   placeholder="Your email password or app password">
                            <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">For Gmail, use an App Password instead of your regular password.</p>
                    </div>

                    <!-- From Address -->
                    <div>
                        <label for="from_address" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            From Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="from_address" id="from_address" 
                               value="{{ old('from_address', $smtpSetting->from_address) }}" 
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('from_address') border-red-500 @enderror" 
                               placeholder="noreply@yourdomain.com" required>
                        @error('from_address')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- From Name -->
                    <div>
                        <label for="from_name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            From Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="from_name" id="from_name" 
                               value="{{ old('from_name', $smtpSetting->from_name) }}" 
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('from_name') border-red-500 @enderror" 
                               placeholder="Your Application Name" required>
                        @error('from_name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Test Email Section -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Test Email Configuration</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Send a test email to verify your SMTP configuration is working correctly.</p>
                    <div class="max-w-xl">
                        <input type="email" name="test_email" id="test_email" 
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="Enter email address to send test email (optional)">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to skip test email.</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Save SMTP Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Help Section -->
    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
            <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
            SMTP Configuration Help
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Popular SMTP Providers:</h4>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <li><strong>Gmail:</strong> smtp.gmail.com, Port 587, TLS</li>
                    <li><strong>Outlook/Hotmail:</strong> smtp-mail.outlook.com, Port 587, TLS</li>
                    <li><strong>Yahoo:</strong> smtp.mail.yahoo.com, Port 587, TLS</li>
                    <li><strong>SendGrid:</strong> smtp.sendgrid.net, Port 587, TLS</li>
                    <li><strong>Mailgun:</strong> smtp.mailgun.org, Port 587, TLS</li>
                </ul>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Security Notes:</h4>
                <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                    <li>• Use App Passwords for Gmail instead of your regular password</li>
                    <li>• Enable 2FA on your email account for better security</li>
                    <li>• Use TLS encryption when possible</li>
                    <li>• Test your configuration after saving</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordField = document.getElementById('password');
    
    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', function() {
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            
            // Toggle icon
            const svg = this.querySelector('svg');
            if (type === 'password') {
                svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            } else {
                svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
            }
        });
    }

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
        alerts.forEach(function(alert) {
            if (alert.querySelector('button')) {
                alert.style.transition = 'opacity 0.3s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
        });
    }, 5000);
});
</script>
@endsection