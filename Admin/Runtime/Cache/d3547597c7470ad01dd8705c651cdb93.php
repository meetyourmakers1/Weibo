<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>检索用户</title>
    <link rel="stylesheet" href="__PUBLIC__/Css/common.css">
    <script type="text/javascript" src="__PUBLIC__/Js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Js/common.js"></script>
</head>
<body>
    <div class="status">
        <span>检索用户</span>
    </div>
    <div style="width: 390px;margin: 20px auto;">
        <form action="__SELF__" method="get">
            检索方式:
            <select name="type" id="">
                <option value="1">用户ID</option>
                <option value="0">用户昵称</option>
            </select>
            <input type="text" name="search">
            <input type="submit" value="" class="see">
        </form>
    </div>
    <table class="table">
    <?php if(isset($user) && !$user): ?><tr>
            <td align="center">没有检索到相关用户</td>
        </tr>
    <?php else: ?>
        <tr>
            <th>用户ID</th>
            <th>用户昵称</th>
            <th>用户头像</th>
            <th>关注信息</th>
            <th>注册时间</th>
            <th>账号状态</th>
            <th>操作</th>
        </tr>
    <?php if(is_array($user)): foreach($user as $key=>$v): ?><tr>
            <td align="center"><?php echo ($v["id"]); ?></td>
            <td align="center"><?php echo ($v["username"]); ?></td>
            <td align="center">
                <img width="50" height="50" src="<?php if($v["face"]): ?>__ROOT__/Uploads/Face/<?php echo ($v["face"]); else: ?>__ROOT__/Public/Images/noface.gif<?php endif; ?>" alt="">
            </td>
            <td align="center">
                <ul>
                    <li>关注:<?php echo ($v["follow"]); ?></li>
                    <li>粉丝:<?php echo ($v["fans"]); ?></li>
                    <li>微博:<?php echo ($v["weibo"]); ?></li>
                </ul>
            </td>
            <td align="center"><?php echo (date('Y-m-d H:i',$v["registime"])); ?></td>
            <td align="center"><?php if($v['lock']): ?><span style="color: red;">锁定</span><?php else: ?><span style="color: blue;">正常</span><?php endif; ?></td>
            <td>
                <?php if($v['lock']): ?><a href="<?php echo U('User/lockUser',array('id' => $v['id'],'lock' => 0));?>" class="add">解除锁定</a>
                    <?php else: ?>
                    <a href="<?php echo U('User/lockUser',array('id' => $v['id'],'lock' => 1));?>" class="add lock">锁定用户</a><?php endif; ?>
            </td>
        </tr><?php endforeach; endif; endif; ?>
    </table>
</body>
</html>