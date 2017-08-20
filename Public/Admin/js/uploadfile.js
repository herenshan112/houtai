$(document).ready(function(){
	//批量上传图片（即时显示）
	$("#picimgary").change(function(){
		var data = new FormData();
		$.each($('#picimgary')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});
		var fileclas=$('#picimgaryset').val();
		data.append("typeid", fileclas); 
		//alert(fileclas);
		$.ajax({

			url:'http://gsh.qcw100.com/Admin/Index/upload',
			type:'POST',
			data:data,
			cache: false,
			contentType: false,	//不可缺参数
			processData: false,		//不可缺参数
			success:function(data){
				data = $(data).html();
				//alert(data);
				
				var strval=new Array();
				strval=data.split(",");
				var strs= new Array();
				
				for(i=0;i<strval.length-1;i++){
					strs=strval[i].split("|");
					if($('#imgvales').val() != ''){
						$('#imgvales').val($('#imgvales').val()+","+strs[0]);
					}else{
						$('#imgvales').val(strs[0]);
					}
					$('#imgcpntiv').html($('#imgcpntiv').html()+'<span><img  onClick=delpicim("'+strs[0]+'") alt="单机删除" src="http://gsh.qcw100.com/upload/images/'+strs[0]+'"><a onClick=delpicim("'+strs[0]+'")>删除</a></span>');
				}
				
				
			},
			error:function(){
				//$('#anidoemsg').html('<b class="tubiao4">&nbsp;</b>');
			}
		});
	
	});
	
	
	//单张上传会员图片（即时显示）
	$("#uppicimg").change(function(){
		var data = new FormData();
		$.each($('#uppicimg')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});
		var fileclas=$('#uppicimgtype').val();
		data.append("typeid", fileclas); 
		
		$.ajax({

			url:'http://gsh.qcw100.com/Admin/Index/upload',
			type:'POST',
			data:data,
			cache: false,
			contentType: false,	//不可缺参数
			processData: false,		//不可缺参数
			success:function(data){
				data = $(data).html();
				
				//alert(data);
				var strval=new Array();
				strval=data.split(",");
				//alert(strval[0]);
				var zhygscz=strval.length-1;
				//alert(strval.length-1);
				$('#erroes').html(strval[zhygscz-1]);
				var strs= new Array(); //定义一数组 
				strs=strval[zhygscz-1].split("|"); //字符分割 
				if(strs[3]==1){
					//alert(strs[1]);
					switch(strs[1]){
						case "0":
							$('#mytxpic').attr("src",strs[0]);
							$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>上传失败');
							break;
						case "1":
							$('#mytxpic').attr("src",strs[0]);
							$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>你上传的文件超过允许上传的最大值');
							break;
						case "2":
							$('#mytxpic').attr("src",strs[0]);
							$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>你上传的文件类型不正确');
							break;
						case "3":
							var txdz='/upload/vip/'+strs[0];
							//alert(txdz)
							$('#mytxpic').attr("src",'/upload/vip/'+strs[0]);
							//$('#sussic1').html('<span class="icon-ok-circle icon-2x greencolor"></span>');
							$('#toppic').val('/upload/vip/'+strs[0]);
							//alert(txdz);
							break;
						case "4":
							$('#mytxpic').attr("src",strs[0]);
							$('#toppic').val(strs[0]);
							$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>文件上传失败');
							break;
						default:
							$('#mytxpic').attr("src",strs[0]);
							$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>文件上传失败');
					}
				}else{
					
					$('#mytxpic').attr("src",strs[0]);
					$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>签名错误');
				}
				
				
			},
			error:function(){
				//$('#anidoemsg').html('<b class="tubiao4">&nbsp;</b>');
			}
		});
	
	});
	
	
	//单张上传营业执照图片（即时显示）
	$("#uppicimgyyzz").change(function(){
		var data = new FormData();
		$.each($('#uppicimgyyzz')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});
		var fileclas=$('#uppicimgtypeyyzz').val();
		data.append("typeid", fileclas); 
		//alert(fileclas);
		$.ajax({

			url:'http://gsh.qcw100.com/Admin/Index/upload',
			type:'POST',
			data:data,
			cache: false,
			contentType: false,	//不可缺参数
			processData: false,		//不可缺参数
			success:function(data){
				data = $(data).html();
				
				//alert(data);
				var strval=new Array();
				strval=data.split(",");
				//alert(strval[0]);
				var zhygscz=strval.length-1;
				//alert(strval.length-1);
				//$('#erroes').html(strval[zhygscz-1]);
				var strs= new Array(); //定义一数组 
				strs=strval[zhygscz-1].split("|"); //字符分割 
				if(strs[3]==1){
					//alert(strs[1]);
					switch(strs[1]){
						case "0":
							$('#myyyzzpic').attr("src",strs[0]);
							$('#sussic16').html('<span class="icon-minus-sign icon-2x redcolor"></span>上传失败');
							break;
						case "1":
							$('#myyyzzpic').attr("src",strs[0]);
							$('#sussic16').html('<span class="icon-minus-sign icon-2x redcolor"></span>你上传的文件超过允许上传的最大值');
							break;
						case "2":
							$('#myyyzzpic').attr("src",strs[0]);
							$('#sussic16').html('<span class="icon-minus-sign icon-2x redcolor"></span>你上传的文件类型不正确');
							break;
						case "3":
							var txdz='/upload/vip/'+strs[0];
							//alert(txdz)
							$('#myyyzzpic').attr("src",'/upload/images/'+strs[0]);
							$('#sussic16').html('<span class="icon-ok-circle icon-2x greencolor"></span>');
							$('#toppicyyzz').val('/upload/images/'+strs[0]);
							//alert(txdz);
							break;
						case "4":
							$('#myyyzzpic').attr("src",strs[0]);
							$('#toppicyyzz').val(strs[0]);
							$('#sussic16').html('<span class="icon-minus-sign icon-2x redcolor"></span>文件上传失败');
							break;
						default:
							$('#myyyzzpic').attr("src",strs[0]);
							$('#sussic16').html('<span class="icon-minus-sign icon-2x redcolor"></span>文件上传失败');
					}
				}else{
					
					$('#myyyzzpic').attr("src",strs[0]);
					$('#sussic16').html('<span class="icon-minus-sign icon-2x redcolor"></span>签名错误');
				}
				
				
			},
			error:function(){
				//$('#anidoemsg').html('<b class="tubiao4">&nbsp;</b>');
			}
		});
	
	});
	
	
	
	
	//单张上传会员图片（即时显示）
	$("#uppicimgijk").change(function(){
		var data = new FormData();
		$.each($('#uppicimgijk')[0].files, function(i, file) {
			data.append('upload_file'+i, file);
		});
		var fileclas=$('#uppicimgtype').val();
		data.append("typeid", fileclas); 
		
		$.ajax({

			url:'http://gsh.qcw100.com/Admin/Index/upload',
			type:'POST',
			data:data,
			cache: false,
			contentType: false,	//不可缺参数
			processData: false,		//不可缺参数
			success:function(data){
				data = $(data).html();
				
				//alert(data);
				var strval=new Array();
				strval=data.split(",");
				//alert(strval[0]);
				var zhygscz=strval.length-1;
				//alert(strval.length-1);
				$('#erroes').html(strval[zhygscz-1]);
				var strs= new Array(); //定义一数组 
				strs=strval[zhygscz-1].split("|"); //字符分割 
				if(strs[3]==1){
					//alert(strs[1]);
					switch(strs[1]){
						case "0":
							$('#mytxpic').attr("src",strs[0]);
							$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>上传失败');
							break;
						case "1":
							$('#mytxpic').attr("src",strs[0]);
							$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>你上传的文件超过允许上传的最大值');
							break;
						case "2":
							$('#mytxpic').attr("src",strs[0]);
							$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>你上传的文件类型不正确');
							break;
						case "3":
							var txdz='/upload/images/'+strs[0];
							//alert(txdz)
							$('#mytxpic').attr("src",'/upload/images/'+strs[0]);
							//$('#sussic1').html('<span class="icon-ok-circle icon-2x greencolor"></span>');
							$('#toppic').val('/upload/images/'+strs[0]);
							//alert(txdz);
							break;
						case "4":
							$('#mytxpic').attr("src",strs[0]);
							$('#toppic').val(strs[0]);
							$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>文件上传失败');
							break;
						default:
							$('#mytxpic').attr("src",strs[0]);
							$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>文件上传失败');
					}
				}else{
					
					$('#mytxpic').attr("src",strs[0]);
					$('#sussic1').html('<span class="icon-minus-sign icon-2x redcolor"></span>签名错误');
				}
				
				
			},
			error:function(){
				//$('#anidoemsg').html('<b class="tubiao4">&nbsp;</b>');
			}
		});
	
	});
	
	
	
	
	
	
	
	
	
});