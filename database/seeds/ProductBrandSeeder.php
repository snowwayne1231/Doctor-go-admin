<?php

use Illuminate\Database\Seeder;

class ProductBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        App\Models\ProductBrand::truncate();

        $items = [
            ['name' => 'Ellanse', 'sort' => 2],
            ['name' => 'UTIMS', 'sort' => 2],
            ['name' => 'body-jet', 'sort' => 2],
            ['name' => 'RE.O', 'sort' => 2],
            ['name' => 'Restylane', 'sort' => 2],
            ['name' => 'Juvederm', 'sort' => 2],
            ['name' => 'Test', 'sort' => 1],
        ];

        foreach($items as $item){
            App\Models\ProductBrand::create($item);
        }
    }
}
