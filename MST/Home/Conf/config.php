<?php
return array(	
    // 显示页面Trace信息
    'SHOW_PAGE_TRACE' =>false, 
    // 关闭多模块访问
    'MODULE_ALLOW_LIST'    =>    array('Home'),
    'DEFAULT_MODULE'     => 'Index', //默认模块
    'DEFAULT_MODULE' => 'Home',
    'URL_MODEL'        => '0',//这是对的服务器是lamp？lnmp?pathinfo重写怎么设置的？      我用的是upupw(实质是lamp)
    'URL_PARAMS_BIND_TYPE'  =>  '1',
	//'URL_ROUTER_ON'   => true,
		
    //数据库连接
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => '127.0.0.1', // 服务器地址
    'DB_NAME' => 'wfw', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => 'root', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_PREFIX' => 'mst_', // 数据库表前缀
    'DB_CHARSET' => 'utf8', // 字符集
    'DEFAULT_FILTER'        =>  'strip_tags,stripslashes',//让I方法使用多种过滤
    'SHOW_ERROR_MSG' => true,
    'LOG_RECORD' => true,
	//自定义success和error的提示页面模板
	'TMPL_ACTION_SUCCESS'=>'Public:dispatch_jump',
	'TMPL_ACTION_ERROR'=>'Public:dispatch_jump',
	'THINK_EMAIL' => array(
		
				'SMTP_HOST' => 'smtp.ym.163.com', //SMTP服务器
		
				'SMTP_PORT' => '465', //SMTP服务器端口
		
				'SMTP_USER' => 'luochao@themistech.cn', //SMTP服务器用户名
		
				'SMTP_PASS' => '275834lc', //SMTP服务器密码
		
				'FROM_EMAIL' => 'luochao@themistech.cn', //发件人EMAIL
		
				'FROM_NAME' => 'luochao', //发件人名称
		
				'REPLY_EMAIL' => 'luochao@themistech.cn', //回复EMAIL（留空则为发件人EMAIL）
		
				'REPLY_NAME' => '', //回复名称（留空则为发件人名称）
		
		)
	
);