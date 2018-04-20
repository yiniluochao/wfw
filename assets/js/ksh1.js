
var wuindex;
var bianliang;
var bianliang2;
var bianliang3;
var haveindex;
var diyizhong;
var dierzhong;
var quanjubiangliang;
var JSonstring;
/*价格提示*/
/*加载第三行*/
    if($(".inputz3>.panel1>.cpmtem").html()==false){
        $.ajax({
            url : "/datashow/getCondition_data",
            type : "POST",
            data : "data=" + "create_place_province",
            dataType : "json",
            success : function(data) {
                //console.log(JSON.stringify(data));
                $(".inputz3>.panel1>.cpmtem").html("");
                for(i=0;i<data.length;i++){
                    $(".inputz3>.panel1>.cpmtem").html($(".inputz3>.panel1>.cpmtem").html()+
                        "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox  name="+data[i].name+"  value="+data[i].id +">"+data[i].name+"</div>"
                    )
                }
            }
        });
    }
    if($(".inputz5>.panel1>.cpmtem").html()==false){
        $.ajax({
            url : "/datashow/getCondition_data",
            type : "POST",
            data : "data=" + "create_place_province",
            dataType : "json",
            success : function(data) {
                //console.log(JSON.stringify(data));
                $(".inputz5>.panel1>.cpmtem").html("")
                for(i=0;i<data.length;i++){
                    $(".inputz5>.panel1>.cpmtem").html($(".inputz5>.panel1>.cpmtem").html()+
                        "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox  name="+data[i].name+"  value="+data[i].id +">"+data[i].name+"</div>"
                    )
                }
            }
        });
    }
    if($(".inputz7>.panel1>.cpmtem").html()==false){
    $.ajax({
        url : "/datashow/getCondition_data",
        type : "POST",
        data : "data=" + "product",
        dataType : "json",
        success : function(data) {
            //console.log(JSON.stringify(data));
            $(".inputz7>.panel1>.cpmtem").html("")
            for(i=0;i<data.length;i++){
                $(".inputz7>.panel1>.cpmtem").html($(".inputz7>.panel1>.cpmtem").html()+
                    "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox  name="+data[i].name+"  value="+data[i].id +">"+data[i].name+"</div>"
                )
            }
        }
    });
}
    if($(".inputz8>.panel1>.cpmtem").html()==false){
        $(".inputz8>.panel1>.cpmtem").html("")
                for(i=2000;i<=2020;i++){
                    $(".inputz8>.panel1>.cpmtem").html($(".inputz8>.panel1>.cpmtem").html()+
                        "<div class=xuanxiang onclick=dianji(this) ><input  type=checkbox  name="+i+"年"+"  value="+i+">"+i+"年</div>"
                    )
                }
    }
/*没确定的打开关闭*/
    $(".dian0").click(function() {
        var index = $('.dian0').index(this);
      if( $(".inputz0").eq(index).css("height")=="200px"){
          $(".inputz0").eq(index).css( "height","30px"  )
          $(".pane").eq(index).css("display","none");
      }else {
          $(".inputz0").css( "height","30px");
          $(".pane").css("display","none");
          $(".inputz").css("height", "30px");
          $(".panel1").css("display", "none");
          $(".inputz0").eq(index).css("height","200px");
          $(".pane").eq(index).css("display","block");
          $(".hint").css({
              "position":"absolute","left":"100%","top":"0%"
          });
          $(".hint_move").css({
              "position":"absolute","left":"97%","top":"75%"
          });
      }
    });
$(".xiala0").click(function() {
    var index = $('.xiala0').index(this);
    if( $(".inputz0").eq(index).css("height")=="200px"){
        $(".inputz0").eq(index).css( "height","30px"  )
        $(".pane").eq(index).css("display","none");
    }else {
        $(".inputz0").css( "height","30px");
        $(".pane").css("display","none");
        $(".inputz").css("height", "30px");
        $(".panel1").css("display", "none");
        $(".inputz0").eq(index).css("height","200px");
        $(".pane").eq(index).css("display","block");
        $(".hint").css({
            "position":"absolute","left":"100%","top":"0%"
        });
        $(".hint_move").css({
            "position":"absolute","left":"97%","top":"75%"
        });
    }
});
/*有确定的打开关闭*/
$(".dian").click(function(){
        var index=  $('.dian').index(this);
        if( $(".inputz").eq(index).css("height")=="240px") {
            $(".inputz").eq(index).css("height", "30px");
            $(".panel1").eq(index).css("display", "none")
        }
        else {
            $(".inputz0").css( "height","30px");
            $(".pane").css("display","none");
            $(".inputz").css("height", "30px");
            $(".panel1").css("display", "none");
            $(".inputz").eq(index).css("height","240px");
            $(".panel1").eq(index).css("display", "block");
            $(".hint").css({
                "position":"absolute","left":"100%","top":"0%"
            });
            $(".hint_move").css({
                "position":"absolute","left":"97%","top":"75%"
            });
        }
    });
    $(".xiala").click(function(){
    var index=  $('.xiala').index(this);
    if( $(".inputz").eq(index).css("height")=="240px") {
        $(".inputz").eq(index).css("height", "30px");
        $(".panel1").eq(index).css("display", "none")
    }
    else {
        $(".inputz0").css( "height","30px");
        $(".pane").css("display","none");
        $(".inputz").css("height", "30px");
        $(".panel1").css("display", "none");
        $(".inputz").eq(index).css("height","240px");
        $(".panel1").eq(index).css("display", "block")
        $(".hint").css({
            "position":"absolute","left":"100%","top":"0%"
        });
        $(".hint_move").css({
            "position":"absolute","left":"97%","top":"75%"
        });
    }
});
///*点击效果*/
function dianji(obj){
    var index=  $(".xuanxiang").index(obj);
    //var abb = $(".xuanxiang").eq(index).children("input");
    $(obj).find(':checkbox').click();
}
/*初始化第2行的方法*/
function csh_erhang(){
    $(".Custom_price").css("display","none");
    $(".erzui").css("display","none");
    $(".erzhong").css("display","none");
    $(".xiala0").eq(1).html("请选择细分方式");
}
/*没有确定按钮的返回值*/
$(".inputz0").click(function(){
    wuindex=$(".inputz0").index(this);
})
/*选择分析对象或者细分明细*/
function analyze(obj){
    $(".inputz0").css( "height","30px" );
    $(".pane").css("display","none");

    $(".xiala0").eq(wuindex).html(obj.innerHTML);
    if(wuindex==0){
        var qu_string= $.trim(obj.innerHTML);
        /*返回第一行中间的值*/
        var qu_string1= $.trim(obj.id);
        //alert(qu_string1);
        $.ajax({
            url : "/datashow/getCondition_data",
            type : "POST",
            data : "data=" + qu_string1,
            dataType : "json",
            success : function(data) {
                //console.log(JSON.stringify(data));
                $(".yizhong>.panel1>.cpmtem").html("")
                for(i=0;i<data.length;i++){
                 $(".yizhong>.panel1>.cpmtem").html($(".yizhong>.panel1>.cpmtem").html()+
                      "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox  name="+data[i].name+"  value="+data[i].id +">"+data[i].name+"</div>"
                     );
                }
            }
        });
        if(qu_string=="产品"){
            $(".yizhong").css("display","block");
            $(".yizhong>div:nth-child(1)").html("请选择产品");
            /*给第二行传值*/
            $(".pane").eq(1).html("<div></div>"+
                "<div onclick=analyze(this)>产地</div>"+
                "<div onclick=analyze(this)>销售地</div>"+
                "<div onclick=analyze(this)>时间</div>"+
                "<div onclick=analyze(this)>售价</div> "
            )
           //$(".inputz7").css("display","none");
           $(".inputz7>.dian").css({
               "background":"#999","position": "absolute",
               "left":"84%",
               "z-index":"-1"
           });
            $(".inputz7>.xiala").css({
                "background":"#999","position": "absolute",
                "z-index":"-1"
            });
            $(".inputz7>.dian").css({
                "background":"#999","position": "absolute",
                "left":"84%",
                "z-index":"-1"
            });
            $(".inputz3>.xiala").css({
                "background":"#6fb3e0","position": "absolute",
                "z-index":"10"
            });
            $(".inputz3>.dian").css({
                "background":"#6fb3e0","position": "absolute",
                "left":"84%",
                "z-index":"10"
            });
            $(".inputz5>.xiala").css({
                "background":"#6fb3e0","position": "absolute",
                "z-index":"10"
            });
            $(".inputz5>.dian").css({
                "background":"#6fb3e0","position": "absolute",
                "left":"84%",
                "z-index":"10"
            });

            /*在选了第2行又去选第一行的时候——初始化第二行*/
            csh_erhang();
        }
        else if(qu_string=="产地"){
            $(".yizhong").css("display","block");
            $(".yizhong>div:nth-child(1)").html("请选择生产省份");
            /*给第二行传值*/
           $(".pane").eq(1).html("<div></div>"+
                "<div onclick=analyze(this)>销售地</div>"+
                "<div onclick=analyze(this)>时间</div>"+
                "<div onclick=analyze(this)>售价</div> "
            )
            //$(".inputz3").css("display","none");
            //$(".inputz4").css("display", "none");
            $(".inputz3>.dian").css({
                "background":"#999","position": "absolute",
                "left":"84%",
                "z-index":"-1"
            });
            $(".inputz3>.xiala").css({
                "background":"#999","position": "absolute",
                "z-index":"-1"
            });
            $(".inputz5>.xiala").css({
                "background":"#6fb3e0","position": "absolute",
                "z-index":"10"
            });
            $(".inputz5>.dian").css({
                "background":"#6fb3e0","position": "absolute",
                "left":"84%",
                "z-index":"10"
            });
            $(".inputz7>.xiala").css({
                "background":"#6fb3e0","position": "absolute",
                "z-index":"10"
            });
            $(".inputz7>.dian").css({
                "background":"#6fb3e0","position": "absolute",
                "left":"84%",
                "z-index":"10"
            });

            csh_erhang();
         }else{
            $(".yizhong").css("display","block");
            $(".yizhong>div:nth-child(1)").html("请选择销售省份");
            /*给第二行传值*/
            $(".pane").eq(1).html("<div></div>"+
                "<div onclick=analyze(this)>产地</div>"+
                "<div onclick=analyze(this)>时间</div>"+
                "<div onclick=analyze(this)>售价</div> "
            )
            //$(".inputz5").css("display","none");
            //$(".inputz5").css("display", "none");
            $(".inputz5>.dian").css({
                "background":"#999","position": "absolute",
                "left":"84%",
                "z-index":"-1"
            });
            $(".inputz5>.xiala").css({
                "background":"#999","position": "absolute",
                "z-index":"-1"
            });
            $(".inputz3>.xiala").css({
                "background":"#6fb3e0","position": "absolute",
                "z-index":"10"
            });
            $(".inputz3>.dian").css({
                "background":"#6fb3e0","position": "absolute",
                "left":"84%",
                "z-index":"10"
            });
            $(".inputz7>.xiala").css({
                "background":"#6fb3e0","position": "absolute",
                "z-index":"10"
            });
            $(".inputz7>.dian").css({
                "background":"#6fb3e0","position": "absolute",
                "left":"84%",
                "z-index":"10"
            });
            csh_erhang();
        }
      }
    /*细分明细*/
    else if(wuindex==1){
            var qu_string= $.trim(obj.innerHTML);
            var san_key1=$(".xiala0").eq(0).html();
            if(qu_string=="产地"){
                $(".max_price").css("display", "block");
                $(".l_yi").css("display", "block");
                $(".mix_price").css("display", "block");
                //$(".inputz3").css("display","none");
                //$(".inputz4").css("display", "none");
                //$(".inputz6").css("display", "none");
                if(san_key1=="产品"){
                    $(".inputz7>.dian").css({
                        "background":"#999","position": "absolute",
                        "left":"84%",
                        "z-index":"-1"
                    });
                    $(".inputz7>.xiala").css({
                        "background":"#999","position": "absolute",
                        "z-index":"-1"
                    });
                    $(".inputz5>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz5>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz8>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz8>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz9>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz9>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                }else if(san_key1=="销售地"){
                    $(".inputz5>.dian").css({
                        "background":"#999","position": "absolute",
                        "left":"84%",
                        "z-index":"-1"
                    });
                    $(".inputz5>.xiala").css({
                        "background":"#999","position": "absolute",
                        "z-index":"-1"
                    });
                    $(".inputz7>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz7>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz8>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz8>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz9>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz9>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                }
                $(".inputz3>.dian").css({
                    "background":"#999","position": "absolute",
                    "left":"84%",
                    "z-index":"-1"
                });
                $(".inputz3>.xiala").css({
                    "background":"#999","position": "absolute",
                    "z-index":"-1"
                });
                $(".Custom_price").css("display","none");
                $(".erzhong").css("display","block");
                $(".erzhong>div:nth-child(1)").html("请选择生产省份");
                $.ajax({
                    url : "/datashow/getCondition_data",
                    type : "POST",
                    data : "data="+"sell_place_province",
                    dataType : "json",
                    success : function(data) {
                        //console.log(JSON.stringify(data));
                        $(".erzhong>.panel1>.cpmtem").html("");
                        for(i=0;i<data.length;i++){
                            $(".erzhong>.panel1>.cpmtem").html($(".erzhong>.panel1>.cpmtem").html()+
                                "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox  name="+data[i].name+"  value="+data[i].id +">"+data[i].name+"</div>"
                            );
                        }
                    }
                });
            }
            else if(qu_string=="销售地"){
                $(".max_price").css("display", "block");
                $(".l_yi").css("display", "block");
                $(".mix_price").css("display", "block");
                //$(".inputz5").css("display","none");
                //$(".inputz6").css("display", "none");
                //$(".inputz4").css("display", "none");
                if(san_key1=="产品"){
                $(".inputz7>.dian").css({
                    "background":"#999","position": "absolute",
                    "left":"84%",
                    "z-index":"-1"
                });
                $(".inputz7>.xiala").css({
                    "background":"#999","position": "absolute",
                    "z-index":"-1"
                });
                    $(".inputz3>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz3>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz8>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz8>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz9>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz9>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
            }else if(san_key1=="产地"){
                $(".inputz3>.dian").css({
                    "background":"#999","position": "absolute",
                    "left":"84%",
                    "z-index":"-1"
                });
                $(".inputz3>.xiala").css({
                    "background":"#999","position": "absolute",
                    "z-index":"-1"
                });
                    $(".inputz7>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz7>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz8>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz8>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz9>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz9>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
            }
                $(".inputz5>.dian").css({
                    "background":"#999","position": "absolute",
                    "left":"84%",
                    "z-index":"-1"
                });
                $(".inputz5>.xiala").css({
                    "background":"#999","position": "absolute",
                    "z-index":"-1"
                });


                $(".Custom_price").css("display","none");
                $(".erzhong").css("display","block");
                $(".erzhong>div:nth-child(1)").html("请选择销售省份");
                /*返回第二行中间的值*/
                $.ajax({
                    url : "/datashow/getCondition_data",
                    type : "POST",
                    data : "data="+"sell_place_province",
                    dataType : "json",
                    success : function(data) {
                        //console.log(JSON.stringify(data));
                        $(".erzhong>.panel1>.cpmtem").html("");
                        for(i=0;i<data.length;i++){
                            $(".erzhong>.panel1>.cpmtem").html($(".erzhong>.panel1>.cpmtem").html()+
                                "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox  name="+data[i].name+"  value="+data[i].id +">"+data[i].name+"</div>"
                            );
                        }
                    }
                });
            }
            else if(qu_string=="时间"){
                $(".max_price").css("display", "block");
                $(".l_yi").css("display", "block");
                $(".mix_price").css("display", "block");
                $(".Custom_price>input").eq(0).val("");
                $(".Custom_price>input").eq(1).val("");
                $(".Custom_price>input").eq(2).val("");
                $(".Custom_price>input").eq(3).val("");
                $(".Custom_price>input").eq(4).val("");
                $(".Custom_price>input").eq(5).val("");
                $(".Custom_price>input").eq(6).val("");
               if(san_key1=="产品"){
                    $(".inputz7>.dian").css({
                        "background":"#999","position": "absolute",
                        "left":"84%",
                        "z-index":"-1"
                    });
                    $(".inputz7>.xiala").css({
                        "background":"#999","position": "absolute",
                        "z-index":"-1"
                    });
                    $(".inputz3>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz3>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz5>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz5>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                }else if(san_key1=="产地"){
                    $(".inputz3>.dian").css({
                        "background":"#999","position": "absolute",
                        "left":"84%",
                        "z-index":"-1"
                    });
                    $(".inputz3>.xiala").css({
                        "background":"#999","position": "absolute",
                        "z-index":"-1"
                    });
                    $(".inputz5>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz5>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz7>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz7>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                }else if(san_key1=="销售地"){
                    $(".inputz5>.dian").css({
                        "background":"#999","position": "absolute",
                        "left":"84%",
                        "z-index":"-1"
                    });
                    $(".inputz5>.xiala").css({
                        "background":"#999","position": "absolute",
                        "z-index":"-1"
                    });
                    $(".inputz3>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz3>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz7>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz7>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                }
                $(".inputz8>.dian").css({
                    "background":"#999","position": "absolute",
                    "left":"84%",
                    "z-index":"-1"
                });
                $(".inputz8>.xiala").css({
                    "background":"#999","position": "absolute",
                    "z-index":"-1"
                });
                $(".inputz9>.dian").css({
                    "background":"#999","position": "absolute",
                    "left":"84%",
                    "z-index":"-1"
                });
                $(".inputz9>.xiala").css({
                    "background":"#999","position": "absolute",
                    "z-index":"-1"
                });
                $(".Custom_price").css("display","none");
                $(".erzhong").css("display","block");
                $(".erzhong>div:nth-child(1)").html("请选择年份");
                $(".erzhong>.panel1>.cpmtem").html("");
                for(i=2010;i<=2020;i++){
                    $(".erzhong>.panel1>.cpmtem").html($(".erzhong>.panel1>.cpmtem").html()+
                        "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox  name="+i+"年"+"  value="+i +">"+i+"年</div>"
                   )
                }
            }
            else{
                $(".erzui").css("display","none");
                $(".erzhong").css("display","none");
                $(".Custom_price").css("display","block");
                $(".l_yi").css("display", "none");
                $(".max_price").css("display", "none");
                $(".mix_price").css("display", "none");
                if(san_key1=="产品"){
                    $(".inputz7>.dian").css({
                        "background":"#999","position": "absolute",
                        "left":"84%",
                        "z-index":"-1"
                    });
                    $(".inputz7>.xiala").css({
                        "background":"#999","position": "absolute",
                        "z-index":"-1"
                    });
                    $(".inputz3>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz3>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz5>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz5>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz8>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz8>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz9>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz9>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                }else if(san_key1=="产地"){
                    $(".inputz3>.dian").css({
                        "background":"#999","position": "absolute",
                        "left":"84%",
                        "z-index":"-1"
                    });
                    $(".inputz3>.xiala").css({
                        "background":"#999","position": "absolute",
                        "z-index":"-1"
                    });

                    $(".inputz5>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz5>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz7>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz7>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz8>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz8>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz9>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz9>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                }else if(san_key1=="销售地"){
                    $(".inputz5>.dian").css({
                        "background":"#999","position": "absolute",
                        "left":"84%",
                        "z-index":"-1"
                    });
                    $(".inputz5>.xiala").css({
                        "background":"#999","position": "absolute",
                        "z-index":"-1"
                    });
                    $(".inputz3>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz3>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });

                    $(".inputz7>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz7>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz8>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz8>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                    $(".inputz9>.xiala").css({
                        "background":"#6fb3e0","position": "absolute",
                        "z-index":"10"
                    });
                    $(".inputz9>.dian").css({
                        "background":"#6fb3e0","position": "absolute",
                        "left":"84%",
                        "z-index":"10"
                    });
                }


            }
    }
 }
/*产品数量按钮*/
//function product(){
//    $(".Sweep").css({
//        "height": "30px",
//        "line-height": "30px",
//        "color": "#444",
//        "margin-top": "10px",
//        "transition":"all 0.1s"
//    });
//    $(".product_quantity").css({
//        "height": "40px",
//        "line-height": "40px",
//        "color": "#00a0e9",
//        "margin-top": "0px",
//        "transition":"all 0.1s"
//    })
//}
/*扫码量按钮*/
//function Sweep1(){
//    $(".product_quantity").css({
//        "height": "30px",
//        "line-height":"30px",
//        "color": "#444",
//        "margin-top": "10px",
//        "transition":"all 0.1s"
//    })
//    $(".Sweep").css({
//        "height":"40px",
//        "line-height":"40px",
//        "color": "#00a0e9",
//        "margin-top": "0px",
//        "transition":"all 0.1s"
//    });
//}
/*有确定按钮的返回值1*/
$(".yizhong").click(function(){
    wuindex=0;
});
$(".erzhong").click(function(){
    wuindex=1;
});
$(".inputz").click(function(){
    haveindex=$(".inputz").index(this);
})
/*有确定按钮的返回值2*/
var  keyvilue=new Array();
function quere() {
    $(".inputz").css("height", "30px");
    $(".panel1").css("display", "none");
    $(".pane").eq(1).css("height", "140px");
    /*初始化数组*/
     keyvilue = [];
    /*初始化html*/
    $(".ye").eq(haveindex).html("");
    /*获取孙子*/
    var list0 = $(".cpmtem").eq(haveindex);
    var list = list0.children("div").children("input")
    //console.log("list"+list.length)
    /*添加到HTML*/
    for (i = 0; i < list.length; i++) {
        if (list[i].checked == true) {
            //console.log(list[i].name);
            $(".ye").eq(haveindex).html($(".ye").eq(haveindex).html() + list[i].name + ",")
            //console.log($(".ye").eq(haveindex).html()+"内容");
            keyvilue.push(list[i].name);
        }
    }    ;

    /*去除重复数组*/
    var new_keyvilue = [];
    for (var i = 0; i < keyvilue.length; i++) {
        var items = keyvilue[i];
        //判断元素是否存在于new_arr中，如果不存在则插入到new_arr的最后
        if ($.inArray(items, new_keyvilue) == -1) {
            new_keyvilue.push(items);
        }
    }
    /*多余2项的干掉*/
    if (new_keyvilue.length > 1) {
        /*排除第一行第二行最后一个*/
        if (haveindex == 0) {
            $(".yizui").css("display", "none");
        }
        if (haveindex == 2) {
            $(".erzui").css("display", "none");
        }
        if (haveindex == 4) {
            $(".inputz4").css("display", "none");
        }
        if (haveindex == 6) {
            $(".inputz6").css("display", "none");
        }
        if (haveindex == 9) {
           $(".inputz9").css("display", "none");
        }
    } else {

        if (haveindex == 4) {
                //$(".inputz4").css("display", "block");
            /*获取孙子*/
            var qu_string5;
            var list0 = $(".inputz3>.panel1");
            var list = list0.children("div").children("input")
            //console.log("list"+list.length)
            //var list = $(".panel1>div>input")
            /*添加到HTML*/
            for (i = 0; i < list.length; i++) {
                if (list[i].checked == true) {
                    //console.log(list[i].name+1);
                    qu_string5 = list[i].value;
                }
            }
            $.ajax({
                url: "/datashow/getCity",
                type: "POST",
                data: "data=" + qu_string5,
                dataType: "json",
                success: function (data) {
                    //console.log(JSON.stringify(data)+"城市");
                    for (i = 0; i < data.length; i++) {
                        $(".inputz4>.panel1>.cpmtem").html($(".inputz4>.panel1>.cpmtem").html() +
                            "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox name=" + data[i] + ">" + data[i] + "</div>"
                        )
                    }
                }
            });
        }
        if (haveindex == 6) {
            //$(".inputz6").css("display", "block");
            /*获取孙子*/
            var qu_string6;
            var list0 = $(".inputz5>.panel1");
            var list = list0.children("div").children("input")
            //console.log("list"+list.length)
            //var list = $(".panel1>div>input")
            /*添加到HTML*/
            for (i = 0; i < list.length; i++) {
                if (list[i].checked == true) {
                    //console.log(list[i].name+1);
                    qu_string6 = list[i].value;
                }
            }
            $.ajax({
                url: "/datashow/getCity",
                type: "POST",
                data: "data=" + qu_string6,
                dataType: "json",
                success: function (data) {
                    //console.log(JSON.stringify(data)+"城市");
                    for (i = 0; i < data.length; i++) {
                        $(".inputz6>.panel1>.cpmtem").html($(".inputz6>.panel1>.cpmtem").html() +
                            "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox name=" + data[i] + ">" + data[i] + "</div>"
                        )
                    }
                }
            });
        }
        if (haveindex == 9) {
            $(".inputz9").css("display", "block");
        }
    }
    //console.log(new_keyvilue)
    //console.log(new_keyvilue.length)
    /*赋值判断*/
    var keyname = $.trim(keyname = $(".xiala0").eq(wuindex).html());
    /*排除1。2行最后的选择*/
    if (haveindex != 1 && haveindex != 3 && haveindex != 5 && haveindex != 7 && haveindex != 8 && haveindex != 9 && haveindex != 10) {
        if (keyname == "产品") {
            keyname = "pid";
            $(".yizui").css("display", "none");
        }
        else if (keyname == "产地") {
            keyname = "startpoint";
            /*判断选了几个*/
            if (new_keyvilue.length == 1) {
                var qu_string2;
                if (wuindex == 0) {
                    $(".yizui").css("display", "none");
                    $(".yizui>.xiala").html("请选择生产城市");
                    /*返回第一行末尾的值*/
                    /*获取孙子*/
                    var list0 = $(".yizhong>.panel1>.cpmtem");
                    var list = list0.children("div").children("input")
                    //console.log("list"+list.length)
                    //var list = $(".panel1>div>input")
                    /*添加到HTML*/
                    for (i = 0; i < list.length; i++) {
                        if (list[i].checked == true) {
                            //console.log(list[i].name+1);
                            qu_string2 = list[i].value;
                        }
                    }
                    $.ajax({
                        url: "/datashow/getCity",
                        type: "POST",
                        data: "data=" + qu_string2,
                        dataType: "json",
                        success: function (data) {
                            //console.log(JSON.stringify(data)+"城市");
                            $(".yizui>.panel1>.cpmtem").html("");
                            for (i = 0; i < data.length; i++) {
                                $(".yizui>.panel1>.cpmtem").html($(".yizui>.panel1>.cpmtem").html() +
                                    "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox name=" + data[i] + ">" + data[i] + "</div>"
                                )
                            }
                        }
                    });
                }
                else {
                    $(".erzui").css("display", "none");
                    $(".erzui>.xiala").html("请选择生产城市");
                    /*获取孙子*/
                    var list0 = $(".erzhong>.panel1>.cpmtem");
                    var list = list0.children("div").children("input")
                    //console.log("list"+list.length)
                    //var list = $(".panel1>div>input")
                    /*添加到HTML*/
                    for (i = 0; i < list.length; i++) {
                        if (list[i].checked == true) {
                            //console.log(list[i].name+1);
                            qu_string2 = list[i].value;
                        }
                    }
                    $.ajax({
                        url: "/datashow/getCity",
                        type: "POST",
                        data: "data=" + qu_string2,
                        dataType: "json",
                        success: function (data) {
                            //console.log(JSON.stringify(data)+"城市");
                            $(".erzui>.panel1>.cpmtem").html("");
                            for (i = 0; i < data.length; i++) {
                                $(".erzui>.panel1>.cpmtem").html($(".erzui>.panel1>.cpmtem").html() +
                                    "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox name=" + data[i] + ">" + data[i] + "</div>"
                                )
                            }
                        }
                    });
                }
            }
        }
        else if (keyname == "销售地") {
            keyname = "sellpoint";
            /*判断选了几个*/
            var qu_string3;
            if (new_keyvilue.length == 1) {
                if (wuindex == 0) {
                    $(".yizui").css("display", "none");
                    $(".yizui>.xiala").html("请选择销售城市");
                    /*返回第一行末尾的值*/
                    /*获取孙子*/
                    var list0 = $(".yizhong>.panel1>.cpmtem");
                    var list = list0.children("div").children("input")
                    console.log("list" + list.length)
                    //var list = $(".panel1>div>input")
                    /*添加到HTML*/
                    for (i = 0; i < list.length; i++) {
                        if (list[i].checked == true) {
                            //console.log(list[i].name+1);
                            qu_string3 = list[i].value;
                        }
                    }
                    ;
                    $.ajax({
                        url: "/datashow/getCity",
                        type: "POST",
                        data: "data=" + qu_string3,
                        dataType: "json",
                        success: function (data) {
                            //console.log(JSON.stringify(data)+"城市");
                            $(".yizui>.panel1>.cpmtem").html("");
                            for (i = 0; i < data.length; i++) {
                                $(".yizui>.panel1>.cpmtem").html($(".yizui>.panel1>.cpmtem").html() +
                                    "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox  name=" + data[i] + ">" + data[i] + "</div>"
                                )
                            }
                        }
                    });
                }
                if (wuindex == 1) {
                    var qu_string4;
                    $(".erzui").css("display", "none");
                    $(".erzui>.xiala").html("请选择销售城市");
                    ///*返回第二行末尾的值*/
                    ///*获取孙子*/
                    var list0 = $(".erzhong>.panel1>.cpmtem");
                    var list = list0.children("div").children("input")
                    console.log("list" + list.length)
                    //var list = $(".panel1>div>input")
                    /*添加到HTML*/
                    for (i = 0; i < list.length; i++) {
                        if (list[i].checked == true) {
                            //console.log(list[i].name+1);
                            qu_string4 = list[i].value;
                        }
                    }
                    ;
                    $.ajax({
                        url: "/datashow/getCity",
                        type: "POST",
                        data: "data=" + qu_string4,
                        dataType: "json",
                        success: function (data) {
                            console.log(JSON.stringify(data) + "城市");
                            $(".erzui>.panel1>.cpmtem").html("");
                            for (i = 0; i < data.length; i++) {
                                $(".erzui>.panel1>.cpmtem").html($(".erzui>.panel1>.cpmtem").html() +
                                    "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox name=" + data[i] + ">" + data[i] + "</div>"
                                )
                            }
                        }
                    });
                }
            }
        }
        else if (keyname == "时间") {
            keyname = "createtime";
            if (new_keyvilue.length == 1) {
                $(".erzui").css("display", "block");
                $(".erzui>.xiala").html("请选择月份");
                $(".erzui>.panel1>.cpmtem").html("");
                for (i = 1; i <= 12; i++) {
                    $(".erzui>.panel1>.cpmtem").html($(".erzui>.panel1>.cpmtem").html() +
                        "<div class=xuanxiang onclick=dianji(this)><input  type=checkbox  name=" + i + "月" + "  value=" + i + ">" + i + "月</div>"
                    )
                }
            }
        }
        else {
            keyname = "retail_price";
        }
    }
    /*IF封口排除1。2行最后的选择*/
    var yihave = new Array();
    if (haveindex == 0) {
        diyizhong = new_keyvilue;
    }
    if (haveindex == 2) {
        dierzhong = new_keyvilue;
    }
    //alert(dierzhong)
    /*第一行返回值分析对象*/
    if (wuindex == 0) {
        //alert(keyname+"keyname")
        if (keyname == "startpoint" || keyname == "产地" || keyname == "sellpoint" || keyname == "销售地") {
            /*第一行都选一个*/
            if (new_keyvilue.length == 1) {
                if (haveindex == 1) {
                    //alert(111)
                    var sanj = $(".yizui>.panel1")
                    var sanji = sanj.children("div").children("input")
                    var yihave;
                    for (i = 0; i < sanji.length; i++) {
                        if (sanji[i].checked == true) {
                            yihave.push(list[i].name);
                        }
                    }
                    //alert(diyizhong+yihave)
                    if (keyname == "startpoint" || keyname == "产地") {
                        //var  diyizhongs1=diyizhong.join("");
                        //var  yihave1=yihave.join("");
                        //bianliang = {"startpoint": [{"province": diyizhongs1, "city": yihave1}]};
                        //alert(new_keyvilue)
                        bianliang = {"startpoint": new_keyvilue}
                    } else {
                        //var  diyizhongs1=diyizhong.join("");
                        //var  yihave1=yihave.join("");
                        //bianliang = {"sellpoint": [{"province": diyizhongs1, "city": yihave1}]};
                        bianliang = {"sellpoint": new_keyvilue}
                    }

                }
            }
            /*第一行中间选两个*/
            if (new_keyvilue.length > 1 && haveindex != 1) {
                //alert(new_keyvilue.length);
                if (keyname == "startpoint" || keyname == "产地") {
                    bianliang = {"startpoint": new_keyvilue}
                } else {
                    bianliang = {"sellpoint": new_keyvilue}
                }

            }
            /*第一行最后选2个*/
            if (new_keyvilue.length > 1 && haveindex == 1) {
                var sanj = $(".yizui>.panel1")
                var sanji = sanj.children("div").children("input")
                var yihave;
                for (i = 0; i < sanji.length; i++) {
                    if (sanji[i].checked == true) {
                        yihave.push(list[i].name);
                    }
                }
                if (keyname == "startpoint" || keyname == "产地") {
                    bianliang = {"startpoint": [{"province": diyizhong, "city": yihave}]};
                } else {
                    bianliang = {"sellpoint": [{"province": diyizhong, "city": yihave}]};
                }
            }
        }
        var diyizhongs=[];
        if (keyname == "pid") {
            //alert(diyizhong)
            /*获取孙子*/
            var list0 = $(".yizhong>.panel1>.cpmtem")
            var list = list0.children("div").children("input")
            //console.log("list"+list.length)
            /*添加到HTML*/
            for (i = 0; i < list.length; i++) {
                if (list[i].checked == true) {
                    diyizhongs.push(list[i].value);
                }
            }
               bianliang = {"pid": diyizhongs}
        }
        console.log(bianliang+"3")
        objjj.legend = bianliang;
        var JSonstring = JSON.stringify(objjj);
        console.log(wuindex + "的时候" + JSonstring);

    }
    /*第二行返回值细分明细*/
    if (wuindex == 1) {
        if (keyname == "startpoint" || keyname == "产地" || keyname == "sellpoint" || keyname == "销售地" || keyname == "createtime" || keyname == "时间") {
            /*第二行都选一个*/
            if (new_keyvilue.length == 1&&haveindex == 2) {
                     var yihave;
                    var sanj = $(".erzui>.panel1>.cpmtem")
                    var sanji = sanj.children("div").children("input")
                    for (i = 0; i < sanji.length; i++) {
                        if (sanji[i].checked == true) {
                            yihave.push(sanji[i].name);
                        }
                    }
                    if (keyname == "startpoint" || keyname == "产地") {
                        //var  dierzhong1=dierzhong.join("");
                        //var  yihave1=yihave.join("");
                        //bianliang2 = {"startpoint": [{"province": dierzhong1, "city": yihave1}]};
                        bianliang2 = {"startpoint": dierzhong}
                    }
                    if (keyname == "sellpoint" || keyname == "销售地") {
                        //var  dierzhong1=dierzhong.join("");
                        //var  yihave1=yihave.join("");
                        //bianliang2 = {"sellpoint": [{"province": dierzhong1, "city": yihave1}]};
                        bianliang2 = {"sellpoint": dierzhong}
                    }
                    if (keyname == "createtime" || keyname == "时间") {
                        var dierzhong1 = dierzhong.join("");
                        var dierzhong1 = dierzhong1.replace(/年/, "-");
                        var yihave = yihave.join("");
                        var yihave = yihave.replace(/月/, "");
                        bianliang2 = {"createtime": [dierzhong1 + yihave]};
                    }
                }
                /*第二行中间选两个*/
                if (new_keyvilue.length > 1 && haveindex==2) {
                    if (keyname == "startpoint" || keyname == "产地") {
                        bianliang2 = {"startpoint": new_keyvilue}
                    }if(keyname == "sellpoint" || keyname == "销售地"){
                        bianliang2 = {"sellpoint": new_keyvilue}
                    }
                    if(keyname== "createtime"|| keyname == "时间"){
                        var new_keyvilue1 = new_keyvilue.join("");
                        for(var i=0;i<new_keyvilue.length;i++){
                            var new_keyvilue1= new_keyvilue1.replace(/年/, ",");
                        }
                        var new_keyvilue2 = new_keyvilue1.split(",")
                        new_keyvilue2.pop();
                        bianliang2 = {"createtime": new_keyvilue2};
                    }
                }
                /*第二行最后选2个*/
                if (new_keyvilue.length > 1 && haveindex == 3) {
                    var yihave;
                    var sanj = $(".erzui>.panel1>.cpmtem")
                    var sanji = sanj.children("div").children("input")
                    for (i = 0; i < sanji.length; i++) {
                        if (sanji[i].checked == true) {
                            yihave.push(sanji[i].name);
                        }
                    }
                    if (keyname == "startpoint" || keyname == "产地") {
                        bianliang2 = {"startpoint": [{"province": dierzhong, "city": yihave}]};
                    }
                    if (keyname == "sellpoint" || keyname == "销售地"){
                        bianliang2 = {"sellpoint": [{"province": dierzhong, "city": yihave}]};
                    }
                    if(keyname== "createtime"|| keyname == "时间"){
                        var dierzhong1 = dierzhong.join("");
                        var dierzhong1 = dierzhong1.replace(/年/, "-");
                        console.log(yihave)
                        var yihave = yihave.join("");
                        for(var i=0;i<yihave.length;i++){
                          var yihave = yihave.replace(/月/, "");
                        }
                        console.log(yihave)
                        var yihave = yihave.split("")
                        console.log(yihave)
                        if(yihave.length==2){
                            bianliang2 = {"createtime": [dierzhong1+yihave[0],dierzhong1+yihave[1]]};
                        }else  if(yihave.length==3){
                            bianliang2 = {"createtime": [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2]]};
                        }else  if(yihave.length==4){
                            bianliang2 = {"createtime": [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3]]};
                        }else  if(yihave.length==5){
                            bianliang2 = {"createtime": [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3],dierzhong1+yihave[4]]};
                        }else  if(yihave.length==6){
                            bianliang2 = {"createtime": [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3],dierzhong1+yihave[4],dierzhong1+yihave[5]]};
                        }else  if(yihave.length==7){
                            bianliang2 = {"createtime": [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3],dierzhong1+yihave[4],dierzhong1+yihave[5],dierzhong1+yihave[6]]};
                        }else  if(yihave.length==8){
                            bianliang2 = {"createtime": [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3],dierzhong1+yihave[4],dierzhong1+yihave[5],dierzhong1+yihave[6],dierzhong1+yihave[7]]};
                        }
                    }
                }
            }
            objjj.legendx = bianliang2;
            var JSonstring = JSON.stringify(objjj);
            console.log(wuindex + "的时候" + JSonstring);
        }

}
/*直接写*/
//data1.legend=bianliang;
/*重新赋值1、2行最后的值*/
/*业务逻辑*/
/*第三行判断逻辑*/
 objjj={
        "legend": {
      },
        "condition": {
      },
        "value": "y1",
        "legendx": {
        }
 }
var beifen;
/*展开筛选条件*/
function zk_analyze(){
    $(".screening_condition").css({
        "height":"250px",
        "overflow": "visible",
        "transition":"all 0.5s"
    });
    $(".screening_condition").children().css("visibility","visible");
    $(".start_analyze").css("display","block");
    $(".unfold_analyze").css({
        "display":"none" ,
        "margin-top":"0px"
    });
    $(".Custom_price").css("display","none");
    if($(".inputz2>.ye0").html()=="售价"){
        $(".Custom_price").css("display","block");
        $(".erzhong").css("display","none");
        $(".erzui").css("display","none");
    }
}

/*开始分析*/
function ks_analyze(){
    $("#main1").css("display","none");
    $("#main2").css("display","none");
    $("#main3").css("display","none");
    $("#main4").css("display","none");
    $(".ztu").eq(0).css("display","block");
    $(".ztu").eq(1).css("display","block");
    $(".ztu").eq(2).css("display","block");
    $(".ztu").eq(3).css("display","block");
    $('.gou').eq(0).css("display","none");
    $('.gou').eq(1).css("display","none");
    $('.gou').eq(2).css("display","none");
    $('.gou').eq(3).css("display","none");


if ($(".Custom_price").css("display") == "block") {
    var a1 = parseFloat($(".Custom_price>input").eq(0).val());
    var b1 = parseFloat($(".Custom_price>input").eq(1).val());
    var c1 = parseFloat($(".Custom_price>input").eq(2).val());
    var d1 = parseFloat($(".Custom_price>input").eq(3).val());
    var e1 = parseFloat($(".Custom_price>input").eq(4).val());
    var f1 = $(".Custom_price>input").eq(5).val()-0;

    var a = $(".Custom_price>input").eq(0).val();
    var b = $(".Custom_price>input").eq(1).val();
    var c = $(".Custom_price>input").eq(2).val();
    var d = $(".Custom_price>input").eq(3).val();
    var e = $(".Custom_price>input").eq(4).val();
    var f = $(".Custom_price>input").eq(5).val();
    if(a!=""&&b==""&&c==""&&d==""&&e==""&&f==""){
            fangfa();
    }
    if(a!=""&&b!=""&&c==""&&d==""&&e==""&&f==""){
        if(a1<b1){
            $(".Nagvis").css("display","block");
            fangfa();
        }else{
            $("#main1").css("display","none");
            $("#main2").css("display","none");
            $("#main3").css("display","none");
            $("#main4").css("display","none");
            alert("按从小到大书序排列");
            $(".Nagvis").css("display","none");
        }
    }
    if(a!=""&&b!=""&&c!=""&&d==""&&e==""&&f==""){
        if(a1<b1&&b1<c1){
            $(".Nagvis").css("display","block");
            fangfa();
        }else{
            $("#main1").css("display","none");
            $("#main2").css("display","none");
            $("#main3").css("display","none");
            $("#main4").css("display","none");
            alert("按从小到大书序排列")
            $(".Nagvis").css("display","none");
        }
    }
    if(a!=""&&b!=""&&c!=""&&d!=""&&e==""&&f==""){
        if(a1<b1&&b1<c1&&c1<d1){
            $(".Nagvis").css("display","block");
            fangfa();
        }else{
            $("#main1").css("display","none");
            $("#main2").css("display","none");
            $("#main3").css("display","none");
            $("#main4").css("display","none");
            alert("按从小到大书序排列")
            $(".Nagvis").css("display","none");
        }
    }
    if(a!=""&&b!=""&&c!=""&&d!=""&&e!=""&&f=="") {
        if(a1<b1&&b1<c1&&c1<d1&&d1<e1){
            $(".Nagvis").css("display","block");
            fangfa();
        }
        else{
            $("#main1").css("display","none");
            $("#main2").css("display","none");
            $("#main3").css("display","none");
            $("#main4").css("display","none");
            alert("按从小到大书序排列")
            $(".Nagvis").css("display","none");
        }
    }
    if(a!=""&&b!=""&&c!=""&&d!=""&&e!=""&&f!=""){
        if(a1<b1&&b1<c1&&c1<d1&&d1<e1&&e1<f1){
            $(".Nagvis").css("display","block");
            fangfa();
        }else{
            $("#main1").css("display","none");
            $("#main2").css("display","none");
            $("#main3").css("display","none");
            $("#main4").css("display","none");
            alert("按从小到大书序排列")
            $(".Nagvis").css("display","none");
        }
    }
}
 function fangfa(){
            var a = $(".Custom_price>input").eq(0).val();
            var b = $(".Custom_price>input").eq(1).val();
            var c = $(".Custom_price>input").eq(2).val();
            var d = $(".Custom_price>input").eq(3).val();
            var e = $(".Custom_price>input").eq(4).val();
            var f = $(".Custom_price>input").eq(5).val();

            var a1 = new Array();        var b1 = new Array();
            var c1 = new Array();        var d1 = new Array();
            var e1 = new Array();        var f1 = new Array();
            var g1=new Array();

            var ba1 = new Array();        var bb1 = new Array();
            var bc1 = new Array();        var bd1 = new Array();
            var be1 = new Array();        var bf1 = new Array();
            var bg1=new Array();

            if(a!=""&&b==""&&c==""&&d==""&&e==""&&f==""){
                a1.push("less");  a1.push(a);
                g1.push("than");g1.push(a);
                bianliang2 = {"retail_price": [a1,g1]};

                ba1.push("小于");  ba1.push(a);
                bg1.push("大于");  bg1.push(a);
                beifen = {"retail_price": [ba1,bg1]};
            }
            if(a!=""&&b!=""&&c==""&&d==""&&e==""&&f==""){
                a1.push("less");  a1.push(a);
                b1.push(a);        b1.push(b);
                g1.push("than");g1.push(b);
                bianliang2 = {"retail_price": [a1,b1,g1]};

                ba1.push("小于"+a);
                bb1.push(a+"-"+b);
                bg1.push("大于"+b);
                beifen = {"retail_price": [ba1,bb1,bg1]};

            }
            if(a!=""&&b!=""&&c!=""&&d==""&&e==""&&f==""){
                a1.push("less");  a1.push(a);
                b1.push(a);        b1.push(b);
                c1.push(b);        c1.push(c);
                g1.push("than");g1.push(c);
                bianliang2 = {"retail_price": [a1,b1,c1,g1]};

                ba1.push("小于"+a);
                bb1.push(a+"-"+b);
                bc1.push(b+"-"+c);
                bg1.push("大于"+c);
                beifen = {"retail_price": [ba1,bb1,bc1,bg1]};
            }
            if(a!=""&&b!=""&&c!=""&&d!=""&&e==""&&f==""){
                a1.push("less");  a1.push(a);
                b1.push(a);        b1.push(b);
                c1.push(b);        c1.push(c);
                d1.push(c);        d1.push(d);
                g1.push("than");g1.push(d);
                bianliang2 = {"retail_price": [a1,b1,c1,d1,g1]};

                ba1.push("小于"+a);
                bb1.push(a+"-"+b);
                bc1.push(b+"-"+c);
                bd1.push(c+"-"+d);
                bg1.push("大于"+d);
                beifen = {"retail_price": [ba1,bb1,bc1,bd1,bg1]};
            }
            if(a!=""&&b!=""&&c!=""&&d!=""&&e!=""&&f==""){
                a1.push("less");  a1.push(a);
                b1.push(a);        b1.push(b);
                c1.push(b);        c1.push(c);
                d1.push(c);        d1.push(d);
                e1.push(d);        e1.push(e);
                g1.push("than");   g1.push(e);
                bianliang2 = {"retail_price": [a1,b1,c1,d1,e1,g1]};

                ba1.push("小于"+a);
                bb1.push(a+"-"+b);
                bc1.push(b+"-"+c);
                bd1.push(c+"-"+d);
                be1.push(d+"-"+e);
                bg1.push("大于"+e);
                beifen = {"retail_price": [ba1,bb1,bc1,bd1,be1,bg1]};
            }
            if(a!=""&&b!=""&&c!=""&&d!=""&&e!=""&&f!=""){
                a1.push("less");  a1.push(a);
                b1.push(a);        b1.push(b);        c1.push(b);        c1.push(c);
                d1.push(c);        d1.push(d);        e1.push(d);        e1.push(e);
                f1.push(e);        f1.push(f);
                g1.push("than");        g1.push(f);
                bianliang2 = {"retail_price": [a1, b1, c1, d1, e1, f1,g1]};

                ba1.push("小于"+a);
                bb1.push(a+"-"+b);
                bc1.push(b+"-"+c);
                bd1.push(c+"-"+d);
                be1.push(d+"-"+e);
                bf1.push(e+"-"+f);
                bg1.push("大于"+f);
                beifen = {"retail_price": [ba1,bb1,bc1,bd1,be1,bf1,bg1]};
            }
            objjj.legendx = bianliang2;
            var JSonstring = JSON.stringify(objjj);
            console.log(wuindex + "的时候" + JSonstring);
        }


    $(".screening_condition").css({
        "height": "50px",
        "overflow": "hidden",
        "transition": "all 0.5s"
    });
    var g = $(".inputz3>.ye").html();
    var h = $(".inputz4>.ye").html();
    var xs = $(".inputz5>.ye").html();
    var z = $(".inputz6>.ye").html();
    var k = $(".inputz7>.panel1>.cpmtem")
    var list = k.children("div").children("input")
    //console.log("list"+list.length)
    /*添加到HTML*/
    var k1 = new Array();
    for (var ii = 0; ii < list.length; ii++) {
        if (list[ii].checked == true) {
            k1.push(list[ii].value);
        }
    }
    var o = $(".inputz8>.ye").html();
    var m = $(".inputz9>.ye").html();
    var l = $(".mix_price").val();
    var n = $(".max_price").val();
    var bb;
    var g1;
    var h1;
    if (k1!="请选择产品") {
        bianliang3 = {"pid":k1};
    }
    if ($(".max_price").css("display") != "none") {
        /*选择最小值*/
        if (l != "" && n == "") {
            statement_Price_array = [];
            statement_Price_array.push("than");
            statement_Price_array.push(l);
        }
        /*选择最大值*/
        else if (l == "" && n != "") {
            statement_Price_array = [];
            statement_Price_array.push("less");
            statement_Price_array.push(n);
        }
        /*相等的情况*/
        else if (l == n) {
            statement_Price_array = [];
            statement_Price_array.push(l);
            statement_Price_array.push(n);
        }
        else {
            if (l > n) {
                statement_Price_array = [];
                statement_Price_array.push(n);
                statement_Price_array.push(l);
            } else {
                statement_Price_array = [];
                statement_Price_array.push(l);
                statement_Price_array.push(n);
            }
        }
        bianliang3 = {"pid": k1, "retail_price": statement_Price_array};
    }
    if (g!="请选择生产省份") {
            //alert(g)
        //if (g.length > 4) {
            var g = g.split(",");
            g.pop();
            bianliang3 = {"startpoint": [{"province": g}],
                "pid": k1,
                "retail_price": statement_Price_array
            };
        //}
        //else {
        //    g1 = g.replace(/,/, "");
        //    h1 = h.replace(/,/, "");
        //    bianliang3 = {
        //        "startpoint": [{"province": g1, "city": h1}],
        //        "pid": k1,
        //        "retail_price": statement_Price_array
        //    };
        //}
    }
    if (xs!="请选择销售省份") {
        //alert(i)
        //if (i.length > 4) {
            var xs = xs.split(",");
             xs.pop();
            bianliang3 = {
                "startpoint": [{"province": g}],
                "sellpoint": [{"province": xs}],
                "pid": k1,
                "retail_price": statement_Price_array
            };
        //}
        //else {
        //    var i1 = i.replace(/,/, "");
        //    var z1 = z.replace(/,/, "");
        //    bianliang3 = {
        //        "startpoint": [{"province": g1, "city": h1}],
        //        "sellpoint": [{"province": i1, "city": z1}],
        //        "pid": k1,
        //        "retail_price": statement_Price_array
        //    };
        //}
    }
    if (o!="请选择年份") {
        if (o.length > 6) {
              var o1= o.split(",");
               o1.pop();
            bianliang3 = {
                "startpoint": [{"province": g}],
                "sellpoint": [{"province": xs}],
                "pid":k1,
                "retail_price": statement_Price_array,
                "createtime": o1
            };
         }
        else {
            var o11 = o.replace(/年/, "-");
            var o1 = o11.replace(/,/, "");
            //var m1 = m.replace(/月/, "");
            //var m2 = m1.replace(/,/, "");
            for(var i=0;i<m.length;i++){
                var m = m.replace(/月/, "");
            }
            var yihave = m.split(",");
            yihave.pop();
           if(yihave.length==0){
                var  xzhouzhi=dierzhong;
            }
            else  if(yihave.length==1){
                var  xzhouzhi=[o1+yihave[0]]
            }else if(yihave.length==2){
                var  xzhouzhi =[o1+yihave[0],o1+yihave[1]]
            }else  if(yihave.length==3){
                var  xzhouzhi = [o1+yihave[0],o1+yihave[1],o1+yihave[2]]
            }else  if(yihave.length==4){
                var  xzhouzhi =  [o1+yihave[0],o1+yihave[1],o1+yihave[2],o1+yihave[3]]
            }else  if(yihave.length==5){
                var  xzhouzhi =[o1+yihave[0],o1+yihave[1],o1+yihave[2],o1+yihave[3],o1+yihave[4]]
            }else  if(yihave.length==6){
                var  xzhouzhi = [o1+yihave[0],o1+yihave[1],o1+yihave[2],o1+yihave[3],o1+yihave[4],o1+yihave[5]]
            }else  if(yihave.length==7){
                var  xzhouzhi = [o1+yihave[0],o1+yihave[1],o1+yihave[2],o1+yihave[3],o1+yihave[4],o1+yihave[5],o1+yihave[6]]
            }else  if(yihave.length==8) {
                var  xzhouzhi = [o1 + yihave[0], o1 + yihave[1], o1 + yihave[2], o1 + yihave[3], o1 + yihave[4], o1 + yihave[5], o1 + yihave[6], o1 + yihave[7]]
            }else  if(yihave.length==9) {
                var  xzhouzhi = [o1 + yihave[0], o1 + yihave[1], o1 + yihave[2], o1 + yihave[3], o1 + yihave[4], o1 + yihave[5], o1 + yihave[6], o1 + yihave[7], o1 + yihave[8]]
            }else  if(yihave.length==11) {
                var  xzhouzhi = [o1 + yihave[0], o1 + yihave[1], o1 + yihave[2], o1 + yihave[3], o1 + yihave[4], o1 + yihave[5], o1 + yihave[6], o1 + yihave[7], o1 + yihave[8], o1 + yihave[9]+ yihave[10]]
            }else  if(yihave.length==13) {
                var  xzhouzhi = [o1 + yihave[0], o1 + yihave[1], o1 + yihave[2], o1 + yihave[3], o1 + yihave[4], o1 + yihave[5], o1 + yihave[6], o1 + yihave[7], o1 + yihave[8], o1 + yihave[9]+ yihave[10], o1 + yihave[11]+ yihave[12]]
            }else  if(yihave.length==15) {
                var  xzhouzhi = [o1 + yihave[0], o1 + yihave[1], o1 + yihave[2], o1 + yihave[3], o1 + yihave[4], o1 + yihave[5], o1 + yihave[6], o1 + yihave[7], o1 + yihave[8], o1 + yihave[9]+ yihave[10], o1 + yihave[11]+ yihave[12], o1 + yihave[13]+ yihave[14]]
            }
            //bianliang3 = {
            //    "startpoint": [{"province": g1, "city": h1}],
            //    "sellpoint": [{"province": i1, "city": z1}],
            //    "pid": k1,
            //    "retail_price": statement_Price_array,
            //    "createtime": [o2 + m2]
            //};
            bianliang3 = {
                "startpoint": [{"province": g}],
                "sellpoint": [{"province": xs}],
                "pid":k1,
                "retail_price": statement_Price_array,
                "createtime":xzhouzhi
            };
        }
    }
    objjj.condition = bianliang3;
    var JSonstring = JSON.stringify(objjj);
    console.log(JSonstring + "0");
    console.log(k1+"k1")
    if(k1==""){
        delete objjj["condition"]["pid"];
    }
    if(g=="请选择生产省份"){
        delete objjj["condition"]["startpoint"];
    }
    if(xs=="请选择销售省份") {
        delete objjj["condition"]["sellpoint"];
    }
    if(o=="请选择年份"){
        delete objjj["condition"]["createtime"];
    }
    if(l==""||n=="") {
        delete objjj["condition"]["retail_price"];
    }

     JSonstring = JSON.stringify(objjj);
    console.log(JSonstring+"1");

     $(".screening_condition").children().css("visibility","hidden");

    $(".start_analyze").css("display","none");
    $(".unfold_analyze").css({
        "display":"block" ,
        "margin-top":"-50px"
    });
    $.ajax({
        url : "/datashow/commondatarequestv2",
        type : "POST",
        data : "data=" + JSonstring,
        dataType : "json",
        success : function(data) {
            console.log(JSON.stringify(data)+"dd");
            quanjubiangliang=data;
            if(quanjubiangliang!=null){
                if(quanjubiangliang.type[0]=="Histogram"){
                    $(".ztu").eq(0).css("display","block")
                    $(".ztu").eq(1).css("display","block")
                    $(".ztu").eq(2).css("display","none")
                    $(".ztu").eq(3).css("display","none")
                }
                    if(quanjubiangliang.type[1]=="linechart"){
                        $(".ztu").eq(1).css("display","none")
                        $(".ztu").eq(1).css("display","block")
                        $(".ztu").eq(2).css("display","none")
                        $(".ztu").eq(3).css("display","none")
                    }
                        if(quanjubiangliang.type=="standardpiechart"){
                            $(".ztu").eq(0).css("display","none")
                            $(".ztu").eq(1).css("display","none")
                            $(".ztu").eq(3).css("display","none")
                        }
                            if(quanjubiangliang.type=="map"){
                                $(".ztu").eq(0).css("display","none")
                                $(".ztu").eq(1).css("display","none")
                                $(".ztu").eq(2).css("display","none")
                            }
            }else {
                $("#main1").css({
                    "display":"block",
                    "border":"1px solied red",
                     "text-align": "center",
                     "line-height": "400px",
                     "color":"blue", "font-size":"30px"
                });
                $("#main1").html(
                    "提示: 亲！您筛选的条件暂时没有数据哦，。。。"
                )
            }
        }
    });

}

/*4个图例的打开关闭*/
$('.ztu').click(function(){
    $("#main1").css("display","block");
    $("#main2").css("display","block");
    $("#main3").css("display","block");
    $("#main4").css("display","block");
    var index=  $('.ztu').index(this);
    if(index==0){
        if(quanjubiangliang!=null) {
            if (quanjubiangliang.type[0] == "Histogram") {
                $('.gou').eq(1).css("display", "none");
                tulixuanze();
            }
        }
    }
    else if(index==1){
        if(quanjubiangliang!=null) {
            if (quanjubiangliang.type[1] == "linechart") {
                $('.gou').eq(0).css("display", "none");
                tulixuanze();
            }
        }
    }
    else if(index==2){
        if(quanjubiangliang!=null) {
            if (quanjubiangliang.type == "standardpiechart") {
                tulixuanze();
            }
        }
    }
    else if(index==3){
        if(quanjubiangliang!=null) {
            if(quanjubiangliang.type=="map") {
                tulixuanze();
            }
        }
    }
    function tulixuanze(){
        if ($('.gou').eq(index).css('display')=='none') {
            $('.gou').eq(index).css("display", "block");
            $(".ztu").eq(index).css({
                    "webkit-box-shadow":"0 0 10px #666",
                    "moz-box-shadow": "0 0 10px #666",
                    "box-shadow": "0 0 10px #666"
                })
            if(index==0){
                $("#main2").css("display","none");
                $("#main3").css("display","none");
                $("#main4").css("display","none");
                $("#main1").css("display","block");
                histogram();
            }
            else  if(index==1){
                $("#main1").css("display","none");
                $("#main3").css("display","none");
                $("#main4").css("display","none");
                $("#main2").css("display","block");
                broken_line();
            }
            else if(index==2) {
                $("#main1").css("display","none");
                $("#main2").css("display","none");
                $("#main4").css("display","none");
                $("#main3").css("display","block");
                cake();
            }
            if(index==3){
                $("#main1").css("display","none");
                $("#main2").css("display","none");
                $("#main3").css("display","none");
                $("#main4").css("display","block");
                ditu();
            }
        }
        else {
            $('.gou').eq(index).css("display", "none");
            $(".ztu").eq(index).css(    {
                    "webkit-box-shadow":"0 0 0",
                    "moz-box-shadow": "0 0 0",
                    "box-shadow": "0 0 0"
                }
            )
        }
    }
});

var tuli=new Array();
/*柱状图和折线图的*/
function  bb(name,whatmap,shuzu){
    this.fangfa=
    {
        name: name,
        type: whatmap,
        data: shuzu,
        markPoint: {
            data: [
                {type: 'max', name: '最大值'},
                {type: 'min', name: '最小值'}
            ]
        },
        markLine: {
            data: [
                {type: 'average', name: '平均值'}
            ]
        }
    }
}
/*地图的*/
/*地图的*/
function bbb(duixiangming,datazhi){
    this.fangfa=
    {
        name: duixiangming,
        type: 'map',
        mapType: 'china',
        roam: false,
        itemStyle:{
            normal:{label:{show:true}},
            emphasis:{label:{show:true}}
        },
        data:datazhi,
    }
}
// 路径配置
require.config({
    paths: {
        echarts: 'http://echarts.baidu.com/build/dist'
    }
});
// 使用
/*柱状图*/
function  histogram(){
    var type1=$(".yizhong>.xiala").html();
    var yihave=[];
    var a = $(".Custom_price>input").eq(0).val();
    if(a!==""){
        //var  xzhouzhi=[a+"元",b+"元",c+"元",d+"元",e+"元",f+"元"]
        //var x2=objjj.legendx.retail_price;
         var x2=beifen.retail_price;
           //var x3=x2[0].join("")
           //var new1=x3.replace("less","小于");
           //var dssd=x2.splice(0,1,new1);
           // var x4=x2[x2.length-1].join("")
           //var new2=x4.replace("than","大于")
           //var dssds=x2.splice(x2.length-1,1,new2);
        var xzhouzhi=x2;

    }
    else{
        var sanj = $(".erzui>.panel1>.cpmtem")
        var sanji = sanj.children("div").children("input")
        for (i = 0; i < sanji.length; i++) {
            if (sanji[i].checked == true) {
                yihave.push(sanji[i].name);
            }
        }
        if(dierzhong.length==1){
            var dierzhong1 = dierzhong.join("");
            var dierzhong1 = dierzhong1.replace(/年/, "-");
        }
        //if(dierzhong.length>1){
            //var dierzhong1 = dierzhong.join("");
            //for(var i=0;i<dierzhong.length;i++){
            //    var dierzhong1= dierzhong1.replace(/年/, ",");
            //}
            //var dierzhong1 = dierzhong1.split(",")
            //dierzhong1.pop();
            //console.log(dierzhong1)
        //}
        console.log(yihave)
        var yihave = yihave.join("");
        for(var i=0;i<yihave.length;i++){
            var yihave = yihave.replace(/月/, "");
        }
        var yihave = yihave.split("")
        if(yihave.length==0){
            var  xzhouzhi=dierzhong;
        }
        else  if(yihave.length==1){
            var  xzhouzhi=[dierzhong1+yihave[0]]
        }else if(yihave.length==2){
            var  xzhouzhi =[dierzhong1+yihave[0],dierzhong1+yihave[1]]
        }else  if(yihave.length==3){
            var  xzhouzhi = [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2]]
        }else  if(yihave.length==4){
            var  xzhouzhi =  [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3]]
        }else  if(yihave.length==5){
            var  xzhouzhi =[dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3],dierzhong1+yihave[4]]
        }else  if(yihave.length==6){
            var  xzhouzhi = [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3],dierzhong1+yihave[4],dierzhong1+yihave[5]]
        }else  if(yihave.length==7){
            var  xzhouzhi = [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3],dierzhong1+yihave[4],dierzhong1+yihave[5],dierzhong1+yihave[6]]
        }else  if(yihave.length==8) {
            var  xzhouzhi = [dierzhong1 + yihave[0], dierzhong1 + yihave[1], dierzhong1 + yihave[2], dierzhong1 + yihave[3], dierzhong1 + yihave[4], dierzhong1 + yihave[5], dierzhong1 + yihave[6], dierzhong1 + yihave[7]]
        }else  if(yihave.length==9) {
        var  xzhouzhi = [dierzhong1 + yihave[0], dierzhong1 + yihave[1], dierzhong1 + yihave[2], dierzhong1 + yihave[3], dierzhong1 + yihave[4], dierzhong1 + yihave[5], dierzhong1 + yihave[6], dierzhong1 + yihave[7], dierzhong1 + yihave[8]]
        }else  if(yihave.length==11) {
            var  xzhouzhi = [dierzhong1 + yihave[0], dierzhong1 + yihave[1], dierzhong1 + yihave[2], dierzhong1 + yihave[3], dierzhong1 + yihave[4], dierzhong1 + yihave[5], dierzhong1 + yihave[6], dierzhong1 + yihave[7], dierzhong1 + yihave[8], dierzhong1 + yihave[9]+ yihave[10]]
        }else  if(yihave.length==13) {
            var  xzhouzhi = [dierzhong1 + yihave[0], dierzhong1 + yihave[1], dierzhong1 + yihave[2], dierzhong1 + yihave[3], dierzhong1 + yihave[4], dierzhong1 + yihave[5], dierzhong1 + yihave[6], dierzhong1 + yihave[7], dierzhong1 + yihave[8], dierzhong1 + yihave[9]+ yihave[10], dierzhong1 + yihave[11]+ yihave[12]]
        }else  if(yihave.length==15) {
            var  xzhouzhi = [dierzhong1 + yihave[0], dierzhong1 + yihave[1], dierzhong1 + yihave[2], dierzhong1 + yihave[3], dierzhong1 + yihave[4], dierzhong1 + yihave[5], dierzhong1 + yihave[6], dierzhong1 + yihave[7], dierzhong1 + yihave[8], dierzhong1 + yihave[9]+ yihave[10], dierzhong1 + yihave[11]+ yihave[12], dierzhong1 + yihave[13]+ yihave[14]]
        }


    }
    tuli=[];
    var g = type1.split(",");
    g.pop()
    console.log(g)
    if(g.length==1){
        var bb1=new bb(g[0],"bar",quanjubiangliang.data[0]);
          tuli.push(bb1.fangfa);
    }else if(g.length==2){
        var bb1=new bb(g[0],"bar",quanjubiangliang.data[0]);
        var bb2=new bb(g[1],"bar",quanjubiangliang.data[1]);
        tuli.push(bb1.fangfa);
        tuli.push(bb2.fangfa);


    }else if(g.length==3){
        var bb1=new bb(g[0],"bar",quanjubiangliang.data[0]);
        var bb2=new bb(g[1],"bar",quanjubiangliang.data[1]);
        var bb3=new bb(g[2],"bar",quanjubiangliang.data[2]);
        tuli.push(bb1.fangfa);
        tuli.push(bb2.fangfa);
        tuli.push(bb3.fangfa);
    }else {
        var bb1=new bb(g[0],"bar",quanjubiangliang.data[0]);
        var bb2=new bb(g[1],"bar",quanjubiangliang.data[1]);
        var bb3=new bb(g[2],"bar",quanjubiangliang.data[2]);
        var bb4=new bb(g[3],"bar",quanjubiangliang.data[3]);
        tuli.push(bb1.fangfa);
        tuli.push(bb2.fangfa);
        tuli.push(bb3.fangfa);
        tuli.push(bb4.fangfa);
    }
    //if(quanjubiangliang.data[1]!=""){
    //    var bb1=new bb(g[0],"bar",quanjubiangliang.data[0]);
    //    var bb2=new bb(g[1],"bar",quanjubiangliang.data[1]);
    //    tuli.push(bb1.fangfa);
    //    tuli.push(bb2.fangfa);
    //}else {
    //    var bb1=new bb(g[0],"bar",quanjubiangliang.data[0]);
    //    tuli.push(bb1.fangfa);
    //}
    require([
            'echarts',
            'echarts/chart/bar' // 使用柱状图就加载bar模块，按需加载
            /**/
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('main1'));
            option = {
                title : {
                    text: type1+"的产品数量",
                    subtext: ''
                },
                tooltip : {
                    trigger: 'axis'
                },
                legend: {
                    data:g
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {show: false, type: ['line', 'bar']},
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'category',
                        data :xzhouzhi,
                    }
                ],
                yAxis : [
                    {
                        type : 'value'
                    }
                ],
                series : tuli

            };
            // 为echarts对象加载数据
            myChart.setOption(option);
        });
}
/*折线图*/
function   broken_line(){
    var type1=$(".yizhong>.xiala").html();
    var yihave=[];
    var a = $(".Custom_price>input").eq(0).val();
    //var b = $(".Custom_price>input").eq(1).val();
    //var c = $(".Custom_price>input").eq(2).val();
    //var d = $(".Custom_price>input").eq(3).val();
    //var e = $(".Custom_price>input").eq(4).val();
    //var f = $(".Custom_price>input").eq(5).val();
    //console.log(quanjubiangliang.data[0])
        if(a!==""){
            //var  xzhouzhi=[a+"元",b+"元",c+"元",d+"元",e+"元",f+"元"]
            var x2=beifen.retail_price;;
            //var x3=x2[0].join("")
            //var new1=x3.replace("less","小于");
            //var dssd=x2.splice(0,1,new1);
            //var x4=x2[x2.length-1].join("")
            //var new2=x4.replace("than","大于")
            //var dssds=x2.splice(x2.length-1,1,new2);
            var xzhouzhi=x2;
        }
    else {
        var sanj = $(".erzui>.panel1>.cpmtem")
        var sanji = sanj.children("div").children("input")
        for (i = 0; i < sanji.length; i++) {
            if (sanji[i].checked == true) {
                yihave.push(sanji[i].name);
            }
        }
        if(dierzhong.length==1){
            var dierzhong1 = dierzhong.join("");
            var dierzhong1 = dierzhong1.replace(/年/, "-");
        }
        console.log(yihave)
        var yihave = yihave.join("");
        for(var i=0;i<yihave.length;i++){
            var yihave = yihave.replace(/月/, "");
        }
        console.log(yihave)
        var yihave = yihave.split("")
        console.log(yihave)
        if(yihave.length==0){
            var  xzhouzhi=dierzhong;
        }
        else if(yihave.length==1){
            var  xzhouzhi=[dierzhong1+yihave[0]]
        }else if(yihave.length==2){
            var  xzhouzhi =[dierzhong1+yihave[0],dierzhong1+yihave[1]]
        }else  if(yihave.length==3){
            var  xzhouzhi = [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2]]
        }else  if(yihave.length==4){
            var  xzhouzhi =  [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3]]
        }else  if(yihave.length==5){
            var  xzhouzhi =[dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3],dierzhong1+yihave[4]]
        }else  if(yihave.length==6){
            var  xzhouzhi = [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3],dierzhong1+yihave[4],dierzhong1+yihave[5]]
        }else  if(yihave.length==7){
            var  xzhouzhi = [dierzhong1+yihave[0],dierzhong1+yihave[1],dierzhong1+yihave[2],dierzhong1+yihave[3],dierzhong1+yihave[4],dierzhong1+yihave[5],dierzhong1+yihave[6]]
        }else  if(yihave.length==8) {
            var  xzhouzhi = [dierzhong1 + yihave[0], dierzhong1 + yihave[1], dierzhong1 + yihave[2], dierzhong1 + yihave[3], dierzhong1 + yihave[4], dierzhong1 + yihave[5], dierzhong1 + yihave[6], dierzhong1 + yihave[7]]
        }else  if(yihave.length==9) {
            var  xzhouzhi = [dierzhong1 + yihave[0], dierzhong1 + yihave[1], dierzhong1 + yihave[2], dierzhong1 + yihave[3], dierzhong1 + yihave[4], dierzhong1 + yihave[5], dierzhong1 + yihave[6], dierzhong1 + yihave[7], dierzhong1 + yihave[8]]
        }else  if(yihave.length==11) {
            var  xzhouzhi = [dierzhong1 + yihave[0], dierzhong1 + yihave[1], dierzhong1 + yihave[2], dierzhong1 + yihave[3], dierzhong1 + yihave[4], dierzhong1 + yihave[5], dierzhong1 + yihave[6], dierzhong1 + yihave[7], dierzhong1 + yihave[8], dierzhong1 + yihave[9]+ yihave[10]]
        }else  if(yihave.length==13) {
            var  xzhouzhi = [dierzhong1 + yihave[0], dierzhong1 + yihave[1], dierzhong1 + yihave[2], dierzhong1 + yihave[3], dierzhong1 + yihave[4], dierzhong1 + yihave[5], dierzhong1 + yihave[6], dierzhong1 + yihave[7], dierzhong1 + yihave[8], dierzhong1 + yihave[9]+ yihave[10], dierzhong1 + yihave[11]+ yihave[12]]
        }else  if(yihave.length==15) {
            var  xzhouzhi = [dierzhong1 + yihave[0], dierzhong1 + yihave[1], dierzhong1 + yihave[2], dierzhong1 + yihave[3], dierzhong1 + yihave[4], dierzhong1 + yihave[5], dierzhong1 + yihave[6], dierzhong1 + yihave[7], dierzhong1 + yihave[8], dierzhong1 + yihave[9]+ yihave[10], dierzhong1 + yihave[11]+ yihave[12], dierzhong1 + yihave[13]+ yihave[14]]
        }
    }
    tuli=[];
    var g = type1.split(",");
    g.pop()
    console.log(g)
    if(g.length==1){
        var bb1=new bb(g[0],"line",quanjubiangliang.data[0]);
        tuli.push(bb1.fangfa);
    }
    else if(g.length==2){
        var bb1=new bb(g[0],"line",quanjubiangliang.data[0]);
        var bb2=new bb(g[1],"line",quanjubiangliang.data[1]);
        tuli.push(bb1.fangfa);
        tuli.push(bb2.fangfa);
    }
    else if(g.length==3){
        var bb1=new bb(g[0],"line",quanjubiangliang.data[0]);
        var bb2=new bb(g[1],"line",quanjubiangliang.data[1]);
        var bb3=new bb(g[2],"line",quanjubiangliang.data[2]);
        tuli.push(bb1.fangfa);
        tuli.push(bb2.fangfa);
        tuli.push(bb3.fangfa);
    }
    else {
        var bb1=new bb(g[0],"line",quanjubiangliang.data[0]);
        var bb2=new bb(g[1],"line",quanjubiangliang.data[1]);
        var bb3=new bb(g[2],"line",quanjubiangliang.data[2]);
        var bb4=new bb(g[3],"line",quanjubiangliang.data[3]);
        tuli.push(bb1.fangfa);
        tuli.push(bb2.fangfa);
        tuli.push(bb3.fangfa);
        tuli.push(bb4.fangfa);
    }
    //if(quanjubiangliang.data[1]!=""){
    //    var bb1=new bb(g[0],"line",quanjubiangliang.data[0]);
    //    var bb2=new bb(g[1],"line",quanjubiangliang.data[1]);
    //    tuli.push(bb1.fangfa);
    //    tuli.push(bb2.fangfa);
    //}else {
    //    var bb1=new bb(g[0],"line",quanjubiangliang.data[0]);
    //    tuli.push(bb1.fangfa);
    //}
    require([
            'echarts',
            'echarts/chart/line' // 使用柱状图就加载bar模块，按需加载
            /**/
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('main2'));
            option = {
                title: {
                    text: type1+"的产品数量",
                    subtext: ''
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: g
                },
                toolbox: {
                    show: true,
                    feature: {
                        mark: {show: true},
                        dataView: {show: true, readOnly: false},
                        magicType: {show: false, type: ['line', 'bar']},
                        restore: {show: true},
                        saveAsImage: {show: true}
                    }
                },
                calculable: true,
                xAxis: [
                    {
                        type: 'category',
                        boundaryGap: false,
                        data: xzhouzhi,
                    }
                ],
                yAxis: [
                    {
                        type: 'value'
                    }
                ],
                series:tuli,
            }
            // 为echarts对象加载数据
            myChart.setOption(option);
        });

}
/*饼图*/
function  cake(){
    var list0 = $(".yizhong>.panel1>.cpmtem")
    var list = list0.children("div").children("input")
    //console.log("list"+list.length)
   var dsafdsa=[];
    for (i = 0; i < list.length; i++) {
        if (list[i].checked == true) {
            dsafdsa.push(list[i].name);
    }
    }
    var dasaa=JSON.parse(quanjubiangliang.data);
    //var suzj=[];
    //for(var i=0;i<dasaa.length;i++){
    //    suzj.push(dasaa[i])
    //}
    require([
            'echarts',
            'echarts/chart/pie' // 使用柱状图就加载bar模块，按需加载
            /**/
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('main3'));
            option = {
                title : {
                    text: '产品数量',
                    subtext: '',
                    x:'center'
                },
                tooltip : {
                    trigger: 'item',
                    formatter: "{a} <br/>{b} : {c} ({d}%)"
                },
                legend: {
                    orient : 'vertical',
                    x : 'left',
                    data:dsafdsa
                },
                toolbox: {
                    show : true,
                    feature : {
                        mark : {show: true},
                        dataView : {show: true, readOnly: false},
                        magicType : {
                            show: false,
                            type: ['pie', 'funnel'],
                            option: {
                                funnel: {
                                    x: '25%',
                                    width: '50%',
                                    funnelAlign: 'left',
                                    max: 1548
                                }
                            }
                        },
                        restore : {show: true},
                        saveAsImage : {show: true}
                    }
                },
                calculable : true,
                series : [
                    {
                        name:'访问来源',
                        type:'pie',
                        radius : '55%',
                        center: ['50%', '60%'],
                        data:dasaa
                    }
                ]
            };
            // 为echarts对象加载数据
            myChart.setOption(option);
        });

}
/*地图*/
function  ditu(){
    var dsafdsa=[];
    dsafdsa.length=0;
    var list0 = $(".yizhong>.panel1>.cpmtem")
    var list = list0.children("div").children("input")
    for (i = 0; i < list.length; i++) {
        if (list[i].checked == true) {
            dsafdsa.push(list[i].name);
        }
    }
    var bbbb = new Object();
    console.log(quanjubiangliang.data+"qj");
     bbbb=JSON.parse(quanjubiangliang.data);
    tuli=[];
    var type1=$(".yizhong>.xiala").html();
    var g = type1.split(",");
    g.pop()
    console.log(bbbb);
    console.log(g);
    if(g.length==1){
       if(g[0]=="铁皮石斛枫斗"){
           var bb1=new bbb(dsafdsa[0],bbbb.铁皮石斛枫斗);
           tuli.push(bb1.fangfa);
       }else if(g[0]=="长城葡萄酒"){
           var bb1=new bbb(dsafdsa[0],bbbb.长城葡萄酒);
           tuli.push(bb1.fangfa);
       }else if(g[0]=="臻农燕窝"){
           var bb1=new bbb(dsafdsa[0],bbbb.臻农燕窝);
           tuli.push(bb1.fangfa);
       }else if(g[0]=="羲之习书砚"){
           var bb1 = new bbb(dsafdsa[0], bbbb.羲之习书砚);
           tuli.push(bb1.fangfa);
       }
   }
    if(g.length==2){
       if(g[0]=="铁皮石斛枫斗"&&g[1]=="长城葡萄酒"){
           var bb1=new bbb(dsafdsa[0],bbbb.铁皮石斛枫斗);
           tuli.push(bb1.fangfa);
           var bb2=new bbb(dsafdsa[1],bbbb.长城葡萄酒);
           tuli.push(bb2.fangfa);
       }else if(g[0]=="铁皮石斛枫斗"&&g[1]=="臻农燕窝"){
           var bb1=new bbb(dsafdsa[0],bbbb.铁皮石斛枫斗);
           tuli.push(bb1.fangfa);
           var bb2=new bbb(dsafdsa[1],bbbb.臻农燕窝);
           tuli.push(bb2.fangfa);
       }else if(g[0]=="铁皮石斛枫斗"&&g[1]=="羲之习书砚"){
           var bb1=new bbb(dsafdsa[0],bbbb.铁皮石斛枫斗);
           tuli.push(bb1.fangfa);
           var bb2=new bbb(dsafdsa[1],bbbb.羲之习书砚);
           tuli.push(bb2.fangfa);
       }
        if(g[0]=="长城葡萄酒"&&g[1]=="臻农燕窝"){
           var bb1=new bbb(dsafdsa[0],bbbb.长城葡萄酒);
           tuli.push(bb1.fangfa);
           var bb2=new bbb(dsafdsa[1],bbbb.臻农燕窝);
           tuli.push(bb2.fangfa);
       }else if(g[0]=="长城葡萄酒"&&g[1]=="羲之习书砚"){
           var bb1=new bbb(dsafdsa[0],bbbb.长城葡萄酒);
           tuli.push(bb1.fangfa);
           var bb2=new bbb(dsafdsa[1],bbbb.羲之习书砚);
           tuli.push(bb2.fangfa);
       }
        if(g[0]=="臻农燕窝"&&g[1]=="羲之习书砚"){
            var bb1=new bbb(dsafdsa[0],bbbb.臻农燕窝);
            tuli.push(bb1.fangfa);
            var bb2=new bbb(dsafdsa[1],bbbb.羲之习书砚);
            tuli.push(bb2.fangfa);
        }
   }
    if(g.length==3){
        if(g[0]=="铁皮石斛枫斗"&&g[1]=="长城葡萄酒"&&g[2]=="臻农燕窝"){
            var bb1=new bbb(dsafdsa[0],bbbb.铁皮石斛枫斗);
            tuli.push(bb1.fangfa);
            var bb2=new bbb(dsafdsa[1],bbbb.长城葡萄酒);
            tuli.push(bb2.fangfa);
            var bb3=new bbb(dsafdsa[2],bbbb.臻农燕窝);
            tuli.push(bb3.fangfa);
        }else if(g[0]=="铁皮石斛枫斗"&&g[1]=="长城葡萄酒"&&g[2]=="羲之习书砚"){
            var bb1=new bbb(dsafdsa[0],bbbb.铁皮石斛枫斗);
            tuli.push(bb1.fangfa);
            var bb2=new bbb(dsafdsa[1],bbbb.长城葡萄酒);
            tuli.push(bb2.fangfa);
            var bb3=new bbb(dsafdsa[2],bbbb.羲之习书砚);
            tuli.push(bb3.fangfa);
        }else if(g[0]=="铁皮石斛枫斗"&&g[1]=="臻农燕窝"&&g[2]=="羲之习书砚"){
            var bb1=new bbb(dsafdsa[0],bbbb.长城葡萄酒);
            tuli.push(bb1.fangfa);
            var bb2=new bbb(dsafdsa[1],bbbb.臻农燕窝);
            tuli.push(bb2.fangfa);
            var bb3=new bbb(dsafdsa[2],bbbb.羲之习书砚);
            tuli.push(bb3.fangfa);
        }
        else if(g[0]=="长城葡萄酒"&&g[1]=="臻农燕窝"&&g[2]=="羲之习书砚"){
            var bb1=new bbb(dsafdsa[0],bbbb.长城葡萄酒);
            tuli.push(bb1.fangfa);
            var bb2=new bbb(dsafdsa[1],bbbb.臻农燕窝);
            tuli.push(bb2.fangfa);
            var bb3=new bbb(dsafdsa[2],bbbb.羲之习书砚);
            tuli.push(bb3.fangfa);
        }

    }
    if(g.length==4){
            var bb1=new bbb(dsafdsa[0],bbbb.长城葡萄酒);
            tuli.push(bb1.fangfa);
            var bb2=new bbb(dsafdsa[1],bbbb.臻农燕窝);
            tuli.push(bb2.fangfa);
            var bb3=new bbb(dsafdsa[2],bbbb.羲之习书砚);
            tuli.push(bb3.fangfa);
            var bb4=new bbb(dsafdsa[3],bbbb.羲之习书砚);
            tuli.push(bb4.fangfa);
    }
    require([
            'echarts',
            'echarts/chart/map' // 使用柱状图就加载bar模块，按需加载
            /**/
        ],
        function (ec) {
            // 基于准备好的dom，初始化echarts图表
            var myChart = ec.init(document.getElementById('main4'));
            option = {
                title: {
                    text: g+"产品数量",
                    subtext: '',
                    x: 'center'
                },
                tooltip: {
                    trigger: 'item'
                },
                legend: {
                    orient: 'vertical',
                    x: 'left',
                    data: dsafdsa
                },
                dataRange: {
                    min: 0,
                    max: 2500,
                    x: 'left',
                    y: 'bottom',
                    text: ['高', '低'],           // 文本，默认为数值文本
                    calculable: true
                },
                toolbox: {
                    show: true,
                    orient: 'vertical',
                    x: 'right',
                    y: 'center',
                    feature: {
                        mark: {show: true},
                        dataView: {show: true, readOnly: false},
                        restore: {show: true},
                        saveAsImage: {show: true}
                    }
                },
                roamController: {
                    show: true,
                    x: 'right',
                    mapTypeControl: {
                        'china': true
                    }
                },
                series: tuli,
            }
            // 为echarts对象加载数据
            myChart.setOption(option);
        });
}
function  clert(){
    location.replace(location.href);;
}








