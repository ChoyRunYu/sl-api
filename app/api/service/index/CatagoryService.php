<?php
namespace app\api\service\index;

use app\api\model\Catagory as CatagoryModel;

/**
 * Class CatagoryService
 * @package app\api\service\index
 */
class CatagoryService
{
    /**
     * 功能：获取所有社团类
     * @return mixed
     */
    public function getCatagoryList(){
        $catagoryModel = new CatagoryModel();
        $res = $catagoryModel->field('name,id')
            ->select();
        return $res;
    }
}