<?php if (!defined('THINK_PATH')) exit();?> <!DOCTYPE html>
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
          <li class="active">用户权限管理</li>
        </ul>
        <!-- .breadcrumb --> 
        
        <!-- #nav-search --> 
      </div>
      <div class="page-content"> 
        <!-- /.page-header -->
        
        <div class="row">
          <div class="col-xs-12"> 
            <!-- PAGE CONTENT BEGINS -->
            
            <div class="widget-box">
              <div class="widget-header widget-header-small">
                <h5 class="lighter">用户查找</h5>
              </div>
              <div class="widget-body">
                <div class="widget-main">
                  <form class="form-search" method="post" action="/pecorder/search">
                    <div class="row">
                      <div class="col-xs-12">
                        <div class="input-group">
                          <input class="form-control search-query" name="key" placeholder="订单查找(输入企业名称或组织机构代码）" type="text">
                          <span class="input-group-btn">
                          <button type="submit" class="btn btn-purple btn-sm">查找<i class="icon-search icon-on-right bigger-110"></i> </button>
                          </span> </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- /row -->
            <div class="row">
              <div class="col-xs-12">
                <div class="table-responsive">
                  <table id="sample-table-1" class="table table-striped table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>用户ID</th>
                        <th>用户名</th>
                        <th>企业名称</th>
                        <th>登录权限</th>
                        <th>自动生成权限</th>
                        <th>组织结构代码</th>
                       <th>操作</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                          <td><?php echo ($vo["cid"]); ?></td>
                          <td><?php echo ($vo["login"]); ?></td>
                          <td><?php echo ($vo["company_name"]); ?></td>
                          <td><?php echo ($vo["state"]); ?></td>
                          <td><?php echo ($vo["auto_approval"]); ?></td>
                          <td><?php echo ($vo["original_code"]); ?></td>
                           
                          <td><div class="visible-md visible-lg hidden-sm hidden-xs btn-group">
                                  <a href="/auth/edit/<?php echo ($vo["uid"]); ?>"target="_self" class="btn btn-xs btn-grey"><i class="icon-edit bigger-120"></i>编辑</a>
                                  
                                   
                         </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                  </table>
                  <div class="row text-center">
                    <div class="btn-group" ><?php echo ($page); ?></div>
                  </div>
                </div>
                <!-- /.table-responsive --> 
              </div>
              <!-- /span --> 
            </div>
            
            <!-- PAGE CONTENT ENDS --> 
          </div>
          <!-- /.col --> 
        </div>
        <!-- /.row --> 
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
</body>
</html>