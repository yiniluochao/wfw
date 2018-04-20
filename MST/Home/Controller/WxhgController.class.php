<?php
namespace Home\Controller;
use Think\Controller;

/*
 * 发红包
 */
class WxhgController extends Controller{
	
	private $app_id = 'wxef710346c5ca8296';
	private $app_secret = '7a996cad786017e41f2e22f48f113c8d';
	private $app_mchid = '1239022102';

	/*
	 * 入口
	 */
	public function index(){
		
		//获取用户信息
		$get = $_GET['param'];//access_token
		$code = $_GET['code'];
		$code = I('code');
		Log::write($code);
		//判断code是否存在
		if($get=='access_token' && !empty($code)){
			$param['param'] = 'access_token';
			$param['code'] = $code;
			//获取用户openid信息
			$userinfo = $this->_route('userinfo',$param);
			if(empty($userinfo['openid'])){
				exit("NOAUTH");
			}
			//调取支付方法
			$this->_route('wxpacket',array('openid'=>$userinfo['openid']));
		}else{
			$this->_route('userinfo');
		}
		
	}
	public function _route($fun,$param = ''){
		switch ($fun)
		{
			case 'userinfo':
				return $this->userinfo($param);
				break;
			case 'wxpacket':
				return $this->wxpacket($param);
				break;
			default:
				exit("Error_fun");
		}
	}
	/**
	 * 用户信息
	 *
	 */
	private function userinfo($param){
		$get = $param['param'];//access_taken
		$code = $param['code'];
		if($get=='access_token' && !empty($code)){
			$json = $this->get_access_token($code);
			if(!empty($json)){
				$userinfo = $this->get_user_info($json['access_token'],$json['openid']);
				return $userinfo;//返回授权后微信用户信息
			}
		}else{
			$this->get_authorize_url('http://127.0.0.1/index.php?param=access_token','STATE');
		}
	}
	/**
	 * 微信红包
	 *
	 */
	private function wxpacket($param){
		return $this->pay($param['openid']);
	}
	
	/*************************************************
	 * *
	 * *
	 * *
	 * ***********************************************/
	
	/**
	 * 获取授权token
	 *
	 * @param string $code 通过get_authorize_url获取到的code
	 */
	public function get_access_token($code = '')
	{
		$token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->app_id}&secret={$this->app_secret}&code={$code}&grant_type=authorization_code";
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
	 * 获取微信授权链接
	 *
	 * @param string $redirect_uri 跳转地址
	 * @param mixed $state 参数
	 */
	public function get_authorize_url($redirect_uri = '', $state = '')
	{
		$redirect_uri = urlencode($redirect_uri);
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->app_id}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state={$state}#wechat_redirect";
		echo "<script language='javascript' type='text/javascript'>";
				echo "window.location.href='$url'";
				echo "</script>";
	}
	
	/**
	 * 微信支付
	 *
	 * @param string $openid 用户openid
	 */
	public function pay($re_openid,$db=null)
	{
		$this->setParameter("nonce_str", $this->great_rand());//随机字符串，丌长于 32 位
		$this->setParameter("mch_billno", $this->app_mchid.date('YmdHis').rand(1000, 9999));//订单号
		$this->setParameter("mch_id", $this->app_mchid);//商户号
		$this->setParameter("wxappid", $this->app_id);
		$this->setParameter("nick_name", '红包');//提供方名称
		$this->setParameter("send_name", '红包');//红包发送者名称
		$this->setParameter("re_openid", $re_openid);//相对于医脉互通的openid
		$this->setParameter("total_amount", 100);//付款金额，单位分
		$this->setParameter("min_value", 100);//最小红包金额，单位分
		$this->setParameter("max_value", 100);//最大红包金额，单位分
		$this->setParameter("total_num", 1);//红包収放总人数
		$this->setParameter("wishing", '恭喜发财');//红包祝福诧
		$this->setParameter("client_ip", '127.0.0.1');//调用接口的机器 Ip 地址
		$this->setParameter("act_name", '红包活动');//活劢名称
		$this->setParameter("remark", '快来抢！');//备注信息
		$postXml = $this->create_hongbao_xml();
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';
		$responseXml = $this->curl_post_ssl($url, $postXml);
		$responseObj = simplexml_load_string($responseXml, 'SimpleXMLElement', LIBXML_NOCDATA);
		return $responseObj->return_code;
	
		return;
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
	
	function setParameter($parameter, $parameterValue) {
		$parameters[trimString($parameter)] = trimString($parameterValue);
	}
	function getParameter($parameter) {
		return $parameters[$parameter];
	}
	
	function curl_post_ssl($url, $vars, $second=30,$aHeader=array())
	{
		$ch = curl_init();
		//超时时间
		curl_setopt($ch,CURLOPT_TIMEOUT,$second);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		//这里设置代理，如果有的话
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
	
		//cert 与 key 分别属于两个.pem文件
		curl_setopt($ch,CURLOPT_SSLCERT,dirname(__FILE__).DIRECTORY_SEPARATOR.'zhengshu'.DIRECTORY_SEPARATOR.'apiclient_cert.pem');
		curl_setopt($ch,CURLOPT_SSLKEY,dirname(__FILE__).DIRECTORY_SEPARATOR.'zhengshu'.DIRECTORY_SEPARATOR.'apiclient_key.pem');
		curl_setopt($ch,CURLOPT_CAINFO,dirname(__FILE__).DIRECTORY_SEPARATOR.'zhengshu'.DIRECTORY_SEPARATOR.'rootca.pem');
	
	
		if( count($aHeader) >= 1 ){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader);
		}
	
		curl_setopt($ch,CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$vars);
		$data = curl_exec($ch);
		if($data){
			curl_close($ch);
			return $data;
		}
		else {
			$error = curl_errno($ch);
			curl_close($ch);
			return false;
		}
	}
	/*
	 * 产生红包
	 */
	function create_hongbao_xml($retcode = 0, $reterrmsg = "ok"){
		try {
			$this->setParameter('sign', $this->get_sign());
			
			return  $this->arrayToXml($this->parameters);
			 
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
	
	}
	/**
	 例如：
	 appid：    wxd111665abv58f4f
	 mch_id：    10000100
	 device_info：  1000
	 Body：    test
	 nonce_str：  ibuaiVcKdpRxkhJA
	 第一步：对参数按照 key=value 的格式，并按照参数名 ASCII 字典序排序如下：
	 stringA="appid=wxd930ea5d5a258f4f&body=test&device_info=1000&mch_i
	 d=10000100&nonce_str=ibuaiVcKdpRxkhJA";
	 第二步：拼接支付密钥：
	 stringSignTemp="stringA&key=192006250b4c09247ec02edce69f6a2d"
	 sign=MD5(stringSignTemp).toUpperCase()="9A0A8659F005D6984697E2CA0A
	 9CF3B7"
	 */
	protected function get_sign(){
		define('PARTNERKEY',"fpdskuangfuluochao12345678912345");
		try {
			if (null == PARTNERKEY || "" == PARTNERKEY ) {
				throw new SDKRuntimeException("密钥不能为空！" . "<br>");
			}
			if($this->check_sign_parameters() == false) {   //检查生成签名参数
				throw new SDKRuntimeException("生成签名参数缺失！" . "<br>");
			}
		
			ksort($parameters);
			$unSignParaString = $this->formatQueryParaMap($this->parameters, false);
	
		
			return $this->sign($unSignParaString,trimString(PARTNERKEY));
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
	
	}
	function arrayToXml($arr)
	{
		$xml = "<xml>";
		foreach ($arr as $key=>$val)
		{
			if (is_numeric($val))
			{
				$xml.="<".$key.">".$val."</".$key.">";
	
			}
			else{
				$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
			}
		}
		$xml.="</xml>";
		return $xml;
	}
	
	function check_sign_parameters(){
		if($parameters["nonce_str"] == null ||
				$parameters["mch_billno"] == null ||
				$parameters["mch_id"] == null ||
				$parameters["wxappid"] == null ||
				$parameters["nick_name"] == null ||
				$parameters["send_name"] == null ||
				$parameters["re_openid"] == null ||
				$parameters["total_amount"] == null ||
				$parameters["max_value"] == null ||
				$parameters["total_num"] == null ||
				$parameters["wishing"] == null ||
				$parameters["client_ip"] == null ||
				$parameters["act_name"] == null ||
				$parameters["remark"] == null ||
				$parameters["min_value"] == null
		)
		{
			return false;
		}
		return true;
	
	}
	function formatQueryParaMap($paraMap, $urlencode){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v){
			if (null != $v && "null" != $v && "sign" != $k) {
				if($urlencode){
					$v = urlencode($v);
				}
				$buff .= $k . "=" . $v . "&";
			}
		}
		$reqPar;
		if (strlen($buff) > 0) {
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	function sign($content, $key) {
		try {
			if (null == $key) {
				throw new SDKRuntimeException("签名key不能为空！" . "<br>");
			}
			if (null == $content) {
				throw new SDKRuntimeException("签名内容不能为空" . "<br>");
			}
			$signStr = $content . "&key=" . $key;
	
			return strtoupper(md5($signStr));
		}catch (SDKRuntimeException $e)
		{
			die($e->errorMessage());
		}
	}
}