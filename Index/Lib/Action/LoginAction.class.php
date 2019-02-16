<?php

/*
 * 注册与登录控制器
 * */
class LoginAction extends Action{
    /*
     * 登录页面
     * */
    public function index(){
        $this -> display();
    }
    /*
     * 注册页面
     * */
    public function regist(){
        if(!C('REGIST_ON')){
            $this -> error('网站暂停注册',U('index'));
        }
        $this -> display();
    }
    /*
     * 注册表单处理
     * */
    public function registHandle(){
        if(!$this -> isPost()){
            halt('页面不存在，请稍候再试！');
        }
        if($_SESSION['verify'] != md5($_POST['verify'])){
            $this -> error('验证码不正确！');
        }
        if($_POST['pwd'] != $_POST['pwded']){
            $this -> error('两次密码不一致！');
        }
        $data = array(
            'account' => $this -> _post('account'),
            'password' => $this -> _post('pwd','md5'),
            'registime' => $_SERVER['REQUEST_TIME'],    //$_SERVER超全局数组
            'userinfo' => array(
                'username' => $this -> _post('username')
            )
        );
        if($user_id = D('UserRelation') -> insert($data)){
            session('user_id',$user_id);
            header('Content-type:text/html;Charset=UTF-8');
            redirect(__APP__,2,'注册成功，请稍候。。。');
        }else{
            $this -> error('注册失败，请重试。。。');
        };
    }
    /*
     * 登录表单处理
     * */
    public function loginHandle(){
        if(!$this -> isPost()){
            halt('页面不存在，请稍候再试！');
        }
        $account = $this -> _post('account');
        $password = $this -> _post('pwd','md5');
        $where = array('account' => $account);
        $user = M('user') -> where($where) -> find();
        if(!$user || $user['password'] != $password){
            $this -> error('用户名或密码不正确');
        }
        if($user['lock']){
            $this -> error('用户被锁定，不能登录！');
        }
        if(isset($_POST['auto'])){
            $ip = get_client_ip();
            $cookie = $user['account'].'|'.$ip;
            $encryption_cookie = encryption($cookie);
            @setcookie('auto_login',$encryption_cookie,C('AUTO_LOGIN_TIME'),'/');
        }
        session('user_id',$user['id']);
        header('Content-Type:text/html;Charset:UTF-8;');
        redirect(__APP__,0,'登录成功，正在跳转。。。');
    }
    /*
     * 退出登录 处理
     * */
    public function loginOut(){
        //释放所有的会话变量
        session_unset();
        //销毁一个会话中的全部数据
        session_destroy();
        //删除自动登录的COOKIE
        @setcookie('auto_login','',time - 3600,'/');

        redirect(U('index'));
    }
    /*
     * 获取验证码
     * */
    public function getVerify(){
        import('ORG.Util.Image');
        Image::buildImageVerify(1,1,png);
    }
    /*
     * 异步验证账号 处理
     * */
    public function checkAccount(){
        if(!$this -> isAjax()){
            halt('页面不存在,请稍候重试！');
        }
        $account = $this -> _post('account');   //$account = htmlspecialchars($_POST['account']);
        $where = array('account' => $account);
        if(M('user') -> where($where) -> getField('id')){
            echo 'false';
        }else{
            echo 'true';
        }
    }
    /*
     * 异步验证昵称 处理
     * */
    public function checkUsername(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        $username = $this -> _post('username');
        $where = array('username' => $username);
        if(M('userinfo') -> where($where) -> getField('id')){
            echo 'false';
        }else{
            echo 'true';
        }
    }
    /*
     * 异步验证验证码 处理
     * */
    public function checkVerify(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        $verify = $this -> _post('verify');
        if($_SESSION['verify'] != md5($verify)){
            echo 'false';
        }else{
            echo 'true';
        }
    }
}