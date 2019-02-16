<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>网站设置</title>
    <link rel="stylesheet" href="__PUBLIC__/Css/common.css">
    <script type="text/javascript" src="__PUBLIC__/Js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Js/common.js"></script>
</head>
<body>
    <div class="status">
        <span>网站设置</span>
    </div>
    <form action="<?php echo U('setWebHandle');?>" method="post">
        <table class="table">
            <tr>
                <td width="45%" align="right">网站名称</td>
                <td>
                    <input type="text" name="webname" value="<?php echo ($config["WEBNAME"]); ?>"/>
                </td>
            </tr>
            <tr>
                <td align="right">网站版权</td>
                <td>
                    <input type="text" name="webcopy" value="<?php echo ($config["WEBCOPY"]); ?>"/>
                </td>
            </tr>
            <tr>
                <td align="right">是否开启注册</td>
                <td>
                    <input type="radio" name="regist_on" value="1" class="radio" <?php if($config['REGIST_ON']): ?>checked<?php endif; ?>/>&nbsp;开启&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input type="radio" name="regist_on" value="0" class="radio" <?php if(!$config['REGIST_ON']): ?>checked<?php endif; ?>/>&nbsp;暂停
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="保存修改" class="big-btn">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>