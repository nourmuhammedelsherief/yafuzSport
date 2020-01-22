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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')->group(function () {
    Route::group(['middleware' =>  'cors'], function () {
        Route::post( '/register-mobile', [
            'uses' => 'Api\AuthController@registerMobile',
            'as'   => 'register-mobile'
        ] );
        Route::post( '/phone-verification', [
            'uses' => 'Api\AuthController@register_phone_post',
            'as'   => 'register_phone_post'
        ] );
        Route::post( '/resend-code', [
            'uses' => 'Api\AuthController@resend_code',
            'as'   => 'resend_code'
        ] );

        Route::get( '/register_countries', [
            'uses' => 'Api\AuthController@register_countries',
            'as'   => 'register_data'
        ] );
        Route::get( '/register_cities/{id}', [
            'uses' => 'Api\AuthController@register_cities',
            'as'   => 'register_data'
        ] );
        Route::post( '/register', [
            'uses' => 'Api\AuthController@register',
            'as'   => 'register'
        ] );
        Route::post( '/login', [
            'uses' => 'Api\AuthController@login',
            'as'   => 'login'
        ] );
        Route::post( '/forget-password', [
            'uses' => 'Api\AuthController@forgetPassword',
            'as'   => 'forgetPassword'
        ] );
        Route::post( '/confirm-reset-code', [
            'uses' => 'Api\AuthController@confirmResetCode',
            'as'   => 'confirmResetCode'
        ] );
        Route::post( '/reset-password', [
            'uses' => 'Api\AuthController@resetPassword',
            'as'   => 'resetPassword'
        ] );

        Route::get( '/terms-and-conditions', [
            'uses' => 'Api\ProfileController@terms_and_conditions',
            'as'   => 'terms_and_conditions'
        ] );
        Route::get( '/about-us', [
            'uses' => 'Api\ProfileController@about_us',
            'as'   => 'about_us'
        ] );
        Route::get('/activities', [
            'uses' => 'Api\ProfileController@activities',
            'as'   => 'activities'
        ] );
        Route::get('/app_contact_info', [
            'uses' => 'Api\ProfileController@app_contact_info',
            'as'   => 'app_contact_info'
        ] );
        Route::get('/groups', [
            'uses' => 'Api\GroupsController@groups',
            'as'   => 'groups'
        ] );
        Route::get( '/get_public_news/{id}', [
            'uses' => 'Api\NewsController@get_public_news',
            'as'   => 'get_public_news'
        ] );
        Route::get( '/get_public_news_by_id/{id}', [
            'uses' => 'Api\NewsController@get_public_news_by_id',
            'as'   => 'get_public_news_by_id'
        ] );
        Route::get( '/get_group_news/{id}', [
            'uses' => 'Api\NewsController@get_group_news',
            'as'   => 'get_group_news'
        ] );
        Route::get( '/get_group_news_by_id/{id}', [
            'uses' => 'Api\NewsController@get_group_news_by_id',
            'as'   => 'get_group_news_by_id'
        ] );
        Route::get( '/get_chat_voice', [
            'uses' => 'Api\GroupsController@get_chat_voice',
            'as'   => 'get_chat_voice'
        ] );
    });


    Route::group(['middleware' => ['auth:api', 'cors']], function () {

        /*notification*/
        Route::get('/list-notifications', 'Api\ApiController@listNotifications');
        Route::post('/delete_Notifications/{id}', 'Api\ApiController@delete_Notifications');

        Route::get('/user_groups/{city_id}', [
            'uses' => 'Api\GroupsController@user_groups',
            'as'   => 'user_groups'
        ] );

        Route::get('/all_user_groups/{city_id}', [
            'uses' => 'Api\GroupsController@all_user_groups',
            'as'   => 'all_user_groups'
        ] );
        /*notification*/
        Route::post( '/change-password', [
            'uses' => 'Api\AuthController@changePassword',
            'as'   => 'changePassword'
        ] );
        Route::post( '/change-phone-number', [
            'uses' => 'Api\AuthController@change_phone_number',
            'as'   => 'change_phone_number'
        ] );
        Route::post( '/check-code-change-phone-number', [
            'uses' => 'Api\AuthController@check_code_changeNumber',
            'as'   => 'check_code_changeNumber'
        ] );
        Route::get('/get_groups_by_city_id/{id}', [
            'uses' => 'Api\GroupsController@get_groups_by_city_id',
            'as'   => 'get_groups_by_city_id'
        ] );
        Route::get('/get_group_by_id/{id}', [
            'uses' => 'Api\GroupsController@get_group_by_id',
            'as'   => 'get_group_by_id'
        ] );
        Route::post( '/change-image', [
            'uses' => 'Api\UserController@change_image',
            'as'   => 'change_image'
        ] );
        Route::post( '/change-country', [
            'uses' => 'Api\UserController@change_country',
            'as'   => 'change_country'
        ] );
        Route::post( '/change-name', [
            'uses' => 'Api\UserController@change_name',
            'as'   => 'change_name'
        ] );
        Route::post( '/change-city', [
            'uses' => 'Api\UserController@change_city',
            'as'   => 'change_city'
        ] );
        Route::post( '/change-gender', [
            'uses' => 'Api\UserController@change_gender',
            'as'   => 'change_gender'
        ] );
        Route::post( '/create-group', [
            'uses' => 'Api\GroupsController@create_group',
            'as'   => 'create_group'
        ] );
        Route::post( '/contact_us', [
            'uses' => 'Api\UserController@contact_us',
            'as'   => 'contact_us'
        ] );
        Route::get( '/check_user_permission', [
            'uses' => 'Api\UserController@check_user_permission',
            'as'   => 'check_user_permission'
        ] );
        Route::get( '/check_groups_by_city_id/{id}', [
            'uses' => 'Api\GroupsController@check_groups_by_city_id',
            'as'   => 'check_groups_by_city_id'
        ] );
        Route::get('/my_groups', [
            'uses' => 'Api\GroupsController@my_group',
            'as'   => 'my_groups'
        ] );
        Route::get('/super_visor_groups/{city_id}', [
            'uses' => 'Api\GroupsController@super_visor_groups',
            'as'   => 'super_visor_groups'
        ] );
        Route::get('/group_messages/{group_id}', 'Api\GroupsController@group_messages');
        Route::post('group_admin_add_member/{group}' , 'Api\GroupMemberController@group_admin_add_member');




        Route::get('/get_user_Admin_super_groups/{id}', [
            'uses' => 'Api\GroupsController@get_user_Admin_super_groups',
            'as'   => 'get_user_Admin_super_groups'
        ] );
        Route::get('/get_user_Admin_member_groups/{id}', [
            'uses' => 'Api\GroupsController@get_user_Admin_member_groups',
            'as'   => 'get_user_Admin_member_groups'
        ] );
        Route::post('/admin_change_group_photo/{id}', [
            'uses' => 'Api\GroupsController@admin_change_group_photo',
            'as'   => 'admin_change_group_photo'
        ] );
        Route::post('/admin_change_group_name/{id}', [
            'uses' => 'Api\GroupsController@admin_change_group_name',
            'as'   => 'admin_change_group_name'
        ] );
        Route::post('/admin_change_group_activity/{id}', [
            'uses' => 'Api\GroupsController@admin_change_group_activity',
            'as'   => 'admin_change_group_activity'
        ] );
         Route::post('/admin_change_group_city/{id}', [
             'uses' => 'Api\GroupsController@admin_change_group_city',
             'as'   => 'admin_change_group_city'
         ] );
        // new routes
        Route::post( '/create_public_news', [
            'uses' => 'Api\NewsController@create_public_news',
            'as'   => 'create_public_news'
        ] );
        Route::post( '/edit_public_news/{news_id}', [
            'uses' => 'Api\NewsController@edit_public_news',
            'as'   => 'edit_public_news'
        ] );
        Route::post( '/delete_public_news/{news_id}', [
            'uses' => 'Api\NewsController@delete_public_news',
            'as'   => 'delete_public_news'
        ] );
        Route::post( '/create_group_news/{group_id}', [
            'uses' => 'Api\NewsController@create_group_news',
            'as'   => 'create_group_news'
        ] );
        Route::post( '/edit_group_news/{news_id}', [
            'uses' => 'Api\NewsController@edit_group_news',
            'as'   => 'edit_group_news'
        ] );
        Route::post( '/delete_group_news/{news_id}', [
            'uses' => 'Api\NewsController@delete_group_news',
            'as'   => 'delete_group_news'
        ] );
        Route::get( '/check_group_users/{id}', [
            'uses' => 'Api\NewsController@check_group_users',
            'as'   => 'check_group_users'
        ] );

        Route::get( '/get_user_group_news/{city_id}', [
            'uses' => 'Api\NewsController@get_user_group_news',
            'as'   => 'get_user_group_news'
        ] );
        Route::post( '/group_join_demand/{id}', [
            'uses' => 'Api\GroupMemberController@group_join_demand',
            'as'   => 'group_join_demand'
        ] );
        Route::post( '/check_city_admin/{id}', [
            'uses' => 'Api\GroupMemberController@check_city_admin',
            'as'   => 'check_city_admin'
        ] );
        Route::post( '/group_cancel_join_demand/{id}', [
            'uses' => 'Api\GroupMemberController@group_cancel_join_demand',
            'as'   => 'group_cancel_join_demand'
        ] );
        Route::get( '/get_group_demands/{id}', [
            'uses' => 'Api\GroupMemberController@get_group_demands',
            'as'   => 'get_group_news'
        ] );
        Route::post( '/group_accept_demand/{id}', [
            'uses' => 'Api\GroupMemberController@group_accept_demand',
            'as'   => 'group_accept_demand'
        ]);
        Route::post( '/group_refuse_demand/{id}', [
            'uses' => 'Api\GroupMemberController@group_refuse_demand',
            'as'   => 'group_refuse_demand'
        ]);
        Route::get( '/get_group_members/{id}', [
            'uses' => 'Api\GroupMemberController@get_group_members',
            'as'   => 'get_group_members'
        ]);
        Route::post( '/group_cancel_user/{group_id}/{user_id}', [
            'uses' => 'Api\GroupMemberController@group_cancel_user',
            'as'   => 'group_cancel_user'
        ]);
        Route::post( '/group_make_user_super_visor/{group_id}/{user_id}', [
            'uses' => 'Api\GroupMemberController@group_make_user_super_visor',
            'as'   => 'group_make_user_super_visor'
        ]);
        Route::post( '/group_cancel_super_visor/{group_id}/{user_id}', [
            'uses' => 'Api\GroupMemberController@group_cancel_super_visor',
            'as'   => 'group_cancel_super_visor'
        ]);

        Route::post( '/update_location', [
            'uses' => 'Api\UserController@update_location',
            'as'   => 'update_location'
        ]);
        Route::get( '/get_group_super_visors/{id}', [
            'uses' => 'Api\GroupMemberController@get_group_super_visors',
            'as'   => 'get_group_super_visors'
        ]);
        Route::get('/settings', 'Api\DetailsController@settings');

//    ===========refreshToken ====================

        Route::post('/refresh-device-token', [
            'uses' => 'Api\DetailsController@refreshDeviceToken',
            'as'   => 'refreshDeviceToken'
        ] );
        Route::post('/refreshToken', [
            'uses' => 'Api\DetailsController@refreshToken',
            'as'   => 'refreshToken'
        ] );
        //===============logout========================

        Route::post('/logout', [
            'uses' => 'Api\AuthController@logout',
            'as'   => 'logout'
        ] );
    });

});
