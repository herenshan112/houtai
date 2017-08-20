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

		

		</ul>

    </div>


		<div class="seco_cont">
        
        	<div class="seco_title">
            	<b>选择配件</b>
                <span onClick="close_div('secodiv')">&nbsp;</span>
            </div>
            
            <div class="seco_cont_cont">
            	<div class="seco_cont_div">
                	<div class="seco_condiv_title">
                    	<a>查询</a>
                        <input type="text" name="sousoxg" id="sousoxg" value="">
                        <select name="sousuotype" id="sousuotype">
                        	<option value="1">商品名称</option><!--
                            <option value="2">关键字</option>
                            <option value="3">全文模糊搜索</option>
                            <option value="4">游戏编号</option>
                            <option value="5">ID</option>-->
                        </select>
                        <span onClick="shopsousuo()">确认查询</span>
                    </div>
                    
                    <div class="seco_list_div">
                    	<div class="seco_list">
                        	<ul id="shoplist">
                            	
                            </ul>
                            <div class="page_div">
                            	<label id="page_list"><a href=""><<</a><a href=""><</a><a href="">1</a><a href="">2</a><a href="">></a><a href="">>></a></label>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="seco_cont_divs">
                	<div class="seco_condiv_title"><b>已选择关联商品</b></div>
                    <div class="seco_list_divs">
                    	<div class="seco_list">
                        	<ul id="codegmlist">
                            	
                            </ul>
                        </div>
                    </div>
                    
                    <div class="seco_entery">
                    <input type="hidden" name="gaetitle" id="gaetitle" value="">
                    <input type="hidden" name="gamenum" id="gamenum" value="">
                    	<input type="button" value="确定选择" onClick="choshopval('gaetitle','gamenum','shopxzlst')">
                        <input type="button" value="取消选择" onClick="allqxshop()" class="hover">
                    </div>
                    
                </div>
            </div>
        
        </div>
	</div>



</body>
</html>