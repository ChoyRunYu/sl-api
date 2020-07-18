<?php
namespace app\api\controller\v1\admin;

use app\api\controller\v1\admin\common\BaseController;
use app\api\service\admin\InfoService;
use think\Exception;
use think\exception\PDOException;
use think\Request;

use app\api\model\User as UserModel;

class Info extends BaseController
{
    /**
     * 获取网站配置信息接口
     * @return array
     */
    public function getWebInfo(){
        $infoService = new InfoService();
        $data = $infoService->getInfo();
        if ($data) {
            return ['code' => 200, 'msg'=>'请求成功','data'=>$data[0]];
        } else {
            return ['code' => 404, 'msg' => '请求数据失败'];
        }
    }
    public function updateFooterInfo(Request $request){
        $file = null;
        $picName = "";
        $domain = $request->domain();//获取域名
        if ($request->file('file')!=null && $request->file('file')!='')
            $file = $request->file('file');//获取上传的图片
        //获取参数
        $siteName = $request->param('siteName');
        $address = $request->param('address');
        $link1 = $request->param('link1');
        $link1Desc = $request->param('link1Desc');
        $link2 = $request->param('link2');
        $link2Desc = $request->param('link2Desc');
        $src = $request->param('wechatCodeSrc');
        if(($src != null && $src != '') && ($address != null && $address != '')
            && ($siteName != null && $siteName != '') && ($link1 !=null && $link1 != '')
            && ($link1Desc != null && $link1Desc != '') && ($link2 !=null && $link2 != '')
            && ($link2Desc != null && $link2Desc != '')) {
            if ($file){//判断是否上传文件
                //判断图片是否合法
                if (!$this->checkImg($file))
                    return ['code' => 10004,'msg'=>'图片非法'];
                //判断图片大小
                if (!$this->checkSize($file))
                    return ['code' => 10005,'msg'=>'图片不能超过1M'];
                $name = $file->getInfo('name');//获取图片名
                $newName = 'wxcode';//新文件名
                $ext = pathinfo($name, PATHINFO_EXTENSION);//获取文件拓展名
                $info = $file->move(ROOT_PATH.'public/upload/info', $newName.'.'.$ext);//移动文件到网站根目录
                if($info){
                    $picName = $info->getFilename();//获取保存的文件名
                }else{
                    return ['code' => 10006, 'msg'=>'图片保存出错'];
                }
            }
            //处理更新操作
            if ($picName)//判断是否有新图片上传，有的话则重写src路径
                $src = '/upload/info/'.$picName;//生成新图片路径
            $src = str_replace($domain, "", $src);//替换图片地址中的域名
            $infoService = new InfoService();
            try {
                $affectRows = $infoService->updateFooterInfo($siteName,$address,
                    $src,$link1,$link1Desc,$link2,$link2Desc);
            } catch (PDOException $e) {
            } catch (Exception $e) {
            }
            if ($affectRows == 1) {
                return ['code' => 200, 'msg' => '保存成功'];
            }
        }else{
            return ['code' => 10003, 'msg' => '参数有误'];
        }
        return ['code' => 10002, 'msg' => '保存失败'];
    }

    /**
     * 更新修改主页关于我们版块信息
     * @param Request $request
     * @return array
     */
    public function updateAboutusInfo(Request $request){
        $file = null;
        $picName = "";
        //获取域名
        $domain = $request->domain();
        if ($request->file('file')!=null && $request->file('file')!='')
            $file = $request->file('file');//获取上传的图片
        $desc = $request->param('desc');//获取参数
        $src = $request->param('src');//获取参数
        //判断参数是否为空
        if(($src != null && $src != '') && ($desc != null && $desc != '')) {
            if ($file){//判断是否上传文件
                //判断图片是否合法
                if (!$this->checkImg($file))
                    return ['code' => 10004,'msg'=>'图片非法'];
                //判断图片大小
                if (!$this->checkSize($file))
                    return ['code' => 10005,'msg'=>'图片不能超过1M'];
                $name = $file->getInfo('name');//获取图片名
                $newName = 'aboutus';//新文件名
                $ext = pathinfo($name, PATHINFO_EXTENSION);//获取文件拓展名
                $info = $file->move(ROOT_PATH.'public/upload/info', $newName.'.'.$ext);//移动文件到网站根目录
                if($info){
                    $picName = $info->getFilename();//获取保存的文件名
                }else{
                    return ['code' => 10006, 'msg'=>'图片保存出错'];
                }
            }
            //处理更新操作
            if ($picName)//判断是否有新图片上传，有的话则重写src路径
                $src = '/upload/info/'.$picName;//生成新图片路径
            $src = str_replace($domain, "", $src);//替换图片地址中的域名
            $infoService = new InfoService();
            try {
                $affectRows = $infoService->updateAboutusInfo($desc, $src);//修改操作
            } catch (PDOException $e) {
            } catch (Exception $e) {
            }
            if ($affectRows == 1) {
                return ['code' => 200, 'msg' => '保存成功'];
            }
        }else{
            return ['code' => 10003, 'msg' => '参数有误'];
        }
        return ['code' => 10002, 'msg' => '保存失败'];
    }

    /**
     * 验证照片是否合法
     * @param $file
     * @return bool
     */
    public function checkImg($file){
        $type = strtolower($file->getInfo('type'));
        $name = strtolower($file->getInfo('name'));
        $ext = pathinfo($name, PATHINFO_EXTENSION);//获取图片拓展名
        if (in_array($type, ['image/png', 'image/jpg', 'image/jpeg','image/gif']) &&
            in_array($ext, ['png', 'jpg', 'jpeg', 'gif']))
            return true;
        return false;
    }

    /**
     * 验证照片大小是否小于1m
     * @param $file
     * @return bool
     */
    public function checkSize($file){
        $size = $file->getInfo('size');
        if ($size <= 1024*1024*1){
            return true;
        }
        return false;
    }
}