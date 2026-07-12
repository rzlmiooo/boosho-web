<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class BookCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_admin_can_create_book_with_cover()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@boosho.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $cover = UploadedFile::fake()->image('cover.jpg');

        $response = $this->actingAs($admin)->post('/books', [
            'title' => 'Buku Baru Uji',
            'author' => 'Penulis Baru',
            'category' => 'Teknologi',
            'price' => 90000,
            'stock' => 10,
            'description' => 'Ini deskripsi buku belajar teknologi baru.',
            'genres' => ['Teknologi', 'Sains'],
            'cover' => $cover
        ]);

        $response->assertRedirect();
        
        $book = Book::first();
        $this->assertNotNull($book);
        $this->assertEquals('Buku Baru Uji', $book->title);
        $this->assertEquals('Teknologi', $book->category);
        $this->assertNotNull($book->cover);

        // Verify that the file was stored on public disk
        Storage::disk('public')->assertExists($book->cover);
    }

    public function test_admin_can_update_book_with_cover()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@boosho.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $book = Book::create([
            'title' => 'Buku Lama',
            'author' => 'Penulis Lama',
            'price' => 50000,
            'stock' => 5,
            'description' => 'Deskripsi lama.',
            'genres' => ['Novel']
        ]);

        $newCover = UploadedFile::fake()->image('new_cover.png');

        $response = $this->actingAs($admin)->put('/books/' . $book->id, [
            'title' => 'Buku Terupdate',
            'author' => 'Penulis Terupdate',
            'category' => 'Edukasi',
            'price' => 60000,
            'stock' => 7,
            'description' => 'Deskripsi terupdate.',
            'genres' => ['Novel', 'Agama'],
            'cover' => $newCover
        ]);

        $response->assertRedirect('/katalog');

        $book->refresh();
        $this->assertEquals('Buku Terupdate', $book->title);
        $this->assertEquals('Edukasi', $book->category);
        $this->assertNotNull($book->cover);

        // Verify that the new cover was stored on public disk
        Storage::disk('public')->assertExists($book->cover);
    }

    public function test_admin_can_set_batch_discount()
    {
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@boosho.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $book1 = Book::create([
            'title' => 'Buku Uji 1',
            'author' => 'Penulis 1',
            'price' => 100000,
            'stock' => 5,
            'description' => 'Deskripsi 1.'
        ]);

        $book2 = Book::create([
            'title' => 'Buku Uji 2',
            'author' => 'Penulis 2',
            'price' => 200000,
            'stock' => 10,
            'description' => 'Deskripsi 2.'
        ]);

        $response = $this->actingAs($admin)->post('/admin/books/batch-discount', [
            'book_ids' => [$book1->id, $book2->id],
            'discount_percent' => 25,
            'discount_start' => now()->subHour()->toDateTimeString(),
            'discount_end' => now()->addDay()->toDateTimeString()
        ]);

        $response->assertRedirect();
        
        $book1->refresh();
        $book2->refresh();

        $this->assertEquals(25, $book1->discount_percent);
        $this->assertEquals(25, $book2->discount_percent);
        $this->assertEquals(75000, $book1->discounted_price);
        $this->assertEquals(150000, $book2->discounted_price);
    }
}
