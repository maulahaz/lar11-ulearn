<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CourseController;
//--Utk selanjutnya Akan menggunakan Alias "my_namespace" yg akan di akses globally
//--yg nama alias tsb akan di simpen di 'RoutesServiceProvider.php' --! bukan utk Laravel-11

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::group(['App\Http\Controllers' => 'Api'], function () {
    Route::post('/auth/login', [UserController::class, 'login']);
    //
    // Route::get('/course-listo', [CourseController::class, 'courseList']);
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('/course-list', [CourseController::class, 'courseList']);
    });
});

// Route::get('/courses', [CourseController::class, 'courses']);

// Route::post('/auth/login', [UserController::class, 'login']);

// Route::post('/auth/register', [UserController::class, 'createUser']);
// Route::post('/auth/login', [UserController::class, 'loginUser']);