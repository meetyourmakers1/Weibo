<?php

/**
 * 用户个人页控制器
 */
class UserAction extends CommonAction{
    //个人页页面
    public function index(){
        //$userId 用户的ID
        $userId = $this -> _get('user_id','intval');
        //$userId = intval($_GET['user_id']);

        //读取用户个人信息
        $where = array('user_id' => $userId);
        $userinfo = M('userinfo') -> where($where) -> field('truename,face50,face80,style','true') -> find();
        if(!$userinfo){
            redirect(__APP__,3,'用户不存在，跳转到首页......');
            exit();
        }
        $this -> userinfo = $userinfo;
        $totalCount = M('weibo') -> where($where) -> count();
        import('ORG.Util.Page');
        $page = new Page($totalCount,10);
        $limit = $page -> firstRow.','.$page -> listRows;

        //读取用户发布的微博
        $this -> weibo = D('WeiboView') -> getAllWeibo($where,$limit);
        $this -> page = $page -> show();

        //我的关注
        if(S('follow_'.$userId)){
            //缓存已生成，并且缓存未过期
            $follow = S('follow_'.$userId);
        }else{
            //生成缓存
            $where = array('fans' => $userId);
            $follow = M('follow_fans') -> where($where) -> field('follow') -> select();
            foreach($follow as $k => $v){
                $follow[$k] = $v['follow'];
            }
            $where = array('user_id' => array('IN',$follow));
            $field = array('username','face50' => 'face','user_id');
            $follow = M('userinfo') -> where($where) -> field($field) -> limit(8) -> select();
            S('follow_'.$userId,$follow,3600);
        }
        //我的粉丝
        if(S('fans_'.$userId)){
            //缓存已生成，并且缓存未过期
            $fans = S('fans_'.$userId);
        }else{
            //生成缓存
            $where = array('follow' => $userId);
            $fans = M('follow_fans') -> where($where) -> field('fans') -> select();
            foreach($fans as $k => $v){
                $fans[$k] = $v['fans'];
            }
            $where = array('user_id' => array('IN',$fans));
            $field = array('username','face50' => 'face','user_id');
            $fans = M('userinfo') -> where($where) -> field($field) -> limit(8) -> select();
            S('fans_'.$userId,$fans,3600);
        }
        $this -> fans = $fans;
        $this -> follow = $follow;
        $this -> display();
    }
    /*
     * 用户关注与粉丝列表 视图
     * */
    public function followFansList(){
        $userId = $this -> _get('user_id','intval');
        //区分 关注 或 粉丝(1: 关注, 2: 粉丝)
        $type = $this -> _get('type','intval');
        //根据$type参数不同，读取用户关注ID 或 粉丝ID
        $where = $type ? array('fans' => $userId) : array('follow' => $userId);
        $field = $type ? 'follow' : 'fans';
        $follow_fans = M('follow_fans');
        $totalCount = $follow_fans -> where($where) -> count();

        import('ORG.Util.Page');
        $page = new Page($totalCount,10);
        $limit = $page -> firstRow.','.$page -> listRows;

        $followFansId = $follow_fans -> where($where) -> field($field) -> limit($limit) -> select();

        if($followFansId){
            //重组数组 用户关注ID 或 粉丝ID为一维数组
            foreach($followFansId as $k => $v){
                $followFansId[$k] = $type ? $v['follow'] : $v['fans'];
            }

            $where = array('user_id' => array('IN',$followFansId));
            $field = array('face50' => 'face','username','sex','location','follow','fans','weibo','user_id');
            //获取用户信息
            $userinfo = M('userinfo') -> where($where) -> field($field) -> select();
            $this -> userinfo = $userinfo;
        }
        //获取当前登录用户的所有关注ID
        $where = array('fans' => session('user_id'));
        $follow = $follow_fans -> where($where) -> field('follow') -> select();
        if($follow){
            //重组数组 关注ID为一维数组
            foreach($follow as $k => $v){
                $follow[$k] = $v['follow'];
            }
        }
        //获取当前登录用户的所有粉丝ID
        $where = array('follow' => session('user_id'));
        $fans = $follow_fans -> where($where) -> field('fans') -> select();
        if($fans){
            //重组数组 粉丝ID为一维数组
            foreach($fans as $k => $v){
                $fans[$k] = $v['fans'];
            }
        }

        $this -> follow = $follow;
        $this -> fans = $fans;
        $this -> type = $type;
        $this -> count = $totalCount;
        $this -> page = $page -> show();

        $this -> display();
    }
    /*
     * 收藏列表 视图
     * */
    public function keep(){
        import('ORG.Util.Page');
        $userId = session('user_id');
        $totalCount = M('keep') -> where(array('user_id' => $userId)) -> count();
        $page = new Page($totalCount,10);
        $where = array('keep.user_id' => $userId);
        $limit = $page -> firstRow.','.$page -> listRows;
        $this -> weibo = D('KeepView') -> getAll($where,$limit);
        $this -> page = $page -> show();
        $this -> display('weiboList');
    }
    /*
     * 异步取消收藏 处理
     * */
    public function cancelKeep(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        $keepId = $this -> _post('keep_id','intval');
        $weiboId = $this -> _post('weibo_id','intval');
        if(M('keep') -> delete($keepId)){
            M('weibo') -> where(array('id' => $weiboId)) -> setDec('keep');
            echo 1;
        }else{
            echo 0;
        }
    }
    /*
     * 私信列表 视图
     * */
    public function letter(){
        import('ORG.Util.Page');
        $userId = session('user_id');
        cache_msg($userId,2,true);
        $totalCount = M('letter') -> where(array('user_id' => $userId)) -> count();
        $page = new Page($totalCount,10);
        $where = array('letter.user_id' => $userId);
        $limit = $page -> firstRow.','.$page -> listRows;
        $letter = D('LetterView') -> where($where) -> limit($limit) -> order('time DESC') -> select();
        $this -> count = $totalCount;
        $this -> letter = $letter;
        $this -> page = $page -> show();
        $this -> display();
    }
    /*
     * 发送私信表单 处理
     * */
    public function letterSend(){
        if(!$this -> isPost()){
            halt('页面不存在，请稍候重试！');
        }
        $username = $this -> _post('name');
        $where = array('username' => $username);
        $userId = M('userinfo') -> where($where) -> getField('user_id');
        if(!$userId){
            $this -> error('用户不存在');
        }
        $data = array(
            'from' => session('user_id'),
            'content' => $this -> _post('content'),
            'time' => time(),
            'user_id' => $userId
        );
        if(M('letter') -> data($data) -> add()){
            //缓存私信的信息到内存
            cache_msg($userId,2);
            $this -> success('发送成功',U('letter'));
        }else{
            $this -> error('发送失败');
        }
    }
    /*
     * 异步删除私信 处理
     * */
    public function delLetter(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        $letterId = $this -> _post('letter_id','intval');
        if(M('letter') -> delete($letterId)){
            echo 1;
        }else{
            echo 0;
        }
    }
    /*
     * 评论列表 视图
     * */
    public function comment(){
        cache_msg(session('user_id'),1,true);
        import('ORG.Util.Page');
        $where = array('user_id' => session('user_id'));
        $totalCount = M('comment') -> where($where) -> count();
        $page = new Page($totalCount,10);
        $limit = $page -> firstRow.','.$page -> listRows;
        $comment = D('CommentView') -> where($where) -> limit($limit) -> order('time DESC') -> select();
        $this -> count = $totalCount;
        $this -> comment = $comment;
        $this -> page = $page -> show();
        $this -> display();
    }
    /*
     * 异步评论回复 处理
     * */
    public function reply(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        $data = array(
            'content' => $this -> _post('content'),
            'time' => time(),
            'user_id' => session('user_id'),
            'weibo_id' => $this -> _post('weibo_id')
        );
        if(M('comment') -> data($data) -> add()){
            M('weibo') -> where(array('id' => $data['weibo_id'])) -> setInc('comment');
            echo 1;
        }else{
            echo 0;
        }
    }
    /*
     * 异步删除评论 处理
     * */
    public function delComment(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        $commentId = $this -> _post('comment_id','intval');
        $weiboId = $this -> _post('weibo_id','intval');
        if(M('comment') -> delete($commentId)){
            M('weibo') -> where(array('id' => $weiboId)) -> setDec('comment');
            echo 1;
        }else{
            echo 0;
        }
    }
    /*
     * @提到我的 视图
     * */
    public function atme(){
        cache_msg(session('user_id'),3,true);
        $where = array('user_id' => session('user_id'));
        $weiboId = M('atme') -> where($where) -> field('weibo_id') -> select();
        if($weiboId){
            foreach($weiboId as $k => $v){
                $weiboId[$k] = $v['weibo_id'];
            }
        }
        import('ORG.Util.Page');
        $totalCount = count($weiboId);
        $page = new Page($totalCount,10);
        $where = array('id' => array('IN',$weiboId));
        $limit = $page -> firstRow . '' . $page -> listRows;
        $weibo = D('WeiboView') -> getAllWeibo($where,$limit);
        $this -> weibo =$weibo;
        $this -> page = $page -> show();
        $this -> atme = 1;
        $this -> display('weiboList');

    }
    /**
     * 空操作$name
     * @param $name
     */
    public function _empty($name){
        $this -> _getUserIdUrl($name);
    }
    /*
     * 用户空操作处理，用$username获取用户ID ,跳转到用户个人页
     * */
    private function _getUserIdUrl($username){
        $username = htmlspecialchars($username);
        $where = array('username' => $username);
        $userId = M('userinfo') -> where($where) -> getField('user_id');
        if(!$userId){
            redirect(U('Index/index'));
        }else{
            redirect(U('/'.$userId));
        }
    }
}