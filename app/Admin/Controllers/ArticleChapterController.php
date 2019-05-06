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

class ArticleChapterController extends Controller
{
    use HasResourceActions;

    static $header_title = '美醫指南文章';
    

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
        $grid = new Grid(new ArticlePsaChapter);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');
        
        $grid->magazine('刊')->display(function($magazine){
            return str_limit($magazine['title'], 12, '..');
        });
        $grid->public_date('發佈日期');
        $grid->enable('啟用')->switch();
        
        $grid->author('作者')->limit(12);
        $grid->title('標題')->limit(12);
        $grid->image('圖片')->image(null, 80, 80);
        $grid->sort('優先權')->sortable();

        $grid->updated_at('最後更新')->sortable();

        $grid->filter(function($filter) {
            $filter->like('title', '標題');
            $filter->like('author', '作者');
            $filter->equal('magazine_id', '刊')->select(ArticlePsaMagazine::all()->pluck('title', 'id'));
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
        $show = new Show(ArticlePsaChapter::findOrFail($id));

        $show->id('ID');
        $show->enable('啟用')->using([1 => '開', 0 => '關']);
        $show->public_date('發佈日期');
        $show->magazine_id('刊')->as(function($magazine){
            return ArticlePsaMagazine::find($magazine)->title;
        });
        
        $show->title('標題');
        $show->image('圖片')->image();
        $show->sort('優先權');
        $show->content('內容')->unescape()->as(function ($content) {

            return $content;
        
        });

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
        $form = new Form(new ArticlePsaChapter);
        $magazine_options = ArticlePsaMagazine::all()->pluck('title', 'id');
        
        $form->switch('enable', '啟用')->default(true);
        $form->date('public_date', '發佈日期(超過該日期才會顯示)');
        $form->select('magazine_id', '刊')->options($magazine_options);
        $form->text('author', '作者');
        $form->text('title', '標題');
        $form->image('image', '圖片')->uniqueName()->rules('max:8192');
        $form->number('sort', '優先權')->default(1);

        $form->editor('content', '內容');

        // $form->hasMany('description', '內容', function($ff) {
        //     $ff->editor('content', '內容');
        // });
        

        return $form;
    }
}
