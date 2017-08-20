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

		<li><a href="#">经销商处理</a></li>

		</ul>

    </div>


	
	
    <div class="formbody">
		<div class="formtitle"><span>经销商处理</span></div>
		<form action="/Admin/User/addjxs/action/<?php echo ($action); ?>/typeid/<?php echo ($typeid); ?>/id/<?php echo ($id); ?>" method="post" name="myform" id="myform">
			
		<table border="0" cellpadding="0" cellspacing="0" style="float: left;; margin: 0 0 0 25px;">
			<tr>
				<td align="left" width="85px" height="40px">用户名</td>
				<td><?php echo ($list["username"]); ?></td>
				<td><em id="load_user" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">联系人</td>
				<td><input name="nickname" class="dfinput" type="text" autocomplete="off" value="<?php echo ($list["nickname"]); ?>" /></td>
				<td><em id="sky_3" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">邮箱</td>
				<td><input name="email" id="email" class="dfinput" type="text" autocomplete="off" value="<?php echo ($list["email"]); ?>" /></td>
				<td><em id="sky_4" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">手机</td>
				<td><input name="phone" id="phone" class="dfinput" type="text" autocomplete="off" value="<?php echo ($list["phone"]); ?>" /></td>
				<td><em id="sky_5" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">固定电话</td>
				<td><input name="tel" class="dfinput" type="text" autocomplete="off" value="<?php echo ($list["telval"]); ?>" /></td>
				<td><em id="sky_6" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">性别</td>
				<td><input name="sex" type="radio" value="1" <?php if(($list["sex"]) == "1"): ?>checked<?php endif; ?> />男&nbsp;&nbsp;<input name="sex" type="radio" value="0" <?php if(($list["sex"]) != "1"): ?>checked<?php endif; ?> />女</td>
				<td><em id="sky_7" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">生日</td>
				<td><input type="text" class="scinput" name="starttime" id="starttime" placeholder="请输入生日..." value="<?php echo (date('Y-m-d',$list["shengri"])); ?>" /><div id="st" style="width: 350px;"></div></td>
				<td><em id="sky_8" style="display: none;"></em></td>
			</tr>
			
			
			<tr>
				<td align="left" width="85px" height="40px">公司名称</td>
				<td><input name="poratename" id="poratename" class="dfinput" type="text" autocomplete="off" value="<?php echo ($list["poratename"]); ?>" /></td>
				<td><em id="sky_12" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">公司地址</td>
				<td><input name="porateaddress" id="porateaddress" class="dfinput" type="text" autocomplete="off" value="<?php echo ($list["porateaddress"]); ?>" /></td>
				<td><em id="sky_10" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">营业执照</td>
				<td><input type="text" class="dfinput" id="titlepicyyzz" name='titlepicyyzz' value="<?php echo ($list["porateipc"]); ?>" /> <input type="button" id="uploadbtnyyzz" value="选择图片" style="height:30px;width:80px;cursor:pointer;border-radius:2px;background-color:#DDD;" /><br><i>限jpg gif jpeg png 格式 200kb以内</i></td>
				<td><em id="sky_11" style="float:left; display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px"></td>
				<td><img src="<?php echo ($list["porateipc"]); ?>" style="width:150px;" src="" id="yyzzimg"></td>
				<td><em id="sky_9" style="display: none;"></em></td>
			</tr>
			
			
			<tr>
				<td align="left" width="85px" height="40px">销售区域</td>
				<td>
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
			
				</td>
				<td><em id="load_jz" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">补充地址</td>
				<td><input name="address" class="dfinput" type="text" autocomplete="off" value="<?php echo ($list["address"]); ?>" /></td>
				<td><em id="sky_9" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="40px">头像</td>
				<td><input type="text" class="dfinput" id="titlepic" name='headpic' value="<?php echo ($list["headpic"]); ?>" /> <input type="button" id="uploadbtn" value="选择图片" style="height:30px;width:80px;cursor:pointer;border-radius:2px;background-color:#DDD;" /><br><i>限jpg gif jpeg png 格式 200kb以内</i></td>
				<td></td>
			</tr>
			<tr>
				<td align="left" width="85px"></td>
				<td><img src="<?php echo ($list["headpic"]); ?>" style="width:150px;" id="titlepicpreview"></td>
				<td><em id="sky_9" style="display: none;"></em></td>
			</tr>
			<tr>
				<td align="left" width="85px" height="150px">备注</td>
				<td><textarea name="count" style="width: 350px; height: 140px; border: 1px solid #ccc; padding: 5px;"><?php echo ($list["count"]); ?></textarea></td>
				<td class="ermyl">
					<label>二维码预览</label>
					<img src="<?php echo U('/Index/Index/qrcode', array('code'=>$list['code'], 'op'=>'1'));?>" alt="">
					<span>二维码地址：<script>document.write(host)</script><?php echo U('/Index/Index/qrcode', array('code'=>$list['code'], 'op'=>'1'));?></span>
					<input type="hidden" value="<?php echo ($list["code"]); ?>" name="code" />
				</td>
			</tr>
			
			
			<tr>
				<td align="left" width="85px" height="40px"></td>
				<td><input type="submit" class="btn" value="确认修改" /></td>
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
					$('#load_jz').html('');
					$('#load_jz').removeClass();
					$('#load_jz').addClass('icon-spinner icon-spin icon-large');
					$('#load_jz').show();
				},
				success:function(data){
					$('#load_jz').html('');
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
						jxsset[3]=1;
					}else{
						jxsset[3]=0;
					}
					
				},
				error:function(error){
					jxsset[3]=0;
					$('#load_jz').html('');
					$('#load_jz').removeClass();
					$('#load_jz').addClass('icon-remove-sign');
					document.getElementById('load_jz').style.color='#ff0000';
				}
			});
		}else{
			jxsset[3]=0;
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