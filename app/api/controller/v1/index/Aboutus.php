<?php

namespace app\api\controller\v1\index;

use app\api\service\index\InfoService;
use app\api\service\index\AboutusService;
use think\Controller;

/**
 * Class Aboutus
 * 描述：用于获取关于我们以及网站信息的控制器
 * @package app\api\controller\v1\index
 */
class Aboutus extends Controller
{
    /**
     * 功能：获取footer版块数据控制器方法
     * @return array
     */
    public function getFooterInfo()
    {
        $infoService = new InfoService();
        $data = $infoService->getFooterInfo();
        if ($data) {
            $res = ['code' => 200, 'msg' => '请求成功', 'data' => $data[0]];
        } else {
            $res = ['code' => 404, 'msg' => '请求数据失败'];
        }
        return $res;
    }

    /**
     * 功能：获取aboutus版块数据控制器方法
     * @return array
     */
    public function getAboutusInfo()
    {
        $infoService = new InfoService();
        $data = $infoService->getAboutusIndex();
        if ($data) {
            $res = ['code' => 200, 'msg' => '请求成功', 'data' => $data[0]];
        } else {
            $res = ['code' => 404, 'msg' => '请求数据失败'];
        }
        return $res;
    }

    /**
     * 功能：获取aboutus页面数据
     * @return array
     */
    public function getAboutusPage()
    {
        $aboutusService = new AboutusService();
        $nav = $aboutusService->getAboutusPageNav();
        $content = $aboutusService->getAboutusPageContent();
        $data = [
            'nav'  => $nav,
            'content'  => $content
        ];
        if ($data) {
            $res = ['code' => 200, 'msg' => '请求成功', 'data' => $data];
        } else {
            $res = ['code' => 404, 'msg' => '请求数据失败'];
        }
        return $res;
    }
}