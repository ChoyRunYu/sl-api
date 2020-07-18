<?php
namespace app\api\model;

use think\Model;

/**
 * Class Activities
 * 描述：近期活动页面活动模型
 * @package app\api\model
 */
class Activity extends Model
{
    protected $table = 'tb_activity';

    /**
     * 功能:格式化活动数据的开始时间
     * @param $value
     * @return false|string
     */
    public function getStartTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }

    /**
     * 功能：格式化活动数据的结束时间
     * @param $value
     * @return false|string
     */
    public function getEndTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }
}