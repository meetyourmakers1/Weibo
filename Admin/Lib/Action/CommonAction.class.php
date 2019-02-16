<?php

/*
 * 公共控制器
 * */
class CommonAction extends Action{
    public function _initialize(){
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['username'])){
            redirect(U('Login/index'));
        }
    }
}