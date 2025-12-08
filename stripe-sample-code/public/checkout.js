// This is your test publishable API key.
const stripe = Stripe("pk_test_51SVxuPFJRUBMFlGAQNf0Fz3AZSCuOueT9HExusKFFmJxZGrPmxq7nXQN6nTk6Ym8sejTXY788VjcpZltvDLMQY0F00Crsj91Rp");

initialize();

// Create a Checkout Session
async function initialize() {
  const fetchClientSecret = async () => {
    const response = await fetch("/checkout.php", {
      method: "POST",
    });
    const { clientSecret } = await response.json();
    return clientSecret;
  };

  const checkout = await stripe.initEmbeddedCheckout({
    fetchClientSecret,
  });

  // Mount Checkout
  checkout.mount('#checkout');
}