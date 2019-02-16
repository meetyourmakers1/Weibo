<?php

/*
 * 微博 关联模型
 * */
class WeiboRelationModel extends RelationModel{

    protected $tableName = 'weibo';

    protected $_link = array(
        'picture' => array(
            'mapping_type' => HAS_ONE,
            'foreign_key' => 'weibo_id'
        ),
        'comment' => array(
            'mapping_type' => HAS_MANY,
            'foreign_key' => 'weibo_id'
        ),
        'keep' => array(
            'mapping_type' => HAS_MANY,
            'foreign_key' => 'weibo_id'
        ),
        'atme' => array(
            'mapping_type' => HAS_MANY,
            'foreign_key' => 'weibo_id'
        )
    );
}