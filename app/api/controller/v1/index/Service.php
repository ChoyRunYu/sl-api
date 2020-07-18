<?php
namespace app\api\controller\v1\index;

use think\Controller;
use app\api\service\index\ServicesService;

/**
 * Class Service
 * 描述：用于处理服务中心页面的api控制器
 * @package app\api\controller\v1\index
 */
class Service extends Controller
{
    /**
     * 功能：用于获取服务中心页面服务列表的api方法
     * @return array
     */
    public function getServiceList(){
        $servicesService = new ServicesService();
        $data = $servicesService->getServiceList();
        if($data){
            $res = ['code'=>200,'msg'=>'请求成功','data'=>$data];
        }else{
            $res = ['code'=>404,'msg'=>'请求数据失败'];
        }
        return $res;
    }
}