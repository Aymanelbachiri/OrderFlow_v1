@extends('layouts.checkout')

@section('title', 'Crypto Payment')

@section('content')
    <div class="min-h-screen flex items-center py-12 px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8 animate-fade-in-up">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">USDT(TRC20) Payment</h1>
                <p class="text-gray-900 dark:text-white/60 text-lg">Secure and anonymous payment with USDT(TRC20)</p>
                <div class="flex items-center justify-center space-x-2 mt-4">
                    <div
                        class="flex items-center space-x-1 bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-medium">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span>Blockchain Secured</span>
                    </div>
                    <div class="text-gray-900 dark:text-white/40">•</div>
                    <span class="text-gray-900 dark:text-white/60 text-sm">Payment ID: #{{ $paymentIntent->id }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                <!-- Order Summary Sidebar -->
                <div class="lg:col-span-2 order-2 lg:order-1">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-[#e5e7eb] dark:border-gray-700 shadow-md sticky top-8 animate-fade-in-up"
                        style="animation-delay: 0.1s;">
                        <div class="px-6 py-5 border-b border-[#e5e7eb] dark:border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Order Summary</h2>
                            </div>
                        </div>

                        <div class="p-6">


                            <!-- Payment Amount -->
                            <h3 class="text-2xl text-center py-4 font-bold text-gray-900 dark:text-white mb-2">
                                @if(isset($customProduct))
                                    {{ $customProduct->name }}
                                @else
                                    {{ $paymentIntent->pricingPlan->display_name ?? $paymentIntent->resellerCreditPack->name ?? 'Order' }}
                                @endif
                            </h3>
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 mb-6">
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Payment Amount</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-900 dark:text-white/70">Amount (USD):</span>
                                        <span
                                            class="text-gray-900 dark:text-white">${{ number_format($paymentIntent->amount, 2) }}</span>
                                    </div>
                                    <div class="border-t border-gray-200 pt-2 flex justify-between font-semibold">
                                        <span class="text-gray-900 dark:text-white">Total:</span>
                                        <span
                                            class="text-xl text-blue-500">${{ number_format($paymentIntent->amount, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Crypto Benefits -->
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                <h4 class="font-semibold text-amber-700 text-sm mb-2">Crypto Benefits</h4>
                                <div class="space-y-2 text-xs text-amber-600">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Anonymous Payment</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>No Chargebacks</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Low Fees</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div class="lg:col-span-3 order-1 lg:order-2">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-[#e5e7eb] dark:border-gray-700 shadow-md animate-fade-in-up"
                        style="animation-delay: 0.2s;">
                        <div class="px-6 py-5 border-b border-[#e5e7eb] dark:border-gray-700">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="green" class="bi bi-currency-bitcoin" viewBox="0 0 16 16">
                                        <path d="M5.5 13v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.5v1.25c0 .138.112.25.25.25h1a.25.25 0 0 0 .25-.25V13h.084c1.992 0 3.416-1.033 3.416-2.82 0-1.502-1.007-2.323-2.186-2.44v-.088c.97-.242 1.683-.974 1.683-2.19C11.997 3.93 10.847 3 9.092 3H9V1.75a.25.25 0 0 0-.25-.25h-1a.25.25 0 0 0-.25.25V3h-.573V1.75a.25.25 0 0 0-.25-.25H5.75a.25.25 0 0 0-.25.25V3l-1.998.011a.25.25 0 0 0-.25.25v.989c0 .137.11.25.248.25l.755-.005a.75.75 0 0 1 .745.75v5.505a.75.75 0 0 1-.75.75l-.748.011a.25.25 0 0 0-.25.25v1c0 .138.112.25.25.25zm1.427-8.513h1.719c.906 0 1.438.498 1.438 1.312 0 .871-.575 1.362-1.877 1.362h-1.28zm0 4.051h1.84c1.137 0 1.756.58 1.756 1.524 0 .953-.626 1.45-2.158 1.45H6.927z"/>
                                      </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">USDT (TRC20) Payment</h2>
                            </div>
                        </div>

                        <div class="p-6">
                            @php $wallet = \App\Models\SystemSetting::get('crypto_wallet_address', ''); @endphp
                            @if (empty($wallet))
                                <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                                    <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-red-700 mb-2">Crypto Payment Unavailable</h3>
                                    <p class="text-red-600">USDT(TRC20) payment is not configured. Please contact our
                                        support team for assistance.</p>
                                </div>
                            @else
                                

                                <!-- Wallet Address -->
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 mb-6">
                                    <div class="flex items-center justify-between mb-3">
                                        <label class="block text-sm font-semibold text-gray-900 dark:text-white">USDT
                                            (TRC20) Wallet Address</label>
                                        <button onclick="copyWalletAddress()"
                                            class="text-xs text-blue-500 hover:text-blue-500/80 font-medium flex items-center space-x-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            <span id="copy-text">Copy</span>
                                        </button>
                                    </div>
                                    
                                        <div class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-300 rounded-lg font-mono text-sm text-gray-900 dark:text-white break-all"
                                            id="wallet-address">
                                            {{ $wallet }}
                                        </div>
                                        
                                    
                                    <p class="mt-2 text-xs text-gray-900 dark:text-white/60 flex items-center space-x-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <span>Send only USDT (TRC20) to this address</span>
                                    </p>
                                </div>

                                <!-- Transaction ID Input (unchanged logic) -->
                                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg border border-gray-200 mb-6">
                                    <label for="txid"
                                        class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Transaction
                                        ID (TXID)</label>
                                    @if ($errors->has('payment_id'))
                                        <div class="mb-4 bg-red-50 border border-red-200 rounded-lg p-4">
                                            <div class="flex items-center space-x-2 text-red-700">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-medium">{{ $errors->first('payment_id') }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-900 dark:text-white/40" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <input type="text" id="txid"
                                            class="w-full bg-white dark:bg-gray-800 border {{ $errors->has('payment_id') ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20' : 'border-gray-200 focus:border-[#D63613] focus:ring-[#D63613]/20' }} rounded-lg pl-10 pr-4 py-3 text-gray-900 dark:text-white focus:ring-2 transition-all duration-300 font-mono text-sm"
                                            placeholder="Enter your transaction ID after sending USDT (TRC20)"
                                            value="{{ old('payment_id') }}">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-900 dark:text-white/60">
                                        You can find your TXID in your USDT (TRC20) wallet after completing the transaction
                                    </p>
                                </div>

                                <!-- Payment Steps -->
                                <div
                                    class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg p-6 mb-6 border border-[#e5e7eb]">
                                    <h3 class="font-semibold text-green-800 mb-4 flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                            </path>
                                        </svg>
                                        <span>Payment Instructions</span>
                                    </h3>
                                    <ol class="space-y-3 text-sm text-green-700">
                                        <li class="flex items-start space-x-3">
                                            <span
                                                class="w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">1</span>
                                            <span>Copy the USDT (TRC20) wallet address below</span>
                                        </li>
                                        <li class="flex items-start space-x-3">
                                            <span
                                                class="w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">2</span>
                                            <span>Send the exact amount (${{ number_format($paymentIntent->amount, 2) }}
                                                USDT) to this address</span>
                                        </li>
                                        <li class="flex items-start space-x-3">
                                            <span
                                                class="w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold">3</span>
                                            <span>Enter your transaction ID (TXID) below and confirm</span>
                                        </li>
                                    </ol>
                                </div>

                                <!-- Important Notice -->
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                    <div class="flex items-start space-x-3">
                                        <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        <div>
                                            <h4 class="font-semibold text-red-700 text-sm mb-1">Important</h4>
                                            <ul class="text-red-600 text-sm space-y-1">
                                                <li>• Send only USDT (TRC20) to this address</li>
                                                <li>• Using any other network (ERC20, BEP20, etc.) will result in permanent
                                                    loss</li>
                                                <li>• Transactions are irreversible - double check the address</li>
                                                <li>• Your order will be processed after blockchain confirmation</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Confirm Button + Script (unchanged functionality) -->
                                <div id="status" class="mb-4"></div>
                                <button id="confirm-button"
                                    class="w-full bg-gradient-to-r from-blue-500 to-blue-500/80 hover:from-blue-500/90 hover:to-blue-500 text-white py-4 px-6 rounded-lg font-semibold text-lg flex items-center justify-center space-x-2 transition-all duration-300 shadow-md hover:shadow-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>I Have Sent the Payment</span>
                                </button>

                                

                                <script>
                                    function copyWalletAddress() {
                                        const walletAddress = document.getElementById('wallet-address').textContent.trim();
                                        navigator.clipboard.writeText(walletAddress).then(function() {
                                            const copyText = document.getElementById('copy-text');
                                            copyText.textContent = 'Copied!';
                                            copyText.classList.add('text-green-600');
                                            setTimeout(() => {
                                                copyText.textContent = 'Copy';
                                                copyText.classList.remove('text-green-600');
                                            }, 2000);
                                        }, function(err) {
                                            console.error('Could not copy text: ', err);
                                        });
                                    }

                                    document.getElementById('confirm-button').addEventListener('click', function() {
                                        const txid = document.getElementById('txid').value.trim();
                                        const statusDiv = document.getElementById('status');

                                        if (!txid) {
                                            statusDiv.innerHTML = `
                            <div class="bg-green-50 border border-[#e5e7eb] rounded-lg p-4">
                                <div class="flex items-center space-x-2 text-green-700">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="font-medium">Please enter your transaction ID (TXID)</span>
                                </div>
                            </div>
                        `;
                                            return;
                                        }

                                        this.disabled = true;
                                        this.innerHTML = `
                        <div class="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                        <span>Processing...</span>
                    `;

                                        const form = document.createElement('form');
                                        form.method = 'POST';
                                        form.action = '{{ route('public.payment-intents.success', $paymentIntent) }}';
                                        form.innerHTML = `
                        <input type="hidden" name="_token" value='{{ csrf_token() }}'/>
                        <input type="hidden" name="payment_id" value='${txid}'/>
                        <input type="hidden" name="payment_method" value='crypto'/>
                        <input type="hidden" name="payment_details" value='${JSON.stringify({txid: txid})}'/>
                    `;
                                        document.body.appendChild(form);
                                        form.submit();
                                    });
                                </script>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out forwards;
        }

        /* Custom spinner animation */
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }
    </style>
@endsection
