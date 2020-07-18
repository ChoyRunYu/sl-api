<?php
namespace app\api\controller\v1\admin;

use think\Controller;
use think\Exception;
use think\Request;
use app\api\service\admin\UserService;
use app\api\service\admin\AuthorizationService;
use think\captcha\Captcha;

class Authorization extends Controller
{

    /**
     * 功能：后台管理系统实现登录功能
     * @param Request $request
     * @return array
     */
    public function authorization(Request $request){
        //初始化服务类以及验证码
        $userService = new UserService();
        $authorizationService = new AuthorizationService();
        $captcha = new Captcha();
        //获取浏览器传输的json数据
        $jsonData = $GLOBALS['HTTP_RAW_POST_DATA'];
        $jsonData = json_decode($jsonData);
        try{
            $username = $jsonData->username;
            $password = $jsonData->password;
            $captchaCode = $jsonData->captcha;
        }catch (Exception $e){
            return ['code'  => 103, 'msg' => '登录失败'];
        }
        //获取登录ip以及登录时间
        $loginIp = $request->ip();
        $loginTime = time();
        //验证验证码
        if(!$captcha->check($captchaCode)) {
            return ['code' => 103, 'msg' => '验证码错误'];
        }
        //调用验证用户名密码模块
        $flag = $userService->checkUser($username,$password);
        //判断帐号密码是否正确
        if($flag == 2){
            return ['code' =>  102,'msg'  =>  '用户名或密码错误'];
        }
        //判断用户是否被禁用
        if($flag == 1){
            return ['code' =>  101,'msg'  =>  '用户已被禁用'];
        }
        //通过验证后的流程
        if($flag == 0){
            $userInfo = $userService->getUserInfo($username);//获取用户信息
            if(!$userInfo) {//验证是否能获取到用户信息
                return ['code'  => 103, 'msg' => '登录失败'];
            }
            $token = $authorizationService->createToken($userInfo);//生成token令牌
            if($token && $token['code'] == 200){//生成token成功
                $userService->updateLogin($loginIp, $loginTime ,$username);//更新登录ip以及时间
                return ['code'  => 200, 'msg' => '登录成功', 'token' => $token['token']];

            }else{
                return ['code'  => 103, 'msg' => '登录失败'];
            }
        }
    }

    /**
     * 功能：生成验证码
     * @return \think\Response
     */
    public function getCaptcha(){
        $captcha = new Captcha();
        return $captcha->entry();
    }
}