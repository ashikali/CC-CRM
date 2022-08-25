<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Take Survey
    Route::delete('take-surveys/destroy', 'TakeSurveyController@massDestroy')->name('take-surveys.massDestroy');
    Route::resource('take-surveys', 'TakeSurveyController');

    // Abandoned Calls
    Route::delete('abandoned-calls/destroy', 'AbandonedCallsController@massDestroy')->name('abandoned-calls.massDestroy');
    Route::resource('abandoned-calls', 'AbandonedCallsController', ['except' => ['create', 'store']]);

    // Contact
    Route::delete('contacts/destroy', 'ContactController@massDestroy')->name('contacts.massDestroy');
    Route::resource('contacts', 'ContactController');
 
    // CSAT Answers 
    Route::get('answers','ReportController@answers')->name('csat.answers');

    //Live Dash
    Route::get('livedash','LiveDashController@livedash')->name('livedash');
     
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
//for survey submit
Route::group(['prefix' => 'csat', 'as' => 'csat.','namespace' => 'CSAT'], function () {
    Route::get('submit/{surveyid}/{uniqueid}','EntriesController@entry')->name('entry');
    Route::post('store/{surveyid}/{uniqueid}','EntriesController@store')->name('store');
});

