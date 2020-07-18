<?php

namespace app\api\controller\v1\index;

use api\lib\exception\NotFoundException;
use app\api\service\index\VideoService;
use think\Controller;

/**
 * Class Video
 * 描述：用于获取视频数据api的控制器
 * @package app\api\controller\v1\index
 */
class Video extends Controller
{
    /**
     * 功能：用于处理获取首页视频版块数据的api方法
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getVideoIndex()
    {
        $videoService = new VideoService();
        $data = $videoService->getVideoIndex();
        if ($data) {
            $res = ['code' => 200, 'msg' => '请求成功', 'data' => $data];

        } else {
            $res = ['code' => 404, 'msg' => '请求数据失败'];
        }
        return $res;
    }

    /**
     * 功能：用于处理分页获取微视频页面视频列表数据的api方法
     * @param $start
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getVideoList($start)
    {
        if (($start % 12) != 0) {
            $res = ['code' => 500, 'msg' => '参数错误'];
        } else {
            $videoService = new VideoService();
            $total = $videoService->getVideoListTotal();
            $data = $videoService->getVideoList($start);
            $newData = [
                'total' => $total,
                'videoList' => $data
            ];
            if ($newData) {
                $res = ['code' => 200, 'msg' => '请求成功', 'data' => $newData];
            } else {
                $res = ['code' => 404, 'msg' => '请求数据失败'];
            }
        }
        return $res;
    }

    /**
     * 功能：获取一条视频信息
     * @param $id
     * @return array
     */
    public function getOneVideo($id)
    {
        $videoService = new VideoService();
        $data = $videoService->getOneVideo($id);
        if (!$data) {
            $res = ['code' => 404, 'msg' => '请求数据失败'];
        } else {
            $res = ['code' => 200, 'msg' => '请求成功', 'data' => $data];
        }
        return $res;
    }

}