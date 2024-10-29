<?php

namespace App\Admin\Controllers;

use App\Models\Course;
use App\Models\CourseType;
use App\Models\User;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Tree;

class CourseController extends AdminController
{
    protected $title = 'Courses';
    protected function grid()
    {
        $grid = new Grid(new Course());

        $grid->column('id', __('Id'));
        //--display : Fungsi dari Laravel-Admin
        $grid->column('user_token', __('Teacher'))->display(function ($usrToken) {
            return User::where('token', '=', $usrToken)->value('name');
        });

        $grid->column('name', __('Course Name'));
        $grid->column('description', __('Description'));
        $grid->column('lesson_num', __('Lesson number'));
        $grid->column('price', __('Price'));
        $grid->column('thumbnail', __('Thummbnail'))->image('', 50, 50);
        $grid->column('video', __('Video'));
        $grid->column('video_length', __('Video length'));
        $grid->column('type_id', __('Type id'));

        $grid->column('created_at', __('Created at'));
        // $grid->disableActions();
        // $grid->disableCreateButton();

        // $grid->disableExport();
        // $grid->disableFilter();

        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Course::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('thumbnail', __('Thumbnail'));
        $show->field('video', __('Video'));
        $show->field('description', __('Description'));
        $show->field('price', __('Price'));
        $show->field('lesson_num', __('Lesson number'));
        $show->field('video_length', __('Video length'));
        $show->field('follow', __('Follow'));
        $show->field('score', __('Score'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        // $show->disableActions();
        // $show->disableExport();
        // $show->disableFilter();

        return $show;
    }

    protected function form()
    {
        $form = new Form(new Course());

        $form->text('name', __('Name'));
        //
        //--Pluck is Laravel Collection methode utk ngambil data KEY dan VALUE
        //---biasa dipake utk DROPDOWN
        //---Yang paling ujung-kanan itu sbg KEY
        $title_opt = CourseType::pluck('title', 'id');
        // dd($title_opt);
        $form->select('type_id', __('Category'))->options($title_opt);
        //--Alternatif:
        // $form->select('type_id', __('Parent Category'))
        //     ->options((new CoursCourseTypee())::selectOptions());
        // ;
        //
        $form->image('thumbnail', __('Thumbnail'))->uniqueName();
        $form->file('video', __('Video'))->uniqueName();
        $form->textarea('description', __('Description'));
        $form->decimal('price', __('Price'));
        $form->number('lesson_num', __('Lesson Number'));
        $form->number('video_length', __('Video Length'));
        //
        $user_opt = \App\Models\User::pluck('name', 'token');
        $form->select('user_token', __('Teacher'))->options($user_opt);
        //
        $form->display('created_at', __('Created at'));
        $form->display('updated_at', __('Updated at'));

        return $form;
    }
}
