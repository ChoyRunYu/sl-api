<?php
namespace app\api\service\index;


use app\api\model\Info as InfoModel;

/**
 * Class InfoService
 * 描述：处理获取网站信息api的service服务类
 * @package app\api\service\index
 */
class InfoService
{
    /**
     * 功能：获取footer数据的模型操作方法
     * @return mixed
     */
    public function getFooterInfo(){
        $infoModel = new InfoModel();
        $res = $infoModel->field('name,address,link1,link1_desc,link2,
        link2_desc,wechat_img')
            ->limit(1)
            ->select();
        return $res;
    }

    /**
     * 功能：获取主页aboutus版块数据的模型操作方法
     * @return mixed
     */
    public function getAboutusIndex(){
        $infoModel = new InfoModel();
        $res = $infoModel->field('name,src,desc')
            ->limit(1)
            ->select();
        return $res;
    }


}