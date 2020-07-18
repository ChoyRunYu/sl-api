<?php
namespace app\api\model;

use think\Model;
use think\Request;

/**
 * Class Infos
 * 描述：网站信息模型类
 * @package app\api\model
 */
class Info extends Model
{
    protected $table = 'tb_info';
    protected $updateTime = 'update_time';
    protected $autoWriteTimestamp = true;
    /**
     * 功能：获取器，用于格式化微信公众号二维码访问路径
     * @param $value
     * @return string
     */
    public function getWechatImgAttr($value){
        $request = Request::instance();
        $domain = $request->domain();
        return $domain.$value;
    }

    /**
     * 功能：获取器，用于格式化主页关于我们组件的照片访问路径
     * @param $value
     * @return string
     */
    public function getSrcAttr($value){
        $request = Request::instance();
        $domain = $request->domain();
        return $domain.$value;
    }
}