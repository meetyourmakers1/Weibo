<?php

/*
 * 评论视图模型
 * */
class CommentViewModel extends ViewModel{
    protected $viewFields = array(
        'comment' => array(
            'id','content','time','weibo_id',
            '_type' => 'LEFT'
        ),
        'userinfo' => array(
            'username',
            '_on' => 'comment.user_id = userinfo.user_id'
        )
    );
}