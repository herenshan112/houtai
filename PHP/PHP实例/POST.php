<?
$phone="18664596717";
$randStr = str_shuffle('1234567890');  
$code = substr($randStr,0,6);
$msg="你的额验证码为：".$code."【中正云通信】";

function NewSms($phone,$msg)
    {
			$url="http://service.winic.org:8009/sys_port/gateway/index.asp?";
			$data = "id=%s&pwd=%s&to=%s&content=%s&time=";
			$id = '帐号';
			$pwd = '密码';
			$to = $phone; 
			$content = iconv("UTF-8","GB2312",$msg);
			$rdata = sprintf($data, $id, $pwd, $to, $content);
			
			
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST,1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$rdata);
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$result = curl_exec($ch);
			curl_close($ch);
			$result = substr($result,0,3);
			return $result;
	}
  echo NewSms($phone,$msg)
?>