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

		
<li><a href="#">列表</a></li>


		</ul>

    </div>


    <div class="rightinfo">
    	<div class="tools">
	    	<ul class="seachform" style="float:left;">
	    		<form name="search_form" action="/Admin/User/lists" method="GET">
				<li><label>查询手机号：</label><input type="text" class="scinput" name="user" placeholder="请输入手机号查询..." value="<?php echo ($_GET['user']); ?>" /></li>
				<!--li><label>：</label><input type="text" class="scinput" name="domain" placeholder="请输入域查询..." value="<?php echo ($_GET['domain']); ?>" /></li-->
				<li><label>&nbsp;</label><input type="submit" value="查询" class="scbtn" /></li>
				</form>
			</ul>

			<ul class="toolbar1">
				<?php if(($op) == "2"): ?><li class="click"><a href="<?php echo U('addjxs', array('typeid'=>$op,'action'=>'add'));?>"><span></span>添加经销商</a></li>
				<?php else: ?>
					<li class="click"><a href="<?php echo U('addhuiyuan', array('typeid'=>$op,'action'=>'add'));?>"><span></span>添加会员</a></li><?php endif; ?>
				
				<li class="click"><a href="<?php echo U('export', array('typeid'=>$op));?>" target="_blank"><span></span>信息导出</a></li>
			</ul>

			<!--ul class="toolbar1">
			<li><span><img src="images/t05.png" /></span>设置</li>
			</ul-->
		</div>

		<form action="<?php echo U('delall');?>" method="POST" id="delall_form">
		<table class="imgtable">

			<thead>
			<tr>
			<th width="50"><input type="checkbox" id="checkall" /></th>
			<th>ID</th>
			<th>帐号</th>
			<th>姓名</th>
			<th>手机号</th>
			
			<th>所在城市</th>
			
			<th>注册时间</th>
			<th>操作</th>
			</tr>
			</thead>

			<tbody>
			<?php if($lists != null): if(is_array($lists)): foreach($lists as $k=>$list_item): ?><tr style="height:32px">
					<td><input name="opid[]" type="checkbox" value="<?php echo ($list_item["id"]); ?>" /></td>
					<td><?php echo ($k+1); ?></td>
					<td><?php echo ($list_item["username"]); ?></td>
					<td><?php echo ($list_item["nickname"]); ?></td>
					<td><?php echo ($list_item["phone"]); ?></td>
					
					<td><?php echo szcitycx($list_item['city'],$list_item['county'],$list_item['address'],$list_item['provinces'],$op)?></td>
					
					<td><?php echo (date('Y-m-d H:i:s',$list_item["addtime"])); ?></td>
					<td><a href="<?php echo U('detail', array('id'=>$list_item['id'], 'op'=>$op));?>">查看</a> <a href="<?php echo U('mod', array('id'=>$list_item['id']));?>">修改密码</a> <?php if(($op) == "2"): ?><a href="<?php echo U('addjxs', array('action'=>'eite','id'=>$list_item['id'],'typeid'=>$op));?>">修改资料</a><?php else: ?><a href="<?php echo U('addhuiyuan', array('action'=>'eite','id'=>$list_item['id'],'typeid'=>$op));?>">修改资料</a><?php endif; ?> <a href="<?php echo U('del', array('id'=>$list_item['id']));?>" onclick="return confirm('是否要删除此条记录，删除后不可恢复?');">删除</a>&nbsp;&nbsp;<a href="<?php echo U('usnotice',array('id'=>$list_item['id']));?>">信息中心</a></td>
					</tr><?php endforeach; endif; ?>
			<?php else: ?>
				<tr style="height:32px"><td colspan="8">暂无记录！</td></tr><?php endif; ?>
			</tbody>
		</table>
		<div class="yellow"><button type="button" id="delall_btn" style="position: absolute;left: 10px;background-color: #f24141;color: #FFF; border-radius: 5px; padding: 5px 10px;cursor: pointer; width: 50px;">删除</button><?php echo ($page); ?></div>
		</form>
    </div>
<script type="text/javascript">
$('.imgtable tbody tr:odd').addClass('odd');

$("#delall_btn").click(function(event) {
	event.preventDefault();

	if (!confirm("是否要删除，删除后不可恢复?")) return false;

	$("#delall_form").submit();
});

$("#checkall").click(function(event) {
	if ($(this).is(':checked')) {
		$("input[name='opid[]']").each(function(index, el) {
			$(this).attr("checked",true);
		});
	} else {
		$("input[name='opid[]']").each(function(index, el) {
			$(this).attr("checked",false);
		});
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