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

		<li><a href="#">修改产品</a></li>

		</ul>

    </div>

    <div class="formbody">		<div class="formtitle"><span>修改产品</span></div>		<form action="/Admin/Products/mod" method="post" name="myform">		<input type="hidden" name="id" value="<?php echo ($data["id"]); ?>" />		<ul class="forminfo">		<li><label>名称:</label><input name="title" class="dfinput" type="text" value="<?php echo ($data["title"]); ?>" /><i></i></li>		<li><label>类型:</label>			<select class="sellect" name="type">				<option value="0">请选择类型</option>				<?php if(is_array($cpls)): $i = 0; $__LIST__ = $cpls;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cpls): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cpls["id"]); ?>" <?php if(($data["type"]) == $cpls["id"]): ?>selected<?php endif; ?>><?php echo ($cpls["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>			</select>		</li>				<li><label>规格:</label>			<select class="sellect" name="spec">				<option value="0">请选择规格</option>				<?php if(is_array($cpgg)): $i = 0; $__LIST__ = $cpgg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cpgg): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cpgg["gg_id"]); ?>" <?php if(($data["spec"]) == $cpgg["gg_id"]): ?>selected<?php endif; ?>><?php echo ($cpgg["gg_title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>			</select>		</li>		<!--<li><label>热卖:</label>			<cite><input name="remai" type="radio" value="1" <?php if(($data["remai"]) == "1"): ?>checked<?php endif; ?> />是&nbsp;&nbsp;			<input name="remai" type="radio" value="0" <?php if(($data["remai"]) == "0"): ?>checked<?php endif; ?> />否</cite>		</li>-->				<li><label>价格:</label><input name="price" class="dfinput" type="text" value="<?php echo ($data["price"]); ?>" /><i>元</i></li>				<li><label>属性:</label>			<cite>				<input type="radio" value="0" name="tejia" id="tejia" onclick="tejiaxy(0)" <?php if(($data["tejia"]) == "0"): ?>checked<?php endif; ?>>无&nbsp;&nbsp;				<input type="radio" value="1" name="tejia" id="tejia" onclick="tejiaxy(0)" <?php if(($data["tejia"]) == "1"): ?>checked<?php endif; ?>>热卖&nbsp;&nbsp;				<input type="radio" value="2" name="tejia" id="tejia" onclick="tejiaxy(2)" <?php if(($data["tejia"]) == "2"): ?>checked<?php endif; ?>>促销&nbsp;&nbsp;				<input type="radio" value="3" name="tejia" id="tejia" onclick="tejiaxy(0)" <?php if(($data["tejia"]) == "3"): ?>checked<?php endif; ?>>新品&nbsp;&nbsp;				<input type="radio" value="4" name="tejia" id="tejia" onclick="tejiaxy(0)" <?php if(($data["tejia"]) == "4"): ?>checked<?php endif; ?>>超值&nbsp;&nbsp;				<input type="radio" value="5" name="tejia" id="tejia" onclick="tejiaxy(5)" <?php if(($data["tejia"]) == "5"): ?>checked<?php endif; ?>>打折&nbsp;&nbsp;				<input type="radio" value="6" name="tejia" id="tejia" onclick="tejiaxy(6)" <?php if(($data["tejia"]) == "6"): ?>checked<?php endif; ?>>特价			</cite>		</li>				<!--<li><label>特价:</label>			<cite><input name="tejia" type="radio" value="1" onclick="tejiaxy(1)" <?php if(($data["tejia"]) == "1"): ?>checked<?php endif; ?> />是&nbsp;&nbsp;			<input name="tejia" type="radio" value="0"  onclick="tejiaxy(0)" <?php if(($data["tejia"]) == "0"): ?>checked<?php endif; ?> />否</cite>		</li>-->		<li id="tejiajg" <?php if(in_array(($data["tejia"]), explode(',',"0,1,3,4"))): ?>style="display: none;"<?php endif; ?>><label id="tjbtval">特价价格:</label><input name="tejiaprice" id="tejiaprice" class="dfinput" type="text" value="<?php echo ($data["tejiaprice"]); ?>" /><i>元</i></li>				<!--li><label>规格:</label><input name="spec" class="dfinput" type="text" value="<?php echo ($data["spec"]); ?>" /></li-->				<li><label>推荐:</label>			<cite>				<input type="radio" value="1" name="tuijian" id="tuijian"  <?php if(($data["tuijian"]) == "1"): ?>checked<?php endif; ?>>是&nbsp;&nbsp;				<input type="radio" value="0" name="tuijian" id="tuijian" <?php if(($data["tuijian"]) != "1"): ?>checked<?php endif; ?>>否&nbsp;&nbsp;			</cite>		</li>				<li><label>库存总数:</label><input name="totalnum" class="dfinput" type="text" value="<?php echo ($data["totalnum"]); ?>" /><i></i></li>		<li><label>虚拟销量:</label><input name="vsalenum" class="dfinput" type="text" value="<?php echo ($data["vsalenum"]); ?>" /><i>仅用于前端显示</i></li>		<li><label>快递费:</label><input name="postfee" class="dfinput" type="text" value="<?php echo ($data["postfee"]); ?>" /><i>0为包邮, 否则填写具体快递费金额</i></li>		<li><label>原图:</label><img src="<?php echo ($data["titlepic"]); ?>" style="width:100px;"><i></i></li>		<li><label>图片:</label>			<input type="text" class="dfinput" id="toppic" name='titlepic' value="<?php echo ($data["titlepic"]); ?>" /> <input type="button" id="uploadbtndf" value="选择图片" onclick="getElementById('uppicimgijk').click()" style="height:30px;width:80px;cursor:pointer;border-radius:2px;background-color:#DDD;" /><i>限jpg gif jpeg png 格式 200kb以内</i>						<input type="file" multiple="uppicimgijk" id="uppicimgijk" style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/> <input type="hidden" name="uppicimgtype" id="uppicimgtype" value="1" /> <!--批量上传图片-->											</li>												<li><label>相册集:</label>			<input type="button" value="上传图片集" onclick="getElementById('picimgary').click()" style="height:30px;width:80px;cursor:pointer;border-radius:2px;background-color:#DDD;">			<a id="imgaryerror"></a>			<span style="padding:0 15px; color:#f00;">单击已上传的图片可删除该图片(规格：480 * 300 大小：200K以内)</span>            <textarea name="imgvales" id="imgvales" style="width:100%; height:100PX; display:none;"><?php echo ($data["imgary"]); ?></textarea>                        <input type="file" multiple="picimgary" id="picimgary" style="height:0;width:0;z-index: -1; position: absolute;left: 10px;top: 5px;"/> <input type="hidden" name="picimgaryset" id="picimgaryset" value="1" /> <!--批量上传图片-->		</li>				<li class="tpjcss"><label>&nbsp;</label><div class="imgcpnt_div" id="imgcpntiv">						<?php $ltimg=explode(',',$data['imgary']); foreach($ltimg as $imal){ if($imal != ''){ echo '<span><img  onClick=delpicim("'.$imal.'") alt="单机删除" src="/upload/images/'.$imal.'"><a onClick=delpicim("'.$imal.'")>删除</a></span>'; } } ?>					</div></li>																						<li><label>配件:</label><div class="form_li"><span id="shopxzlst"><?php echo (shopspec($data["parts"])); ?></span><em onclick="chopeijian('secodiv','shoplist','page_list')">选择配件</em><input type="hidden" name="parts" id="parts" value="<?php echo ($data["parts"]); ?>" /></div></li>				<li><label>简介:</label><textarea name="smalltext" class="textinput" style="height: 80px"><?php echo ($data["smalltext"]); ?></textarea><i></i></li>		<li><label>详情:</label><textarea id="pagecontent" name="content" rows="20" cols="100" style="width:65%;height:300px;visibility:hidden;"><?php echo ($data["content"]); ?></textarea></li>		<li><label>排序:</label><input name="showturn" class="dfinput" type="text" value="<?php echo ($data["showturn"]); ?>" /><i>数值越大,越前显示</i></li>		<li><label>&nbsp;</label><input  type="submit" class="btn" value="确认修改"/></li>		</ul>		</form>		        </div>    <div class="pu_wkj" id="secodiv">
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