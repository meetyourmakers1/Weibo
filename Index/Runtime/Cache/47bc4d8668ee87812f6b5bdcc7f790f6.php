<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <?php $style = M('userinfo') -> where(array('user_id' => session('user_id'))) -> getField('style'); ?>
	<title><?php echo (C("WEBNAME")); ?>-我的收藏</title>
	<link rel="stylesheet" href="__PUBLIC__/Theme/<?php echo ($style); ?>/Css/nav.css" />
	<link rel="stylesheet" href="__PUBLIC__/Theme/<?php echo ($style); ?>/Css/weibo_list.css" />
	<link rel="stylesheet" href="__PUBLIC__/Theme/<?php echo ($style); ?>/Css/bottom.css" />
	<script type="text/javascript" src='__PUBLIC__/Js/jquery-1.7.2.min.js'></script>
    <script type="text/javascript" src='__PUBLIC__/Js/nav.js'></script>
    <script type='text/javascript' src='__PUBLIC__/Js/weibo-list.js'></script>
    <script type='text/javascript'>
        var commentUrl = "<?php echo U('Index/comment');?>";
        var getCommentUrl = '<?php echo U("Index/getComment");?>';
        var keepUrl = '<?php echo U("Index/keep");?>';
        var cancelKeepUrl = "<?php echo U('cancelKeep');?>";
    </script>
    <script type="text/javascript">
        var delFollowUrl = '<?php echo U("Common/delFollow");?>';
        var styleUrl = '<?php echo U("Common/style");?>';
        var pushMsgUrl = '<?php echo U("Common/pushMsg");?>';
    </script>
</head>
<body>
    <!--==========顶部固定导行条==========-->
    <div id='top_wrap'>
        <div id="top">
            <div class='top_wrap'>
                <div class="logo fleft"></div>
                    <ul class='top_left fleft'>
                        <li class='cur_bg'><a href='__APP__'>首页</a></li>
                        <li><a href="<?php echo U('User/atme');?>">@我</a></li>
                        <li><a href="<?php echo U('User/letter');?>">私信</a></li>
                        <li><a href="<?php echo U('User/comment');?>">评论</a></li>
                    </ul>
                <div id="search" class='fleft'>
                    <form action='<?php echo U("Search/searchUser");?>' method='get'>
                        <input type='text' name='keyword' id='sech_text' class='fleft' value='搜索微博、找人'/>
                        <input type='submit' value='' id='sech_sub' class='fleft'/>
                    </form>
                </div>
                <div class="user fleft">
                    <a href="<?php echo U('/'.session('user_id'));?>"><?php echo M('userinfo') -> where(array('user_id' => session('user_id'))) -> getField('username'); ?></a>
                </div>
                <ul class='top_right fleft'>
                    <li title='快速发微博' class='fast_send'><i class='icon icon-write'></i></li>
                    <li class='selector'><i class='icon icon-msg'></i>
                        <ul class='hidden'>
                            <li><a href="<?php echo U('User/atme');?>">查看@我</a></li>
                            <li><a href="<?php echo U('User/letter');?>">查看私信</a></li>
                            <li><a href="<?php echo U('User/comment');?>">查看评论</a></li>
                            <li><a href="<?php echo U('User/keep');?>">查看收藏</a></li>
                        </ul>
                    </li>
                    <li class='selector'><i class='icon icon-setup'></i>
                        <ul class='hidden'>
                            <li><a href="<?php echo U('Account/index');?>">帐号设置</a></li>
                            <li><a href="" class="set_model">模版设置</a></li>
                            <li><a href="<?php echo U('Login/loginOut');?>">退出登录</a></li>
                        </ul>
                    </li>
                    <!--信息推送-->
                    <li id='news' class='hidden'>
                        <i class='icon icon-news'></i>
                        <ul>
                            <li class='news_comment hidden'>
                                <a href="<?php echo U('User/comment');?>"></a>
                            </li>
                            <li class='news_letter hidden'>
                                <a href="<?php echo U('User/letter');?>"></a>
                            </li>
                            <li class='news_atme hidden'>
                                <a href="<?php echo U('User/atme');?>"></a>
                            </li>
                        </ul>
                    </li>
                    <!--信息推送-->
                </ul>
            </div>
        </div>
    </div>
    <!--==========顶部固定导行条==========-->
    <!--==========自定义模版==========-->
    <div id='model' class='hidden'>
        <div class="model_head">
            <span class="model_text">个性化设置</span>
            <span class="close fright"></span>
        </div>
        <ul>
            <li style='background:url(__PUBLIC__/Images/default.jpg) no-repeat;' theme='default'></li>
            <li style='background:url(__PUBLIC__/Images/style2.jpg) no-repeat;' theme='style2'></li>
            <li style='background:url(__PUBLIC__/Images/style3.jpg) no-repeat;' theme='style3'></li>
            <li style='background:url(__PUBLIC__/Images/style4.jpg) no-repeat;' theme='style4'></li>
        </ul>
        <div class='model_operat'>
            <span class='model_save'>保存</span>
            <span class='model_cancel'>取消</span>
        </div>
    </div>
    <!--==========自定义模版==========-->
    <!--==========转发输入框==========-->
    <div id='turn' class='hidden'>
        <div class="turn_head">
            <span class='turn_text fleft'>转发微博</span>
            <span class="close fright"></span>
        </div>
        <div class="turn_main">
            <form action='<?php echo U("Index/turnHandle");?>' method='post' name='turn'>
                <p></p>
                <div class='turn_prompt'>
                    你还可以输入<span id='turn_num'>140</span>个字</span>
                </div>
                <textarea name='content' sign='turn'></textarea>
                <ul>
                    <li class='phiz fleft' sign='turn'></li>
                    <li class='turn_comment fleft'>
                        <label>
                            <input type="checkbox" name='becomment'/>同时评论给<span class='turn-cname'></span>
                        </label>
                    </li>
                    <li class='turn_btn fright'>
                        <input type="hidden" name='weibo_id' value=''/>
                        <input type="hidden" name='turn_id' value=''/>
                        <input type="submit" value='转发' class='turn_btn'/>
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <!--==========转发输入框==========-->
    <!--==========加关注弹出框==========-->
    <?php
 $group = M('group') -> where(array('user_id' => session('user_id'))) -> select(); ?>
<script type='text/javascript'>
        var addFollow = "<?php echo U('Common/addFollow');?>";
    </script>
    <div id='follow'>
        <div class="follow_head">
            <span class='follow_text fleft'>关注好友</span>
        </div>
        <div class='sel-group'>
            <span>好友分组：</span>
            <select name="groupid">
                <option value="0">默认分组</option>
                <?php if(is_array($group)): foreach($group as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>"><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
            </select>
        </div>
        <div class='fl-btn-wrap'>
            <input type="hidden" name='follow'/>
            <span class='add-follow-sub'>关注</span>
            <span class='follow-cencle'>取消</span>
        </div>
    </div>
    <!--==========加关注弹出框==========-->
    <!--==========顶部固定导行条==========-->
    <!--==========内容主体==========-->
	<div style='height:60px;opcity:10'></div>
    <div class="main">
        <!--==========左侧==========-->
       <div id="left" class='fleft'>
            <ul class='left_nav'>
                <li><a href="__APP__"><i class='icon icon-home'></i>&nbsp;&nbsp;首页</a></li>
                <li><a href="<?php echo U('User/atme');?>"><i class='icon icon-at'></i>&nbsp;&nbsp;@我</a></li>
                <li><a href="<?php echo U('User/letter');?>"><i class='icon icon-letter'></i>&nbsp;&nbsp;私信</a></li>
                <li><a href="<?php echo U('User/comment');?>"><i class='icon icon-comment'></i>&nbsp;&nbsp;评论</a></li>
                <li><a href="<?php echo U('User/keep');?>"><i class='icon icon-keep'></i>&nbsp;&nbsp;收藏</a></li>
            </ul>
            <div class="group">
                <fieldset><legend>分组</legend></fieldset>
                <ul>
                    <?php $group = M('group') -> where(array('user_id' => session('user_id'))) -> select(); ?>
                    <li><a href="__APP__"><i class='icon icon-group'></i>&nbsp;&nbsp;全部</a></li>
                    <?php if(is_array($group)): foreach($group as $key=>$v): ?><li><a href="<?php echo U('Index/index',array('group_id' => $v['id'],'name' => $v['name']));?>"><i class='icon icon-group'></i>&nbsp;&nbsp;<?php echo ($v["name"]); ?></a></li><?php endforeach; endif; ?>
                </ul>
                <span id='create_group'>创建新分组</span>
            </div>
        </div>
        <!--==========左侧==========-->
        <!--=====创建新分组=====-->
        <script type='text/javascript'>
            var addGroupUrl = "<?php echo U('Common:addGroup');?>";
        </script>
        <div id='add-group'>
            <div class="group_head">
                <span class='group_text fleft'>创建好友分组</span>
            </div>
            <div class='group-name'>
                <span>分组名称：</span>
                <input type="text" name='name' id='group_name'>
            </div>
            <div class='gp-btn-wrap'>
                <span class='add-group-sub'>添加</span>
                <span class='group-cencle'>取消</span>
            </div>
        </div>
        <!--=====创建新分组=====-->
        <!--==========右侧==========-->
    	<div id='right'>
    		<div id='content'>
	    		<div class='view_line'>
	                <strong><?php if(isset($atme)): ?>@我<?php else: ?>收藏<?php endif; ?></strong>
	            </div>
    <?php if($weibo): if(is_array($weibo)): foreach($weibo as $key=>$v): if(!$v['isturn']): ?><!--=====普通微博样式=====-->
                <div class="weibo">
                    <!--头像-->
                    <div class="face">
                        <a href="<?php echo U('/'.$v['user_id']);?>"><img src="<?php if($v["face"]): ?>__ROOT__/Uploads/Face/<?php echo ($v["face"]); else: ?>__PUBLIC__/Images/noface.gif<?php endif; ?>" width='50' height='50'/></a>
                    </div>
                    <div class="wb_cons">
                        <dl>
                            <!--用户名-->
                            <dt class='author'>
                                <a href="<?php echo U('/'.$v['user_id']);?>"><?php echo ($v["username"]); ?></a>
                            </dt>
                            <!--发布内容-->
                            <dd class='content'>
                                <p><?php echo (replace_content($v["content"])); ?></p>
                            </dd>
                        <?php if($v['max']): ?><!--微博图片-->
                            <dd>
                                <div class='wb_img'>
                                    <!--小图-->
                                    <img src="__ROOT__/Uploads/Picture/<?php echo ($v["mini"]); ?>" class='mini_img'/>
                                    <div class="img_tool hidden">
                                        <ul>
                                            <li>
                                                <i class='icon icon-packup'></i>
                                                <span class='packup'>&nbsp;收起</span>
                                            </li>
                                            <li>|</li>
                                            <li>
                                                <i class='icon icon-bigpic'></i>
                                                <a href="__ROOT__/Uploads/Picture/<?php echo ($v["max"]); ?>" target='_blank'>&nbsp;查看大图</a>
                                            </li>
                                        </ul>
                                        <!--中图-->
                                        <div class="img_info"><img src="__ROOT__/Uploads/Picture/<?php echo ($v["medium"]); ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </dd><?php endif; ?>
                        </dl>
                        <!-----操作----->
                        <div class="wb_tool">
                            <!--发布时间-->
                            <span class="send_time"><?php echo (time_format($v["time"])); ?></span>
                            <ul>
                                <?php if(isset($v['keep_id'])): ?><li><span>收藏: <?php echo (time_format($v["keep_time"])); ?></span></li>
                                    <li>|</li><?php endif; ?>
                                <li>
                                    <span class='turn' weibo_id="<?php echo ($v["id"]); ?>">转发<?php if($v['turn']): ?>(<?php echo ($v["turn"]); ?>)<?php endif; ?></span>
                                </li>
                                <li>|</li>
                                <li class="keep-wrap">
                                <?php if(isset($v['keep_id'])): ?><span class="cancel-keep" keep_id="<?php echo ($v["keep_id"]); ?>" weibo_id="<?php echo ($v["id"]); ?>">取消收藏</span>
                                <?php else: ?>
                                    <span class='keep' weibo_id="<?php echo ($v["id"]); ?>">收藏<?php if($v['keep']): ?>(<?php echo ($v["keep"]); ?>)<?php endif; ?></span>
                                    <div class="keep-up hidden"></div><?php endif; ?>
                                </li>
                                <li>|</li>
                                <li>
                                    <span class='comment' weibo_id="<?php echo ($v["id"]); ?>">评论<?php if($v['comment']): ?>(<?php echo ($v["comment"]); ?>)<?php endif; ?></span>
                                </li>
                            </ul>
                        </div>
                        <!--=====回复框=====-->
                        <div class='comment_load hidden'>
                            <img src="__PUBLIC__/Images/loading.gif">评论加载中，请稍候...
                        </div>
                        <div class='comment_list hidden'>
                            <textarea name="" sign='comment<?php echo ($key); ?>'></textarea>
                            <ul>
                                <li class='phiz fleft' sign='comment<?php echo ($key); ?>'></li>
                                <li class='comment_turn fleft'>
                                    <label>
                                        <input type="checkbox" name=''/>同时转发到我的微博
                                    </label>
                                </li>
                                <li class='comment_btn fright' weibo_id="<?php echo ($v["id"]); ?>" user_id="<?php echo ($v["user_id"]); ?>">评论</li>
                            </ul>
                        </div>
                        <!--=====回复框结束=====-->
                    </div>
                </div>
                <!--=====普通微博样式结束=====-->
            <?php else: ?>
                <!--=====转发微博样式=====-->
                <div class="weibo">
                    <!--头像-->
                    <div class="face">
                        <a href="<?php echo U('/'.$v['user_id']);?>">
                            <img src="<?php if($v["face"]): ?>__ROOT__/Uploads/Face/<?php echo ($v["face"]); else: ?>__PUBLIC__/Images/noface.gif<?php endif; ?>" width='50' height='50'/>
                        </a>
                    </div>
                    <div class="wb_cons">
                        <dl>
                            <!--用户名-->
                            <dt class='author'>
                                <a href="<?php echo U('/'.$v['user_id']);?>">
                                    <?php echo ($v["username"]); ?>
                                </a>
                            </dt>
                            <!--发布内容-->
                            <dd class='content'>
                                <p><?php echo (replace_content($v["content"])); ?></p>
                            </dd>
                            <!--转发的微博内容-->
                        <?php if($v['isturn'] == -1): ?><dd class="wb_turn">该微博已被主人删除</dd>
                        <?php else: ?>
                            <dd>
                                <div class="wb_turn">
                                    <dl>
                                        <!--原作者-->
                                        <dt class='turn_name'><a href="<?php echo U('/'.$v['isturn']['user_id']);?>">@<?php echo ($v["isturn"]["username"]); ?></a></dt>
                                        <!--原微博内容-->
                                        <dd class='turn_cons'><p><?php echo (replace_content($v["isturn"]["content"])); ?></p></dd>
                                    <?php if($v['isturn']['max']): ?><!--原微博图片-->
                                        <dd>
                                            <div class="turn_img">
                                                <!--小图-->
                                                <img src="__ROOT__/Uploads/Picture/<?php echo ($v["isturn"]["mini"]); ?>" class='turn_mini_img'/>
                                                <div class="turn_img_tool hidden">
                                                    <ul>
                                                        <li>
                                                            <i class='icon icon-packup'></i>
                                                            <span class='packup'>&nbsp;收起</span></li>
                                                        <li>|</li>
                                                        <li>
                                                            <i class='icon icon-bigpic'></i>
                                                            <a href="__ROOT__/Uploads/Picture/<?php echo ($v["isturn"]["max"]); ?>" target='_blank'>&nbsp;查看大图</a>
                                                        </li>
                                                    </ul>
                                                    <!--中图-->
                                                    <div class="turn_img_info">
                                                        <img src="__ROOT__/Uploads/Picture/<?php echo ($v["isturn"]["medium"]); ?>"/>
                                                    </div>
                                                </div>
                                            </div>
                                        </dd><?php endif; ?>
                                    </dl>
                                    <!-----转发微博操作----->
                                    <div class="turn_tool">
                                        <span class="send_time"><?php echo (time_format($v["isturn"]["time"])); ?></span>
                                        <ul>
                                            <li>
                                                <a href="">转发<?php if($v['isturn']['turn']): ?>(<?php echo ($v["isturn"]["turn"]); ?>)<?php endif; ?></a>
                                            </li>
                                            <li>|</li>
                                            <li>
                                                <a href="">评论<?php if($v['isturn']['comment']): ?>(<?php echo ($v["isturn"]["comment"]); ?>)<?php endif; ?></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </dd><?php endif; ?>
                        </dl>
                        <!-----操作----->
                        <div class="wb_tool">
                            <!--发布时间-->
                            <span class="send_time"><?php echo (time_format($v["time"])); ?></span>
                            <ul>
                            <?php if(isset($v['keep_id'])): ?><li><span>收藏于: <?php echo (time_format($v["keep_time"])); ?></span></li>
                                <li>|</li><?php endif; ?>
                                <li>
                                    <span class='turn' weibo_id="<?php echo ($v["id"]); ?>" turn_id="<?php echo ($v["isturn"]["id"]); ?>">转发<?php if($v['turn']): ?>(<?php echo ($v["comment"]); ?>)<?php endif; ?></span>
                                </li>
                                <li>|</li>
                                <li class="keep-wrap">
                                <?php if(isset($v['keep_id'])): ?><span class="cancel-keep" keep_id="<?php echo ($v["keep_id"]); ?>" weibo_id="<?php echo ($v["id"]); ?>">取消收藏</span>
                                <?php else: ?>
                                    <span class='keep' weibo_id="<?php echo ($v["id"]); ?>">收藏<?php if($v['keep']): ?>(<?php echo ($v["keep"]); ?>)<?php endif; ?></span>
                                    <div class="keep-up hidden"></div><?php endif; ?>
                                </li>
                                <li>|</li>
                                <li>
                                    <span class='comment' weibo_id="<?php echo ($v["id"]); ?>">评论<?php if($v['comment']): ?>(<?php echo ($v["comment"]); ?>)<?php endif; ?></span>
                                </li>
                            </ul>
                        </div>
                        <!--=====回复框=====-->
                        <div class='comment_load hidden'>
                            <img src="__PUBLIC__/Images/loading.gif">评论加载中，请稍候...
                        </div>
                        <div class='comment_list hidden'>
                            <textarea name="" sign='comment<?php echo ($key); ?>'></textarea>
                            <ul>
                                <li class='phiz fleft' sign='comment<?php echo ($key); ?>'></li>
                                <li class='comment_turn fleft'>
                                    <label>
                                        <input type="checkbox" name=''/>同时转发到我的微博
                                    </label>
                                </li>
                                <li class='comment_btn fright' weibo_id="<?php echo ($v["id"]); ?>" user_id="<?php echo ($v["user_id"]); ?>">评论</li>
                            </ul>
                        </div>
                        <!--=====回复框结束=====-->
                    </div>
                </div>
                <!--=====转发微博样式结束=====--><?php endif; endforeach; endif; ?>
    <?php else: ?>
        没有<?php if(isset($atme)): ?>@我<?php else: ?>收藏<?php endif; ?>的微博<?php endif; ?>
                <div style="font-size: 18px;width: 100%;height: 100px;line-height: 100px;text-align: center;"><?php echo ($page); ?></div>
            </div>
    	</div>
    </div>
    <!--==========内容主体结束==========-->
    <!--==========底部==========-->
    <div id="bottom">
        <div class='link'>
            <dl>
                <dt>新浪新闻</dt>
                <dd><a href="">新浪网新闻中心是新浪网最重要的频道之一</a></dd>
            </dl>
            <dl>
                <dt>新浪财经</dt>
                <dd><a href="">新浪财经提供7X24小时财经资讯及全球金融市场报价</a></dd>
            </dl>
            <dl>
                <dt>新浪娱乐</dt>
                <dd><a href="">新浪娱乐是最新最全面的娱乐新闻信息综合站点</a></dd>
            </dl>
            <dl>
                <dt>新浪体育</dt>
                <dd><a href="">新浪体育提供最快速最全面最专业的体育新闻和赛事报道</a></dd>
            </dl>
            <dl>
                <dt>新浪邮箱</dt>
                <dd><a href="">新浪邮箱,提供以@sina.com和@sina.cn为后缀的免费邮箱</a></dd>
            </dl>
        </div>
        <div id="copy">
            <div>
                <p>版权所有：<?php echo (C("WEBCOPY")); ?> 站长统计 All rights reserved, weibo.com services for Shannxi 2018-2022</p>
            </div>
        </div>
    </div>
    <!--==========底部==========-->
    <!--==========表情选择框==========-->
    <div id="phiz" class='hidden'>
        <div>
            <p>常用表情</p>
            <span class='close fright'></span>
        </div>
        <ul>
            <li><img src="__PUBLIC__/Images/phiz/hehe.gif" alt="呵呵" title="呵呵" /></li>
            <li><img src="__PUBLIC__/Images/phiz/xixi.gif" alt="嘻嘻" title="嘻嘻" /></li>
            <li><img src="__PUBLIC__/Images/phiz/haha.gif" alt="哈哈" title="哈哈" /></li>
            <li><img src="__PUBLIC__/Images/phiz/keai.gif" alt="可爱" title="可爱" /></li>
            <li><img src="__PUBLIC__/Images/phiz/kelian.gif" alt="可怜" title="可怜" /></li>
            <li><img src="__PUBLIC__/Images/phiz/wabisi.gif" alt="挖鼻屎" title="挖鼻屎" /></li>
            <li><img src="__PUBLIC__/Images/phiz/chijing.gif" alt="吃惊" title="吃惊" /></li>
            <li><img src="__PUBLIC__/Images/phiz/haixiu.gif" alt="害羞" title="害羞" /></li>
            <li><img src="__PUBLIC__/Images/phiz/jiyan.gif" alt="挤眼" title="挤眼" /></li>
            <li><img src="__PUBLIC__/Images/phiz/bizui.gif" alt="闭嘴" title="闭嘴" /></li>
            <li><img src="__PUBLIC__/Images/phiz/bishi.gif" alt="鄙视" title="鄙视" /></li>
            <li><img src="__PUBLIC__/Images/phiz/aini.gif" alt="爱你" title="爱你" /></li>
            <li><img src="__PUBLIC__/Images/phiz/lei.gif" alt="泪" title="泪" /></li>
            <li><img src="__PUBLIC__/Images/phiz/touxiao.gif" alt="偷笑" title="偷笑" /></li>
            <li><img src="__PUBLIC__/Images/phiz/qinqin.gif" alt="亲亲" title="亲亲" /></li>
            <li><img src="__PUBLIC__/Images/phiz/shengbin.gif" alt="生病" title="生病" /></li>
            <li><img src="__PUBLIC__/Images/phiz/taikaixin.gif" alt="太开心" title="太开心" /></li>
            <li><img src="__PUBLIC__/Images/phiz/ldln.gif" alt="懒得理你" title="懒得理你" /></li>
            <li><img src="__PUBLIC__/Images/phiz/youhenhen.gif" alt="右哼哼" title="右哼哼" /></li>
            <li><img src="__PUBLIC__/Images/phiz/zuohenhen.gif" alt="左哼哼" title="左哼哼" /></li>
            <li><img src="__PUBLIC__/Images/phiz/xiu.gif" alt="嘘" title="嘘" /></li>
            <li><img src="__PUBLIC__/Images/phiz/shuai.gif" alt="衰" title="衰" /></li>
            <li><img src="__PUBLIC__/Images/phiz/weiqu.gif" alt="委屈" title="委屈" /></li>
            <li><img src="__PUBLIC__/Images/phiz/tu.gif" alt="吐" title="吐" /></li>
            <li><img src="__PUBLIC__/Images/phiz/dahaqian.gif" alt="打哈欠" title="打哈欠" /></li>
            <li><img src="__PUBLIC__/Images/phiz/baobao.gif" alt="抱抱" title="抱抱" /></li>
            <li><img src="__PUBLIC__/Images/phiz/nu.gif" alt="怒" title="怒" /></li>
            <li><img src="__PUBLIC__/Images/phiz/yiwen.gif" alt="疑问" title="疑问" /></li>
            <li><img src="__PUBLIC__/Images/phiz/canzui.gif" alt="馋嘴" title="馋嘴" /></li>
            <li><img src="__PUBLIC__/Images/phiz/baibai.gif" alt="拜拜" title="拜拜" /></li>
            <li><img src="__PUBLIC__/Images/phiz/sikao.gif" alt="思考" title="思考" /></li>
            <li><img src="__PUBLIC__/Images/phiz/han.gif" alt="汗" title="汗" /></li>
            <li><img src="__PUBLIC__/Images/phiz/kun.gif" alt="困" title="困" /></li>
            <li><img src="__PUBLIC__/Images/phiz/shuijiao.gif" alt="睡觉" title="睡觉" /></li>
            <li><img src="__PUBLIC__/Images/phiz/qian.gif" alt="钱" title="钱" /></li>
            <li><img src="__PUBLIC__/Images/phiz/shiwang.gif" alt="失望" title="失望" /></li>
            <li><img src="__PUBLIC__/Images/phiz/ku.gif" alt="酷" title="酷" /></li>
            <li><img src="__PUBLIC__/Images/phiz/huaxin.gif" alt="花心" title="花心" /></li>
            <li><img src="__PUBLIC__/Images/phiz/heng.gif" alt="哼" title="哼" /></li>
            <li><img src="__PUBLIC__/Images/phiz/guzhang.gif" alt="鼓掌" title="鼓掌" /></li>
            <li><img src="__PUBLIC__/Images/phiz/yun.gif" alt="晕" title="晕" /></li>
            <li><img src="__PUBLIC__/Images/phiz/beishuang.gif" alt="悲伤" title="悲伤" /></li>
            <li><img src="__PUBLIC__/Images/phiz/zuakuang.gif" alt="抓狂" title="抓狂" /></li>
            <li><img src="__PUBLIC__/Images/phiz/heixian.gif" alt="黑线" title="黑线" /></li>
            <li><img src="__PUBLIC__/Images/phiz/yinxian.gif" alt="阴险" title="阴险" /></li>
            <li><img src="__PUBLIC__/Images/phiz/numa.gif" alt="怒骂" title="怒骂" /></li>
            <li><img src="__PUBLIC__/Images/phiz/xin.gif" alt="心" title="心" /></li>
            <li><img src="__PUBLIC__/Images/phiz/shuangxin.gif" alt="伤心" title="伤心" /></li>
        </ul>
    </div>
    <!--==========表情==========-->
    <!--[if IE 6]>
    <script type="text/javascript" src="__PUBLIC__/Js/DD_belatedPNG_0.0.8a-min.js"></script>
    <script type="text/javascript">
        DD_belatedPNG.fix('#top','background');
        DD_belatedPNG.fix('.logo','background');
        DD_belatedPNG.fix('#sech_text','background');
        DD_belatedPNG.fix('#sech_sub','background');
        DD_belatedPNG.fix('.send_title','background');
        DD_belatedPNG.fix('.icon','background');
        DD_belatedPNG.fix('.ta_right','background');
    </script>
    <![endif]-->
</body>
</html>