<?php
namespace app\api\model;

use think\Model;

/**
 * 用户类
 * Class User
 * @package app\api\model
 */
class User extends Model
{
    protected $table = 'tb_user';
    protected $updateTime = 'update_time';
    protected $autoWriteTimestamp = true;

    public function getLastTimeLoginAttr($value){
        return date("Y-m-d H:i:s", $value);
    }
}