<?php
use Illuminate\Support\Facades\Route;


Route::get('webfree','WebFreeController@index');
Route::get('webfree/downloadPdf','WebFreeController@downloadPdf');
Route::get('pdftojson','PdfToJsonController@index');
Route::get('pdftojson2','PdfToJsonController@tojson2');
Route::get('demo/index','DemoController@index');
Route::get('plan/showDataSum','PlanController@index');
Route::get('mining','MiningpoolstatsController@index');
Route::get('xzfgk','XzfgkController@index');
Route::get('xzfgk/download','XzfgkController@download');
Route::get('xzfgk/downloadWord','XzfgkController@downloadWord');
Route::get('demo/zh','DemoController@zh');
Route::get('demo/testBaiDu','DemoController@testBaiDu');
Route::get('tripartite','TripartiteController@index');
Route::get('detection','DetectionController@index');





