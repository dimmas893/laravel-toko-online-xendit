<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 10) as $index) {
            DB::table('produk')->insert([
                'kode_produk' => rand(1, 5000000000),
                'nama_produk' => 'Baju Kaos Pria Lengan Pendek' . rand(0, 500),
                'merek' => 'Mizuno',
                'slug' => 'produk' . rand(1, 1000000000),
                'kategori_id' => rand(1, 10),
                'satuan' => 'Satuan',
                'harga' => rand(10000, 1000000),
                'harga_jual' => rand(1000, 10000),
                'jumlah' => rand(1, 100),
                'gambar_utama' => rand(1, 10) . '.jpg',
                'berat' => rand(200, 600),
                'deskripsi' => 'Deskripsi',
                'jenis_jual' => rand(1, 2),
                'created_by' => '1',
                'updated_by' => '1',
            ]);
        }
    }
}
