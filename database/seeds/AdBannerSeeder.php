<?php

use Illuminate\Database\Seeder;

class AdBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\AdBanner::truncate();

        $items = [
            ['name' => '第一個Banner', 'title' => '', 'link'=> 'https://www.google.com.au'],
            ['name' => '第二個Banner', 'title' => '', 'link'=> 'https://www.google.com.au'],
            ['name' => '第三個Banner', 'title' => '', 'link'=> ''],
        ];

        foreach($items as $item){
            App\Models\AdBanner::create($item);
        }
    }
}
