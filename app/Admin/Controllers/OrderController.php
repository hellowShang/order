<?php

namespace App\Admin\Controllers;

use App\Model\OrderModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Foundation\Auth\User;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class OrderController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OrderModel);

        $grid->oid('订单ID');
        $grid->uid('用户名')->display(function($uid){
            $arr = User::where('id',$uid)->value('name');
            return $arr;
        });
        $grid->order_sn('订单号');
        $grid->order_amount('支付总价')->display(function($amount){
            return ($amount/100).'元';
        });
        $grid->pay_status('支付状态')->display(function($pay_status){
            if($pay_status == 1){
                return '已支付';
            }else{
                return '未支付';
            }
        });
        $grid->create_time('下单时间')->display(function($time){
            return date('Y-m-d H:i:s',$time);
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
        $show = new Show(OrderModel::findOrFail($id));

        $show->oid('订单ID');
        $show->uid('用户名');
        $show->order_sn('订单号');
        $show->order_amount('支付总价');
        $show->pay_status('支付状态');
        $show->create_time('下单时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OrderModel);

        $form->number('oid', '订单ID');
        $form->number('uid', '用户名');
        $form->text('order_sn', '订单号');
        $form->number('order_amount', '支付总价');
        $form->text('pay_status', '支付状态');

        return $form;
    }
}
