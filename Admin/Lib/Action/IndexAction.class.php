<?php

/*
 * 后台首页控制器
 * */
class IndexAction extends CommonAction{
    /*
     * 后台首页 视图
     * */
    public function index(){
        $this -> display();
    }
    /*
     * 信息页面 视图
     * */
    public function copy(){
        $admin = M('admin');
        $this -> user = $admin -> count();
        $this -> lock = $admin -> where(array('lock' => 1)) -> count();
        $weibo = M('weibo');
        $this -> weibo = $weibo -> where(array('isturn' => 0)) -> count();
        $this -> turn = $weibo -> where(array('isturn' => array('GT',0))) -> count();
        $this -> comment = M('comment') -> count();
        $this -> display();
    }
    /*
     * 退出登录 处理
     * */
    public function loginOut(){
        session_unset();
        session_destroy();
        redirect(U('Login/index'));
    }
}