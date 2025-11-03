<?php

test('no smoke on public routes', function () {
    $routes = [route('home'), route('login'), route('register')];

    visit($routes)->assertNoSmoke();
});

test('no smoke on auth routes', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);

    $post = \App\Models\Post::factory()->for($user, 'author')->create();

    $routes = [
        route('posts.index'),
        route('posts.create'),
        route('posts.show', $post),
        route('posts.edit', $post)
    ];

    visit($routes)->assertNoSmoke();
});
