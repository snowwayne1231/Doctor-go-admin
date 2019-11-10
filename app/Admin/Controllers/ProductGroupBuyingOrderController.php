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
use App\Models\PaymentGroupOrder;

class ProductGroupBuyingOrderController extends Controller
{
    use HasResourceActions;

    static $header_title = '團購訂單管理';


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

        $grid->product_description('團購產品名稱')->display(function($product){
            return $product['name'];
        });

        $grid->status('狀態')->select([
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
            $filter->equal('status', '狀態')->select([
                '0' => '取消',
                '1' => '正常',
                '2' => '暫停',
                '5' => '結單',
            ]);
            
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            // $actions->disableView();
        });

        $grid->disableCreateButton();

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
        $model = ProductGroupBuying::findOrFail($id);
        $show = new Show($model);
        // $final_price = $model->price;

        $show->panel()
            ->tools(function ($tools) {
                $tools->disableEdit();
                // $tools->disableList();
                $tools->disableDelete();
            });;

        // $show->id('ID');

        $show->status('狀態')->using([
            '0' => '取消',
            '1' => '正常',
            '2' => '暫停',
            '5' => '結單',
        ]);

        $show->product_description('團購商品')->as(function($product){
            return $product['name'];
        });

        $show->product('產品原價')->as(function($pp){
            return $pp['price'];
        });

        $show->price('當前價格');

        $show->sum_quantity('團購購買數量');

        // $show->discount_json('折扣')->unescape()->as(function($discount) use($model){
        //     $str = '';
        //     foreach ($discount as $val) {
        //         $str .= '<p>數量: '.$val['condition_quantity'];
        //         $str .= ', 單價: '.$val['price'];
        //         $str .= '</p>';
        //     }
        //     $str .= $model->sum_quantity;
        //     return $str;
        // });

        

        $show->time_start('團購開始時間');
        $show->time_end('團購結束時間');
        
        $show->created_at('創建時間');
        $show->updated_at('最後更新');

        $show->orders('團購訂單', function($orders) {
            $orders->resource('/admin/maintainer/payment_group_buying_order');

            $orders->customer('客戶名/手機')->display(function($customer){
                return $customer['firstname'].' '.$customer['lastname'].' / '.$customer['telephone'];
            });;
            $orders->status('狀態')->select([
                '0' => '取消',
                '1' => '確認中',
                '5' => '完成',
            ]);
            $orders->quantity('購買數量');
            $orders->final_price('最後價格');
            $orders->total_net('淨總價');

            $orders->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $actions->disableView();
            });

            $orders->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->equal('status', '狀態')->select([
                    '0' => '取消',
                    '1' => '正常',
                    '5' => '完成',
                ]);
            });

            $orders->tools(function ($tools) {
                $tools->batch(function ($batch) {
                    $batch->disableDelete();
                });
            });

            $orders->disableCreateButton();
            $orders->disableActions();
        });

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


        // saved
        $form->saved(function (Form $form) {

            $model = $form->model();

            $status = $model->status;
            $id = $model->id;

            if ($status == 5) {
                $price = $model->price;

                $orders = PaymentGroupOrder::where('product_order_id', $id)->get();

                $group_total = 0;
                $group_total_net = 0;
                $group_total_redeem = 0;
                $group_sum_quantity = 0;
                $group_sum_order = 0;

                foreach($orders as $order) {
                    if ($order->status == 5) {

                        $order->final_price = $price;
                        $order->total_net = $order->quantity * $price;
                        $order->save();

                        $group_sum_quantity += $order->quantity;
                        $group_total += $order->total_net;
                        $group_sum_order += 1;
                    } else {
                        $order->status = 0;
                        $order->save();
                    }

                    
                }

                $group_buying = ProductGroupBuying::find($id);
                $group_buying->sum_quantity = $group_sum_quantity;
                $group_buying->sum_order = $group_sum_order;
                $group_buying->total = $group_total;
                $group_buying->total_net = $group_total;
                $group_buying->save();

            } else if ($status >= 1) {

                // PaymentGroupOrder::where('product_order_id', $id)->update(['status' => 1]);

            } else {

                // PaymentGroupOrder::where('product_order_id', $id)->update(['status' => 0]);

            }
        
        });

        return $form;
    }
}
