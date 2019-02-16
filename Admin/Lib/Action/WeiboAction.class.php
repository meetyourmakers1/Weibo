<?php

/*
 * 微博管理控制器
 * */
class WeiboAction extends CommonAction{
    /*
     * 原创微博列表 视图
     * */
    public function index(){
        import('ORG.Util.Page');
        $where = array('isturn' => 0);
        $totalCount = M('weibo') -> where($where) -> count();

        $page = new Page($totalCount,10);
        $limit = $page -> firstRow . ',' . $page -> listRows;

        $this -> weibo = D('WeiboView') -> where($where) -> limit($limit) -> order('time DESC') -> select();
        $this -> page = $page -> show();
        $this -> display();
    }
    /*
     * 删除微博 处理
     * */
    public function delWeibo(){
        $weiboId = $this -> _get('id','intval');
        $userId = $this -> _get('user_id','intval');
        if(D('WeiboRelation') -> relation(true) -> delete($weiboId)){
            M('userinfo') -> where(array('user_id' => $userId)) -> setDec('weibo');
            $this -> success('删除成功！',$_SERVER['HTTP_REFERER']);
        }else{
            $this -> error('删除失败！');
        }
    }
    /*
     * 转发微博列表 视图
     * */
    public function turn(){
        import('ORG.Util.Page');
        $where = array('isturn' => array('GT',0));
        $totalCount = M('weibo') -> where($where) -> count();
        $page = new Page($totalCount,10);
        $limit = $page -> firstRow . ',' . $page -> listRows;

        $weiboView = D('WeiboView');
        unset($weiboView -> viewFields['picture']);

        $this -> turnWeibo =  $weiboView-> where($where) -> limit($limit) -> order('time DESC') -> select();
        $this -> page = $page -> show();
        $this -> display();
    }
    /*
     * 检索微博视图/表单 处理
     * */
    public function searchWeibo(){
        if(isset($_GET['search']) && !empty($_GET['search'])){
            $where = array('content' => array('LIKE', '%' . $this -> _get('search') .'%'));
            $weibo = D('WeiboView') -> where($where) -> order('time DESC') -> select();
            $this -> weibo = $weibo ? $weibo : false;
        }
        $this -> display();
    }
    /*
     *评论列表 视图
     * */
    public function comment(){
        import('ORG.Util.Page');
        $totalCount = M('comment') -> count();
        $page = new Page($totalCount,10);
        $limit = $page ->firstRow . ',' . $page -> listRows;
        $this -> comment = D('CommentView') -> limit($limit) -> order('time DESC') -> select();
        $this -> page = $page -> show();
        $this -> display();
    }
    /*
     * 删除评论 处理
     * */
    public function delComment(){
        $id = $this -> _get('id','intval');
        $weiboId = $this -> _get('weibo_id','intval');
        if(M('comment') -> delete($id)){
            M('weibo') -> where(array('id' => $weiboId)) -> setDec('comment');
            $this -> success('删除成功！',$_SERVER['HTTP_REFERER']);
        }else{
            $this -> error('删除失败！');
        }
    }
    /*
     * 检索评论 视图
     * */
    public function searchComment(){
        if(isset($_GET['search']) && !empty($_GET['search'])){
            $where = array('content' => array('LIKE', '%' . $this -> _get('search') .'%'));
            $comment = D('CommentView') -> where($where) -> order('time DESC') -> select();
            $this -> comment = $comment ? $comment : false;
        }
        $this -> display();
    }
}