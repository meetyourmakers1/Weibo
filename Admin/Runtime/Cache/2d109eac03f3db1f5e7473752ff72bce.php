<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>检索评论</title>
    <link rel="stylesheet" href="__PUBLIC__/Css/common.css">
    <script type="text/javascript" src="__PUBLIC__/Js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Js/common.js"></script>
</head>
<body>
    <div class="status">
        <span>检索评论</span>
    </div>
    <div style="width: 302px;text-align: center;margin: 20px auto;">
        <form action="__SELF__" method="get">
            检索关键字:
            <input type="text" name="search"/>
            <input type="submit" value="" class="see">
        </form>
    </div>
    <table class="table">
    <?php if(isset($content) && !empty($content)): ?><tr>
            <td align="center">没有检索到相关的评论</td>
        </tr>
    <?php else: ?>
        <tr>
            <th>评论ID</th>
            <th>评论内容</th>
            <th>评论用户</th>
            <th>评论时间</th>
            <th>操作</th>
        </tr>
    <?php if(is_array($comment)): foreach($comment as $key=>$v): ?><tr>
            <td align="center"><?php echo ($v["id"]); ?></td>
            <td><?php echo ($v["content"]); ?></td>
            <td align="center"><?php echo ($v["username"]); ?></td>
            <td align="center"><?php echo (date('Y-m-d H:i',$v["time"])); ?></td>
            <td align="center">
                <a href="<?php echo U('delComment',array('id' => $v['id'],'weibo_id' => $v['weibo_id']));?>" class="del"></a>
            </td>
        </tr><?php endforeach; endif; endif; ?>
    </table>
</body>
</html>