<?php
namespace app\api\controller\v1\admin;
use app\api\controller\v1\admin\common\BaseController;
use think\Session;

class News extends BaseController
{
    public function getNewsList(){
        Session::set('code',111);
        return Session::get('code');
    }
    public function getOneNews(){
        return Session::get('code');
    }
}