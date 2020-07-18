<?php
namespace app\api\model;

use think\Model;
use think\Request;

/**
 * Class Banner banner模型类
 * @package app\api\model
 */
class Banner extends Model
{
    protected $table = 'tb_banner';

    /**
     * 功能：获取器，用于格式化创建时间字段，将时间戳生成xxxx-xx-xx的日期格式
     * @param $value
     * @return false|string
     */
    public function getCreateTimeAttr($value){
        return date("Y-m-d",$value);
    }

    /**
     * 功能：获取器，用于格式化海报图片访问路径
     * @param $value
     * @return string
     */
    public function getImgAttr($value){
        $request = Request::instance();
        $domain = $request->domain();
        return $domain.$value;
    }
}