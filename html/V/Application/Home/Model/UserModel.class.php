<?php
namespace Home\Model;
use Think\Model;
class UserModel extends Model {
    public function register($data){

        $data_arr['uname'] = $data['username'];
        $data_arr['upwd'] = $data['userpwd'];
        $this->add($data_arr);
    }

    public function login($user_name,$user_pwd){

        $user_info = $this ->where("uname = '{$user_name}' AND upwd = '{$user_pwd}'")->find();
        return $user_info;
    }

}