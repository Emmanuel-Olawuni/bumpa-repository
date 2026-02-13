<?php

namespace Tests\Feature;

use App\Models\Merchant;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test homepage loads successfully
     */
    public function test_homepage_loads_successfully(): void
    {
        // Seed some data first
        $merchant = Merchant::create([
            'name' => 'Test Merchant',
            'slug' => 'test-merchant',
            'email' => 'merchant@test.com',
            'phone' => '+234 800 000 0000',
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Test cart page loads
     */
    public function test_cart_page_loads_successfully(): void
    {
        $response = $this->get('/cart');

        $response->assertStatus(200);
    }

    /**
     * Test checkout redirects when cart is empty
     */
    public function test_checkout_redirects_when_cart_is_empty(): void
    {
        $response = $this->get('/checkout');

        $response->assertRedirect('/cart');
    }

    /**
     * Test adding item to cart
     */
    public function test_can_add_product_to_cart(): void
    {
        $merchant = Merchant::create([
            'name' => 'Test Merchant',
            'slug' => 'test-merchant',
            'email' => 'merchant@test.com',
            'phone' => '+234 800 000 0000',
        ]);

        $product = Product::create([
            'merchant_id' => $merchant->id,
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'A test product',
            'price' => 5000,
            'stock' => 10,
            'image' => 'https://example.com/image.jpg',
        ]);

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }
}
