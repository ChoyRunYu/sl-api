<?php

namespace app\api\service\index;

use app\api\model\Video as VideoModel;

/**
 * Class VideoService
 * 描述：处理获取视频信息api的service服务类
 * @package app\api\service\index
 */
class VideoService
{
    /**
     * 功能：获取首页视频api的模型操作
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getVideoIndex()
    {
        $videoModel = new VideoModel();
        $res = $videoModel->where('is_up', '=', 1)
            ->field('id,title,poster,create_time')
            ->limit(4)
            ->order('create_time desc')
            ->select();
        return $res;
    }

    /**
     * 功能：分页获取视频页面列表api的模型操作
     * @param $start
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getVideoList($start)
    {
        $videoModel = new VideoModel();
        $res = $videoModel->where('is_up', '=', 1)
            ->field('id,title,poster,create_time')
            ->limit($start, 12)
            ->order('create_time desc')
            ->select();
        return $res;
    }


    /**
     * 功能：获取微视频页面列表视频总数
     * @return mixed
     */
    public function getVideoListTotal()
    {
        $videoModel = new VideoModel();
        $res = $videoModel->field('id')
            ->select();
        return count($res);
    }

    /**
     * 功能：获取一条视频信息
     * @param $id
     * @return mixed
     */
    public function getOneVideo($id)
    {
        $videoModel = new VideoModel();
        $res = $videoModel->where('is_up', '=', 1)
            ->field('id,title,poster,src,create_time')
            ->limit(1)
            ->where('id', '=', $id)
            ->select();
        return $res;
    }
}