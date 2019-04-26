<?php

namespace App\Admin\Controllers;

use App\Model\MediaModel;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class MediaController extends Controller
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
        $grid = new Grid(new MediaModel);

        $grid->id('Id');
        $grid->openid('Openid');
        $grid->mediaid('Mediaid');
        $grid->content('Content');
        $grid->create_time('Create time');
        $grid->type('Type');
        $grid->url('Url');

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
        $show = new Show(MediaModel::findOrFail($id));

        $show->id('Id');
        $show->openid('Openid');
        $show->mediaid('Mediaid');
        $show->content('Content');
        $show->create_time('Create time');
        $show->type('Type');
        $show->url('Url');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new MediaModel);

        $form->text('openid', 'Openid');
        $form->text('mediaid', 'Mediaid');
        $form->text('content', 'Content');
        $form->number('create_time', 'Create time');
        $form->text('type', 'Type');
        $form->url('url', 'Url');

        return $form;
    }
}
