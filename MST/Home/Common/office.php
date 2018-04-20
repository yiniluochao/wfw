<?php
return array(	
    // 显示页面Trace信息
    'SHOW_PAGE_TRACE' =>true, 
    // 关闭多模块访问
    'MODULE_ALLOW_LIST'    =>    array('Home'),
    'DEFAULT_MODULE'     => 'Index', //默认模块
    'DEFAULT_MODULE' => 'Home',
    'URL_MODEL'        => '2',
    //数据库连接
    'DB_TYPE' => 'mysql', // 数据库类型
    'DB_HOST' => 'localhost', // 服务器地址
    'DB_NAME' => 'mst', // 数据库名
    'DB_USER' => 'root', // 用户名
    'DB_PWD' => 'root', // 密码
    'DB_PORT' => 3306, // 端口
    'DB_PREFIX' => 'mst_', // 数据库表前缀
    'DB_CHARSET' => 'utf8', // 字符集
);