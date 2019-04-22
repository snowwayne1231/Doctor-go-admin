<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

use App\Models\Product;
use App\Models\WatchlistArticle;

class CustomerWatchlistArticleController extends Controller
{
    use HasResourceActions;

    static $header_title = '文章收藏清單管理';


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
        $grid = new Grid(new WatchlistArticle);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');

        $grid->customer('會員');
        
        $grid->updated_at('最後更新')->sortable();

        $grid->filter(function($filter){
            $filter->equal('customer_id', '會員ID');

            $products = Product::where('status', '>', 0)->get();
            $options = [];
            foreach ($products as $product) {
                $options[$product->id] = $product->description->first()['name'];

            }

            $filter->equal('product_id', '產品')->select($options);

            $filter->disableIdFilter();
        });

        $grid->actions(function($cations) {
            $actions->disableEdit();
            $actions->disableView();
        });

        $grid->disableCreateButton();

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
        $show = new Show(WatchlistArticle::findOrFail($id));

        $show->id('ID');
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
        $form = new Form(new WatchlistArticle);

        return $form;
    }
}
