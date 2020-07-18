<?php

namespace app\api\model;

use think\Model;
use think\Request;

/**
 * Class Video
 * 描述：视频模型类
 * @package app\api\model
 */
class Video extends Model
{
    protected $table = 'tb_videos';

    /**
     * 功能：获取器，用于格式化创建时间字段，将时间戳生成xxxx-xx-xx的日期格式
     * @param $value
     * @return false|string
     */
    public function getCreateTimeAttr($value)
    {
        return date('Y-m-d H:i:s',$value);
    }

    /**
     * 功能：获取器，用于格式化视频海报图片的访问地址
     * @param $value
     * @return string
     */
    public function getPosterAttr($value){
        $request = Request::instance();
        $domain = $request->domain();
        return $domain.$value;
    }

}