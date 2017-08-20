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
<title>首页</title>
</head>
<body>

<div class="rol ind-top">
    <div class="m-box scsy-top"><a href="<?php echo U('Index/index');?>">
        <img class="lt scsy-logo" src="/Public/index/img/logo.png" alt=""/></a>
        <form method="post" action="<?php echo U('Message/sousuo');?>" name="ssform">
	        <input class="scsy-ss" type="text" placeholder="请输入您要搜索的商品" name="ssgjc" />
	        <input class="scsy-ss-txt" type="submit" value="搜索"/>
        </form>
    </div>
</div>



<!--banner-->
<div class="rol">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner" style="text-align:center">
            <div class="item active">
                <img alt="" src="/Public/index/img/ban.jpg" >
            </div>
            <div class="item">
                <img alt="" src="/Public/index/img/ban.jpg" >
            </div>
            <div class="item">
                <img alt="" src="/Public/index/img/ban.jpg" >
            </div>
        </div>
        <!-- Controls -->
    </div>
</div>
<!--导航-->
<div class="rol ind-nav-box">
    <div class="m-box ind-nav">
        <a href="<?php echo U('Page/about');?>">
            <b class="ind-nav-img1"></b>
            <div>关于我们</div>
        </a>
        <a href="<?php echo U('Mall/index');?>">
            <b class="ind-nav-img2"></b>
            <div>产品中心</div>
        </a>
        <a href="<?php echo U('UserInfo/index');?>">
            <b class="ind-nav-img3"></b>
            <div>会员中心</div>
        </a>
        <a href="<?php echo U('Message/index');?>">
            <b class="ind-nav-img4"></b>
            <div>帮助中心</div>
        </a>
        <a href="<?php echo U('Distri/index');?>">
            <b class="ind-nav-img5"></b>
            <div>经销商中心</div>
        </a>
        <a href="<?php echo U('News/showlists');?>">
            <b class="ind-nav-img6"></b>
            <div>新闻中心</div>
        </a>
    </div>
</div>
<!--新闻中心-->
<div class="rol ind-new">
    <div class="m-box">
        <div class="scsy-rmfl-nav">
            <div class="scsy-rmfl-tit-ch"><b></b><span>新闻资讯</span><b></b></div>
            <div class="scsy-rmfl-tit-en">NEWS CENTER</div>
        </div>
        <div class="ind-new-list">
        	<?php if(is_array($index_news)): foreach($index_news as $key=>$news_item): ?><div class="rol ind-new-list-box">
                <div class="lt ind-new-list-lt">
                    <div class="ind-new-list-tit"><a href="<?php echo U('News/detail', array('id'=>$news_item['id']));?>" class="rol index-news-title"><?php echo ($news_item["title"]); ?></a></div>
                    <div class="ind-new-list-data"><span></span><?php echo (substr($news_item["addtime"],0,10)); ?></div>
                </div>
                <div class="gt">
                    <a href="<?php echo U('News/detail', array('id'=>$news_item['id']));?>" class="lt index-news-lt">
	                    <img class="index_img" src="<?php echo ($news_item["titlepic"]); ?>" class="rol" alt="<?php echo ($news_item["title"]); ?>">
	                </a>
                </div>
            </div><?php endforeach; endif; ?>
            
        </div>
    </div>
</div>

<?php echo W('Base/showFooter');?>



</body>
</html>