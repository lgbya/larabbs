<?php

use Illuminate\Support\Facades\Route;


function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function category_nav_active($categoryId)
{
    return active_class((if_route('categories.show') && if_route_param('category', $categoryId)));
}
