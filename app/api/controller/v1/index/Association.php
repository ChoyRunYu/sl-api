<?php

namespace app\api\controller\v1\index;

use app\api\service\index\AssociationService;
use app\api\service\index\CatagoryService;
use think\Controller;

class Association extends Controller
{
    /**
     * 功能：获取多彩社团页面的数据
     * @return array
     */
    public function getAssociationPage()
    {
        $associationService = new AssociationService();
        $catagoryService = new CatagoryService();
        $catagoryList = $catagoryService->getCatagoryList();
        $catagory = [];
        $association = [];
        if ($catagoryList) {
            foreach ($catagoryList as $item) {
                array_push($catagory, $item->name);
                $associationList = $associationService->getAssociationList($item->id);
                if ($associationList) {
                    array_push($association, $associationList);
                }
            }
            if ($association) {
                $res = [
                    'code' => 200,
                    'msg' => '请求成功',
                    'data' => [
                        'catagory' => $catagory, 'association' => $association
                    ]
                ];
            }
        } else {
            $res = ['code' => 404, 'msg' => '请求数据失败'];
        }
        return $res;
    }

    /**
     * 功能：获取一条社团详情信息
     * @param $id
     * @return array
     */
    public function getOneAssociation($id)
    {
        $associationService = new AssociationService();
        $data = $associationService->getOneAssociations($id);
        if ($data) {
            $res = ['code' => 200, 'msg' => '请求成功', 'data' => $data];
        } else {
            $res = ['code' => 404, 'msg' => '请求数据失败'];
        }
        return $res;

    }
}