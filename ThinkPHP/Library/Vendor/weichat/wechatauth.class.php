<?php
/**
 * 微信授权相关接口
 *
 *
 */

class wechatauth {

	//高级功能-》开发者模式-》获取
//	private $app_id = 'wxef710346c5ca8296';
//	private $app_secret = '7a996cad786017e41f2e22f48f113c8d';
    function __construct(){
    	
    }

	/**
	 * 获取微信授权链接
	 *
	 * @param string $redirect_uri 跳转地址
	 * @param mixed $state 参数
	 */
	public function get_authorize_url($redirect_uri = '', $api_id_key = '')
	{
		//dump($api_id_order_id);die;
		$state = substr($api_id_key, 0,5);
		$return_api = get_weixin_configuration($state);
		//dump($return_api);
		foreach ($return_api as $val){
			$app_id = $val['api_weixin_appid'];   //appid
			//$app_mchid = $val['api_weixin_pay_account']; //商户号
			//$api_weixin_pay_key = $val['api_weixin_pay_key'];        //商户支付密钥
			//$app_secret = $val['api_weixin_aes_key'];		//app_secret
		}
		$redirect_uri = urlencode($redirect_uri);
		//dump($app_id);
		return "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$app_id}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state={$api_id_key}#wechat_redirect";
	}

		/**
		* 获取授权token
		 *
		 * @param string $code 通过get_authorize_url获取到的code
		 */
		 public function get_access_token($app_id = '', $app_secret = '', $code = '',$api_id)
		 {
		 	$return_api = get_weixin_configuration($api_id);
		 	//dump($return_api);die;
		 	foreach ($return_api as $val){
		 		$app_id = $val['api_weixin_appid'];   //appid
		 		//$app_mchid = $val['api_weixin_pay_account']; //商户号
		 		//$api_weixin_pay_key = $val['api_weixin_pay_key'];        //商户支付密钥
		 		$app_secret = $val['api_weixin_aes_key'];		//app_secret
		 	}
		 $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$app_id}&secret={$app_secret}&code={$code}&grant_type=authorization_code";
		 $token_data = $this->http($token_url);
			//dump($token_data);die;
		 if(null != $token_data)
		 {		//dump($token_data);die;
		 	
		 	return json_decode($token_data[0],true);
		 }else{

		 	return FALSE;}
		 }

		 /**
		 * 获取授权后的微信用户信息
		 	*
		 	* @param string $access_token
		 	* @param string $open_id
		 	*/
		 	public function get_user_info($access_token = '', $open_id = '')
		 	{
		 	if($access_token && $open_id)
		 	{
		 	$info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
		 	$info_data = $this->http($info_url);
			//dump($info_data);die;
		 	if(null != $info_data[0])
		 	{
		 	return json_decode($info_data[0], TRUE);
		 	}
		 	}

		 	return FALSE;
		 	}
		 	/**
		 	* 验证授权
		 	*
		 	* @param string $access_token
		 		* @param string $open_id
		 		*/
		 		public function check_access_token($access_token = '', $open_id = '')
		 		{
		 		 if($access_token && $open_id)
		 		 {
		 		 $info_url = "https://api.weixin.qq.com/sns/auth?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
		 		 $info_data = $this->http($info_url);
				 //dump($info_data);die;
		 		 if($info_data[0] == 200)
		 			{
		 			return json_decode($info_data[1], TRUE);
		 		 }
		 		 }

		 		 return FALSE;
		 		 }
		 		 //curl
		 		 public function http($url, $method, $postfields = null, $headers = array(), $debug = false)
		 		 {
		 		 	$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$output = curl_exec($ch);//输出内容
		curl_close($ch);
		return array($output);
		 	}

		 	}