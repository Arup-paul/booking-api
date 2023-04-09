<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\ApartmentFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(RoleSeeder::class);
        $this->call(AdminUserSeeder::class);
        $this->call(PermissionSeeder::class);


        $this->call(CountryTableSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(GeoobjectSeeder::class);
//        $this->call(UserSeeder::class);
//        $this->call(PropertySeeder::class);
//        $this->call(ApartmentSeeder::class);

        $this->call(FacilityCategorySeeder::class);
        $this->call(FacilitySeeder::class);

    }
}
