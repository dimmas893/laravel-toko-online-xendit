<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1, 10) as $index)  {
            DB::table('kategori')->insert([
                'nama_kategori' =>'Baju Kaos'.rand(1,1000),
                'slug' => 'kategori'.rand(1,10),
                'icon'=>rand(1,10).'.jpg',
                'created_by'=>'1',
                'updated_by'=>'1',
            ]);
        }
    }
}
