<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use App\Models\Product;
use App\Models\ProductBrand;

class ProductBrandController extends Controller
{
    use HasResourceActions;

    static $header_title = '商品品牌管理';


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
        $grid = new Grid(new ProductBrand);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');

        $grid->name('名稱')->limit(24);

        $grid->enable('啟用')->switch();
        
        $grid->image('LOGO')->image(null, 120, 48);
        

        $grid->sort('優先權')->sortable();
        $grid->updated_at('最後更新')->sortable();

        $grid->filter(function($filter){
        
            $filter->like('name', '名稱');
            $filter->equal('enable', '啟用')->select([ 1 => '開啟', 0 => '關閉']);
            
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
        $show = new Show(ProductBrand::findOrFail($id));

        $show->id('ID');

        $show->name('主圖');
        $show->description('詳細介紹');

        $show->enable('啟用')->switch([
            'on'  => ['value' => true, 'text' => 'O', 'color' => 'primary'],
            'off' => ['value' => false, 'text' => 'x', 'color' => 'danger'],
        ]);

        $show->image('LOGO')->image();
        $show->logo_max_width('LOGO最大寬度(px)');
        $show->event_image('活動圖')->image();

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
        $form = new Form(new ProductBrand);

        $form->switch('enable', '啟用')->default(true);

        $form->text('name', '名稱');
        $form->textarea('description', '詳細介紹');

        $form->image('image', 'LOGO')->uniqueName();
        $form->number('logo_max_width', 'LOGO最大寬度(px)');
        $form->image('event_image', '活動圖片')->uniqueName();
        $form->number('sort', '優先權')->default(1);

        return $form;
    }
}
