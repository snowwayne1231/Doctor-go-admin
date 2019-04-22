<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(ApiKeySeeder::class);
        $this->call(AdBannerSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(ProductBrandSeeder::class);
        // $this->call(ProductSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CurrencySeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(TaxSeeder::class);
        $this->call(AdEventSeeder::class);
        // $this->call(BlogArticleSeeder::class);

    }
}
