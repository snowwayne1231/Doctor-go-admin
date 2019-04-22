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

class ProductDescriptionController extends Controller
{
    use HasResourceActions;

    static $header_title = '商品文字內容管理';


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
        $grid = new Grid(new ProductDescription);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');

        $grid->filter(function($filter){
            $filter->disableIdFilter();
        
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
        // $show->description('');
        
        
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
        $form = new Form(new ProductDescription);

        $form->editor('description', '圖文詳情');

        $form->tools(function (Form\Tools $tools){
            $tools->disableList();
            $tools->disableDelete();
            $tools->disableView();
        });

        $form->footer(function ($footer) {

            // 去掉`重置`按钮
            $footer->disableReset();
        
            // 去掉`查看`checkbox
            $footer->disableViewCheck();
        
            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();
        
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();
        
        });

        $form->saved(function(Form $form){
            admin_toastr('儲存成功');
            return redirect('/admin/maintainer/products/'.$form->model()->product_id.'/edit');
        });

        return $form;
    }
}
