<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->resource('sms', SmsKingController::class);

    // upload for wang editor
    $router->any('/upload/product/image', 'UploadController@image');
    $router->any('/mysqldump', 'DatabaseController@mysqldump');

    $router->group([
        'prefix'        => 'maintainer',
    ], function(Router $router2) {

        $router2->resource('customers', CustomerController::class);
        $router2->resource('customers_watchlist', CustomerWatchlistController::class);
        $router2->resource('customers_watchlist_article', CustomerWatchlistArticleController::class);

        $router2->resource('countries', CountryController::class);

        $router2->resource('products', ProductController::class);
        $router2->resource('products_description', ProductDescriptionController::class);
        $router2->resource('products_category', ProductCategoryController::class);
        $router2->resource('products_brand', ProductBrandController::class);
        // $router2->resource('cart', CartController::class);
        $router2->resource('payment_order', PaymentOrderController::class);
        $router2->resource('ad_banner', AdBannerController::class);
        $router2->resource('ad_point_banner', AdPointBannerController::class);
        $router2->resource('ad_news', AdNewsController::class);
        $router2->any('ad_news_links', 'AdNewsController@links');
        $router2->resource('ad_event', AdEventController::class);

        $router2->resource('article_magazine', ArticleMagazineController::class);
        $router2->resource('article_chapter', ArticleChapterController::class);

        $router2->resource('setting_point_give', SettingPointGiveController::class);
        $router2->resource('setting_promotion', SettingPromotionController::class);
        
        
        

    });

});
