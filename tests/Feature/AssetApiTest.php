<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_paginated_assets_with_required_fields(): void
    {
        $category = Category::factory()->create(['nama' => 'Elektronik']);
        Asset::factory()->count(15)->create([
            'category_id' => $category->id,
            'status' => Asset::STATUS_TERSEDIA,
        ]);

        $response = $this->getJson('/api/assets');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['kode', 'nama_kategori', 'status'],
                ],
                'links',
                'meta',
            ]);

        $this->assertCount(10, $response->json('data'));
        $this->assertSame(15, $response->json('meta.total'));
        $this->assertSame('Elektronik', $response->json('data.0.nama_kategori'));
    }

    public function test_api_can_filter_assets_by_status(): void
    {
        $category = Category::factory()->create();
        Asset::factory()->count(3)->create([
            'category_id' => $category->id,
            'status' => Asset::STATUS_DIPINJAM,
        ]);
        Asset::factory()->count(2)->create([
            'category_id' => $category->id,
            'status' => Asset::STATUS_TERSEDIA,
        ]);

        $response = $this->getJson('/api/assets?status=dipinjam');

        $response->assertOk();
        $this->assertSame(3, $response->json('meta.total'));
        $this->assertSame(
            [Asset::STATUS_DIPINJAM],
            array_values(array_unique(array_column($response->json('data'), 'status'))),
        );
    }

    public function test_api_rejects_invalid_status_filter(): void
    {
        $this->getJson('/api/assets?status=hilang')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('status');
    }
}
