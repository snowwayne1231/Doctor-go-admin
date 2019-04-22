<?php

use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Tax::truncate();

        $items = [
            ['title' => '免稅', 'zone_id' => 1, 'rate' => 0],
            ['title' => '消費稅', 'zone_id' => 1, 'rate' => 0.05],
        ];

        foreach($items as $item){
            App\Models\Tax::create($item);
        }
    }
}
