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
<title>产品中心</title>
</head>
<body>

	<div class="rol sc-pro-top">
		<form method="post" action="<?php echo U('Message/sousuo');?>" name="ssform">
      <input class="sc-pro-ss-txt" type="submit" value="搜索"/>
      <div class="sc-pro-top-ss">
          <input class="sc-pro-ss-inp" type="search"/>
          <input class="sc-pro-txt" type="text" placeholder="请输入您要搜索的商品" name="ssgjc" />
      </div>
      </form>
  	</div>
  	<input type="hidden" name="actval" id="actval" value="<?php echo ($action); ?>" />
  	<input type="hidden" name="setlst" id="setlst" value="0" />
  	<input type="hidden" name="pageset" id="pageset" value="1" />
  	
  	<input type="hidden" name="mrsetval" id="mrsetval" value="0" />
  	<input type="hidden" name="rmsetval" id="rmsetval" value="0" />
  	<input type="hidden" name="sqsetval" id="sqsetval" value="0" />
  	<input type="hidden" name="xlsetval" id="xlsetval" value="0" />
  	
  	<input type="hidden" name="tpval" id="tpval" value="<?php echo ($tpval); ?>" />
  	
  	<div class="rol sc-pro-nav">
  	  <a href="<?php echo U('prolist');?>"><div <?php if(($action) == "all"): ?>class="sc-pro-nav-act"<?php endif; ?> data-type = "0">默认<span></span></div></a>
      <a href="<?php echo U('prolist',array('action'=>'tj'));?>"><div <?php if(($action) == "tj"): ?>class="sc-pro-nav-act"<?php endif; ?> data-type = "1">推荐<span></span></div></a>
      <a href="<?php echo U('prolist',array('action'=>'xp'));?>"><div <?php if(($action) == "xp"): ?>class="sc-pro-nav-act"<?php endif; ?> data-type = "2">新品<span></span></div></a>
      <a href="<?php echo U('prolist',array('action'=>'tuij'));?>"><div <?php if(($action) == "tuij"): ?>class="sc-pro-nav-act"<?php endif; ?> data-type = "3">推荐<span></span></div></a>
  	</div>




<div class="rol ind-new">
    <div class="zrrx-list" id="porlist">
    	
    	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><a href="<?php echo U('procont',array('id'=>$list['id']));?>">
            <img src="<?php echo ($list["titlepic"]); ?>" alt="<?php echo ($list["title"]); ?>"/>
            <div class="zrrx-list-txt"><span><?php echo ($list["tyname"]); ?></span><?php echo ($list["title"]); ?></div>
            <div class="zrrx-list-bot">
            	
                <div class="lt zrrx-jh pro-jg2">
                	
                	<?php if(in_array(($list["tejia"]), explode(',',"2,5,6"))): if(($list["tejiaprice"]) != "0"): ?><span>￥<?php echo ($list["tejiaprice"]); ?></span>
	               			<s class="rjg">¥ <?php echo ($list["price"]); ?></s>
	               		<?php else: ?>
	               			<span>￥<?php echo ($list["price"]); ?></span><?php endif; ?>
	               	<?php else: ?>
	               		<span>￥<?php echo ($list["price"]); ?></span><?php endif; ?>
                </div>
                <div class="gt zrrx-fx">
                    <span class="zrrx-fx-img"></span>
                    <div class="zrrx-fx-txt">月销<?php echo ($list["salenum"]); ?>笔</div>
                </div>
            </div>
            <?php switch($list["tejia"]): case "1": ?><span class="rm">热卖</span><?php break;?>    
	            	<?php case "2": ?><span class="rm">促销</span><?php break;?>   
	            	<?php case "3": ?><span class="rm">新品</span><?php break;?>   
	            	<?php case "4": ?><span class="rm">超值</span><?php break;?>   
	            	<?php case "5": ?><span class="rm">打折</span><?php break;?>   
	            	<?php case "6": ?><span class="rm">特价</span><?php break;?>    
            	<?php default: endswitch;?>
            
        </a><?php endforeach; endif; else: echo "" ;endif; ?>
        
    </div>
    <div onclick="click_cxtj(2)" class="gengduo_pro"><span id='sky_next'>查看更多商品</span></div>
    
</div>

<script>
	var $body = $('body');
document.title = '<?php echo ($cptitle); ?>';
var $iframe = $('<iframe src="/favicon.ico"></iframe>');
$iframe.on('load',function() {
  setTimeout(function() {
      $iframe.off('load').remove();
  }, 0);
}).appendTo($body);
</script>


<?php echo W('Base/showFooter');?>


</body>
</html>