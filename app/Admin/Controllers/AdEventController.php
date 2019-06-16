<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use App\Models\AdEvent;
use App\Models\Product;
use App\Admin\Controllers\AdNewsController;

class AdEventController extends Controller
{
    use HasResourceActions;

    static $header_title = '促銷活動管理';
    

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
        $grid = new Grid(new AdEvent);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');
        
        $grid->enable('啟用')->switch();
        $grid->name('名稱');
        $grid->image('活動圖片')->image(null, 120, 48);

        $grid->sort('優先權')->sortable();

        $grid->updated_at('最後更新')->sortable();

        $grid->filter(function ($filter){
            
            $filter->like('name', '名稱');
            $filter->equal('enable', '啟用')->select([1 => '開', 0 => '關']);
        });

        $grid->actions(function($action){
            if ($action->row['key']) {
                $action->disableDelete();
            }
        });

        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
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
        $show = new Show(AdEvent::findOrFail($id));

        $show->id('ID');
        
        $show->enable('啟用')->using([1=>'開', 0=>'關']);
        $show->name('名稱');
        $show->image('活動圖片')->image();
        // $show->description('內容');

        // $show->link_type('連結類型')->using([
        //     0 => '無',
        //     1 => '商品連結',
        //     2 => '商品品牌連結',
        //     3 => '商品分類連結',
        //     4 => '文章連結',
        //     5 => '其他',
        //     6 => '外部連結',
        // ]);

        $show->link('活動連結');
        $show->show_index('首頁顯示');
        
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
        $form = new Form(new AdEvent);
        $model = $id
            ? AdEvent::find($id)
            : null;

        $form->switch('enable', '啟用')->default(true);
        
        $form->text('name', '名稱');
        // $form->select('link_type','連結類型')->options([
        //     0 => '無',
        //     1 => '商品連結',
        //     2 => '商品品牌連結',
        //     3 => '商品分類連結',
        //     4 => '文章連結',
        //     5 => '其他',
        //     6 => '外部連結(請先儲存)',
        // ])->default(6)->load('link', '/admin/maintainer/ad_news_links');
        // if ($model) {
        //     if ($model->link_type == 6) {
        //         $form->text('link', '連結');
        //     } else {
        //         $news = new AdNewsController();
        //         $links = $news->getLinksByType($model->link_type);
        //         $option_links = [];
        //         foreach ($links as $key => $val) {
        //             $option_links[$val['id']] = $val['text'];
        //         }
                
        //         $form->select('link', '連結')->options($option_links);
        //     }
        // } else {
        //     $form->text('link', '連結');
            
        // }
        $form->image('image', '活動圖片')->uniqueName();
        $form->text('link', '活動連結');
        $form->switch('show_index', '是否顯示在首頁');
        $form->number('sort', '優先權')->default(1);

        $multipleArray = [];
        $products = Product::all();
        foreach ($products as $product) {
            $des = $product->description;
            $name = isset($des[0])
                ? $des[0]['name']
                : '';
            $multipleArray[$product->id] = $name;
        }

        $form->listbox('product_ids', '產品')->options($multipleArray);

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });

        return $form;
    }
}
