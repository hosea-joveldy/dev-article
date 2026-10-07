<?php

namespace Tests\Feature;

use App\Models\Artikel;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArtikelTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_article_routes(): void
    {
        $artikel = Artikel::factory()->create();

        // List
        $this->get('/artikel')->assertRedirect('/login');

        // Detail
        $this->get("/artikel/{$artikel->id}")->assertRedirect('/login');

        // Create
        $this->get('/artikel/create')->assertRedirect('/login');

        // Store
        $this->post('/artikel', [
            'judul' => 'Test Title',
            'konten' => 'Test Content',
        ])->assertRedirect('/login');

        // Edit
        $this->get("/artikel/{$artikel->id}/edit")->assertRedirect('/login');

        // Update
        $this->put("/artikel/{$artikel->id}", [
            'judul' => 'Updated Title',
            'konten' => 'Updated Content',
        ])->assertRedirect('/login');

        // Destroy
        $this->delete("/artikel/{$artikel->id}")->assertRedirect('/login');

        // Protected image
        $this->get("/artikel/{$artikel->id}/image")->assertRedirect('/login');

        // Feed & Sitemap
        $this->get('/feed')->assertRedirect('/login');
        $this->get('/sitemap.xml')->assertRedirect('/login');
    }

    public function test_guest_landing_page_displays_actual_articles(): void
    {
        $title = 'Confidential Quantum Leak 9988';
        Artikel::factory()->create(['judul' => $title]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($title);
    }

    public function test_authenticated_user_on_root_is_redirected_to_article_list(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');
        $response->assertRedirect('/artikel');
    }

    public function test_authenticated_user_can_view_articles_and_detail(): void
    {
        $user = User::factory()->create();
        $artikel = Artikel::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user)->get('/artikel')
            ->assertStatus(200)
            ->assertSee($artikel->judul);

        $this->actingAs($user)->get("/artikel/{$artikel->id}")
            ->assertStatus(200)
            ->assertSee($artikel->judul);
    }

    public function test_authenticated_user_sees_peoples_choice_section_in_sidebar(): void
    {
        $user = User::factory()->create();
        $artikel = Artikel::factory()->create([
            'user_id' => $user->id,
            'judul' => 'A Special Highlight Story',
        ]);

        $response = $this->actingAs($user)->get('/artikel');

        $response->assertStatus(200)
            ->assertSee("PEOPLE'S CHOICE", false)
            ->assertSee('A Special Highlight Story');
    }

    public function test_user_can_create_article_with_ownership(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $image = UploadedFile::fake()->image('banner.jpg');

        $response = $this->actingAs($user)->post('/artikel', [
            'judul' => 'My New Perspective',
            'konten' => 'Deep insights on engineering leadership.',
            'category_id' => $category->id,
            'gambar' => $image,
        ]);

        $this->assertDatabaseHas('artikels', [
            'judul' => 'My New Perspective',
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $created = Artikel::where('judul', 'My New Perspective')->first();
        $response->assertRedirect("/artikel/{$created->id}");
        $this->assertNotNull($created->gambar);
        Storage::disk('public')->assertExists($created->gambar);
    }

    public function test_author_can_update_own_article_but_other_user_cannot(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $artikel = Artikel::factory()->create(['user_id' => $author->id]);

        // Other user cannot edit or update
        $this->actingAs($otherUser)->get("/artikel/{$artikel->id}/edit")->assertStatus(403);
        $this->actingAs($otherUser)->put("/artikel/{$artikel->id}", [
            'judul' => 'Hacked Title',
            'konten' => 'Hacked Content',
        ])->assertStatus(403);

        // Author can edit and update
        $this->actingAs($author)->get("/artikel/{$artikel->id}/edit")->assertStatus(200);
        $this->actingAs($author)->put("/artikel/{$artikel->id}", [
            'judul' => 'Legit Updated Title',
            'konten' => 'Legit Updated Content',
        ])->assertRedirect("/artikel/{$artikel->id}");

        $this->assertDatabaseHas('artikels', [
            'id' => $artikel->id,
            'judul' => 'Legit Updated Title',
        ]);
    }

    public function test_admin_can_update_and_delete_any_article(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $author = User::factory()->create(['is_admin' => false]);
        $artikel = Artikel::factory()->create(['user_id' => $author->id]);

        // Admin can edit
        $this->actingAs($admin)->get("/artikel/{$artikel->id}/edit")->assertStatus(200);

        // Admin can update
        $this->actingAs($admin)->put("/artikel/{$artikel->id}", [
            'judul' => 'Admin Edited Title',
            'konten' => 'Admin Content',
        ])->assertRedirect("/artikel/{$artikel->id}");

        // Admin can delete
        $this->actingAs($admin)->delete("/artikel/{$artikel->id}")->assertRedirect('/artikel');
        $this->assertDatabaseMissing('artikels', ['id' => $artikel->id]);
    }

    public function test_image_is_deleted_from_storage_when_article_is_deleted(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $imagePath = UploadedFile::fake()->image('post.jpg')->store('gambars', 'public');
        $artikel = Artikel::factory()->create([
            'user_id' => $user->id,
            'gambar' => $imagePath,
        ]);

        Storage::disk('public')->assertExists($imagePath);

        $this->actingAs($user)->delete("/artikel/{$artikel->id}");

        $this->assertDatabaseMissing('artikels', ['id' => $artikel->id]);
        Storage::disk('public')->assertMissing($imagePath);
    }

    public function test_search_and_category_filtering_and_sorting(): void
    {
        $user = User::factory()->create();
        $catTech = Category::factory()->create(['nama' => 'Tech', 'slug' => 'tech']);
        $catBiz = Category::factory()->create(['nama' => 'Business', 'slug' => 'biz']);

        $art1 = Artikel::factory()->create([
            'user_id' => $user->id,
            'judul' => 'Kubernetes in Production',
            'category_id' => $catTech->id,
        ]);

        $art2 = Artikel::factory()->create([
            'user_id' => $user->id,
            'judul' => 'Venture Capital Fundamentals',
            'category_id' => $catBiz->id,
        ]);

        // Search
        $resSearch = $this->actingAs($user)->get('/artikel?q=Kubernetes');
        $resSearch->assertSee('Kubernetes in Production');
        $resSearch->assertDontSee('Venture Capital Fundamentals');

        // Category filter
        $resCat = $this->actingAs($user)->get("/artikel?category={$catTech->slug}");
        $resCat->assertSee('Kubernetes in Production');
        $resCat->assertDontSee('Venture Capital Fundamentals');

        // Sorting preserves query string
        $resSort = $this->actingAs($user)->get('/artikel?sort=oldest&q=Kubernetes');
        $resSort->assertStatus(200);
    }

    public function test_comments_create_and_delete_authorization(): void
    {
        $author = User::factory()->create();
        $commenter = User::factory()->create();
        $otherUser = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        $artikel = Artikel::factory()->create(['user_id' => $author->id]);

        // Commenter adds comment
        $this->actingAs($commenter)->post("/artikel/{$artikel->id}/komentar", [
            'body' => 'Terrific article!',
        ])->assertRedirect();

        $comment = Comment::where('artikel_id', $artikel->id)->first();
        $this->assertNotNull($comment);
        $this->assertEquals($commenter->id, $comment->user_id);

        // Other user cannot delete commenter's comment
        $this->actingAs($otherUser)->delete("/komentar/{$comment->id}")->assertStatus(403);

        // Commenter can delete own comment
        $this->actingAs($commenter)->delete("/komentar/{$comment->id}")->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);

        // Admin can delete any comment
        $newComment = Comment::create([
            'artikel_id' => $artikel->id,
            'user_id' => $commenter->id,
            'body' => 'Another comment',
        ]);
        $this->actingAs($admin)->delete("/komentar/{$newComment->id}")->assertRedirect();
        $this->assertDatabaseMissing('comments', ['id' => $newComment->id]);
    }

    public function test_feed_and_sitemap_xml(): void
    {
        $user = User::factory()->create();
        $artikel = Artikel::factory()->create(['user_id' => $user->id, 'judul' => 'RSS Public Post']);

        $resFeed = $this->actingAs($user)->get('/feed');
        $resFeed->assertStatus(200);
        $resFeed->assertHeader('Content-Type', 'application/rss+xml; charset=UTF-8');
        $resFeed->assertSee('RSS Public Post');

        $resSitemap = $this->actingAs($user)->get('/sitemap.xml');
        $resSitemap->assertStatus(200);
        $resSitemap->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $resSitemap->assertSee(route('artikel.show', $artikel->id));
    }
}
