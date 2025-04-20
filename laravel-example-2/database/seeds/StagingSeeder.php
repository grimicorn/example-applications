<?php

use Illuminate\Database\Seeder;

class StagingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (app()->environment() !== 'staging') {
            return;
        }

        $this->call(DeveloperAccountSeeder::class);
        $this->call(TestAccountSeeder::class);
        $this->call(BoardSeeder::class);
        $this->call(PodSeeder::class);
        $this->call(MachineSeeder::class);
        $this->call(JobSeeder::class);
        $this->call(BoardPodSeeder::class);
    }
}
