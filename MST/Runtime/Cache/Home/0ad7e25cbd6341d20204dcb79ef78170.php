<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>控制台 微防伪商品信息系统</title>
<meta name="keywords" content="微防伪商品信息系统" />
<meta name="description" content="微防伪商品信息系统" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
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

<script src="/assets/js/ace-extra.min.js"></script>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

<!--[if lt IE 9]>
		<script src="/assets/js/html5shiv.js"></script>
		<script src="/assets/js/respond.min.js"></script>
		<![endif]-->

<script type="text/javascript" charset="utf-8" src="/assets/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/assets/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/assets/ueditor/lang/zh-cn/zh-cn.js"></script>
</head>

<body>
<div class="navbar navbar-default" id="navbar"> 
  <script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
  <div class="navbar-container" id="navbar-container">
    <div class="navbar-header pull-left"> <a href="/" class="navbar-brand"> <img src="/assets/images/logo.png" style="height:60px;"></img> </a><!-- /.brand --> 
   		</div>		
    <!-- /.navbar-header --><!-- /.navbar-header --> 
  </div>
  <!-- /.container --> 
</div>
<div class="main-container" id="main-container"> 
  <script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>
  <div class="main-container-inner">           <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
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
          <li class="active">编辑订单</li>
        </ul>
        <!-- .breadcrumb --> 
        
        <!-- #nav-search --> 
      </div>
      <div class="page-content"> 
        <!-- /.page-header -->
        
        <div class="row">
     <?php if(!empty($errorinfo)): ?><div class="alert alert-block alert-danger">
									<button type="button" class="close" data-dismiss="alert">
										<i class="icon-remove"></i>
									</button>

									<i class="icon-ok green"></i>
                                    出现错误:
									<strong class="green">
										<?php echo ($errorinfo); ?>
									</strong>.	
								</div><?php endif; ?>
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            
            <div class="widget-box">
              <div class="widget-header">
                <h4>修改订单</h4>
                <div class="widget-toolbar"> <a href="#" data-action="collapse"> <i class="icon-chevron-up"></i> </a> </div>
              </div>
              <div class="widget-body">
                <div class="widget-main">
                 
                  <form class="form" method="post" action="/pecorder/edit_deal/<?php echo ($pec_order_id); ?>">
              <fieldset>
              
                
                                          
                     <div class="widget-main">
                  <!-- Text input-->
                  <label class="control-label" for="batch_code">批次代码</label>
                  <div class="controls">
                    <input class="form-control"  type="text" value="<?php echo ($batch_code); ?>" disabled name="batch_code">
                    <p class="help-block"></p>
                  </div>
                   </div>

                  <div class="widget-main">
                  <!-- Text input-->
                  <label class="control-label">申请产品身份码数量</label>
                  <div class="controls">
                    <input class="form-control" <?php echo ($can_edit_sum); ?> disabled type="text" value="<?php echo ($sum); ?>"    name="sum">
                    <p class="help-block"></p>
                  </div>
                   </div>      
                  <div class="widget-main">
                  <!-- Text input-->
                  <label class="control-label">创建时间</label>
                  <div class="controls">
                    <input class="form-control"   disabled type="text" value="<?php echo ($createtime); ?>" name="createtime">
                    <p class="help-block"></p>
                  </div>
                   </div>      
                   
                   <div class="widget-main">
                  <!-- Text input-->
                  <label class="control-label">产品身份码订单流水号</label>
                  <div class="controls">
                    <input class="form-control"  type="text" disabled value="<?php echo ($pec_order_code); ?>" name="pec_order_code">
                    <p class="help-block"></p>
                  </div>
                   </div>     
                           <div class="widget-main">
                  <!-- Text input-->
                  <label class="control-label">订单状态</label>
                  <div class="controls">
                    <input class="form-control" disabled type="text"  <?php echo ($mode); ?> value="<?php echo ($state); ?>" name="state"  >
                    <p class="help-block"></p>
                  </div>
                   </div>        
                   
                    <div class="widget-main">
                  <!-- Text input-->
                  <label class="control-label">是否开启红包功能</label>
                  <div class="controls">
                    
                  <p> <input type="radio" value="1<?php echo ($repair_on); ?>" name="ison" <?php echo ($repair_on); ?>>开启</p>
                   <p> <input type="radio" value="0<?php echo ($repair_off); ?>" name="ison" <?php echo ($repair_off); ?>>关闭</p>
                    <p class="help-block"></p>
                  </div>
                   </div>      
                   
                    <div class="widget-main">
                    
                  <!-- Button -->
                  <div class="controls">
                    <button class="btn btn-success">修改</button>
                       <a type="button" class="btn btn-success" href="javascript:history.go(-1);">返回
                </a>
                  </div>
                </div>                      

       
              </fieldset>
            </form>
                 
                </div>
              </div>
            </div>
            
            
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
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

<!-- inline scripts related to this page --> 

<script type="text/javascript">
    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
    var ue = UE.getEditor('editor');
</script>
</body>
</html>