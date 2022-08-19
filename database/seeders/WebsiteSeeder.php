<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class WebsiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('website')->insert([
            'nama_website' => 'Suma Jaya Berkah',
            'tagline'=>'Souvenir,Branding,Produk ART',
            'contact' => '085265889195',
            'address' => 'Jl. Lintas Sumatera KM 4 Sawah Ilie, Nagari Saok Laweh, Kec. Kubung, Kab. Solok, Prov. Sumatera Barat, Indonesia. Kode Pos 27361',
            'icon' => '',
            'description'=>'Web Description'
        ]);
    }
}
