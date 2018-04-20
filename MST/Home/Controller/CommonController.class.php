<?php
namespace Home\Controller;

use Think\Controller;
class CommonController extends Controller{
	public function __construct(){
		parent::__construct();
		if (!is_login()) {
			$this->error('请重新没登录!','/user/login');
		}
	}
}