<?php
namespace app\api\model;

use think\Model;
use think\Request;

/**
 * Class News
 * 描述：新闻模型类
 * @package app\api\model
 */
class News extends Model
{
    protected $table = 'tb_news';

    /**
     * 功能：获取器，用于格式化创建时间字段，将时间戳生成xxxx-xx-xx的日期格式
     * @param $value
     * @return false|string
     */
    public function getCreateTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }

    /**
     * 功能：获取器，用于格式化新闻图片访问路径
     * @param $value
     * @return string
     */
    public function getImgAttr($value){
        $request = Request::instance();
        $domain = $request->domain();
        return $domain.$value;
    }
}