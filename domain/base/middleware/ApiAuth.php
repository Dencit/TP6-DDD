<?php

namespace domain\base\middleware;

use domain\base\error\BaseError;
use domain\base\exception\Exception;

class ApiAuth extends BaseAuth
{

    public function handle($request, \Closure $next, $scopes = []){

        //检查授权
        $auth = $this->apiAuth();
        $scopeId = $auth->scope_id;
        $request->auth = $auth;
        
        if( !in_array($scopeId,$scopes) ){
            Exception::app(BaseError::code('AUTH_SCOPE_FAIL'),BaseError::msg('AUTH_SCOPE_FAIL'));
        }

        //建立auth授权数据对象
        $authData = Auth::instance();
        $authData->scopeId = $auth->scope_id;
        $authData->clientId = $auth->client_id;
        $authData->role = $auth->role;
        $authData->userId = $auth->user_id ?? 0;
        $authData->adminId = $auth->admin_id ?? 0;

        $response = $next($request);
        return $response;
    }


}