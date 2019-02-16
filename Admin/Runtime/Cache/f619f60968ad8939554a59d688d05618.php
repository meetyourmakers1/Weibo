<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>管理员列表</title>
    <link rel="stylesheet" href="__PUBLIC__/Css/common.css">
    <script type="text/javascript" src="__PUBLIC__/Js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Js/common.js"></script>
</head>
<body>
    <div class="status">
        <span>管理员列表</span>
    </div>
    <table class="table">
        <tr>
            <th>用户ID</th>
            <th>管理员名称</th>
            <th>管理级别</th>
            <th>最后登录时间</th>
            <th>最后登录IP</th>
            <th>账号状态</th>
        <?php if(!$_SESSION['admin']): ?><th>操作</th><?php endif; ?>
        </tr>
    <?php if(is_array($admin)): foreach($admin as $key=>$v): ?><tr>
            <td height="26" align="center"><?php echo ($v["id"]); ?></td>
            <td align="center"><?php echo ($v["username"]); ?></td>
            <td align="center">
            <?php if($v['admin']): ?>普通管理员
            <?php else: ?>
                超级管理员<?php endif; ?>
            </td>
            <td align="center"><?php echo (date('Y-m-d H:i',$v["logintime"])); ?></td>
            <td align="center"><?php echo ($v["loginip"]); ?></td>
            <td align="center">
            <?php if($v['lock']): ?><span style="color: red;">锁定</span>
            <?php else: ?>
                <span style="color: blue;">正常</span><?php endif; ?>
            </td>
        <?php if(!$_SESSION['admin']): ?><td align="center">
        <?php if($v['admin']): if($v['lock']): ?><a href="<?php echo U('lockAdmin',array('id' => $v['id'],'lock' => 0));?>" class="add lock">解除锁定</a>
            <?php else: ?>
                <a href="<?php echo U('lockAdmin',array('id' => $v['id'],'lock' => 1));?>" class="add lock">锁定用户</a><?php endif; ?>
            <a href="<?php echo U('delAdmin',array('id' =>$v['id']));?>" class="add lock">删除管理员</a>
        <?php else: ?>
            没有权限<?php endif; ?>
            </td><?php endif; ?>

        </tr><?php endforeach; endif; ?>
    </table>
</body>
</html>