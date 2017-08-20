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
<title>我的订单</title>
</head>
<body>

<div class="rol cw-tit ddzx">
    <div class="m-box">
        <div class=""><a href="<?php echo U('index');?>"><span class="lt cw-fh"></span></a>订单中心</div>
    </div>
</div>
<div class="rol ddzx-nav">
    <!--<a <?php if(($setval) == "all"): ?>class="ddzx-nav-act"<?php endif; ?> href="<?php echo U('myorder',array('action'=>'all'));?>">全部</a>-->
    <a <?php if(($setval) == "dfk"): ?>class="ddzx-nav-act"<?php endif; ?> href="<?php echo U('myorder',array('action'=>'dfk'));?>">待付款</a>
    <a <?php if(($setval) == "dfh"): ?>class="ddzx-nav-act"<?php endif; ?> href="<?php echo U('myorder');?>">待发货</a>
    <a <?php if(($setval) == "dsh"): ?>class="ddzx-nav-act"<?php endif; ?> href="<?php echo U('myorder',array('action'=>'dsh'));?>">待收货</a>
    <a <?php if(($setval) == "ywc"): ?>class="ddzx-nav-act"<?php endif; ?> href="<?php echo U('myorder',array('action'=>'ywc'));?>">已完成</a>
</div>



<div class="rol ddzx-con-list">
	
    <div id="dfh" class="active">
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><div class="rol ddzx-list">
        	
        	<div class="rol ddzx-jg">
                <div class="m-box">
                    <div class="lt ">
                        <div>订单编号：<a href="<?php echo U('ordercont',array('ordnum'=$list['ordernum']));?>"><font color="#ff0000"><?php echo ($list["ordernum"]); ?></font></a></div>
                    </div>
                </div>
        	</div>
        	<?php $smje=0; foreach ($list['shoplist'] as $key => $valsop) { ?>	
            <div class="rol cwzx-box2">
                <div class="m-box">
                    <div class="rol cwzx-cp-list ddzx-cp-list">
                    <div class="ddzx-dfh"><?php switch($valsop["tejia"]): case "1": ?>热卖<?php break;?>    
	            	<?php case "2": ?>促销<?php break;?>   
	            	<?php case "3": ?>新品<?php break;?>   
	            	<?php case "4": ?>超值<?php break;?>   
	            	<?php case "5": ?>打折<?php break;?>   
	            	<?php case "6": ?>特价<?php break;?>   
	            	<?php default: endswitch;?></div>
                        <div class="lt cwzx-cp-lt">
                            <img class="gwc_img" src="<?php echo ($valsop["titlepic"]); ?>" alt=""/>
                        </div>
                        <div class="lt cwzx-cp-gt">
                            <div class="cwzx-cp-tit">
                                <span><?php echo (shoptype($valsop["type"])); ?></span><?php echo ($valsop["title"]); ?>
                            </div>
                            <div class="cwzx-cp-jg">
                                <span class="lt ddzx-jk">￥<?php echo ($valsop["price"]); ?></span>
                                <span class="gt">购买数量：<?php echo ($valsop["num"]); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $smje+=$valsop['price']*$valsop['num']; } ?>
            <div class="rol ddzx-jg">
                <div class="m-box">
                	
                	<?php switch($setval): case "dfh": ?><div class="lt jxs-dd">
		                        <div>来源：<?php echo (jxstgly($list["code_jxs"])); ?></div>
		                        <div><?php echo (date('Y-m-d',$list["addtime"])); ?></div>
		                    </div>
		                    <span>合计￥<?php echo ($smje); ?>元</span>
		                    <a class="but_zf bkyj" href="<?php echo U('Distri/myorder',array('action'=>'fqfh','id'=>$list['id']));?>" onclick="if (confirm('您确定要放弃该订单吗？')) return true; else return false;">放弃发货</a>
		                    <a class="but_zf bkyj redcol" href="<?php echo U('Distri/myorder',array('action'=>'ljfh','id'=>$list['id']));?>">立即发货</a><?php break;?>    
                		<?php case "dsh": ?><div class="lt jxs-dd">
		                        <div>来源：<?php echo (jxstgly($list["code_jxs"])); ?>&nbsp;发货：<?php echo (jxstgly($list["fahuofang"])); ?></div>
		                        <div>下单：<?php echo (date('Y-m-d',$list["addtime"])); ?>&nbsp;发货：<?php echo (date('Y-m-d',$list["fhtime"])); ?></div>
		                    </div>
		                    <span>合计￥<?php echo ($smje); ?>元</span><?php break;?>    
                		<?php case "dfk": ?><div class="lt jxs-dd">
		                        <div>来源：<?php echo (jxstgly($list["code_jxs"])); ?></div>
		                        <div>下单：<?php echo (date('Y-m-d',$list["addtime"])); ?></div>
		                    </div>
		                    <span>合计￥<?php echo ($smje); ?>元</span><?php break;?>    
                		<?php case "ywc": ?><div class="lt jxs-dd">
		                        <div>来源：<?php echo (jxstgly($list["code_jxs"])); ?>&nbsp;发货：<?php echo (jxstgly($list["fahuofang"])); ?></div>
		                        <div>下单：<?php echo (date('Y-m-d',$list["addtime"])); ?>&nbsp;发货：<?php echo (date('Y-m-d',$list["fhtime"])); ?></div>
		                    </div>
		                    <span>合计￥<?php echo ($smje); ?>元</span><?php break;?>    
                		<?php default: ?>default<?php endswitch;?>
                	
                    
                    
                    
                    
                    
                </div>
            </div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    
    
</div>


</body>
</html>