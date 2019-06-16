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
use App\Models\ProductCategory;
use App\Models\ProductCategoryDescription;

class ProductCategoryController extends Controller
{
    use HasResourceActions;

    static $header_title = '商品分類管理';


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
        $grid = new Grid(new ProductCategory);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');

        $grid->column('parentName', '父分類');

        $grid->name('分類名稱')->limit(16);
        
        

        $grid->enable('啟用')->switch();

        $grid->sort('優先權')->sortable();
        $grid->created_at('最後更新')->sortable();

        $grid->filter(function($filter){
        
            $filter->equal('enable', '啟用')->select([ 1 => '開啟', 0 => '關閉']);
            
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
        $show = new Show(ProductCategory::findOrFail($id));

        $show->id('ID');

        $show->name('分類名稱');

        $show->event_image('活動圖')->image();

        $show->enable('啟用')->using([1=>'開', 0=>'關']);
        
        $form->outside_link_name('右上角外部連結名稱');
        $form->outside_link('右上角外部連結');

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
        $form = new Form(new ProductCategory);

        $form->tab('分類介紹', function($form){

            $form->hasMany('description', '名稱與內容', function (Form\NestedForm $form) {

                $form->select('language_id', '語系')->options([
                    1 => '繁體中文',
                    2 => '简体中文',
                    3 => 'English',
                ])->default(1);
    
                $form->text('name', '名稱')->rules('required');
                $form->textarea('description', '詳細內容');
                
            })->mode('tab');
        
        })->tab('屬性', function($form){

            $form->switch('enable', '啟用')->default(true);

            $categories = ProductCategory::all();

            $map_parent = $categories->pluck('parent_id', 'id')->toArray();
            $names = $categories->pluck('name', 'id')->toArray();

            $form->select('parent_id', '父分類')->options(function ($a) use($map_parent, $names) {
                $this_id = $this->id;
                $result = array_filter($names, function($id) use($map_parent, $this_id) {
                    if ($id == $this_id) { return false; }
                    $parent_id = $map_parent[$id];
                    while (true) {
                        if (empty($parent_id)) {
                            return true;
                        } else if ($parent_id == $this_id) {
                            return false;
                        }
                        $parent_id = $map_parent[$parent_id];
                    }
                },ARRAY_FILTER_USE_KEY);
                return $result;
            });

            $form->image('event_image', '活動圖片')->uniqueName();
            // $form->select('key', '首頁獨特分類')->options([
            //     'none' => '無',
            //     'equipment' => '醫美儀器',
            //     'consumable' => '醫美耗材',
            //     'second' => '二手商品',
            //     'maintenance' => '保養品',
            // ]);

            $form->text('outside_link_name', '右上角外部連結名稱');
            $form->text('outside_link', '右上角外部連結');
        
            $form->number('sort', '優先權')->default(1);
        });

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
        });

        return $form;
    }
}
