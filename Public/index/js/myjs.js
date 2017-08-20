var hosthead='http://';
var tmpTag = 'https:' == document.location.protocol ? false : true;

var winWidth = 0; 
var winHeight = 0; 

if(tmpTag){
	hosthead='http://';
}else{
	hosthead='https://';	
}

if(window.location.host != null){
			var host = hosthead+window.location.host;
		}else{
			var host=hosthead+document.domain; 
}
function tiaozhuan(url){
	window.location.herf=url;
}
//产品列表副本排序
function click_cxtj(page=1){
	var action=$('#actval').val();
	var setlst=$('#setlst').val();
	var tpval=$('#tpval').val();
	if(page != 1){
		var pages=$('#pageset').val();
	}else{
		var pages=1;
	}
	switch(setlst){
		case '0':
			if($('#mrsetval').val() == 0){
				$('#mrsetval').val(1);
				$('#rmsetval').val(0);
				$('#sqsetval').val(0);
				$('#xlsetval').val(0);
			}else{
				$('#mrsetval').val(0);
			}
			break;
		case '1':
			if($('#rmsetval').val() == 0){
				$('#rmsetval').val(1);
				$('#mrsetval').val(0);
				$('#sqsetval').val(0);
				$('#xlsetval').val(0);
			}else{
				$('#rmsetval').val(0);
			}
			break;
		case '2':
			if($('#sqsetval').val() == 0){
				$('#sqsetval').val(1);
				$('#mrsetval').val(0);
				$('#rmsetval').val(0);
				$('#xlsetval').val(0);
			}else{
				$('#sqsetval').val(0);
			}
			break;
		case '3':
			if($('#xlsetval').val() == 0){
				$('#xlsetval').val(1);
				$('#mrsetval').val(0);
				$('#rmsetval').val(0);
				$('#sqsetval').val(0);
			}else{
				$('#xlsetval').val(0);
			}
			break;
		default:
			$('#mrsetval').val(0);
			$('#rmsetval').val(0);
			$('#sqsetval').val(0);
			$('#xlsetval').val(0);
			break;
	}
	
	var stlst='';
	$.ajax({
		type:"post",
		url:host+"/Index/Mall/paixulist",
		async:true,
		data:{p:pages,setlst:setlst,action:action,tpval:tpval},
		dataType:'json',
		beforeSend:function(){
			$('#sky_next').html('<div class="error_loading"></div>')
		},
		success:function(data){
			
			if(data.code == 1){
				var sum=data.infor.sum;
				var cont=data.infor.cont;
				for(i=0;i<sum;i++){
					stlst+='<a href="#">';
            		stlst+='<img src="'+cont[i].titlepic+'" alt="'+cont[i].title+'"/>';
            		stlst+='<div class="zrrx-list-txt"><span>'+cont[i].tyname+'</span>'+cont[i].title+'</div>';
            		stlst+='<div class="zrrx-list-bot">';
                	stlst+='<div class="lt zrrx-jh">￥';
                	switch(cont[i].tejia){
                		case '2':
                			if(cont[i].tejia != 0){
                				stlst+=cont[i].tejiaprice;
                			}else{
                				stlst+=cont[i].price;
                			}
                			break;
                		case '5':
                			if(cont[i].tejia != 0){
                				stlst+=cont[i].tejiaprice;
                			}else{
                				stlst+=cont[i].price;
                			}
                			break;
                		case '6':
                			if(cont[i].tejia != 0){
                				stlst+=cont[i].tejiaprice;
                			}else{
                				stlst+=cont[i].price;
                			}
                			break;
                		default:
                			stlst+=cont[i].price;
                			break;
                	}
                	stlst+='</div>';
                	stlst+='<div class="gt zrrx-fx">';
                    stlst+='<span class="zrrx-fx-img"></span>';
                    stlst+='<div class="zrrx-fx-txt">月销'+cont[i].salenum+'笔</div>';
                	stlst+='</div></div>';
                	switch(cont[i].tejia){
                		case 1:
                			stlst+='<span class="rm"></span>';
                			break;
                		case 2:
                			stlst+='<span class="rm1"></span>';
                			break;
                		case 3:
                			stlst+='<span class="rm2"></span>';
                			break;
                		case 4:
                			stlst+='<span class="rm3"></span>';
                			break;
                		case 5:
                			stlst+='<span class="rm4"></span>';
                			break;
                		case 6:
                			stlst+='<span class="rm5"></span>';
                			break;
                		default:
                			stlst+='';
                			break;
                	}
        			stlst+='</a>';
				}
				if(pages > 1){
					$('#porlist').append(stlst);
					$('#sky1').html('');
				}else{
					$('#porlist').html(stlst);
					$('#sky1').html('');
				}
				var jhp=pages*1+1;
				$('#pageset').val(jhp);
				$('#sky_next').html('查看更多商品！')
				
			}else{
				$('#sky_next').css('cursor','not-allowed');
				$('#sky_next').html('已展示全部！');
				
			}
		},
		error:function(){
			$('#sky_next').html('网络链接错误！')
		}
	});
}



/**
 * 抓取API数据
 * @param  {String} url    链接
 * @param  {Objece} params 参数
 * @return {Promise}       包含抓取任务的Promise
 */
function fetchApi (url, params) {
  return new Promise((resolve, reject) => {
    wx.request({
      url: `${url}`,
      data: Object.assign({}, params),
      header: { 'Content-Type': 'application/json' },
      success: resolve,
      fail: reject
    })
  })
}
/**
 * 抓取首页布局
 * @return {Promise}       包含抓取任务的Promise
 */
function getHomeLayout(){
    return fetchApi(`${URI}/ad/get?id=iOS.HomeV2.Layout&_rndev=104042`).then(res => res.data)
}

//删除配件
function quxpeij(id){
	$('#pjlbtk_'+id).hide();
	$('#pjtp'+id).hide();
	str=$('#shoppj').val();
		Deletestr=id+",";
		str=str.replace(Deletestr,"");
		Deletestr=","+id;
		str=str.replace(Deletestr,"");
		str=str.replace(id,"");	
		$('#shoppj').val(str);
	if(str == ''){
		$('.xiugaipj-box').css('display','none')
	}
}
//收藏处理
function jrqxsc(uid=0,pid=0){
	if(uid == 0){
		alert('请先登陆');
		location.href=host+'/Index/User/login';
		return false;
	}
	$.ajax({
		type:"post",
		url:host+"/Index/Mall/shoucangcl",
		async:true,
		data:{uid:uid,pid:pid},
		dataType:'json',
		success:function(data){
			switch(data.code){
				case 1:
					$('#scdivcnt').html('<b class="spxq-jrgwcysc"></b><div>取消收藏</div>')
					break;
				case 2:
					$('#scdivcnt').html('<b class="spxq-jrgwc"></b><div>加入收藏</div>')
					break;
				case 3:
					alert('请先登陆');
					location.href=host+'/Index/User/login';
					break;
				default:
					alert(data.msg);
					break;
			}
		},
		error:function(){
			alert('网络链接错误！请检查您的网络！')
		}
	});
}
//加入购物车
function jrgwccl(uid=0){
  var shopid=$('#shopid').val();
  var shoppj=$('#shoppj').val();
  var shopsum=parseInt($('#prosum').html());
  if(uid == 0){
    alert('请先登陆');
    location.href=host+'/Index/User/login';
    return false;
  }
  if(shopid == 0){
    alert('参数错误！请重新选择商品！');
    return false;
  }
  if(shopsum <= 0){
  	shopsum=1;
  }
  $.ajax({
    url:host+'/Index/Mall/addgwc',
    type:'POST',
    data:{uid:uid,shopid:shopid,shopsum:shopsum,shoppj:shoppj},
    dataType:'json',
    async:true,
    success:function(data){
      switch(data.code){
          case 1:
            if(confirm('是否去结算？')){
                location.href=host+'/Index/Orders/newsgwc.html';
                return false;
            }
            break;
          case 2:
            alert(data.msg);
            location.href=host+'/Index/User/login';
    		return false;
            break;
          default:
            alert(data.msg);
            break;
      }
    },
    error:function(){
      alert('网络链接错误！请检查您的网络！');
    }
  });
}


function jqchk(){ //jquery获取复选框值 
  var chk_value =[]; 
  var chk_num=[];
  $('input[name="items"]:checked').each(function(){ 
    chk_value.push($(this).val()); 
    chk_num.push($(this).data('numv')); 
  }); 
  	var spic=0;
    if(chk_num.length > 0){
    	for(jbq=0;jbq<chk_num.length;jbq++){
    		spic+=parseInt($('#itsum'+chk_num[jbq]).val())*parseFloat($('#itpicer'+chk_num[jbq]).val());
    	}
    }
    $('#gwcspzj').html('¥ '+spic);
  /*if(chk_value.length != 0){
  	$.ajax({
  	  url:host+'/Index/Orders/jsgwczj',
  	  type:'POST',
  	  data:{spid:chk_value},
  	  dataType:'json',
  	  async:true,
  	  beforeSend:function(){
  	  	$('#gwcspzj').html('<img src="/Public/index/img/loading.gif" />');
  	  },
  	  success:function(data){
  	  	//alert(data.msg)
  	  	if(data.code == 1){
  	  		$('#gwcspzj').html(data.sum);
  	  	}else{
  	  		$('#gwcspzj').html(data.msg);
  	  	}
  	  	setTotal(); 
  	  },
  	  error:function(){
  	    $('#gwcspzj').html('网络链接错误！请检查您的网络！');
  	  }
  	});
  }else{
  	$('#gwcspzj').html(0);
  }*/
} 

//购物车价格调整
function setTotal(){ 
	var s=0; 
	
	
	$("#jsazmff table").each(function(){ 
		s+=parseInt($(this).find('input[class*=shop_gwcsum]').val())*parseFloat($(this).find('input[class*=shop_danj]').val()); 
	}); 
	
	$("#gwcspzj").html('¥ '+s.toFixed(2)); 
} 

//购物车加副本
function jiajscz(id){
	$('#itsum'+id).val(parseInt($('#itsum'+id).val())+1);
	if($("#items"+id).is(':checked')){
	}else{
		$("#items"+id).attr("checked","checked");
	}
	var flag="checked";
	$("[name=items]:checkbox").each(function(){
		if(!this.checked){
			flag=false;
		}
	});
	$("#all").attr("checked",flag);
	jqchk()
}
//购物车减副本
function jianjscz(id){
	var sum=parseInt($('#itsum'+id).val());
	if($("#items"+id).is(':checked')){
	}else{
		$("#items"+id).attr("checked","checked");
	}
	if(sum-1<=0){
		$('#itsum'+id).val(1);
	}else{
		$('#itsum'+id).val(parseInt($('#itsum'+id).val())-1);
	}
	
	var flag="checked";
	$("[name=items]:checkbox").each(function(){
		if(!this.checked){
			flag=false;
		}
	});
	$("#all").attr("checked",flag);
	jqchk()
}

//生成订单
function addordercl(uid=0){
  if(uid == 0){
    alert('请先登陆');
    location.href=host+'/Index/User/login';
    return false;
  }
  var chk_value =[]; 
  var chk_num=[];
  var fsdata=[];
  $('input[name="items"]:checked').each(function(){ 
    chk_value.push($(this).val()); 
    chk_num.push($(this).data('numv')); 
  }); 
  if(chk_value.length <= 0){
  	alert('请您选择要结算商品！')
  	return false;
  }
  for(var js=0;js<chk_num.length;js++){
  	var cs=chk_value[js]+'|'+parseInt($('#itsum'+chk_num[js]).val());
  	fsdata.push(cs)
  }
  $.ajax({
  	type:"post",
  	url:host+"/Index/Orders/addorderxd",
  	async:true,
  	data:{uid:uid,ordcont:fsdata},
  	dataType:'json',
  	beforeSend:function(){
  		$('#gwcspzj').html('<img src="/Public/index/img/loading.gif" />');
  	},
  	success:function(data){
  		console.log(data);
  		$('#gwcspzj').html(data.jb);
  		if(data.code==1){
  			location.href=host+'/Index/Orders/jiesuan.html?ordnum='+data.ordnum;
  		}else{
  			location.href=host+'/Index/Mall/index.html';
  		}
  	},
  	error:function(){
  		$('#gwcspzj').html('网络链接错误！请检查您的网络！');
  	}
  });
  
}
//立即购买
function ljgmcljs(uid=0){
  var shopid=$('#shopid').val();
  var shoppj=$('#shoppj').val();
  var shopsum=parseInt($('#prosum').html());
  if(uid == 0){
    alert('请先登陆');
    location.href=host+'/Index/User/login';
    return false;
  }
  if(shopid == 0){
    alert('参数错误！请重新选择商品！');
    return false;
  }
  if(shopsum <= 0){
  	shopsum=1;
  }
  $.ajax({
    url:host+'/Index/Mall/ljxdcl.html',
    type:'POST',
    data:{uid:uid,shopid:shopid,shopsum:shopsum,shoppj:shoppj},
    dataType:'json',
    async:true,
    success:function(data){
      switch(data.code){
          case 1:
            
            location.href=host+'/Index/Orders/jiesuan.html?ordnum='+data.ordnum;
            return false;
            
            break;
          case 2:
            alert(data.msg);
            location.href=host+'/Index/User/login';
    		return false;
            break;
          default:
            alert(data.msg);
            break;
      }
    },
    error:function(){
      alert('网络链接错误！请检查您的网络！');
    }
  });
}
/*
 * 选择发货方式
 */
function xxfhfzcl(name,conest,sum){
	$('#fhsetval').val(conest);
	for(i=1;i<=sum;i++){
		if(i == conest){
			
			
			$('#azdizhi').val('');
			$('#azname').val('');
			$('#aztel').val('');
			
			$('#'+name+i).addClass('rjt1');
			$('#con_'+name+'_'+i).show();
		}else{
			
			$('#kuaidinum').val('');
			
			$('#'+name+i).removeClass('rjt1');
			$('#con_'+name+'_'+i).hide();
		}
	}
}


//购物车删除商品
function deltgwsp(id,sid){
	var jiage=$('#itpicer'+id).val();
	var itsum=$('#itsum'+id).val();
	
	var items=$('#items'+id).val();
	if (confirm('确定要删除吗？')){
		$("#xihgwui"+id).empty();
		
		$.ajax({
			url:host+'/Index/Orders/deltgwc.html',
			type:'POST',
			data:{id:items},
			dataType:'json',
			success:function(data){
				if(data.code == 1){
					jqchk();
				}else{
					alert(data.msg);
				}
				
			}
		});
	}
	
}





































