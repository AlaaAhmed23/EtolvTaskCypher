<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\SchoolViewController;
use App\Http\Controllers\Web\StudentViewController;
use App\Http\Controllers\Web\SubjectViewController;
use App\Http\Controllers\Web\StudentPaginationController;


Route::get('/students/paginated', [StudentPaginationController::class, 'index'])
    ->name('students.paginated');
Route::get('/students/report', [StudentViewController::class, 'report'])->name('students.report');
Route::resource('schools', SchoolViewController::class);
Route::resource('students', StudentViewController::class);
Route::resource('subjects', SubjectViewController::class);
Route::get('/', function () {
    return redirect()->route('students.index');
});