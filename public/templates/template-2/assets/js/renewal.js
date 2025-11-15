const domain = window.location.hostname;
const apiBaseUrl = window.location.origin + '/api';

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('renewal_domain').value = domain;
    
    // Handle lookup form
    document.getElementById('renewal-lookup-form').addEventListener('submit', handleLookup);
    
    // Handle renewal form
    document.getElementById('renewal-form').addEventListener('submit', handleRenewal);
});

async function handleLookup(e) {
    e.preventDefault();
    
    const form = e.target;
    const errorDiv = document.getElementById('error-message');
    const successDiv = document.getElementById('success-message');
    const renewalContainer = document.getElementById('renewal-form-container');
    
    errorDiv.style.display = 'none';
    successDiv.style.display = 'none';
    renewalContainer.style.display = 'none';

    const orderNumber = form.order_number.value;
    const email = form.email.value;

    try {
        const response = await fetch(`${apiBaseUrl}/shield-domain/renewal/lookup?order_number=${orderNumber}&email=${email}`);
        const data = await response.json();

        if (data.order) {
            // Show renewal form
            document.getElementById('renewal_order_number').value = orderNumber;
            document.getElementById('renewal_email').value = email;
            
            // Load renewal data
            await loadRenewalData(orderNumber, email);
            
            renewalContainer.style.display = 'block';
            form.style.display = 'none';
        } else {
            showError(data.error || 'Order not found. Please check your order number and email.');
        }
    } catch (error) {
        console.error('Lookup error:', error);
        showError('An error occurred. Please try again.');
    }
}

async function loadRenewalData(orderNumber, email) {
    try {
        const response = await fetch(`${apiBaseUrl}/shield-domain/renewal/${orderNumber}?email=${email}`);
        const data = await response.json();

        if (data.order) {
            // Load plans
            const planSelect = document.getElementById('renewal_plan_id');
            planSelect.innerHTML = '<option value="">Select a plan</option>' +
                data.available_plans.map(plan => 
                    `<option value="${plan.id}" ${plan.id === data.order.plan?.id ? 'selected' : ''}>${plan.name} - $${plan.price}/${plan.duration_months} months</option>`
                ).join('');

            // Load payment methods
            const paymentSelect = document.getElementById('renewal_payment_method');
            paymentSelect.innerHTML = '<option value="">Select payment method</option>' +
                data.payment_methods.map(method => 
                    `<option value="${method.value}">${method.label}</option>`
                ).join('');
        }
    } catch (error) {
        console.error('Failed to load renewal data:', error);
        showError('Failed to load renewal data. Please refresh the page.');
    }
}

async function handleRenewal(e) {
    e.preventDefault();
    
    const form = e.target;
    const errorDiv = document.getElementById('error-message');
    errorDiv.style.display = 'none';

    const orderNumber = form.renewal_order_number.value;
    const formData = {
        email: form.renewal_email.value,
        pricing_plan_id: form.renewal_plan_id.value,
        payment_method: form.renewal_payment_method.value,
    };

    try {
        const response = await fetch(`${apiBaseUrl}/shield-domain/renewal/${orderNumber}`, {
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
            showError(data.error || 'Failed to process renewal. Please try again.');
        }
    } catch (error) {
        console.error('Renewal error:', error);
        showError('An error occurred. Please try again.');
    }
}

function showError(message) {
    const errorDiv = document.getElementById('error-message');
    errorDiv.textContent = message;
    errorDiv.style.display = 'block';
}

