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

		<li><a href="#">添加规格</a></li>

		</ul>

    </div>


    <div class="formbody">
		<div class="formtitle"><span>添加</span></div>
		<form action="/Admin/Products/guige/action/<?php echo ($action); ?>/id/<?php echo ($id); ?>" method="post" enctype="multipart/form-data" name="myform">
		<ul class="forminfo">
		
		<li><label>规格名称:</label><input name="title" class="dfinput" type="text" value="<?php echo ($list["gg_title"]); ?>"><i></i></li>
		<li><label>状态</label><input name="setval" type="radio" value="1" <?php if(($list["gg_set"]) == "1"): ?>checked<?php endif; ?> />启用&nbsp;&nbsp;<input name="setval" type="radio" value="0" <?php if(($list["gg_set"]) != "1"): ?>checked<?php endif; ?> />关闭</li>

		<li><label>&nbsp;</label><input type="submit" class="btn" value="确认添加"></li>

		</ul>
		</form>
    </div>


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