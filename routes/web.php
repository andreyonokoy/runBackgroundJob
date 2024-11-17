<?php

use App\Contracts\BackgroundJobClassValidationInterface;
use App\Contracts\BackgroundJobLaunchInterface;
use Illuminate\Support\Facades\Route;
use App\Services\BackgroundJobService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {

    $backgroundJobService =  app(BackgroundJobService::class);
    $result=$backgroundJobService->execute('ComplexJob', [
        'time'=>3,
        'successExecutePercent'=>15
    ]);
    dd($result);
    die();
    return view('welcome');
});
