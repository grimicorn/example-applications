<?php

namespace Database\Seeders;

use App\Imports\LocationImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class GoogleMapLinkToLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(
            new LocationImport,
            base_path('data/google-map-links.csv')
        );
    }
}
