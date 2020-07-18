<?php
namespace app\api\controller\v1;

use think\Controller;

class ErrorUrl extends Controller
{
    /**
     * 功能：处理非法url请求
     * @return \think\response\Json
     */
    public function errorUrl(){
        return json([
            'code'=>'403',
            'msg'=>'非法请求'
        ]);
    }
}