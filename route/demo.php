<?php

use domain\demo\port\controller\EsSampleController;
use domain\demo\port\controller\SampleController;
use think\facade\Route;

//开放权限
Route::group('demo', function () {

    //只对测试开放-正式接口不要放这里
    if(config('app.app_debug')) {

        //-新增
        Route::post('/sample/save', SampleController::class . '@sampleSave');
        //-新增-异步
        Route::post('/sample/job-save', SampleController::class . '@sampleJobSave');
        //-获取-列表
        Route::get('/sample/index', SampleController::class . '@sampleIndex');
        //-获取-详情
        Route::get('/sample/:id', SampleController::class . '@sampleRead');
        //-更新-详情
        Route::put('/sample/:id', SampleController::class . '@sampleUpdate');
        //-删除-详情
        Route::delete('/sample/:id', SampleController::class . '@sampleDelete');
        //-批量新增
        Route::post('/sample/batch-save', SampleController::class . '@sampleBatchSave');
        //-批量更新
        Route::put('/sample/batch-update', SampleController::class . '@sampleBatchUpdate');


        //-ES新增索引库
        Route::post('/es_sample/table/save', EsSampleController::class . '@esSampleTableSave');
        //-ES新增
        Route::post('/es_sample/save', EsSampleController::class . '@esSampleSave');
        //-ES获取-列表
        Route::get('/es_sample/index', EsSampleController::class . '@esSampleIndex');
        //-ES获取-详情
        Route::get('/es_sample/:id', EsSampleController::class . '@esSampleRead');
        //-ES更新-详情
        Route::put('/es_sample/:id', EsSampleController::class . '@esSampleUpdate');
        //-ES删除-详情
        Route::delete('/es_sample/:id', EsSampleController::class . '@esSampleDelete');
        //-ES批量新增
        Route::post('/es_sample/batch-save', EsSampleController::class . '@esSampleBatchSave');
        //-ES批量更新
        Route::put('/es_sample/batch-update', EsSampleController::class . '@esSampleBatchUpdate');

    }

})->pattern(['id' => '\d+'])->allowCrossDomain();


//用户以上权限
Route::group('user/demo', function () {



})->pattern(['id' => '\d+'])->middleware('ApiAuth',['user_auth'])->allowCrossDomain();


//管理以上权限
Route::group('admin/demo', function () {



})->pattern(['id' => '\d+'])->middleware('ApiAuth',['admin_auth'])->allowCrossDomain();

//管理以上权限
Route::group('demo', function () {



})->pattern(['id' => '\d+'])->middleware('ApiAuth',['admin_auth'])->allowCrossDomain();


//系统以上权限
Route::group('demo', function () {


})->pattern(['id' => '\d+'])->middleware('ApiAuth',['system_auth'])->allowCrossDomain();