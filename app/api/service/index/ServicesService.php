<?php
namespace app\api\service\index;

use app\api\model\Service as ServiceModel;

/**
 * Class ServicesService
 * 述：处理获取服务中心页面api的service服务类
 * @package app\api\service\index
 */
class ServicesService
{
    /**
     * 功能：用于获取服务中心页面的服务列表服务类方法
     * @return mixed
     */
    public function getServiceList(){
        $serviceModel = new ServiceModel();
        $res = $serviceModel->field('id,title,desc,img,link')
            ->select();
        return $res;
    }
}