<?php
namespace app\api\controller\v1\index;

use app\api\service\index\ActivityService;
/**
 * Class Activity
 * 描述:近期活动页面api控制器
 * @package app\api\controller\v1\index
 */
class Activity
{

    /**
     * 功能：用于获取近期活动页面活动列表
     * @param $start
     * @return array
     */
    public function getActivityList($start){
        if(($start % 15) != 0){
            $res = ['code'=>500,'msg'=>'参数错误'];
        }else{
            $ActivityService = new ActivityService();
            $data = $ActivityService->getActivityList($start);
            $total = $ActivityService->getActivityTotal();
            $newData = [
                'total'=>$total,
                'activityList'=>$data
            ];
            if($newData){
                $res=['code'=>200,'msg'=>'请求成功','data'=>$newData];
            }else{
                $res = ['code'=>404,'msg'=>'请求数据失败'];
            }
        }
        return $res;
    }
}