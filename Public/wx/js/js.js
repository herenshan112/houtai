$(function(){
    var num1=$(".pro-num-input").val();
    num1=parseInt(num1);
    $(".pro-num-jian").click(function(){
        if(num1>1){
            --num1;
            $(".pro-num-input").val(num1);
        }
    });
    $(".pro-num-jia").click(function(){
        ++num1;
        $(".pro-num-input").val(num1);

    });
    $(".buy-all").click(function(){
        if($(this).hasClass("on")){
            $(this).removeClass("on");
            $(".buy-one").removeClass("on");
        }else{
            $(this).addClass("on");
            $(".buy-one").addClass("on");
        }
    });
    $(".buy-one").click(function(){
        if($(this).hasClass("on")){
            $(this).removeClass("on");
            $(".buy-all").removeClass("on");
        }else{
            $(this).addClass("on");
        }
    });
    /*$(".buy-d").click(function(){
       $(this).closest(".buy-box2").hide();
    });*/
    /*$(".pj-star .star").click(function(){
        var num=parseInt($(this).attr("data-star"));
        var num1=1;
        $(".pj-star .star").attr("src","/Public/wx/img/star-off.png");
        $(".pj-star .star").each(function(){
            if(num>=num1){
                $(this).attr("src","/Public/wx/img/star-on.png");
                ++num1;
            }
        });
    });
    $(".pj-star2 .star").click(function(){
        var num=parseInt($(this).attr("data-star"));
        var num1=1;
        $(".pj-star2 .star").attr("src","/Public/wx/img/star-off.png");
        $(".pj-star2 .star").each(function(){
            if(num>=num1){
                $(this).attr("src","/Public/wx/img/star-on.png");
                ++num1;
            }
        });
    });
    $(".pj-star3 .star").click(function(){
        var num=parseInt($(this).attr("data-star"));
        var num1=1;
        $(".pj-star3 .star").attr("src","/Public/wx/img/star-off.png");
        $(".pj-star3 .star").each(function(){
            if(num>=num1){
                $(this).attr("src","/Public/wx/img/star-on.png");
                ++num1;
            }
        });
    });*/
    /*$(".qy-btn").click(function(){
        $(".qy-btn").attr("src","/Public/wx/img/qy-off.png");
       $(this).attr("src","/Public/wx/img/qy-on.png");
    });*/

    $("#message_content").focusin(function(event) {
        message_content_val = $(this).val();
        if (message_content_val=='简单描述您的需求') {
            $(this).val('')
        }
    });

    $("#message_content").focusout(function(event) {
        message_content_val = $(this).val();
        if (message_content_val=='') {
            $(this).val('简单描述您的需求')
        }
    });
});