<?php
use think\facade\Route;
use domain\user\port\controller\UserController;

//开放权限
Route::group('/user', function () {

    //只对测试开放-正式接口不要放这里
    if(config('app.app_debug')) {

    }

    //用户-新增-注册
    Route::post('/register', UserController::class . '@userRegister');
    //用户-更新-登录
    Route::put('/login', UserController::class . '@userLogin');

})->pattern(['id' => '\d+'])->allowCrossDomain();


//用户以上权限
Route::group('/user', function () {

    //用户-获取-自己的详情
    Route::get('/me', UserController::class . '@userMeRead');
    //用户-更新-自己的详情
    Route::put('/me', UserController::class . '@userMeUpdate');

})->pattern(['id' => '\d+'])->middleware('ApiAuth',['user_auth','admin_auth','system_auth'])->allowCrossDomain();


//管理以上权限
Route::group('/user', function () {

    //管理员-获取-用户列表
    Route::get('/adm-list', UserController::class . '@userAdmIndex');

    //管理员-获取-用户详情
    Route::get('/adm/:id', UserController::class . '@userAdmRead');

    //管理员-更新-用户详情
    Route::put('/adm/:id', UserController::class . '@userAdmUpdate');


})->pattern(['id' => '\d+'])->middleware('ApiAuth',['admin_auth','system_auth'])->allowCrossDomain();


//系统以上权限
Route::group('/user', function () {

    //系统-删除-用户详情
    Route::delete('/sys/:id', UserController::class . '@userSysDelete');

})->pattern(['id' => '\d+'])->middleware('ApiAuth',['system_auth'])->allowCrossDomain();