<?php

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
/*
Route::get('/', function () {
    return view('welcome');
});
 */

Route::get('/',['as'=>'getuploadimage','uses'=>'ImageUploadController@getUploadImage']);

Route::get('image-upload-with-validation',['as'=>'getuploadimage','uses'=>'ImageUploadController@getUploadImage']);
Route::post('image-upload-with-validation',['as'=>'postuplodeimage','uses'=>'ImageUploadController@postUplodeImage']);

Route::post('biodata',['as'=>'biodata','uses'=>'ImageUploadController@postBioData']);