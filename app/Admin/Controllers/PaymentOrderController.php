<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use App\Models\PaymentOrder;
use App\Models\Product;
use App\Models\ProductDescription;
use App\Models\Customer;

class PaymentOrderController extends Controller
{
    use HasResourceActions;

    static $header_title = '訂單管理';
    

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
        $grid = new Grid(new PaymentOrder);

        $grid->model()->orderBy('id', 'desc');

        $grid->id('ID');
        $grid->customer_telephone('客戶手機號')->sortable();
        $grid->customer_name('客戶名');
        $grid->status('狀態')->select([
            1 => '洽詢處理中',
            5 => '完成',
            9 => '錯誤/取消',
        ]);
        
        // $grid->column('status')->editable('select', [
        //     1 => '待洽',
        //     2 => '已洽詢',
        //     3 => '處理中',
        //     5 => '完成',
        //     9 => '錯誤/取消',
        // ]);

        $grid->total_net('淨價')->sortable();
        $grid->ip('IP');

        $grid->updated_at('最後更新')->sortable();

        $grid->filter(function($filter){

            $filter->equal('狀態')->select([
                1 => '洽詢處理中',
                5 => '完成',
                9 => '錯誤/取消',
            ]);
        
            $filter->like('customer_telephone', '客戶手機號');
            $filter->like('customer_name', '客戶名');
            $filter->like('ip', 'IP');
            
            
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
        $show = new Show(PaymentOrder::findOrFail($id));

        $show->id('ID');
        $show->customer_name('客戶名稱');
        $show->customer_email('客戶EMail');
        $show->customer_telephone('客戶電話');
        $show->payment_city('城市');
        $show->payment_postcode('郵遞區號');
        $show->payment_address('地址');
        $show->ip('IP');
        

        $show->total('訂單總價');
        $show->total_redeem('訂單總折抵');
        $show->total_net('訂單淨價');

        $show->promotion('選用優惠', function($promotion) {
            
            $promotion->name('名稱');
            $promotion->redeem_point('折抵點數');

            $promotion->panel()->tools(function ($tools) {
                $tools->disableEdit();
                $tools->disableList();
                $tools->disableDelete();
            });;
        });
        

        $show->products('商品', function($product) {

            $product->disableCreateButton();
            $product->disablePagination();
            $product->disableRowSelector();
            $product->disableFilter();
            $product->disableActions();
            $product->disableExport();

            $product->product_id('商品')->display(function($id){
                return ProductDescription::where('product_id', $id)->orderBy('language_id')->first()->name;
            });
            $product->price('單價');
            $product->quantity('數量');
            $product->total('總價');
            $product->redeem('紅利抵扣');
            $product->total_net('淨價');
        });
        
        // $show->created_at('創建時間');
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
        $form = new Form(new PaymentOrder);
        
        $form->select('status', '狀態')->options([
            1 => '洽詢處理中',
            5 => '完成',
            9 => '錯誤/取消',
        ]);
        $form->text('customer_name', '客戶名稱');
        $form->text('customer_email', '客戶EMail');
        $form->text('customer_telephone', '客戶電話');
        $form->text('payment_city', '城市');
        $form->text('payment_postcode', '郵遞區號');
        $form->text('payment_address', '地址');
        $form->text('total', '訂單總價');
        $form->text('total_redeem', '訂單總折抵');
        $form->text('total_net', '訂單淨價');

        $form->hasMany('products', '產品列表', function(Form\NestedForm $form) {
            
            $form->display('product_id', '商品名稱')->with(function($id){
                // return $id;
                return ProductDescription::where('product_id', $id)->orderBy('language_id')->first()['name'];
            });
            // $form->display('price', '價錢');
            // $form->display('quantity', '價錢');
            $form->display('total_net', '淨價');
        })
        ->disableCreate();

        $form->saved(function (Form $form) {
            $model = $form->model();

            if ($model->status == 5) {
                $reward = 0;
                $products = $model->products;
                foreach ($products as $product) {
                    $reward += $product['reward'];
                }

                Customer::find($model->customer_id)->increasePoint($reward);
            }
        });

        return $form;
    }
}
