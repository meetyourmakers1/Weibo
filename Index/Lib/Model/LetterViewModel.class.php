<?php

/*
 * 私信表与用户信息表视图模型
 * */
class LetterViewModel extends ViewModel{
    protected $viewFields = array(
        'letter' => array(
            'id','content','time',
            '_type' => 'LEFT'
        ),
        'userinfo' => array(
            'username','face50' => 'face','user_id',
            '_on' => 'letter.from = userinfo.user_id'
        )
    );
}