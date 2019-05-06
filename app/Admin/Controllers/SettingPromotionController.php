<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use App\Models\SettingPromotion;

class SettingPromotionController extends Controller
{
    use HasResourceActions;

    static $header_title = '結單點數抵扣設定';
    

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
        $grid = new Grid(new SettingPromotion);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');
        $grid->name('名稱');
        $grid->enable('啟用')->switch();
        $grid->condition('條件')->sortable();
        $grid->redeem_point('可折抵點數')->sortable();

        $grid->updated_at('最後更新');

        $grid->filter(function($filter) {
            $filter->like('name', '名稱');
            
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
        $show = new Show(SettingPromotion::findOrFail($id));

        $show->id('ID');
        $show->name('名稱');
        $show->enable('啟用')->using([1 => '開啟', 0 => '關閉']);
        $show->condition('條件');
        $show->redeem_point('可折抵點數');
        
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
        $form = new Form(new SettingPromotion);
        $form->switch('enable', '啟用')->default(true);
        $form->text('name', '名稱');
        $form->number('condition', '條件');
        $form->number('redeem_point', '可折抵點數');

        return $form;
    }
}
