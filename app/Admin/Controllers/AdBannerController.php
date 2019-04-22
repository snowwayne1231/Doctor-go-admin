<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use App\Models\AdBanner;

class AdBannerController extends Controller
{
    use HasResourceActions;

    static $header_title = '首頁輪播管理';
    

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
        $grid = new Grid(new AdBanner);

        $grid->id('ID');
        $grid->name('名稱');
        $grid->status('狀態')->using([1=> '正常', 4=> '關閉']);
        $grid->image('圖片')->image(null,240,60);
        $grid->sort('優先權')->sortable();

        $grid->updated_at('最後更新')->sortable();

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
        $show = new Show(AdBanner::findOrFail($id));

        $show->id('ID');
        $show->status('狀態')->using([1=> '正常', 4=> '關閉']);
        $show->image('圖片');
        $show->name('名稱');
        $show->description('描述');
        $show->link('連結');
        $show->sort('優先權');
        
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
        $form = new Form(new AdBanner);

        $form->select('status', '狀態')->options([1=> '正常', 4=> '關閉']);
        $form->image('image', '圖片(768x384最佳)')->uniqueName();
        $form->text('name', '名稱');
        $form->textarea('description', '描述');
        $form->text('link', '連結');
        $form->number('sort', '優先權');

        return $form;
    }
}
