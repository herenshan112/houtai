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
<title>商品详情</title>
</head>
<body>



<div class="rol ">
    <div class="rol sp-top">
        <div class="m-box">
            <div class="lt spxq-nav">
                <a class="spxq-nav-act" href="#sp">商品</a>
                <a href="#xq">详情</a>
            </div>
            <a href="<?php echo U('Mall/index');?>" ><div class="fx gt"></div></a>
        </div>
    </div>
    <div class="spxq-box-list">
        <div id="sp" class="active">
            <div class="rol spxq-ban">
                <div class="m-box">
                    <!--效果html开始-->
                    <div class="wraper">
                        <header id="scroll_pic_view" class="scroll_pic_view" style="overflow: hidden; ">
                            <div id="scroll_pic_view_div" style="width: 3840px; -webkit-transition: -webkit-transform 0ms cubic-bezier(0.33, 0.66, 0.66, 1); -webkit-transform-origin: 0px 0px; -webkit-transform: translate(0px, 0px) translateZ(0px); ">
                                <ul id="scroll_pic_view_ul">
                                	<li style="width: 710px; "> <a href="#"> <img src="<?php echo ($list["titlepic"]); ?>"> </a> </li>
                                	<?php $ltimg=explode(',',$list['imgary']); foreach($ltimg as $imal){ if($imal != ''){ echo '<li style="width: 710px; "> <a onclick="return false;"> <img src="/upload/images/'.$imal.'" > </a> </li>'; } } ?>
                                    
                                </ul>
                            </div>
                            <div>
                                <ol id="scroll_pic_nav" class="scroll_pic_nav">
                                    <script>
                                        (function(d, $){
                                            var scrollPicView = d.getElementById("scroll_pic_view"),
                                                    scrollPicViewDiv = d.getElementById("scroll_pic_view_div"),
                                                    lis = scrollPicViewDiv.querySelectorAll("li"),
                                                    w = scrollPicView.offsetWidth,
                                                    len = lis.length;
                                            for(var i=0; i<len; i++){
                                                lis[i].style.width = w+"px";
                                                if(i == len-1){
                                                    scrollPicViewDiv.style.width = w * len + "px";
                                                }
                                            }

                                            var scroll_pic_view = new iScroll('scroll_pic_view', {
                                                snap: true,
                                                momentum: false,
                                                hScrollbar: false,
                                                useTransition: true,
                                                onScrollEnd: function() {
                                                    $("#scroll_pic_nav li").removeClass("on").eq(this.currPageX).addClass("on");
                                                    //$("#scroll_pic_nav li.on").prev().addClass("left");
                                                    //$("#scroll_pic_nav li.on").next().removeClass("left");

                                                    var  list=$("#scroll_pic_nav li");
                                                    for(var k=0;k<list.length;k++){
                                                        if(k<this.currPageX)
                                                            $(list[k]).addClass("left");
                                                        else
                                                            $(list[k]).removeClass("left");
                                                    }
                                                }
                                            });
                                            //
                                            
                                            var nav_lis = new Array(lis.length);
                                            
                                            d.write('<li class="on"><span>1</span></li>');
                                            for(var i=1; i<nav_lis.length; i++){
                                                d.write("<li><span>"+(i+1)+"</span></li>");
                                            }
                                            d.write("<em>/"+nav_lis.length+"</em>");
                                        })(document, $);
                                    </script>
                                  
                                </ol>
                            </div>
                        </header>
                    </div>
                    <!--效果html结束-->
                    <div class="spxq-tit">
	                     <?php echo ($list["ptel"]); ?>
                    </div>
                </div>
            </div>
            <div class="rol spxq-jh">
                <div class="m-box">
                	
                	<?php if(in_array(($list["tejia"]), explode(',',"2,5,6"))): if(($list["tejiaprice"]) != "0"): ?><span> ¥</span><?php echo ($list["tejiaprice"]); ?>
	               			<s class="rjg">原价：¥ <?php echo ($list["price"]); ?></s>
	               		<?php else: ?>
	               			<span> ¥</span><?php echo ($list["price"]); endif; ?>
	               	<?php else: ?>
	               		<span> ¥</span><?php echo ($list["price"]); endif; ?>
                </div>
            </div>
            <div class="rol spxq-sl">
                <div class="m-box spxq-ggsl ggsum">
                    <span class="lt">请选择商品规格及数量</span>
                    <span class="gt rjt"></span>
                </div>
            </div>
             <?php if(($list["parts"]) != "0"): ?><div class="rol spxqlxb">
        <div class="m-box spxq-ggsl">
            <div>选购配件套餐更优惠，为您带来更丰富的应用搭配体验~</div>
            <div>
                <span class="lt">配：</span>
                <div class="lt pj-img">
                	<?php if(is_array($pjls)): $i = 0; $__LIST__ = $pjls;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pjls): $mod = ($i % 2 );++$i;?><img id="pjtp<?php echo ($pjls["id"]); ?>" src="<?php echo ($pjls["titlepic"]); ?>" alt="<?php echo ($pjls["title"]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <span class="gt rjt" id="ggpjcl"></span>
            </div>
        </div>
    </div><?php endif; ?>
        </div>
        <div id="xq">
            <div class="rol">
                <div class="m-box cpxq-img">
                    <img src="<?php echo ($list["titlepic"]); ?>" alt="<?php echo ($list["title"]); ?>"/>
                    <div class="cpcs">产品参数</div>
                    <div class="spxq-xq-tit"><?php echo (htmlspecialchars_decode($list["content"])); ?></div>
                </div>
            </div>
        </div>
    </div>
    
    
    <input type="hidden" name="shoppj" id="shoppj" value="<?php echo ($list["parts"]); ?>" />
    <input type="hidden" name="shopid" id="shopid" value="<?php echo ($list["pid"]); ?>" />
    <div class="rol spxq-bot-box">
        <div class="lt spxq-bot-lt">
            <div class="spxq-bot" onclick="jrqxsc(<?php echo ($uidv); ?>,<?php echo ($list["pid"]); ?>)" id="scdivcnt">
            	<?php if(($sctbxs) != "0"): ?><b class="spxq-jrgwcysc"></b>
            		<div>取消收藏</div>
            	<?php else: ?>
            		<b class="spxq-jrgwc"></b>
            		<div>加入收藏</div><?php endif; ?>
            </div>
            <b></b>
            <div class="spxq-bot">
                <a href="<?php echo U('Orders/newsgwc');?>">
                	<b class="spxq-gwc"></b>
                	<div>购物车</div>
                </a>
            </div>
        </div>
        <div class="gt spxq-bot-gwc">
            <button class="jrgwc" onclick="jrgwccl(<?php echo ($uidv); ?>)">加入购物车</button>
            <button class="ljgm" onclick="ljgmcljs(<?php echo ($uidv); ?>)">立即购买</button>
        </div>
    </div>
</div>
<div class=""></div>
<!--更改数量和规格-->
<div class="jrgwc-box">
    <div class="spxq-box2 rol">
        <div class="rol jrgwc">
            <div class="m-box">
                <div class="lt">
                    <img src="<?php echo ($list["titlepic"]); ?>" alt=""/>
                </div>
                <div class="lt gwc-lt2">
                    <div class="gwc-jg">
                    	
                    	<?php if(in_array(($list["tejia"]), explode(',',"2,5,6"))): if(($list["tejiaprice"]) != "0"): ?><span> ¥</span><?php echo ($list["tejiaprice"]); ?>
	               			<s class="rjg">原价：¥ <?php echo ($list["price"]); ?></s>
	               		<?php else: ?>
	               			<span> ¥</span><?php echo ($list["price"]); endif; ?>
	               	<?php else: ?>
	               		<span> ¥</span><?php echo ($list["price"]); endif; ?>
                    	
                    </div>
                    <div class="gwc-spbh">商品编号: <?php echo ($list["productnum"]); ?></div>
                </div>
            </div>
        </div>
        <div class="rol">
            <div class="m-box">
                <div class="rol gg">规格</div>
                <div class="rol ggcs">
                    <span><?php echo (shopguige($list["spec"])); ?></span>
                </div>
            </div>
        </div>
        <div class="rol">
            <div class="m-box sl-box">
                <div class="lt sl">数量</div>
                <div class="gt">
                    <table class="sl-tab">
                        <tr>
                            <td id="projian">-</td>
                            <td id="prosum">1</td>
                            <td id="projia">+</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="gwc-gb gt">×</div>
    </div>


</div>













<!--修改配件-->
<div class="xiugaipj-box">
    <div class="spxq-box2 rol">
        <div class="rol jrgwc">
            <div class="m-box">
                <div class="lt">
                    <img src="<?php echo ($list["titlepic"]); ?>" alt=""/>
                </div>
                <div class="lt gwc-lt2">
                    <div class="gwc-jg">
                    	
                    	<?php if(in_array(($list["tejia"]), explode(',',"2,5,6"))): if(($list["tejiaprice"]) != "0"): ?><span> ¥</span><?php echo ($list["tejiaprice"]); ?>
	               			<s class="rjg">原价：¥ <?php echo ($list["price"]); ?></s>
	               		<?php else: ?>
	               			<span> ¥</span><?php echo ($list["price"]); endif; ?>
	               	<?php else: ?>
	               		<span> ¥</span><?php echo ($list["price"]); endif; ?>
                    	
                    </div>
                    <div class="gwc-spbh">商品编号: <?php echo ($list["productnum"]); ?></div>
                </div>
            </div>
        </div>
        
        <div class="rol">
        	
        	<?php if(is_array($pjlsfb)): $i = 0; $__LIST__ = $pjlsfb;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pjlsfb): $mod = ($i % 2 );++$i;?><div class="pj_lb" id="pjlbtk_<?php echo ($pjlsfb["id"]); ?>">
            	<img src="<?php echo ($pjlsfb["titlepic"]); ?>">
            	<div class="pj_lb_label">
            		<i><?php echo ($pjlsfb["title"]); ?></i>
            		<div class="gwc-jg">
                    	
                    	<?php if(in_array(($pjlsfb["tejia"]), explode(',',"2,5,6"))): if(($pjlsfb["tejiaprice"]) != "0"): ?><span> ¥</span><?php echo ($pjlsfb["tejiaprice"]); ?>
	               			<s class="rjg">原价：¥ <?php echo ($pjlsfb["price"]); ?></s>
	               		<?php else: ?>
	               			<span> ¥</span><?php echo ($pjlsfb["price"]); endif; ?>
	               	<?php else: ?>
	               		<span> ¥</span><?php echo ($pjlsfb["price"]); endif; ?>
                    	
                    </div>
                    <div class="gwc-spbh">商品编号: <?php echo ($pjlsfb["productnum"]); ?><em onclick="quxpeij(<?php echo ($pjlsfb["id"]); ?>)">取消</em></div>
            	</div>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
            
        </div>
        <div class="xiugaipj-gb gt">×</div>
    </div>


</div>



































</body>
</html>