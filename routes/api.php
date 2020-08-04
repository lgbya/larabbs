<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => ['serializer:array', 'bindings'],
],function ($api){
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ],function($api){
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verificationCodes.store');

        $api->post('users', 'UsersController@store')
            ->name('api.users.store');

        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');

        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('api.socials.authorizations.store');

        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');

        // 刷新token
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');

        // 删除token
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');

        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');

        $api->get('topics', 'TopicsController@index')
            ->name('api.topics.index');

        $api->get('users/{user}/topics', 'TopicsController@userIndex')
            ->name('api.users.topics.index');


        $api->group(['middleware' => 'api.auth'], function ($api){
            $api->get('users', 'UsersController@me')
                ->name('api.user.show');

            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');

            $api->patch('users', 'UsersController@update')
                ->name('api.user.update');

            $api->post('topics', 'TopicsController@store')
                ->name('api.topics.store');

            $api->patch('topics/{topic}', 'TopicsController@update')
                ->name('api.topics.update');

            $api->delete('topics/{topic}', 'TopicsController@destroy')
                ->name('api.topics.destroy');

        });

    });

});



