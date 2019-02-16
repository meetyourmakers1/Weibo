<?php

/*
 * 微博表与用户信息表，微博图片表的视图模型
 * */
class WeiboViewModel extends ViewModel{
    protected $viewFields = array(
        'weibo' => array(
            'id','content','isturn','time','turn','keep','comment','user_id',
            '_type' => 'LEFT'
        ),
        'userinfo' => array(
            'username','face50' => 'face',
            '_on' => 'weibo.user_id = userinfo.user_id',
            '_type' => 'LEFT'
        ),
        'picture' => array(
            'mini','medium','max',
            '_on' => 'weibo.id = picture.weibo_id',
        )
    );
    public function getAllWeibo($where,$limit){
        $weibo = $this -> where($where) -> limit($limit) -> order('time DESC') -> select();
        //echo $this -> getLastSql();die;
        if($weibo){
            foreach($weibo as $k => $v){
                if($v['isturn']){
                    $isturn = $this -> find($v['isturn']);
                    $weibo[$k]['isturn'] = $isturn ? $isturn : -1;
                }
            }
        }
        return $weibo;
    }
}