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

class PaymentGroupBuyingOrderController extends Controller
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
        $grid = new Grid(new PaymentGroupOrder);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->customer('客戶名/手機')->display(function($customer){
            return $customer['firstname'].' '.$customer['lastname'].' / '.$customer['telephone'];
        });;
        $grid->status('狀態')->using([
            '0' => '取消',
            '1' => '確認中',
            '5' => '完成',
        ]);
        $grid->quantity('購買數量');
        $grid->total_net('總價');

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            // $actions->disableView();
        });

        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->equal('status', '狀態')->select([
                '0' => '取消',
                '1' => '確認中',
                '5' => '完成',
            ]);
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
        $model = PaymentGroupOrder::findOrFail($id);
        $show = new Show($model);

        $show->id('ID');

        $show->customer('客戶名/手機')->as(function($customer){
            return $customer['firstname'].' '.$customer['lastname'].' / '.$customer['telephone'];
        });

        $show->status('狀態')->using([
            '0' => '取消',
            '1' => '確認中',
            '5' => '完成',
        ]);

        $show->quantity('購買數量');
        $show->final_price('最後價格');
        $show->total_net('淨總價');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PaymentGroupOrder);

        $form->select('status', '狀態')->options([
            '0' => '取消',
            '5' => '完成',
        ])->default(1);

        $form->saved(function (Form $form) {

            $model = $form->model();

            $status = $model->status;
            $id = $model->id;
            $product_order_id = $model->product_order_id;

            if ($status == 5 || true) {
                // dd($model);

                $all_payment_orders = PaymentGroupOrder::where(['product_order_id' => $product_order_id])->get();

                $sum_quantity = 0;
                $sum_order = 0;
                // dd($all_payment_orders);
                foreach($all_payment_orders as $order) {
                    if ($order->status == 5) {
                        $sum_quantity += $order->quantity;
                        $sum_order += 1;
                    }
                }
                $product_group = ProductGroupBuying::find($product_order_id);
                $product_group->sum_quantity = $sum_quantity;
                $product_group->sum_order = $sum_order;
                $product_group->save();
            }
        
        });

        return $form;
    }
}
