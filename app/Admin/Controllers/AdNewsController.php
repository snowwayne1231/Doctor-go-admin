<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use App\Models\AdNews;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\ArticlePsaChapter;

class AdNewsController extends Controller
{
    use HasResourceActions;

    static $header_title = '新聞管理';
    

    public function index(Content $content)
    {
        return $content
            ->header(self::$header_title)
            ->description('列表')
            ->body($this->grid());
    }

    public function show($id, Content $content)
    {
        return $content
            ->header(self::$header_title)
            ->description('詳細資料')
            ->body($this->detail($id));
    }

    public function edit($id, Content $content)
    {
        return $content
            ->header(self::$header_title)
            ->description('編輯')
            ->body($this->form($id)->edit($id));
    }

    public function create(Content $content)
    {
        return $content
            ->header(self::$header_title)
            ->description('建立新資料')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdNews);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');
        $grid->type('類型')->using([
            1 => '頭條',
            2 => '推薦',
            3 => '必讀',
            4 => '最新',
        ]);
        $grid->enable('啟用')->switch();
        $grid->title('標頭');
        $grid->headline('標題');
        $grid->sort('優先權')->sortable();

        $grid->updated_at('最後更新')->sortable();

        $grid->filter(function ($filter){
            $filter->equal('type', '類型')->select([
                1 => '頭條',
                2 => '推薦',
                3 => '必讀',
                4 => '最新',
            ]);

            // $filter->equal('enable', '啟用');
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(AdNews::findOrFail($id));
        $model = $show->getModel();
        
        $show->id('ID');
        $show->type('類型')->using([
            1 => '頭條',
            2 => '推薦',
            3 => '必讀',
            4 => '最新',
        ]);
        $show->enable('啟用')->using([1=>'開', 0=>'關']);
        $show->title('標頭');
        $show->headline('標題');
        $show->content('內容')->unescape()->as(function($content){
            return "<textarea readonly style='min-height: 160px; min-width: 400px;'>$content</textarea>";
        });
        $show->sort('優先權');

        $show->link_type('連結類型')->using([
            0 => '無',
            1 => '商品連結',
            2 => '商品品牌連結',
            3 => '商品分類連結',
            4 => '文章連結',
            5 => '其他',
        ]);

        $links = $this->getLinksByType($model->link_type);
        $options = [];
        foreach ($links as $val) {
            $options[$val['id']] = $val['text'];
        }
        // dd($options);
        $show->link_value('連結')->using($options);
        
        $show->created_at('創建時間');
        $show->updated_at('最後更新');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id = null)
    {
        $form = new Form(new AdNews);
        $model = $id
            ? AdNews::find($id)
            : null;

        $form->select('type', '類型')->options([
            1 => '頭條',
            2 => '推薦',
            3 => '必讀',
            4 => '最新',
        ]);
        $form->switch('enable', '啟用')->default(true);
        $form->text('title', '標頭');
        $form->text('headline', '標題');
        $form->textarea('content', '內容');
        $form->number('sort', '優先權')->default(1);

        $form->select('link_type','連結類型')->options([
            0 => '無',
            1 => '商品連結',
            2 => '商品品牌連結',
            3 => '商品分類連結',
            4 => '文章連結',
            5 => '其他',
        ])->default(0)->load('link_value', '/admin/maintainer/ad_news_links');
        
        // dd($model);
        if ($model) {
            $form->select('link_value', '連結')->options('/admin/maintainer/ad_news_links?q='.$model->link_type);
        } else {
            $form->select('link_value', '連結');
        }

        return $form;
    }

    public function links(Request $request) {
        $link_type = $request->get('q');
        return $this->getLinksByType($link_type);
    }

    public function getLinksByType($type) {
        $result = [];
        switch (intval($type)) {
            case 1:
                $products = Product::all();
                foreach ($products as $product) {
                    $des = $product->description;
                    $name = isset($des[0])
                        ? $des[0]['name']
                        : '';
                    $result[] = ['id' => $product->id, 'text' => $name];
                }
            break;
            case 2:
                $brands = ProductBrand::all();
                foreach ($brands as $brand) {
                    $name = $brand->name ?? '';
                    $result[] = ['id' => $brand->id, 'text' => $name];
                }
            break;
            case 3:
                $categories = ProductCategory::all();
                foreach ($categories as $cate) {
                    $des = $cate->description;
                    $name = isset($des[0])
                        ? $des[0]['name']
                        : '';
                    $result[] = ['id' => $cate->id, 'text' => $name];
                }
            break;
            case 4:
                $articles = ArticlePsaChapter::all();
                foreach ($articles as $arti) {
                    $name = $arti->title ?? '';
                    $result[] = ['id' => $arti->id, 'text' => $name];
                }
            break;
            case 5:
                $result = [
                    ['id' => '/tab-article', 'text' => 'PSA美醫指南'],
                    ['id' => '/redeemingcenter\/', 'text' => '點數中心'],
                    ['id' => '/productevent/new', 'text' => '新品專區'],
                    ['id' => '/productevent/discount', 'text' => '折扣專區'],
                    ['id' => '/productevent/primary', 'text' => '專屬特惠'],
                    ['id' => '/productevent/event', 'text' => '活動專區'],
                    ['id' => '/productbrand\/', 'text' => '品牌專區'],
                    ['id' => '/collectivebuying\/', 'text' => '團購專區'],
                ];
            break;
            default:
                $result = [];
        }
        return $result;
    }
}
