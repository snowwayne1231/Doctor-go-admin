<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use App\Models\ArticlePsaMagazine;
use App\Models\ArticlePsaChapter;

class ArticleMagazineController extends Controller
{
    use HasResourceActions;

    static $header_title = '美醫指南雜誌';
    

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
        $grid = new Grid(new ArticlePsaMagazine);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');
        
        // $grid->author('作者');
        $grid->title('標題')->limit(24);
        $grid->enable('啟用')->switch();
        $grid->image('圖片')->image(null, 80, 80);
        $grid->sort('優先權')->sortable();
        $grid->public_date('發布日期')->sortable();

        $grid->updated_at('最後更新')->sortable();

        $grid->filter(function($filter) {
            $filter->like('title', '標題');
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
        $show = new Show(ArticlePsaMagazine::findOrFail($id));

        $show->id('ID');
        $show->enable('啟用')->using([1 => '開', 2 => '關']);
        
        $show->title('標題');
        $show->image('圖片')->image(null, 80, 80);
        $show->sort('優先權');
        $show->public_date('發布日期');

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
        $form = new Form(new ArticlePsaMagazine);

        $form->switch('enable', '啟用')->default(true);
        // $form->text('author', '作者');
        $form->text('title', '標題');
        $form->image('image', '圖片(752x300)');
        $form->date('public_date', '發布日期');
        $form->number('sort', '優先權')->default(1);

        return $form;
    }
}
