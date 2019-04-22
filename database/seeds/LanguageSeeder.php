<?php

use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\Language::truncate();

        $items = [
            ['name' => '繁體中文', 'code' => 'zh_tw'],
            ['name' => '简体中文', 'code' => 'zh_cn'],
            ['name' => 'English', 'code' => 'en'],
        ];

        foreach($items as $item){
            App\Models\Language::create($item);
        }
    }
}
