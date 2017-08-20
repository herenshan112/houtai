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
	
	<script type="text/javascript" charset="utf-8" src="/Public/Admin/js/JTimer_1.3.js"></script>
	
	<script type="text/javascript" charset="utf-8" src="/Public/Admin/js/myjs.js"></script>
	<script type="text/javascript" charset="utf-8" src="/Public/Admin/js/uploadfile.js"></script>
	
	<script>  
          JTC.setDateFormat('yyyy-MM-dd');   //设置返回格式  
   	</script>



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
		<form action="/Admin/User/addhuiyuan/action/<?php echo ($action); ?>/typeid/<?php echo ($typeid); ?>" method="post" name="myform" id="myform">
			
		<table border="0" cellpadding="0" cellspacing="0" style="float: left;; margin: 0 0 0 25px;">
			<tr>
				<td align="left" width="85px" height="40px">用户名</td>
				<td><input name="usernamers"  id="usernamers" class="dfinput" type="text" autocomplete="off" onblur="jumpuser()" /></td>
				<td><em id="load_user" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">密码</td>
				<td><input name="password" id="password" class="dfinput" type="password" autocomplete="off" onblur="jumpmia()" /></td>
				<td><em id="sky_1" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">确认密码</td>
				<td><input name="password2" id="password2" class="dfinput" type="password" autocomplete="off" onblur="jumpqrmm()" /></td>
				<td><em id="sky_2" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">昵称</td>
				<td><input name="nickname" class="dfinput" type="text" autocomplete="off" /></td>
				<td><em id="sky_3" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">邮箱</td>
				<td><input name="email" id="email" class="dfinput" type="text" autocomplete="off" onblur="jumpemail()" /></td>
				<td><em id="sky_4" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">手机</td>
				<td><input name="phone" id="phone" class="dfinput" type="text" autocomplete="off" onblur="jumpphon()" /></td>
				<td><em id="sky_5" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">固定电话</td>
				<td><input name="tel" class="dfinput" type="text" autocomplete="off" /></td>
				<td><em id="sky_6" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">性别</td>
				<td><input name="sex" type="radio" value="1" checked />男&nbsp;&nbsp;<input name="sex" type="radio" value="0" />女</td>
				<td><em id="sky_7" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">生日</td>
				<td><input type="text" class="scinput" name="starttime" id="starttime" placeholder="请输入生日..." value="" /><div id="st" style="width: 350px;"></div></td>
				<td><em id="sky_8" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">地址</td>
				<td>
					<select class="sellect" id="pro_list" name="provinces">
						<option value="-1">请选择城市</option>
						<?php if(is_array($prolist)): $i = 0; $__LIST__ = $prolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$prolist): $mod = ($i % 2 );++$i;?><option value="<?php echo ($prolist["region_id"]); ?>"><?php echo ($prolist["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
					</select>
					<select class="sellect" id='city_list' name="city" style="display: none;">
						<option value="-1">请选择城市</option>
					</select>
					<select class="sellect" id="county_list" name="county" style="display: none;">
						<option value="-1">请选择地区</option>
					</select>
			
				</td>
				<td><em id="load_jz" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">补充地址</td>
				<td><input name="address" class="dfinput" type="text" autocomplete="off" /></td>
				<td><em id="sky_9" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">头像</td>
				<td>
				
				<input type="text" class="dfinput" id="toppic" name='headpic' /> <input type="button" id="uploadbtndf" value="选择图片" onclick="getElementById('uppicimg').click()" style="height:30px;width:80px;cursor:pointer;border-radius:2px;background-color:#DDD;" />
			
			<input type="file" multiple="uppicimg" id="uppicimg" style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/> <input type="hidden" name="uppicimgtype" id="uppicimgtype" value="4" /> <!--批量上传图片-->
				
				
				</td>
				<td><i>限jpg gif jpeg png 格式 200kb以内</i></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px"></td>
				<td><input type="button" class="btn" value="确认添加" onclick="tianjiaqr()" /></td>
				<td><em id="load_user" style="display: none;"></em></td>
			</tr>
		</table>
		
		
		</form>
    </div>
<script type="text/javascript" src="/Public/Admin/js/calendar.js"></script>
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
$('#st').calendar({
    trigger: '#starttime',
    zIndex: 999,
	format: 'yyyy-mm-dd',
    onSelected: function (view, date, data) {
        console.log('event: onSelected')
    },
    onClose: function (view, date, data) {
        console.log('event: onClose')
        console.log('view:' + view)
        console.log('date:' + date)
        console.log('data:' + (data || 'None'));
    }
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
					$('#titlepicpreview').show();
					editor.hideDialog();
				}
			});
		});
	});
	
	
	
	//上传图片（本地、网络），营业制造
	K('#uploadbtnyyzz').click(function() {
		editor.loadPlugin('image', function() {
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#titlepicyyzz').val(),
				clickFn : function(url, title, width, height, border, align) {
					K('#titlepicyyzz').val(url);
					K('#yyzzimg').attr('src', url);
					$('#yyzzimg').show();
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
	
	
	/*K('#J_selectImage').click(function() {
		editor.loadPlugin('multiimage', function() {
			editor.plugin.multiImageDialog({
				clickFn : function(urlList) {
					var div = K('#J_imageView');
					//div.html('');
					var dzcc='';
					alert(urlList);
					K.each(urlList, function(i, data) {
						div.append('<img src="' + data.url + '">');
						dzcc=dzcc+data.url+',';
					});
					K('#picary').val(K('#picary').val()+dzcc);
					editor.hideDialog();
				}
			});
		});
	});*/
	
	
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