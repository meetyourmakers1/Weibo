<?php

/*
 * 微博用户 视图模型
 * */
class UserViewModel extends ViewModel{
    protected $viewFields = array(
        'user' => array(
            'id','registime','`lock`',
            '_type' => 'LEFT'
        ),
        'userinfo' => array(
            'username','face50' => 'face','follow','fans','weibo',
            '_on' => 'user.id = userinfo.user_id'
        )
    );
}