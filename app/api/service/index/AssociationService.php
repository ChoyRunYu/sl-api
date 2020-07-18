<?php

namespace app\api\service\index;

use app\api\model\Association as AssociationModel;

class AssociationService
{
    /**
     * 功能：获取多彩社团页面的各种类别中包含的社团
     * @param $cid
     * @return mixed
     */
    public function getAssociationList($cid)
    {
        $associationModel = new AssociationModel();
        $res = $associationModel->field('id,name')
            ->where('cid', '=', $cid)
            ->select();
        return $res;
    }

    /**
     * 功能：获取一条社团详情信息
     * @param $id
     * @return mixed
     */
    public function getOneAssociations($id)
    {
        $associationModel = new AssociationModel();
        $res = $associationModel->alias('a')
            ->join('tb_catagory b', 'a.cid = b.id')
            ->field('a.id,a.name,b.name as catagory,a.desc,a.update_time')
            ->where('a.id', '=', $id)
            ->limit(1)
            ->select();
        return $res;
    }
}