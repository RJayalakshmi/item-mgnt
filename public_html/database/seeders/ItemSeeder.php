<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ItemSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $position = ['LEFT', 'RIGHT'];
        // check if table users is empty
        while (DB::table('items')->get()->count() <= 10) {
            DB::table('items')->insert([
                'name' => Str::random(10),
                'position' => $position[rand(0, 1)],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }

}
