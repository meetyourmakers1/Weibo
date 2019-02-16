<?php

/*
 * 收藏表与微博表，微博图片表，用户信息表视图模型
 * */
class KeepViewModel extends ViewModel{
    protected $viewFields =array(
        'keep' => array(
            'id' => 'keep_id','time' => 'keep_time',
            '_type' => 'INNER'
        ),
        'weibo' => array(
            'id','content','isturn','time','turn','comment','user_id',
            '_on' => 'keep.weibo_id = weibo.id',
            '_type' => 'LEFT'
        ),
        'picture' => array(
            'mini','medium','max',
            '_on' => 'weibo.id = picture.weibo_id',
            '_type' => 'LEFT'
         ),
        'userinfo' => array(
            'username','face50' => 'face',
            '_on' => 'weibo.user_id = userinfo.user_id'
        )
    );
    public function getAll($where,$limit){
        $result = $this -> where($where) -> limit($limit) -> order('keep_time DESC') -> select();
        //在KeepView视图模型里，复用WeiboView视图模型
        $weiboview = D('WeiboView');
        foreach($result as $k => $v){
            if($v['isturn']){
                $result[$k]['isturn'] = $weiboview -> find($v['isturn']);
            }
        }
        return $result;
    }
}