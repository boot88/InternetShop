<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Tests\TestCase;

class StorefrontFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Keeps HTTP flow tests independent from a cached production config.
        // CSRF remains enabled for the actual application.
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    public function test_guest_can_add_an_available_product_to_cart(): void
    {
        $this->withSession(['cart_test' => 'guest']);
        $product = Product::create([
            'name' => 'Тестовый товар',
            'slug' => 'test-product',
            'sku' => 'TEST-1',
            'price' => 1000,
            'is_active' => true,
        ]);
        Stock::create(['product_id' => $product->id, 'quantity' => 3]);

        $this->postJson(route('cart.add', $product), ['quantity' => 1])
            ->assertOk()
            ->assertJson(['success' => true, 'cart_count' => 1]);

        $this->assertDatabaseHas('cart_items', ['product_id' => $product->id, 'quantity' => 1]);
    }

    public function test_checkout_requires_privacy_and_terms_consent(): void
    {
        $this->withSession(['cart_test' => 'consent']);
        $product = Product::create([
            'name' => 'Тестовый товар',
            'slug' => 'checkout-product',
            'sku' => 'TEST-2',
            'price' => 1000,
            'is_active' => true,
        ]);
        Stock::create(['product_id' => $product->id, 'quantity' => 3]);
        $this->postJson(route('cart.add', $product), ['quantity' => 1])->assertOk();

        $this->post(route('checkout.store'), [
            'name' => 'Покупатель',
            'phone' => '+7 999 000-00-00',
            'shipping_address' => 'Новосибирск, тестовый адрес 1',
            'shipping_method' => 'e2e4_pickup',
            'payment_method' => 'online_prepayment',
        ])->assertSessionHasErrors(['privacy_consent', 'terms_consent']);
    }

    public function test_checkout_creates_order_and_decrements_stock(): void
    {
        $this->withSession(['cart_test' => 'checkout']);
        $user = User::factory()->create();
        $this->actingAs($user);
        $product = Product::create([
            'name' => 'Товар для заказа',
            'slug' => 'ordered-product',
            'sku' => 'TEST-3',
            'price' => 2500,
            'is_active' => true,
        ]);
        $stock = Stock::create(['product_id' => $product->id, 'quantity' => 2]);
        $this->postJson(route('cart.add', $product), ['quantity' => 1])->assertOk();

        $response = $this->post(route('checkout.store'), [
            'name' => 'Покупатель',
            'phone' => '+7 999 000-00-00',
            'shipping_address' => 'Новосибирск, тестовый адрес 1',
            'shipping_method' => 'e2e4_pickup',
            'payment_method' => 'online_prepayment',
            'privacy_consent' => '1',
            'terms_consent' => '1',
        ]);

        $order = \App\Models\Order::query()->firstOrFail();
        $response->assertRedirect(route('checkout.success', $order));
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'user_id' => $user->id, 'status' => 'pending', 'total' => 2500]);
        $this->assertDatabaseHas('order_items', ['order_id' => $order->id, 'product_id' => $product->id, 'quantity' => 1]);
        $this->assertSame(1, $stock->fresh()->quantity);
    }

    public function test_authenticated_user_can_open_profile_and_orders(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('profile.edit'))->assertOk();
        $this->actingAs($user)->get(route('orders.index'))->assertOk();
    }

    public function test_regular_user_cannot_open_admin_section(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();
    }

    public function test_public_pages_do_not_expose_developer_placeholders(): void
    {
        $this->get(route('about'))->assertOk()->assertDontSee('.env');
        $this->get(route('deals'))->assertOk()->assertDontSee('демо-набор');
    }
}
