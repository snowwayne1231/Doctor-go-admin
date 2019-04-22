<?php

namespace App\Admin\Controllers;

use App\Models\Country;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class CountryController extends Controller
{
    use HasResourceActions;

    static $header_title = '國家管理';
    

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
        $grid = new Grid(new Country);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');
        $grid->name('名稱');
        $grid->iso_code_3('ISO代碼');
        // $grid->postcode_require('需要郵政區號');
        $grid->status('狀態')->display( function($status) {
            switch($status) {
                case '1': return '正常';
                default: return '停用';
            }
        });

        $grid->updated_at('最後更新')->sortable();


        $grid->filter(function($filter){

            // 去掉默认的id过滤器
            // $filter->disableIdFilter();
        
            // 在这里添加字段过滤器
            $filter->like('name', '名稱');
            $filter->like('iso_code_3', 'ISO代碼');
        
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
        $show = new Show(Country::findOrFail($id));

        $show->id('ID');
        $show->name('名稱');
        $show->iso_code_2('ISO代碼(2)');
        $show->iso_code_3('ISO代碼(3)');
        // $show->postcode_require('需要郵政區號');
        $show->status('狀態')->as( function($status) {
            switch($status) {
                case '1': return '正常';
                default: return '停用';
            }
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
        $form = new Form(new Country);

        $form->text('name', '名稱');
        $form->text('iso_code_2', 'ISO代碼(2)');
        $form->text('iso_code_3', 'ISO代碼(3)');

        // $form->select('postcode_require', '需要郵政區號')->options([
        //     '1' => '需要',
        //     '0' => '不需要',
        // ]);

        $form->select('status', '狀態')->options([
            '1' => '正常',
            '4' => '停用',
        ]);

        return $form;
    }
}
