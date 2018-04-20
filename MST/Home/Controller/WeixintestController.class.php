<?php
namespace Home\Controller;

use Think\Controller;


class WeixintestController extends Controller{
	
	/*private $app_id = 'wxef710346c5ca8296';
	private $app_secret = '7a996cad786017e41f2e22f48f113c8d';
	private $app_mchid = '1239022102';*/
	
	
	
	//微信返回字符串
	function wxrurl(){
		//dump($_GET);die;
		$state = $_GET['state'];
		//dump($state);
		$api_id = substr($state,0,5);
		//dump($api_id);
		$pec_order_id = substr($state, 5,3);
		//dump($pec_order_id);
		$key = substr($state, 8);
		//dump($key);
		if(null == $_GET){
			$this->error('fail');
		}
		//Vendor('weichat.wechatauth');
		include_once '\ThinkPHP\Library\Vendor\weichat\wechatauth.class.php';
		$Wechat = new \wechatauth();
		
		//print_r($_GET); //授权成功后跳转到此页面获取的信息
		//dump($_GET);die;
		//dump($_GET['code']);
		$token = $Wechat->get_access_token('','',$_GET['code'],$api_id); //确认授权后会，根据返回的code获取token
		//dump($token);die;
		//dump($token['access_token']);
		//dump($token['openid']);
		session('token',$token); //保存授权信息
		$user_info = $Wechat->get_user_info($token['access_token'],$token['openid']); //获取用户信息
		//dump($user_info);die;//成功获取用户信息
		/*
		 * 是否领取红包
		 */
		$type = M('pec_order')->where("pec_order_id = '$pec_order_id'")->getField('type');
		if(0 == $type || null == $type){
			$this->error('此产品未开启红包功能!');die;
		}
		
		$datas['openid'] = $user_info['openid'];
		$datas['nickname'] = $user_info['nickname'];
		$datas['sex'] = $user_info['sex'];
		$datas['language'] = $user_info['language'];
		$datas['city'] = $user_info['city'];
		$datas['province'] = $user_info['province'];
		$datas['country'] = $user_info['country'];
		$datas['ishongbao'] = 1;
		$datas['key'] = $key;
		
		
		$uid = M('api_list')->where("api_id='$api_id'")->getField('api_uid');
		$datas['uid'] = $uid;
	
			
		//M('hongbao_user_isget')->add($datas);
		$conditions['uid'] = $uid;
		$conditions['key'] = $key;
		$conditions['openid'] = $user_info['openid'];
	    $return_ishongbao = M('hongbao_user_isget')->where($conditions)->getField('ishongbao');
		if(null == $return_ishongbao){
			M('hongbao_user_isget')->add($datas);
		}else{
			$this->error('您已领取该产品红包!');die;
		}
		
		$hongbao_send_sum = M('hongbao_sum_statistic')->where("uid = '$uid' and pec_order_id='$pec_order_id'")->getField('hongbao_send_sum');
		if(null == $hongbao_send_sum){
			
			$data['hongbao_send_sum'] = 1;
			M('hongbao_sum_statistic')->where("uid = '$uid' and pec_order_id='$pec_order_id'")->save($data);
		}else{
			$hongbao_send_sum += 1;
			$data['hongbao_send_sum'] = $hongbao_send_sum;
			M('hongbao_sum_statistic')->where("uid = '$uid' and pec_order_id='$pec_order_id'")->save($data);
		}
		//dump($token['openid']);die;
		$this->send_hg($token['openid'],$api_id,$pec_order_id);
	}
	/*
	 * 发红包
	 */
	function send_hg($re_openid,$api_id,$pec_order_id){
		include_once '\ThinkPHP\Library\Vendor\weichat\oauth2.php';
		
		//$api_id = session('api_id');
		//dump($api_id);die;
		$Wxapi = new \Wxapi($re_openid,$api_id,$pec_order_id);
		//$Wxapi->pay($re_openid);
		
	}
	/*
	 * 显示微信端红包页面
	 */
	function hongbao($return_hongbao_display){
		//dump($return_hongbao_display);die;
		foreach ($return_hongbao_display as $val){
			$this->company_name = $val['company_name'];
			$this->company_logo = $val['company_logo'];
		}
		//dump($val['company_logo']);die;
		$this->display("hongbao");
	}
		
}