<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use App\Models\AdPointBanner;

class AdPointBannerController extends Controller
{
    use HasResourceActions;

    static $header_title = '點數中心輪播管理';
    

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
        $grid = new Grid(new AdPointBanner);

        $grid->id('ID');
        $grid->name('名稱');
        $grid->enable('啟用')->switch();
        $grid->image('圖片')->image(null,160,48);
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
        $show = new Show(AdPointBanner::findOrFail($id));

        $show->id('ID');
        $show->enable('啟用')->using([1=> '開', 0=> '關']);
        $show->name('名稱');
        $show->image('圖片');
        
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
        $form = new Form(new AdPointBanner);

        $form->switch('enable', '狀態')->default(true);
        $form->image('image', '圖片(768x384)')->uniqueName();
        $form->text('name', '名稱');
        $form->text('description', '描述');
        $form->text('link', '連結');
        $form->number('sort', '優先權')->default(1);

        return $form;
    }
}
