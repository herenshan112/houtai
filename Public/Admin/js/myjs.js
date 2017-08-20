var hosthead='http://';
var tmpTag = 'https:' == document.location.protocol ? false : true;

var winWidth = 0; 
var winHeight = 0; 

if(tmpTag){
	hosthead='http://';
}else{
	hosthead='https://';	
}
var host='http://gsh.qcw100.com'
if(window.location.host != null){
			host = hosthead+window.location.host;
		}else{
			host=hosthead+document.domain; 
}

function tiaozhuan(url){
	window.location.herf=url;
}

function findDimensions() //函数：获取尺寸 
{ 
//获取窗口宽度 
if (window.innerWidth) 
winWidth = window.innerWidth; 
else if ((document.body) && (document.body.clientWidth)) 
winWidth = document.body.clientWidth; 
//获取窗口高度 
if (window.innerHeight) 
winHeight = window.innerHeight; 
else if ((document.body) && (document.body.clientHeight)) 
winHeight = document.body.clientHeight; 
//通过深入Document内部对body进行检测，获取窗口大小 
if (document.documentElement && document.documentElement.clientHeight && document.documentElement.clientWidth) 
{ 
winHeight = document.documentElement.clientHeight; 
winWidth = document.documentElement.clientWidth; 
} 
}
//关闭
function close_div(name){
	$('#'+name).hide(200);
	
	if($('#gamenum').val() == ''){
		$('#fahuofang').val(0);
		
		var radio = document.getElementsByName("fhfset");  
	    radio[0].checked=true;
	    $('#jxsfahuo').hide();
	    $('#shippernum').val('');
	    $('#zbfhcl').show(200);
		
		
		$('#jxszhans').html('总部');
		$('#jxszhans').removeClass();
		document.getElementById('jxszhans').style.color='#f00';
	}else{
		var radio = document.getElementsByName("fhfset");  
	    for (i=0; i<radio.length; i++) {  
	        if (radio[i].checked) {  
	            radio[i].value=$('#gamenum').val();
	        }  
	    }
	}
	
	//$('#fhfset').val($('#gamenum').val());
	$('#codegmlist').html('');
	$('#shoplist').html('');
	$('#sousoxg').val('');
	$('#gaetitle').val('');
	$('#gamenum').val('');
}
//特价
function tejiaxy(tjset){
	//alert(tjset);
	if(tjset != 0){
		/*$("#tejia").eq(0).attr("checked","checked");
	    $("#tejia").eq(1).removeAttr("checked");
	    $("#tejia").eq(0).click();*/
	   	switch(tjset){
	   		case 2:
	   			$('#tjbtval').html('促销价格');
	   			break;
	   		case 5:
	   			$('#tjbtval').html('打折价格');
	   			break;
	   		case 6:
	   			$('#tjbtval').html('特价价格');
	   			break;
	   	}
	   	$('#tejiaprice').val('');
	    $('#tejiajg').show(300);
	}else{
		/*$("#tejia").eq(1).attr("checked","checked");
	    $("#tejia").eq(0).removeAttr("checked");
	    $("#tejia").eq(1).click();*/
	   
	    $('#tejiajg').hide(100);
	    $('#tejiaprice').val('');
	}
}
/*
 * 选择商品配件
 */
function chopeijian(name,minc,pageid){
	
	findDimensions();
	$('#shoplist').css('height','380px');
	var gaod=winHeight+'px';
	$('#'+name).css('height',gaod);
	$('#'+name).show(500,function(){
		shopajax(minc,'list',pageid,1);
	});
}

/*
 * 商品ajax显示
 */
function shopajax(name,action,pageid,page)
{
	if(page == ''){
		page=1;
	}
	if(action == ''){
		action='list';
	}
	if(pageid == ''){
		pageid='page_list';
	}
	/*var name=name,
	name='', action='list', pageid='page_list', page=1*/
	var shopslist='';
	
	$.ajax({
		url:host+'/Admin/Products/partsapi',
		type:'post',
		data:{action:action,page:page},
		dataType:'json',
		beforeSend:function(xmldata){
			$('#'+name).html('<div class="load_img"></div>');
		},
		success:function(data){
			if(data.code == 1){
				var infor=data.infor;
				var cont=infor.cont;
				for(i=0;i<infor.sum;i++){
					shopslist+='<li id="shoval'+cont[i].id+'"><b>'+cont[i].id+'</b>';
                    shopslist+='<img src="'+cont[i].titlepic+'" alt="'+cont[i].title+'" />';
                    shopslist+='<a>'+cont[i].title+'<br>';
                    if(cont[i].spec != 0){
                    	var guige=cont[i].spec;
                    }else{
                    	var guige='';
                    }
                    shopslist+='<i>规格：'+guige+'</i>';
                    shopslist+='<i>库存：'+(cont[i].totalnum-cont[i].salenum)+'&nbsp;销量：'+cont[i].salenum+'&nbsp;总量：'+cont[i].totalnum+'</i>';
                    shopslist+='</a><em>';
                    if(cont[i].tejia != 0){
                    	shopslist+='特价￥：'+cont[i].tejiaprice;
                    }else{
                    	shopslist+='￥'+cont[i].price;
                    }
                    shopslist+='</em>';
                    shopslist+='<span onclick=choiceshop('+cont[i].id+')>选择</span></li>';
				}
				$('#'+name).html(shopslist);
				if(infor.page != ''){
					$('#'+pageid).html(infor.page);
				}
				jumpoldsop();
			}else{
				$('#'+name).html(data.msg);
			}
				
		},
		error:function(error_msg){
			$('#'+name).html('网络链接错误！请检查你的网络！');
		}
	});
}
/*
 * 判断是否已经存在选中的
 */
function jumpoldsop(){
	var sopidval=$('#parts').val();
	if(sopidval != '' && sopidval != 0){
		var strs= new Array(); 
		strs=sopidval.split(","); 
		for (i=0;i<strs.length ;i++ ){
			xzspbj('shoval',strs[i]);
			choiceshop(strs[i]);
		}
	}
}
/*
 * 选择商品
 */
function choiceshop(id){
	var ylid='#xzshop'+id;
	var sfczdiv=$(ylid).length
	var shopst='';
	
	$xzspls=$('#codegmlist').html();
	$.ajax({
		url:host+'/Admin/Products/partsapi',
		type:'POST',
		data:{action:'oneshop',id:id},
		dataType:'json',
		beforeSend:function(xmldata){
			
			if(sfczdiv > 0){
				//$(ylid).html(Loading......);
			}else{
				$('#codegmlist').html($xzspls+'<li id="xzshop'+id+'">Loading......</li>');
			}
			/*alert(sfczdiv);
			$('#codegmlist').html($xzspls+'<li id="xzshop'+id+'">Loading......</li>');*/
			
		},
		success:function(data){
			if(data.code == 1){
				xzspbj('shoval',id);
				var cont=data.infor.cont;
				shopst+='<b>'+cont.id+'</b>';
                shopst+='<img src="'+cont.titlepic+'" alt="'+cont.title+'" />';
                shopst+='<a>'+cont.title+'<br>';
                if(cont.spec != '0'){
	                    	var guige=cont.spec;
	            }else{
	                    	var guige='';
	            }
                shopst+='<i>规格：'+guige+'</i>';
                shopst+='<i>库存：'+(cont.totalnum-cont.salenum)+'&nbsp;销量：'+cont.salenum+'&nbsp;总量：'+cont.totalnum+'</i>';
                shopst+='</a><em>';
                   	if(cont.tejia != 0){
                    	shopst+='特价￥：'+cont.tejiaprice;
                    }else{
                    	shopst+='￥'+cont.price;
                    }
                shopst+='</em>';
                shopst+='<span onclick=delcshop('+cont.id+',"'+cont.title+'")>删除</span>';
                $(ylid).html(shopst);
                
                var dqshop=$('#gamenum').val();
                if(dqshop != ''){
                	if(isContains(dqshop,id) == 1){
	                	$('#gamenum').val(dqshop+','+id);
	                }
                }else{
                	$('#gamenum').val(id);
                }
                
                var dqshopnam=$('#gaetitle').val();
                if(dqshopnam != ''){
                	if(isContains(dqshopnam,cont.title) == 1){
	                	$('#gaetitle').val(dqshopnam+','+cont.title);
	                }
                }else{
                	$('#gaetitle').val(cont.title);
                }
                
                
			}
		},
		error:function(){
			$('#xzshop'+id).html('网络链接错误！请检查你的网络！');
		}
	});
}
/*
 * 删除已选中商品
 */
function delcshop(id,shopnam){
	if(confirm('您确定要取消该配件商品吗？')){
		//去除商品列表中的选中状态
		qxxzspbj('shoval',id);
		//删除商品ID操作
		str=$('#gamenum').val();
		Deletestr=id+",";
		str=str.replace(Deletestr,"");
		Deletestr=","+id;
		str=str.replace(Deletestr,"");
		str=str.replace(id,"");	
		$('#gamenum').val(str);
		//删除商品名称操作
		strnam=$('#gaetitle').val();
		Delestrsp=shopnam+",";
		strnam=strnam.replace(Delestrsp,"");
		Delestrsp=","+shopnam;
		strnam=strnam.replace(Delestrsp,"");
		strnam=strnam.replace(shopnam,"");	
		$('#gaetitle').val(strnam);
		
		
		
		strxdz=$('#parts').val();
		Dexdztr=id+",";
		strxdz=strxdz.replace(Dexdztr,"");
		Dexdztr=","+id;
		strxdz=strxdz.replace(Dexdztr,"");
		strxdz=strxdz.replace(id,"");	
		$('#parts').val(strxdz);
		//删除已选择商品列表内容
		$('#spxd'+id).remove();
		
		//删除已选择商品列表内容
		$('#xzshop'+id).remove();
	}
}
/*
 * 判断是否存在
 */
function isContains(str, substr) {
    var strs= new Array(); //定义一数组 
	strs=str.split(","); //字符分割 
	for (i=0;i<strs.length ;i++ ){
		if(strs[i] == substr){
			return 0;
		}
	}
	return 1;
}
/*
 * 选择商品标记
 */
function xzspbj(name,id){
	var xlid='#'+name+id;
	$(xlid).addClass('hover');
}
/*
 * 取消选择商品标记
 */
function qxxzspbj(name,id){
	var xlid='#'+name+id;
	$(xlid).removeClass('hover');
}

/*
 * 全部取消
 */
function allqxshop(){
	str=$('#gamenum').val();
	var strs= new Array(); //定义一数组 
	strs=str.split(","); //字符分割 
	for (i=0;i<strs.length ;i++ ){
		$('#xzshop'+strs[i]).remove();
		qxxzspbj('shoval',strs[i]);
	}
	$('#gamenum').val('');
	$('#gaetitle').val('');
}
/*
 * 选定商品
 */
function choshopval(){
	var addshoplt='';
	//商品id
	str=$('#gamenum').val();
	
		var strs= new Array(); 
		strs=str.split(","); 
		//商品名称
		strtile=$('#gaetitle').val();
		var strstile= new Array(); 
		strstile=strtile.split(","); 
		
		if(str != ''){
			$('#parts').val(str);
			for (i=0;i<strs.length ;i++ ){
				addshoplt+='<i id="spxd'+strs[i]+'">'+strstile[i]+'<a class="fform_li_a icon-remove-circle" onclick=deltqdval('+strs[i]+')>&nbsp;</a></i>';
			}
		}else{
			$('#parts').val();
		}
		
		$('#shopxzlst').html(addshoplt);
		
	
	close_div('secodiv');
}
/*
 * 删除已经选中的商品
 */
function deltqdval(id){
	if(confirm('您确定要取消该配件商品吗？')){
		//删除商品ID操作
		str=$('#parts').val();
		Deletestr=id+",";
		str=str.replace(Deletestr,"");
		Deletestr=","+id;
		str=str.replace(Deletestr,"");
		str=str.replace(id,"");	
		$('#parts').val(str);
		//删除已选择商品列表内容
		$('#spxd'+id).remove();
	}
}

//商品ajax翻页
function pageshopajax(page,action,ssval){
	if(page == ''){
		page=1;
	}
	if(action == ''){
		action='list';
	}
	/*page=1,action='list',ssval=''*/
	if(action == 'sousuo' && ssval == ''){
		action='list';
		//return;
	}
	var shopslist='';
	$.ajax({
		type:"POST",
		url:host+"/Admin/Products/partsapi",
		data:{action:action,p:page,ssval:ssval},
		dataType:'json',
		beforeSend:function(xmldata){
			$('#shoplist').html('<div class="load_img"></div>');
		},
		success:function(data){
			if(data.code == 1){
				var infor=data.infor;
				var cont=infor.cont;
				for(i=0;i<infor.sum;i++){
					shopslist+='<li id="shoval'+cont[i].id+'"><b>'+cont[i].id+'</b>';
                    shopslist+='<img src="'+cont[i].titlepic+'" alt="'+cont[i].title+'" />';
                    shopslist+='<a>'+cont[i].title+'<br>';
                    if(cont[i].spec != 0){
                    	var guige=cont[i].spec;
                    }else{
                    	var guige='';
                    }
                    shopslist+='<i>规格：'+guige+'</i>';
                    shopslist+='<i>库存：'+(cont[i].totalnum-cont[i].salenum)+'&nbsp;销量：'+cont[i].salenum+'&nbsp;总量：'+cont[i].totalnum+'</i>';
                    shopslist+='</a><em>';
                    if(cont[i].tejia != 0){
                    	shopslist+='特价￥：'+cont[i].tejiaprice;
                    }else{
                    	shopslist+='￥'+cont[i].price;
                    }
                    shopslist+='</em>';
                    shopslist+='<span onclick=choiceshop('+cont[i].id+')>选择</span></li>';
				}
				$('#shoplist').html(shopslist);
				if(infor.page != ''){
					$('#page_list').html(infor.page);
				}
				jumpoldsop();
			}else{
				$('#shoplist').html(data.msg);
			}
		},
		error:function(errordata){
			$('#shoplist').html('网络链接错误！请检查你的网络！');
			$('#page_list').html('');
		}
	});
}
/*
 * 商品搜索ajax
 */
function shopsousuo(){
	var shopslist='';
	var sousoxg=$('#sousoxg').val();
	var sousuotype=$('#sousuotype').val();
	if(sousoxg != ''){
		$.ajax({
			type:"post",
			url:host+"/Admin/Products/partsapi",
			data:{action:'sousuo',p:1,ssval:sousoxg,type:sousuotype},
			dataType:'json',
			beforeSend:function(xmldata){
				$('#shoplist').html('<div class="load_img"></div>');
			},
			success:function(data){
				if(data.code == 1){
					var infor=data.infor;
					var cont=infor.cont;
					for(i=0;i<infor.sum;i++){
						shopslist+='<li id="shoval'+cont[i].id+'"><b>'+cont[i].id+'</b>';
	                    shopslist+='<img src="'+cont[i].titlepic+'" alt="'+cont[i].title+'" />';
	                    shopslist+='<a>'+cont[i].title+'<br>';
	                    if(cont[i].spec != 0){
	                    	var guige=cont[i].spec;
	                    }else{
	                    	var guige='';
	                    }
	                    shopslist+='<i>规格：'+guige+'</i>';
	                    shopslist+='<i>库存：'+(cont[i].totalnum-cont[i].salenum)+'&nbsp;销量：'+cont[i].salenum+'&nbsp;总量：'+cont[i].totalnum+'</i>';
	                    shopslist+='</a><em>';
	                    if(cont[i].tejia != 0){
	                    	shopslist+='特价￥：'+cont[i].tejiaprice;
	                    }else{
	                    	shopslist+='￥'+cont[i].price;
	                    }
	                    shopslist+='</em>';
	                    shopslist+='<span onclick=choiceshop('+cont[i].id+')>选择</span></li>';
					}
					$('#shoplist').html(shopslist);
					if(infor.page != ''){
						$('#page_list').html(infor.page);
					}
					jumpoldsop();
				}else{
					$('#shoplist').html(data.msg);
				}
			},
			error:function(){
				$('#shoplist').html('网络链接错误！请检查你的网络！');
				$('#page_list').html('');
			}
		});
	}else{
		alert('请输入要查询商品的名称');
	}
}
/*
 * 打开色彩选择器
 */
function show_colselect(name){
	if($("#"+name).is(":hidden")){
        $("#"+name).show(300);    //如果元素为隐藏,则将它显现
	}else{
	    $("#"+name).hide(100);     //如果元素为显现,则将其隐藏
	}
}
/*
 * 选定色彩
 */
function xzsecl(id){
	var colval=document.getElementById('solor'+id).dataset.color;
	show_colselect('selecolr');
	$('#colrval').html(colval);
	$('#coloval').val(colval);
	//$('#colrval').css(background:colval);
	document.getElementById('setcolork').style.backgroundColor=colval;
	document.getElementById('colrval').style.color=rgb2hex($('#solor'+id).css('color'));
	
}
function rgb2hex(rgb) {
	rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
	function hex(x) {
		return ("0" + parseInt(x).toString(16)).slice(-2);
	}
	return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}


//---------手机号合法性检查

function mysjh_jc(id_name){
	//var sjhid=/^(\d{3,4}\-?)?\d{7,8}$/;
	var sjhid=/^1[3|4|5|8][0-9]\d{4,8}$/;
	var sjhclje=sjhid.test(id_name);
	if(!sjhclje){
		return 0;
	}
	else{//合法
		return 1;
	}
}
//---------固定电话号合法性检查
function mygddh_jc(id_name){
	var gdid=/^((0(10|2[1-3]|[3-9]\d{2}))?[1-9]\d{6,7})$/;
	var gdclje=gdid.test(id_name);
	if(!gdclje){
		pamyif2=0;
	}
	else{//合法
		pamyif2=1;
	}
}

var aduset=[0,0,0,0,0,0];
//检测用户名
function jumpuser(){
	var mobset=0;
	var usval=$('#usernamers').val();
	if(usval == ''){
		$('#load_user').html('请输入用户名！');
		$('#load_user').removeClass();
		$('#load_user').addClass('icon-remove-sign');
		document.getElementById('load_user').style.color='#ff0000';
		$('#load_user').show();
		aduset[0]=0;
		return;
	}
	if(usval.length < 6){
		$('#load_user').html('请输入6-16个字符的用户名！');
		$('#load_user').removeClass();
		$('#load_user').addClass('icon-remove-sign');
		document.getElementById('load_user').style.color='#ff0000';
		$('#load_user').show();
		aduset[0]=0;
		return;
	}
	if(mysjh_jc(usval) == 1){
		mobset=1;
	}
	$.ajax({
		type:"post",
		url:host+"/Admin/User/addhuiyuan",
		async:true,
		data:{action:'lookset',mobset:mobset,usval:usval},
		dataType:'json',
		beforeSend:function(){
			$('#load_user').removeClass();
			$('#load_user').addClass('icon-spinner icon-spin icon-large');
			$('#load_user').show();
		},
		success:function(data){
			if(data.code == 1){
				$('#load_user').html('');
				$('#load_user').removeClass();
				$('#load_user').addClass('icon-ok-circle icon-2x');
				document.getElementById('load_user').style.color='#73bf00';
				aduset[0]=1;
			}else{
				$('#load_user').removeClass();
				$('#load_user').addClass('icon-remove-sign');
				document.getElementById('load_user').style.color='#ff0000';
				$('#load_user').html(data.msg);
				aduset[0]=0;
			}
		},
		error:function(){
			aduset[0]=0;
			$('#load_user').html('网络链接错误！');
			$('#load_user').removeClass();
			$('#load_user').addClass('icon-remove-sign');
			document.getElementById('load_user').style.color='#ff0000';
			$('#load_user').show();
			
		}
	});
}

//检测密码
function jumpmia(){
	var usval=$('#password').val();
	var reChinese=/[\u0391-\uFFE5]+/;
	var reSpace=/\s+/;
	var b_chinese=reChinese.test(usval);
	var b_space=reSpace.test(usval);
	if(usval == ''){
		$('#sky_1').html('请输入密码！');
		$('#sky_1').removeClass();
		$('#sky_1').addClass('icon-remove-sign');
		document.getElementById('sky_1').style.color='#ff0000';
		$('#sky_1').show();
		aduset[1]=0;
		return;
	}
	if(b_chinese){
		$('#sky_1').html('密码中不能包含中文！');
		$('#sky_1').removeClass();
		$('#sky_1').addClass('icon-remove-sign');
		document.getElementById('sky_1').style.color='#ff0000';
		$('#sky_1').show();
		aduset[1]=0;
		return;
	}
	if(b_space){
		$('#sky_1').html('密码不能包含空格！');
		$('#sky_1').removeClass();
		$('#sky_1').addClass('icon-remove-sign');
		document.getElementById('sky_1').style.color='#ff0000';
		$('#sky_1').show();
		aduset[1]=0;
		return;
	}
	if(usval.length < 6){
		$('#sky_1').html('请输入6-16个字符的密码组合！');
		$('#sky_1').removeClass();
		$('#sky_1').addClass('icon-remove-sign');
		document.getElementById('sky_1').style.color='#ff0000';
		$('#sky_1').show();
		aduset[1]=0;
		return;
	}
	var aqx=getResult(usval);
	switch(aqx){
		case 0:
			$('#sky_1').html('密码安全性低');
			$('#sky_1').removeClass();
			$('#sky_1').addClass('dijing');
			document.getElementById('sky_1').style.color='#000000';
			$('#sky_1').show();
			break;
		case 1:
			$('#sky_1').html('密码安全性中');
			$('#sky_1').removeClass();
			$('#sky_1').addClass('zhjing');
			document.getElementById('sky_1').style.color='#000000';
			$('#sky_1').show();
			break;
		case 2:
			$('#sky_1').html('密码安全性高');
			$('#sky_1').removeClass();
			$('#sky_1').addClass('hajing');
			document.getElementById('sky_1').style.color='#000000';
			$('#sky_1').show();
			break;
		default:
			$('#sky_1').html('密码安全性低');
			$('#sky_1').removeClass();
			$('#sky_1').addClass('dijing');
			document.getElementById('sky_1').style.color='#000000';
			$('#sky_1').show();
			break;
	}
	aduset[1]=1;
	if($('#password2').val() != ''){
		aduset[2]=0;
		$('#password2').val('');
	}else{
		aduset[2]=0;
	}
	
}
//检测确认密码
function jumpqrmm(){
	var qrmm=$('#password2').val();
	if(qrmm == ''){
		$('#sky_2').html('请输入确认密码！');
		$('#sky_2').removeClass();
		$('#sky_2').addClass('icon-remove-sign');
		document.getElementById('sky_2').style.color='#ff0000';
		$('#sky_2').show();
		aduset[2]=0;
		return;
	}
	if(qrmm != $('#password').val()){
		$('#sky_2').html('你两次输入的密码不一致！');
		$('#sky_2').removeClass();
		$('#sky_2').addClass('icon-remove-sign');
		document.getElementById('sky_2').style.color='#ff0000';
		$('#sky_2').show();
		aduset[2]=0;
		return;
	}
	aduset[2]=1;
	$('#sky_2').html('');
	$('#sky_2').removeClass();
	$('#sky_2').addClass('icon-ok-circle icon-2x');
	document.getElementById('sky_2').style.color='#73bf00';
	$('#sky_2').show();
}
//定义检测函数,返回0/1/2分别代表差/一般/强
function getResult(s){
	var ls =-1;
	if (s.match(/[a-z]/ig)){
		ls++;
	}
	if (s.match(/[0-9]/ig)){
		ls++;
	}
	if (s.match(/(.[^a-z0-9])/ig)){
		ls++;
	}
	return ls;
}
//判断邮箱
function jumpemail(){
	var qrmm=$('#email').val();
	if(qrmm == ''){
		$('#sky_4').html('请输入邮箱！');
		$('#sky_4').removeClass();
		$('#sky_4').addClass('icon-remove-sign');
		document.getElementById('sky_4').style.color='#ff0000';
		$('#sky_4').show();
		aduset[3]=0;
		return;
	}
	if(check_email(qrmm) != 1){
		$('#sky_4').html('您输入的邮箱不正确！');
		$('#sky_4').removeClass();
		$('#sky_4').addClass('icon-remove-sign');
		document.getElementById('sky_4').style.color='#ff0000';
		$('#sky_4').show();
		aduset[3]=0;
		return;
	}
	aduset[3]=1;
	$('#sky_4').html('');
	$('#sky_4').removeClass();
	$('#sky_4').addClass('icon-ok-circle icon-2x');
	document.getElementById('sky_4').style.color='#73bf00';
	$('#sky_4').show();
}
//----------邮箱检测
function check_email(email){
	var reEmail=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	var b_email=reEmail.test(email);
	if(b_email){
		return 1;
	}
	else{
		return 0;
	}
}

//判断手机是否正确
function jumpphon(){
	var qrmm=$('#phone').val();
	if(qrmm == ''){
		$('#sky_5').html('请输入手机号码！');
		$('#sky_5').removeClass();
		$('#sky_5').addClass('icon-remove-sign');
		document.getElementById('sky_5').style.color='#ff0000';
		$('#sky_5').show();
		aduset[4]=0;
		return;
	}
	if(mysjh_jc(qrmm) != 1){
		$('#sky_5').html('您输入的手机号码不正确！');
		$('#sky_5').removeClass();
		$('#sky_5').addClass('icon-remove-sign');
		document.getElementById('sky_5').style.color='#ff0000';
		$('#sky_5').show();
		aduset[4]=0;
		return;
	}
	$.ajax({
		url:host+'/Admin/User/addhuiyuan',
		type:'post',
		data:{action:'looksetpho',qrmm:qrmm},
		dataType:'json',
		async:true,
		beforeSend:function(){
			$('#sky_5').html('');
			$('#sky_5').removeClass();
			$('#sky_5').addClass('icon-spinner icon-spin icon-large');
			$('#sky_5').show();
		},
		success:function(data){
			if(data.code == 1){
				$('#sky_5').html('');
				$('#sky_5').removeClass();
				$('#sky_5').addClass('icon-ok-circle icon-2x');
				document.getElementById('sky_5').style.color='#73bf00';
				aduset[4]=1;
			}else{
				$('#sky_5').removeClass();
				$('#sky_5').addClass('icon-remove-sign');
				document.getElementById('sky_5').style.color='#ff0000';
				$('#sky_5').html(data.msg);
				aduset[4]=0;
			}
		},
		error:function(){
			aduset[4]=0;
			$('#sky_5').html('网络链接错误！');
			$('#sky_5').removeClass();
			$('#sky_5').addClass('icon-remove-sign');
			document.getElementById('sky_5').style.color='#ff0000';
			$('#sky_5').show();
		}
	});
	/*aduset[4]=1;
	$('#sky_5').html('');
	$('#sky_5').removeClass();
	$('#sky_5').addClass('icon-ok-circle icon-2x');
	document.getElementById('sky_5').style.color='#73bf00';
	$('#sky_5').show();*/
}
//提交会员按钮
function tianjiaqr(){
	if(aduset[0] == 1 && aduset[1] == 1 && aduset[2] == 1 && aduset[3] == 1 && aduset[4] == 1){
		$('#myform').submit();
	}else{
		if(aduset[0] != 1){
			jumpuser();
		}
		if(aduset[1] != 1){
			jumpmia();
		}
		if(aduset[2] != 1){
			jumpqrmm();
		}
		if(aduset[3] != 1){
			jumpemail();
		}
		if(aduset[4] != 1){
			jumpphon();
		}
	}
	
}

//经销商设置
var jxsset=[0,0,0,0];
//判断公司名称
function jumpgsmc(){
	var poratename=$('#poratename').val();
	if(poratename == ''){
		$('#sky_12').html('请输入公司名称！');
		$('#sky_12').removeClass();
		$('#sky_12').addClass('icon-remove-sign');
		document.getElementById('sky_12').style.color='#ff0000';
		$('#sky_12').show();
		jxsset[0]=0;
		return;
	}
	jxsset[0]=1;
	$('#sky_12').html('');
	$('#sky_12').removeClass();
	$('#sky_12').addClass('icon-ok-circle icon-2x');
	document.getElementById('sky_12').style.color='#73bf00';
	$('#sky_12').show();
}
//判断公司地址
function jumpgsdizi(){
	var porateaddress=$('#porateaddress').val();
	if(porateaddress == ''){
		$('#sky_10').html('请输入公司地址！');
		$('#sky_10').removeClass();
		$('#sky_10').addClass('icon-remove-sign');
		document.getElementById('sky_10').style.color='#ff0000';
		$('#sky_10').show();
		jxsset[1]=0;
		return;
	}
	jxsset[1]=1;
	$('#sky_10').html('');
	$('#sky_10').removeClass();
	$('#sky_10').addClass('icon-ok-circle icon-2x');
	document.getElementById('sky_10').style.color='#73bf00';
	$('#sky_10').show();
}
//判断公司营业执照
function jumpgsiicp(){
	var titlepicyyzz=$('#titlepicyyzz').val();
	if(titlepicyyzz == ''){
		$('#sky_11').html('请上传公司营业执照！');
		$('#sky_11').removeClass();
		$('#sky_11').addClass('icon-remove-sign');
		document.getElementById('sky_11').style.color='#ff0000';
		$('#sky_11').show();
		jxsset[2]=0;
		return;
	}
	jxsset[2]=1;
	$('#sky_11').html('');
	$('#sky_11').removeClass();
	$('#sky_11').addClass('icon-ok-circle icon-2x');
	document.getElementById('sky_11').style.color='#73bf00';
	$('#sky_11').show();
}
//提交经销商
function tianjiaqrjxs(){
	if(aduset[0] == 1 && aduset[1] == 1 && aduset[2] == 1 && aduset[3] == 1 && aduset[4] == 1 && jxsset[0] == 1 && jxsset[1] == 1 && jxsset[2] == 1 && jxsset[3] == 1){
		$('#myform').submit();
	}else{
		if(aduset[0] != 1){
			jumpuser();
		}
		if(aduset[1] != 1){
			jumpmia();
		}
		if(aduset[2] != 1){
			jumpqrmm();
		}
		if(aduset[3] != 1){
			jumpemail();
		}
		if(aduset[4] != 1){
			jumpphon();
		}
		
		if(jxsset[0] != 1){
			jumpgsmc();
		}
		if(jxsset[1] != 1){
			jumpgsdizi();
		}
		if(jxsset[2] != 1){
			jumpgsiicp();
		}
		if(jxsset[3] != 1){
			$("#pro_list").change();
			$('#load_jz').html('请选择销售区域！');
			$('#load_jz').removeClass();
			$('#load_jz').addClass('icon-remove-sign');
			document.getElementById('load_jz').style.color='#ff0000';
			$('#load_jz').show();
		}
		
	}
}


function clfhfs(id,fhfset){
	
	if(fhfset == 0){
		
		$('#jxsfahuo').hide();
		//$('#jxszhans').html('');
		//$('#fahuofang').val(0);
		$('#zbfhcl').show();
		$('#shippernum').val();
		return;
	}
	
	if(id != 0){
		genggaijxs($('#fahuofang').val());
		$('#jxsfahuo').show();
		$('#zbfhcl').hide();
		$('#shippernum').val();
		return;
	}
	genggaijxs($('#fahuofang').val());
	$('#jxsfahuo').show();
	$('#zbfhcl').hide();
		
		
	
	
}

//更改经销商
function genggaijxs(usid){
	findDimensions();
	if(usid == 0){
		usid=$('#fahuofang').val();
	
	
	var gaod=winHeight+'px';
	$('#secodiv').css('height',gaod);
	$('#secodiv').show(500,function(){
		lookusjxs('list',usid);
	});
	}
}
//显示经销商列表
function lookusjxs(action,usid){
	if(action == ''){
		action='list';
	}
	/*action='list',usid*/
	var shopslist='';
	$.ajax({
		url:host+'/Admin/Orders/usqdhyjxs',
		type:'post',
		data:{action:action},
		dataType:'json',
		beforeSend:function(){
			$('#shoplist').html('<div class="load_imgs"></div>');
		},
		success:function(data){
			if(data.code == 1){
				var infor=data.infor;
				var cont=infor.cont;
				for(i=0;i<infor.sum;i++){
					shopslist+='<li id="shoval'+cont[i].id+'"><b>'+cont[i].id+'</b>';
                    shopslist+='<img src="'+cont[i].headpic+'" alt="'+cont[i].username+'" />';
                    shopslist+='<a>帐号：'+cont[i].username+'<br>';
                    
                    shopslist+='<i>姓名：'+cont[i].nickname+'</i>';
                    shopslist+='<i>销售区域：'+cont[i].xsqu+'</i>';
                    shopslist+='</a><em>';
                    shopslist+='电话：'+cont[i].phone+'</em>';
                    shopslist+='<span onclick=xzqudao('+cont[i].id+')>选择</span></li>';
				}
				$('#shoplist').html(shopslist);
				if(infor.page != ''){
					$('#page_list').html(infor.page);
				}
				if(usid != 0){
					xzqudao(usid);
				}
				//jumpoldsop();
			}else{
				$('#shoplist').html(data.msg);
			}
		},
		error:function(){
			$('#shoplist').html('网络链接错误！请检查你的网络！');
		}
	});
	
}

/*
 * 选择渠道
 */
function xzqudao(id){
	var ylid='#xzshop'+id;
	var sfczdiv=$(ylid).length
	var shopst='';
	
	$xzspls='';
	$.ajax({
		url:host+'/Admin/Orders/usqdhyjxs',
		type:'POST',
		data:{action:'oneshop',id:id},
		dataType:'json',
		beforeSend:function(xmldata){
			
			if(sfczdiv > 0){
				//$(ylid).html(Loading......);
			}else{
				$('#codegmlist').html($xzspls+'<li id="xzshop'+id+'">Loading......</li>');
			}
			/*alert(sfczdiv);
			$('#codegmlist').html($xzspls+'<li id="xzshop'+id+'">Loading......</li>');*/
			
		},
		success:function(data){
			if(data.code == 1){
				//xzspbj('shoval',id);
				var cont=data.infor.cont;
				shopst+='<b>'+cont.id+'</b>';
                shopst+='<img src="'+cont.headpic+'" alt="'+cont.username+'" />';
                shopst+='<a>帐号：'+cont.username+'<br>';
                shopst+='<i>姓名：'+cont.nickname+'</i>';
                shopst+='<i>销售区域：'+cont.xsqu+'</i>';
                shopst+='</a><em>';
                
                shopst+='电话：'+cont.phone+'</em>';
                shopst+='<span onclick=delcjsxus('+cont.id+',"'+cont.username+'")>删除</span>';
                $(ylid).html(shopst);
                
                var dqshop=$('#gamenum').val();
                if(dqshop != ''){
                	if(isContains(dqshop,id) == 1){
	                	$('#gamenum').val(id);
	                }
                }else{
                	$('#gamenum').val(id);
                }
                
                var dqshopnam=$('#gaetitle').val();
                if(dqshopnam != ''){
                	if(isContains(dqshopnam,cont.title) == 1){
	                	$('#gaetitle').val(dqshopnam+','+cont.title);
	                }
                }else{
                	$('#gaetitle').val(cont.title);
                }
                
                
			}
		},
		error:function(){
			$('#xzshop'+id).html('网络链接错误！请检查你的网络！');
		}
	});
}

//经销商翻页
function pageusajax(page,ssval,action,sstype,provinces,city,county){
	
	if(page == ''){
		 page=1;
	}
	
	if(action == ''){
		 action='list';
	}
	
	if(sstype == ''){
		 action=1;
	}
	
	if(provinces == ''){
		 provinces=-1;
	}
	if(city == ''){
		 city=-1;
	}
	if(county == ''){
		 county=-1;
	}
	/*page=1,ssval='',action='list',sstype=1,provinces=-1,city=-1,county=-1*/
	
	if((action == 'sousuo' && ssval == '')&&(action == 'sousuo' && provinces == -1)){
		action='list';
		//return;
	}
	
	var shopslist='';
	$.ajax({
		type:"post",
		url:host+'/Admin/Orders/usqdhyjxs',
		async:true,
		data:{action:action,p:page,sstype:sstype,provinces:provinces,city:city,county:county,ssval:ssval},
		dataType:'json',
		beforeSend:function(xmldata){
			$('#shoplist').html('<div class="load_img"></div>');
		},
		success:function(data){
			if(data.code == 1){
				var infor=data.infor;
				var cont=infor.cont;
				for(i=0;i<infor.sum;i++){
					shopslist+='<li id="shoval'+cont[i].id+'"><b>'+cont[i].id+'</b>';
                    shopslist+='<img src="'+cont[i].headpic+'" alt="'+cont[i].username+'" />';
                    shopslist+='<a>帐号：'+cont[i].username+'<br>';
                    
                    shopslist+='<i>姓名：'+cont[i].nickname+'</i>';
                    shopslist+='<i>销售区域：'+cont[i].xsqu+'</i>';
                    shopslist+='</a><em>';
                    shopslist+='电话：'+cont[i].phone+'</em>';
                    shopslist+='<span onclick=xzqudao('+cont[i].id+')>选择</span></li>';
				}
				$('#shoplist').html(shopslist);
				if(infor.page != ''){
					$('#page_list').html(infor.page);
				}
				//jumpoldsop();
			}else{
				$('#shoplist').html(data.msg);
			}
		},
		error:function(){
			$('#shoplist').html('网络链接错误！请检查你的网络！');
		}
	});
}

//搜索经销商
function lookqudaokl(){
	var sousoxg=$('#sousoxg').val();
	var sousuotype=$('#sousuotype').val();
	
	var pro_list=$('#pro_list').val();
	var city_list=$('#city_list').val();
	var county_list=$('#county_list').val();
	if(sousoxg == '' && pro_list == -1){
		alert('渠道信息和销售区域至少提供一个！谢谢');
		return;
	}
	pageusajax(1,sousoxg,'sousuo',sousuotype,pro_list,city_list,county_list);
}


/*
 * 选定经销商
 */
function chousjxsval(){
	var addshoplt='';
	//商品id
	str=$('#gamenum').val();
	if(str != ''){
		var strs= new Array(); 
		strs=str.split(","); 
		//商品名称
		strtile=$('#gaetitle').val();
		var strstile= new Array(); 
		strstile=strtile.split(","); 
		
		if(str != ''){
			$('#fahuofang').val(str);
			for (i=0;i<strs.length ;i++ ){
				
				xdjxscllc(strs[i]);
				var radio = document.getElementsByName("fhfset");  
			    for (i=0; i<radio.length; i++) {  
			        if (radio[i].checked) {
			        	
			        		radio[i].value=strs[i];
			        
			        		radio[i].value=0;
			        	
			            
			        }  
			    }
					//addshoplt+='<i id="spxd'+strs[i]+'">'+strstile[i]+'<a class="fform_li_a icon-remove-circle" onclick=deltqdval('+strs[i]+')>&nbsp;</a></i>';
			}
		}else{
			$('#fahuofang').val(0);
		}
		
		//$('#jxszhans').html(addshoplt);
	}else{
		$('#fahuofang').val(0);
		var radio = document.getElementsByName("fhfset");  
	    radio[0].checked=true;
	    $('#jxsfahuo').hide();
	    $('#shippernum').val('');
	    $('#zbfhcl').show(200);
		$('#jxszhans').html('总部');
		$('#jxszhans').removeClass();
		document.getElementById('jxszhans').style.color='#f00';
	}
	
	close_div('secodiv');
}

function xdjxscllc(uid){
	$.ajax({
		type:"post",
		url:host+'/Admin/Orders/usqdhyjxs',
		async:true,
		data:{action:'xdus',id:uid},
		dataType:'json',
		beforeSend:function(){
			$('#jxszhans').html('');
			$('#jxszhans').removeClass();
			$('#jxszhans').addClass('icon-spinner icon-spin icon-large');
			document.getElementById('jxszhans').style.color='#666';
		},
		success:function(data){
			//alert(data.msg);
			if(data.code == 1){
				$('#jxszhans').html(data.msg);
				$('#jxszhans').removeClass();
				document.getElementById('jxszhans').style.color='#666';
			}else{
				$('#jxszhans').html(data.msg);
				$('#jxszhans').removeClass();
				document.getElementById('jxszhans').style.color='#ff0000';
			}
		},
		error:function(){
			$('#jxszhans').html('网络链接错误！');
			$('#jxszhans').removeClass();
			$('#jxszhans').addClass('icon-remove-sign');
			document.getElementById('jxszhans').style.color='#ff0000';
		}
	});
}

//设定商品修改
function jxstzjsan(id){
	
	var aizt=$('#tjjxsxaja'+id).data('type');
	if(aizt == 1){
		$('#jxsjszsxg'+id).attr('contenteditable',true);
		$('#jxsjszsxg'+id).addClass('wbkgasr');
		$('#tjjxsxaja'+id).data('type',2);
		$('#tjjxsxaja'+id).attr('data-type',2);
		$('#tjjxsxaja'+id).val('提交');
	}else{
		
		var jxsjg=$('#jxsjszsxg'+id).html();
		
		if(jxsjg != ''){
			$.ajax({
				type:"post",
				url:host+'/Admin/Orders/usqdhyjxs',
				async:true,
				data:{action:'eitbysop',byid:id,bymony:jxsjg},
				dataType:'json',
				beforeSend:function(){
					$('#jxsjszsxg'+id).html('');
					$('#jxsjszsxg'+id).removeClass('wbkgasr');
					$('#jxsjszsxg'+id).attr('contenteditable',false);
					$('#jxsjszsxg'+id).addClass('icon-spinner icon-spin icon-large');
					document.getElementById('jxsjszsxg'+id).style.color='#666';
				},
				success:function(data){
					if(data.code == 1){
						$('#jxsjszsxg'+id).html(jxsjg);
						$('#jxsjszsxg'+id).removeClass('wbkgasr');
						$('#jxsjszsxg'+id).removeClass('icon-spinner icon-spin icon-large');
						$('#jxsjszsxg'+id).attr('contenteditable',false);
						document.getElementById('jxsjszsxg'+id).style.color='#FF9600';
						
					}else{
						$('#jxsjszsxg'+id).html(data.msg);
						$('#jxsjszsxg'+id).removeClass('wbkgasr');
						$('#jxsjszsxg'+id).removeClass('icon-spinner icon-spin icon-large');
						$('#jxsjszsxg'+id).attr('contenteditable',false);
						document.getElementById('jxsjszsxg'+id).style.color='#ff0000';
					}
					$('#tjjxsxaja'+id).data('type',1);
					$('#tjjxsxaja'+id).attr('data-type',1);
					$('#tjjxsxaja'+id).val('修改');
				},
				error:function(){
					$('#jxsjszsxg'+id).html('网络链接错误！');
					$('#jxsjszsxg'+id).removeClass();
					$('#jxsjszsxg'+id).addClass('icon-remove-sign');
					document.getElementById('jxsjszsxg'+id).style.color='#ff0000';
				}
			});
		}else{
			$('#jxsjszsxg'+id).removeClass('wbkgasr');
			$('#jxsjszsxg'+id).attr('contenteditable',false);
			$('#tjjxsxaja'+id).data('type',1);
			$('#tjjxsxaja'+id).attr('data-type',1);
			$('#tjjxsxaja'+id).val('修改');
		}
		
		
	}
}

//关闭经销商调价
function close_jxstj(name){
	$('#'+name).hide(200);
	$('#jsxtjlistc').html('');
	var ordnum=$('#ddnumbh').val();
	var kylb='';
	$.ajax({
		type:'post',
		url:host+'/Admin/Orders/usqdhyjxs',
		data:{action:'shoplist',ordnum:ordnum},
		dataType:'json',
		beforeSend:function(){
			$('#shanpxxlb').html('<div class="load_img"></div>');
		},
		success:function(data){
			if(data.code==1){
				var infor=data.infor;
				var cont=infor.cont;
						
				for(i=0;i<infor.sum;i++){
					kylb+='<label>商品:</label><cite>'+cont[i].title+' ';
					kylb+='【数量：'+cont[i].num+'&nbsp;&nbsp;';
					kylb+='原始单价:￥'+cont[i].spyuanjia;
					
					if(cont[i].tejiaprice != 0){
        				switch(cont[i].shuxing){
        					case '2':
        						kylb+='&nbsp;&nbsp;<i style="color: #f00;">促销价:￥'+cont[i].tejiaprice+'</i>'
        						split+='<em>促销价：<i>'+cont[i].tejiaprice+'</i></em>';
        						break;
        					case '5':
        						kylb+='&nbsp;&nbsp;<i style="color: #f00;">打折价:￥'+cont[i].tejiaprice+'</i>'
        						split+='<em>打折价：<i>'+cont[i].tejiaprice+'</i></em>';
        						break;
        					case '6':
        						kylb+='&nbsp;&nbsp;<i style="color: #f00;">特价:￥'+cont[i].tejiaprice+'</i>'
        						break;
        					default:
        						kylb+='';
        						break;
        				}
        			}
					
					if(cont[i].price_jsx != 0){
						kylb+='<i style="color: #f00;">&nbsp;&nbsp;经销商:￥'+cont[i].price_jsx+'</i>'
					}
					kylb+='&nbsp;&nbsp;下单单价:￥'+cont[i].price+'】</cite><br>';
				}
				$('#shanpxxlb').html(kylb);
			}else{
				$('#shanpxxlb').html('数据读取失败！请刷新网页！');
			}
		},
		error:function(){
			$('#shanpxxlb').html('网络链接错误！请刷新网络！');
		}
	})
}

//打开经销商调整价格
function admintzjgjsx(ordnum){
	if(ordnum == ''){
		alert('参数错误！请刷新页面！');
		return;
	}
	findDimensions();
	var gaod=winHeight+'px';
	$('#taozhjgkj').css('height',gaod);
	$('#taozhjgkj').show(500,function(){
		$('#ddnumbh').val(ordnum);
		ddcplieb(ordnum);
	});
}
//列出订单商品
function ddcplieb(ordnum){
	var split='';
	$.ajax({
		type:"post",
		url:host+'/Admin/Orders/usqdhyjxs',
		async:true,
		data:{action:'shoplist',ordnum:ordnum},
		dataType:'json',
		beforeSend:function(){
			$('#jsxtjlistc').html('<div class="load_img"></div>');
		},
		success:function(data){
			
			if(data.code == 1){
				var infor=data.infor;
				var cont=infor.cont;
						
				for(i=0;i<infor.sum;i++){
					split+='<div class="shop_tjlist">';
        			split+='<img src="'+cont[i].imgpic+'">';
        			split+='<div><label>'+cont[i].title+'<em>购买数量：<i>'+cont[i].num+'</i></em></label>';
        			split+='<span><p>';
        			split+='<em>下单价格：<i style="color: #f00;">'+cont[i].price+'</i></em>';
        			split+='<em>原始价格：<i>'+cont[i].spyuanjia+'</i></em><br>';
        			
        			if(cont[i].tejiaprice != 0){
        				switch(cont[i].shuxing){
        					case '2':
        						split+='<em>促销价：<i>'+cont[i].tejiaprice+'</i></em>';
        						break;
        					case '5':
        						split+='<em>打折价：<i>'+cont[i].tejiaprice+'</i></em>';
        						break;
        					case '6':
        						split+='<em>特价：<i>'+cont[i].tejiaprice+'</i></em>';
        						break;
        					default:
        						split+='';
        						break;
        				}
        			}
        			split+='<em>经销商价格：<i style="color: #FF9600;" data-spid = "'+cont[i].id+'" id="jxsjszsxg'+cont[i].id+'">';
        			if(cont[i].price_jsx != 0){
        				split+=cont[i].price_jsx;
        			}
        			split+='</i></em>';
        			split+='</p><input class="xgtjxan" type="button" value="修改" name="xiugai" id="tjjxsxaja'+cont[i].id+'" data-type="1" onclick="jxstzjsan('+cont[i].id+')" />';
        			split+='</span></div></div>';
				}
				$('#jsxtjlistc').html(split);
			}else{
				$('#jsxtjlistc').html(data.msg);
			}
		},
		error:function(){
			$('#jsxtjlistc').html('网络链接错误！');
		}
	});
}


/*
 * 删除已选中商品
 */
function delcjsxus(id,shopnam){
	if(confirm('您确定要取消该经销商发货吗？')){
		
		//删除商品ID操作
		str=$('#gamenum').val();
		Deletestr=id+",";
		str=str.replace(Deletestr,"");
		Deletestr=","+id;
		str=str.replace(Deletestr,"");
		str=str.replace(id,"");	
		$('#gamenum').val(str);


		strxdz=$('#fahuofang').val();
		Dexdztr=id+",";
		strxdz=strxdz.replace(Dexdztr,"");
		Dexdztr=","+id;
		strxdz=strxdz.replace(Dexdztr,"");
		strxdz=strxdz.replace(id,"");	
		$('#fahuofang').val(strxdz);
		
		//删除已选择商品列表内容
		$('#xzshop'+id).remove();
	}
}
//查询选择
function cxddfscx(){
	var lookset=$('#lookset').val();
	switch(lookset){
		case '2':
		case '3':
			$('#ddcxff').show();
			break;
		default:
			$('#ddcxff').hide();
			break;
	}
}


function exportongji(setval){
	$('#exportype').val(setval);
	switch(setval){
		case 1:
			$('#dctstitle').html('总部发货统计信息导出');
			$('#jxszhdy').hide();
			break;
		case 2:
			$('#jxszhdy').show();
			$('#dctstitle').html('经销商推广统计信息导出');
			break;
		case 3:
			$('#jxszhdy').show();
			$('#dctstitle').html('经销商发货统计信息导出');
			break;
		case 4:
			$('#jxszhdy').show();
			$('#dctstitle').html('经销价统计信息导出');
			break;
		default:
			$('#jxszhdy').hide();
			$('#dctstitle').html('总部推广统计信息导出');
			break;
	}
	findDimensions();
	
		var gaod=winHeight+'px';
		$('#expordiv').css('height',gaod);
		$('#expordiv').show(function(){
			
		});

}

function close_xinxidac(){
	$('#expordiv').hide();
	$('#ertisky').html('');
	$('#jxsuser').val('');
	$('#begtimeks').val('');
	$('#endtimeks').val('');
	$('#exportype').val('');
	
}
//执行统计导出
function exporxxcl(){
	var exportype=$('#exportype').val();
	if(exportype == ''){
		alert('参数错误！不能执行操作！')
	}else{
		$.ajax({
			type:"post",
			url:host+"/Admin/Exporsitc/index",
			async:true,
			data:{type:exportype,jxsuser:$('#jxsuser').val(),begtimeks:$('#begtimeks').val(),endtimeks:$('#endtimeks').val()},
			dataType:'json',
			beforeSend:function(){
				$('#ertisky').html('<i class="icon-spinner icon-spin icon-large"></i>执行操作中,请等待....</em>');
			},
			success:function(data){
				if(data.code == 1){
					//location.href=data.downurl;
					//alert(host+data.downurl);
					close_xinxidac('expordiv')
					window.open(host+data.downurl);
					
				}else{
					alert(data.msg);
				}
				
			},
			error:function(){
				$('#ertisky').html('<i class="icon-minus-sign"></i>网络链接错误！</em>');
			}
		});
	}
}


//单机删除图片
//删除图片集图片
function delpicim(url){
	if(confirm('您确定要删除该图片吗？')){
		var pic_list=document.getElementById("imgcpntiv");
	
	
		var textval=document.getElementById("imgvales");
		
		str=textval.value;
		Deletestr=url+",";
		str=str.replace(Deletestr,"");
		Deletestr=","+url;
		str=str.replace(Deletestr,"");
		str=str.replace(url,"");	
		textval.value=str;
		pic_list.innerHTML="";
		var strs= new Array(); //定义一数组 
		strs=str.split(","); //字符分割 
			for (i=0;i<strs.length ;i++ ) 
			{ 
			//document.write(strs[i]+"<br/>"); //分割后的字符输出 
			//pic_list.innerHTML=pic_list.innerHTML+"<img src="+strs[i]+" onclick=jqzfc('"+strs[i]+"','list_picture') alt='单击删除'>";
				if(strs[i] != ""){
				pic_list.innerHTML=pic_list.innerHTML+"<span><img onclick=delpicim('"+strs[i]+"') alt='单机删除' src='"+host+"/upload/images/"+strs[i]+"'><a onclick=delpicim('"+strs[i]+"') >删除</a></span>";
				}
			} 
	}
}






































