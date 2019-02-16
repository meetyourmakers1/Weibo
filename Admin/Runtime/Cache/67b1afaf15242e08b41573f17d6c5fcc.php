<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>检索微博</title>
    <link rel="stylesheet" href="__PUBLIC__/Css/common.css">
    <script type="text/javascript" src="__PUBLIC__/Js/jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="__PUBLIC__/Js/common.js"></script>
</head>
<body>
    <div class="status">
        <span>检索微博</span>
    </div>
    <div style="width: 390px;margin: 20px auto;">
        <form action="__SELF__" method="get">
            检索关键字:
            <input type="text" name="search">
            <input type="submit" value="" class="see">
        </form>
    </div>
    <table class="table">
        <?php if(isset($weibo) && !$weibo): ?><tr>
                <td align="center">没有检索到相关微博</td>
            </tr>
        <?php else: ?>
            <tr>
                <th>微博ID</th>
                <th>作者</th>
                <th>微博内容</th>
                <th>微博类型</th>
                <th>统计信息</th>
                <th>发布时间</th>
                <th>操作</th>
            </tr>
        <?php if(is_array($weibo)): foreach($weibo as $key=>$v): ?><tr>
                <td align="center"><?php echo ($v["id"]); ?></td>
                <td align="center"><?php echo ($v["username"]); ?></td>
                <td><?php echo ($v["content"]); ?></td>
                <td align="center">
                    <?php if($v['isturn']): ?>转发
                    <?php elseif($v["picture"]): ?>
                        <a href="__ROOT__/Uploads/Picture/<?php echo ($v["picture"]); ?>" target="_blank">查看图片</a>
                    <?php else: ?>
                        原创<?php endif; ?>
                </td>
                <td align="center">
                    <ul>
                        <li>转发:<?php echo ($v["turn"]); ?></li>
                        <li>收藏:<?php echo ($v["keep"]); ?></li>
                        <li>评论:<?php echo ($v["comment"]); ?></li>
                    </ul>
                </td>
                <td align="center"><?php echo (date('Y-m-d H:i',$v["time"])); ?></td>
                <td align="center">
                    <a href="<?php echo U('delWeibo',array('id' => $v['id'],'user_id' => $v['user_id']));?>" class="del"></a>
                </td>
            </tr><?php endforeach; endif; endif; ?>
    </table>
</body>
</html>