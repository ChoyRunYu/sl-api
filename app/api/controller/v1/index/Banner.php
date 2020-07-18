<?php
namespace app\api\controller\v1\index;
use think\Controller;
use app\api\service\index\BannerService;

/**
 * Class Banner
 * 功能：用于获取首页海报api的控制器
 * @package app\api\controller\v1\index
 */
class Banner extends Controller{

    /**
     * 功能：获取首页海报控制器方法
     * @return array
     * code=>200,数据请求成功
     * code=>403,数据请求失败
     */
    public function getBannerIndex(){
        $bannerService = new BannerService();
        $data = $bannerService->getBannerIndex();
        if($data){
            $res = ['code' => 200, 'msg' => '请求成功', 'data' => $data];
        }else{
            $res = ['code' => 404, 'msg' => '请求数据失败'];
        }
        return $res;
    }
}
