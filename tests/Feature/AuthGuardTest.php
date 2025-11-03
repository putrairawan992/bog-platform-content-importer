<?php

use App\Models\User;

describe('Authentication Redirects', function () {
    it('redirects guests to login', function () {
        $routes = [
            route('posts.index'),
            route('posts.create'),
            route('posts.show', 123),
            route('posts.edit', 123)
        ];

        foreach ($routes as $route) {
            $this->get($route)->assertRedirect(route('login'));
        }
    });

    it('redirects authenticated users from auth pages', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->get(route('login'))
             ->assertRedirect(route('home'));

        $this->actingAs($user)
             ->get(route('register'))
             ->assertRedirect(route('home'));
    });

    it('redirects after logout', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post(route('logout'))
             ->assertRedirect(route('home'));
    });
});
