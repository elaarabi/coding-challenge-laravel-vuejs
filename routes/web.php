<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});
Route::get('/media/{folder}/{filename}', function ($folder, $filename) {
    $filePath = storage_path('app' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename);
    if (!file_exists($filePath)) {
        return Response::make("File does not exist.", 404);
    }
    $fileContents = File::get($filePath);
    return response($fileContents, 200)->header('Content-Type', File::mimeType($filePath));
});
