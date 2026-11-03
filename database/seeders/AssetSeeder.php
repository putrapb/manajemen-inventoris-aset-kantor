<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::query()->pluck('id', 'nama');

        $assets = [
            ['nama' => 'Laptop Dell Latitude 5440', 'kode' => 'AST-001', 'kategori' => 'Elektronik', 'lokasi' => 'Lantai 2', 'status' => 'tersedia', 'tanggal_beli' => '2024-02-12', 'nilai' => 14500000],
            ['nama' => 'Proyektor Epson EB-X06', 'kode' => 'AST-002', 'kategori' => 'Elektronik', 'lokasi' => 'Ruang Meeting', 'status' => 'dipinjam', 'tanggal_beli' => '2023-08-05', 'nilai' => 7200000],
            ['nama' => 'Printer Canon PIXMA G2770', 'kode' => 'AST-003', 'kategori' => 'Elektronik', 'lokasi' => 'Lantai 1', 'status' => 'tersedia', 'tanggal_beli' => '2024-05-20', 'nilai' => 2850000],
            ['nama' => 'AC Split Sharp 1 PK', 'kode' => 'AST-004', 'kategori' => 'Elektronik', 'lokasi' => 'Ruang Direksi', 'status' => 'rusak', 'tanggal_beli' => '2021-11-08', 'nilai' => 4100000],
            ['nama' => 'Meja Kerja Staf', 'kode' => 'AST-005', 'kategori' => 'Furnitur', 'lokasi' => 'Lantai 1', 'status' => 'tersedia', 'tanggal_beli' => '2022-03-15', 'nilai' => 1750000],
            ['nama' => 'Kursi Ergonomis', 'kode' => 'AST-006', 'kategori' => 'Furnitur', 'lokasi' => 'Lantai 2', 'status' => 'tersedia', 'tanggal_beli' => '2023-01-10', 'nilai' => 1350000],
            ['nama' => 'Lemari Arsip Besi', 'kode' => 'AST-007', 'kategori' => 'Furnitur', 'lokasi' => 'Gudang Utama', 'status' => 'tersedia', 'tanggal_beli' => '2020-07-22', 'nilai' => 2350000],
            ['nama' => 'Sofa Ruang Tamu', 'kode' => 'AST-008', 'kategori' => 'Furnitur', 'lokasi' => 'Lobby', 'status' => 'dipinjam', 'tanggal_beli' => '2022-09-01', 'nilai' => 5600000],
            ['nama' => 'Whiteboard 120x90', 'kode' => 'AST-009', 'kategori' => 'Peralatan Kantor', 'lokasi' => 'Ruang Meeting', 'status' => 'tersedia', 'tanggal_beli' => '2023-04-18', 'nilai' => 650000],
            ['nama' => 'Layar Proyektor Tripod', 'kode' => 'AST-010', 'kategori' => 'Peralatan Kantor', 'lokasi' => 'Ruang Meeting', 'status' => 'tersedia', 'tanggal_beli' => '2023-06-11', 'nilai' => 900000],
            ['nama' => 'Mesin Fotokopi Xerox', 'kode' => 'AST-011', 'kategori' => 'Peralatan Kantor', 'lokasi' => 'Lantai 1', 'status' => 'rusak', 'tanggal_beli' => '2019-12-03', 'nilai' => 18500000],
            ['nama' => 'Telepon Kantor Panasonic', 'kode' => 'AST-012', 'kategori' => 'Peralatan Kantor', 'lokasi' => 'Resepsionis', 'status' => 'tersedia', 'tanggal_beli' => '2022-10-27', 'nilai' => 750000],
            ['nama' => 'Dispenser Air Galon', 'kode' => 'AST-013', 'kategori' => 'Peralatan Kantor', 'lokasi' => 'Pantry', 'status' => 'tersedia', 'tanggal_beli' => '2023-02-14', 'nilai' => 1600000],
            ['nama' => 'Monitor LG 27 inci', 'kode' => 'AST-014', 'kategori' => 'Elektronik', 'lokasi' => 'Lantai 2', 'status' => 'dipinjam', 'tanggal_beli' => '2024-01-30', 'nilai' => 3200000],
            ['nama' => 'Meja Rapat Besar', 'kode' => 'AST-015', 'kategori' => 'Furnitur', 'lokasi' => 'Ruang Meeting', 'status' => 'tersedia', 'tanggal_beli' => '2021-05-19', 'nilai' => 6800000],
        ];

        foreach ($assets as $asset) {
            Asset::updateOrCreate(
                ['kode' => $asset['kode']],
                [
                    'nama' => $asset['nama'],
                    'category_id' => $categories[$asset['kategori']],
                    'lokasi' => $asset['lokasi'],
                    'status' => $asset['status'],
                    'tanggal_beli' => $asset['tanggal_beli'],
                    'nilai' => $asset['nilai'],
                ],
            );
        }
    }
}
