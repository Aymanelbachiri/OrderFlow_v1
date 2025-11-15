const domain = window.location.hostname;
const apiBaseUrl = window.location.origin + '/api';

// Get plan_id from URL
const urlParams = new URLSearchParams(window.location.search);
const planId = urlParams.get('plan_id');

document.addEventListener('DOMContentLoaded', async () => {
    // Set domain
    document.getElementById('domain').value = domain;

    // Load checkout data
    await loadCheckoutData();

    // Set selected plan if provided in URL
    if (planId) {
        const planSelect = document.getElementById('pricing_plan_id');
        planSelect.value = planId;
    }

    // Handle form submission
    document.getElementById('checkout-form').addEventListener('submit', handleCheckout);
});

async function loadCheckoutData() {
    try {
        const response = await fetch(`${apiBaseUrl}/shield-domain/checkout/init?domain=${domain}`);
        const data = await response.json();

        // Load pricing plans
        const planSelect = document.getElementById('pricing_plan_id');
        planSelect.innerHTML = '<option value="">Select a plan</option>' +
            data.pricing_plans.map(plan => 
                `<option value="${plan.id}">${plan.name} - $${plan.price}/${plan.duration_months} months</option>`
            ).join('');

        // Load payment methods
        const paymentSelect = document.getElementById('payment_method');
        paymentSelect.innerHTML = '<option value="">Select payment method</option>' +
            data.payment_methods.map(method => 
                `<option value="${method.value}">${method.label}</option>`
            ).join('');

        // Set default payment method
        if (data.default_payment_method) {
            paymentSelect.value = data.default_payment_method;
        }
    } catch (error) {
        console.error('Failed to load checkout data:', error);
        showError('Failed to load checkout data. Please refresh the page.');
    }
}

async function handleCheckout(e) {
    e.preventDefault();
    
    const form = e.target;
    const errorDiv = document.getElementById('error-message');
    errorDiv.style.display = 'none';

    const formData = {
        full_name: form.full_name.value,
        email: form.email.value,
        phone: form.phone.value,
        pricing_plan_id: form.pricing_plan_id.value,
        subscription_type: form.subscription_type.value,
        payment_method: form.payment_method.value,
        domain: domain,
    };

    try {
        const response = await fetch(`${apiBaseUrl}/shield-domain/checkout/submit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData),
        });

        const data = await response.json();

        if (data.success && data.payment_url) {
            // Redirect to payment gateway
            window.location.href = data.payment_url;
        } else {
            showError(data.error || 'Failed to process checkout. Please try again.');
        }
    } catch (error) {
        console.error('Checkout error:', error);
        showError('An error occurred. Please try again.');
    }
}

function showError(message) {
    const errorDiv = document.getElementById('error-message');
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

