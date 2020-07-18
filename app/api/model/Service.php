<?php
namespace app\api\model;

use think\Model;
use think\Request;

/**
 * Class Service
 * 描述：服务中心页面服务项目模型
 * @package app\api\model
 */
class Service extends Model
{
    protected $table = 'tb_service';

    /**
     * 功能：获取器，用于格式化服务项目的图标路径
     * @param $value
     * @return string
     */
    public function getImgAttr($value){
        $request = Request::instance();
        $domain = $request->domain();
        return $domain.$value;
    }
}