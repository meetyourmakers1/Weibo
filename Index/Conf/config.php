<?php
return array(
	'DB_HOST' => '127.0.0.1',   //数据库服务地址
    'DB_USER' => 'root',    //数据库连接用户名
    'DB_PWD' => 'root',     //数据库连接密码
    'DB_NAME' => 'weibo',   //数据库名称
    'DB_PREFIX' => 'ningbo_',   //数据库表前缀

    // 默认的模板主题
    'DEFAULT_THEME' => 'default',
    //URL访问地址模式
    'URL_MODEl' => 1,   //0 (普通模式);1 (PATHINFO 模式);2 (REWRITE 模式);3 (兼容模式)

    //是否开启令牌验证
    'TOKEN_ON' => false,

    //异位或加密的KEY
    'ENCRYPTION_KEY' => 'www.weibo.com',
    //自动登录保存时间
    'AUTO_LOGIN_TIME' => time() + 3600 * 24 * 7,    //一个星期

    //上传图片
    'UPLOAD_MAX_SIZE' => 2000000,   //图片上传大小
    'UPLOAD_SAVE_PATH' => './Uploads/', //图片上传目录
    'UPLOAD_ALLOW_EXTS' => array('jpg','jpeg','png','gif'), //允许上传的文件后缀名

    //开启路由
    'URL_ROUTER_ON' => true,
    //路由规则
    'URL_ROUTE_RULES' => array(
    ':user_id\d' => 'User/index',
    //'路由规则'          => array('[分组/模块/操作]','额外参数1=值1')
    'follow/:user_id\d' => array('User/followFansList','type=1'),
    'fans/:user_id\d' => array('User/followFansList','type=0')
    ),

    //自定义标签
    'TAGLIB_LOAD'       => true,
    'APP_AUTOLOAD_PATH' => '@.TagLib',  //加载自定义标签库文件
    'TAGLIB_BUILD_IN'   => 'Cx,Weibo',    //定义成内置标签

    //缓存设置
    /*'DATA_CACHE_SUBDIR' => true,    //启用哈希子目录缓存
    'DATA_PATH_LEVEL' => 2, //设置哈希目录的层次*/
    'DATA_CACHE_TYPE' => 'Memcache', //数据缓存类型

    'MEMCACHE_HOST' => '127.0.0.1',
    'MEMCACHE_PORT' => 11211,

    //加载扩展配置
    'LOAD_EXT_CONFIG' => 'system',
);
?>