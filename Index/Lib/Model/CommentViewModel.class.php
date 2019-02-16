<?php

/*
 * 评论表与用户信息表的 视图模型
 * */
class CommentViewModel extends ViewModel{
    protected $viewFields = array(
        'comment' => array(
            'id','content','time','weibo_id',
            '_type' => 'LEFT'
        ),
        'userinfo' => array(
            'username','face50' => 'face','user_id',
            '_on' => 'userinfo.user_id = comment.user_id'
        )
    );
}