<?php

/*
 * 用户管理控制器
 * */
class UserAction extends CommonAction{
    /*
     * 微博用户列表 视图
     * */
    public function index(){
        import('ORG.Util.Page');
        $totalCount = M('user') -> count();
        $page = new Page($totalCount,10);
        $limit = $page -> firstRow . ',' . $page -> listRows;
        $this -> userinfo = D('UserView') -> limit($limit) -> select();
        $this -> page = $page -> show();
        $this -> display();
    }
    /*
     * 锁定/解锁 用户 处理
     * */
    public function lockUser(){
       $data = array(
           'id' => $this -> _get('id','intval'),
           'lock' => $this -> _get('lock','intval')
       );
       $msg = $data['lock'] ? '锁定' : '解锁';
       if(M('user') -> save($data)){
           $this -> success($msg.'成功！',$_SERVER['HTTP_REFERER']);
       }else{
           $this -> error('失败！');
       }
    }
    /*
     * 检索用户视图 / 表单 处理
     * */
    public function searchUser(){
        if(isset($_GET['type']) && isset($_GET['search'])){
            $where = $_GET['type'] ? array('id' => $this -> _get('search','intval')) :
                array('username' => array('LIKE', '%' . $this -> _get('search') .'%'));
            $user = D('UserView') -> where($where) -> select();
            $this -> user = $user ? $user : false;;
        }
        $this -> display();
    }
    /*
     * 后台管理员列表 视图
     * */
    public function admin(){
        $this -> admin = M('admin') -> select();
        $this -> display();
    }
    /*
     * 锁定/解锁 管理员 处理
     * */
    public function lockAdmin(){
        $data = array(
            'id' => $this -> _get('id','intval'),
            'lock' => $this -> _get('lock','intval')
        );
        $msg = $data['lock'] ? '锁定' : '解锁';
        if(M('admin') -> save($data)){
            $this -> success($msg . '成功',$_SERVER['HTTP_REFERER']);
        }else{
            $this -> error($msg . '失败');
        }
    }
    /*
     * 删除管理员 处理
     * */
    public function delAdmin(){
        $id = $this -> _get('id','intval');
        if(M('admin') -> delete($id)){
            $this -> success('删除成功',U('admin'));
        }else{
            $this -> error('删除失败');
        }
    }
    /*
     * 添加管理员 视图
     * */
    public function addAdmin(){
        $this -> display();
    }
    /*
     * 添加管理员表单 处理
     * */
    public function addAdminHandle(){
        if(!$this -> isPost()){
            halt('页面不存在，请稍候重试！');
        }
        if($_POST['pwd'] != $_POST['pwded']){
            $this -> error('两次密码不一致！');
        }
        $data = array(
            'username' => $this -> _post('username'),
            'password' => $this -> _post('pwd','md5'),
            'logintime' => time(),
            'loginip' => get_client_ip(),
            'admin' => $this -> _post('admin','intval')
        );
        if(M('admin') -> data($data) -> add()){
            $this -> success('添加成功',U('admin'));
        }else{
            $this -> error('添加失败');
        }
    }
    /*
     * 修改管理员密码 视图
     * */
    public function editPwd(){
        $this -> display();
    }
    /*
     * 修改管理员密码表单 处理
     * */
    public function editPwdHandle(){
        if(!$this -> isPost()){
            halt('页面不存在，请稍候重试！');
        }
        $admin = M('admin');
        $old = $admin -> where(array('id' => session('user_id'))) -> getField('password');
        if($_POST['pwd'] != $_POST['pwded']){
            $this -> error('两次密码不一致！');
        }
        if(md5($_POST['old']) != $old){
            $this -> error('旧密码不正确！');
        }
        $data = array(
            'id' => session('user_id'),
            'password' => $this -> _post('pwd','md5')
        );
        if($admin -> save($data)){
            $this -> success('修改成功',U('Index/copy'));
        }else{
            $this -> error('修改失败');
        }
    }
}