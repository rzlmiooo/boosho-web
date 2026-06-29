<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test alur lengkap: user tambah buku dengan kuantitas khusus,
     * checkout (status pending), admin input kode pembayaran (waiting_payment),
     * dan user melakukan simulasi pembayaran (completed).
     */
    public function test_alur_checkout_pembayaran_lengkap(): void
    {
        // 1. Setup data: user pembeli, admin, dan buku
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $admin = User::create([
            'name' => 'Admin BooSho',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $book = Book::create([
            'title' => 'Laravel Advanced',
            'author' => 'Taylor Otwell',
            'price' => 100000,
            'stock' => 10
        ]);

        // 2. User login & tambah buku ke keranjang dengan kuantitas = 2
        $this->actingAs($user);
        $responseCart = $this->post("/cart/{$book->id}", ['quantity' => 2]);
        $responseCart->assertStatus(302); // Redirect back

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'quantity' => 2
        ]);

        // 3. User melakukan Checkout
        $responseCheckout = $this->post('/checkout');
        $responseCheckout->assertRedirect(route('dashboard'));

        // Cek keranjang harus sudah dikosongkan
        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id
        ]);

        // Order baru harus terbentuk dengan status 'pending'
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_price' => 200000,
            'status' => 'pending',
            'payment_code' => null
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);

        // Order item detail harus terbentuk dengan qty = 2
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'book_id' => $book->id,
            'quantity' => 2,
            'price' => 100000
        ]);

        // Stok buku harus berkurang menjadi 8
        $book->refresh();
        $this->assertEquals(8, $book->stock);

        // 4. Admin login & input kode pembayaran
        $this->actingAs($admin);
        $responseAssign = $this->post("/admin/orders/{$order->id}/assign-code", [
            'payment_code' => 'VA-MANDIRI-112233'
        ]);
        $responseAssign->assertStatus(302); // Redirect back

        // Cek status order harus berubah menjadi 'waiting_payment' dengan payment_code terisi
        $order->refresh();
        $this->assertEquals('waiting_payment', $order->status);
        $this->assertEquals('VA-MANDIRI-112233', $order->payment_code);

        // 5. User login kembali & melakukan simulasi bayar
        $this->actingAs($user);
        $responsePay = $this->post("/orders/{$order->id}/pay");
        $responsePay->assertStatus(302); // Redirect back

        // Cek status order harus berubah menjadi 'completed'
        $order->refresh();
        $this->assertEquals('completed', $order->status);
    }

    /**
     * Test checkout gagal jika stok buku tidak mencukupi kuantitas pembelian.
     */
    public function test_checkout_fails_if_stock_is_insufficient(): void
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $book = Book::create([
            'title' => 'Limited Edition',
            'author' => 'Author A',
            'price' => 50000,
            'stock' => 1
        ]);

        $this->actingAs($user);

        // Tambah ke keranjang dengan qty = 1
        $this->post("/cart/{$book->id}", ['quantity' => 1]);

        // Secara manual men-simulate perubahan quantity di DB melebihi stok yang ada
        $cart = Cart::where('user_id', $user->id)->first();
        $cart->update(['quantity' => 2]); // Kebutuhan 2, stok cuma 1

        // Checkout
        $response = $this->post('/checkout');
        $response->assertSessionHas('error');

        // Order untuk user ini tidak boleh terbentuk
        $this->assertDatabaseMissing('orders', [
            'user_id' => $user->id
        ]);

        // Stok tidak boleh berkurang
        $book->refresh();
        $this->assertEquals(1, $book->stock);
    }
}
