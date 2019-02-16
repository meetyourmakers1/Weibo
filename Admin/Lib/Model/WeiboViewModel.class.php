<?php

/*
 * 微博视图模型
 * */
class WeiboViewModel extends ViewModel{
    public $viewFields = array(
        'weibo' => array(
            'id','content','isturn','time','turn','keep','comment',
            '_type' => 'LEFT'
        ),
        'picture' => array(
            'max' => 'picture',
            '_type' => 'LEFT',
            '_on' => 'weibo.id = picture.weibo_id'
        ),
        'userinfo' => array(
            'user_id','username',
            '_on' => 'weibo.user_id = userinfo.user_id'
        )
    );
}

