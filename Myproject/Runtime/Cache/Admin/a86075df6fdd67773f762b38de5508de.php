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

		<li><a href="#">发货</a></li>

		</ul>

    </div>

    <div class="formbody">		<div class="formtitle"><span>发货</span></div>		<form action="/Admin/Orders/fahuo" method="post" name="myform">				<input type="hidden" name="id" value="<?php echo ($order_row["id"]); ?>">		<ul class="forminfo">				<li><label>订单编号:</label><cite><?php echo ($order_row["ordernum"]); ?></cite></li>		<li><label>推广来源:</label><cite><?php echo (ordersources($order_row["code_jxs"])); ?></cite></li>					<li><span class="title_dingdan">商品信息</span></li>				<li id="shanpxxlb">		<?php if(is_array($order_row['buy'])): foreach($order_row['buy'] as $key=>$buy_row): ?><label>商品:</label><cite><?php echo ($buy_row["title"]); ?> 【数量：<?php echo ($buy_row["num"]); ?>&nbsp;&nbsp;原始单价:￥<?php echo ($buy_row["spyuanjia"]); if(in_array(($buy_row["shuxing"]), explode(',',"2,5,6"))): ?>,&nbsp;&nbsp;<i style="color: #f00;"><?php switch($buy_row["shuxing"]): case "2": ?>促销<?php break;?>    <?php case "5": ?>打折<?php break;?>    <?php case "6": ?>特价<?php break;?>     <?php default: ?>特价<?php endswitch;?>:￥<?php echo ($buy_row["tejiaprice"]); ?></i><?php endif; if(($buy_row["price_jsx"]) != "0"): ?>,<i style="color: #f00;" id='jxsjg<?php echo ($buy_row["id"]); ?>'>&nbsp;&nbsp;经销商:￥<?php echo ($buy_row["price_jsx"]); ?></i><?php endif; ?>&nbsp;&nbsp;下单单价:￥<?php echo ($buy_row["price"]); ?>】</cite><br><?php endforeach; endif; ?>		</li>		<li><span class="title_dingdan">收货人信息</span></li>		<li><label>收货人:</label><cite><?php echo ($order_row["detail"]["uname"]); ?></cite><br><label>电话:</label><cite><?php echo ($order_row["detail"]["uphone"]); ?></cite><br><label>地址:</label><cite><?php echo ($order_row["detail"]["uaddr"]); ?></cite></li>				<li><span class="title_dingdan">发货操作</span></li>				<li><label>发货方:</label><cite><input type="radio" name="fhfset" id="fhfset" value="0" onclick="clfhfs(0,0)" <?php if(($fhsdval) == "0"): ?>checked<?php endif; ?> />总部&nbsp;&nbsp;<input type="radio" name="fhfset" id="fhfset" value="<?php echo ($fhsdval); ?>" onclick="clfhfs(<?php echo ($fhsdval); ?>,1)" <?php if(($fhsdval) != "0"): ?>checked<?php endif; ?> />经销商</cite></li>				<li><label>商品调价:</label><cite><input type="button" name="sptjanniu" id="sptjanniu" value="调整价格" onclick="admintzjgjsx('<?php echo ($order_row["ordernum"]); ?>')" style="background: #d98c1d; border-radius: 3px;" class="btn" /></cite></li>		<div id="jxsfahuo" <?php if(($fhsdval) == "0"): ?>style='display:none'<?php endif; ?>>				<li >			<label>发货经销商</label>			<cite><b id="jxszhans"><?php echo (ordersources($fhsdval)); ?></b><span class="btn ggjxsanniu" onclick='genggaijxs(0)'>更改经销商</span></cite>			<input type="hidden" name="fahuofang" id="fahuofang" value="<?php echo ($fhsdval); ?>" />		</li>		</div>				<div id="zbfhcl" <?php if(($fhsdval) != "0"): ?>style='display:none'<?php endif; ?>>		<li><label>物流公司:</label>			<select class="sellect" name="shipperid">				<?php if(is_array($shipper_rows)): foreach($shipper_rows as $key=>$lists): ?><option value="<?php echo ($lists["id"]); ?>"><?php echo ($lists["name"]); ?></option><?php endforeach; endif; ?>			</select>		</li>		<li><label>单号:</label><input name="shippernum" id="shippernum" class="dfinput" type="text" /><i></i></li>		</div>				<li><label>&nbsp;</label><input type="submit" class="btn subbtn" value="确认发货" /> <input type="button" onclick="history.go(-1);" style="background: #d98c1d; border-radius: 3px;" class="btn" value="返回" /></li>		</ul>		</form>    </div><script type="text/javascript">$(function(e){	$(".subbtn").click(function(e) {		e.preventDefault();		var fhf=0;		var radio = document.getElementsByName("fhfset");  	    for (i=0; i<radio.length; i++) {  	        if (radio[i].checked) {  	            fhf=radio[i].value;	        }  	    }		if(fhf != 0){			var fhfid=$('#fahuofang').val();			if(fhfid == 0){				alert('请填写物流单号!');				return false;			}		}else{			var shippernum_val = $("input[name='shippernum']").val();			if(shippernum_val.length < 1) {				alert('请填写物流单号！');				return false;			}		}					$("form[name='myform']").submit();	});});</script><div class="pu_wkj" id="secodiv" style="display: none;">
		<div class="seco_cont">
        
        	<div class="seco_title">
            	<b>选择经销商</b>
                <span onClick="close_div('secodiv')">&nbsp;</span>
            </div>
            
            <div class="seco_cont_cont">
            	<div class="seco_cont_div">
                	<div class="seco_condiv_titlefb">
                    	<a>渠道查询</a>
                        <input type="text" name="sousoxg" id="sousoxg" value="" style="width: 250px;">
                        <select name="sousuotype" id="sousuotype">
                        	<option value="1">经销商帐号</option>
                            <option value="2">经销商姓名</option><!--
                            <option value="3">全文模糊搜索</option>
                            <option value="4">游戏编号</option>
                            <option value="5">ID</option>-->
                        </select>
                        <span onClick="lookqudaokl()">确认查询</span>
                        <i>
                        	<a>销售区域</a>
	                        <select id="pro_list" name="provinces">
	                        	<option value="-1">省</option>
	                        	<?php if(is_array($prolist)): $i = 0; $__LIST__ = $prolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$prolist): $mod = ($i % 2 );++$i;?><option value="<?php echo ($prolist["region_id"]); ?>"><?php echo ($prolist["region_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	                        </select>
	                        <select id='city_list' name="city" style="display: none;">
	                        	<option value="-1">市</option>
	                        </select>
	                        <select id="county_list" name="county" style="display: none;">
	                        	<option value="-1">县</option>
	                        </select>
                        </i>
                    </div>
                    
                    <div class="seco_list_divs">
                    	<div class="seco_listjxs">
                        	<ul id="shoplist">
                            	
                            </ul>
                            <div class="page_div">
                            	<label id="page_list"><a href=""><<</a><a href=""><</a><a href="">1</a><a href="">2</a><a href="">></a><a href="">>></a></label>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="seco_cont_divs">
                	<div class="seco_condiv_title"><b>已选择经销商</b></div>
                    <div class="seco_list_divs">
                    	<div class="seco_list">
                        	<ul id="codegmlist">
                            	
                            </ul>
                        </div>
                    </div>
                    
                    <div class="seco_entery">
                    <input type="hidden" name="gaetitle" id="gaetitle" value="">
                    <input type="hidden" name="gamenum" id="gamenum" value="">
                    	<input type="button" value="确定选择" onClick="chousjxsval('gaetitle','gamenum','shopxzlst')">
                        <input type="button" value="取消选择" onClick="allqxshop()" class="hover">
                    </div>
                    
                </div>
            </div>
        
        </div> 
    
</div>

<div class="pu_wkj" id="taozhjgkj" style="display: none;">
	<div class="tjkj_cont">
		<div class="seco_title">
            <b>商品调价</b><input type="hidden" name="ddnumbh" id="ddnumbh" value="" />
            <span onClick="close_jxstj('taozhjgkj')">&nbsp;</span>
        </div>
        
        <div class="tzjgshop_lst">
        	<div class="tzjs_cont" id='jsxtjlistc'>
        		
        	
        		
        	</div>
        </div>
        
	</div>
</div>
<script>
$(function(e){
	
	//列出市级
	$('#pro_list').bind('change',function(e){
		var proval=this.value;
		if(!$("#city_list").is(":hidden")){
				$("#city_list").hide(100);
			}
			if(!$("#county_list").is(":hidden")){
				$("#county_list").hide(100);
			}
			$('#city_list').empty();
			$('#city_list').append('<option value="-1">请选择城市</option>');
			
			$('#county_list').empty();
			$('#county_list').append('<option value="-1">请选择地区</option>');
			
		if(proval != '-1'){
			$.ajax({
				url:host+'/Admin/User/lookaddres',
				type:'post',
				data:{proval:proval},
				dataType:'json',
				beforeSend:function(xmldata){
					$('#load_jz').removeClass();
					$('#load_jz').addClass('icon-spinner icon-spin icon-large');
					$('#load_jz').show();
				},
				success:function(data){
					$('#load_jz').removeClass();
					if(data.code == 1){
						$('#city_list').empty();
						$('#city_list').append('<option value="-1">请选择城市</option>');
						var sumval=data.infor.sum;
						var cont=data.infor.cont;
						for(i=0;i<sumval;i++){
							$('#city_list').append('<option value="'+cont[i].region_id+'">'+cont[i].region_name+'</option>');
						}
						$("#city_list").show(300);
					}
					
				},
				error:function(error){
					$('#load_jz').removeClass();
					$('#load_jz').addClass('icon-remove-sign');
					document.getElementById('load_jz').style.color='#ff0000';
				}
			});
		}
	});
	
	
	//列出县级
	$('#city_list').bind('change',function(e){
		var proval=this.value;
		if(!$("#county_list").is(":hidden")){
				$("#county_list").hide(100);
			}
			
			$('#county_list').empty();
			$('#county_list').append('<option value="-1">请选择地区</option>');
		if(proval != '-1'){
			$.ajax({
				url:host+'/Admin/User/lookaddres',
				type:'post',
				data:{proval:proval},
				dataType:'json',
				beforeSend:function(xmldata){
					$('#load_jz').removeClass();
					$('#load_jz').addClass('icon-spinner icon-spin icon-large');
					$('#load_jz').show();
				},
				success:function(data){
					$('#load_jz').removeClass();
					if(data.code == 1){
						$('#county_list').empty();
						$('#county_list').append('<option value="-1">请选择城市</option>');
						var sumval=data.infor.sum;
						var cont=data.infor.cont;
						for(i=0;i<sumval;i++){
							$('#county_list').append('<option value="'+cont[i].region_id+'">'+cont[i].region_name+'</option>');
						}
						$("#county_list").show(300);
					}
					
				},
				error:function(error){
					$('#load_jz').removeClass();
					$('#load_jz').addClass('icon-remove-sign');
					document.getElementById('load_jz').style.color='#ff0000';
				}
			});
		}
	});
	
	
	
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