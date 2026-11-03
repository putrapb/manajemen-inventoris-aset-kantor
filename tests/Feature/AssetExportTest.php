<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_export_filtered_assets_as_csv(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_STAFF]);
        $category = Category::factory()->create(['nama' => 'Elektronik']);

        Asset::factory()->create([
            'nama' => 'Laptop Tersedia',
            'kode' => 'AST-EXPORT-001',
            'category_id' => $category->id,
            'lokasi' => 'Lantai 2',
            'status' => Asset::STATUS_TERSEDIA,
            'tanggal_beli' => '2025-01-01',
            'nilai' => 1500000,
        ]);

        Asset::factory()->create([
            'nama' => 'Laptop Rusak',
            'kode' => 'AST-EXPORT-002',
            'category_id' => $category->id,
            'status' => Asset::STATUS_RUSAK,
        ]);

        $response = $this->actingAs($staff)->get(route('assets.export', ['status' => Asset::STATUS_TERSEDIA]));

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('attachment;', $response->headers->get('content-disposition'));

        $content = $response->streamedContent();

        $this->assertStringContainsString('Kode,Nama,Kategori,Lokasi,Status,"Tanggal Beli",Nilai', $content);
        $this->assertStringContainsString('AST-EXPORT-001', $content);
        $this->assertStringContainsString('Laptop Tersedia', $content);
        $this->assertStringContainsString('Elektronik', $content);
        $this->assertStringNotContainsString('AST-EXPORT-002', $content);
    }

    public function test_csv_export_escapes_formula_injection(): void
    {
        $staff = User::factory()->create(['role' => User::ROLE_STAFF]);

        Asset::factory()->create([
            'nama' => '=HYPERLINK("http://example.com")',
            'kode' => 'AST-EXPORT-003',
        ]);

        $content = $this->actingAs($staff)
            ->get(route('assets.export'))
            ->streamedContent();

        $this->assertStringContainsString("'=HYPERLINK", $content);
    }
}
