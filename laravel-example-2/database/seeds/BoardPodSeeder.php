<?php

use App\BoardPod;
use Illuminate\Database\Seeder;

class BoardPodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(BoardPod::class, 10)->create();
    }
}
