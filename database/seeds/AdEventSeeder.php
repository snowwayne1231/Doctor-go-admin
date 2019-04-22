<?php

use Illuminate\Database\Seeder;

class AdEventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\AdEvent::truncate();

        $items = [
            ['name' => '新品專區', 'key' => 'new'],
            ['name' => '折扣專區', 'key' => 'discount'],
            ['name' => '專屬特惠', 'key' => 'primary'],
            ['name' => '活動專區', 'key' => 'event'],
            ['name' => '推薦商品', 'key' => 'recommend'],
        ];

        foreach($items as $item){
            App\Models\AdEvent::create($item);
        }
    }
}
