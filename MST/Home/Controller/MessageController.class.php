<?php
namespace Home\Controller;

use Think\Controller;

class MessageController extends Controller{
	/*
	 * 登陆验证
	 */
	public function index(){
		if (!is_login()) {
			$this->error('亲,你还没登录呢!');
		}
	}
 	
	/*
	 * 显示编辑信息页面
	 */
	public function EditMessage(){
		if (!is_login()) {
			$this->error('亲,你还没登录呢!','/user/login');
		}else{
		$cid = get_user_id();
		$condition['cid'] = $cid;
		$message = M('edit_company');
		$data = $message->where($condition)->select();
		//dump($data);die;
		if(null == $data){
			$this->display();
		}else{
			foreach($data as $val){
					
				$this->company_name = $val['company_name'];
				$this->note_information = $val['note_information'];
				$this->company_content = $val['company_content'];
				$this->specific_content = $val['specific_content'];
				$this->photo_url = $val['photo_url'];
					
			}
		
			$this->display();
		}
	}
	}
	/*
	 * 图片上传类
	 */
	function upload($photo_name)
	{
		$upload = new \Think\Upload();// 实例化上传类
		$rand = mt_rand(0, 99);
		$upload->maxSize = 3145728 ;// 设置附件上传大小
		$upload->exts = array('jpg', 'png', 'jpeg');// 设置附件上传类型
		$upload->rootPath = './upload/'; // 设置附件上传根目录
		$upload->savePath = '/company/';
		$upload->saveHash = false;
		$upload->saveName = "company-"."$photo_name".$rand;
		// 上传文件
		$info = $upload->upload();
		
		//dump($info);die;
		//如果没上传就显示系统默认图片 default.png
		/*if(!$info) {// 上传错误提示错误信息
			$this->error($upload->getError());
			return NULL;
		}*/
		foreach($info as $file){
			return $file['savepath'].$file['savename'];
		}
	}
	/*
	 * 处理获取信息
	 */
	public function deal_message(){
		if (!is_login()) {
			$this->error('亲,你还没登录呢!','/user/login');
		}else{
		$cid = get_user_id();
		$company_name = I('post.company_name');
		$company_name = str_replace(' ', '',$company_name);//去空格
		if(null == $company_name){
			$this->error('亲，你还没填写企业英文简称,可任意填写哦！');die;
		}
		//获取相关信息
		$data['cid'] = $cid;
		$data['company_name'] = $company_name;
		$data['note_information'] = I('post.note_information');
		$data['company_content'] = I('post.company_content');
		$data['specific_content'] = I('post.specific_content','', '');
		$data['photo_url'] = $this->upload($data['company_name']);
		
		//dump($data['photo_url']);die();
		$ECompany = M('edit_company');
		$condition['cid'] = $cid;
		$answer = $ECompany->where($condition)->find();
		if(null == $answer){
			//如果第一次编辑，则保存到数据库
			$return = $ECompany->data($data)->add();
			if(null != $return){
				
				$this->redirect('message/PreviewMessage');
			}
			else{
				$this->error('读取失败，请重新编辑!');
				redirect('message/deal_message');
			}
		}else{
			//不是首次编辑，则更新数据库
			
			$return = $ECompany->where($condition)->save($data);
			if(null != $return){
				$this->redirect('message/PreviewMessage');
			}
			else{
				$this->error('更新失败');
			}
		}
	}
		
		}
		
		/*
		 * 显示查看信息页面
		 */
		public function PreviewMessage(){
			if (!is_login()) {
				$this->error('亲,你还没登录呢!','/user/login');
			}else{
			$cid = get_user_id();
			$condition['cid'] = $cid;
			$message = M('edit_company');
			$data = $message->where($condition)->select();
			foreach($data as $val){
				
				$this->company_name = $val['company_name'];
				$this->note_information = $val['note_information'];
				$this->company_content = $val['company_content'];
				$this->specific_content = $val['specific_content'];
				$this->photo_url = $val['photo_url'];
				
			}
			
			$this->display();
			}
		}
		/*
		 * 显示修改信息页面
		 */
		public function ModifyMessage_display(){
			$cid = get_user_id();
			$condition['cid'] = $cid;
			$message = M('edit_company');
			$data = $message->where($condition)->select();
			//dump($data);die;
			if(null == $data){
				$this->error('亲，你还没填写企业信息呢！');
			}else{
			foreach($data as $val){
			
				$this->company_name = $val['company_name'];
				$this->note_information = $val['note_information'];
				$this->company_content = $val['company_content'];
				$this->specific_content = $val['specific_content'];
				$this->photo_url = $val['photo_url'];
			
			}
				
			$this->display();
			}
		}
		/*
		 * 修改信息
		 */
	public function ModifyMessage(){
		$cid = get_user_id();
		$company_name = I('post.company_name');
		$company_name = str_replace(' ', '',$company_name);//去空格
		//获取相关信息
		if(null == $company_name){
			$this->error('亲，你还没填写企业英文简称,可任意填写哦！');die;
		}
		$data['cid'] = $cid;
		$data['company_name'] = $company_name;
		$data['note_information'] = I('post.note_information');
		$data['company_content'] = I('post.company_content');
		$data['specific_content'] = I('post.specific_content','', '');
		$data['photo_url'] = $this->upload($data['company_name']);
		
		//dump($data['photo_url']);die();
		$ECompany = M('edit_company');
		$condition['cid'] = $cid;
		$answer = $ECompany->where($condition)->find();
		$return = $ECompany->where($condition)->save($data);
		if(null != $return){
			$this->redirect('message/PreviewMessage');
		}
		else{
			$this->error('修改失败');
		}
	}
}