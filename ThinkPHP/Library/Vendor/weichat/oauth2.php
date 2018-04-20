<?php
use Home\Controller\WeixintestController;
class Wxapi {
   
    function __construct($re_openid,$api_id,$pec_order_id){
    //do sth here....
    	//global $app_id,$app_secret,$app_mchid,$api_weixin_pay_key;
    	$return_api = get_weixin_configuration($api_id);     //微信配置
    	//dump($return_api);die;
    	$return_hongbao_display = get_hongbao_display($api_id);//红包展示页面配置
    	//dump($app_id);
    	$return_hongbao_configuration = get_hongbao_configuration($api_id,$pec_order_id);
    	//dump($return_hongbao_configuration);die;
    	$this->pay($re_openid,'',$return_api,$return_hongbao_display,$return_hongbao_configuration);
    }
    /**
     * 微信支付
     * 
     * @param string $openid 用户openid
     */
    public function pay($re_openid,$db=null,$return_api,$return_hongbao_display,$return_hongbao_configuration)
    {
    	
    	foreach ($return_api as $val){
    	    $app_id = $val['api_weixin_appid'];   //appid
    		$app_mchid = $val['api_weixin_pay_account']; //商户号
    		$api_weixin_pay_key = $val['api_weixin_pay_key'];        //商户支付密钥
    		$app_secret = $val['api_weixin_aes_key'];		//app_secret
    		$client_ip = $val['api_weixin_client_ip'];
    	}
    	foreach ($return_hongbao_configuration as $val){
    		$send_name = $val['send_name'];
    		$onece_money = $val['onece_money'];
    		$bless_message = $val['bless_message'];
    	}
    	//dump($client_ip);die;
    	//dump($return_api);die;
    	//dump($this->app_mchid);dump($this->app_id);die;
        include_once('WxHongBaoHelper.php');
        $commonUtil = new CommonUtil();
        $wxHongBaoHelper = new WxHongBaoHelper();

        $wxHongBaoHelper->setParameter("nonce_str", $this->great_rand());//随机字符串，丌长于 32 位
        $wxHongBaoHelper->setParameter("mch_billno", $app_mchid.date('YmdHis').rand(1000, 9999));//订单号
        $wxHongBaoHelper->setParameter("mch_id", $app_mchid);//商户号
        $wxHongBaoHelper->setParameter("wxappid", $app_id);
        $wxHongBaoHelper->setParameter("nick_name", $send_name);//提供方名称
        $wxHongBaoHelper->setParameter("send_name", $send_name);//红包发送者名称
        $wxHongBaoHelper->setParameter("re_openid", $re_openid);//相对于医脉互通的openid
        $wxHongBaoHelper->setParameter("total_amount", $onece_money);//付款金额，单位分
        $wxHongBaoHelper->setParameter("min_value", $onece_money);//最小红包金额，单位分
        $wxHongBaoHelper->setParameter("max_value", $onece_money);//最大红包金额，单位分
        $wxHongBaoHelper->setParameter("total_num", 1);//红包収放总人数
        $wxHongBaoHelper->setParameter("wishing", $bless_message);//红包祝福诧
        $wxHongBaoHelper->setParameter("client_ip", $client_ip);//调用接口的机器 Ip 地址
        $wxHongBaoHelper->setParameter("act_name", '红包活动');//活劢名称
        $wxHongBaoHelper->setParameter("remark", '快来抢！');//备注信息
        
        $postXml = $wxHongBaoHelper->create_hongbao_xml($retcode,$reterrmsg,$api_weixin_pay_key);
       //dump($postXml);die;
       // redirect('http://pec.weproduct.cn/MST/Home/View/weixin/hongbao/hongbao.html');
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
        //redirect('http://pec.weproduct.cn/MST/Home/View/weixin/hongbao/hongbao.html');
       
        //redirect('http://www.cdfpds.com/mobile/hongbao.html');
      //  dump($postXml);
        $responseXml = $wxHongBaoHelper->curl_post_ssl($url, $postXml);
        //dump($responseXml);die;
        $weixintest = new WeixintestController();
        $weixintest->hongbao($return_hongbao_display);
       // redirect("http://pec.weproduct.cn/weixintest/hongbao/$return_hongbao_display");
		$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
		//dump($responseObj->return_code);die;
		return $responseObj->return_code;
		
		return;
    }
    /**
     * 获取微信授权链接
     * 
     * @param string $redirect_uri 跳转地址
     * @param mixed $state 参数
     */
    public function get_authorize_url($redirect_uri = '', $state = '')
    {
        $redirect_uri = urlencode($redirect_uri);
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$app_id}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";  
        echo "<script language='javascript' type='text/javascript'>";  
        echo "window.location.href='$url'";  
        echo "</script>";       
    }       
    
    /**
     * 获取授权token
     * 
     * @param string $code 通过get_authorize_url获取到的code
     */
    public function get_access_token($code = '')
    {
        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$app_id}&secret={$app_secret}&code={$code}&grant_type=authorization_code";
        $token_data = $this->http($token_url);
        if(!empty($token_data[0]))
        {
            return json_decode($token_data[0], TRUE);
        }
        
        return FALSE;
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
			$access_url = "https://api.weixin.qq.com/sns/auth?access_token={$access_token}&openid={$open_id}";
			$access_data = $this->http($access_url);
			$access_info = json_decode($access_data[0], TRUE);
			if($access_info['errmsg']!='ok'){
				exit('页面过期');
			}
            $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
            $info_data = $this->http($info_url);  		
            if(!empty($info_data[0]))
            {
                return json_decode($info_data[0], TRUE);
            }
        }
        
        return FALSE;
    }   	
    /**
     * Http方法
     * 
     */ 
    public function http($url)
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

    /**
     * 生成随机数
     * 
     */     
    public function great_rand(){
        $str = '1234567890abcdefghijklmnopqrstuvwxyz';
        for($i=0;$i<30;$i++){
            $j=rand(0,35);
            $t1 .= $str[$j];
        }
        return $t1;    
    }
}
?>