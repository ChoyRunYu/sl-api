<?php
namespace app\api\lib\exception;

use api\lib\exception\BaseException;
use Exception;
use think\exception\Handle;

class ExceptionHandle extends Handle
{
    private $httpCode;//http响应状态码
    private $msg;//错误详情信息
    private $code;//自定义状态码

    /**
     * 自定义错误处理，网站简单，暂时只需要500错误
     * 只处理程序报错
     * @param Exception $e
     * @return \think\Response|\think\response\Json
     */
    public function render(Exception $e)
    {
        //if这部分按理来说没什么用
//        if($e instanceof BaseException){
//            $this->httpCode = $e->httpCode;
//            $this->msg = $e->msg;
//            $this->code = 111;
//        }else{
            $this->httpCode = 200;
            $this->msg = '服务器内部出错';
            $this->code = 500;
//        }
        $result=[
            'code' => $this->code,
            'msg' =>$this->msg
        ];
        return json($result,$this->httpCode);
    }
}