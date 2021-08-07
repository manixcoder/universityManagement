<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
 */
Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', function () {
    return view('welcome');
});
//Auth::routes();
Auth::routes(['verify' => true]);
Route::get('/validate-user', 'HomeController@checkUserRole');

/*=====================================ADMIN=====================================*/
Route::group(['prefix' => 'admin', 'middleware' => ['admin', 'auth']], function () {
    
    Route::get('/', 'Admin\DashboardController@index');
    /*
    |---------------------------------
    | Employee Management Routes Here     |
    |---------------------------------
     */
    Route::group(['prefix' => 'university-management'], function () {
        Route::get('/', 'Admin\UniversityManagementController@index');
        Route::get('university-data', 'Admin\UniversityManagementController@universityData');
        Route::get('create', 'Admin\UniversityManagementController@create');
        Route::post('/save-university', 'Admin\UniversityManagementController@store');
        Route::get('{id}/edit', 'Admin\UniversityManagementController@edit');
        Route::post('{id}/update', 'Admin\UniversityManagementController@update');
        Route::get('delete/{id}', 'Admin\UniversityManagementController@destroy');
    });
    /*
    |------------------------------------------
    | Student Management Routes Here          |
    |------------------------------------------
     */
    Route::group(['prefix' => 'student-management'], function () {
        Route::get('/', 'Admin\StudentManagementController@index');
        Route::get('student-data', 'Admin\StudentManagementController@studentData');
        Route::get('create', 'Admin\StudentManagementController@create');
        Route::post('/save-student', 'Admin\StudentManagementController@store');
        Route::get('{id}/edit', 'Admin\StudentManagementController@edit');
        Route::post('{id}/update', 'Admin\StudentManagementController@update');
        Route::get('{id}/view', 'Admin\StudentManagementController@show');
        Route::get('delete/{id}', 'Admin\StudentManagementController@destroy');
    });
});


/*=====================================University=====================================*/
Route::group(['prefix' => 'university', 'middleware' => ['university', 'auth']], function () {
   
    Route::get('/', 'University\DashboardController@index');
    /*
    |---------------------------------
    | Employee Management Routes Here    |
    |---------------------------------
     */
    Route::group(['prefix' => 'student-management'], function () {
        Route::get('/', 'University\StudentManagementController@index');
        Route::get('student-data', 'University\StudentManagementController@studentData');
        Route::get('create', 'University\StudentManagementController@create');
        Route::post('/save-student', 'University\StudentManagementController@store');
        Route::get('{id}/edit', 'University\StudentManagementController@edit');

        Route::post('{id}/update', 'University\StudentManagementController@update');
        Route::get('delete/{id}', 'University\StudentManagementController@destroy');
    });
});


/*=====================================Student=====================================*/
Route::group(['prefix' => 'student', 'middleware' => ['student', 'auth']], function () {
    return "Student";
    Route::get('/', 'Student\DashboardController@index');
    /*
    |---------------------------------
    | Employee Management Routes Here    |
    |---------------------------------
     */
    Route::group(['prefix' => 'employee-management'], function () {
        Route::get('/', 'Student\EmployeeManagementController@index');
        Route::get('employee-data', 'Student\EmployeeManagementController@employeeData');
        Route::get('create', 'Student\EmployeeManagementController@create');
        Route::post('/save-employee', 'Student\EmployeeManagementController@store');
        Route::get('{id}/edit', 'Student\EmployeeManagementController@edit');

        Route::post('{id}/update', 'Student\EmployeeManagementController@update');
        Route::get('delete/{id}', 'Student\EmployeeManagementController@destroy');
    });
});
