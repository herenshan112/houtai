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

		<li><a href="#">支付统计</a></li>

		</ul>

    </div>

    <div class="rightinfo">		<div class="tools">			<ul class="seachform" style="float:left;">				<form name="search_form" action="/Admin/Finance/paylists" method="GET">				<!--li><label>查询订单号：</label><input type="text" class="scinput" name="ordernum" placeholder="请输入订单号查询..." value="<?php echo ($_GET['ordernum']); ?>" /></li-->				<li><label>支付方式：</label><select class="select3 scinput" name="paytype"><option value="0">全部</option><option value="2"<?php if($_GET['paytype']==2){echo ' selected';}?>>微信支付</option><option value="1"<?php if($_GET['paytype']==1){echo ' selected';}?>>积分兑换</option></select></li>				<li><label>支付开始日期：</label><input type="text" class="scinput" name="starttime" id="starttime" placeholder="请输入开始日期..." value="<?php echo ($_GET['starttime']); ?>" /><div id="st" style="width: 350px;"></div></li>				<li><label>支付结束日期：</label><input type="text" class="scinput" name="endtime" id="endtime" placeholder="请输入结束日期..." value="<?php echo ($_GET['endtime']); ?>" /><div id="et" style="width: 350px;"></div></li>				<li><label>&nbsp;</label><input type="submit" value="查询" class="scbtn" /></li>				</form>			</ul>			<!-- <ul class="toolbar1">				<li class="click"><a href="<?php echo U('exports');?>" target="_blank"><span></span>导出报表</a></li>			</ul> -->			<!--ul class="toolbar1">			<li><span><img src="images/t05.png" /></span>设置</li>			</ul-->		</div>		<table class="imgtable">			<thead>			<tr>			<th>订单编号</th>			<th>会员信息</th>			<th>商品</th>			<th>订单金额</th>			<th>下单时间</th>			<th>状态</th>			<th>支付信息</th>			<th>发货时间</th>			</tr>			</thead>					<tbody>			<?php if(is_array($orders_list)): foreach($orders_list as $key=>$list): ?><tr>				<td><?php echo ($list["ordernum"]); ?></td>				<td><p><?php echo ($list["user"]["nickname"]); ?></p><p><?php echo ($list["user"]["phone"]); ?></p></td>				<td><p>&nbsp;</p>				<?php if(is_array($list['buy'])): foreach($list['buy'] as $key=>$buy_row): ?><p>产品：<?php echo ($buy_row["title"]); ?> 【数量：<?php echo ($buy_row["num"]); ?>】</p><?php endforeach; endif; ?>				<p>&nbsp;</p></td>				<td>&yen;<?php echo ($list["money"]); ?>元</td>				<td><?php echo ($list["addtime"]); ?></td>				<td><?php echo ($list["stat"]); ?></td>				<td><br><?php if($list['orderstatus']>1) { echo '<p>支付方式：'; if ($list['paytype'] == 0) { echo '积分兑换'; } else if ($list['paytype'] == 1) { echo '微信'; } else if ($list['paytype'] == 2) { echo '支付宝'; } echo '</p>'; if ($list['paytype'] == 0) { echo '<p>消费积分：'; echo $list['total_fee']; echo '</p>'; } else if ($list['paytype'] == 1) { echo '<p>支付金额：'; echo $list['total_fee']/100 . '元'; echo '</p>'; echo '<p>支付编号：'; echo $list['transaction_id']; echo '</p>'; } echo '<p>支付时间：'; echo date('Y-m-d H:i:s', strtotime($list['time_end'])); echo '</p>'; } else { echo '未支付'; } ?><br></td>				<td><?php if($list['orderstatus'] > 2): echo ($list["fhtime"]); endif; ?></td>				<!--td>					<p><a href="/Admin/Finance/del/id/<?php echo ($list["id"]); ?>/status/<?php echo ($_GET['status']); ?>" onclick="if (confirm('确定要删除吗？')) return true; else return false;">删除</a></p>				</td-->				</tr><?php endforeach; endif; ?>			</tbody>				</table>		<div class="yellow"><?php echo ($page); ?></div>    </div><script type="text/javascript">$('.imgtable tbody tr:odd').addClass('odd');</script><script type="text/javascript" src="/Public/Admin/js/jquery2.js"></script><script type="text/javascript" src="/Public/Admin/js/calendar.js"></script><script type="text/javascript">$('#st').calendar({    trigger: '#starttime',    zIndex: 999,	format: 'yyyy-mm-dd',    onSelected: function (view, date, data) {        console.log('event: onSelected')    },    onClose: function (view, date, data) {        console.log('event: onClose')        console.log('view:' + view)        console.log('date:' + date)        console.log('data:' + (data || 'None'));    }});$('#et').calendar({    trigger: '#endtime',    zIndex: 999,	format: 'yyyy-mm-dd',    onSelected: function (view, date, data) {        console.log('event: onSelected')    },    onClose: function (view, date, data) {        console.log('event: onClose')        console.log('view:' + view)        console.log('date:' + date)        console.log('data:' + (data || 'None'));    }});</script>

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