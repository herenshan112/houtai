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

		<li><a href="#">报表导出</a></li>

		</ul>

    </div>

    <div class="rightinfo">		<div class="tools">			<!-- <ul class="seachform" style="float:left;">				<form name="search_form" action="/Admin/Finance/export" method="GET">				<li><label>查询订单号：</label><input type="text" class="scinput" name="ordernum" placeholder="请输入订单号查询..." value="<?php echo ($_GET['ordernum']); ?>" /></li>				<li><label>状态：</label><select class="select3 scinput" name="status"><option value="0">全部</option><option value="2"<?php if($_GET['status']==2) echo ' selected';?>>待发货</option><option value="3"<?php if($_GET['status']==3) echo ' selected';?>>已发货</option></select></li>				<li><label>支付开始日期：</label><input type="text" class="scinput" name="starttime" id="starttime" placeholder="请输入开始日期..." value="<?php echo ($_GET['starttime']); ?>" /><div id="st" style="width: 350px;"></div></li>				<li><label>支付结束日期：</label><input type="text" class="scinput" name="endtime" id="endtime" placeholder="请输入结束日期..." value="<?php echo ($_GET['endtime']); ?>" /><div id="et" style="width: 350px;"></div></li>				<li><label>&nbsp;</label><input type="submit" value="查询" class="scbtn" /></li>				</form>			</ul> -->			<!-- <ul class="toolbar1">				<li class="click"><a href="<?php echo U('exports');?>" target="_blank"><span></span>导出报表</a></li>			</ul> -->			<!--ul class="toolbar1">			<li><span><img src="images/t05.png" /></span>设置</li>			</ul-->		</div>		<div id="usual1" class="usual"> 	    	    <div class="itab">		  	<ul> 		    <li><a href="<?php echo U('export');?>" class="selected">销售报表</a></li> 		    <li><a href="<?php echo U('export1');?>">支付报表</a></li>		    <li><a href="<?php echo U('export2');?>">推荐报表</a></li>		  	</ul>	    </div> 	    	  	<div id="tab1" class="tabson">	  		<form action="/Admin/Finance/export" method="POST" target="_blank">	  		<input type="hidden" name="op" value="sell">		    <ul class="seachform">		    	<li><label>订单状态</label><select class="select3 scinput" name="status"><option value="0">全部</option><option value="2">待发货</option><option value="3">已发货</option></select></li>		    	<li><label>支付开始日期：</label><input type="text" class="scinput" name="starttime" id="starttime" placeholder="请输入开始日期..." value="" /><div id="st" style="width: 350px;"></div></li>				<li><label>支付结束日期：</label><input type="text" class="scinput" name="endtime" id="endtime" placeholder="请输入结束日期..." value="" /><div id="et" style="width: 350px;"></div></li>		    	<li><label>&nbsp;</label><input type="submit" class="btn" value="导出" /></li>		    </ul>		    </form>	    </div>	    	  	<!--div id="tab2" class="tabson">		    <form action="/Admin/Finance/export" method="POST" target="_blank">	  		<input type="hidden" name="op" value="pay">		    <ul class="seachform">		    	<li><label>支付方式</label><select class="select3 scinput" name="status"><option value="0">全部</option><option value="2">微信</option><option value="3">积分</option></select></li>		    	<li><label>支付开始日期：</label><input type="text" class="scinput" name="starttime" id="starttime" placeholder="请输入开始日期..." value="" /><div id="st" style="width: 350px;"></div></li>				<li><label>支付结束日期：</label><input type="text" class="scinput" name="endtime" id="endtime" placeholder="请输入结束日期..." value="" /><div id="et" style="width: 350px;"></div></li>		    	<li><label>&nbsp;</label><input type="submit" class="btn" value="导出" /></li>		    </ul>		    </form>	    </div>	    <div id="tab3" class="tabson">		    <form action="/Admin/Finance/export" method="POST" target="_blank">	  		<input type="hidden" name="op" value="rec">		    <ul class="seachform">		    	<li><label>推荐类型</label><select class="select3 scinput" name="status"><option value="0">全部</option><option value="2">医生</option><option value="3">患者</option></select></li>		    	<li><label>开始日期：</label><input type="text" class="scinput" name="starttime" id="starttime" placeholder="请输入开始日期..." value="" /><div id="st" style="width: 350px;"></div></li>				<li><label>结束日期：</label><input type="text" class="scinput" name="endtime" id="endtime" placeholder="请输入结束日期..." value="" /><div id="et" style="width: 350px;"></div></li>		    	<li><label>&nbsp;</label><input type="submit" class="btn" value="导出" /></li>		    </ul>		    </form>	    </div-->	       		</div>     </div><script type="text/javascript"> $("#usual1 ul").idTabs(); </script><script type="text/javascript" src="/Public/Admin/js/jquery2.js"></script><script type="text/javascript" src="/Public/Admin/js/calendar.js"></script><script type="text/javascript">$('#st').calendar({    trigger: '#starttime',    zIndex: 999,	format: 'yyyy-mm-dd',    onSelected: function (view, date, data) {        console.log('event: onSelected')    },    onClose: function (view, date, data) {        console.log('event: onClose')        console.log('view:' + view)        console.log('date:' + date)        console.log('data:' + (data || 'None'));    }});$('#et').calendar({    trigger: '#endtime',    zIndex: 999,	format: 'yyyy-mm-dd',    onSelected: function (view, date, data) {        console.log('event: onSelected')    },    onClose: function (view, date, data) {        console.log('event: onClose')        console.log('view:' + view)        console.log('date:' + date)        console.log('data:' + (data || 'None'));    }});</script>

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