<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>修改管理员密码</title>
    <link rel="stylesheet" href="__PUBLIC__/Css/common.css">
    <script type="text/javascript" src="__PUBLIC__/Js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Js/common.js"></script>
</head>
<body>
    <div class="status">
        <span>修改管理员密码</span>
    </div>
    <form action="<?php echo U('editPwdHandle');?>" method="post">
        <table class="table">
            <tr>
                <td width="45%" align="right">旧密码:</td>
                <td>
                    <input type="password" name="old">
                </td>
            </tr>
            <tr>
                <td align="right">新密码:</td>
                <td>
                    <input type="password" name="pwd">
                </td>
            </tr>
            <tr>
                <td align="right">确认密码:</td>
                <td>
                    <input type="password" name="pwded">
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