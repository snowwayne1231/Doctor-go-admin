<?php

namespace App\Admin\Controllers;

use App\Models\Customer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SmsController;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;


class CustomerController extends Controller
{
    use HasResourceActions;

    static $header_title = '會員管理';
    

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
        $grid = new Grid(new Customer);

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->id('ID');
        $grid->email('Email');
        $grid->telephone('手機');
        // $grid->account('帳號/手機');
        // $grid->password('密碼');
        $grid->firstname('名字');
        $grid->lastname('姓氏');
        $grid->status('狀態')->select([
            '0' => '待啟用',
            '1' => '正常',
            '4' => '停用',
        ]);
        $grid->doctor_profile('醫生執照號碼');
        $grid->doctor_profile_image_id('醫生執照圖檔')->display(function($id) {
            return '<img style="max-height: 45px; max-width: 90px;" src="/api/image/'.$id.'">';
        });;
        // $grid->doctor_clinic('醫生診所執照號');
        // $grid->doctor_clinic_image_id('醫生診所執照圖檔')->display(function($id) {
        //     return '<img style="max-height: 45px; max-width: 90px;" src="/api/image/'.$id.'">';
        // });;
        
        // $grid->created_at('創建時間');
        $grid->updated_at('最後更新')->sortable();
        $grid->created_at('註冊時間')->sortable();

        $grid->filter(function($filter){

            $filter->like('email', 'Email');
            $filter->like('telephone', '手機');
            $filter->like('firstname', '名字');
            $filter->like('lastname', '姓氏');
        
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
        $show = new Show(Customer::findOrFail($id));

        $show->id('ID');
        $show->email('Email');
        $show->telephone('手機');
        // $show->account('帳號/手機');
        
        $show->firstname('名字');
        $show->lastname('姓氏');
        
        $show->fax('傳真');
        $show->point('點數');
        $show->status('狀態')->using([
            '0' => '待啟用',
            '1' => '正常',
            '4' => '停用',
        ]);

        $show->doctor_profile('醫生執照');
        $show->doctor_profile_image_id('醫生執照圖檔')->unescape()->as(function($id) {
            return '<img style="max-height: 160px; max-width: 480px;" src="/api/image/'.$id.'">';
        });

        $show->doctor_clinic('醫生診所執照');
        $show->doctor_clinic_image_id('醫生診所執照圖檔')->unescape()->as(function($id) {
            return '<img style="max-height: 160px; max-width: 480px;" src="/api/image/'.$id.'">';
        });
        
        
        $show->created_at('註冊時間');
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
        $form = new Form(new Customer);

        $form->select('status', '狀態')->options([
            '0' => '待啟用',
            '1' => '正常',
            '4' => '停用',
        ]);

        $form->email('email', 'Email');
        $form->text('telephone', '手機');
        $form->password('password', '密碼');
        
        $form->text('firstname', '名字');
        $form->text('lastname', '姓氏');
        
        $form->text('fax', '傳真');
        $form->number('point', '點數');

        $form->text('doctor_profile', '醫生執照');
        // $form->display('doctor_profile_image_id', '');
        $form->text('doctor_clinic', '醫生診所執照');
        // $form->display('doctor_clinic_image_id', '');
        // ->unescape()->as(function($id) {
        //     return '<img style="max-height: 160px; max-width: 480px;" src="/api/image/'.$id.'">';
        // });

        $form->saving(function (Form $form) {
            $model = $form->model();
            
            if ($model->status != 1 && $form->status == 1) {
                // dd($model->status.' : '. $form->status);
                if (!is_null(env('MAIL_USERNAME'))) {
                    \Mail::raw('您的帳戶: '.$model->email.' 已經激活， 激活時間: '. now(), function($message) use($model) {
                        $message->to($model->email)->subject('美醫聯購 手機 APP 帳戶激活');
                    });
                }
            }

            if ($form->password) {
                $form->password = bcrypt($form->password);
            } else {
                $form->password = $model->password;
            }
            
        });

        $form->saved(function (Form $form) {
            $model = $form->model();
            Customer::find($model->id)->removeCache();
        });

        return $form;
    }
}
