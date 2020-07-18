<?php
namespace app\api\service\index;

use app\api\model\Banner as BannerModel;

/**
 * Class BannerService
 * 描述：处理获取视频api的service服务类
 * @package app\api\service\index
 */
class BannerService
{
    /**
     * 功能：获取首页banner的模型操作方法
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getBannerIndex(){
        $bannerModel = new BannerModel();
        $res = $bannerModel->where('is_use','=',1)
            ->field('id,title,img,create_time')
            ->limit(6)
            ->order('create_time desc')
            ->select();
        return $res;
    }
}