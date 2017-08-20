/**
 * Created by DELL on 2017/6/20.
 */
$(function(e){
  $(".cw-nav a").click(function(e){
      $(this).addClass('cw-nav-act').siblings('.cw-nav-act').removeClass('cw-nav-act')
  });
  $(".sc-pro-nav div").click(function(e){
        $(this).addClass('sc-pro-nav-act').siblings('.sc-pro-nav-act').removeClass('sc-pro-nav-act')
  });
  $(".ddzx-nav a").click(function(e){
        $(this).addClass('ddzx-nav-act').siblings('.ddzx-nav-act').removeClass('ddzx-nav-act')
        var h=$(this).attr('href');
      $(h).addClass('active').siblings('.active').removeClass('active')
  });
    $(".gwc-list-lt span").click(function(e){
        if($(this).css('background','url("img/gwc1.png")no-repeat')){
            $(this).css('background','url("img/gwc2.png")no-repeat')
            return false;
        }
        if($(this).css('background','url("img/gwc2.png")no-repeat')){
            $(this).css('background','url("img/gwc1.png")no-repeat')
        }
    });
    $(".gwc-foot-js button").click(function(e){
        $(this).addClass('gwc-foot-but-act').siblings('.gwc-foot-but-act').removeClass('gwc-foot-but-act')
    });
    $(".gwc-gb").click(function(e){
        $('.jrgwc-box').css('display','none')
    })
    $(".ggsum").click(function(e){
        $('.jrgwc-box').css('display','block')
    });

    $(".spxq-nav a").click(function(e){
        $(this).addClass('spxq-nav-act').siblings('.spxq-nav-act').removeClass('spxq-nav-act')
        var h=$(this).attr('href');
        $(h).addClass('active').siblings('.active').removeClass('active')
    });
    $(".xxzx-nav a").click(function(e){
        $(this).addClass('xx-nav-act').siblings('.xx-nav-act').removeClass('xx-nav-act')
        var h=$(this).attr('href');
        $(h).addClass('active').siblings('.active').removeClass('active')
    });
    $(function() {
        $('label').click(function(){

            var radioId = $(this).attr('name');
            $('label').removeAttr('class') && $(this).attr('class', 'checked');
            $('input[type="radio"]').removeAttr('checked') && $('#' + radioId).attr('checked', 'checked');
        });
    });
    
    $('#projian').click(function(e){
    	var dqsum=parseInt($('#prosum').html());
    	if(dqsum-1 > 0){
    		$('#prosum').html(dqsum-1);
    	}
    });
    
    $('#projia').click(function(e){
    	var dqsum=parseInt($('#prosum').html());
    	
    	$('#prosum').html(dqsum+1);
    	
    });
    
    
    $("#ggpjcl").click(function(e){
    	if($('#shoppj').val() != ''){
        	$('.xiugaipj-box').css('display','block')
       	}
    });
    
    $(".xiugaipj-gb").click(function(e){
        $('.xiugaipj-box').css('display','none')
    })
    
    //全选/全不选
	$("#all").click(function(){
		$("[name=items]:checkbox").attr("checked",this.checked);
		jqchk()
	});
	
	$("[name=items]:checkbox").click(function(){
		var flag=true;
		$("[name=items]:checkbox").each(function(){
			if(!this.checked){
				flag=false;
			}
		});
		$("#all").attr("checked",flag);
		jqchk()
	});
	
	/*//购物车加操作
	$(".jiacaoz").click(function(){ 
		var t=$(this).parent().find('input[class*=shop_gwcsum]'); 
		t.val(parseInt(t.val())+1) 
		//setTotal(); 
		var jk=$(this).parent().parent().css({"color":"red","border":"2px solid red"});
	}) 
	//购物车减操作
	$(".jiancaoz").click(function(){ 
		var t=$(this).parent().find('input[class*=shop_gwcsum]'); 
		t.val(parseInt(t.val())-1) 
		if(parseInt(t.val())<=0){ 
			t.val(1); 
		}
		
		
		
		//setTotal(); 
	}) */
	
	


})
