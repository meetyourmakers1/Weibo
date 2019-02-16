<?php

/*
 * 格式化打印数组
 * */
function p($array){
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

/*
 * 异位或加密 字符串
 *@param [String] $str [需要加密的字符串]
 *@param [Integer] $type [加密解密 (0: 加密，1: 解密)]
 *@return [String] [返回 加密或解密的字符串]
 * */
function encryption($str,$type = 0){
    $key = md5(C('ENCRYPTION_KEY'));
    //加密
    if(!$type){
        return base64_encode($str^$key);
    }
    //解密
    $str = base64_decode($str);
    return $str ^ $key;
}

/**
 * 格式化时间
 * @param $time     [需要格式化的时间戳]
 * @return false|string     [description]
 */
function time_format($time){
    //当前时间
    $now = time();
    //今天 零时 零分 零秒
    $today = strtotime(date('Y-m-d',$now));
    $diff = $now - $time;
    $str = '';
    switch($time){
        case $diff < 60 :
            $str = $diff . '秒前';
            break;
        case $diff < 3600 :
            $str = floor($diff/60) . '分钟前';
            break;
        case $diff < (3600*12) :
            $str = floor($diff/3600) . '小时前';
            break;
        case $time > $today :
            $str = '今天' .date('H:i',$time);
            break;
        default :
            $str = date('Y-m-d H:i:s',$time);
    }
    return $str;
}

/**
 * 替换微博内容的 URL地址，@用户 与 表情
 * @param string $content   [需要替换的微博内容]
 * @return string           [替换完成的内容字符串]
 */
function replace_content($content){
    if(empty($content)) return;
    //给 URL地址 加上<a></a>链接
    $preg = '/(?:http:\/\/)?([\w.]+[\w\/]*.[\w.]+[\w\/]*\??[\w=\&\+\%\-]*.[\w]+)/is';
    //  \\1 : 配合()使用，表示()中的内容，[\w.]+[\w\/]*.[\w.]+[\w\/]*\??[\w=\&\+\%\-]*.[\w]+  元字组
    $content = preg_replace($preg,'<a href="http://\\1" target="_blank">\\1</a> ',$content);
    //给 @用户 加上<a></a>链接
    $preg = '/@(\S*)\s/is';
    //  \\1 : 配合()使用，表示()中的内容，\S*  元字组
    $content = preg_replace($preg,'<a href="' . __APP__ . '/User/\\1">@\\1</a> ',$content);
    //替换微博内容的表情
    $preg = '/\[(\S+?)\]/is';
        //提取微博内容中的表情
    preg_match_all($preg,$content,$arr);    //$arr[0] 内容中要替换的表情,  $arr[1] 正则元字组
    $phiz = include './Public/Data/phiz.php';
    if(!empty($arr[1])){
        foreach($arr[1] as $k => $v){
            //array_search();在数组中搜索键值，返回它的键名
            $name = array_search($v,$phiz);
            if($name){
                $content = str_replace($arr[0][$k],'<img title="' . $v . '" src="'.__ROOT__.'/Public/Images/phiz/' . $name . '.gif"/>',$content);
            }
        }
    }
    return str_replace(C('FILTER'),'*******',$content);
}

/**
 * 推送给用户的消息写入到内存
 * @param  [int] $userId [用户的ID]
 * @param  [int] $type [1: 评论,2: 私信,3: @用户]
 * @param  [boolean] $flush [消息状态 和 条数 是否清零]
 * @return [type]
 */
function cache_msg($userId,$type,$flush=false){
    $name = '';
    switch ($type) {
        case 1:
            $name = 'comment';
            break;
        case 2:
            $name = 'letter';
            break;
        case 3:
            $name = 'atme';
            break;
    }
    if($flush){
        $data = S('userMsg'.$userId);
        //$data[$name]['status'] = 0;
        $data[$name]['count'] = 0;
        S('userMsg'.$userId,$data,0);
        return;
    }
    //内存数据已存在时，让相应的用户数据 +1
    if(S('userMsg'.$userId)){
        $data = S('userMsg'.$userId);
        $data[$name]['count']++;
        $data[$name]['status'] = 1;
        S('userMsg'.$userId,$data,0);
        //内存数据不存在时，初始化用户数据并写入到内存
    }else{
        $data = array(
            'comment' => array('count' => 0,'status' => 0),
            'letter' => array('count' => 0,'status' => 0),
            'atme' => array('count' => 0,'status' => 0)
        );
        $data[$name]['count']++;
        $data[$name]['status'] = 1;
        S('userMsg'.$userId,$data,0);
    }
}