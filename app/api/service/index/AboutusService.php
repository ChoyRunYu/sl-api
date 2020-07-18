<?php
namespace app\api\service\index;

use app\api\model\Aboutus as AboutusModel;

/**
 * Class AboutusService
 * @package app\api\service\index
 */
class AboutusService
{
    /**
     * 功能：获取魅力社联页面左侧导航栏
     * @return mixed
     */
    public function getAboutusPageNav(){
        $aboutusModel = new AboutusModel();
        $res = $aboutusModel->field('id,nav_name')
            ->order('id asc')
            ->select();
        return $res;
    }

    /**
     * 功能：获取魅力社联页面右侧容器数据
     * @return mixed
     */
    public function getAboutusPageContent(){
        $aboutusModel = new AboutusModel();
        $res = $aboutusModel->field('id,title,article')
            ->order('id asc')
            ->select();
        return $res;
    }
}