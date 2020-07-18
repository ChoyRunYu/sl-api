<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

/**
 * 网站前台数据路由
 */
//主页获取bannerapi路由
Route::get('api/:version/index/banner', 'api/:version.index.banner/getBannerIndex');

//新闻中心页面获取新闻列表api路由
Route::get('api/:version/index/news/page/:start', 'api/:version.index.news/getNewsPage');
//获取一条新闻数据api路由
Route::get('api/:version/index/news/:id', 'api/:version.index.news/getOneNew');
//主页获取news版块api路由
Route::get('api/:version/index/news', 'api/:version.index.news/getNewsIndex');

//分页获取微视频页面视频列表api路由
Route::get('api/:version/index/video/page/:start', 'api/:version.index.video/getVideoList');
//微视频页面获取一条视频信息api路由
Route::get('api/:version/index/video/:id', 'api/:version.index.video/getOneVideo');
//主页获取video版块api路由
Route::get('api/:version/index/video', 'api/:version.index.video/getVideoIndex');


//获取footer版块api路由
Route::get('api/:version/index/footer', 'api/:version.index.aboutus/getFooterInfo');

//获取近期活动页面活动列表api路由
Route::get('api/:version/index/activity/page/:start', 'api/:version.index.activity/getActivityList');
//获取服务中心页面服务列表api路由
Route::get('api/:version/index/service', 'api/:version.index.service/getServiceList');

//获取缤纷社团一个社团信息api路由
Route::get('api/:version/index/association/:id', 'api/:version.index.association/getOneAssociation');
//获取缤纷社团页面api路由
Route::get('api/:version/index/association', 'api/:version.index.association/getAssociationPage');

//获取魅力社联页面api路由
Route::get('api/:version/index/aboutus/page', 'api/:version.index.aboutus/getAboutusPage');
//获取aboutus版块api路由
Route::get('api/:version/index/aboutus', 'api/:version.index.aboutus/getAboutusInfo');

/*
 * admin后台管理系统路由
 */
//处理登录接口
Route::post('api/:version/admin/authorization','api/:version.admin.authorization/authorization');
//获取管理员用户信息
Route::get('api/:version/admin/user/info','api/:version.admin.user/getUserInfo');
Route::get('api/:version/admin/user/page/:start', 'api/:version.admin.user/getUserList');
Route::put('api/:version/admin/user/use', 'api/:version.admin.user/setIsUse');
//重置密码
Route::post('api/:version/admin/user/pwd/:id', 'api/:version.admin.user/resetUserPwd');
Route::put("api/:version/admin/user/:id", 'api/:version.admin.user/editUser');
Route::delete("api/:version/admin/user/:id", 'api/:version.admin.user/delUser');
Route::delete("api/:version/admin/user", 'api/:version.admin.user/delMoreUser');
Route::post('api/:version/admin/user', 'api/:version.admin.user/addUser');

//修改密码
Route::put('api/:version/admin/user/pwd','api/:version.admin.user/updatePwd');


//生成验证码
Route::get('api/:version/captcha','api/:version.admin.authorization/getCaptcha');
//获取网站运行情况url->获取访问量api路由
Route::get('api/:version/admin/visit/:days', 'api/:version.admin.visit/getVisitByDay');
Route::get('api/:version/admin/visit', 'api/:version.admin.visit/getVisitInfo');
//获取系统使用率
Route::get('api/:version/admin/system/info', 'api/:version.admin.systemInfo/getSystemInfo');
Route::get('api/:version/admin/system/usage', 'api/:version.admin.systemInfo/getSystemUsage');
Route::get('api/:version/admin/system/disk', 'api/:version.admin.systemInfo/getDiskUsage');

//获取网站属性api路由
Route::get('api/:version/admin/info','api/:version.admin.info/getWebInfo');
Route::post('api/:version/admin/info/aboutus','api/:version.admin.info/updateAboutusInfo');
Route::post('api/:version/admin/info/footer','api/:version.admin.info/updateFooterInfo');

//处理非法url
Route::miss('api/v1.errorUrl/errorUrl');
