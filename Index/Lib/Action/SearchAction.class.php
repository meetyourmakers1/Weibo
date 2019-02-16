<?php

/*
 * 搜索控制器
 * */
class SearchAction extends CommonAction{
    /*
     * 搜索找人 页面( 表单 处理 )
     * */
    public function searchUser(){
        $keyword = $this -> _getKeyword();
        if($keyword){
            //检索除自己外，昵称含有关键字的用户
            $where = array(
                'username' => array('LIKE','%'.$keyword.'%'),
                'user_id' => array('NEQ',session('user_id'))
            );
            $field = array('username','sex','location','intro','face80','follow','fans','weibo','user_id');
            $userinfo = M('userinfo');
            //引入 ThinkPHP 分页类
            import('ORG.Util.Page');
            $totalCount = $userinfo -> where($where) -> count('id');
            $page = new Page($totalCount,10);
            $limit = $page -> firstRow.','.$page -> listRows;
            $result = $userinfo -> where($where) -> field($field) -> limit($limit) -> select();

            /*p($result);
            echo $page -> show().'<br/>';*/

            //重新组合结果集，返回是否已关注与是否互相关注
            $result = $this -> _getMutualFollow($result);
            /*p($result);die;*/

            //分配处理完成的 搜索结果集 到页面
            $this -> result = $result ? $result : false;
            $this -> page = $page -> show();
            $this -> totalCount = $totalCount;
        }
        $this -> keyword = $keyword;
        $this -> display();
    }
    /*
     * 搜索找微博页面 (表单 处理)
     * */
    public function searchWeibo(){
        $keyword = $this -> _getKeyword();
        if($keyword){
            //检索含有关键字的微博
            $where = array(
                'content' => array('LIKE','%'.$keyword.'%')
            );
            $weibo = D('WeiboView');
            //引入 ThinkPHP 分页类
            import('ORG.Util.Page');
            $totalCount = M('weibo') -> where($where) -> count('id');
            $page = new Page($totalCount,10);
            $limit = $page -> firstRow.','.$page -> listRows;
            $weibo = $weibo -> getAllWeibo($where,$limit);
            //分配处理完成的 搜索结果集 到页面
            $this -> weibo = $weibo ? $weibo : false;
            $this -> page = $page -> show();
            $this -> totalCount = $totalCount;
        }
        $this -> keyword = $keyword;
        $this -> display();
    }
    /*
     * 搜索找人 处理 方法( 返回 搜索关键字 )
     * */
    private function _getKeyword(){
        return $_GET['keyword'] == '搜索微博、找人' ? NUll : $this -> _get('keyword');
    }
    /*
     * 重新组合数组，返回是否已关注与是否互相关注 方法
     * @param   [Array]   $result     [需要处理的结果集]
     * $return  [Array]               [处理完成的结果集]
     * */
    private function _getMutualFollow($result){
        if(!$result) return false;
        $follow = M('follow_fans');
        foreach($result as $k => $v){
            //检索是否互相关注
            $sql = '(SELECT `follow` FROM `ningbo_follow_fans` WHERE `follow` = ' . $v['user_id'] . ' AND 
            `fans` = ' . session('user_id') . ') UNION (SELECT `follow` FROM `ningbo_follow_fans` WHERE 
            `follow` = ' . session('user_id') . ' AND `fans` = ' . $v['user_id'] . ')';
            $mutualFollow = $follow -> query($sql);

           /*//模型调试(输出上次执行的SQL语句)
            echo $follow -> getLastSql();*/

           //判断是否互相关注
            if(count($mutualFollow) == 2){
                $result[$k]['mutualFollow'] = 1;    //1: 互相关注,0: 未互相关注
                $result[$k]['followed'] = 1;        //1: 已关注
            }else{
                $result[$k]['mutualFollow'] = 0;    //未互相关注
                //未互相关注时 检索是否未关注
                $where = array(
                    'follow' => $v['user_id'],
                    'fans' => session('user_id')
                );
                //0:  0条查询结果条数: 未关注，1:  1条查询结果条数:已关注
                $result[$k]['followed'] = $follow -> where($where) -> count();
            }
        }
        return $result;
    }
}