<?php

/*
 * 系统设置控制器
 * */
class SystemAction extends CommonAction{
    /*
     * 网站设置 页面
     * */
    public function index(){
        $this -> config = include './Index/Conf/system.php';
        $this -> display();
    }
    /*
     * 网站设置表单 处理
     * */
    public function setWebHandle(){
        $path = './Index/Conf/system.php';
        $config = include $path;
        $config['WEBNAME'] = $_POST['webname'];
        $config['WEBCOPY'] = $_POST['webcopy'];
        $config['REGIST_ON'] = $_POST['regist_on'];
        $data = "<?php\r\nreturn " . var_export($config,true) . ";\r\n?>";      //var_export()数组转换成字符串
        if(file_put_contents($path,$data)){
            $this -> success('修改成功！',U('index'));
        }else{
            $this -> error('修改失败！');
        }
    }
    /*
     * 设置关键字 页面
     * */
    public function filter(){
        //引入system配置文件
        $config = include './Index/Conf/system.php';
        //合并配置文件中的FILTER配置项
        $this -> filter = implode('|',$config['FILTER']);
        $this -> display();
    }
    /*
     * 设置关键字表单 处理
     * */
    public function setFilterHandle(){
        //配置文件路径
        $path = './Index/Conf/system.php';
        //引入配置文件
        $cinfig = include $path;
        //替换配置项FILTER
        $config['FILTER'] = explode('|',$_POST['filter']);
        //修改后的配置项写入配置文件
        $data = "<?php\r\nreturn " . var_export($config,true) . ";\r\n?>";      //var_export()数组转换成字符串
        if(file_put_contents($path,$data)){
            $this -> success('修改成功！',U('filter'));
        }else{
            $this -> error('修改失败！');
        }
    }
}