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
}
