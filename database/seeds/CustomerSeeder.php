<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        App\Models\CustomerGroup::truncate();

        $items = [
            ['name' => '一般', 'description' => '一般'],
            ['name' => '業內', 'description' => '業內'],
            ['name' => '員工', 'description' => '員工'],
            ['name' => '老闆', 'description' => '老闆'],
            ['name' => '管理者', 'description' => '管理者'],
        ];

        foreach($items as $item){
            App\Models\CustomerGroup::create($item);
        }
    }
}
