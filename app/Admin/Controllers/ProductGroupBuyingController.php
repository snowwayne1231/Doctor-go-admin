<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use App\Models\Product;
use App\Models\ProductGroupBuying;

class ProductGroupBuyingController extends Controller
{
    use HasResourceActions;

    static $header_title = '團購管理';


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
        $grid = new Grid(new ProductGroupBuying);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');

        $grid->product_description('產品名稱')->display(function($product){
            return $product['name'];
        });

        $grid->status('狀態')->using([
            '0' => '取消',
            '1' => '正常',
            '2' => '暫停',
            '5' => '結單',
        ]);
        
        $grid->time_start('開始時間');
        $grid->time_end('結束時間');
        
        
        $grid->updated_at('最後更新')->sortable();

        $grid->filter(function($filter){
        
            // $filter->like('name', '名稱');
            $filter->equal('status', '狀態')->select([ 1 => '正常', 0 => '停用']);
            
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
        $show = new Show(ProductGroupBuying::findOrFail($id));

        $show->id('ID');

        $show->product_description('產品')->as(function($product){
            return $product['name'];
        });

        $show->product('產品原價')->as(function($pp){
            return $pp['price'];
        });

        // $show->product_description('產品' ,function ($author) {
        //     $author->id('ID');
        //     $author->name('產品名稱');
        //     $author->description('產品描述');
        // });

        $show->status('狀態')->using([
            '0' => '取消',
            '1' => '正常',
            '2' => '暫停',
            '5' => '結單',
        ]);

        $show->time_start('開始時間');
        $show->time_end('結束時間');

        $show->discount_json('折扣')->unescape()->as(function($discount){
            $str = '';
            foreach ($discount as $val) {
                $str .= '<p>數量: '.$val['condition_quantity'];
                $str .= ', 單價: '.$val['price'];
                $str .= '</p>';
            }
            return $str;
        });
        
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
        $form = new Form(new ProductGroupBuying);

        $multipleArray = [];
        $products = Product::all();
        foreach ($products as $product) {
            $des = $product->description;
            $name = isset($des[0])
                ? $des[0]['name']
                : '';
            $multipleArray[$product->id] = $name;
        }

        $form->select('product_id', '產品')->options($multipleArray);

        // $form->text('organizer', '發起人名稱');
        $form->select('status', '狀態')->options([
            '0' => '取消',
            '1' => '正常',
            '2' => '暫停',
            '5' => '結單',
        ])->default(1);

        $form->datetime('time_start', '開始時間');
        $form->datetime('time_end', '結束時間');

        $form->table('discount_json', '折扣', function ($table) {
            $table->number('condition_quantity', '條件數量');
            $table->number('price', '單價');
            
        });

        return $form;
    }
}
