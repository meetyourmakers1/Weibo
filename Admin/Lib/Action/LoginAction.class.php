<?php

/*
 * 后台登陆控制器
 * */
class LoginAction extends Action{
    /*
     * 登录页面 视图
     * */
    public function index(){
        $this -> display();
    }
    /*
     * 登录表单 处理
     * */
    public function login(){
        if(!$this -> isPost()){
            halt('页面不存在，请稍后重试！');
        }
        if(!isset($_POST['submit'])){
            return false;
        }
        if($_SESSION['verify'] != md5($_POST['verify'])){
            $this -> error('验证码不正确！');
        }
        $username = $this -> _post('uname');
        $password = $this -> _post('pwd','md5');
        $admin = M('admin');
        $user = $admin -> where(array('username' => $username)) -> find();
        if(!$user || $user['password'] != $password){
            $this -> error('用户名或密码不正确！');
        }
        if($user['lock']){
            $this -> error('账号被锁定！');
        }
        $data = array(
            'id' => $user['id'],
            'logintime' => time(),
            'loginip' => get_client_ip()
        );
        $admin -> save($data);
        session('user_id',$user['id']);
        session('username',$user['username']);
        session('logintime',date('Y-m-d H:i',$user['logintime']));
        session('nowtime',date('Y-m-d H:i',time()));
        session('loginip',$user['loginip']);
        session('admin',$user['admin']);
        $this -> success('正在登陆',__APP__);
    }
    /*
     * 验证码 处理
     * */
    public function verify(){
        import('ORG.Util.Image');
        Image::buildImageVerify(1,1,'png',48,22);
    }
}