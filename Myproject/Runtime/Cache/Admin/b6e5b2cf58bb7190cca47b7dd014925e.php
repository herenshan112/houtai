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

		

		<li><a href="#">产品分类列表</a></li>
		<ul class="toolbar1" style="margin: 3px 10px 0 0;">
		<li class="click"><a href="<?php echo U('typelist', array('action'=>'add'));?>"><span></span>添加产品分类</a></li>
		</ul>
	

		</ul>

    </div>



    <div class="rightinfo">

		<table class="imgtable">

			<thead>

			<tr>

			<th>序号</th>
			<th>栏目</th>
			<th>写入时间</th>
			<th>状态</th>
			<th>操作</th>

			</tr>

			</thead>

		

			<tbody>

			<?php if(is_array($products_list)): foreach($products_list as $k=>$list): ?><tr>

				<td><?php echo ($k+1); ?></td>
				<td><?php echo ($list["delimiter"]); echo ($list["title"]); ?></td>
				<td><?php echo (date('Y-m-d H:i:s',$list["time"])); ?></td>
				<td><?php if(($list["setval"]) != "0"): ?>启用<?php else: ?><font color="#ff0000">关闭</font><?php endif; ?></td>

				<td>

					<p><a href="/Admin/Products/typelist/action/eite/id/<?php echo ($list["id"]); ?>">修改</a></p>

					<p><a href="/Admin/Products/typelist/action/del/id/<?php echo ($list["id"]); ?>" onclick="if (confirm('确定要删除吗？')) return true; else return false;">删除</a></p>

				</td>

				</tr><?php endforeach; endif; ?>

			</tbody>

		

		</table>

		<div class="yellow"><?php echo ($page); ?></div>

    </div>

    

<script type="text/javascript">

	$('.imgtable tbody tr:odd').addClass('odd');

	</script>





</body>
</html>