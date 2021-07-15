<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/home', '/admin');
Route::redirect('/', '/admin');

Auth::routes(['register' => false]);

//Route::group([ 'as' => 'site.', 'namespace' => 'Site'], function () {
//    Route::get('/home', "HomeController@index")->name('home');
//    Route::get('/get_popup_data/{type}', "HomeController@get_popup_data")->name('get_popup_data');
//
//});
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

    // Doctor
    Route::delete('doctors/destroy', 'DoctorController@massDestroy')->name('doctors.massDestroy');
    Route::post('doctors/media', 'DoctorController@storeMedia')->name('doctors.storeMedia');
    Route::post('doctors/ckmedia', 'DoctorController@storeCKEditorImages')->name('doctors.storeCKEditorImages');
    Route::resource('doctors', 'DoctorController');

    // Patient
    Route::delete('patients/destroy', 'PatientController@massDestroy')->name('patients.massDestroy');
    Route::resource('patients', 'PatientController');

    // Doctor Appointments
    Route::delete('doctor-appointments/destroy', 'DoctorAppointmentsController@massDestroy')->name('doctor-appointments.massDestroy');
    Route::resource('doctor-appointments', 'DoctorAppointmentsController');

    // Registrations
    Route::delete('registrations/destroy', 'RegistrationsController@massDestroy')->name('registrations.massDestroy');
    Route::resource('registrations', 'RegistrationsController');

    // Config
    Route::delete('configs/destroy', 'ConfigController@massDestroy')->name('configs.massDestroy');
    Route::post('configs/media', 'ConfigController@storeMedia')->name('configs.storeMedia');
    Route::post('configs/ckmedia', 'ConfigController@storeCKEditorImages')->name('configs.storeCKEditorImages');
    Route::resource('configs', 'ConfigController');
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


//Route::get('mail', 'Api\V1\DoctorsController@mail')->name('test.mail');

Route::get('chart', function () {
    $appointments = \App\Models\DoctorAppointment::with([])->get();
    $times = [];
    foreach ($appointments as $appointment) {
        $found = false;
        for ($i = 0; $i < sizeof($times); $i++) {
            if ($times[$i]['label'] == $appointment->created_day) {
                $repeat = $times[$i]['y'] + 1;
                $times[$i]['y'] = $repeat;
                $found = true;
            }
        }
        if (!$found) {
            array_push($times, ['label' => $appointment->created_day, 'y' => 1]);
        }
    }
    return view('chart',compact('times'));
});
