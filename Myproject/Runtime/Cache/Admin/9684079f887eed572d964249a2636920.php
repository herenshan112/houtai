<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>无标题文档</title>

	<link href="/Public/Admin/css/style.css" rel="stylesheet" type="text/css" />
	<link href="/Public/Admin/css/page.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="/Public/Admin/js/jquery-1.8.3.min.js"></script>
	<script type="text/javascript" src="/Public/Admin/js/jquery.idTabs.min.js"></script>
	<script type="text/javascript" src="/Public/Admin/js/select-ui.min.js"></script>
	<link href="/Public/icon/font-awesome.css" rel="stylesheet">
	<!-- 日历引入 -->
	<link href="/Public/Admin/css/calendar.css" rel="stylesheet" type="text/css" />

	<!--文字编辑器引入-->
	<script type="text/javascript" charset="utf-8" src="/Public/kindeditor/kindeditor-min.js"></script>
	<script type="text/javascript" charset="utf-8" src="/Public/kindeditor/lang/zh_CN.js"></script>
	<link href="/Public/kindeditor/themes/default/default.css" rel="stylesheet" type="text/css" />
	
	<script type="text/javascript" charset="utf-8" src="/Public/Admin/js/myjs.js"></script>



</head>

<body>

	<div class="place">

		<span>位置：</span>

		<ul class="placeul">

		<li><a href="/Admin/Index/right">首页</a></li>

		<li><a href="#">用户处理</a></li>

		</ul>

    </div>


    <div class="formbody">
		<div class="formtitle"><span>用户处理</span></div>
		<form action="/Admin/User/usercontcl/action/<?php echo ($action); ?>/typeid/<?php echo ($typeid); ?>/id/<?php echo ($id); ?>" method="post" name="myform">
		<ul class="forminfo">
		
		<li><label>用户名:</label><span><?php echo ($list["phone"]); ?></span><em id="load_user" style="display: none;"></em></li>
		<li><label>姓名:</label><input name="nickname" class="dfinput" type="text" autocomplete="off" value="<?php echo ($list["nickname"]); ?>" /><i></i></li>
		<li><label>邮箱:</label><input name="email" class="dfinput" type="text" autocomplete="off" value="<?php echo ($list["email"]); ?>" /><i></i></li>
		
		
		<li><label>地区:</label>
			<select class="sellect" id="pro_list" name="provinces">
				<option value="-1">请选择城市</option>
				<?php if(is_array($prolist)): $i = 0; $__LIST__ = $prolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$prolist): $mod = ($i % 2 );++$i;?><option value="<?php echo ($prolist["region_id"]); ?>" <?php if(($list["provinces"]) == $prolist["region_id"]): ?>selected<?php endif; ?>><?php echo ($prolist["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
			<select class="sellect" id='city_list' name="city" <?php if(($list["provinces"]) == "0"): ?>style="display: none;"<?php endif; ?>>
				<option value="-1">请选择城市</option>
				<?php if(is_array($citylt)): $i = 0; $__LIST__ = $citylt;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$citylt): $mod = ($i % 2 );++$i;?><option value="<?php echo ($citylt["region_id"]); ?>" <?php if(($list["city"]) == $citylt["region_id"]): ?>selected<?php endif; ?>><?php echo ($citylt["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
			<select class="sellect" id="county_list" name="county" <?php if(($list["city"]) == "0"): ?>style="display: none;"<?php endif; ?>>
				<option value="-1">请选择地区</option>
				<?php if(is_array($countylst)): $i = 0; $__LIST__ = $countylst;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$countylst): $mod = ($i % 2 );++$i;?><option value="<?php echo ($countylst["region_id"]); ?>" <?php if(($list["county"]) == $countylst["region_id"]): ?>selected<?php endif; ?>><?php echo ($countylst["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
			</select>
			<em id="load_jz" style="display: none;"></em>
		</li>
		<li><label>补充地址:</label><input name="address" class="dfinput" type="text" autocomplete="off" value="<?php echo ($list["address"]); ?>" /><i></i></li>
		
		<li><label>头像:</label><input type="text" class="dfinput" id="titlepic" name='headpic' value="<?php echo ($list["headpic"]); ?>" /> <input type="button" id="uploadbtn" value="选择图片" style="height:30px;width:80px;cursor:pointer;border-radius:2px;background-color:#DDD;" /><i>限jpg gif jpeg png 格式 200kb以内</i></li>
		<li><label>原图:</label><img src="<?php echo ($list["headpic"]); ?>" style="width:100px;"><i></i></li>
		<li><label>&nbsp;</label><input type="submit" class="btn" value="确认添加" /></li>
		</ul>
		</form>
    </div>
<script type="text/javascript">
var kaiguan=0;

$(function(e){
	
	//列出市级
	$('#pro_list').bind('change',function(e){
		var proval=this.value;
		if(!$("#city_list").is(":hidden")){
				$("#city_list").hide(100);
			}
			if(!$("#county_list").is(":hidden")){
				$("#county_list").hide(100);
			}
			$('#city_list').empty();
			$('#city_list').append('<option value="-1">请选择城市</option>');
			
			$('#county_list').empty();
			$('#county_list').append('<option value="-1">请选择地区</option>');
			
		if(proval != '-1'){
			$.ajax({
				url:host+'/Admin/User/lookaddres',
				type:'post',
				data:{proval:proval},
				dataType:'json',
				beforeSend:function(xmldata){
					$('#load_jz').removeClass();
					$('#load_jz').addClass('icon-spinner icon-spin icon-large');
					$('#load_jz').show();
				},
				success:function(data){
					$('#load_jz').removeClass();
					if(data.code == 1){
						$('#city_list').empty();
						$('#city_list').append('<option value="-1">请选择城市</option>');
						var sumval=data.infor.sum;
						var cont=data.infor.cont;
						for(i=0;i<sumval;i++){
							$('#city_list').append('<option value="'+cont[i].region_id+'">'+cont[i].region_name+'</option>');
						}
						$("#city_list").show(300);
					}
					
				},
				error:function(error){
					$('#load_jz').removeClass();
					$('#load_jz').addClass('icon-remove-sign');
					document.getElementById('load_jz').style.color='#ff0000';
				}
			});
		}
	});
	
	
	//列出县级
	$('#city_list').bind('change',function(e){
		var proval=this.value;
		if(!$("#county_list").is(":hidden")){
				$("#county_list").hide(100);
			}
			
			$('#county_list').empty();
			$('#county_list').append('<option value="-1">请选择地区</option>');
		if(proval != '-1'){
			$.ajax({
				url:host+'/Admin/User/lookaddres',
				type:'post',
				data:{proval:proval},
				dataType:'json',
				beforeSend:function(xmldata){
					$('#load_jz').removeClass();
					$('#load_jz').addClass('icon-spinner icon-spin icon-large');
					$('#load_jz').show();
				},
				success:function(data){
					$('#load_jz').removeClass();
					if(data.code == 1){
						$('#county_list').empty();
						$('#county_list').append('<option value="-1">请选择城市</option>');
						var sumval=data.infor.sum;
						var cont=data.infor.cont;
						for(i=0;i<sumval;i++){
							$('#county_list').append('<option value="'+cont[i].region_id+'">'+cont[i].region_name+'</option>');
						}
						$("#county_list").show(300);
					}
					
				},
				error:function(error){
					$('#load_jz').removeClass();
					$('#load_jz').addClass('icon-remove-sign');
					document.getElementById('load_jz').style.color='#ff0000';
				}
			});
		}
	});
	
	
	

	
});
</script>


<script type="text/javascript">
KindEditor.ready(function(K) {
	K.create('#pagecontent', {
		allowFileManager : true
	});

	var editor = K.editor({
		allowFileManager : true
	});
	//上传图片（本地、网络）
	K('#uploadbtn').click(function() {
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#titlepic').val(),
				clickFn : function(url, title, width, height, border, align) {
					K('#titlepic').val(url);
					K('#titlepicpreview').attr('src', url);
					editor.hideDialog();
				}
			});
		});
	});

	//文件上传
	K('#insertfile').click(function() {
		editor.loadPlugin('insertfile', function() {
			editor.plugin.fileDialog({
				fileUrl : K('#fileurl').val(),
				clickFn : function(url, title) {
					K('#fileurl').val(url);
					editor.hideDialog();
				}
			});
		});
	});
});
/*//实例化编辑器
	var editor = K.editor({
		allowFileManager : true
	});

	//文件上传
	K('#insertfile').click(function() {
		editor.loadPlugin('insertfile', function() {
			editor.plugin.fileDialog({
				fileUrl : K('#url').val(),
				clickFn : function(url, title) {
					K('#url').val(url);
					editor.hideDialog();
				}
			});
		});
	});
	
	//上传图片（本地、网络）
	K('#image1').click(function() {
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				imageUrl : K('#url1').val(),
				clickFn : function(url, title, width, height, border, align) {
					K('#url1').val(url);
					editor.hideDialog();
				}
			});
		});
	});
});
//批量上传图片
KindEditor.ready(function(K) {
	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_selectImage').click(function() {
		editor.loadPlugin('multiimage', function() {
			editor.plugin.multiImageDialog({
				clickFn : function(urlList) {
					var div = K('#J_imageView');
					div.html('');
					K.each(urlList, function(i, data) {
						div.append('<img src="' + data.url + '">');
					});
					editor.hideDialog();
				}
			});
		});
	});
});*/
</script>

</body>
</html>