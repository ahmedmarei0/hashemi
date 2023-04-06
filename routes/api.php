<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::get('/register', [App\Http\Controllers\HomeController::class,'register']);
Route::post('/login', [App\Http\Controllers\Apis\Auth\AuthController::class,'login']);

Route::group(['middleware' => ['auth:sanctum']],function () {
    Route::post('/app/image', [App\Http\Controllers\Apis\Courses\CoursesController::class , 'app_image']);

    Route::pattern('id', '[0-9]+');
    Route::post('/logout', [App\Http\Controllers\Apis\Auth\AuthController::class , 'logout']);
    Route::post('/subjects', [App\Http\Controllers\Apis\Courses\CoursesController::class , 'show_subjects']);
    Route::post('/courses', [App\Http\Controllers\Apis\Courses\CoursesController::class , 'show_courses']);
    Route::post('/lessons/{id}', [App\Http\Controllers\Apis\Courses\CoursesController::class , 'show_lesson']);
    Route::post('/attendance', [App\Http\Controllers\Apis\Courses\CoursesController::class , 'attendance']);
    Route::post('/sheet', [App\Http\Controllers\Apis\Courses\CoursesController::class , 'sheet']);
    // Route::post('/message/support', [App\Http\Controllers\Apis\Courses\CoursesController::class , 'add_message']);
    // Route::post('/message/show/{page?}', [App\Http\Controllers\Apis\Courses\CoursesController::class , 'show_message']);
    Route::post('/contact/add', [App\Http\Controllers\Apis\Courses\CoursesController::class , 'contact_post']);
    Route::post('/contact/show', [App\Http\Controllers\Apis\Courses\CoursesController::class , 'show_contacts']);
    Route::post('/notification/show', [App\Http\Controllers\Apis\Courses\CoursesController::class , 'show_notifications']);
});
