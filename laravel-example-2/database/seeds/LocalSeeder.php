<?php

use Illuminate\Database\Seeder;
use App\Domain\Supports\Geocoder;

class LocalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment() !== 'local') {
            return;
        }

        $this->call(DeveloperAccountSeeder::class);
        $this->call(TestAccountSeeder::class);
        $this->call(BoardSeeder::class);
        $this->call(PodSeeder::class);
        $this->call(MachineSeeder::class);
        $this->call(JobSeeder::class);
        $this->call(BoardPodSeeder::class);
        $this->call(MachinePodSeeder::class);
    }
}
