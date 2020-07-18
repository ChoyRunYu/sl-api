<?php
namespace app\api\service\index;

use app\api\model\Activity as ActivityModel;

/**
 * Class ActivityService
 * 描述：活动模型的操作类
 * @package app\api\service\index
 */
class ActivityService
{
    /**
     * 功能：获取近期活动模型操作
     * @param $start 开始索引
     * @return mixed
     */
    public function getActivityList($start){
        $activityModel = new ActivityModel();
        $res = $activityModel->field('id,name,start_time,end_time,place,organizer,status')
            ->limit($start,15)
            ->order('create_time desc')
            ->select();
        return $res;
    }

    /**
     * 功能：获取近期活动总条数
     * @return int
     */
    public function getActivityTotal(){
        $activityModel = new ActivityModel();
        $res = $activityModel->field('id')
            ->select();
        return count($res);
    }
}