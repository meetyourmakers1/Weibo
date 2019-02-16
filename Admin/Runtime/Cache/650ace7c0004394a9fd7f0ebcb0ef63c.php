<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>原创微博列表</title>
    <link rel="stylesheet" href="__PUBLIC__/Css/common.css" />
    <script type="text/javascript" src='__PUBLIC__/Js/jquery-1.8.2.min.js'></script>
    <script type="text/javascript" src='__PUBLIC__/Js/common.js'></script>
</head>
<body>
    <div class="status">
        <span>原创微博列表</span>
    </div>
    <table class="table">
        <tr>
            <th>微博ID</th>
            <th>作者</th>
            <th>微博内容</th>
            <th>微博配图</th>
            <th>统计信息</th>
            <th>发布时间</th>
            <th>操作</th>
        </tr>
    <?php if(is_array($weibo)): foreach($weibo as $key=>$v): ?><tr>
            <td align="center"><?php echo ($v["id"]); ?></td>
            <td align="center"><?php echo ($v["username"]); ?></td>
            <td style="width: 16%;height: 16px;overflow: hidden"><?php echo ($v["content"]); ?></td>
            <td align="center">
            <?php if($v['picture']): ?><a href="__ROOT__/Uploads/Picture/<?php echo ($v["picture"]); ?>" target="_blank">查看图片</a><?php endif; ?>
            </td>
            <td align="center">
                <ul>
                    <li>转发:<?php echo ($v["turn"]); ?></li>
                    <li>收藏:<?php echo ($v["keep"]); ?></li>
                    <li>评论:<?php echo ($v["comment"]); ?></li>
                </ul>
            </td>
            <td align="center"><?php echo (date('y-m-d H:i',$v["time"])); ?></td>
            <td align="center">
                <a href="<?php echo U('delWeibo',array('id' => $v['id'],'user_id' => $v['user_id']));?>" class="del"></a>
            </td>
        </tr><?php endforeach; endif; ?>
        <tr>
            <td colspan="7" align="center">
                <span style="font-size: 18px;width: 100%;height: 30px;line-height: 30px;text-align: center;"><?php echo ($page); ?></span>
            </td>
        </tr>
    </table>
</body>
</html>