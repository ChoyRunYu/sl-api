<?php
namespace app\api\service\admin;
use \Firebase\JWT;

class AuthorizationService
{
    private $tokenKey = "stlhtxjb";//token密钥

    /**
     * 功能：创建token
     * @param string $data
     * @param int $exp_time
     * @return array
     */
    public function createToken($data = "",$exp_time = 0){
        vendor('firebase.JWT');//加载第三方插件
        try{
            $key = $this->tokenKey;
            $time = time();//获取当前时间
            $token['iat'] = $time;//设置签发时间
            $token['nbf'] = $time;//设置生效时间
            if(!$exp_time){
                $exp_time = 3600;//设置1小时过期
            }
            $token['exp'] = $time + $exp_time;//设置过期时间
            if($data){//如果需要携带数据则添加数据
                $token['data'] = $data;
            }
            $jwt = \Firebase\JWT\JWT::encode($token,$key);//生成token
            $returnData = [
                'code' => 200,
                'msg'  => '登录成功',
                'token'=> $jwt
            ];
        }catch(\Firebase\JWT\ExpiredException $e){//签名不正确
            $returnData = [
                'code' => 104,
                'msg'  => $e->getMessage(),
            ];
        }catch (\Exception $e){//其他错误
            $returnData = [
                'code' => 199,
                'msg'  => $e->getMessage(),
            ];
        }
        return $returnData;
    }

    /**
     * 功能：检验token是否有效
     * @param $jwt
     * @return array
     */
    public function checkToken($jwt){
        vendor('firebase.JWT');
        $key = $this->tokenKey;
        try{
            \Firebase\JWT\JWT::$leeway = 60;//设置时间偏差
            $decode = \Firebase\JWT\JWT::decode($jwt,$key,['HS256']);//验证token是否有效
            $arr = (array)$decode;
            $returnData = [
                'code'  => 200,
                'msg'   => '已登录',
                'data'  => $arr
            ];
        }catch (\Firebase\JWT\SignatureInvalidException $e){//签名不正确
            $returnData = [
                'code'  => 101,
                'msg'   => $e->getMessage()
            ];
        }catch (\Firebase\JWT\BeforeValidException $e){//签名在某个时间点后才能用
            $returnData = [
                'code'  => 102,
                'msg'   => $e->getMessage()
            ];
        }catch (\Firebase\JWT\ExpiredException $e){//token过期
            $returnData = [
                'code'  => 103,
                'msg'   => $e->getMessage()
            ];
        }catch (\Exception $e){//其他错误
            $returnData = [
                'code'  => 199,
                'msg'   => $e->getMessage()
            ];
        }
        return $returnData;
    }
}