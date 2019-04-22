<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $articles = [1,3,4,4,4,4,4,4,4,4,4,5,5,5,5,5,5,5,5,5,5,5,5,5,6,6,6,6,6,6,6,6,6,6];
        $authors = ["傑西卡 普林斯頓","Sam Kromstain","晶鑽醫美整形團隊","Edna Barton","盧杰明醫師","PSA NO.1","PSA NO.2","PSA NO.3"];
        $author_deses = [
            [1,3,"&lt;p&gt;Mega positive shop assistant always ready to help you make the right choice and charm you with a smile.&lt;/p&gt;","",""],
            [1,3,"&lt;p&gt;Mega positive shop assistant always ready to help you make the right choice and charm you with a smile.&lt;/p&gt;","",""],
            [2,3,"&lt;p&gt;Wholesale manager. Contact him if you want to buy a batch of the products offered at our store. &lt;/p&gt;","",""],
            [2,3,"&lt;p&gt;Wholesale manager. Contact him if you want to buy a batch of the products offered at our store. &lt;/p&gt;","",""],
            [2,1,"&lt;p&gt;批發經理。 如果您想購買我們商店提供的一批產品，請與他聯繫。&lt;/p&gt;","",""],
            [3,1,"&lt;p&gt;&lt;br&gt;&lt;/p&gt;","",""],
            [4,3,"&lt;p&gt;Quality control manager. Her mission is to check the products we ship and settle quality issues if any.&lt;/p&gt;","",""],
            [4,3,"&lt;p&gt;Quality control manager. Her mission is to check the products we ship and settle quality issues if any.&lt;/p&gt;","",""],
            [4,1,"&lt;p&gt;質量控制經理。 她的任務是檢查我們運送的產品並解決質量問題。&lt;/p&gt;","",""],
            [1,1,"&lt;p&gt;超級積極的店員隨時準備幫助你做出正確的選擇，並以微笑魅惑你。&lt;/p&gt;","",""],
            [5,1,"","",""],
            [6,1,"","",""],
            [7,1,"","",""],
            [8,1,"","",""]
        ];
        $categories = [1,2,3,4];
        $category_deses = ['整形手術','針劑注射','其他醫美相關','雷射相關療程'];

        $blog_articles = DB::table('blog_articles');
        $blog_article_descriptions = DB::table('blog_article_descriptions');
        $blog_article_authors = DB::table('blog_article_authors');
        $blog_article_author_descriptions = DB::table('blog_article_author_descriptions');
        $blog_article_categories = DB::table('blog_article_categories');
        $blog_article_category_descriptions = DB::table('blog_article_category_descriptions');
        $blog_article_views = DB::table('blog_article_views');

        $blog_articles->truncate();
        $blog_article_descriptions->truncate();
        $blog_article_authors->truncate();
        $blog_article_author_descriptions->truncate();
        $blog_article_categories->truncate();
        $blog_article_category_descriptions->truncate();
        $blog_article_views->truncate();

        foreach ($articles as $article) {
            $id = $blog_articles->insertGetId(['author_id' => $article, 'image_id' => 1]);
            $blog_article_views->insert(['article_id'=> $id]);
        }

        foreach ($authors as $author) {
            $blog_article_authors->insert(['name' => $author]);
        }

        foreach ($author_deses as $des) {
            $blog_article_author_descriptions->insert([
                'author_id' => $des[0],
                'language_id' => $des[1],
                'description' => $des[2],
                'meta_description' => $des[3],
                'meta_keyword' => $des[4],
            ]);
        }

        foreach ($categories as $cate) {
            $blog_article_categories->insert(['status' => 1]);
        }

        foreach ($category_deses as $i => $cate_des) {
            $blog_article_category_descriptions->insert(['language_id' => 1, 'name' => $cate_des, 'category_id'=> $categories[$i]]);
        }

        // Eloquent::unguard();

        // $path = './database/seeds/article.sql';
        // DB::unprepared(file_get_contents($path));
        // $this->command->info('blog_article_descriptions table seeded!');
    }
}
