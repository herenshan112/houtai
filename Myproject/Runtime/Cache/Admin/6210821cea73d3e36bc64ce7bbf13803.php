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

		<li><a href="#">经销商经销价格统计</a></li>

		</ul>

    </div>


<style type="text/css">
.imgtable th, .imgtable td {
	text-align: center;
	text-indent: 0;
}
</style>
    <div class="rightinfo">
		<div class="tools">
			<ul class="seachform" style="float:left;">
				<form name="search_form" action="<?php echo U('lookjxsjgx');?>?ly=<?php echo ($ly); ?>" method="post">
				<li><label>查询：</label><input type="text" class="scinput" name="ordernum" placeholder="请输入关键词..." value="<?php echo ($p_ordernum); ?>" />
					<select name="lookset" id="lookset" class="scinputfb">
						<option value="0" <?php if(($lookset) == "0"): ?>selected<?php endif; ?>>订单号</option>
						<option value="1" <?php if(($lookset) == "1"): ?>selected<?php endif; ?>>产品名称</option>
						<option value="2" <?php if(($lookset) == "2"): ?>selected<?php endif; ?>>经销商帐号</option>
						<option value="3" <?php if(($lookset) == "3"): ?>selected<?php endif; ?>>经销商姓名</option>
					</select>
					<select name="ddcxff"  id="ddcxff" style="display: none;" class="scinputfb">
						
						<option value="2" <?php if(($ddcxff) == "2"): ?>selected<?php endif; ?>>发货</option>
						
						
						
					</select>
					<input type="text" class="scinput" name="starttime" id="starttime" placeholder="请输入查询日期" value="<?php echo ($starttime); ?>" /><div id="stdd" style="width: 350px;"></div>
				</li>
				
				<!--li><label>：</label><input type="text" class="scinput" name="domain" placeholder="请输入域查询..." value="<?php echo ($_GET['domain']); ?>" /></li-->
				<li><label>&nbsp;</label><input type="submit" value="查询" class="scbtn" /></li>
				<li><label>&nbsp;</label><input type="button" value="导出" class="scbtn" onclick="exportongji(4)" /></li>
				<li class="jsztcx"><a href="<?php echo U('jxsjiage',array('action'=>'wei'));?>" class="scbtn" style="text-align: center; margin-right: 10px;">未结算</a><a href="<?php echo U('jxsjiage',array('action'=>'deng'));?>" class="scbtn" style="text-align: center; margin-right: 10px;">等待经销商确认</a><a href="<?php echo U('jxsjiage',array('action'=>'wan'));?>" class="scbtn" style="text-align: center; margin-right: 10px;">完成</a></li>
				</form>
				
				
			</ul>
	
			<!--<ul class="toolbar1">
				<li class="click"><a href="<?php echo U('daochu', array('typeid'=>-1));?>" target="_blank"><span></span>信息导出</a></li>
			</ul>-->

			<!--ul class="toolbar1">
			<li><span><img src="images/t05.png" /></span>设置</li>
			</ul-->
		</div>
		<table class="imgtable">

			<thead>

			<tr>

			<th>订单编号</th>
			<th>会员信息</th>
			<th>商品</th>
			<th>订单金额</th>
			<th>经销商金额</th>
			<th>下单时间</th>
			<th>状态</th>
			<th>支付信息</th>
			<th>收货信息</th>
			<th>是否已结算</th>
			<th>操作</th>

			</tr>

			</thead>

		

			<tbody>

			<?php if(is_array($orders_list)): foreach($orders_list as $key=>$list): ?><tr>

				<td><?php echo ($list["ordernum"]); ?></td>
				<td><p><?php echo ($list["user"]["nickname"]); ?></p><p><?php echo ($list["user"]["phone"]); ?></p></td>
				<td><p>&nbsp;</p>
				<?php if(is_array($list['buy'])): foreach($list['buy'] as $key=>$buy_row): ?><p><?php echo ($buy_row["title"]); ?> 【数量：<?php echo ($buy_row["num"]); ?>&nbsp;商品价格:<?php echo ($buy_row["price"]); ?>元&nbsp;经销商价格:<font color="#ff0000"><?php echo ($buy_row["price_jsx"]); ?></font>元</p><?php endforeach; endif; ?>
				<p>&nbsp;</p></td>
				<td>&yen;<?php echo ($list["money"]); ?>元</td>
				<td>&yen;<?php echo (jsxjagejs($list["ordernum"])); ?>元</td>
				<td>
					<p>下单：<?php echo (date('Y-m-d H:i:s',$list["addtime"])); ?></p>
					推广来源：<?php echo (ordersource($list["code_jxs"])); ?>
				</td>
				<td><?php echo ($list["stat"]); if(($list["fhsetval"]) == "1"): ?><br><?php if(($list["orderstatus"]) == "5"): ?>委托<?php else: ?>发货<?php endif; ?>时间:<?php echo (date('Y-m-d H:i:s',$list["fpsj"])); ?><br>发货方：<?php echo (fhfordersource($list["fahuofang"])); endif; ?></td>
				<td><?php
 if($list['orderstatus']>1) { echo '<p>支付方式：'; if ($list['paytype'] == 0) { echo '线下支付'; } else if ($list['paytype'] == 1) { echo '微信'; } else if ($list['paytype'] == 2) { echo '支付宝'; } echo '</p>'; if ($list['paytype'] == 0) { echo '<p>消费积分：'; echo $list['corn']; echo '</p>'; } else if ($list['paytype'] == 1){ echo '<p>支付金额：'; echo $list['total_fee']/100 . '元'; echo '</p>'; echo '<p>支付编号：'; echo $list['transaction_id']; echo '</p>'; } echo '<p>支付时间：'; echo date('Y-m-d H:i:s', strtotime($list['time_end'])); echo '</p>'; } else { echo '未支付'; } ?></td>
				
				<td><br><p>收货人：<?php echo ($list["detail"]["uname"]); ?></p><p>电话：<?php echo ($list["detail"]["uphone"]); ?></p><p>地址：<?php echo ($list["detail"]["uaddr"]); ?></p><br></td>
				<td>
					<?php switch($list["sfjsset"]): case "1": ?><font color="#D9D300">等待经销商确认</font><?php break;?>    
						<?php case "2": ?><font color="#008000">完成</font><?php break;?>    
						<?php default: ?><font color="#ff0000">未结算</font><?php endswitch;?>
				</td>
				<td>
					<?php if($list['orderstatus'] == 2): ?><p><a href="/Admin/Statis/fahuo/id/<?php echo ($list["id"]); ?>">发货</a></p>
					<!--else />
					<p><a href="/Admin/Statis/detail/id/<?php echo ($list["id"]); ?>">查看</a></p--><?php endif; ?>
					<p><a href="<?php echo U('lookjs');?>?id=<?php echo ($list["id"]); ?>">查看</a></p>
					<?php if(!in_array(($list["sfjsset"]), explode(',',"1,2"))): ?><a href="<?php echo U('jiesuan');?>?setval=1&id=<?php echo ($list["id"]); ?>"><font color="#ff0000">结算</font></a><?php endif; ?>
					<!--p><a href="/Admin/Statis/mod/id/<?php echo ($list["id"]); ?>">修改</a></p-->
					<p><a href="/Admin/Orders/del/id/<?php echo ($list["id"]); ?>/status/<?php echo ($_GET['status']); ?>" onclick="if (confirm('确定要删除吗？')) return true; else return false;">删除</a></p>
				</td>

				</tr><?php endforeach; endif; ?>

			</tbody>

		

		</table>

		<div class="yellow"><?php echo ($page); ?></div>

    </div> <div class="pu_wkj" id="expordiv" style="display: none;">
	<div class="tjkj_cont">
		<div class="seco_title">
            <b id="dctstitle">信息导出</b><input type="hidden" name="ddnumbh" id="ddnumbh" value="" />
            <span onClick="close_xinxidac('expordiv')">&nbsp;</span>
        </div>
        
        <div class="tzjgshop_lst">
        	<div class="tzjs_cont_ll" id='jsxtjlistc'>
        		
        		<ul class="expor_div">
        			<li id="jxszhdy"><label>经销商帐号：</label><input class="scinput" type="text" placeholder="请输入经销商帐号" name="jxsuser" name="jxsuser" value=""><em class="tisjo">留空默认导出全部经销商</em></li>
        			<li><label>开始时间：</label><input type="text" class="scinput" name="begtimeks" id="begtimeks" placeholder="请输入开始日期" value="" onclick="JTC.setday()" /></li>
        			<li><label>结束时间：</label><input type="text" class="scinput" name="endtimeks" id="endtimeks" placeholder="请输入结束日期" value="" onclick="JTC.setday()" /><em id="ertisky" class="jkhns"></li>
        			<li style="color: #f00;"><label>&nbsp;</label>不选择开始结束时间的话，默认是全部时间的内容！</li>
        		</ul>
        		<div>
        			<input class="button_expor" type="button" value="确定导出" onclick="exporxxcl()" />
        		</div>
        		<input type="hidden" name="exportype" id="exportype" value="" />
        	</div>
        </div>
        
	</div>
</div>

<script type="text/javascript" src="/Public/Admin/js/calendar.js"></script>
<script>
$('.imgtable tbody tr:odd').addClass('odd');
$('#stdd').calendar({
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