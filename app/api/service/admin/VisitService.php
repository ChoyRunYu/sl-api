<?php
namespace app\api\service\admin;

use app\api\model\Visit as VisitModel;

/**
 * 获取访问量数据模型操作
 * Class VisitService
 * @package app\api\service\admin
 */
class VisitService
{
    /**
     * 功能：获取访问量数据
     * @return array
     */
    public function getVisitInfo(){
        $visitModel = new VisitModel();
        $today = strtotime(date("Y-m-d"));//获取今天年月日的时间戳
        //获取总访问数的pv和uv
        $totalVisit = $visitModel->field('pv,uv')
            ->where('is_total','=',1)
            ->limit(1)
            ->select();
        //获取今天的pv和uv
        $todayVisit = $visitModel->field('pv,uv')
            ->where('is_total','=',0)
            ->where('time','=',$today)
            ->limit(1)
            ->select();
        $data = [
            "totalPv"  =>  0,
            "totalUv"  =>  0,
            "todayPv"  =>  0,
            "todayUv"  =>  0
        ];
        //总访问数不为null
        if ($totalVisit){
            $data["totalPv"] = $totalVisit[0]['pv'];
            $data["totalUv"] = $totalVisit[0]['uv'];
        }
        //今日访问数不为null
        if ($todayVisit){
            $data["todayPv"] = $todayVisit[0]['pv'];
            $data["todayUv"] = $todayVisit[0]['uv'];
        }
        return $data;
    }

    /**
     * 功能：获取一定天数的访问数量
     * @param $days
     * @return array
     */
    public function getVisitByDay($days){
        $visitModel = new VisitModel();
        $today = strtotime(date("Y-m-d"));//获取今天年月日的时间戳
        //要返回的数据
        $time = [];//时间列表
        $pv = [];  //pv列表,默认数据全为0
        $uv = [];  //uv列表,默认数据全为0
        //生成默认数据列表
        for($i = 0; $i < $days; $i++) {
            array_unshift($time, date('Y-m-d',$today - $i * 86400));
            array_unshift($pv, 0);
            array_unshift($uv, 0);
        }
        //获取几天前的数据
        $data = $visitModel->field('pv,uv,time')
            ->where('is_total', '=', 0)
            ->where('time', '<=', $today)
            ->where('time', '>', $today - $days*86400)
            ->order('time desc')
            ->limit($days)
            ->select();
        for($i = 0; $i < count($data); $i++) {
            //算出数据库取出的时间数据是前几天,0为今天，1为昨天
            $day = ($today-$data[$i]["time"])/86400;
            //将pv跟uv存放到列表中
            $pv[$days-$day-1] = $data[$i]['pv'];
            $uv[$days-$day-1] = $data[$i]['uv'];
        }
        $res = [
            'time'  => $time,
            'pv'    => $pv,
            'uv'    => $uv
        ];
        return $res;
    }
}