<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            WebsiteSeeder::class,
            ProdukSeeder::class,
            KategoriSeeder::class,
            // IndoRegionProvinceSeeder::class,
            // IndoRegionRegencySeeder::class,
            // IndoRegionDistrictSeeder::class,
            // IndoRegionVillageSeeder::class,
        ]);
    }
}
