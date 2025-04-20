<?php

use App\Board;
use Illuminate\Database\Seeder;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Board::class)->create(['name' => 'Board 1']);
        factory(Board::class)->create(['name' => 'Board 2']);
        factory(Board::class)->create(['name' => 'Board 3']);
    }
}
