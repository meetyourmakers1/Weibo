<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>添加管理员</title>
    <link rel="stylesheet" href="__PUBLIC__/Css/common.css">
    <script type="text/javascript" src="__PUBLIC__/Js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Js/common.js"></script>
</head>
<body>
    <div class="status">
        <span>添加管理员</span>
    </div>
    <form action="<?php echo U('addAdminHandle');?>" method="post">
        <table class="table">
            <tr>
                <td width="46%" align="right">管理员账号:</td>
                <td>
                    <input type="text" name="username">
                </td>
            </tr>
            <tr>
                <td align="right">管理员密码:</td>
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
                <td align="right">管理员级别:</td>
                <td>
                    <select name="admin">
                        <option value="1">普通管理员</option>
                        <option value="0">超级管理员</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="保存添加" class="big-btn">
                </td>
            </tr>
        </table>
    </form>
</body>
</html>