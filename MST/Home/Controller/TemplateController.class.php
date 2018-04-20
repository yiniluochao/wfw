<?php
namespace Home\Controller;
use Think\Controller;

class TemplateController extends Controller{
	
	/*
	 * 显示模板管理
	 */
	public function Template(){
		$this->display();
		
	}
	public function NewTemplate(){
		
		$template_name = I('post.key');
		$this->display();
	}
}