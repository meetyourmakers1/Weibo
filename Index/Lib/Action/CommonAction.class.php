<?php

/*
 * 公用控制器
 * */
class CommonAction extends Action{
    //初始化方法(自动运行的方法)
    public function _initialize(){
        //自动登录 处理
        if($_COOKIE['auto_login'] && !isset($_SESSION['user_id'])){
            //取出cookie，并且对它解密
            $account_ip = explode('|',encryption($_COOKIE['auto_login'],1));

            $last_login_account = $account_ip[0];   //上次登录的账号
            $last_login_ip = $account_ip[1];    //上次登录IP

            $now_login_ip = get_client_ip();    //本次登录的IP

            //本次登录的IP和上次登录的IP一致
            if($now_login_ip == $last_login_ip){
                $where = array('account' => $last_login_account);
                $user = M('user') -> where($where) -> field(array('id','lock')) -> find();
                //检索用户信息并且用户没被锁定，保存登录状态(保存session)
                if($user && !$user['lock']){
                    session('user_id',$user['id']);
                }
            }            
        }
        //是否登录 处理
        if(!isset($_SESSION['user_id'])){
            redirect(U('Login/index'));
        }
    }
    /*
     * 头像上传表单 处理
     * */
    public function uploadFaceHandle(){
        $upload = $this -> _uploadPicture('Face','180,80,50','180,80,50');
        $where = array('user_id' => session('user_id'));
        $field = array('face180','face80','face50');
        $old = M('userinfo') -> where($where) -> field($field) -> find();
        if(M('userinfo') -> where($where) -> save($upload)){
            if(!empty($old['face180'])){
                /*=====unlink() 函数删除文件=====*/
                @unlink('./Uploads/Face/'.$old['face180']);
                @unlink('./Uploads/Face/'.$old['face80']);
                @unlink('./Uploads/Face/'.$old['face50']);
            }
            $this -> success('保存成功',U('Account/index'));
        }else{
            $this -> error('保存失败');
        }
    }

    /*
     * 异步 创建新分组 处理
     * */
    public function addGroup(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        $data = array(
            'name' => $this -> _post('name'),
            'user_id' => session('user_id')
        );
        if(M('group') -> data($data) -> add()){
            echo json_encode(array('status' => 1,'msg' => '保存成功'));
        }else{
            echo json_encode(array('status' => 0,'msg' => '保存失败'));
        }
    }
    /*
     * 异步添加关注 处理
     * */
    public function addFollow(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        $data = array(
            'follow' => $this -> _post('follow','intval'),
            'fans' => (int) session('user_id'),
            'group_id' => $this -> _post('group','intval')
        );
        if(M('follow_fans') -> data($data) -> add()){
            $userinfo = M('userinfo');
            $userinfo -> where(array('user_id' => $data['follow'])) -> setInc('fans');
            $userinfo -> where(array('user_id' => $data['fans'])) -> setInc('follow');
            echo json_encode(array('status' => 1,'msg' => '关注成功！'));
        }else{
            echo json_encode(array('status' => 0,'msg' => '关注失败！'));
        }
    }
    /*
     * 异步移除关注 /  粉丝 处理
     * */
    public function delFollow(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        $userId = $this -> _post('user_id','intval');
        $type = $this -> _post('type','intval');

        $where = $type ?
            array('follow' => $userId,'fans' => session('user_id')) :
            array('fans' => $userId,'follow' => session('user_id'));
        if(M('follow_fans') -> where($where) -> delete()){
            $userinfo = M('userinfo');
            if($type){
                $userinfo -> where(array('user_id' => session('user_id'))) -> setDec('follow');
                $userinfo -> where(array('user_id' => $userId)) -> setDec('fans');
            }else{
                $userinfo -> where(array('user_id' => session('user_id'))) -> setDec('fans');
                $userinfo -> where(array('user_id' => $userId)) -> setDec('follow');
            }
            echo 1;
        }
        echo 0;
    }
    /*
     * 异步修改模板风格 处理
     * */
    public function style(){
        if(!$this -> isAjax()){
            halt('页面不存在，请稍候重试！');
        }
        $style = $this -> _post('style');
        $where = array('user_id' => session('user_id'));
        if(M('userinfo') -> where($where) -> save(array('style' => $style))){
            echo 1;
        }else{
            echo 0;
        }
    }

    /**
     * 异步轮询推送消息
     */
    public function pushMsg(){
        if(!$this -> isAjax()){
            halt('页面不存在,请稍候重试！');
        }
        $userId = session('user_id');
        $userMsg = S('userMsg'.$userId);
        if($userMsg){
            if($userMsg['comment']['status']){
                $userMsg['comment']['status'] = 0;
                S('userMsg'.$userId,$userMsg,0);
                echo json_encode(array(
                    'status' => 1,
                    'count' => $userMsg['comment']['count'],
                    'type' => 1
                ));
                exit();
            }
            if($userMsg['letter']['status']){
                $userMsg['letter']['status'] = 0;
                S('userMsg'.$userId,$userMsg,0);
                echo json_encode(array(
                    'status' => 1,
                    'count' => $userMsg['letter']['count'],
                    'type' => 2
                ));
                exit();
            }
            if($userMsg['atme']['status']){
                $userMsg['atme']['status'] = 0;
                S('userMsg'.$userId,$userMsg,0);
                echo json_encode(array(
                    'status' => 1,
                    'count' => $userMsg['atme']['count'],
                    'type' => 3
                ));
                exit();
            }
        }
        echo json_encode(array('status' => 0));
    }
    /*
     * 图片上传处理 方法
     * */
    private function _uploadPicture($path,$width,$height){
        import('ORG.Net.UploadFile');   //引入 ThinkPHP 文件上传类
        $obj = new UploadFile();    //实例化文件上传类
        $obj -> maxSize = C('UPLOAD_MAX_SIZE');     //图片上传大小
        $obj -> savePath = C('UPLOAD_SAVE_PATH').$path.'/';   //图片上传目录
        $obj -> saveRule = 'uniqid';    //上传文件保存规则
        $obj -> uploadReplace = true;   //覆盖存在的同名文件
        $obj -> allowExts = C('UPLOAD_ALLOW_EXTS');     //允许上传的文件后缀名
        $obj -> thumb = true;   //生成缩略图
        $obj -> thumbMaxWidth = $width;     //缩略图的宽度
        $obj -> thumbMaxHeight = $height;   //缩略图的高度
        $obj -> thumbPrefix = 'max_,medium_,mini_';     //缩略图的前缀名
        $obj -> thumbPath = $obj -> savePath.date('Y-m').'/';   //缩略图保存路径
        $obj -> thumbRemoveOrigin = true;   //删除原图(生成缩略图后)
        $obj -> autoSub = true;     //使用子目录保存文件
        $obj -> subType = 'date';   //使用日期为子目录名称
        $obj -> dateFormat = 'Y-m';     //使用 年_月 形式的 子目录名称
        if($obj -> upload()){
            $info =  $obj->getUploadFileInfo();
            $pictureUrl = explode('/',$info[0]['savename']);
            return array(
                'face180' => $pictureUrl[0].'/max_'.$pictureUrl[1],
                'face80' => $pictureUrl[0].'/medium_'.$pictureUrl[1],
                'face50' => $pictureUrl[0].'/mini_'.$pictureUrl[1]
            );
        }else{
            return $this -> error($obj->getErrorMsg());
        }
    }
}