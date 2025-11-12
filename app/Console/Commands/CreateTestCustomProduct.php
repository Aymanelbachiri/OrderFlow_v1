<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CustomProduct;

class CreateTestCustomProduct extends Command
{
    protected $signature = 'test:create-custom-product';
    protected $description = 'Create a test custom product for testing checkout';

    public function handle()
    {
        $product = CustomProduct::create([
            'name' => 'Test Custom Product',
            'slug' => 'test-custom-product',
            'short_description' => 'A test custom product for testing checkout functionality',
            'description' => 'This is a test custom product created to test the checkout functionality. It includes all necessary features for testing payment processing.',
            'price' => 25.00,
            'product_type' => 'service',
            'is_active' => true,
            'stock_quantity' => null, // Unlimited stock
        ]);

        $this->info("Created test custom product: {$product->name}");
        $this->info("Slug: {$product->slug}");
        $this->info("Price: \${$product->price}");
        $this->info("Available: " . ($product->isAvailable() ? 'Yes' : 'No'));
        $this->info("Checkout URL: " . route('custom-product.checkout.show', $product->slug));
    }
}