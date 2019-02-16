<?php

/*
 * 首页控制器
 * */
class IndexAction extends CommonAction{

    /*public function __construct(){
        parent::__construct();
        if(!isset($_SESSION['user_id'])){
            redirect(U('Login/index'));
        }
    }*/
    /*
     * 首页视图
     * */
    public function index(){
        echo time();
        //取得当前用户的ID与当前用户所有关注的好友的ID
        $userId = array(session('user_id'));

        $where = array('fans' => session('user_id'));
        if(isset($_GET['group_id'])){
            $groupId = $this -> _get('group_id','intval');
            $where['group_id'] = $groupId;
            $userId = array();
            $this -> name = $this -> _get('name');
        }

        $followId = M('follow_fans') -> where($where) -> field('follow') -> select();
        if($followId){
            foreach($followId as $k => $v){
                $userId[] = $v['follow'];
            }
        }
        //组合WHERE条件，条件为当前用户自己的ID与当前用户所有关注好友的ID
        $where = array('user_id' => array('IN',$userId));
        //分页
        import('ORG.Util.Page');
        $totalCount = D('WeiboView') -> where($where) -> count();
        $page = new Page($totalCount,10);
        $limit = $page -> firstRow . ',' . $page -> listRows;
        //读取当前用户与他所有关注好友的微博
        $this -> weibo = D('WeiboView') -> getAllWeibo($where,$limit);
        $this -> page = $page -> show();
        $this -> display();
    }
    /*
     * 发布微博表单 处理
     * */
    public function sendWeibo(){
        if(!$this ->isPost()){
            halt('页面不存在，请稍候重试!');
        }
        $data = array(
            'content' => $this -> _post('content'),
            'time' => time(),
            'user_id' => session('user_id')
        );
        if($id = M('weibo') -> data($data) -> add()){
            if(!empty($_POST['picture'])){
                //做点有意义的事
            }
            M('userinfo') -> where(array('user_id' => session('user_id'))) -> setInc('weibo');
            //@用户 处理
            $this -> _atmeHandle($data['content'],$id);
            $this -> success('发布成功',$_SERVER['HTTP_REFERER']);
        }else{
            $this -> error('发布失败');
        }
    }
    /*
     * @用户 处理
     * */
    private function _atmeHandle($data,$id){
        $preg = '/@(\S+?)\s/is';
        preg_match_all($preg,$data,$arr);
        if(!empty($arr[1])){
            $userinfo = M('userinfo');
            $atme = M('atme');
            foreach($arr[1] as $k => $v){
                $userId = $userinfo -> where(array('username' => $v)) -> getField('user_id');
                if($userId){
                    $data = array(
                        'user_id' => $userId,
                        'weibo_id' => $id
                    );
                    //缓存@用户的消息到内存
                    cache_msg($userId,3);
                    $atme -> data($data) -> add();
                }
            }
        }
    }
    /*
     * 转发微博表单 处理
     * */
    public function turnHandle(){
        if(!$this -> isPost()){
            halt('页面不存在，请稍候重试！');
        }
        //原微博ID
        $weiboId = $this -> _post('weibo_id');
        //多重转发的原微博ID
        $turnId = $this -> _post('turn_id','intval');
        //内容/评论
        $content = $this -> _post('content');
        //插入数据
        $data = array(
            'content' => $content,
            'isturn' => $turnId ? $turnId : $weiboId,
            'time' => time(),
            'user_id' => session('user_id')
        );
        $weibo = M('weibo');
        //插入数据到微博表
        if($id = $weibo -> data($data) -> add()){
            //原微博被转发数 +1
            $weibo -> where(array('id' => $weiboId)) -> setInc('turn');
            //多重转发原微博被转发数 +1
            if($turnId){
                $weibo -> where(array('id' => $turnId)) -> setInc('turn');
            }
            //用户转发微博数 +1
            M('userinfo') -> where(array('user_id' => session('user_id'))) -> setInc('weibo');
            //@用户 处理
            $this -> _atmeHandle($data['content'],$id);
            //如果点击 同时评论 插入评论到 评论表
            if(isset($_POST['becomment'])){
                $data = array(
                    'content' => $content,
                    'time' => time(),
                    'user_id' => session('user_id'),
                    'weibo_id' => $weiboId
                );
                //插入评论内容后给原微博评论次数 +1
                if(M('comment') -> data($data) -> add()){
                    $weibo -> where(array('id' => $weiboId)) -> setInc('comment');
                }
            }
            $this -> success('转发成功',$_SERVER['HTTP_REFERER']);
        }else{
            $this -> error('转发失败');
        }
    }
    /*
     * 异步收藏 处理
     * */
    public function keep(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        $userId = session('user_id');
        $weiboId = $this -> _post('weibo_id','intval');
        $keep = M('keep');
        //判断是否已收藏
        $where = array('user_id' => $userId,'weibo_id' => $weiboId);
        if($keep -> where($where) -> getField('id')){
            echo -1;
            exit();
        }
        //添加收藏
        $data = array(
            'user_id' => $userId,
            'time' => $_SERVER['REQUEST_TIME'],
            'weibo_id' => $weiboId
        );
        if($keep -> data($data) -> add()){
            //微博收藏 +1
            M('weibo') -> where(array('id' => $weiboId)) -> setInc('keep');
            echo 1;
        }else{
            echo 0;
        }
    }
    /*
     * 异步评论 处理
     * */
    public function comment(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        //获取评论的数据
        $cdata = array(
            'content' => $this -> _post('content'),
            'time' => time(),
            'user_id' => session('user_id'),
            'weibo_id' => $this -> _post('weibo_id')
        );
        //评论内容插入评论表
        if(M('comment') -> data($cdata) -> add()){
            $weibo = M('weibo');

            //微博评论 +1
            $weibo -> where(array('id' => $cdata['weibo_id'])) -> setInc('comment');

            //被评论微博的发布者用户名
            $userId = $this -> _post('user_id','intval');
            $username = M('userinfo') -> where(array('user_id' => $userId)) -> getField('username');
            //评论 同时 转发时 处理
            if($_POST['isturn']){
                //读取转发微博内容
                $field = array('content','isturn');
                $turnWeibo = $weibo -> field($field) -> find($cdata['weibo_id']);
                $content = $turnWeibo['isturn'] ? $cdata['content'] . '//@' . $username . ': '.$turnWeibo['content']: $cdata['content'];

                //同时转发到我的微博的  数据
                $wdata = array(
                    'content' => $content,
                    'isturn' => $turnWeibo['isturn'] ? $turnWeibo['isturn'] : $cdata['weibo_id'],
                    'time' => $cdata['time'],
                    'user_id' => $cdata['user_id']
                );
                if($weibo -> data($wdata) -> add()){
                    $weibo -> where(array('id' => $cdata['weibo_id'])) -> setInc('turn');
                }
                exit();
            }

            //读取评论用户信息
            $where = array('user_id' => $cdata['user_id']);
            $field = array('face50' => 'face','username','user_id');
            $userinfo = M('userinfo') -> where($where) -> field($field) -> find();

            //组合评论字符串返回
            $str = '<dl class="comment_content"><dt><a href="' . U('/'.$cdata['user_id']) . '">';
            $str .= '<img src="'. __ROOT__ ;
            if($userinfo['face']){
                $str .= '/Uploads/Face/'.$userinfo['face'];
            }else{
                $str .= 'Public/Images/noface.gif';
            }
            $str .= '" alt="" width="30" height="30">';
            $str .= '</a></dt><dd>';
            $str .= '<a href="'. U('/'.$cdata['user_id']) .'" class="commemt_name">';
            $str .= $userinfo['username'];
            $str .= '</a>:' . replace_content($cdata['content'] ). '&nbsp;&nbsp;&nbsp;&nbsp;(' . time_format($cdata['time']) . ')';
            $str .= '<div class="reply">';
            $str .= '<a href="">回复</a>';
            $str .= '</div></dd></dl>';
            //缓存评论的信息到内存
            cache_msg($userId,1);
            echo $str;
        }else{
            echo 'false';
        }
    }
    /*
     *异步获取评论  处理
     * */
    public function getComment(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        //延缓执行若干秒
        sleep(1);
        $weiboId = $this -> _post('weibo_id','intval');
        $where = array('weibo_id' => $weiboId);
        //数据的总条数
        $totalCount = M('comment') -> where($where) -> count();
        //数据可分的总页数
        $totalPage = ceil($totalCount / 4);
        //当前的页码
        $page = isset($_POST['page']) ? $this -> _post('page','intval') : 1;
        $limit = $page < 2 ? '0,4' : (4 * ($page -1)) . ',4';

        $comment = D('CommentView') -> where($where) -> limit($limit) -> order('time DESC') -> select();
        if($comment){
            $str = '';
            foreach($comment as $k => $v){
                $str .= '<dl class="comment_content"><dt><a href="' . U('/'.$v['user_id']) . '">';
                $str .= '<img src="'. __ROOT__ ;
                if($v['face']){
                    $str .= '/Uploads/Face/'.$v['face'];
                }else{
                    $str .= 'Public/Images/noface.gif';
                }
                $str .= '" alt="" width="30" height="30">';
                $str .= '</a></dt><dd>';
                $str .= '<a href="'. U('/'.$v['user_id']) .'" class="commemt_name">';
                $str .= $v['username'];
                $str .= '</a>:' . replace_content($v['content'] ). '&nbsp;&nbsp;&nbsp;&nbsp;(' . time_format($v['time']) . ')';
                $str .= '<div class="reply">';
                $str .= '<a href="">回复</a>';
                $str .= '</div></dd></dl>';
            }
            if($totalPage > 1){
                $str .= '<dl class="comment-page">';
                    switch ($page){
                        case $page > 1 && $page < $totalPage :
                            $str .= '<dd page=" ' . ($page - 1) . ' " weibo_id=" ' .$weiboId. ' ">上一页</dd>';
                            $str .= '<dd page=" ' . ($page + 1) . ' " weibo_id=" ' .$weiboId. ' ">下一页</dd>';
                            break;
                        case $page < $totalPage :
                            $str .= '<dd page=" ' . ($page + 1) . ' " weibo_id=" ' .$weiboId. ' ">下一页</dd>';
                            break;
                        case $page == $totalPage :
                            $str .= '<dd page=" ' . ($page - 1) . ' " weibo_id=" ' .$weiboId. ' ">上一页</dd>';
                            breack;
                    }
                $str .= '</dl>';
            }
            echo $str;
        }else{
            echo 'false';
        }
    }
    /*
     * 异步删除微博
     * */
    public function delWeibo(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        //获取删除微博的ID
        $weiboId = $this -> _post('weibo_id','intval');
        if(M('weibo') -> delete($weiboId)){
            M('userinfo') -> where(array('user_id' => session('user_id'))) -> setDec('weibo');
            //如果微博有图
            $picture = M('picture');
            $image = $picture -> where(array('weibo_id' => $weiboId)) -> find();
            if($image){
                //删除对应图片记录
                $picture -> delete($image['id']);
                //删除图片文件
                @unlink('./Uploads/Picture/'.$image['max']);
                @unlink('./Uploads/Picture/'.$image['medium']);
                @unlink('./Uploads/Picture/'.$image['mini']);
            }
            $keep = M('keep');
            $keeps = $keep -> where(array('weibo_id' => $weiboId)) -> find();
            if($keeps){
                $keep -> where(array('weibo_id' => $weiboId)) -> delete();
            }
            $comment = M('comment');
            $comments = $comment -> where(array('weibo_id' => $weiboId)) -> find();
            if($comments){
                $comment -> where(array('weibo_id' => $weiboId)) -> delete();
            }
            $atme = M('atme');
            $atmes = $atme -> where(array('weibo_id' => $weiboId)) -> find();
            if($atmes){
                $atme -> where(array('weibo_id' => $weiboId)) -> delete();
            }
            echo 1;
        }else{
            echo 0;
        }
    }
}