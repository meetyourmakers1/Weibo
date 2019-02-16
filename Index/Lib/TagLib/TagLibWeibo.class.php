<?php

import('TagLib');
class TagLibWeibo extends TagLib{
    protected $tags = array(
        'userinfo' => array('attr' => 'user-id','close' => 1),
        'maybe' => array('attr' => 'user-id','close' => 1)
    );

    /**
     * 用户信息标签
     * @param $attr
     * @param $content
     */
    public function _userinfo($attr,$content){
        $attr = $this -> parseXmlAttr($attr);
        $userId = $attr['user-id'];
        $str = '';
        $str .= '<?php ';
        $str .= '$where = array("user_id" => ' . $userId . ');';
        $str .= '$field = array("username","face80" => "face","follow","fans","weibo","user_id");';
        $str .= '$userinfo = M("userinfo") -> where($where) -> field($field) -> find();';
        $str .= 'extract($userinfo);';
        $str .= '?>';
        $str .= $content;
        return $str;
    }

    /**
     * 可能感兴趣的人标签
     * @param $attr
     * @param $content
     * @return string
     */
    public function _maybe($attr,$content){
        $attr = $this -> parseXmlAttr($attr);
        $userId = $attr['user-id'];
        $str = '';
        $str .= '<?php ';
        $str .= '$userId = ' . $userId . ';';
        $str .= '$follow = M("follow_fans");';
        $str .= '$where = array("fans" => $userId);';
        $str .= '$follows = $follow->where($where)->field("follow")->select();';
        $str .= 'foreach ($follows as $k => $v) :';
        $str .= '$follows[$k] = $v["follow"];';
        $str .= 'endforeach;';
        $str .= '$sql = "SELECT `user_id`,`username`,`face50` AS `face`,COUNT(f.`follow`) AS `count` FROM `ningbo_follow_fans` f LEFT JOIN `ningbo_userinfo` u ON f.`follow` = u.`user_id` WHERE f.`fans` IN (" . implode(\',\', $follows) . ") AND f.`follow` NOT IN (" . implode(\',\',$follows) . ") AND f.`follow` <>" . $userId . " GROUP BY f.`follow` ORDER BY `count` DESC LIMIT 4";';
        $str .= '$friend = $follow->query($sql);';
        $str .= 'foreach ($friend as $v) :';
        $str .= 'extract($v);?>';
        $str .= $content;
        $str .= '<?php endforeach;?>';
        return $str;
    }


}