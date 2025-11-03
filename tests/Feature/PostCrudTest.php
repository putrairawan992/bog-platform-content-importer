<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

// Helper functions
function validPostData($overrides = [])
{
    return array_merge([
        'title'   => 'Test Post Title',
        'content' => 'This is the test post content.',
        'status'  => 'draft',
    ], $overrides);
}

describe('List Posts', function () {
    it('displays the posts index page', function () {
        $this->get(route('posts.index'))
             ->assertStatus(200)
             ->assertSee('Posts')
             ->assertSee('Create Post');
    });

    it('displays users posts', function () {
        $posts = Post::factory()->count(3)->for($this->user, 'author')->create();

        $this->get(route('posts.index'))
             ->assertStatus(200)
             ->assertSee($posts->first()->title)
             ->assertSee($posts->last()->title);
    });

    it('does not display other users posts', function () {
        $otherUser = User::factory()->create();
        $otherUserPost = Post::factory()->for($otherUser, 'author')->create();

        $this->get(route('posts.index'))
             ->assertStatus(200)
             ->assertDontSee($otherUserPost->title);
    });

    it('displays empty state when no posts', function () {
        $this->get(route('posts.index'))
             ->assertStatus(200)
             ->assertSee('No posts found');
    });
});

describe('Create Posts', function () {
    it('displays the create post form', function () {
        $this->get(route('posts.create'))
             ->assertStatus(200)
             ->assertSee('Create New Post')
             ->assertSee('Title')
             ->assertSee('Content')
             ->assertSee('Status')
             ->assertSee('Source')
             ->assertSee('External ID')
             ->assertSee('Save');
    });

    it('creates a new post with valid data', function () {
        $postData = validPostData();

        $this->post(route('posts.store'), $postData)
             ->assertRedirect(route('posts.index'))
             ->assertSessionHas('success', 'Post created successfully!');

        $this->assertDatabaseHas('posts', [
            'title'   => $postData['title'],
            'content' => $postData['content'],
            'user_id' => $this->user->id,
        ]);
    });

    it('requires title when creating post', function () {
        $this->post(route('posts.store'), validPostData(['title' => '']))
             ->assertSessionHasErrors('title');
    });
});

describe('Edit Posts', function () {
    it('displays the edit post form', function () {
        $post = Post::factory()->for($this->user, 'author')->create();

        $this->get(route('posts.edit', $post))
             ->assertStatus(200)
             ->assertSee('Edit Post')
             ->assertSee($post->title)
             ->assertSee($post->content);
    });

    it('updates a post with valid data', function () {
        $post = Post::factory()->for($this->user, 'author')->create();
        $updatedData = validPostData([
            'title'   => 'Updated Post Title',
            'content' => 'Updated post content.',
            'status'  => 'published',
        ]);

        $this->put(route('posts.update', $post), $updatedData)
             ->assertRedirect(route('posts.index'))
             ->assertSessionHas('success', 'Post updated successfully!');

        $this->assertDatabaseHas('posts', [
            'id'      => $post->id,
            'title'   => 'Updated Post Title',
            'content' => 'Updated post content.',
            'status'  => 'published',
        ]);
    });

    it('requires title when updating post', function () {
        $post = Post::factory()->for($this->user, 'author')->create();

        $this->put(route('posts.update', $post), validPostData(['title' => '']))
             ->assertSessionHasErrors('title');
    });

    it('prevents updating other users posts', function () {
        $otherUser = User::factory()->create();
        $otherUserPost = Post::factory()->for($otherUser, 'author')->create();

        $this->put(route('posts.update', $otherUserPost), validPostData())
             ->assertStatus(403);

        // Verify post wasn't updated
        $this->assertDatabaseHas('posts', [
            'id'    => $otherUserPost->id,
            'title' => $otherUserPost->title,
        ]);
    });
});

describe('Delete Posts', function () {
    it('deletes a post', function () {
        $post = Post::factory()->for($this->user, 'author')->create();

        $this->delete(route('posts.destroy', $post))
             ->assertRedirect(route('posts.index'))
             ->assertSessionHas('success', 'Post deleted successfully!');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    });

    it('prevents deleting other users posts', function () {
        $otherUser = User::factory()->create();
        $otherUserPost = Post::factory()->for($otherUser, 'author')->create();

        $this->delete(route('posts.destroy', $otherUserPost))
             ->assertStatus(403);

        $this->assertDatabaseHas('posts', ['id' => $otherUserPost->id]);
    });
});
