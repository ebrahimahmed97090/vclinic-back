<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1', 'middleware' => []], function () {
    Route::get('home_page/sms', 'HomePageController@test')->name('get.homepage.test');
    Route::get('home_page/stats', 'HomePageController@stats')->name('get.homepage.stats');
    Route::get('home_page/waiting_area', 'HomePageController@waiting')->name('get.homepage.waiting');
    Route::get('home_page/video', 'HomePageController@HomeVideo')->name('get.homepage.video');
    Route::get('home_page/about_us', 'HomePageController@AboutUs')->name('get.homepage.about_us');
    Route::get('home_page/intro', 'HomePageController@Intro')->name('get.homepage.intro');
    Route::get('home_page/faq', 'HomePageController@Faq')->name('get.homepage.faq');
    Route::get('zoom/create/{doctor_id}/{time}', 'HomePageController@zoomCreate')->name('get.zoom.create');
    Route::get('zoom/get_users', 'HomePageController@getUsersList')->name('get.zoom.users.list');
    Route::get('home_page/doctors/list', 'DoctorsController@list')->name('get.doctors.list');
    Route::post('home_page/doctors/add_appointment', 'DoctorsController@saveAppointment')->name('post.doctors.add_appointment');
    Route::post('home_page/doctors/add_rating', 'DoctorsController@saveRating')->name('post.doctors.add_rating');
    Route::post('doctors/login', 'DoctorsController@login')->name('doctors.login');
    Route::any('doctors/forget_password', 'DoctorsController@forgetPassowrd')->name('doctors.forget_password');
    Route::get('admin/doctors/list', 'DoctorsController@adminList')->name('admin.list');
});
Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1', 'middleware' => ['auth:api']], function () {
    Route::get('doctors/appointments', 'DoctorsController@getAppointments')->name('doctors.get.appointments');
    Route::post('doctors/appointments/done', 'DoctorsController@done')->name('doctors.post.done');
    Route::get('doctors/profile', 'DoctorsController@getProfile')->name('doctors.get.profile');
    Route::post('doctors/profile', 'DoctorsController@postProfile')->name('doctors.post.profile');
});
Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Doctor
    Route::apiResource('doctors', 'DoctorApiController');

    // Patient
    Route::apiResource('patients', 'PatientApiController');

    // Doctor Appointments
    Route::apiResource('doctor-appointments', 'DoctorAppointmentsApiController');

    // Registrations
    Route::apiResource('registrations', 'RegistrationsApiController');

    // Config
    Route::post('configs/media', 'ConfigApiController@storeMedia')->name('configs.storeMedia');
    Route::apiResource('configs', 'ConfigApiController');
});
