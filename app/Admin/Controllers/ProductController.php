<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use App\Models\Product;
use App\Models\ProductDescription;
use App\Models\ProductCategory;
use App\Models\ProductCategoryDescription;
use App\Models\ProductBrand;

class ProductController extends Controller
{
    use HasResourceActions;

    static $header_title = '商品資料管理';


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
            ->body($this->form()->edit($id));
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
        $grid = new Grid(new Product);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');

        // $grid->description('名稱')->view('admin/grid/product_name');
        $grid->description('名稱')->pluck('name')->map('ucwords')->implode(',')->limit(16);

        $grid->category_first_description('類別')->display(function($category){
            return $category['name'];
        });

        $grid->image('商品圖')->image(null,100,48);

        $grid->status('狀態')->using(['1' => '正常', '0' => '關閉']);
        // $grid->status('狀態')->switch([
        //     'on'  => ['value' => 1, 'text' => '正常', 'color' => 'primary'],
        //     'off' => ['value' => 0, 'text' => '關閉', 'color' => 'default'],
        // ]);

        $grid->quantity('數量')->sortable();
        // $grid->stock_status('庫存狀態')->display( function($status) {
        //     switch($status) {
        //         case '1': return '正常';
        //         default: return '缺貨';
        //     }
        // });

        $grid->price('售價')->sortable();
        $grid->point_can_be_discount('可抵點數')->sortable();
        $grid->viewed('熱度')->sortable();

        $grid->updated_at('最後更新')->sortable();


        $grid->filter(function($filter){
            $filter->disableIdFilter();
        
            // 在这里添加字段过滤器
            $filter->equal('status', '狀態')->select(['1' => '正常', '0' => '關閉']);
            $filter->equal('category_id', '類別')->select(ProductCategory::all()->pluck('name', 'id'));
            $filter->between('quantity', '數量');
            $filter->between('price', '價錢');
            $filter->between('point_can_be_discount', '可抵點數');
            $filter->gt('viewed', '熱度');
            $filter->date('updated_at', '最後更新日期');
        
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
        $show = new Show(Product::findOrFail($id));

        $show->id('ID');

        $show->image('主圖')->image();
        $show->image_detail('詳細內容圖片')->unescape()->as(function ($images){
            $str = '';
            if (isset($images)) {
                foreach ($images as $img) {
                    $str .= "<img src='/upload/$img' />";
                }
            }
            return $str;
        });

        $show->description('名稱與圖文詳情')->unescape()->as(function ($description) {
            $str = '';
            $description->map( function($des) use(&$str) {
                $str .= "<li style=\"border: 1px solid #999; padding: 5px; margin: 5px;\"><p style=\"border-bottom: 1px solid #999;\">".$des->language->name."</p><p>$des->name</p><div>$des->description</div></li>";
            });
            return "<ul style=\"list-style: none; padding: 0px;\"> $str </ul>";
        });

        $show->category_first_description('分類', function ($author) {
            $author->id('ID');
            $author->name('名稱');
            $author->description('分類詳細描述');

        });

        $show->brand_id('品牌')->as( function($brand) {
            if (isset($brand)) {
                return ProductBrand::find($brand)->name;
            }
            return '';
        });

        $show->status('狀態')->as( function($status) {
            switch($status) {
                case '1': return '正常';
                default: return '停用';
            }
        });

        $show->quantity('數量');

        $show->price('售價');
        $show->point_can_be_discount('可抵點數');

        $show->sort('優先權');
        $show->viewed('熱度');
        
        
        $show->created_at('創建時間');
        $show->updated_at('最後更新');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product);

        $form->tab('商品介紹', function($form){

            
            $form->hasMany('description', '名稱與內容', function (Form\NestedForm $form) {

                $model = $form->getForm()->model();

                $form->select('language_id', '語系')->options([
                    1 => '繁體中文',
                    2 => '简体中文',
                    3 => 'English',
                ])->default(1);

                $form->text('name', '名稱')->rules('required');
                $form->textarea('meta_description', '內容大綱');

                if ($model->id) {
                    $form->display('description', '圖文詳情')->with( function($value) {
                        return '<div>
                            <div class="kits" style="text-align: right;"></div>
                            <div class="html-content" style="max-height: 220px; overflow: auto;">'.$value.'</div>
                        </div>';
                    });

                    $form->display('id', '')->with( function($id) {
                        $html = '';
                        // ../../products_description/'.$id.'/edit
                        if ($id) {
                            $html = '<a style="text-align: center; display: block; cursor:pointer;"
                            onclick="javascript:$(\'button[type=submit]\').click(); setTimeout(function() {location.href=\'../products_description/'.$id.'/edit\';}, 100);"
                            >↑↑↑↑↑ 編輯圖文詳情 ↑↑↑↑↑</a>';
                        }
                        return $html;
                    });
                }
                
            })->mode('tab');

            // dd($form->model());
        
        })->tab('屬性數值', function($form){
            $categories = [];
            ProductCategory::all()->pluck('description')->each(function($description) use (&$categories){
                $first = $description->first();
                $categories[$first->id] = $first->name;
            })->toArray();
            
            $form->select('category_id', '分類')->options($categories);

            $brands = ProductBrand::all()->sortByDesc('sort')->pluck('name', 'id');
            $form->select('brand_id', '品牌')->options($brands);


            $form->select('status', '狀態')->options([
                1 => '正常',
                0 => '關閉',
            ])->setWidth(4)->default(1);
            
            $form->number('quantity', '數量');
            // $form->select('stock_status', '庫存狀態')->options([
            //     1 => '正常',
            //     0 => '缺貨',
            // ])->setWidth(4)->default(1);
    
            $form->number('price', '價錢');
            $form->number('price_forshow', '顯示用原價錢');
            $form->number('point_reward', '紅利點數');
            $form->number('point_can_be_discount', '可折抵點數');
            $form->number('sort', '優先權')->default(1);
            // $form->select('tax_id', '稅賦')->options([ 1 => '預設', 2 => '消費稅5%' ])->default(1);
        })->tab('圖片', function($form){
            $form->image('image', '主圖')->uniqueName();
            $form->multipleImage('image_detail', '詳細內容圖片')->removable();
        });

        $form->saved(function($form){
            $after = $form->input('after-save');
            if (isset($after)) {

            } else {
                return redirect('/admin/maintainer/products');
            }
        });
        
        return $form;
    }
}
