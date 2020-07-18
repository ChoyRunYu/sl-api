<?php
namespace app\api\controller\v1\admin;

use app\api\controller\v1\admin\common\BaseController;
use app\api\service\admin\AuthorizationService;
use app\api\service\admin\UserService;
use think\Cache;
use think\captcha\Captcha;
use think\Exception;
use think\Request;
use think\Session;

class User extends BaseController
{
    /**
     * 功能：获取用户信息接口
     * @param Request $request
     * @return bool
     */
    public function getUserInfo(Request $request){
        $token = $request->header('Authorization');
        $userService = new UserService();
        $authorizationService = new AuthorizationService();
        //解析token获取token中的username参数
        $jwtReturnData = $authorizationService->checkToken($token)['data'];
        $username = $jwtReturnData['data'][0]->username;
        $userInfo = $userService->getUserMoreInfo($username);
        if($userInfo){
            $res = ['code' => 200, 'msg' => '请求成功','data' => $userInfo];
        }else{
            $res = ['code' => 403, 'msg' => '请求数据失败'];
        }
        return $res;
    }

    /**
     * 修改密码控制器层
     * @param Request $request
     * @return array
     * @throws \think\Exception\DbException
     */
    public function updatePwd(Request $request){
        $userService = new UserService();
        $authorizationService = new AuthorizationService();
        $oldPassword = $request->param('oldPassword')?:null;
        $newPassword = $request->param('newPassword')?:null;
        $token = $request->header('Authorization')?:null;
        if ($oldPassword == null || $oldPassword == ''){
            return ['code' => 10003, 'msg' => '参数错误'];
        }
        if ($newPassword == null || $newPassword == ''){
            return ['code' => 10003, 'msg' => '参数错误'];
        }
        //解析token获取token中的username参数
        $jwtReturnData = $authorizationService->checkToken($token)['data'];
        $username = $jwtReturnData['data'][0]->username;
        //验证密码是否正确
        $res = $userService->checkPwd($username, $oldPassword);
        if(!$res)
            return ['code'=> 50002, 'msg' => '旧密码错误'];
        if($userService->updatePwd($username, $newPassword))
            return ['code' => 200, 'msg'=> '修改成功'];
        return ['code' => 50001, 'msg' => '修改失败'];
    }


    /**
     * 获取所有用户列表
     * @return array
     */
    public function getUserList($start){
        $userService = new UserService();
        $data = $userService->getUserList($start);
        $row = $userService->getUserTotal()?:0;
        if($data){
            $resData = [
                'total'   => $row,
                'data'    => $data
            ];
            return ['code' => 200, 'msg' => '请求成功', 'data'=> $resData];
        }
        return ['code' => 404, 'msg' => '请求数据失败'];
    }

    /**
     * 启用/禁用用户
     * @param Request $request
     * @return array
     * @throws \think\Exception\DbException
     */
    public function setIsUse(Request $request){
        $userService = new UserService();
        $id = $request->put('id')?:null;
        $isUse = $request->put('isUse')?:null;
        if($id != null && $isUse != null){
            $affectRows = $userService->setIsUser($id, $isUse);
            if($affectRows && $affectRows == 1)
                return ['code' => 200, 'msg' => '操作成功'];
        }else {
            return ['code' => 10003, 'msg' => '参数有误'];
        }
        return ['code' => 10002, 'msg' => '操作失败'];
    }

    /**
     * 删除用户操作
     * @param $id
     * @return array
     */
    public function delUser($id){
        $userService = new UserService();
        if ($id == 0 || !is_numeric($id) || strpos($id,".")!==false){
            return ['code' => 10003, 'msg' => '参数错误'];
        }
        $res = $userService->delUser($id);
        if($res && $res == 1){
            return ['code' => 200, 'msg' => '删除成功'];
        }
        return ['code' => 10002, 'msg' => '删除失败'];
    }

    /**
     * 批量删除用户操作
     * @param Request $request
     * @return array
     */
    public function delMoreUser(Request $request){
        $ids = $request->delete("ids/a")?:null;
        //  /a -> 强制转属组
        if(!$ids || !is_array($ids))
            return ['code' => 10003, 'msg' => '参数错误'];
        $userService = new UserService();
        $affectRows = $userService->delMoreUser($ids);
        if ($affectRows > 0)
            return ['code' => 200, 'msg' => '删除成功'];
        return ['code' => 10002, 'msg' => '删除失败'];
    }

    /**
     * 修改用户信息
     * @param $id
     * @return array
     * @throws \think\Exception\DbException
     */
    public function editUser($id) {
        if ($id == 0 || !is_numeric($id) || strpos($id,".")!==false){
            return ['code' => 10003, 'msg' => '参数错误'];
        }
        $request = Request::instance();
        $userService = new UserService();
        $nickname = $request->put('nickname')?:null;
        $role = $request->put('role')?:null;
        if ($nickname == null || $nickname == '' || $role == null || $role == '')
            return ['code' => 10003, 'msg' => '参数错误'];
        $res = $userService->editUser($id, $nickname, $role);
        if($res && $res == 1)
            return ['code' => 200, 'msg' => '修改成功'];
        return ['code' => 10002, 'msg' => '修改失败'];
    }

    /**
     * 创建用户
     * @return array
     */
    public function addUser(){
        $userService = new UserService();
        //获取浏览器传输的json数据
        $jsonData = $GLOBALS['HTTP_RAW_POST_DATA'];
        $jsonData = json_decode($jsonData);
        try{
            $user['username'] = $jsonData->username?:null;
            $user['password'] = $jsonData->password?:null;
            $user['nickname'] = $jsonData->nickname?:null;
            $user['role'] = $jsonData->role?:null;
        }catch (Exception $e){
            return ['code' => 10003, 'msg' => '参数错误'];
        }
        if($user['username'] == null || $user['username'] == ''){
            return ['code' => 10003, 'msg' => '参数错误'];
        }
        if($user['nickname'] == null || $user['nickname'] == ''){
            return ['code' => 10003, 'msg' => '参数错误'];
        }
        if($user['password'] == null || $user['password'] == ''){
            return ['code' => 10003, 'msg' => '参数错误'];
        }
        if($user['role'] == null || $user['role'] == ''){
            return ['code' => 10003, 'msg' => '参数错误'];
        }
        if(!$userService->checkUsernameIsExist($user['username'])){
            return ['code' => 10002, 'msg' => '用户名已存在'];
        }
        $res = $userService->addUser($user);
        if($res == 1){
            return ['code' => 200, 'msg' => '创建成功'];
        }
        return ['code' => 10002, 'msg' => '创建失败'];
    }

    /**
     * @param $id
     * @return array
     * @throws Exception\DbException
     */
    public function resetUserPwd($id){
        if ($id == 0 || !is_numeric($id) || strpos($id,".")!==false){
            return ['code' => 10003, 'msg' => '参数错误'];
        }
        $userService = new UserService();
        //获取浏览器传输的json数据
        $jsonData = $GLOBALS['HTTP_RAW_POST_DATA'];
        $jsonData = json_decode($jsonData);
        try{
            $password = $jsonData->password;
        }catch (Exception $e){
            return ['code' => 10003, 'msg' => '参数错误'];
        }
        $res = $userService->resetUserPwd($id, $password);
        if($res && $res == 1){
            return ['code' => 200, 'msg' => '重置成功'];
        }
        return ['code' => 10002, 'msg' => '重置失败'];
    }
}