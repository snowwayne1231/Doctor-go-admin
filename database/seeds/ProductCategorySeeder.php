<?php

use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Models\ProductCategory::truncate();
        App\Models\ProductCategoryDescription::truncate();

        $items = [
            ['column' => 1],
            ['column' => 2],
            ['column' => 3],
            ['column' => 4],
            ['column' => 5],
            ['column' => 6],
        ];

        foreach($items as $item){
            App\Models\ProductCategory::create($item);
        }

        $items2 = [
            ['top_id' => 1, 'parent_id' => 1, 'column' => 1],
            ['top_id' => 1, 'parent_id' => 1, 'column' => 2],
            ['top_id' => 2, 'parent_id' => 2, 'column' => 1],
            ['top_id' => 2, 'parent_id' => 2, 'column' => 2],
            ['top_id' => 3, 'parent_id' => 3, 'column' => 1],
            ['top_id' => 3, 'parent_id' => 3, 'column' => 2],
            ['top_id' => 3, 'parent_id' => 3, 'column' => 3],
            ['top_id' => 4, 'parent_id' => 4, 'column' => 1],
            ['top_id' => 4, 'parent_id' => 4, 'column' => 2],
            ['top_id' => 5, 'parent_id' => 5, 'column' => 1],
            ['top_id' => 6, 'parent_id' => 6, 'column' => 1],
            ['top_id' => 6, 'parent_id' => 6, 'column' => 2],
        ];

        foreach($items2 as $item){
            App\Models\ProductCategory::create($item);
        }

        function getKey($id) {
            switch ($id) {
                case 2: return 'equipment';
                case 3: return 'consumable';
                case 6: return 'second';
                case 1: return 'maintenance';
                default: return '';
            }
        }
        
        function getName($id) {
            switch ($id) {
                case 1: return '保養品系列';
                case 2: return '醫美儀器設備';
                case 3: return '醫美耗材';
                case 4: return '醫美藥品';
                case 5: return '其他類別';
                case 6: return '二手商品';
                case 7: return '臉部保養';
                case 8: return '身體保養';
                case 9: return '雷射系列';
                case 10: return '抽脂&amp;溶脂系列';
                case 11: return '隆鼻';
                case 12: return '針頭';
                case 13: return '隆乳';
                case 14: return '探頭';
                case 15: return '注射系列';
                case 16: return '口服系列';
                case 17: return '其他醫美商品';
                case 18: return '二手儀器設備';
                case 19: return '其他二手品';
                default: return '';
            }
        }

        function getDes($id) {
            switch ($id) {
                case 7: return '美，是未來非常重要的趨勢，也是一個龐大的市場。';
                case 8: return '醫美耗材';
                case 9: return 'backgr';
                case 10: return '抽脂&amp;溶脂系列';
                case 11: return '臉部保養';
                case 12: return '身體保養';
                case 13: return '雷射手術是現在最平凡也是一般人最常使用醫美療程之一，';
                case 14: return '女性愛美是天性，擁有S型曲線的身材，更是每個女人夢寐以求的願望，';
                case 15: return '擁有深邃的輪廓，是多少人心中的夢想，';
                case 16: return '針頭';
                case 17: return '隆乳';
                case 18: return '口服系列';
                case 19: return '其他醫美商品';
                default: return '';
            }
        }

        function getTitle($id) {
            switch ($id) {
                case 1: return '保養品系列';
                case 2: return '醫美儀器設備';
                case 3: return '醫美耗材';
                case 4: return '醫美藥品';
                case 5: return '其他類別';
                case 6: return '二手商品';
                case 7: return '臉部保養';
                case 8: return '身體保養';
                case 9: return '雷射系列';
                case 10: return '抽脂&amp;溶脂系列';
                case 11: return '隆鼻';
                case 12: return '針頭';
                case 13: return '隆乳';
                case 14: return '探頭';
                case 15: return '注射系列';
                case 16: return '口服系列';
                case 17: return '其他醫美商品';
                case 18: return 'index.php?route=information/contact';
                case 19: return '其他二手品';
                default: return '';
            }
        }

        $categories = App\Models\ProductCategory::all();
        foreach($categories as $cate) {
            $item = [
                'category_id' => $cate->id,
                'language_id' => 1,
                'name' => getName($cate->id),
                'description' => getDes($cate->id),
                'title' => getTitle($cate->id),
            ];
            App\Models\ProductCategoryDescription::create($item);
            $cate->key = getKey($cate->id);
            $cate->save();
        }
    }
}
