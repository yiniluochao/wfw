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
<style type="text/css">
.batch_list {
	display: none;
}
</style>
<!--[if lte IE 8]>
		  <link rel="stylesheet" href="/assets/css/ace-ie.min.css" />
		<![endif]-->

<!-- ace settings handler -->

<script src="/assets/js/ace-extra.min.js"></script>
<script src="/assets/js/ajax/Base.js"></script>
<script src="/assets/js/ajax/prototype.js"></script>
<script src="/assets/js/ajax/mootools.js"></script>
<script src="/assets/js/ajax/ThinkAjax.js"></script>
<script src="/assets/js/check.js"></script>
<script type="text/javascript" src="/assets/js/jquery.min.js"></script>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

<!--[if lt IE 9]>
		<script src="/assets/js/html5shiv.js"></script>
		<script src="/assets/js/respond.min.js"></script>
		<![endif]-->

<script type="text/javascript" charset="utf-8"
	src="/assets/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8"
	src="/assets/ueditor/ueditor.all.min.js">
	
</script>
<script type="text/javascript" charset="utf-8"
	src="/assets/ueditor/lang/zh-cn/zh-cn.js"></script>

</head>

<body>
	<div class="navbar navbar-default" id="navbar">
		<script type="text/javascript">
			try {
				ace.settings.check('navbar', 'fixed')
			} catch (e) {
			}
		</script>
		<div class="navbar-container" id="navbar-container">
			<div class="navbar-header pull-left">
				<a href="/" class="navbar-brand"> <img
					src="/assets/images/logo.png" style="height: 60px;"></img>
				</a>
				<!-- /.brand -->
			</div>
			<!-- /.navbar-header -->
			<!-- /.navbar-header -->
		</div>
		<!-- /.container -->
	</div>
	<div class="main-container" id="main-container">
		<script type="text/javascript">
			try {
				ace.settings.check('main-container', 'fixed')
			} catch (e) {
			}
		</script>
		<div class="main-container-inner">
			          <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
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
						try {
							ace.settings.check('breadcrumbs', 'fixed')
						} catch (e) {
						}
					</script>
					<ul class="breadcrumb">
						<li><i class="icon-home home-icon"></i> <a href="/">首页</a></li>
						<li class="active">添加批次</li>
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

							<i class="icon-ok green"></i> 出现错误: <strong class="green">
								<?php echo ($errorinfo); ?> </strong>.
						</div><?php endif; ?>
						<div class="col-xs-12">
							<!-- PAGE CONTENT BEGINS -->

							<div class="widget-box">
								<div class="widget-header">
									<h4>添加产品身份码订单信息</h4>
								</div>
								<div class="widget-body">
									<div class="widget-main">
										<form class="form" method="post" enctype="multipart/form-data"
											action="/pecorder/newone_deal">
											<fieldset>

												<!-- Text input-->
												<div class="widget-main" style="float:left;width:100%;clear: both;">
												<div class="col-md-4">
													<label class="control-label" for="product_code">选择产品</label>
													<div class="controls">
														<select id="rt3" name="product_code"
															onchange="getBatchCode();">
															<option selected="selected">请选择产品</option>
															<?php if(is_array($plist)): $i = 0; $__LIST__ = $plist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value=<?php echo ($vo["product_name"]); ?>><?php echo ($vo["product_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
														</select>

													</div>
												</div>
												<div class="col-md-8 batch_list">
													<label class="control-label" for="batch_code">选择批次代码</label>
													<div class="controls">
													
														<select id="rt2" name="batch_code">
																
														</select><label class="text-danger" style="margin-left:20px;"> 若不选择或无批次,系统将自动创建批次，以"auto"为标记</label>

													</div>
												</div>
												</div>



												<div class="widget-main" style="clear: both;">
													<!-- Text input-->
													<div class="tabbable">
														<ul id="myTab" class="nav nav-tabs">
															<li class="active"><a href="#default_box"
																data-toggle="tab">默认产品身份码</a></li>
															<li><a href="#leadin_box" data-toggle="tab">导入产品身份码</a>
															</li>
														</ul>

														<div class="tab-content">
															<div class="tab-pane in active" id="default_box">
																<label class="control-label">产品身份码位数(产品身份码库存)</label>
																<div class="controls">
																	<select id="rtl" name="rtl">
																		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><!--  <option value="8">8 位产品身份码(库存 <?php echo ($vo["eight"]); ?>)</option>
																		<option value="9">9 位产品身份码(库存<?php echo ($vo["nine"]); ?>)</option>
																		<option value="10">10 位产品身份码(库存<?php echo ($vo["ten"]); ?>)</option>
																		<option value="11">11 位产品身份码(库存<?php echo ($vo["eleven"]); ?>)</option>-->
																		<option value="12">12 位产品身份码(库存<?php echo ($vo["twelve"]); ?>)</option><?php endforeach; endif; else: echo "" ;endif; ?>
																	</select>

																</div>
																<label class="control-label">申请产品身份码数量</label>
																<div class="controls">
																	<input class="col-md-3" type="text" value="<?php echo ($sum); ?>"
																		name="sum">
																	<p class="help-block"></p>
																</div>
															</div>
															<div class="tab-pane" id="leadin_box">
																<div class="leadin_box">
																	<input type="file" name="file">请选择文件
																</div>
															</div>
														</div>
													</div>

												</div>
												<div class="widget-main" style="clear: both;">
													<!-- Text input-->
													<label class="control-label">是否开启红包功能</label>
													<div class="controls">
														<label> <input name="ison"
															class="ace ace-switch ace-switch-4" type="checkbox">
															<span class="lbl"></span>
														</label>
													</div>
												</div>






												<div class="widget-main">

													<!-- Button -->
													<div class="controls">
														<button class="btn btn-success">提交</button>
														<a type="button" class="btn btn-inverse"
															href="javascript:history.go(-1);">返回 </a>
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

		//选择批次代码选项
		$("select[name='product_code']").change(function() {
			$(".batch_list").show();
		});
	</script>
	<script type="text/javascript">
		function remove() {
			var obj = document.getElementByIdx_x('rt2');
			obj.options.length = 0;
		}
		function getBatchCode() {

			var product_name = $('#rt3').val();

			//alert(product_name);
			$.ajax({
				url : "/pecorder/getBatchCodeByProductCode",
				type : "POST",
				data : "prduct_name=" + product_name,
				dataType : "json",
				success : function(data) {

					var obj = document.all("rt2");
					for (var i = obj.options.length - 1; i >= 0; i--) {
						obj.options.remove(i); //从后往前删除
					}
					$('#rt2').append("<option selected='selected' value='auto'>请选择批次代码(auto)</option>");
					for (var i = 0; i < data.length; i++) {
						$('#rt2').append(
								"<option visibility='visible' value='"+data[i]+"'>"
										+ data[i] + "</option>");
						//f.add(new Option(data[i]));
					}

				}

			});
		}
	</script>
</body>
</html>