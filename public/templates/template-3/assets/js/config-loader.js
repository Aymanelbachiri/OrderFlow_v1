// Load configuration from API
const domain = window.location.hostname;
const apiBaseUrl = window.location.origin + '/api';

let siteConfig = {};

async function loadConfig() {
    try {
        const response = await fetch(`${apiBaseUrl}/shield-domain/config?domain=${domain}`);
        const config = await response.json();
        siteConfig = config;
        applyConfig(config);
    } catch (error) {
        console.error('Failed to load config:', error);
    }
}

function applyConfig(config) {
    // Apply logo
    const logo = document.getElementById('logo');
    if (logo && config.source?.company_name) {
        logo.textContent = config.source.company_name;
    }

    // Apply hero title
    const heroTitle = document.getElementById('hero-title');
    if (heroTitle && config.source?.company_name) {
        heroTitle.textContent = `Welcome to ${config.source.company_name}`;
    }

    // Apply hero description
    const heroDesc = document.getElementById('hero-description');
    if (heroDesc) {
        heroDesc.textContent = config.source?.company_name 
            ? `Premium IPTV services from ${config.source.company_name}`
            : 'Get access to thousands of channels and premium content';
    }

    // Apply footer
    const footer = document.getElementById('footer-text');
    if (footer && config.source?.company_name) {
        footer.textContent = `© ${new Date().getFullYear()} ${config.source.company_name}. All rights reserved.`;
    }

    // Load pricing plans
    if (config.pricing_plans && config.pricing_plans.length > 0) {
        renderPricingPlans(config.pricing_plans);
    }
}

function renderPricingPlans(plans) {
    const container = document.getElementById('pricing-plans');
    if (!container) return;

    container.innerHTML = plans.map(plan => `
        <div class="pricing-card">
            <h3>${plan.name}</h3>
            <div class="price">
                $${plan.price}
                <span>/${plan.duration_months} month${plan.duration_months > 1 ? 's' : ''}</span>
            </div>
            <ul>
                <li>${plan.device_count} Device${plan.device_count > 1 ? 's' : ''}</li>
                <li>${plan.server_type === 'premium' ? 'Premium' : 'Basic'} Server</li>
                <li>${plan.duration_months} Month${plan.duration_months > 1 ? 's' : ''} Access</li>
            </ul>
            <a href="/templates/template-1/checkout.html?plan_id=${plan.id}" class="btn btn-primary">Select Plan</a>
        </div>
    `).join('');
}

// Load config on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadConfig);
} else {
    loadConfig();
}

