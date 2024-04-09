<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2023/12/8
 * Time: 17:08
 */
use Illuminate\Support\Facades\Route;
use Modules\Api\Controllers\TripartiteController;

Route::get('/Api/tripartite/index', [TripartiteController::class, 'index']);
