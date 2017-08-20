<?php
namespace Index\Controller;

class Util {
    /**
     * 分类ID转名称
     */
    public static function cateid2name($cateid) {
        $M_category = M('category');
        return $M_category->where(array('id'=>$cateid))->getField('title');
    }
    
    /**
     * cookie加密
     */
    public static function cookie_salt($username, $password) {
        $salt_str = '';
        $salt_str = md5($username) . strlen($password) . md5($password) . strlen($username);
        $salt_str = md5($salt_str);
        
        return $salt_str;
    }
    
    /**
     * cookie验证
     */
    public static function cookie_ckeck($username, $password) {
        $salt_str = '';
        $salt_str = md5($username) . strlen($password) . md5($password) . strlen($username);
        $salt_str = md5($salt_str);
    
        return $salt_str;
    }
    
    /**
     * 用户信息加密解密函数
     *
     * 待加密内容用\t分割
     * @return String 加密或解密字符串
     * @param String $string 待加密或解密字符串
     * @param String $operation 操作类型定义 DECODE=解密 ENDODE=加密
     * @param String $key 加密算子
     */
    
    public static function authcode($string, $operation, $key = '3DSg45oenmdf9043tmdsfg09dsjguFIKasd') {
        /**
         * 获取密码算子,如未指定，采取系统默认算子
         * 默认算子是论坛授权码和用户浏览器信息的md5散列值
         * $GLOBALS['discuz_auth_key']----全局变量
         * 取值为:md5($_DCACHE['settings']['authkey'].$_SERVER['HTTP_USER_AGENT'])
         * $_DCACHE['settings']['authkey']是论坛安装时生成的15位随机字符串
         */
        $key = md5($key ? $key : $GLOBALS['discuz_auth_key']);
        $key_length = strlen($key);
        
        /**
         * 如果解密，先对密文解码
         * 如果加密,将密码算子和待加密字符串进行md5运算后取前8位
         * 并将这8位字符串和待加密字符串连接成新的待加密字符串
         */
        $string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
        $string_length = strlen($string);
        $rndkey = $box = array();
        $result = '';

        /**
         * 初始化加密变量,$rndkey和$box
         */
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
    
    
        /**
         * $box数组打散供加密用
         */
        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
    
        /**
         * $box继续打散,并用异或运算实现加密或解密
         */
        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
    
        if($operation == 'DECODE') {
            if(substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }
    
    /**
     * curl http
     */
    public static function curlGet($url, $header=null, $ispost=false, $postdata=null) {
        if(!$url) return -1;
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 500);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    
        if($header && is_array($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
    
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0');

        if ($ispost AND $postdata) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        } else {
            curl_setopt($ch, CURLOPT_POST, false);
        }
        //curl_setopt($ch, CURLOPT_REFERER, 'http://mobile.umeng.com');
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
        $output = curl_exec($ch);
    
        curl_close($ch);
    
        return $output;
    }
    
    /**
     * 生成唯一推荐码
     */
    public static function genRecCode($codelength=6) {
        $code = '';
        $rand_repo = '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';
        $rand_repo_len = 55;
        
        $existed = true;
        
        $M_reccode = M('reccode');
        
        do {
            $code = '';
            do {
                $rand = mt_rand (0 , 54);
                $code .=  $rand_repo[$rand];
            } while(--$codelength);
            
            $existed = $M_reccode->where(array('code'=>$code))->count();
        } while($existed);
        
        return $code;
    }
    


    /**
     * 获取access_token
     */
    public static function getWxToken() {
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . WX_APPID;
        $url .= '&secret=' . WX_APPSECRET;
    
        $return_data = Util::curlGet($url);
    
        if (!$return_data) return false;
    
        $json_obj = json_decode($return_data);
    
        if ($json_obj->access_token) return $json_obj->access_token;
    
        return false;
    }
}