$(function(){
    //点击刷新验证码
     var verifyUrl = $('#verify-img').attr('src');
     $('#verify-img').click(function(){
         $(this).attr('src',verifyUrl + '/' + Math.random());
     });
     //jQuery Validate 表单验证
    /*
    * 添加验证方法
    * 以字母开头，5-16位 字母，数字，下划线
    * */
    jQuery.validator.addMethod("user", function(value, element) {
        var tel = /^[a-zA-Z][\w]{4,15}$/;
        return this.optional(element) || (tel.test(value));
    }, "以字母开头，5-16 位字母，数字，下划线'_'");
    $('form[name=register]').validate({
        errorElement : 'span',
        success : function(label){
            label.addClass('success');
        },
        rules : {
            account : {
                required : true,
                user : true,
                remote : {
                    url : checkAccountUrl,
                    type : 'post',
                    dataType : 'json',
                    data : {
                        account : function(){
                            return $('#account').val();
                        }
                    }
                }
            },
            pwd : {
                required : true,
                user : true
            },
            pwded : {
                required : true,
                equalTo : '#pwd'
            },
            username : {
                required : true,
                rangelength : [2,10],
                remote : {
                    url : checkUsernameUrl,
                    type : 'post',
                    dataType : 'json',
                    data : {
                        username : function(){
                            return $('#username').val();
                        }
                    }
                }
            },
            verify : {
                required : true,
                remote : {
                    url : checkVerifyUrl,
                    type : 'post',
                    dataType : 'json',
                    data : {
                        verify : function(){
                            return $('#verify').val();
                        }
                    }
                }
            }
        },
        messages : {
            account : {
                required : '账号不能为空',
                remote : '该账号已存在！'
            },
            pwd : {
                required : '密码不能为空'
            },
            pwded : {
                required : '请确认密码',
                equalTo : '两次密码不一致'
            },
            username : {
                required : '请填写昵称',
                rangelength : '昵称在2-10个字之间',
                remote : '该昵称已存在'
            },
            verify : {
                required : '',
                remote : ''
            }
        }
    });
});