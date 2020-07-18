<?php
namespace app\api\service\admin;

use app\api\model\Info as InfoModel;

class InfoService
{
    /**
     * 获取网站属性
     * @return mixed
     */
    public function getInfo(){
        $infoModel = new InfoModel();
        $res = $infoModel->field('name,src,address,desc,link1,link2,link1_desc,link2_desc,wechat_img')
            ->where('id', '=', '1')
            ->select();
        return $res;
    }

    /**
     * 更新主页关于我们版块的网站配置信息
     * @param $desc
     * @param $src
     * @return int|string
     * @throws \think\Exception
     */
    public function updateAboutusInfo($desc,$src){
        $infoModel = new InfoModel();
        $infoModel = $infoModel->get(1);
        $res = $infoModel->save([
           "desc" => $desc,
           "src"  => $src
        ]);
        return $res;
    }

    public function updateFooterInfo($name,$address,$wxcode,
        $link1,$link1desc,$link2, $link2desc){
        $infoModel = new InfoModel();
        $infoModel = $infoModel->get(1);
        $res = $infoModel->save([
            'name'       => $name,
            'address'    => $address,
            'link1'      => $link1,
            'link1_desc' => $link1desc,
            'link2'      => $link2,
            'link2_desc' => $link2desc,
            'wechat_img' => $wxcode
        ]);
        return $res;
    }
}