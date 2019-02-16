<?php

/*
 * 账号设置控制器
 * */
class AccountAction extends CommonAction{
    /*
     *基本信息 视图
     * */
    public function index(){
        $where = array('user_id' => session('user_id'));
        $field = array('username','truename','sex','constellation','intro','location','face180');
        $userinfo = M('userinfo') -> where($where) -> field($field) -> find();
        $this -> userinfo = $userinfo;
        //$this -> assign('userinfo',$userinfo);
        $this -> display();
    }
    /*
     * 修改基本信息表单 处理
     * */
    public function editBasicHandle(){
        if(!$this -> isPost()){
            halt('页面不存在，请稍候重试！');
        }
        $data = array(
            'username' => $this -> _post('nickname'),
            'truename' => $this -> _post('truename'),
            'sex' => (int) $_POST['sex'],
            'location' => $this -> _post('province').' '.$this -> _post('city'),
            'constellation' => $this -> _post('night'),
            'intro' => $this -> _post('intro')
        );
        $where = array('user_id' => session('user_id'));
        if(M('userinfo') -> where($where) -> save($data)){
            $this -> success('保存成功',U('index'));
        }else{
            $this -> error('保存失败');
        }
    }
    /*
     * 修改密码表单 处理
     * */
    public function editPwdHandle(){
        if(!$this -> isPost()){
            halt('页面不存在，请稍候重试！');
        }
        $old = M('user') -> where() -> getField('password');
        if($this -> _post('old','md5') != $old){
            $this -> error('旧密码不正确');
        }
        if($this -> _post('new') != $this -> _post('newed')){
            $this -> error('两次密码不一致');
        }
        $data = array(
            'id' => session('user_id'),
            'password' => $this -> _post('new',md5)
        );
        if(M('user') -> save($data)){
            $this -> success('保存成功',U('index'));
        }else{
            $this -> error('保存失败');
        }
    }
}