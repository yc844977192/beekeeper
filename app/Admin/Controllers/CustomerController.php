<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2023/11/28
 * Time: 10:05
 */

namespace App\Admin\Controllers;



use App\Models\Customer;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CustomerController extends AdminController
{
    protected function title()
    {
        return trans('客户');
    }
    public function grid(){
        $grid = new Grid(new Customer());
        $grid->model()->orderBy('id','desc');
        $grid->filter(function (Grid\Filter $filter) {
            // 禁用默认的 id 查询条件
            $filter->disableIdFilter();
            $filter->column(1/2 ,function ($filter){
                $filter->where(function ($query) {
                    $query->where('customer_mobile', $this->input);}, '客户电话');
            });
            $filter->column(1/2, function ($filter) {
                $filter->between('created_at','提交时间')->date();
            });
        });
        $grid->column('id', 'ID')->sortable();
        $grid->column('customer_name','名称')->display(function($code) {
            $id = $this->id;
            return '<a href="/admin/customer/'.$id.'/edit" >'.$code.'</a>';
        });
        $grid->column('customer_mobile', '电话');
        $grid->column('province_name.area_name', '省');
        $grid->column('city_name.area_name', '市');
        $grid->column('district_name.area_name', '区');
        $grid->column('created_at', '提交时间');
        return $grid;
    }
    public  function  form(){
        $form = new Form(new Customer());
        $form->text('customer_name', '姓名')->rules('required');
        $form->mobile('customer_mobile', '电话')
            ->creationRules (['required','min:11','min:11',"unique:admin_customer",'numeric'],['unique' => '手机号码必须位为数字，11位，不能重复'])
            ->updateRules (['required','min:11','min:11','numeric',"unique:admin_customer,customer_mobile,{{id}}"], ['unique' => ' 手机号码必须位为数字，11位，不能重复']);

        $form->select('customer_type', ('性质'))
            ->options(function (){
                return ['B端客户'=>'B端客户','C端客户'=>'C端客户'];
            });
        $form->text('customer_category', '所属行业');
        $form->url('customer_website', '网址')->placeholder("以http或https开头");
        $form->text('customer_address', '地址');
        $form->email('customer_email', '邮箱');
        $form->text('customer_wechat', '微信');
        $form->text('customer_applet', '小程序');
        $form->text('customer_accounts', '公众号');
        $form->distpicker(['province_id', 'city_id', 'district_id']);
        $form->text('from_mobile', '来电号码');
        return $form;
    }
    protected function detail($id)
    {
        $report =new Customer();
        $show = new Show($report::findOrFail($id));
        $show->field('customer_name', '姓名');
        $show->field('customer_mobile', '电话');
        $show->field('customer_type', '性质');
        $show->field('customer_category', '所属行业');
        $show->field('customer_website', '网址');
        $show->field('customer_email', '邮箱');
        $show->field('customer_wechat', '微信');
        $show->field('customer_applet', '小程序');
        $show->field('customer_accounts', '公众号');
        $show->field('province_name.area_name', '省');
        $show->field('city_name.area_name', '市');
        $show->field('district_name.area_name', '区');
        return $show;
    }
}