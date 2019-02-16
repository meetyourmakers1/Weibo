<?php
return array(
    //数据库配置
    'DB_HOST' => '127.0.0.1',   //数据库服务地址
    'DB_USER' => 'root',    //数据库连接用户名
    'DB_PWD' => 'root',     //数据库连接密码
    'DB_NAME' => 'weibo',   //数据库名称
    'DB_PREFIX' => 'ningbo_',   //数据库表前缀

    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' => __ROOT__.'/Admin/TPL/Public'
    ),
);
?>