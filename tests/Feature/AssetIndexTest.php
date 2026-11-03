<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AssetIndexTest extends TestCase
{
    use RefreshDatabase;

    private function actingUser(): User
    {
        return User::factory()->create();
    }

    private function itemNames($response): array
    {
        return collect($response->viewData('assets')->items())->pluck('nama')->all();
    }

    public function test_asset_list_is_paginated_ten_per_page(): void
    {
        Asset::factory()->count(12)->create();

        $response = $this->actingAs($this->actingUser())->get(route('assets.index'));

        $response->assertOk();
        $this->assertCount(10, $response->viewData('assets')->items());
        $this->assertSame(12, $response->viewData('assets')->total());
    }

    public function test_asset_list_can_be_searched_by_name_or_code(): void
    {
        $category = Category::factory()->create();
        Asset::factory()->create(['nama' => 'Laptop Khusus', 'kode' => 'AST-CARI-001', 'category_id' => $category->id]);
        Asset::factory()->create(['nama' => 'Kursi Biasa', 'kode' => 'AST-CARI-002', 'category_id' => $category->id]);

        $user = $this->actingUser();

        $byName = $this->actingAs($user)->get(route('assets.index', ['search' => 'Laptop Khusus']));
        $this->assertSame(['Laptop Khusus'], $this->itemNames($byName));

        $byCode = $this->actingAs($user)->get(route('assets.index', ['search' => 'AST-CARI-002']));
        $this->assertSame(['Kursi Biasa'], $this->itemNames($byCode));
    }

    public function test_asset_list_can_be_filtered_by_category_and_status(): void
    {
        $elektronik = Category::factory()->create(['nama' => 'Elektronik']);
        $furnitur = Category::factory()->create(['nama' => 'Furnitur']);

        Asset::factory()->create([
            'nama' => 'Laptop Filter',
            'category_id' => $elektronik->id,
            'status' => Asset::STATUS_TERSEDIA,
        ]);
        Asset::factory()->create([
            'nama' => 'Kursi Filter',
            'category_id' => $furnitur->id,
            'status' => Asset::STATUS_RUSAK,
        ]);

        $user = $this->actingUser();

        $byCategory = $this->actingAs($user)->get(route('assets.index', ['category_id' => $elektronik->id]));
        $this->assertSame(['Laptop Filter'], $this->itemNames($byCategory));

        $byStatus = $this->actingAs($user)->get(route('assets.index', ['status' => Asset::STATUS_RUSAK]));
        $this->assertSame(['Kursi Filter'], $this->itemNames($byStatus));
    }

    public function test_category_relationship_is_eager_loaded_to_avoid_n_plus_one(): void
    {
        Asset::factory()->count(15)->create();

        DB::flushQueryLog();
        DB::enableQueryLog();

        $this->actingAs($this->actingUser())->get(route('assets.index'))->assertOk();

        $categoryQueries = collect(DB::getQueryLog())
            ->filter(fn (array $query) => str_contains($query['query'], 'categories'))
            ->count();

        DB::disableQueryLog();

        $this->assertLessThanOrEqual(2, $categoryQueries);
    }
}
