<?php
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

define('__APP__','');
// 定义应用目录
define('APP_PATH','./Myproject/');

// 定义微信开发信息
define('WX_URL', 'http://gsh.qcw100.com');
define('WX_APPID', 'wx79e99ac7b2ceacb4');
define('WX_APPSECRET', '037047cae82f0fee9f4e1417e17c0b11');
define('WX_MID', '1482925842');
define('WX_KEY', '6622793749389379195pengsendPpSsD');
define('WX_URL_REDIRECT', '/Index/Index/wx_oauth.html');
define('WX_URL_SALES', '/Index/Index/wx_oauth_sales.html');

// 定义短信接口信息
define('SMS_USER', '');
define('SMS_PASS', strtoupper(md5('')));

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单