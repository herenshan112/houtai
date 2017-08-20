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

		<li><a href="#">授权管理</a></li>

		</ul>

    </div>

    <div class="formbody">		<div class="formtitle"><span>授权管理</span></div>		<form action="/Admin/Admin/authlist" name="authform" method="POST">		<ul class="forminfo">		<?php if(is_array($auth_rows)): foreach($auth_rows as $key=>$auth_rows_item): ?><li><label style="width: 500px;">权限组：<b><?php echo ($auth_rows_item["authority"]); ?></b></label></li>		<li><?php if(is_array($menucate_rows)): foreach($menucate_rows as $key=>$menucate_rows_item): ?><div class="menucate"><label>1<input type="checkbox" name="menucate[]" value="<?php echo ($auth_rows_item["authority"]); ?>||<?php echo ($menucate_rows_item["id"]); ?>"<?php if(strpos($menucate_rows_item['authtype'], '|'.$auth_rows_item['authority'].'|')!==false){echo ' checked';}?> /> <?php echo ($menucate_rows_item["name"]); ?> </label>				<div class="menuitem">					<?php if(is_array($menucate_rows_item['subitem'])): foreach($menucate_rows_item['subitem'] as $key=>$subitem): ?><label>2<input type="checkbox" name="menuitem[]" value="<?php echo ($auth_rows_item["authority"]); ?>||<?php echo ($subitem["id"]); ?>"<?php if(strpos($subitem['authtype'], '|'.$auth_rows_item['authority'].'|')!==false){echo ' checked';}?> /> <?php echo ($subitem["name"]); ?></label><?php endforeach; endif; ?>				</div>			</div>			<div class="clear"></div><?php endforeach; endif; ?>		</li><?php endforeach; endif; ?>		<li><label>&nbsp;</label><input type="submit" class="btn" value="确认修改"/></li>		</ul>		</form>    </div><script type="text/javascript">/*$(function(e){	$("input[name='menuitem[]']").click(function(event) {		var checked_val = $(this).val();		var checked_txt = $(this).parent('label').text().trim();		var checked_val_arr = checked_val.split("||");		if ($(this).is(":checked")) {			if (confirm('是否要添加 "'+checked_val_arr[0]+'" 的 "'+checked_txt+'" 权限')) {				alert('x');			} else {				return false;			}		} else {			if (confirm('是否要删除 "'+checked_val_arr[0]+'" 的 "'+checked_txt+'" 权限')) {				alert('y');			} else {				return false;			}		}	});});*/</script>



</body>
</html>