<?php

use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Currecny::truncate();

        $items = [
            ['name' => '新台幣', 'title' => '新台幣', 'code' => 'TWD', 'status' => 1, 'symbol_left' => '$', 'symbol_right' => '元'],
            ['name' => '人民币', 'title' => '人民币', 'code' => 'CNY', 'status' => 1, 'symbol_left' => '$', 'symbol_right' => '元'],
            ['name' => '美元', 'title' => '美元', 'code' => 'USD', 'status' => 1, 'symbol_left' => '$', 'symbol_right' => 'Dollars'],
        ];

        foreach($items as $item){
            App\Models\Currecny::create($item);
        }
    }
}
