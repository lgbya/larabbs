<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function category_nav_active($categoryId)
{
    return active_class((if_route('categories.show') && if_route_param('category', $categoryId)));
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return Str::limit($excerpt, $length);
}

function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}

function model_link($title, $model, $prefix = '')
{
    $modelName = model_plural_name($model);

    $prefix = $prefix ? "/$prefix/" : '/';

    $url = config('app.url') . $prefix . $modelName . '/' . $model->id;

    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

function model_plural_name($model)
{
    $fullClassName = get_class($model);

    $className = class_basename($fullClassName);

    $snakeCaseName = Str::snake($className);
    return Str::plural($snakeCaseName);
}
