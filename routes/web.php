<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    \Illuminate\Support\Facades\Artisan::call('check::commission');
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
/*admin panel routes*/

Route::get('/admin/home', ['middleware'=> 'auth:admin', 'uses'=>'AdminController\HomeController@index'])->name('admin.home');

Route::prefix('admin')->group(function () {


    Route::get('login', 'AdminController\Admin\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'AdminController\Admin\LoginController@login')->name('admin.login.submit');
    Route::get('password/reset', 'AdminController\Admin\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/email', 'AdminController\Admin\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token}', 'AdminController\Admin\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset', 'AdminController\Admin\ResetPasswordController@reset')->name('admin.password.update');
    Route::post('logout', 'AdminController\Admin\LoginController@logout')->name('admin.logout');

    Route::get('get/regions/{id}', 'AdminController\HomeController@get_regions');

    // public notifications
    Route::get('public_notifications' , 'AdminController\HomeController@public_notifications')->name('public_notifications');
    Route::post('store_public_notifications' , 'AdminController\HomeController@store_public_notifications')->name('storePublicNotification');

    // Active Group
    Route::get('active_group/{group_id}' , 'AdminController\GroupController@active_group')->name('activeGroup');
    Route::get('stop_group/{group_id}' , 'AdminController\GroupController@stop_group')->name('stopGroup');
    //Active User
    Route::get('active_user/{user_id}' , 'AdminController\UserController@active_user')->name('activeUser');
    Route::get('dispose_user/{user_id}' , 'AdminController\UserController@dispose_user')->name('DisposeUser');
    Route::group(['middleware'=> ['web','auth:admin']],function(){

        Route::get('setting','AdminController\SettingController@index');
        Route::post('add/settings','AdminController\SettingController@store');



        Route::get('pages/about','AdminController\PageController@about');
        Route::post('add/pages/about','AdminController\PageController@store_about');


        Route::get('pages/terms','AdminController\PageController@terms');
        Route::post('add/pages/terms','AdminController\PageController@store_terms');


//        ===========================ages===========================================


        Route::get('age','AdminController\AgeController@index');
        Route::get('add/age','AdminController\AgeController@create');
        Route::post('add/age','AdminController\AgeController@store');
        Route::get('edit/age/{id}','AdminController\AgeController@edit');
        Route::post('update/age/{id}','AdminController\AgeController@update');
        Route::get('delete/{id}/age','AdminController\AgeController@destroy');







//        ===========================all order===========================================

        Route::get('order/student','AdminController\OrderController@student');
        Route::get('order/employee','AdminController\OrderController@employee');
        Route::get('order/rent','AdminController\OrderController@rent');
        Route::get('show/offer/{id}','AdminController\OrderController@offer');
        Route::get('commission','AdminController\OrderController@commission');
        Route::get('edit/commission/{id}','AdminController\OrderController@edit_commission');
        Route::post('update/commission/{id}','AdminController\OrderController@update_commission');



//        ===========================carModel===========================================

        Route::get('carModel','AdminController\carModelController@index');
        Route::get('add/carModel','AdminController\carModelController@create');
        Route::post('add/carModel','AdminController\carModelController@store');
        Route::get('edit/carModel/{id}','AdminController\carModelController@edit');
        Route::post('update/carModel/{id}','AdminController\carModelController@update');
        Route::get('delete/{id}/carModel','AdminController\carModelController@destroy');





//        ===========================company===========================================

        Route::get('company','AdminController\CompanyController@index');
        Route::get('add/company','AdminController\CompanyController@create');
        Route::post('add/company','AdminController\CompanyController@store');
        Route::get('edit/company/{id}','AdminController\CompanyController@edit');
        Route::post('update/company/{id}','AdminController\CompanyController@update');
        Route::get('delete/{id}/company','AdminController\CompanyController@destroy');






//        ===========================driver===========================================

        Route::get('driver','AdminController\DriverController@index');
        Route::get('add/driver','AdminController\DriverController@create');
        Route::post('add/driver','AdminController\DriverController@store');
        Route::get('edit/driver/{id}','AdminController\DriverController@edit');
        Route::post('update/driver/{id}','AdminController\DriverController@update');
        Route::get('delete/{id}/driver','AdminController\DriverController@destroy');



//        ===========================ModelCityController===========================================

        Route::get('modelCity','AdminController\ModelCityController@index');
        Route::get('add/modelCity','AdminController\ModelCityController@create');
        Route::post('add/modelCity','AdminController\ModelCityController@store');
        Route::get('edit/modelCity/{id}','AdminController\ModelCityController@edit');
        Route::post('update/modelCity/{id}','AdminController\ModelCityController@update');
        Route::get('delete/{id}/modelCity','AdminController\ModelCityController@destroy');



//        ===========================nationality===========================================

        Route::get('nationality','AdminController\NationalityController@index');
        Route::get('add/nationality','AdminController\NationalityController@create');
        Route::post('add/nationality','AdminController\NationalityController@store');
        Route::get('edit/nationality/{id}','AdminController\NationalityController@edit');
        Route::post('update/nationality/{id}','AdminController\NationalityController@update');
        Route::get('delete/{id}/nationality','AdminController\NationalityController@destroy');



//        ===========================passenger===========================================

        Route::get('passenger','AdminController\PassengerController@index');
        Route::get('add/passenger','AdminController\PassengerController@create');
        Route::post('add/passenger','AdminController\PassengerController@store');
        Route::get('edit/passenger/{id}','AdminController\PassengerController@edit');
        Route::post('update/passenger/{id}','AdminController\PassengerController@update');
        Route::get('delete/{id}/passenger','AdminController\PassengerController@destroy');


//        ===========================country and cities===========================================
        Route::get('country','AdminController\CountryCont@index')->name('countries');
        Route::get('country/create','AdminController\CountryCont@create')->name('createCountry');
        Route::post('country/store','AdminController\CountryCont@store')->name('storeCountry');
        Route::get('country/edit/{id}','AdminController\CountryCont@edit')->name('editCountry');
        Route::post('country/update/{id}','AdminController\CountryCont@update')->name('updateCountry');
        Route::get('country/delete/{id}','AdminController\CountryCont@destroy')->name('deleteCountry');
//     =============================== Start  Sports Activities =======================================
        Route::get('activities','AdminController\ActivityController@index')->name('activities');
        Route::get('activities/create','AdminController\ActivityController@create')->name('createActivities');
        Route::post('activities/store','AdminController\ActivityController@store')->name('storeActivities');
        Route::get('activities/edit/{id}','AdminController\ActivityController@edit')->name('editActivities');
        Route::post('activities/update/{id}','AdminController\ActivityController@update')->name('updateActivities');
        Route::get('activities/delete/{id}','AdminController\ActivityController@destroy')->name('deleteActivities');
//     ===============================  End Sports Activities =======================================

//     =============================== Start  Contact Us     =======================================
        Route::get('contact_us','AdminController\ContactUsController@index')->name('contact_us');
        Route::get('contact_us/create','AdminController\ContactUsController@create')->name('createContact_us');
        Route::post('contact_us/store','AdminController\ContactUsController@store')->name('storeContact_us');
        Route::get('contact_us/show/{id}','AdminController\ContactUsController@show')->name('showContact_us');
        Route::get('contact_us/edit/{id}','AdminController\ContactUsController@edit')->name('editContact_us');
        Route::post('contact_us/update/{id}','AdminController\ContactUsController@update')->name('updateContact_us');
        Route::get('contact_us/delete/{id}','AdminController\ContactUsController@destroy')->name('deleteContact_us');
//     ===============================  End Contact Us     =======================================


        Route::get('cities','AdminController\Citycontroller@index')->name('cities');
        Route::get('city/create','AdminController\Citycontroller@create')->name('createCity');
        Route::post('city/store','AdminController\Citycontroller@store')->name('storeCity');
        Route::get('city/edit/{id}','AdminController\Citycontroller@edit')->name('editCity');
        Route::post('city/update/{id}','AdminController\Citycontroller@update')->name('updateCity');
        Route::get('city/delete/{id}','AdminController\Citycontroller@destroy')->name('deleteCity');


 //================================ Start Groups ======================================================//
        Route::get('groups','AdminController\GroupController@index')->name('groups');
        Route::get('groups/create','AdminController\GroupController@create')->name('createGroup');
        Route::post('groups/store','AdminController\GroupController@store')->name('storeGroup');
        Route::get('groups/edit/{id}','AdminController\GroupController@edit')->name('editGroup');
        Route::post('groups/update/{id}','AdminController\GroupController@update')->name('updateGroup');
        Route::get('groups/delete/{id}','AdminController\GroupController@destroy')->name('deleteGroup');
 //================================ End Groups ======================================================//

//================================ Start News ======================================================//
        Route::get('news','AdminController\NewsController@index')->name('news');
        Route::get('news/create','AdminController\NewsController@create')->name('createNews');
        Route::post('news/store','AdminController\NewsController@store')->name('storeNews');
        Route::get('news/edit/{id}','AdminController\NewsController@edit')->name('editNews');
        Route::post('news/update/{id}','AdminController\NewsController@update')->name('updateNews');
        Route::get('news/delete/{id}','AdminController\NewsController@destroy')->name('deleteNews');
 //================================ End News ======================================================//

//================================ Start Group News ======================================================//
        Route::get('groupNews','AdminController\GroupNewsController@index')->name('groupNews');
        Route::get('groupNews/create','AdminController\GroupNewsController@create')->name('createGroupNews');
        Route::post('groupNews/store','AdminController\GroupNewsController@store')->name('storeGroupNews');
        Route::get('groupNews/edit/{id}','AdminController\GroupNewsController@edit')->name('editGroupNews');
        Route::post('groupNews/update/{id}','AdminController\GroupNewsController@update')->name('updateGroupNews');
        Route::get('groupNews/delete/{id}','AdminController\GroupNewsController@destroy')->name('deleteGroupNews');
 //================================ End Group News ======================================================//

//        ===================================users============================================

        Route::get('users','AdminController\UserController@index')->name('users');
        Route::get('users/create','AdminController\UserController@create')->name('createUser');
        Route::post('users/store','AdminController\UserController@store')->name('storeUser');
        Route::get('users/edit/{id}','AdminController\UserController@edit')->name('editUser');
        Route::get('edit/userAccount/{id}/{type}','AdminController\UserController@edit_account');
        Route::post('update/userAccount/{id}/{type}','AdminController\UserController@update_account');
        Route::post('users/update/{id}','AdminController\UserController@update')->name('updateUser');
        Route::post('update/pass/{id}','AdminController\UserController@update_pass');
        Route::post('update/privacy/{id}','AdminController\UserController@update_privacy');
        Route::get('delete/{id}/users','AdminController\UserController@destroy')->name('deleteUser');

// contact messages
        Route::post('contactUs/reply/{id}' , 'AdminController\ContactUsController@replay');
        Route::get('AgreeToPost/{id}' , 'AdminController\ContactUsController@AgreeToPost')->name('AgreeToPost');
        Route::get('delete/{id}/contact' , 'AdminController\ContactUsController@deleteContact');



        //===============================================================


        // Admins Route
        Route::resource('admins', 'AdminController\AdminController');

        Route::get('/profile', [
            'uses' => 'AdminController\AdminController@my_profile',
            'as' => 'my_profile' // name
        ]);
        Route::post('/profileEdit', [
            'uses' => 'AdminController\AdminController@my_profile_edit',
            'as' => 'my_profile_edit' // name
        ]);
        Route::get('/profileChangePass', [
            'uses' => 'AdminController\AdminController@change_pass',
            'as' => 'change_pass' // name
        ]);
        Route::post('/profileChangePass', [
            'uses' => 'AdminController\AdminController@change_pass_update',
            'as' => 'change_pass' // name
        ]);

        Route::get('/admin_delete/{id}', [
            'uses' => 'AdminController\AdminController@admin_delete',
            'as' => 'admin_delete' // name
        ]);

    });



});
Route::get('/test', function () {
     return view('test');
});
Route::get('/Privacy-Policy' , function ()
{
    return view('admin.privacy_and_policy');
});
