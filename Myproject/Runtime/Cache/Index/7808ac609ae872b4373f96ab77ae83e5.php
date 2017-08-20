<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">


    <link rel="stylesheet" href="/Public/index/css/bootstrap.css"/>
    <link rel="stylesheet" href="/Public/index/css/style.css"/>
    <script src="/Public/index/js/jquery-1.9.1.min.js"></script>
    <script src="/Public/index/js/js.js"></script>
    <script src="/Public/index/js/jquery.js"></script>
    <script src="/Public/index/js/myjs.js"></script>
    <meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi"/>
<title>注册</title>
</head>
<body>

<div class="rol cw-tit fhfs">
    <div class="m-box">
        用户注册
    </div>
</div>



<div class="rol">
      <div class="m-box zc-list">
	<form name="myform" action="<?php echo U('regcontcl');?>" method="POST">
		<div>
             <span class="zc-img1"></span>
             <b>手机号码</b>
             <input type="text" name="username" id="username" placeholder="请输入手机号码" />
          </div>
          <div>
              <span class="zc-img2"></span>
              <b>密码</b>
              <input type="password" name="pwdval" id="pwdval" />
          </div>
          <div>
              <span class="zc-img3"></span>
              <b>确认密码</b>
              <input type="password" name="qrpwdval" id="qrpwdval" />
          </div>
          
          <div>
              <span class="zc-img6"></span>
              <b>验证码</b>
              <input type="text" name="codeval" id="codeval" placeholder="请输入验证码" style="width: 200px;" />
              <input type="button" style="width:250px;" value="点击发送验证码" onclick="sendCode(this)" />

          </div>
    </div>
        <div class="rol zc-an">
        	<input type="hidden" name="tjrval" id="tjrval" value="<?php echo ($uidval); ?>" />
           <div><button class="zc btn">注册</button></div>
           <div><span>已有账号？</span><a href="<?php echo U('User/login');?>">登录</a></div>
      	</div>
</div>
	</form>
	
	
	<script type="text/javascript">
 var clock = '';
 var nums = 60;
 var btn;
 function sendCode(thisBtn)
 { 
 	btn = thisBtn;
	var username=$('#username').val();
	if(username != ''){
		var pdz=mysjh_jc(username);
		if(pdz == 1){
			//alert(username);
			$.ajax({
				url:host+'/Index/index/smscode.html',
				type:'post',
				data:{telval:username},
				dataType:'json',
				beforeSend:function(){
					btn.disabled = true; //将按钮置为不可点击
					btn.value='Loading......'
				},
				success:function(data){
					//alert(data);
					//console.log(data);
					if(data.code == 1){
						btn.value = nums+'秒后可重新获取';
						clock = setInterval(doLoop, 1000); //一秒执行一次
					}else{
						btn.value ='点击发送验证码';
						btn.disabled = false;
						alert(data.msg);
					}
				},
				error:function(){
					btn.value='网络链接错误！'
				}
			});
			
			/*btn.disabled = true; //将按钮置为不可点击
			btn.value = nums+'秒后可重新获取';
			clock = setInterval(doLoop, 1000); //一秒执行一次*/
		}else{
			alert('请输入正确的手机号码！');
		}
		
	}else{
		alert('请输入手机号码！');
	}
 
 }
 function doLoop()
 {
 nums--;
 if(nums > 0){
  btn.value = nums+'秒后可重新获取';
 }else{
  clearInterval(clock); //清除js定时器
  btn.disabled = false;
  btn.value = '点击发送验证码';
  nums = 10; //重置时间
 }
 }
 
 //---------手机号合法性检查

function mysjh_jc(id_name){
	var sjhid=/^1[3|4|5|8][0-9]\d{4,8}$/;
	var sjhclje=sjhid.test(id_name);
	if(!sjhclje){
		return 0;
	}else{
		return 1;
	}
}


$(".btn").click(function(e) {
		if($('#username').val()==''){
			alert('请输入手机号码');
			$('#username').focus();
			return false;
		}
		if($('#pwdval').val()==''){
			alert('请输入密码');
			$('#pwdval').focus();
			return false;
		}
		if($('#qrpwdval').val()== ''){
			alert('请输入确认密码');
			$('#qrpwdval').focus();
			return false;
		}
		if($('#qrpwdval').val() != $('#pwdval').val()){
			alert('您两次输入的密码不一致！');
			$('#qrpwdval').focus();
			return false;
		}
		if($('#codeval').val()==''){
			alert('请输入验证码');
			$('#codeval').focus();
			return false;
		}
		
		$("form[name='myform']").submit();
	});
</script>


</body>
</html>