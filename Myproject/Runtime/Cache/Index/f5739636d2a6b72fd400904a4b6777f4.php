<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">

<link rel="stylesheet" href="/Public/index/css/ban.css"/>
    <link rel="stylesheet" href="/Public/index/css/bootstrap.css"/>
    <link rel="stylesheet" href="/Public/index/css/style.css"/>
    <script src="/Public/index/js/jquery-1.9.1.min.js"></script>
    <script src="/Public/index/js/js.js"></script>
    <script src="/Public/index/js/jquery.js"></script>
    <script src="/Public/index/js/iscroll.js"></script>
    <script src="/Public/index/js/myjs.js"></script>
    <meta name="viewport" content="width=750, user-scalable=no, target-densitydpi=device-dpi"/>
<title>购物车</title>
</head>
<body>

<div class="rol cw-tit">
    <div class="m-box">
        <div class="lt" onclick="javascript:history.go(-1);"><span class="lt cw-fh gwc-top"></span>购物车(<?php echo ($shopsum); ?>)</div>
        <!--div class="gt bj">编辑</div-->
    </div>
</div>




<div class="rol" id='jsazmff'>
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><div class="gwc-list rol" id='xihgwui<?php echo ($i); ?>'>
        <div class="m-box" >
            <div class="lt gwc-list-lt">
                <input type="checkbox" data-numv="<?php echo ($i); ?>" value="<?php echo ($list["sid"]); ?>" name="items" id='items<?php echo ($i); ?>' />
            </div>
            <div class="lt gwc-list-con">
                <div class="lt cwzx-cp-lt">
                    <img class="gwc_img" src="<?php echo ($list["titlepic"]); ?>" alt="<?php echo ($list["title"]); ?>"/>
                </div>
                <div class="lt cwzx-cp-gt gwc-cp-gt">
                    <div class="cwzx-cp-tit">
                        <span><?php echo (shoptype($list["type"])); ?></span><?php echo ($list["title"]); ?>
                    </div>
                    <div class="delt_shop">
                    	<em onclick=deltgwsp(<?php echo ($i); ?>,<?php echo ($list["sid"]); ?>)>&nbsp;</em>
                    </div>
                    <div class="cwzx-cp-jg">
                        <span class="ddzx-jk">￥<?php echo ($list["jiage"]); ?></span>
                        <span class="gt">
                           <table class="tab">
                               <tr>
                                   <td class="jiancaoz" onclick="jianjscz(<?php echo ($i); ?>)">-</td>
                                   <td>
                                   	<input name="shopsum" class="shop_gwcsum" value="<?php echo ($list["num"]); ?>" type="text"  id='itsum<?php echo ($i); ?>' />
                                    <input name="danjiag" class="shop_danj" value="<?php echo ($list["jiage"]); ?>" type="hidden"  id='itpicer<?php echo ($i); ?>' />
                                   </td>
                                   <td class="jiacaoz" onclick="jiajscz(<?php echo ($i); ?>)">+</td>
                               </tr>
                           </table>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>
    

</div>






<!--底部-->
<div class="rol gwc-foot">
    <div >
        <div class="lt gwc-foot-qc">
            <input  id="all" class="check" type="checkbox"/><span class="qc">全选</span>
        </div>
        <div class="lt gwc-foot-hj">
            合计：
            <span id="gwcspzj">¥ 0</span>
        </div>
        <div class="gt gwc-foot-js">
            <a onclick="addordercl(<?php echo ($list["uid"]); ?>)">去结算(<?php echo ($shopsum); ?>)</a>
        </div>
    </div>
</div>


</body>
</html>