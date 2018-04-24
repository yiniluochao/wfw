<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>注册页面</title>
	<link rel="stylesheet" type="text/css" href="/assets/css/sign.css">
	<script type="text/javascript" src="/assets/js/verify.js"></script>
		<!-- basic styles -->

		<link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/assets/css/font-awesome.min.css" />

		<!--[if IE 7]>
		  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
		<![endif]-->





		<!-- ace styles -->

		<link rel="stylesheet" href="/assets/css/ace.min.css" />
		<link rel="stylesheet" href="/assets/css/ace-rtl.min.css" />
</head>
<body>
	<div>
		<div class="signbox">
			<div>
				<p class="title" style="font-size: 22px;font-weight: bold;">微防伪商品信息系统</p>
			</div>
			<div class="sign">
				<p class="title" style="font-size: 18px;font-weight: bold;">企业客户注册</p>
				<font color="red"><?php echo ($error); ?></font>
				<div style="width: 65%;margin: 20px auto 40px auto;">
				<form action="/user/register_deal" enctype="multipart/form-data" method="post">
				<div class="inputbox">
				
					<div>
					<label class="sign_label"><b style="color: red;">*</b>&nbsp;用户名</label><input class="inp" type="text" name="login" placeholder=" 用户名">
					</div>
					<div>
					<label class="sign_label"><b style="color: red;">*</b>&nbsp;密码</label><input class="inp" type="password" name="password" placeholder=" 密码">
					</div>
					<div>
					<label class="sign_label"><b style="color: red;">*</b>&nbsp;确认密码</label><input class="inp" type="password" name="repeatpassword" placeholder=" 确认密码">
					</div>
					<div>
					<label class="sign_label"><b style="color: red;">*</b>&nbsp;企业名称</label><input class="inp" type="text" name="company_name" placeholder=" 企业名称">
					</div>
					<div>
					<label class="sign_label">&nbsp;企业英文名称</label><input class="inp" type="text" name="english_name" placeholder=" 企业英文名称">
					</div>
					<div>
					<label class="sign_label"><b style="color: red;">*</b>&nbsp;企业地址</label><input class="inp" type="text" name="adress" placeholder=" 企业地址">
					</div>
					<div>
					<label class="sign_label"><b style="color: red;">*</b>&nbsp;联系人名称</label><input class="inp" type="text" name="contact_name" placeholder=" 联系人名称">
					</div>
					<div>
					<label class="sign_label"><b style="color: red;">*</b>&nbsp;联系人手机</label><input class="inp" type="text" name="phone_num" placeholder=" 联系人手机">
					</div>
					<div>
					<label class="sign_label"><b style="color: red;">*</b>&nbsp;联系人邮箱</label><input class="inp" type="text" name="email" placeholder=" 联系人邮箱">
					</div>
				    <div>
					<label class="sign_label">&nbsp;组织机构代码</label><input class="inp" type="text" name="original_code" placeholder=" 组织机构代码">
					</div>
					<div>
					<p><b style="color: red;">*</b>&nbsp;营业执照</p>
					<input style="margin-top:10px;" type="file" name="photo">
					</div>
					<div class="check">
					<label><b>输入下方验证码</b>
					<input  class="inp" id="inpcode" name="code"></label>
					<div>
					<label><img src="<?php echo U('Verify/verifier','','');?>" id="code"><a href="javascript:void(change_code(this));" style=""><b>看不清</b></a></label>
					</div>
				</div>
					<button type="submit" id="done">确定</button>
			</div>
				</form>
				</div>
			</div>
		</div>
	</div>
	<script src="/assets/js/jquery-2.0.3.min.js"></script>



		<script type="text/javascript">
			window.jQuery || document.write("<script src='/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
		</script>

		
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='/assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
		</script>
		<script type="text/javascript">
			function show_box(id) {
			 jQuery('.widget-box.visible').removeClass('visible');
			 jQuery('#'+id).addClass('visible');
			}
		</script>
	
	<div style="display:none"><script  src='http://v7.cnzz.com/stat.php?id=155540&web_id=155540' language='JavaScript' charset='gb2312'></script></div>
</body>
</html>