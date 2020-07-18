<?php
namespace app\api\controller\v1\admin;

use app\api\controller\v1\admin\common\BaseController;
use app\api\service\admin\VisitService;

class Visit extends BaseController
{
    /**
     * 功能：获取访问量数据
     * @return array
     */
    public function getVisitInfo(){
        $visitService = new VisitService();
        $data = $visitService->getVisitInfo();
        if ($data){
            return ['code' => 200, 'msg' => '请求成功', 'data' => $data];
        }else{
            return ['code' => 404, 'msg' => '请求数据失败'];
        }
    }

    public function getVisitByDay($days){
        $visitService = new VisitService();
        $data = $visitService->getVisitByDay($days);
        if ($data){
            return ['code' => 200, 'msg' => '请求成功', 'data' => $data];
        }else{
            return ['code' => 404, 'msg' => '请求数据失败'];
        }
    }
}