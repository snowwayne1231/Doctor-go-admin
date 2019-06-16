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
            ->body($this->form($id)->edit($id));
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
        $show->link_type('連結類型')->using([
            0 => '無',
            1 => '商品連結',
            2 => '商品品牌連結',
            3 => '商品分類連結',
            4 => '文章連結',
            5 => '其他',
        ]);
        // $show->link_value('內部連結');
        $show->link('外部連結');
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
    protected function form($id = null)
    {
        $form = new Form(new AdBanner);
        $model = $id
            ? AdBanner::find($id)
            : null;

        $form->select('status', '狀態')->options([1=> '正常', 4=> '關閉']);
        $form->image('image', '圖片(768x384最佳)')->uniqueName();
        $form->text('name', '名稱');
        $form->textarea('description', '描述');
        $form->select('link_type','連結類型')->options([
            0 => '無',
            1 => '商品連結',
            2 => '商品品牌連結',
            3 => '商品分類連結',
            4 => '文章連結',
            5 => '其他',
            6 => '外部連結',
        ])->default(0)->load('link_value', '/admin/maintainer/ad_news_links');

        if ($model) {
            $form->select('link_value', '內部連結')->options('/admin/maintainer/ad_news_links?q='.$model->link_type);
        } else {
            $form->select('link_value', '內部連結');
        }
        
        $form->text('link', '外部連結 (必須設置連結類型)');
        
        
        $form->number('sort', '優先權');

        return $form;
    }
}
