<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function validPayload(Category $category, array $overrides = []): array
    {
        return array_merge([
            'nama' => 'Laptop Baru',
            'kode' => 'AST-TEST-001',
            'category_id' => $category->id,
            'lokasi' => 'Lantai 1',
            'status' => Asset::STATUS_TERSEDIA,
            'tanggal_beli' => '2025-01-01',
            'nilai' => 1000000,
        ], $overrides);
    }

    public function test_staff_can_view_assets_list_and_detail(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
        $asset = Asset::factory()->create();

        $this->actingAs($staff)->get(route('assets.index'))->assertOk();
        $this->actingAs($staff)->get(route('assets.show', $asset))->assertOk();
    }

    public function test_staff_cannot_access_admin_asset_routes(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
        $category = Category::factory()->create();
        $asset = Asset::factory()->create(['category_id' => $category->id]);

        $this->actingAs($staff)->get(route('assets.create'))->assertForbidden();
        $this->actingAs($staff)->post(route('assets.store'), $this->validPayload($category))->assertForbidden();
        $this->actingAs($staff)->get(route('assets.edit', $asset))->assertForbidden();
        $this->actingAs($staff)->put(route('assets.update', $asset), $this->validPayload($category))->assertForbidden();
        $this->actingAs($staff)->delete(route('assets.destroy', $asset))->assertForbidden();

        $this->assertDatabaseCount('assets', 1);
    }

    public function test_admin_can_view_create_and_edit_forms(): void
    {
        $admin = User::factory()->admin()->create();
        $asset = Asset::factory()->create();

        $this->actingAs($admin)->get(route('assets.create'))->assertOk();
        $this->actingAs($admin)->get(route('assets.edit', $asset))->assertOk();
    }

    public function test_admin_can_create_update_and_delete_asset(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $this->actingAs($admin)
            ->post(route('assets.store'), $this->validPayload($category))
            ->assertRedirect(route('assets.index'));

        $asset = Asset::query()->where('kode', 'AST-TEST-001')->firstOrFail();

        $this->actingAs($admin)
            ->put(route('assets.update', $asset), $this->validPayload($category, [
                'nama' => 'Laptop Diperbarui',
                'nilai' => 2500000,
            ]))
            ->assertRedirect(route('assets.index'));

        $this->assertDatabaseHas('assets', [
            'id' => $asset->id,
            'nama' => 'Laptop Diperbarui',
            'nilai' => 2500000,
        ]);

        $this->actingAs($admin)
            ->delete(route('assets.destroy', $asset))
            ->assertRedirect(route('assets.index'));

        $this->assertSoftDeleted('assets', ['id' => $asset->id]);
    }

    public function test_soft_deleted_asset_is_hidden_from_list_detail_and_api(): void
    {
        $admin = User::factory()->admin()->create();
        $asset = Asset::factory()->create(['kode' => 'AST-SOFT-DELETE']);

        $this->actingAs($admin)->delete(route('assets.destroy', $asset))->assertRedirect(route('assets.index'));

        $this->assertSoftDeleted('assets', ['id' => $asset->id]);

        $this->actingAs($admin)
            ->get(route('assets.index'))
            ->assertDontSee('AST-SOFT-DELETE');

        $this->actingAs($admin)
            ->get(route('assets.show', $asset->id))
            ->assertNotFound();

        $this->getJson('/api/assets')
            ->assertOk()
            ->assertJsonMissing(['kode' => 'AST-SOFT-DELETE']);
    }
}
