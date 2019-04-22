<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class ApiKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        //

        App\Models\ApiKey::truncate();

        $items = [
            ['name' => 'lv_01', 'key' => str_random(32)],
            ['name' => 'lv_02', 'key' => str_random(28)],
            ['name' => 'lv_03', 'key' => str_random(28)],
            ['name' => 'lv_04', 'key' => str_random(24)],
            ['name' => 'lv_05', 'key' => str_random(24)],
        ];

        foreach($items as $item){
            App\Models\ApiKey::create($item);
        }
    }
}
