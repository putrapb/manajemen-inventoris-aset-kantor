<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetValidationTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(Category $category, array $overrides = []): array
    {
        return array_merge([
            'nama' => 'Aset Valid',
            'kode' => 'AST-VALID-001',
            'category_id' => $category->id,
            'lokasi' => 'Gudang',
            'status' => Asset::STATUS_TERSEDIA,
            'tanggal_beli' => '2025-01-01',
            'nilai' => 0,
        ], $overrides);
    }

    public function test_nama_is_required(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->post(route('assets.store'), $this->validPayload($category, ['nama' => '']))
            ->assertSessionHasErrors('nama');
    }

    public function test_kode_must_be_unique(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        Asset::factory()->create(['kode' => 'AST-DUPLIKAT']);

        $this->actingAs($admin)
            ->post(route('assets.store'), $this->validPayload($category, ['kode' => 'ast-duplikat']))
            ->assertSessionHasErrors('kode');
    }

    public function test_nilai_must_be_numeric_and_not_negative(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->post(route('assets.store'), $this->validPayload($category, ['nilai' => 'bukan-angka']))
            ->assertSessionHasErrors('nilai');

        $this->actingAs($admin)
            ->post(route('assets.store'), $this->validPayload($category, ['nilai' => -1]))
            ->assertSessionHasErrors('nilai');
    }

    public function test_category_must_exist(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->post(route('assets.store'), $this->validPayload($category, ['category_id' => 999999]))
            ->assertSessionHasErrors('category_id');
    }

    public function test_status_must_be_valid(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->post(route('assets.store'), $this->validPayload($category, ['status' => 'hilang']))
            ->assertSessionHasErrors('status');
    }

    public function test_update_keeps_its_own_kode_valid(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $asset = Asset::factory()->create(['kode' => 'AST-TETAP-001', 'category_id' => $category->id]);

        $this->actingAs($admin)
            ->put(route('assets.update', $asset), $this->validPayload($category, [
                'kode' => 'AST-TETAP-001',
                'nama' => 'Nama Baru',
            ]))
            ->assertRedirect(route('assets.index'));

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'nama' => 'Nama Baru',
        ]);
    }
}
