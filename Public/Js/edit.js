$(function () {
	//基本信息，修改头像，修改密码 选项卡
	$('#sel-edit li').click( function () {
		var index = $(this).index();
		$(this).addClass('edit-cur').siblings().removeClass('edit-cur');
		$('.form').hide().eq(index).show();
	} );
	//城市联动
	var province = '';
	$.each(city, function (i, k) {
		province += '<option value="' + k.name + '" index="' + i + '">' + k.name + '</option>';
	});
	$('select[name=province]').append(province).change(function () {
		var option = '';
		if ($(this).val() == '') {
			option += '<option value="">请选择</option>';
		} else {
			var index = $(':selected', this).attr('index');
			var data = city[index].child;
			for (var i = 0; i < data.length; i++) {
				option += '<option value="' + data[i] + '">' + data[i] + '</option>';
			}
		}
		$('select[name=city]').html(option);
	});
	//所在地默认选项
	address = address.split(' ');
	$('select[name=province]').val(address[0]);
	$.each(city,function(i,k){
		if(k.name == address[0]){
			var str = '';
			for(var j in k.child){
				str += '<option value="' + k.child[j] + '"';
				if(k.child[j] == address[1]){
					str += 'selected = "selected"';
				}
				str += '>' + k.child[j] + '</option>';
			}
			$('select[name=city]').html(str);
		}
	});
	//星座默认选项
	$('select[name=night]').val(constellation);
	 //头像上传 Uploadify 插件
	/*$('#face').uploadify({
		swf : PUBLIC + '/Uploadify/uploadify.swf',	//引入Uploadify核心Flash文件
		uploader : uploadFaceUrl,		//PHP处理脚本地址
		width : 120,	//上传按钮宽度
		height : 30,	//上传按钮高度
		buttonImage : PUBLIC + '/Uploadify/browse-btn.png',	//上传按钮背景图
		fileTypeDesc : 'Image File',		//选择文件提示文字
		fileTypeExts : '*.jpeg; *.jpg; *.png; *.gif',	// 允许选择的文件类型
		formData : {'session_id' : session_id},
		//上传成功后的回调函数
		onUploadSuccess : function(file,data,response){
			alert(data);
		}
	});*/
    //jQuery Validate 表单验证
    /*
    * 添加验证方法
    * 以字母开头，5-16位 字母，数字，下划线
    * */
    jQuery.validator.addMethod("user", function(value, element) {
        var tel = /^[a-zA-Z][\w]{4,15}$/;
        return this.optional(element) || (tel.test(value));
    }, "以字母开头，5-16 位字母，数字，下划线'_'");
    $('form[name=editPwd]').validate({
        errorElement : 'span',
        success : function(label){
            label.addClass('success');
        },
        rules : {
            old : {
                required : true,
                user : true,
            },
            new : {
                required : true,
                user : true
            },
            newed : {
                required : true,
                equalTo : '#new'
            }
        },
        messages : {
            old : {
                required : '旧密码不能为空',
            },
            new : {
                required : '新密码不能为空'
            },
            newed : {
                required : '请确认密码',
                equalTo : '两次密码不一致'
            }
        }
    });
});