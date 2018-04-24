<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <title>控制台 微防伪商品信息系统</title>
    <meta name="keywords" content="微防伪商品信息系统" />
    <meta name="description" content="微防伪商品信息系统" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="x-UA-compatible" content="IE=Edge,chrome=1">
    <!-- basic styles -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/assets/css/font-awesome.min.css" />

    <!--[if IE 7]>
    <link rel="stylesheet" href="/assets/css/font-awesome-ie7.min.css" />
    <![endif]-->

    <link rel="stylesheet" href="/assets/css/ace.min.css" />
    <link rel="stylesheet" href="/assets/css/ace-rtl.min.css" />
    <link rel="stylesheet" href="/assets/css/ace-skins.min.css" />

    <!--[if lte IE 8]>
    <link rel="stylesheet" href="/assets/css/ace-ie.min.css" />
    <![endif]-->

    <!-- ace settings handler -->
    <link rel="stylesheet" href="/assets/css/data-ksh.css" />

    <script src="/assets/js/ace-extra.min.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="/assets/js/html5shiv.js"></script>
    <script src="/assets/js/respond.min.js"></script>
    <![endif]-->


</head>
<body>
<div class="navbar navbar-default" id="navbar">
    <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
    </script>
    <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left"><a href="/" class="navbar-brand"> <img src="/assets/images/logo.png" style="height:60px;"></img> </a><!-- /.brand -->
        </div>
        <!-- /.navbar-header --><!-- /.navbar-header -->
    </div>
    <!-- /.container -->
</div>
<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>
    <div class="main-container-inner">          <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
          	<a class="menu-toggler" id="menu-toggler" href="#">
					<span class="menu-text"></span>
				</a>
<div class="sidebar" id="sidebar">
					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
					</script>

					<div class="sidebar-shortcuts" id="sidebar-shortcuts">
						

						<div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
							<span class="btn btn-success"></span>

							<span class="btn btn-info"></span>

							<span class="btn btn-warning"></span>

							<span class="btn btn-danger"></span>
						</div>
					</div><!-- #sidebar-shortcuts -->

					<ul class="nav nav-list">
                     
                     <li class="menu-list">
							<a href="/index">
								<i class="icon-fa fa fa-list-ul"></i>
								<span class="menu-text">信息管理</span>
							</a>
							<ul class="menu-list list2">
								<li class="list2
                        <?php if(($page_id) == "2"): ?>active open<?php endif; ?>
                        <?php if(($page_id) == "3"): ?>active open<?php endif; ?>
                        ">
							<a href="/product/manager/" class="dropdown-toggle">
								<i class="icon-list"></i>
								<span class="menu-text">产品信息</span>
							</a>
						</li>
								<li class="list2
                        <?php if(($page_id) == "4"): ?>open active<?php endif; ?>
                        <?php if(($page_id) == "5"): ?>open active<?php endif; ?>
                        <?php if(($page_id) == "15"): ?>open active<?php endif; ?>
                        "
                        >
							<a href="/batch/manager/" class="dropdown-toggle">
								<i class="icon-edit"></i>
								<span class="menu-text">批次信息 </span>
							</a>
						</li>
								<li class="list2
                        <?php if(($page_id) == "12"): ?>open active<?php endif; ?>
                        <?php if(($page_id) == "13"): ?>open active<?php endif; ?>
                         <?php if(($page_id) == "14"): ?>open active<?php endif; ?>
                        "
                        >
							<a href="/message/PreviewMessage/" class="dropdown-toggle">
								<i class="icon-edit-sign"></i>
								<span class="menu-text">企业信息 </span>
							</a>
						</li>
					</ul>
						</li>
                     
                     
                     
                             <li class="
                        <?php if(($page_id) == "6"): ?>open active<?php endif; ?>
                        <?php if(($page_id) == "7"): ?>open active<?php endif; ?>
                        "
                        >
							<a href="/pecorder/manager/" class="dropdown-toggle">
								<i class="icon-file-text"></i>
								<span class="menu-text">订单管理 </span>
							</a>

						
						</li>
                     
                     
                     
                     <li class="menu-list">
							<a href="#">
								<i class="icon-fa fa fa-database"></i>
								<span class="menu-text">红包管理</span>
							</a>
							<ul class="menu-list list2">
						<li class="list2
                        <?php if(($page_id) == "2"): ?>active open<?php endif; ?>
                        <?php if(($page_id) == "3"): ?>active open<?php endif; ?>
                        ">
							<a href="/weixin/hongbao_display" class="dropdown-toggle">
								<i class="icon-list"></i>
								<span class="menu-text">红包配置</span>
							</a>

						
						</li>
								<li class="list2
                        <?php if(($page_id) == "12"): ?>open active<?php endif; ?>
                        <?php if(($page_id) == "13"): ?>open active<?php endif; ?>
                         <?php if(($page_id) == "14"): ?>open active<?php endif; ?>
                        "
                        >
							<a href="/weixin/hongbao_pecorder_display" class="dropdown-toggle">
								<i class="icon-edit-sign"></i>
								<span class="menu-text">红包列表 </span>
							</a>

						</li>
						
					</ul>
						</li>
						
					<!-- 	<li class="
                        <?php if(($page_id) == "6"): ?>open active<?php endif; ?>
                        <?php if(($page_id) == "7"): ?>open active<?php endif; ?>
                        "
                        >
							<a href="/weixin/edit_evidence_display/" class="dropdown-toggle">
								<i class="icon-file-text"></i>
								<span class="menu-text">举证信息 </span>
							</a>

						
						</li> -->
						
						   
                         <li>
							<a href="/apiconfig/manager">
								<i class="icon-fa fa fa-sliders"></i>
								<span class="menu-text">接口管理 </span>
							</a>
						</li>
						<li>
							<a href="/datashow/index">
								<i class="icon-fa fa fa-sliders"></i>
								<span class="menu-text">数据可视化 </span>
							</a>
						</li>
                                                
                                                            <li class="
                            <?php if(($page_id) == "8"): ?>active<?php endif; ?>
                        ">
							<a href="/index/changepw">
								<i class="icon-fa fa fa-unlock-alt"></i>
								<span class="menu-text"> 修改密码 </span>
							</a>
						</li>
						
						
                            
                          <li class="<?php if(($page_id) == "9"): ?>open active<?php endif; ?>
                          ">
                                  <?php if(($mode) == "1"): ?><a href="/auth/index">
								<i class="icon-gear"></i>
								<span class="menu-text"> 授权管理 </span>
							</a><?php endif; ?>
						</li>
                        
                               
                        
                	 <li>
							<a href="/user/loginout">
								<i class="icon-fa fa fa-power-off"></i>
								<span class="menu-text">注销离开 </span>
							</a>
						</li>
						<!--   <li>
							<a href="/codequery/query">
								<i class="icon-gear"></i>
								<span class="menu-text">产品身份码查询 </span>
							</a>
						</li> -->
						<!-- <li>
							<a href="/showmessage/showmessage">
								<i class="icon-lock"></i>
								<span class="menu-text">test </span>
							</a>
						</li>-->
                        
					</ul><!-- /.nav-list -->

					<div class="sidebar-collapse" id="sidebar-collapse">
						<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
					</div>

					<script type="text/javascript">
						try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
					</script>
				</div>
        <div class="main-content">
            <div class="breadcrumbs" id="breadcrumbs">
                <script type="text/javascript">
                    try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
                </script>
                <ul class="breadcrumb">
                    <li> <i class="icon-home home-icon"></i> <a href="/">首页</a> </li>
                    <li class="active">数字可视化</li>
                </ul>
                <!-- .breadcrumb -->
                <!-- #nav-search -->
            </div>

            <div class="page-content">
                <!--功能按钮-->
                       <div class="function_keys">
                        <span class="product_quantity" >产品数量</span>
                        <!--<span class="Sweep" onclick="Sweep1()">扫码量</span>-->
                    </div>
                <!--筛选条件-->
                       <form action="" class="screening_condition">
                           <!--第一行-->
                           <!--分析对象-->
                           <label for="" class="label1">分析对象：</label>
                           <div class="inputz0">
                               <div class="xiala0 ye0">请选择分析对象类型</div>
                               <div class="dian0"><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="pane">
                                   <div></div>
                                   <div onclick="analyze(this)" id="product">产品</div>
                                   <div onclick="analyze(this) " id = "create_place_province" >产地</div>
                                   <div onclick="analyze(this)" id = "sell_place_province" >销售地</div>
                               </div>
                               <div class="hint" >
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1">  <img src="/assets/images/tishi_01.png">  </div>
                                   <div class="ts_wz1">
                                       选择要对比对象的类型，可在产品、销售地、生产地三者里面选。
                                   </div>
                               </div>
                           </div>

                           <!--第一行中间-->
                           <div class="inputz yizhong">
                               <div class="xiala ye"></div>
                               <div class="dian"><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="panel1">
                                   <div class="dingw">
                                       <div class="kong1"></div>
                                       <div class="queren" onclick="quere()">确认</div>
                                       <div class="xian"></div>
                                   </div>
                                    <div  class="cpmtem"></div>
                                 </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint" >
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1">  <img src="/assets/images/tishi_4.png">  </div>
                                   <div class="ts_wz1">
                                       选择要分析的1个或多个生产省份，如果选择了多个省份则无法进一步选择生产城市，直接省份间对比。
                                   </div>
                               </div>
                           </div>
                           <!--第一行最后-->
                           <div class="inputz inputz1 yizui">
                               <div class="xiala ye"></div>
                               <div class="dian"><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="panel1">
                                   <div class="dingw">
                                       <div class="kong1"></div>
                                       <div class="queren" onclick="quere()">确认</div>
                                       <div class="xian"></div>
                                   </div>
                                   <div  class="cpmtem"></div>
                               </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint" >
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1">  <img src="/assets/images/tishi_01.png">  </div>
                                   <div class="ts_wz1">
                                     		  选择要分析的1个或多个生产城市，如果指定了一个省则可以进一步选择多个生产城市。
                                   </div>
                               </div>
                           </div>
                            <!--第一行结束-->
                           <!--第二行-->
                           <div for="" class="label2">细分方式：</div>
                           <!--请选择细分方式-->
                           <div class="inputz0 inputz2">
                                   <div class="xiala0 ye0">请选择细分方式</div>
                               <div class="dian0"><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="pane">
                               </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint" >
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1">  <img src="/assets/images/tishi_4.png">  </div>
                                   <div class="ts_wz1">
				                                       按某种方式进行细分展现图形。
				                                       例如：可以把待分析的对象从2016年1月到12月的按时间细分为12个点进行走势查看。
                                   </div>
                               </div>
                           </div>
                           <!--第二行中间售价-->
                           <div class="Custom_price">
                               自定义售价区间：
                               <input type="text"  class="new1" placeholder="按">
                               <input type="text"  class="new1" placeholder="顺">
                               <input type="text"  class="new1" placeholder="序">
                               <input type="text"  class="new1" placeholder="输">
                               <input type="text"  class="new1" placeholder="入">
                               <input type="text"  class="new1" placeholder="值">
                               <div class="hint" >
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1 tishi3">  <img src="/assets/images/tishi_4.png">  </div>
                                   <div class="ts_wz1  ts_wz3">
                                       设置价格区间，比如40,60,100,200,500,800, 区间为：0-40,40-60,60-100,100-200,200-500,500-800,大于800。
                                   </div>
                                   <!--<div class="tishikuang"></div>-->
                               </div>
                           </div>
                           <!--第二行中间-->
                           <div class="inputz erzhong">
                               <div class="xiala ye"></div>
                               <div class="dian"><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="panel1">
                                   <div class="dingw">
                                       <div class="kong1"></div>
                                       <div class="queren" onclick="quere()">确认</div>
                                       <div class="xian"></div>
                                   </div>
                                   <div  class="cpmtem"></div>
                               </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint" >
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1">  <img src="/assets/images/tishi_4.png">  </div>
                                   <div class="ts_wz1">
                                       请选择多个省进行省份间对比。
                                   </div>
                               </div>
                           </div>
                           <!--第二行最后-->
                           <div class="inputz inputz1 erzui">
                               <div class="xiala ye"></div>
                               <div class="dian"><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="panel1">
                                   <div class="dingw">
                                       <div class="kong1"></div>
                                       <div class="queren" onclick="quere()">确认</div>
                                       <div class="xian"></div>
                                   </div>
                                   <div  class="cpmtem"></div>
                               </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint" >
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1">  <img src="/assets/images/tishi_01.png">  </div>
                                   <div class="ts_wz1">
                                       选择要分析的1个或多个生产城市，如果指定了一个省则可以进一步选择多个生产城市。
                                   </div>
                               </div>
                           </div>
                           <!--第二行结束-->
                           <!--第三行-->
                                <!--筛选条件-->
                           <div for="" class="label3">筛选条件：</div>
                                <!--生成的省份-->
                           <div class="inputz inputz3">
                               <div class="xiala ye">请选择生产省份</div>
                               <div class="dian"><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="panel1">
                                   <div class="dingw">
                                       <div class="kong1"></div>
                                       <div class="queren" onclick="quere()">确认</div>
                                       <div class="xian"></div>
                                   </div>
                                   <div  class="cpmtem"></div>
                               </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint" >
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1">  <img src="/assets/images/tishi_4.png">  </div>
                                   <div class="ts_wz1">
                                       选择要分析的1个或多个生产省份，如果选择了多个省份则无法进一步选择生产城市，直接省份间对比。
                                   </div>
                               </div>
                           </div>

                               <!--请选择生成城市-->
                           <div class="inputz inputz4">
                               <div class="xiala ye">请选择生成城市</div>
                               <div class="dian "><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="panel1">
                                   <div class="dingw">
                                       <div class="kong1"></div>
                                       <div class="queren" onclick="quere()">确认</div>
                                       <div class="xian"></div>
                                   </div>
                                   <div  class="cpmtem"></div>
                               </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint" >
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1">  <img src="/assets/images/tishi_01.png">  </div>
                                   <div class="ts_wz1">
                                       选择要分析的1个或多个生产城市，如果指定了一个省则可以进一步选择多个生产城市。
                                   </div>
                               </div>
                           </div>


                           <!--请选择销售省份-->
                           <div class="inputz inputz5">
                               <div class="xiala ye">请选择销售省份</div>
                               <div class="dian "><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="panel1">
                                   <div class="dingw">
                                       <div class="kong1"></div>
                                       <div class="queren" onclick="quere()">确认</div>
                                       <div class="xian"></div>
                                   </div>
                                   <div  class="cpmtem"></div>
                               </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint" >
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1"> <img src="/assets/images/tishi_01.png">  </div>
                                   <div class="ts_wz1">
                                       选择1个或多个销售省份作为筛选条件，不选则不限。
                                   </div>
                               </div>
                           </div>
                           <!--请选择销售城市-->
                           <div class="inputz inputz6">
                               <div class="xiala ye">请选择销售城市</div>
                               <div class="dian "><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="panel1">
                                   <div class="dingw">
                                       <div class="kong1"></div>
                                       <div class="queren" onclick="quere()">确认</div>
                                       <div class="xian"></div>
                                   </div>
                                   <div  class="cpmtem"></div>
                               </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint">
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1 tishi2"><img src="/assets/images/tishi_4.png"></div>
                                   <div class="ts_wz1 ts_wz2">
                                       选择要分析的的1个或多个销售的城市，如果指定了一个省则可以进一步选择多个销售城市。
                                   </div>
                               </div>
                           </div>
                           <!--第四行-->
                           <!--请选择产品-->
                           <div class="inputz inputz7">
                               <div class="xiala ye">请选择产品</div>
                               <div class="dian "><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="panel1">
                                   <div class="dingw">
                                       <div class="kong1"></div>
                                       <div class="queren" onclick="quere()">确认</div>
                                       <div class="xian"></div>
                                   </div>
                                   <div  class="cpmtem"></div>

                               </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint">
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1 "><img src="/assets/images/tishi_01.png"></div>
                                   <div class="ts_wz1 ">
                                       选择要分析的1个或多个产品名称。
                                   </div>
                               </div>
                           </div>
                           <!--请选择年份-->
                           <div class="inputz inputz8">
                               <div class="xiala ye">请选择年份</div>
                               <div class="dian "><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="panel1">
                                   <div class="dingw">
                                       <div class="kong1"></div>
                                       <div class="queren" onclick="quere()">确认</div>
                                       <div class="xian"></div>
                                   </div>
                                   <div  class="cpmtem"></div>

                               </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint">
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1 "><img src="/assets/images/tishi_01.png"></div>
                                   <div class="ts_wz1 ">
                                       选择要分析的1个或多个产品名称。
                                   </div>
                               </div>
                           </div>
                           <!--请选择月份-->
                           <div class="inputz inputz9">
                               <div class="xiala ye">请选择月份</div>
                               <div class="dian"><img  alt="sanjiao"  src="/assets/images/sanjiao_03.png"></div>
                               <div class="kong"></div>
                               <div class="panel1">
                                   <div class="dingw">
                                       <div class="kong1"></div>
                                       <div class="queren" onclick="quere()">确认</div>
                                       <div class="xian"></div>
                                   </div>
                                   <div  class="cpmtem">
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox"  name="1月">1月</div>
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox" name="2月">2月</div>
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox" name="3月">3月</div>
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox" name="4月">4月</div>
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox" name="5月">5月</div>
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox" name="6月">6月</div>
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox" name="7月">7月</div>
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox" name="8月">8月</div>
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox" name="9月">9月</div>
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox" name="10月">10月</div>
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox" name="11月">11月</div>
                                           <div class="xuanxiang" onclick="dianji(this)"><input type="checkbox" name="12月">12月</div>
                                       </div>
                               </div>
                               <span class="hint"><img src="/assets/images/tixing.png"></span>
                               <div class="hint">
                                   <img src="/assets/images/tixing.png" >
                                   <div  class="tishi1 "><img src="/assets/images/tishi_01.png"></div>
                                   <div class="ts_wz1 ">
                                       选择生产时间的一个或多个月份，前提需先选择年份，该项不选择代表全年12个月。
                                   </div>
                               </div>
                           </div>
                            <!--售价最小值-->
                            <input type="text" placeholder="售价最小值" class="mix_price">
                            <span class="l_yi">一</span>
                           <input type="text" placeholder="售价最大值" class="max_price">
                           <div class="hint hint_move">
                               <img src="/assets/images/tixing.png" >
                               <div  class="tishi1 tishi2"><img src="/assets/images/tishi_right.png"></div>
                               <div class="ts_wz1 ts_wz2">
                                   设置售价区间的最大值、最小值，也可以只填其中一项。
                               </div>
                           </div>
                            <div class="clert_1" onclick="clert()">重置</div>
                       </form>
                        <div class="start_analyze" onclick="ks_analyze()">开始分析</div>
                        <div class="unfold_analyze" onclick="zk_analyze()">展开筛选条件</div>


                        <!--展示图形-->
                        <div class="Nagvis">
                            <div >
                                 <label for="name" class="tux"> 展示图形：</label>
                                <div class="ztu">
                                    <div class="ltu"><img alt="zhu" src="../../../../assets/images/icon_01.jpg."></div>
                                    <!--<div>柱状图 Bar</div>-->
                                    <div class="gou"><img alt="zhu" src="../../../../assets/images/gou.png"></div>
                                </div>
                                <div class="ztu">
                                    <div class="ltu"><img alt="zhu" src="../../../../assets/images/icon_02.jpg"></div>
                                    <!--<div>折线图 Line</div>-->
                                    <div class="gou"><img alt="zhu" src="../../../../assets/images/gou.png"></div>
                                </div>
                                <div class="ztu dds" >
                                    <div class="ltu"><img alt="zhu" src="../../../../assets/images/icon_03.jpg"></div>
                                    <!--<div>饼图 Pie</div>-->
                                    <div class="gou"><img alt="zhu" src="../../../../assets/images/gou.png"></div>
                                </div>
                                <div class="ztu">
                                    <div class="ltu"><img alt="zhu" src="../../../../assets/images/icon_04.jpg"></div>
                                    <!--<div>地图 Map</div>-->
                                    <div class="gou"><img alt="zhu" src="../../../../assets/images/gou.png"></div>
                                </div>
                                <div class="tishiyu"></div>
                            </div>
                        </div>
                        <div class="legend">
                            <div id="main1" style="width:100%;height:400px"></div>
                            <div id="main2" style="width:100%;height:400px"></div>
                            <div id="main3" style="width:100%;height:400px"></div>
                            <div id="main4" style=" width:100%;height:400px"></div>
                        </div>
                        <div class="pdf">生成PDF</div>
                <!-- PAGE CONTENT ENDS -->
             </div>

            <!-- /.page-content -->
        </div>
        <!-- /.main-content -->

        <!-- /#ace-settings-container -->
    </div>
    <!-- /.main-container-inner -->

</div>
<!-- /.main-container -->



<script src="/assets/js/jquery-1.10.2.min.js"></script>
<script src="/assets/js/bootstrap.min.js"></script>
<script src="/assets/js/typeahead-bs2.min.js"></script>

<!-- page specific plugin scripts -->

<!--[if lte IE 8]>
<script src="/assets/js/excanvas.min.js"></script>
<![endif]-->

<script src="/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
<script src="/assets/js/jquery.ui.touch-punch.min.js"></script>
<script src="/assets/js/jquery.slimscroll.min.js"></script>
<script src="/assets/js/jquery.easy-pie-chart.min.js"></script>
<script src="/assets/js/jquery.sparkline.min.js"></script>
<script src="/assets/js/flot/jquery.flot.min.js"></script>
<script src="/assets/js/flot/jquery.flot.pie.min.js"></script>
<script src="/assets/js/flot/jquery.flot.resize.min.js"></script>

<!-- ace scripts -->
<script src="/assets/js/ace-elements.min.js"></script>
<script src="/assets/js/ace.min.js"></script>

<script src="/assets/js/jquery.js"></script>
<script src="/assets/js/jquery.min.js"></script>



<!-- inline scripts related to this page -->

<script src="http://echarts.baidu.com/build/dist/echarts.js"></script>
<script src="/assets/js/ksh1.js"></script>
</body>
</html>