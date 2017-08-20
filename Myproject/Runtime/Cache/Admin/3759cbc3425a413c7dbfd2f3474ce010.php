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
	<!--<script type="text/javascript" charset="utf-8" src="/Public/kindeditor/kindeditor-min.js"></script>
	<script type="text/javascript" charset="utf-8" src="/Public/kindeditor/lang/zh_CN.js"></script>
	<link href="/Public/kindeditor/themes/default/default.css" rel="stylesheet" type="text/css" />-->
	
	
	<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
    <script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
	
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

    <div class="rightinfo">    	<form action="<?php echo U('delall');?>" method="POST" id="delall_form">		<table class="imgtable">			<thead>			<tr>			<th><input type="checkbox" id="checkall" /></th>			<th>姓名</th>			<th>联系方式</th>			<th>留言时间</th>			<th>操作</th>			</tr>			</thead>			<tbody>				<?php if(is_array($message_list)): foreach($message_list as $key=>$list): ?><tr>					<td><input name="opid[]" type="checkbox" value="<?php echo ($list["id"]); ?>" /></td>				<td><?php if(!$list['isread']){echo '<b style="color:#F00">(未读)</b>';}?> <?php echo ($list["name"]); ?></td>				<td><?php echo ($list["phone"]); ?></td>				<td><?php echo ($list["addtime"]); ?></td>				<td>					<p><a href="/Admin/Message/detail/id/<?php echo ($list["id"]); ?>">查看</a></p>					<p><a href="/Admin/Message/del/id/<?php echo ($list["id"]); ?>" onclick="if (confirm('确定要删除吗？')) return true; else return false;">删除</a></p>				</td>				</tr><?php endforeach; endif; ?>			</tbody>		</table>		<div class="yellow"><button type="button" id="delall_btn" style="position: absolute;left: 10px;background-color: #f24141;color: #FFF; border-radius: 5px; padding: 5px 10px;cursor: pointer; width: 50px;">删除</button><?php echo ($page); ?></div>		</form>    </div><script type="text/javascript">$('.imgtable tbody tr:odd').addClass('odd');$("#delall_btn").click(function(event) {	event.preventDefault();	if (!confirm("是否要删除，删除后不可恢复?")) return false;	$("#delall_form").submit();});$("#checkall").click(function(event) {	if ($(this).is(':checked')) {		$("input[name='opid[]']").each(function(index, el) {			$(this).attr("checked",true);		});	} else {		$("input[name='opid[]']").each(function(index, el) {			$(this).attr("checked",false);		});	}});</script><script type="text/javascript">$('.imgtable tbody tr:odd').addClass('odd');</script>



</body>
</html>