<?php

namespace app\api\controller\v1\index;

use app\api\service\index\NewsService;
use think\Controller;

/**
 * Class News
 * 描述：用于获取新闻数据api的控制器
 * @package app\api\controller\v1\index
 */
class News extends Controller
{
    /**
     * 功能：获取主页新闻版块控制器方法
     * @return array
     */
    public function getNewsIndex()
    {
        $newsService = new NewsService();
        $data = $newsService->getNewsIndex();
        if ($data) {
            $res = ['code' => 200, 'msg' => '请求成功', 'data' => $data];
        } else {
            $res = ['code' => 404, 'msg' => '请求数据失败'];
        }
        return $res;
    }

    /**
     * 功能:获取新闻中心页面的数据
     * @param $start
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNewsPage($start)
    {
        if ($start % 4 != 0) {
            $res = ['code' => 500, 'msg' => '参数错误'];
        } else {
            $newsService = new NewsService();
            $total = $newsService->getNewsListTotal();
            $newsList = $newsService->getNewsPage($start);
            $data = [
                'total' => $total,
                'newsList' => $newsList
            ];
            if ($data) {
                $res = ['code' => 200, 'msg' => '请求成功', 'data' => $data];
            } else {
                $res = ['code' => 404, 'msg' => '请求数据失败'];
            }
        }
        return $res;
    }


    /**
     * 功能：获取一条新闻数据
     * @param $id
     * @return array
     */
    public function getOneNew($id)
    {
        $newService = new NewsService();
        $data = $newService->getOneNew($id);
        if ($data) {
            $res = ['code' => 200, 'msg' => '请求成功', 'data' => $data];
        } else {
            $res = ['code' => 404, 'msg' => '请求数据失败'];
        }
        return $res;
    }
}