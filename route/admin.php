<?php
use think\facade\Route;
use domain\admin\port\controller\AdminController;

//开放权限
Route::group('admin', function () {

    //只对测试开放-正式接口不要放这里
    if(config('app.app_debug')) {

    }

    Route::put('/login', AdminController::class . '@adminLogin');

})->pattern(['id' => '\d+'])->allowCrossDomain();


//用户以上权限
Route::group('admin', function () {


})->pattern(['id' => '\d+'])->middleware('ApiAuth',['user_auth','admin_auth','system_auth'])->allowCrossDomain();


//管理以上权限
Route::group('admin', function () {

    //管理-获取-自己的信息
    Route::get('/me', AdminController::class . '@adminMeRead');
    //管理员-更新-自己的详情
    Route::put('/me', AdminController::class . '@adminMeUpdate');

})->pattern(['id' => '\d+'])->middleware('ApiAuth',['admin_auth','system_auth'])->allowCrossDomain();


//系统以上权限
Route::group('admin', function () {

    //系统-新增-管理员
    Route::post('/sys', AdminController::class . '@adminSysSave');
    //系统-获取-管理员列表
    Route::get('/sys-list', AdminController::class . '@adminSysIndex');
    //系统-获取-管理员详情
    Route::get('/sys/:id', AdminController::class . '@adminSysRead');
    //系统-更新-管理员详情
    Route::put('/sys/:id', AdminController::class . '@adminSysUpdate');
    //系统-删除-管理员信息
    Route::delete('/sys/:id', AdminController::class . '@adminSysDelete');

})->pattern(['id' => '\d+'])->middleware('ApiAuth',['system_auth'])->allowCrossDomain();