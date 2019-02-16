/**
 * 首页
 * @author Carmen
 */
$(function () {
    /**
     * 图片点击放大处理
     */
    $('.mini_img').click(function () {
        $(this).hide().next().show();
    });
    $('.img_info img').click(function () {
        $(this).parents('.img_tool').hide().prev().show();
    });
    $('.packup').click(function () {
        $(this).parent().parent().parent().hide().prev().show();
    });
    $('.turn_mini_img').click(function () {
        $(this).hide().next().show();
    });
    $('.turn_img_info img').click(function () {
        $(this).parents('.turn_img_tool').hide().prev().show();
    });
    /**
     * 转发框处理
     */
    $('.turn').click(function () {
        //获取原微博内容，并添加到转发框
        var orgObj = $(this).parents('.wb_tool').prev();
        var author = $.trim(orgObj.find('.author').html());
        var content = orgObj.find('.content p').html();
        //提取 原微博/ 转发微博ID
        $('form[name=turn] input[name=weibo_id]').val($(this).attr('weibo_id'));
        var turnId = $(this).attr('turn_id') ? $(this).attr('turn_id') : 0;
        $('form[name=turn] input[name=turn_id]').val(turnId);
        var cons = '';
        //多重转发时，转发框处理
        if(turnId){
            author = orgObj.find('.author a').html();
            cons = replace_weibo('//@' + author + ': ' + content);
            //替换微博内容，去除<a>链接，与 表情图片
            author = $.trim(orgObj.find('.turn_name').html());
            content = orgObj.find('.turn_cons p').html();
        }
        $('.turn_main p').html(author + ':' + content);
        $('.turn_main textarea').val(cons);
        //获取同时评论的 用户名称
        $('.turn-cname').html(author);
        //隐藏表情框
        $('#phiz').hide();
        //点击转发创建透明背景层
        createBg('opacity_bg');
        //定位转发框居中
        var turnLeft = ($(window).width() - $('#turn').width()) / 2;
        var turnTop = $(document).scrollTop() + ($(window).height() - $('#turn').height()) / 2;
        $('#turn').css({
            'left' : turnLeft,
            'top' : turnTop
        }).fadeIn().find('textarea').focus(function () {
            $(this).css('borderColor', '#FF9B00').keyup(function () {
                var content = $(this).val();
                var lengths = check(content);  //调用check函数取得当前字数
                //最大允许输入140个字
                if (lengths[0] >= 140) {
                    $(this).val(content.substring(0, Math.ceil(lengths[1])));
                }
                var num = 140 - Math.ceil(lengths[0]);
                var msg = num < 0 ? 0 : num;
                //当前字数同步到显示提示
                $('#turn_num').html(msg);
            });
        }).focus().blur(function () {
            $(this).css('borderColor', '#CCCCCC');	//失去焦点时还原边框颜色
        });
    });
    drag($('#turn'), $('.turn_text'));  //拖拽转发框
    /*
    * 异步收藏处理
    * */
    $('.keep').click(function(){
        var weiboId = $(this).attr('weibo_id');
        var keepUp = $(this).next();
        var msg = '';
        $.post(keepUrl,{'weibo_id': weiboId},function(data){
            if(data == 1){
                msg = '收藏成功';
            }
            if(data == -1){
                msg = '已收藏';
            }
            if(data == 0){
                msg = '收藏失败';
            }
            keepUp.html(msg).fadeIn();
            setTimeout(function(){
                keepUp.fadeOut();
            },3000);
        },'json');
    });
    /**
     * 评论框处理
     */
    //点击评论时异步提取数据
    $('.comment').toggle(function () {
        //异步加载状态DIV
        var commentLoad = $(this).parents('.wb_tool').next();
        var commentList = commentLoad.next();
        //获取被评论微博的ID
        var weiboId = $(this).attr('weibo_id');
        //异步获取评论内容
        $.ajax({
            url : getCommentUrl,
            data : {'weibo_id' : weiboId},
            dataType : 'html',
            type : 'post',
            //在发送请求之前调用
            beforeSend : function (){
                commentLoad.show();
            },
            success : function (data){
                if(data != 'false'){
                    commentList.append(data);
                }
            },
            //当请求完成之后调用
            complete : function (){
                commentLoad.hide();
                commentList.show().find('textarea').val('').focus();
            }
        });
    }, function () {
        $(this).parents('.wb_tool').next().next().hide().find('dl').remove();
        $('#phiz').hide();
    });
    //评论输入框获取焦点时改变边框颜色
    $('.comment_list textarea').focus(function () {
        $(this).css('borderColor', '#FF9B00');
    }).blur(function () {
        $(this).css('borderColor', '#CCCCCC');
    }).keyup(function () {
        var content = $(this).val();
        var lengths = check(content);  //调用check函数取得当前字数
        //最大允许输入140个字
        if (lengths[0] >= 140) {
            $(this).val(content.substring(0, Math.ceil(lengths[1])));
        }
    });
    //回复
    $('.reply a').live('click', function () {
        var reply = $(this).parent().siblings('a').html();
        $(this).parents('.comment_list').find('textarea').val('回复@' + reply + ' ：');
        return false;
    });
    //异步提交评论
    $('.comment_btn').click(function () {

        var commentList = $(this).parents('.comment_list');
        var _textarea = commentList.find('textarea');
        var content = _textarea.val();
        //评论内容为空时，不做处理
        if (content == '') {
            _textarea.focus();
            return false;
        }
        //提取评论内容
        var cdata = {
            'content' : content,
            weibo_id : $(this).attr('weibo_id'),
            'isturn' : $(this).prev().find('input:checked').val() ? 1 : 0,
            'user_id' : $(this).attr('user_id')
        };
        //异步评论
        $.post(commentUrl,cdata,function(data){
            if(data != 'false'){
                //判断是否评论并同时转发，如果转发，页面刷新，如果不，添加子节点
                if(cdata.isturn){
                    window.location.reload();
                }else{
                    commentList.find('ul').after(data);
                    _textarea.val('');
                }
            }else{
                alert('评论失败');
            }
        },'html');
    });
    /*
    * 异步评论分页 处理
    * */
    $('.comment-page dd').live('click',function (){
        var commentList = $(this).parents('.comment_list');
        var commentLoad = commentList.prev();
        var weiboId = $(this).attr('weibo_id');
        var page = $(this).attr('page');
        $.ajax({
            url : getCommentUrl,
            data : {'weibo_id' : weiboId,'page' : page},
            dataType : 'html',
            type : 'post',
            //在发送请求之前调用
            beforeSend : function (){
                commentList.hide().find('dl').remove();
                commentLoad.show();
            },
            success : function (data){
                if(data != 'false'){
                    commentList.append(data);
                }
            },
            //当请求完成之后调用
            complete : function (){
                commentLoad.hide();
                commentList.show().find('textarea').val('').focus();
            }
        });
    });
    /*
    * 异步取消收藏
    * */
    $('.cancel-keep').click(function(){
        var isCancel = confirm('确认取消收藏？？？？？？')
        var data = {
            keep_id : $(this).attr('keep_id'),
            weibo_id : $(this).attr('weibo_id')
        };
        var obj = $(this).parents('.weibo');
        if(isCancel){
            $.post(cancelKeepUrl,data,function(data){
                if(data){
                    obj.slideUp('slow',function(){
                        obj.remove();
                    });
                }else{
                    alert('取消收藏失败');
                }
            },'json');
        }
    });
    /**
     * 表情处理
     * 以原生JS添加点击事件，不走jQuery队列事件机制
     */
    var phiz = $('.phiz');
    for (var i = 0; i < phiz.length; i++) {
        phiz[i].onclick = function () {
            //定位表情框到对应位置
            $('#phiz').show().css({
                'left' : $(this).offset().left,
                'top' : $(this).offset().top + $(this).height() + 5
            });
            //为每个表情图片添加事件
            var phizImg = $("#phiz img");
            var sign = this.getAttribute('sign');
            for (var i = 0; i < phizImg.length; i++){
                phizImg[i].onclick = function () {
                    var content = $('textarea[sign = '+sign+']');
                    content.val(content.val() + '[' + $(this).attr('title') + ']');
                    $('#phiz').hide();
                }
            }
        }
    }
    //关闭表情框
    $('.close').hover(function () {
        $(this).css('backgroundPosition', '-100px -200px');
    }, function () {
        $(this).css('backgroundPosition', '-75px -200px');
    }).click(function () {
        $(this).parent().parent().hide();
        $('#phiz').hide();
        if ($('#turn').css('display') == 'none') {
            $('#opacity_bg').remove();
        };
    });
});
/**
 * 统计字数
 * @param  字符串
 * @return 数组[当前字数, 最大字数]
 */
function check (str) {
    var num = [0, 140];
    for (var i=0; i<str.length; i++) {
        //字符串不是中文时
        if (str.charCodeAt(i) >= 0 && str.charCodeAt(i) <= 255){
            num[0] = num[0] + 0.5;//当前字数增加0.5个
            num[1] = num[1] + 0.5;//最大输入字数增加0.5个
        } else {//字符串是中文时
            num[0]++;//当前字数增加1个
        }
    }
    return num;
}
/*
* 替换微博内容，去除<a>链接 与 表情图片
* */
function replace_weibo(cons){
    //$1 元字组 (.*?)
    cons = cons.replace(/<img.*?title=['"](.*?)['"].*?\/?>/ig,'[$1]');
    return cons.replace( /<a.*?>(.*?)<\/a>/ig,'$1');
}