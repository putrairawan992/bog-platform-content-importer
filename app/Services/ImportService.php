<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportService
{
    // import random post from jsonplaceholder
    public function importFromJsonPlaceholder(): array
    {
        try {
            $randomId = rand(1, 100);

            $response = Http::get("https://jsonplaceholder.typicode.com/posts/{$randomId}");

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch data from JSONPlaceholder API'
                ];
            }

            $data = $response->json();

            $exists = Post::where('source', 'jsonplaceholder')
                ->where('external_id', $data['id'])
                ->exists();

            if ($exists) {
                return [
                    'success' => false,
                    'message' => 'This post has already been imported'
                ];
            }

            $post = Post::create([
                'title' => $data['title'],
                'content' => $data['body'],
                'image' => null,
                'status' => 'draft',
                'source' => 'jsonplaceholder',
                'external_id' => $data['id'],
            ]);

            return [
                'success' => true,
                'message' => 'Post imported successfully from JSONPlaceholder',
                'post' => $post
            ];
        } catch (\Exception $e) {
            Log::error('JSONPlaceholder import failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error importing from JSONPlaceholder: ' . $e->getMessage()
            ];
        }
    }

    // import random product from fakestore and transform to post
    public function importFromFakeStore(): array
    {
        try {
            $randomId = rand(1, 20);

            $response = Http::get("https://fakestoreapi.com/products/{$randomId}");

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch data from FakeStore API'
                ];
            }

            $data = $response->json();

            $exists = Post::where('source', 'fakestore')
                ->where('external_id', $data['id'])
                ->exists();

            if ($exists) {
                return [
                    'success' => false,
                    'message' => 'This product has already been imported'
                ];
            }

            $title = $data['title'];
            $content = $this->transformProductToContent($data);
            $image = $data['image'] ?? null;

            $post = Post::create([
                'title' => $title,
                'content' => $content,
                'image' => $image,
                'status' => 'draft',
                'source' => 'fakestore',
                'external_id' => $data['id'],
            ]);

            return [
                'success' => true,
                'message' => 'Product imported successfully from FakeStore',
                'post' => $post
            ];
        } catch (\Exception $e) {
            Log::error('FakeStore import failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error importing from FakeStore: ' . $e->getMessage()
            ];
        }
    }

    // transform product data to blog post content
    private function transformProductToContent(array $product): string
    {
        $content = $product['description'] . "\n\n";

        if (isset($product['price'])) {
            $content .= "Price: $" . $product['price'] . "\n";
        }

        if (isset($product['category'])) {
            $content .= "Category: " . $product['category'] . "\n";
        }

        if (isset($product['rating']['rate'])) {
            $content .= "Rating: " . $product['rating']['rate'] . "/5";
        }

        if (isset($product['rating']['count'])) {
            $content .= " (" . $product['rating']['count'] . " reviews)";
        }

        return $content;
    }

    // import from custom api url
    public function importFromUrl(string $url, string $source): array
    {
        try {
            $response = Http::get($url);

            if (!$response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch data from the provided URL'
                ];
            }

            $data = $response->json();

            if (!is_array($data)) {
                return [
                    'success' => false,
                    'message' => 'Invalid response format from URL'
                ];
            }

            $externalId = $data['id'] ?? $data['external_id'] ?? null;

            if (!$externalId) {
                return [
                    'success' => false,
                    'message' => 'Could not find ID field in response'
                ];
            }

            $exists = Post::where('source', $source)
                ->where('external_id', $externalId)
                ->exists();

            if ($exists) {
                return [
                    'success' => false,
                    'message' => 'This content has already been imported'
                ];
            }

            $transformed = $this->transformGenericApiData($data);

            if (!$transformed['title'] || !$transformed['content']) {
                return [
                    'success' => false,
                    'message' => 'Could not extract title and content from response'
                ];
            }

            $post = Post::create([
                'title' => $transformed['title'],
                'content' => $transformed['content'],
                'image' => $transformed['image'],
                'status' => 'draft',
                'source' => $source,
                'external_id' => $externalId,
            ]);

            return [
                'success' => true,
                'message' => 'Content imported successfully from ' . $source,
                'post' => $post
            ];
        } catch (\Exception $e) {
            Log::error('Manual import failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Error importing from URL: ' . $e->getMessage()
            ];
        }
    }

    // transform generic api data to blog post format
    private function transformGenericApiData(array $data): array
    {
        $title = $data['title'] ?? $data['name'] ?? null;

        $content = $data['body']
            ?? $data['content']
            ?? $data['description']
            ?? $data['text']
            ?? null;

        $image = $data['image'] ?? $data['image_url'] ?? $data['thumbnail'] ?? null;

        return [
            'title' => $title,
            'content' => $content,
            'image' => $image,
        ];
    }
}
