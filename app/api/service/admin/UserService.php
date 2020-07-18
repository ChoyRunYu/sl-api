<?php
namespace app\api\service\admin;

use app\api\controller\v1\admin\User;
use app\api\model\User as UserModel;
use think\console\command\make\Model;

class UserService
{
    /**
     * 功能：检验登录帐号密码是否正确
     * @param $username
     * @param $password
     * @return int  0  登录成功
     * @return int  1  帐号已禁用
     * @return int  2  用户名或密码错误
     */
    public function checkUser($username,$password){
        $userModel = new UserModel();
        $user = $userModel->field('password,is_use')
            ->where('username','=',$username)
            ->select();
        if(!$user){//用户不存在
            return 2;
        }
        if($user[0]['is_use'] == 0){//用户已禁用
            return 1;
        }
        return password_verify($password,$user[0]['password'])?0:2;
    }

    /**
     * 功能：获取用户详细信息
     * @param $username
     * @return bool
     */
    public function getUserMoreInfo($username){
        $userModel = new UserModel();
        $res = $userModel->field('id,username,nickname,last_time_login,last_ip_login,role,is_use')
            ->where('username','=',$username)
            ->limit(1)
            ->select();
        return $res?$res:false;
    }

    /**
     * 功能：获取用户id和帐号信息
     * @param $username
     * @return bool
     */
    public function getUserInfo($username){
        $userModel = new UserModel();
        $res = $userModel->field('id,username,role')
            ->where('username','=',$username)
            ->limit(1)
            ->select();
        return $res?$res:false;
    }

    /**
     * 功能：更新用户登录ip和登录时间
     * @param $ip
     * @param $time
     */
    public function updateLogin($ip,$time,$username){
        $userModel = new UserModel();
        $affectRows = $userModel->where('username', '=' , $username)
            ->update([
                'last_ip_login'  => $ip,
                'last_time_login' => $time
            ]);
        return $affectRows;
    }

    /**
     * 获取用户密码，用于修改密码时的比对
     * @param $username
     * @return bool
     */
    public function checkPwd($username,$password){
        $userModel = new UserModel();
        $pwd = $userModel->field('password')
            ->where('username', '=', $username)
            ->limit(1)
            ->select();
        return password_verify($password, $pwd[0]['password']);
    }

    /**
     * 修改密码的操作
     * @param $username
     * @param $newPass
     * @return false|int
     * @throws \think\Exception\DbException
     */
    public function updatePwd($username, $newPass){
        $userModel = new UserModel();
        $affectRows = $userModel->where('username', '=', $username)
            ->update(['password' => password_hash($newPass, PASSWORD_DEFAULT)]);
        return $affectRows;
    }

    /**
     * 获取用户列表
     * @return mixed
     */
    public function getUserList($start){
        $userModel = new UserModel();
        $res = $userModel->field('id,username,nickname,role,is_use,last_time_login,last_ip_login')
            ->limit($start, 8)
            ->order('id')
            ->select();
        return $res;
    }

    /**
     * 统计用户列表总数
     * @return int
     */
    public function getUserTotal(){
        $userModel = new  UserModel();
        $total = $userModel->count();
        return $total;
    }

    /**
     * 启用/禁用用户
     * @param $id
     * @param $isUse
     * @return false|int
     * @throws \think\Exception\DbException
     */
    public function setIsUser($id, $isUse){
        $userModel = new UserModel();
        $userModel = $userModel->get($id);
        if ($userModel){
            $affectRows = $userModel->save([
                'is_use' => $isUse
            ]);
            return $affectRows;
        }else{
            return false;
        }
    }

    /**
     * 删除用户
     * @param $id
     * @return int
     * @throws \think\Exception\DbException
     */
    public function delUser($id){
        $userModel = (new UserModel())->get($id);
        if($userModel){
            return $userModel->delete();
        }
        return false;
    }

    /**
     * 批量删除用户
     * @param $ids
     * @return bool
     */
    public function delMoreUser($ids){
        $res = UserModel::destroy($ids);
        return $res?:false;
    }


    /**
     * 修改用户信息
     * @param $id
     * @param $nickname
     * @param $role
     * @return bool
     * @throws \think\Exception\DbException
     */
    public function editUser($id,$nickname,$role){
        $userModel = (new UserModel())->get($id);
        if($userModel){
            $affectRows = $userModel->save([
                'nickname'  => $nickname,
                'role'      => $role
            ]);
            return $affectRows;
        }
        return false;
    }

    /**
     * 添加用户
     * @param $user
     * @return false|int
     */
    public function addUser($user){
        $userModel = new UserModel();
        $userModel->data([
            'username'  => $user['username'],
            'nickname'  => $user['nickname'],
            'password'  => password_hash($user['password'], PASSWORD_DEFAULT),
            'role'      => $user['role'],
            'is_use'    => 0
        ]);
        return $userModel->save();
    }

    /**
     * 验证用户名是否存在
     * @param $username
     * @return bool
     */
    public function checkUsernameIsExist($username){
        $userModel = new UserModel();
        $count = $userModel->where('username', '=', $username)
            ->count();
        return $count == 0? true: false;
    }

    /**
     * 管理员重置用户密码
     * @param $id
     * @param $password
     * @return bool|false|int
     * @throws \think\Exception\DbException
     */
    public function resetUserPwd($id, $password){
        $userModel = new UserModel();
        $userModel = $userModel::get($id);
        if($userModel){
            return $userModel->save([
                'password'  => password_hash($password, PASSWORD_DEFAULT)
            ]);
        }
        return false;
    }
}