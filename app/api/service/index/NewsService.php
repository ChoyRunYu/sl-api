<?php

namespace app\api\service\index;

use app\api\model\News as NewsModel;

/**
 * Class NewsService
 * 描述：处理获取新闻信息api的service服务类
 * @package app\api\service\index
 */
class NewsService
{
    /**
     * 功能：获取首页新闻api的模型操作
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNewsIndex()
    {
        $newsModel = new NewsModel();
        $res = $newsModel->where('is_up', '=', 1)
            ->field('id,title,img,create_time')
            ->limit(6)
            ->order('create_time desc')
            ->select();
        return $res;
    }

    /**
     * 功能：分页获取新闻页面的模型操作
     * @param $start
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNewsPage($start)
    {
        $newsModel = new NewsModel();
        $res = $newsModel->where('is_up', '=', 1)
            ->field('id,title,img,article,create_time')
            ->limit($start, 4)
            ->order('create_time desc')
            ->select();
        return $res;
    }

    /**
     * 功能：获取新闻列表总条数
     * @return int
     */
    public function getNewsListTotal()
    {
        $newModel = new NewsModel();
        $res = $newModel->field('id')
            ->select();
        return count($res);
    }


    /**
     * 功能：获取一条新闻信息
     * @param $id
     * @return mixed
     */
    public function getOneNew($id)
    {
        $newModel = new NewsModel();
        $res = $newModel->field('id,title,create_time,editor,article')
            ->where('is_up', '=', 1)
            ->where('id','=',$id)
            ->limit(1)
            ->select();
        return $res;
    }
}