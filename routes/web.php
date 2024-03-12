<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\TestController;
use Vimeo\Laravel\Facades\Vimeo;

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

// Route::get('/', function () {
//    $response = Vimeo::request('/groups', ['per_page' => 10], 'GET');
//    dd( $response['body'] );
// });
Route::get('/tk', [TestController::class, 'test']);

Route::get('/view/file', function () {
    $path = request('path') ?? null;
    if ($path && $path != 'null') {
        if (file_exists(storage_path('app/' . $path))) {
            return response()->file(storage_path('app/' . $path));
        }
    }
    return response()->file(public_path('/blank.png'));
})->name('view-file');

Route::get("/view/htmlquestionnaire/{id}/{user_id}", function () {
    return view("htmlquestionnaire");
});
Route::get("/view/instruction/{id}", function () {
    return view("instruction");
});
Route::get("/view/htmlquestionnaireresult/{id}/{user_id}", function () {
    return view("htmlquestionnaireresult");
});
Route::get("/view/terms-conditions", function () {
    return view("termsandconditions");
});
Route::get("/view/disclaimer", function () {
    return view("disclaimer");
});


Route::get('/config-cache', function() {
    $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('config:cache');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    return 'Config cache cleared';
});

Route::get('/{any}', [ApplicationController::class, 'index'])->where('any', '.*');

Route::get('/generate/password', [ApplicationController::class, 'index'])->name('generate-password');
