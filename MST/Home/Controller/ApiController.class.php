<?php

namespace Home\Controller;

use Think\Controller;

class ApiController extends Controller {
	
	/**
	 * 用户登录时接口列表页面显示
	 */
	public function manager() {
		if (true == is_login ()) {
			$uid = get_user_id ();
			$user_type = get_user_type ();
			$this->page_id = 10;
			$api_list = M ( 'api_list' );
			if (0 == $user_type) {
				$arrange = "api_uid=$uid";
			} else {
				$arrange = "";
			}
			$list = $api_list->where ( $arrange )->order ( "createtime desc" )->select ();
			$this->assign ( 'list', $list ); // 赋值数据集
			$this->display ( 'manager' );
		} else {
			$this->__userNotLogin ();
		}
	}
	
	/**
	 * 用户登录时编辑页面显示
	 *
	 * @param unknown_type $api_id        	
	 */
	public function edit($api_id = 0) {
		if (true == is_login ()) {
			// 登录情况下
			if (0 == $api_id) {
				// api_id异常
				$this->display ( 'manager' );
				return;
			}
			// 数据加载
			$this->api_list = M ( 'api_list' );
			$this->api_id = $api_list ['api_id'];
			$this->api_uid = $api_list ['api_uid'];
			$this->api_weixin = $api_list ['api_weixin'];
			$this->api_login = $api_list ['api_login'];
			$this->api_password = $api_list ['api_password'];
			$this->api_encodemode = $api_list ['api_encodemode'];
			$this->api_encode_key = $api_list ['api_encode_key'];
			$this->api_weixin_appid = $api_list ['api_weixin_appid'];
			$this->api_weixin_token = $api_list ['api_weixin_token'];
			$this->api_weixin_aes_key = $api_list ['api_weixin_aes_key'];
			$this->api_other_url = $api_list ['api_other_url'];
			$this->api_user_ips = $api_list ['api_user_ips'];
			$this->createtime = $api_list ['createtime'];
			// 最后做数据渲染
			$this->display ();
		} else {
			$this->__userNotLogin ();
		}
	}
	
	/**
	 * 产品身份码查询入口
	 *
	 * @param unknown_type $api_id        	
	 */
	public function index($api_id = 0) {
		session ( 'api_id', $api_id );
		$api_list = M ( 'api_list' );
		$api_result = $api_list->where ( "api_id=$api_id" )->find ();
		$api_login = $api_result ['api_login'];
		if (null == $api_result) {
			exit ( "no door" );
		}
		// 缓存当前接口对应的企业id，后续比对用
		session ( 'api_uid', $api_result ['api_uid'] );
		session ( 'api_login', $api_login );
		$token = $api_result ['api_weixin_token']; // token
		
		$this->valid ( $token );
		session ( 'token', $token ); // 打开，不确定
		if ($this->from_weixin ()) {
			// 来自微信
			$APPID = $api_result ['api_weixin_appid'];
			
			$APPSECRET = $api_result ['api_weixin_aes_key'];
			$tokenarr = getAccessToken ( $APPID, $APPSECRET );
			// $return = weixin_menu_fpds($APPID,$APPSECRET,$tokenarr);
			// var_dump($return);
			// echo createMenu($tokenarr['access_token'],$data);
			if ($api_login == "themistech") {
				
				$tokenarr = getAccessToken ( $APPID, $APPSECRET );
				$return = weixin_menu_fpds ( $APPID, $APPSECRET, $tokenarr );
				var_dump ( $return );
			} else if ($api_login == "老坑洮砚") {
				
				$tokenarr = getAccessToken ( $APPID, $APPSECRET );
				$return = weixin_menu_laokentaoyan ( $APPID, $APPSECRET, $tokenarr );
				var_dump ( $return );
			} else if ($api_login == "御芝林") {
				// $APPID = "wxbcbb70349a9d44df";
				// $APPSECRET = "cd6efea865b842703d36f89a78c87282";
				$tokenarr = getAccessToken ( $APPID, $APPSECRET );
				$return = weixin_menu_yuzhilin ( $APPID, $APPSECRET, $tokenarr );
				var_dump ( $return );
			} else if ($api_login == "GSTYXH") {
				
				$tokenarr = getAccessToken ( $APPID, $APPSECRET );
				$return = weixin_menu_GSTYXH ( $APPID, $APPSECRET, $tokenarr );
				var_dump ( $return );
			}
			
			$weixin_result = $this->deal_weixin ( $api_result, $api_login );
		} else {
			// 来自浏览器远程接口调用
			$remote_result = $this->deal_remote ( $api_result );
			echo $remote_result;
			exit ();
		}
	}
	public function from_weixin() {
		return true;
	}
	
	/**
	 * 动态验证微信接口
	 *
	 * @param unknown_type $token        	
	 */
	public function valid($token) {
		if (isset ( $_GET ["echostr"] )) {
			if ($this->checkSignature ( $token )) {
				exit ( $_GET ['echostr'] );
			}
		}
	}
	/**
	 * 动态验证token
	 *
	 * @param unknown_type $token        	
	 * @return boolean
	 */
	private function checkSignature($token) {
		$signature = $_GET ["signature"];
		$timestamp = $_GET ["timestamp"];
		$nonce = $_GET ["nonce"];
		
		$tmpArr = array (
				$token,
				$timestamp,
				$nonce 
		);
		// use SORT_STRING rule
		sort ( $tmpArr, SORT_STRING );
		$tmpStr = implode ( $tmpArr );
		$tmpStr = sha1 ( $tmpStr );
		
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 获取微信数据明文,有bug
	 */
	public function get_data_by_weixin($postStr, $api_result) {
		$nonce = $_GET ['nonce'];
		$timeStamp = $_GET ['timeStamp'];
		$msg_signature = $_GET ['msg_signature'];
		$api_encodemode = $api_result ['api_encodemode'];
		$appId = $api_result ['api_weixin_appid'];
		$token = $api_result ['api_weixin_token'];
		$encodingAesKey = $api_result ['api_weixin_aes_key'];
		
		if (1 == $api_encodemode) {
			$pc = new WXBizMsgCrypt ( $token, $encodingAesKey, $appId );
			// aes解密
			$encryptMsg = $postStr;
			$xml_tree = new DOMDocument ();
			$xml_tree->loadXML ( $encryptMsg );
			$array_e = $xml_tree->getElementsByTagName ( 'Encrypt' );
			$array_s = $xml_tree->getElementsByTagName ( 'MsgSignature' );
			$encrypt = $array_e->item ( 0 )->nodeValue;
			$msg_sign = $array_s->item ( 0 )->nodeValue;
			
			$format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
			$from_xml = sprintf ( $format, $encrypt );
			
			// 第三方收到公众号平台发送的消息
			$result = "";
			$errCode = $pc->decryptMsg ( $msg_sign, $timeStamp, $nonce, $from_xml, $result );
			if ($errCode != 0) {
				$result = $errCode;
			}
		} else {
			$result = $postStr;
		}
		return $result;
	}
	
	/**
	 * 验证接口用户
	 * 分为ip验证，身份验证两步
	 *
	 * @param unknown_type $api_result        	
	 */
	public function deal_remote($api_result) {
		$api_user_ips = $api_result ['api_user_ips'];
		if (null != $api_user_ips) {
			// 如果有ip白名单，则进行ip验证
			valid_ips ( $api_user_ips );
		}
		// 获取远程接口的明文信息
		$plain_data = $this->get_data_by_remote ( $api_result );
		// 获取消息，解密。身份验证
		$api_login = $api_result ['api_login'];
		$api_password = $api_result ['api_password'];
		$checked = $this->check_login_pw ();
		if (true == $checked) {
			// 获取产品身份码查询结果
			$remote_result = $this->pec_search ( $plain_data );
			return $remote_result;
		}
		return "remote failed";
	}
	public function get_data_by_remote($api_result) {
		$plain_data = "remote access ";
		return $plain_data;
	}
	
	/**
	 * 验证远程接口身份
	 */
	public function check_login_pw() {
		return false;
	}
	/**
	 * 根据接口数据和微信发来的数据进行处理
	 *
	 * @return 返回非空值则由
	 */
	public function deal_weixin($api_result, $api_login) {
		$separator = "\r\n";
		// get post data, May be due to the different environments
		$postStr = file_get_contents ( "php://input" );
		
		// extract post data
		if (! empty ( $postStr )) {
			// 如果是密文，则获取明文
			try {
				
				// $postStr = $this->get_data_by_weixin($postStr,$api_result);
				// 明文处理方式
				/*
				 * libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
				 * the best way is to check the validity of xml by yourself
				 */
				libxml_disable_entity_loader ( true );
				$postObj = simplexml_load_string ( $postStr, 'SimpleXMLElement', LIBXML_NOCDATA );
				$fromUsername = $postObj->FromUserName;
				
				session_start ();
				session_id ( $fromUsername );
				$_SESSION ['username'] = $fromUsername;
				
				$toUsername = $postObj->ToUserName;
				$event = $postObj->Event;
				$type = $postObj->MsgType;
				$keyword = $event == 'CLICK' ? $postObj->EventKey : trim ( $postObj->Content );
				if ($event == "subscribe") {
					$time = time ();
					$msgType = "text";
					$textTpl = "<xml>
         <ToUserName><![CDATA[%s]]></ToUserName>
         <FromUserName><![CDATA[%s]]></FromUserName>
         <CreateTime>%s</CreateTime>
         <MsgType><![CDATA[%s]]></MsgType>
         <Content><![CDATA[%s]]></Content>
         <FuncFlag>0</FuncFlag>
         </xml>";
					if ($api_login == "themistech") {
						$textContent = "欢迎关注成都菲普迪斯产品认证平台,您可输入产品身份码进行查询!
您可输入'你好'后直接留言，客服将通过微信直接回复您" . "$separator" . "" . "";
						$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
						echo $resutlStr;
						exit ();
					}
					if ($api_login == "老坑洮砚") {
						$textContent = "欢迎关注老坑洮砚产品认证平台,您可输入产品身份码进行查询!" . "$separator";
						$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
						echo $resutlStr;
						exit ();
					} else if ($api_login == "fpds") {
						$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxef710346c5ca8296&redirect_uri=http://pec.weproduct.cn/Home/Wxhg/index&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
						$textContent = "欢迎关注成都菲普迪斯产品认证平台,您可输入产品身份码进行查询!
您可输入'你好'后直接留言，客服将通过微信直接回复您" . "$separator" . "" . "";
						$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
						echo $resutlStr;
						exit ();
					} else if ($api_login == "御芝林") {
						$textContent = "亲，你来了？终于来了。关注养生，什么时候都不晚。点一下菜单，开始了解吧。";
						$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
						echo $resutlStr;
						exit ();
					}else if ($api_login == "GSTYXH") {
						$textContent = "您好！欢迎关注甘肃省洮砚协会，我们将为您竭诚服务！";
						$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
						echo $resutlStr;
						exit ();
					}
				}
			} catch ( Exception $e ) {
				exit ( sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $e ) );
			}
			if ($event == 'LOCATION') {
				
				$username = $postObj->FromUserName;
				// session_id($username);
				// session('openid_username',$username);
				
				$weidu = $postObj->Latitude;
				// $weidu == null ? $weidu = 1 : $weidu = ok;
				$jingdu = $postObj->Longitude;
				$condition ['weidu'] = strval ( $weidu );
				$condition ['jingdu'] = strval ( $jingdu );
				$condition ['username'] = strval ( $username );
				$condition ['createtime'] = date ( 'Y-m-d H:i:s' );
				$username = strval ( $username );
				
				// session_start();
				// $_SESSION["username"] = $username;
				// session('openid_username',$username);
				$data ['weidu'] = strval ( $weidu );
				$data ['jingdu'] = strval ( $jingdu );
				$data ['createtime'] = date ( 'Y-m-d H:i:s' );
				if (M ( 'location' )->where ( "username = '$username'" )->select () == null) {
					M ( 'location' )->data ( $condition )->add ();
				} else {
					M ( 'location' )->where ( "username = '$username'" )->save ( $data );
				}
			}
			if ($event == 'CLICK') {
				$time = time ();
				
				$textTpl = "<xml>
         <ToUserName><![CDATA[%s]]></ToUserName>
         <FromUserName><![CDATA[%s]]></FromUserName>
         <CreateTime>%s</CreateTime>
         <MsgType><![CDATA[%s]]></MsgType>
         <Content><![CDATA[%s]]></Content>
         <FuncFlag>0</FuncFlag>
         </xml>";
				$picTpl = "<xml>
              
                                 <ToUserName><![CDATA[%s]]></ToUserName>
              
                                 <FromUserName><![CDATA[%s]]></FromUserName>
              
                                 <CreateTime>%s</CreateTime>
              
                                 <MsgType><![CDATA[%s]]></MsgType>
              
              
                                 <ArticleCount>1</ArticleCount>
              
                                 <Articles>
              
                                 <item>
              
                                 <Title><![CDATA[%s]]></Title>
              
                                 <Description><![CDATA[%s]]></Description>
              
                                 <PicUrl><![CDATA[%s]]></PicUrl>
              
                                 <Url><![CDATA[%s]]></Url>
              
                                 </item>
              
                                 </Articles>
              
                                 <FuncFlag>1</FuncFlag>
              
                            </xml> ";
				
				if ($keyword == 'yzl_yzzw') {
					$msgType = "text";
					$textContent = "您可输入20位产品身份码进行查询!" . "$separator";
					$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
					
					echo $resutlStr;
					exit ();
				}
				if ($keyword == 'yzl_qyry') {
					$msgType = "news";
					$title = "品牌荣誉";
					$desription = "2012年度御芝林药业获得高新技术企业证书2012年御芝林药业荣获“十二五期间全国民族特许商品定点生产企业";
					$image = "http://pec.weproduct.cn/upload/yuzhilin/pin_pai_rong_yu/pin_pai_rong_yu.jpg";
					$turl = "http://mp.weixin.qq.com/s?__biz=MzA3NjczNTYwMg==&mid=400216135&idx=1&sn=14fa21d3a16856de13263e3ddb2ac245&scene=18#wechat_redirect";
					$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
					echo $resutlStr;
					exit ();
				}
				if ($keyword == 'yzl_btyfgs') {
					$msgType = "news";
					$title = '变通研发故事 ';
					$desription = '变通发明人：御医后代金建文发明人简介金建文（爱新觉罗•顺安），63岁，满族。系满清皇族宫廷医药世家后代，爱新';
					$image = 'http://pec.weproduct.cn/upload/yuzhilin/bian_tong_yan_fa_gu_shi/bian_tong_yan_fa_gu_shi.jpg';
					$turl = 'http://mp.weixin.qq.com/s?__biz=MzA3NjczNTYwMg==&mid=400216265&idx=1&sn=073ec229c9b7117bda06b9989a2b0370&scene=18#wechat_redirect';
					$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
					echo $resutlStr;
					exit ();
				}
				if ($keyword == 'yzl_fjpzs') {
					$msgType = "news";
					$title = '蜂胶配紫苏';
					$desription = '';
					$image = 'http://pec.weproduct.cn/upload/yuzhilin/feng_jiao_pei_zi_su/fjpzs.jpg';
					$turl = 'http://mp.weixin.qq.com/s?__biz=MzA3NjczNTYwMg==&mid=400216370&idx=1&sn=8f8d33542b46b0aa5e125a4bd00ae0a7&scene=18#wechat_redirect';
					$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
					echo $resutlStr;
					exit ();
				}
				if ($keyword == 'yzl_bgyjj') {
					$msgType = "news";
					$title = '补钙要讲究 ';
					$desription = '';
					$image = 'http://pec.weproduct.cn/upload/yuzhilin/bu_gai_yao_jiang_jiu/bgyjj.jpg';
					$turl = 'http://mp.weixin.qq.com/s?__biz=MzA3NjczNTYwMg==&mid=400216436&idx=1&sn=9c7e431639f3259caf5441a91e993ba2#wechat_redirect';
					$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
					echo $resutlStr;
					exit ();
				}
				if ($keyword == 'yzl_lxwm') {
					$msgType = "news";
					$title = '联系我们  ';
					$desription = '公司总部地址：石家庄高新技术产业开发区(东区)昆仑大街海河道168号企业邮箱：yuzhilin@yuzhl.';
					$image = 'http://pec.weproduct.cn/upload/yuzhilin/lian_xi_wo_men/lxwm.jpg';
					$turl = 'http://mp.weixin.qq.com/s?__biz=MzA3NjczNTYwMg==&mid=400216396&idx=1&sn=4c081bec3953b2b1dc1dca7d1ab5cae7#wechat_redirect';
					$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
					echo $resutlStr;
					exit ();
				}
				
				if ($keyword == 'lianxikefu') {
					$msgType = "text";
					$textContent = "您可输入'你好'后直接留言，客服将通过微信直接回复您" . "$separator";
					$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
					
					echo $resutlStr;
					exit ();
				}
				//洮砚协会

				if ($keyword == 'xhjj') {
					$msgType = "news";
					$title = '甘肃省洮砚协会简介  ';
					$desription = '协会简介　　甘肃省洮砚协会是经甘肃省省民政厅社会组织管理局批准成立的，由甘肃省内从事洮砚生产、研究、经营、销';
					$image = 'http://pec.weproduct.cn/upload/tyxh/0.jpg';
					$turl = 'http://mp.weixin.qq.com/s?__biz=MzI4MDE1MjE1NQ==&mid=407088928&idx=1&sn=13e3dd4a039a628815c10999c9fa2c9c#rd';
					$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
					echo $resutlStr;
					exit ();
				}if ($keyword == 'xhzc') {
					$msgType = "news";
					$title = '甘肃省洮砚协会章程  ';
					$desription = '协会章程第一章 总则　　第一条 本协会的名称为甘肃省洮砚协会，是甘肃省第一个省级洮砚协会，挂靠于甘肃省文化厅';
					$image = 'http://pec.weproduct.cn/upload/tyxh/0.jpg';
					$turl = 'http://mp.weixin.qq.com/s?__biz=MzI4MDE1MjE1NQ==&mid=407088992&idx=1&sn=ca7d6d10b460a4b9412d3425736f2386#rd';
					$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
					echo $resutlStr;
					exit ();
				}if ($keyword == 'hydt') {
					$msgType = "news";
					$title = '甘肃省洮砚协会第一届会员代表大会暨成立大会  ';
					$desription = '2015年10月18日，甘肃省洮砚协会于今日在兰州隆重成立了。我们相聚金城，为弘扬民族传统文化，传承保护文化';
					$image = 'http://pec.weproduct.cn/upload/tyxh/0.jpg';
					$turl = 'http://mp.weixin.qq.com/s?__biz=MzI4MDE1MjE1NQ==&mid=407089101&idx=1&sn=14d07c214ff50f676cf11c5892f5efe3#rd';
					$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
					echo $resutlStr;
					exit ();
				}if ($keyword == 'rhxz') {
					$msgType = "news";
					$title = '甘肃省洮砚协会入会须知 ';
					$desription = '入会须知　　经甘肃省民政厅社会组织管理局批准，筹备成立甘肃省洮砚协会，诚邀甘肃省内从事洮砚生产、研究、经营、';
					$image = 'http://pec.weproduct.cn/upload/tyxh/0.jpg';
					$turl = 'http://mp.weixin.qq.com/s?__biz=MzI4MDE1MjE1NQ==&mid=407089023&idx=1&sn=db139dd88edbf7bd19321dde4d70602e#rd';
					$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
					echo $resutlStr;
					exit ();
				}if ($keyword == 'gsty_lxwm') {
					$msgType = "news";
					$title = '联系我们';
					$desription = '兰州 ～ 蒙刘军：138-9324-3388临洮 ～ 张 斌：139-9328-1366岷县';
					$image = 'http://pec.weproduct.cn/upload/tyxh/0.jpg';
					$turl = 'http://mp.weixin.qq.com/s?__biz=MzI4MDE1MjE1NQ==&mid=407089071&idx=1&sn=4bd2b4348ff810f262d8c5c7e30fad7d#rd';
					$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
					echo $resutlStr;
					exit ();
				}
			}
			
			if ($type == 'text' && ! empty ( $keyword )) {
				// 记录用户信息
				if (is_numeric ( $keyword )) {
					$APPID = $api_result ['api_weixin_appid'];
					$APPSECRET = $api_result ['api_weixin_aes_key'];
					$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";
					$postres = json_decode ( file_get_contents ( $url ) );
					// dump($postres->access_token);die;
					$access_token = $postres->access_token;
					$openid = strval ( $postObj->FromUserName );
					// print_r($openid);exit;
					$murl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid";
					$mpostres = json_decode ( file_get_contents ( $murl ) );
					// print_r($mpostres);exit;
					
					/*
					 * $mcondition['uid'] = session('api_uid');
					 * $mcondition['openid'] = $openid;
					 * $mcondition['querynumber'] = $keyword;
					 * $rrsult = M('consumers')->where($mcondition)->find();
					 * if($rrsult == null){
					 */
					$mdatas ['uid'] = session ( 'api_uid' );
					$mdatas ['openid'] = $openid;
					$mdatas ['nickname'] = $mpostres->nickname;
					$mdatas ['sex'] = $mpostres->sex;
					$mdatas ['city'] = $mpostres->city;
					$mdatas ['province'] = $mpostres->province;
					$mdatas ['country'] = $mpostres->country;
					$mdatas ['querynumber'] = $keyword;
					$mdatas ['querycount'] = 1;
					$mdatas ['createtime'] = date ( 'Y-m-d H:i:s' );
					$id = M ( 'consumers' )->add ( $mdatas );
					session ( 'id', $id );
					if ($api_login == "themistech") {
						// $back = $this->pec_search_fpds($keyword);
						$back == null ? $datas ['numberstate'] = "error" : $datas ['numberstate'] = "right";
					} else {
						// $back = $this->pec_search($keyword);
						$back == null ? $datas ['numberstate'] = "error" : $datas ['numberstate'] = "right";
					}
					M ( 'consumers' )->where ( "id = '$id'" )->save ( $datas );
					/*
					 * }else{
					 * $mdatas['nickname'] = $mpostres->nickname;
					 * $mdatas['sex'] = $mpostres->sex;
					 * $mdatas['city'] = $mpostres->city;
					 * $mdatas['province'] = $mpostres->province;
					 * $mdatas['country'] = $mpostres->country;
					 * $mdatas['querycount'] = $rrsult['querycount'] + 1;
					 * M('consumers')->where($mcondition)->save($mdatas);
					 * }
					 */
				}
				// 如果是微防伪该处理的内容则进行处理
				if (! is_numeric ( $keyword )) {
					
					if (strstr ( $keyword, "您好" ) || strstr ( $keyword, "你好" ) || strstr ( $keyword, "在吗" ) || strstr ( $keyword, "有人吗" )) {
						$result = $this->transmitService ( $postObj );
						echo $result;
					} else {
						$time = time ();
						$msgType = "text";
						$textTpl = "<xml>
         <ToUserName><![CDATA[%s]]></ToUserName>
         <FromUserName><![CDATA[%s]]></FromUserName>
         <CreateTime>%s</CreateTime>
         <MsgType><![CDATA[%s]]></MsgType>
         <Content><![CDATA[%s]]></Content>
         <FuncFlag>0</FuncFlag>
         </xml>";
						$textContent = "很抱歉，您输入的类型不对！请重新输入数字类型" . "$separator";
						$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
						echo $resutlStr;
						exit ();
					}
				} else {
					
					// 案例特殊需求,在菲普蒂斯平台上查询，任意一个产品
					if ($api_login == "themistech") {
						// dump(strlen($keyword));die;
						if(strlen($keyword) == 18){
							//echo 'ok';
							$time = time ();
							$msgType = "text";
							$textTpl = "<xml>
         <ToUserName><![CDATA[%s]]></ToUserName>
         <FromUserName><![CDATA[%s]]></FromUserName>
         <CreateTime>%s</CreateTime>
         <MsgType><![CDATA[%s]]></MsgType>
         <Content><![CDATA[%s]]></Content>
         <FuncFlag>0</FuncFlag>
         </xml>";
							$return = $this->getAic($keyword);
							if(null == $return){
								$textContent = "查询失败";
								$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
								echo $resutlStr;
								exit ();
							}else{
						
								$textContent = "<a href='$return'>查询成功,点击查看详情</a>";
								$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
								echo $resutlStr;
								exit ();
							}
								
						}
						
						
						$returns = $this->pec_search_fpds ( $keyword );
						
						$time = time ();
						$msgType = "news";
						$textTpl = "<xml>
         <ToUserName><![CDATA[%s]]></ToUserName>
         <FromUserName><![CDATA[%s]]></FromUserName>
         <CreateTime>%s</CreateTime>
         <MsgType><![CDATA[text]]></MsgType>
         <Content><![CDATA[%s]]></Content>
         <FuncFlag>0</FuncFlag>
         </xml>";
						$picTpl = "<xml>
                        			
                                 <ToUserName><![CDATA[%s]]></ToUserName>
                        			
                                 <FromUserName><![CDATA[%s]]></FromUserName>
                        			
                                 <CreateTime>%s</CreateTime>
                        			
                                 <MsgType><![CDATA[%s]]></MsgType>
                        			
                        			
                                 <ArticleCount>1</ArticleCount>
                        			
                                 <Articles>
                        			
                                 <item>
                        			
                                 <Title><![CDATA[%s]]></Title>
                        			
                                 <Description><![CDATA[%s]]></Description>
                        			
                                 <PicUrl><![CDATA[%s]]></PicUrl>
                        			
                                 <Url><![CDATA[%s]]></Url>
                        			
                                 </item>
                        			
                                 </Articles>
                        			
                                 <FuncFlag>1</FuncFlag>
                        			
                            </xml> ";
						
						if (null == $returns) {
							$username = $_SESSION ["username"];
							if ($username == null) {
								$username = 'username_null';
							}
							$api_uid = session ( 'api_uid' ); // 两位，需调整
							$datas = $api_uid . $username;
							$textContent = "<a href='http://pec.weproduct.cn/api/photo_upload/$datas'>很抱歉，您所查询的商品不存在或为假品，点击取证</a>";
							$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $textContent );
							echo $resutlStr;
							exit ();
						} else {
							$product_datas = $returns ['product']; // 产品信息
							$pid = $returns ['pid'];
							$company_datas = $returns ['company_datas'];
							$pec_order_id = session ( 'pec_order_id' );
							// dump($pec_order_id);die;
							$pec_random_code = session ( 'pec_random_code' );
							// dump($pec_random_code);die;
							$api_id = session ( 'api_id' ); // 微信红包使用
							
							$pec_number = $api_id . $pec_order_id . $pec_random_code . $keyword;
							// exit($pec_number);die;
							
							foreach ( $product_datas as $value ) {
								$title = "产品名称:" . $value ['product_name'];
								$desription = $value ['txt_content'];
								$pic_url = $value ['pic_url'];
								// exit($pic_url);die;
								
								// $app_id = $api_result['api_weixin_appid'];
								// $app_secret = $api_result['api_weixin_aes_key'];
								// $redirect_uri = "http://pec.weproduct.cn/weixin/weixin_display_special/$app_id/$app_secret";
								// $turl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$app_id}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_userinfo&state=$pec_number#wechat_redirect";
								$turl = "http://pec.weproduct.cn/weixin/weixin_display_special/" . $pec_number;
							}
							// $desription = $desription."内有红包,请猛戳!";
							// foreach ($company_datas as $value1){
							// exit($value1['photo_url']);
							// if(null == $value1['photo_url']){
							
							if (null == $pic_url) {
								$image = "http://pec.weproduct.cn/upload/image/default/default.png";
							} else {
								$image = "http://pec.weproduct.cn/upload" . $pic_url;
								// dump($image);die;
							}
							// }else{
							// exit($value1['photo_url']);
							// $image = "http://pec.weproduct.cn/upload".$value1['photo_url'];
							// }
							// }
							$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
							echo $resutlStr;
							exit ();
						}
						// 查询成功，返回给用户红包
					} else {
						if ($api_login == "老坑洮砚") { // 案例特殊需求,专为老坑洮砚设计
							$returns = $this->pec_search_special ( $keyword );
							
							$time = time ();
							$msgType = "news";
							$textTpl = "<xml>
         <ToUserName><![CDATA[%s]]></ToUserName>
         <FromUserName><![CDATA[%s]]></FromUserName>
         <CreateTime>%s</CreateTime>
         <MsgType><![CDATA[text]]></MsgType>
         <Content><![CDATA[%s]]></Content>
         <FuncFlag>0</FuncFlag>
         </xml>";
							$picTpl = "<xml>
                        				
                                 <ToUserName><![CDATA[%s]]></ToUserName>
                        				
                                 <FromUserName><![CDATA[%s]]></FromUserName>
                        				
                                 <CreateTime>%s</CreateTime>
                        				
                                 <MsgType><![CDATA[%s]]></MsgType>
                        				
                        				
                                 <ArticleCount>1</ArticleCount>
                        				
                                 <Articles>
                        				
                                 <item>
                        				
                                 <Title><![CDATA[%s]]></Title>
                        				
                                 <Description><![CDATA[%s]]></Description>
                        				
                                 <PicUrl><![CDATA[%s]]></PicUrl>
                        				
                                 <Url><![CDATA[%s]]></Url>
                        				
                                 </item>
                        				
                                 </Articles>
                        				
                                 <FuncFlag>1</FuncFlag>
                        				
                            </xml> ";
							
							if (null == $returns) {
								$textContent = "很抱歉，您所查询的商品为假品！" . "$separator";
								$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $textContent );
								echo $resutlStr;
								exit ();
							} else {
								$product_datas = $returns ['product']; // 产品信息
								$pid = $returns ['pid'];
								$company_datas = $returns ['company_datas'];
								$pec_order_id = session ( 'pec_order_id' );
								$pec_random_code = session ( 'pec_random_code' );
								$pec_number = $pec_order_id . $pec_random_code;
								// exit($pec_number);die;
								foreach ( $product_datas as $value ) {
									$title = "产品名称:" . $value ['product_name'];
									$desription = $value ['txt_content'];
									$pic_url = $value ['pic_url'];
									$turl = "http://pec.weproduct.cn/weixin/weixin_display_special_laokengtaoyan/" . $pec_number;
								}
								foreach ( $company_datas as $value1 ) {
									// exit($value1['photo_url']);die;
									if (null == $value1 ['photo_url']) {
										
										if (null == $pic_url) {
											$image = "http://pec.weproduct.cn/upload/image/default/default.png";
										} else {
											$image = "http://pec.weproduct.cn/upload" . $pic_url;
										}
									} else {
										$image = "http://pec.weproduct.cn/upload" . $value1 ['photo_url'];
									}
								}
								$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
								echo $resutlStr;
								exit ();
							}
						}  // 案例特殊需求
else {
	if(strlen($keyword) == 18){
		//echo 'ok';
		$time = time ();
		$msgType = "text";
		$textTpl = "<xml>
         <ToUserName><![CDATA[%s]]></ToUserName>
         <FromUserName><![CDATA[%s]]></FromUserName>
         <CreateTime>%s</CreateTime>
         <MsgType><![CDATA[%s]]></MsgType>
         <Content><![CDATA[%s]]></Content>
         <FuncFlag>0</FuncFlag>
         </xml>";
		$return = $this->getAic($keyword);
		if(null == $return){
			$textContent = "查询失败";
			$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
			echo $resutlStr;
			exit ();
		}else{
				
			$textContent = "<a href='$return'>查询成功,点击查看详情</a>";
			$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $msgType, $textContent );
			echo $resutlStr;
			exit ();
		}
			
	}
							$returns = $this->pec_search ( $keyword );
							
							$time = time ();
							$msgType = "news";
							$textTpl = "<xml>
         <ToUserName><![CDATA[%s]]></ToUserName>
         <FromUserName><![CDATA[%s]]></FromUserName>
         <CreateTime>%s</CreateTime>
         <MsgType><![CDATA[text]]></MsgType>
         <Content><![CDATA[%s]]></Content>
         <FuncFlag>0</FuncFlag>
         </xml>";
							$picTpl = "<xml>

                                 <ToUserName><![CDATA[%s]]></ToUserName>

                                 <FromUserName><![CDATA[%s]]></FromUserName>

                                 <CreateTime>%s</CreateTime>

                                 <MsgType><![CDATA[%s]]></MsgType>
								

                                 <ArticleCount>1</ArticleCount>

                                 <Articles>

                                 <item>

                                 <Title><![CDATA[%s]]></Title>

                                 <Description><![CDATA[%s]]></Description>

                                 <PicUrl><![CDATA[%s]]></PicUrl>

                                 <Url><![CDATA[%s]]></Url>

                                 </item>

                                 </Articles>

                                 <FuncFlag>1</FuncFlag>

                            </xml> ";
							
							if (null == $returns) {
								// 一键取证
								$username = $_SESSION ["username"];
								if ($username == null) {
									$username = 'username_null';
								}
								$api_uid = session ( 'api_uid' ); // 两位，需调整
								$datas = $api_uid . $username;
								$textContent = "很抱歉，您所查询的商品不存在或为假品";
								// $textContent = "<a href='http://pec.weproduct.cn/api/photo_upload/$datas'>很抱歉，您所查询的商品不存在或为假品，点击取证</a>";
								$resutlStr = sprintf ( $textTpl, $fromUsername, $toUsername, $time, $textContent );
								
								echo $resutlStr;
								exit ();
							} else {
								$product_datas = $returns ['product']; // 产品信息
								$pid = $returns ['pid'];
								$company_datas = $returns ['company_datas'];
								$pec_order_id = session ( 'pec_order_id' );
								// dump($pec_order_id);die;
								$pec_random_code = session ( 'pec_random_code' );
								// dump($pec_random_code);die;
								$api_id = session ( 'api_id' ); // 微信红包使用
								
								$pec_number = $api_id . $pec_order_id . $pec_random_code . $keyword;
								// exit($pec_number);die;
								foreach ( $product_datas as $value ) {
									$title = "产品名称:" . $value ['product_name'];
									$desription = $value ['txt_content'];
									$pic_url = $value ['pic_url'];
									$turl = "http://pec.weproduct.cn/weixin/weixin_display/" . $pec_number;
								}
								// $desription = $desription."内有红包,请猛戳!";
								/*
								 * foreach ($company_datas as $value1){ //先公司缩略图 ， 产品缩略图，默认图
								 * //exit($value1['photo_url']);die;
								 * if(null == $value1['photo_url']){
								 *
								 * if(null == $pic_url){
								 * $image = "http://pec.weproduct.cn/upload/image/default/default.png";
								 * }else{
								 * $image = "http://pec.weproduct.cn/upload".$pic_url;
								 * }
								 * }else{
								 * $image = "http://pec.weproduct.cn/upload".$value1['photo_url'];
								 * }
								 * }
								 */
								if (null == $pic_url) {
									$image = "http://pec.weproduct.cn/upload/image/default/default.png";
								} else {
									$image = "http://pec.weproduct.cn/upload" . $pic_url;
								}
								$resutlStr = sprintf ( $picTpl, $fromUsername, $toUsername, $time, $msgType, $title, $desription, $image, $turl );
								echo $resutlStr;
								exit ();
							}
						}
					}
				}
			} 

			else {
				$api_other_url = $api_result ['api_other_url'];
				if ($api_other_url == null) {
					exit ( "no other url" );
				}
				// 原文请求转接到other_url去
				try {
					$contentStr = $this->request_by_other ( $api_other_url, $postStr );
				} catch ( Exception $e ) {
					exit ( $e );
				}
				
				exit ( $contentStr );
			}
		} else {
			exit ( "non post data" );
		}
	}
	public function request_by_other($remote_server, $postStr) {
		$context = array (
				'http' => array (
						'method' => 'POST',
						'header' => 'Content-type: application/x-www-form-urlencoded' . 'rn' . 'User-Agent : MicroMessenger' . 'rn' . 'Content-length:' . strlen ( $postStr ),
						'content' => $postStr 
				) 
		);
		$stream_context = stream_context_create ( $context );
		$data = file_get_contents ( $remote_server, false, $stream_context );
		return $data;
	}
	
	/**
	 * 产品身份码查询
	 *
	 * @param unknown_type $pec_id        	
	 * @return string
	 */
	public function pec_search($pec_id = 0) {
		$separator = "\r\n";
		$str = "";
		$dividing = "______________________" . $separator;
		if (empty ( $pec_id )) {
			$anti_result = "输入电码！" . "$separator";
			return $anti_result;
		}
		
		if (substr ( $pec_id, 0, 4 ) == "1022") {
			$profix_foure = substr ( $pec_id, 0, 4 );
			$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
			$pec_random_code = substr ( $pec_id, 4, 8 );
		}
		
		if (strlen ( $pec_id ) == 8) {
			$profix_foure = substr ( $pec_id, 0, 4 );
			$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
			$pec_random_code = substr ( $pec_id, 4, 4 );
		}
		if (strlen ( $pec_id ) == 9) {
			$profix_foure = substr ( $pec_id, 0, 4 );
			$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
			$pec_random_code = substr ( $pec_id, 4, 5 );
		}
		if (strlen ( $pec_id ) == 10) {
			$profix_foure = substr ( $pec_id, 0, 4 );
			$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
			$pec_random_code = substr ( $pec_id, 4, 6 );
		}
		if (strlen ( $pec_id ) == 11) {
			$profix_foure = substr ( $pec_id, 0, 4 );
			$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
			$pec_random_code = substr ( $pec_id, 4, 7 );
		}
		if (strlen ( $pec_id ) == 12) {
			if (substr ( $pec_id, 0, 4 ) == "1022") {
				$profix_foure = substr ( $pec_id, 0, 4 );
				$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
				$pec_random_code = substr ( $pec_id, 4, 8 );
			} else {
				$profix_foure = substr ( $pec_id, 1, 1 ) . substr ( $pec_id, 3, 1 ) . substr ( $pec_id, 5, 1 ) . substr ( $pec_id, 7, 1 );
				
				$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
				
				$pec_random_code = substr ( $pec_id, 0, 1 ) . substr ( $pec_id, 2, 1 ) . substr ( $pec_id, 4, 1 ) . substr ( $pec_id, 6, 1 ) . substr ( $pec_id, 8 );
			}
		}
		if (strlen ( $pec_id ) == 20) { // 外来导入
			
			$pec_random_code = $pec_id;
		}
		// exit($pec_random_code);die;
		// return $profix_six;die;
		
		$api_list = M ( 'api_list' );
		$api_uid = session ( 'api_uid' );
		
		$api_condition ['api_uid'] = $api_uid;
		$api_login = $api_list->where ( $api_condition )->getField ( 'api_login' );
		$index = M ( 'company_user' );
		$condition ['login'] = $api_login;
		$return = $index->where ( $condition )->getField ( 'pec_name' );
		// return $return;die;
		
		if ($return != null) {
			if (strlen ( $pec_id ) == 20) { // 外来导入
				$pec_name = M ( $return );
				// dump($pec_name);die;
				$condition ['pec_dataPrefix_code'] = 0;
				$datas = $pec_name->field ( 'pec_random_code,pec_order_id' )->where ( $condition )->select ();
			} else {
				// dump($return);die;
				$pec_name = M ( $return );
				// dump($pec_name);die;
				$condition ['pec_dataPrefix_code'] = $profix_six;
				$datas = $pec_name->field ( 'pec_random_code,pec_order_id' )->where ( $condition )->select ();
			}
			
			foreach ( $datas as $val ) {
				$data = json_decode ( $val ["pec_random_code"], true );
				if (null == $data) {
					$this->error ( "无法获取数据，请重新输入!" );
				}
				// var_dump($data);die;
				foreach ( $data as $val1 ) {
					// return $val1;die;
					if ($pec_random_code == $val1) {
						// $pec_id_record = M('id_record');
						
						// $anti_result="恭喜，您所查询的商品为正品！"."$separator";
						$pec_order_id = $val ["pec_order_id"];
						
						session ( 'pec_order_id', $pec_order_id );
						session ( 'pec_random_code', $pec_random_code );
						// exit($pec_id);die;
						$returns = get_product_message_byPecOrder ( $pec_order_id, $pec_id );
						// exit($pec_order_id);die;
						if ($return == false) {
							$returns = "数据查询不正确,请与管理员联系" . "$separator";
						}
						return $returns;
					}
				}
			}
		}
	}
	/**
	 * 产品身份码查询
	 *
	 * @param unknown_type $pec_id        	
	 * @return string
	 */
	public function pec_search_fpds($pec_id = 0) {
		$separator = "\r\n";
		$str = "";
		$dividing = "______________________" . $separator;
		if (empty ( $pec_id )) {
			$anti_result = "输入电码！" . "$separator";
			return $anti_result;
		}
		
		// 案例演示
		if (substr ( $pec_id, 0, 6 ) == '150317') {
			if (strlen ( $pec_id ) == 8) {
				$profix_six = substr ( $pec_id, 0, 6 );
				$pec_random_code = substr ( $pec_id, 6, 2 );
			} else {
				$profix_six = substr ( $pec_id, 0, 6 );
				$pec_random_code = substr ( $pec_id, 6, 6 );
			}
		} else {
			// 正常运营时，启用次算法
			if (strlen ( $pec_id ) == 8) {
				$profix_foure = substr ( $pec_id, 0, 4 );
				$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
				$pec_random_code = substr ( $pec_id, 4, 4 );
			}
			if (strlen ( $pec_id ) == 9) {
				$profix_foure = substr ( $pec_id, 0, 4 );
				$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
				$pec_random_code = substr ( $pec_id, 4, 5 );
			}
			if (strlen ( $pec_id ) == 10) {
				$profix_foure = substr ( $pec_id, 0, 4 );
				$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
				$pec_random_code = substr ( $pec_id, 4, 6 );
			}
			if (strlen ( $pec_id ) == 11) {
				$profix_foure = substr ( $pec_id, 0, 4 );
				$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
				$pec_random_code = substr ( $pec_id, 4, 7 );
			}
			if (strlen ( $pec_id ) == 12) {
				$profix_foure = substr ( $pec_id, 1, 1 ) . substr ( $pec_id, 3, 1 ) . substr ( $pec_id, 5, 1 ) . substr ( $pec_id, 7, 1 );
				
				$profix_six = pec_dataPrefix_code_to_six ( $profix_foure );
				
				$pec_random_code = substr ( $pec_id, 0, 1 ) . substr ( $pec_id, 2, 1 ) . substr ( $pec_id, 4, 1 ) . substr ( $pec_id, 6, 1 ) . substr ( $pec_id, 8 );
			}
		}
		
		// exit($pec_random_code);die;
		// return $profix_six;die;
		
		$api_list = M ( 'api_list' );
		$api_uid = session ( 'api_uid' );
		
		$api_condition ['api_uid'] = $api_uid;
		$api_login = $api_list->where ( $api_condition )->getField ( 'api_login' );
		$index = M ( 'company_user' );
		$condition ['login'] = $api_login;
		$return = $index->where ( $condition )->getField ( 'pec_name' );
		// dump($return);die;
		
		if ($return != null) {
			
			// dump($return);die;
			$pec_name = M ( $return );
			// dump($pec_name);die;
			$condition ['pec_dataPrefix_code'] = $profix_six;
			// dump($profix_six);die;
			$datas = $pec_name->field ( 'pec_random_code,pec_order_id' )->where ( $condition )->select ();
			
			// dump($datas);die;
			foreach ( $datas as $val ) {
				$data = json_decode ( $val ["pec_random_code"], true );
				if (null == $data) {
					$this->error ( "无法获取数据，请重新输入!" );
				}
				// var_dump($data);die;
				foreach ( $data as $val1 ) {
					// return $val1;die;
					if ($pec_random_code == $val1) {
						// $pec_id_record = M('id_record');
						
						// $anti_result="恭喜，您所查询的商品为正品！"."$separator";
						$pec_order_id = $val ["pec_order_id"];
						
						session ( 'pec_order_id', $pec_order_id );
						session ( 'pec_random_code', $pec_random_code );
						// exit($pec_id);die;
						$returns = get_product_message_byPecOrder ( $pec_order_id, $pec_id );
						// exit($pec_order_id);die;
						if ($return == false) {
							$returns = "数据查询不正确,请与管理员联系" . "$separator";
						}
						return $returns;
					}
				}
			}
		}
	}
	/**
	 * 产品身份码查询,案例特殊需求
	 *
	 * @param unknown_type $pec_id        	
	 * @return string
	 */
	public function pec_search_special($pec_id = 0) {
		$separator = "\r\n";
		$str = "";
		$dividing = "______________________" . $separator;
		if (empty ( $pec_id )) {
			$anti_result = "输入电码！" . "$separator";
			return $anti_result;
		}
		
		if (strlen ( $pec_id ) == 8) {
			$profix_six = substr ( $pec_id, 0, 6 );
			$pec_random_code = substr ( $pec_id, 6, 2 );
		} else {
			$profix_six = substr ( $pec_id, 0, 6 );
			$pec_random_code = substr ( $pec_id, 6, 6 );
		}
		// exit($pec_random_code);die;
		// return $profix_six;die;
		
		$api_list = M ( 'api_list' );
		$api_uid = session ( 'api_uid' );
		
		$api_condition ['api_uid'] = $api_uid;
		$api_login = $api_list->where ( $api_condition )->getField ( 'api_login' );
		$index = M ( 'company_user' );
		$condition ['login'] = $api_login;
		$return = $index->where ( $condition )->getField ( 'pec_name' );
		// return $return;die;
		
		if ($return != null) {
			
			// dump($return);die;
			$pec_name = M ( $return );
			// dump($pec_name);die;
			$condition ['pec_dataPrefix_code'] = $profix_six;
			$datas = $pec_name->field ( 'pec_random_code,pec_order_id' )->where ( $condition )->select ();
			
			foreach ( $datas as $val ) {
				$data = json_decode ( $val ["pec_random_code"], true );
				if (null == $data) {
					$this->error ( "无法获取数据，请重新输入!" );
				}
				// var_dump($data);die;
				foreach ( $data as $val1 ) {
					// return $val1;die;
					if ($pec_random_code == $val1) {
						// $pec_id_record = M('id_record');
						
						// $anti_result="恭喜，您所查询的商品为正品！"."$separator";
						$pec_order_id = $val ["pec_order_id"];
						
						session ( 'pec_order_id', $pec_order_id );
						session ( 'pec_random_code', $pec_random_code );
						
						$returns = get_product_message_byPecOrder_special ( $pec_order_id, $pec_random_code );
						// exit($pec_order_id);die;
						if ($return == false) {
							$returns = "数据查询不正确,请与管理员联系" . "$separator";
						}
						return $returns;
					}
				}
			}
		}
	}
	/*
	 * 回复多客服消息
	 */
	private function transmitService($object) {
		$xmlTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
		$result = sprintf ( $xmlTpl, $object->FromUserName, $object->ToUserName, time () );
		return $result;
	}
	public function photo_upload($datas = null) {
		$uid = substr ( $datas, 0, 2 );
		$username = substr ( $datas, 2 );
		
		$username == null ? $this->username = 'ok' : $this->username = $username;
		$uid == null ? $this->uid = 'uid_null' : $this->uid = $uid;
		$this->display ();
	}
	/*
	 * 定位
	 */
	public function getLocation($uid = null, $number = null) {
		$username = I ( 'username' );
		$username = strval ( $username );
		
		$result = M ( 'location' )->where ( "username='$username'" )->find ();
		$x = $result ['weidu'];
		$y = $result ['jingdu'];
		$url = "http://api.map.baidu.com/geocoder/v2/?ak=10M9FOeIKPwNGbgN7TxReN0Y&location=$x,$y&output=json";
		// $url = "http://api.map.baidu.com/geocoder/v2/?ak=10M9FOeIKPwNGbgN7TxReN0Y&callback=renderReverse&location=30.578945,104.059364&output=json&pois=1";
		// echo $url;
		// $result = $this->http($url);
		$result = file_get_contents ( $url );
		
		$data = json_decode ( $result );
		$datas ['location'] = $data->result->formatted_address;
		$condition ['uid'] = $uid;
		$condition ['username'] = $username;
		$condition ['number'] = $number;
		$return = M ( 'evidence' )->where ( $condition )->find ();
		if ($return == null) {
			$datas ['uid'] = $uid;
			$datas ['username'] = $username;
			$datas ['location'] = $data->result->formatted_address;
			$datas ['createtime'] = date ( 'Y-m-d H:i' );
			$datas ['number'] = $number;
			$datas ['type'] = 0;
			M ( 'evidence' )->data ( $datas )->add ();
		} else {
			$condition1 ['uid'] = $uid;
			$condition1 ['username'] = $username;
			$condition1 ['createtime'] = $return ['createtime'];
			$condition1 ['number'] = $number;
			$datas1 ['location'] = $data->result->formatted_address;
			M ( 'evidence' )->where ( $condition1 )->save ( $datas1 );
		}
		
		echo $data->result->formatted_address;
		// print_r(json_decode($result,true));exit;
		
		// echo $data;
	}
	public function http($url) {
		$ch = curl_init ( $url );
		curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 10 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_HEADER, false );
		$output = curl_exec ( $ch ); // 输出内容
		curl_close ( $ch );
		return $output;
	}
	
	/*
	 * 取证上传
	 */
	public function uploadFile($username = null, $uid = null, $number = null) {
		if ($number == "WU_FILE_0")
			$number = "undefined";
		
		if ($_FILES ["file"] ["error"] > 0) {
			echo "Error: " . $_FILES ["file"] ["error"] . "<br />";
		} else {
			/*
			 * echo "Upload: " . $_FILES["file"]["name"] . "<br />";
			 * echo "Type: " . $_FILES["file"]["type"] . "<br />";
			 * echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
			 * echo "Stored in: " . $_FILES["file"]["tmp_name"];
			 */
			if (file_exists ( "upload/" . $_FILES ["file"] ["name"] )) {
				echo $_FILES ["file"] ["name"] . " already exists. ";
			} else {
				move_uploaded_file ( $_FILES ["file"] ["tmp_name"], "upload/evindence/" . $_FILES ["file"] ["name"] );
				
				$imageurl = "upload/evindence/" . $_FILES ["file"] ["name"];
				
				$condition ['uid'] = $uid;
				$condition ['username'] = $username;
				$condition ['createtime'] = date ( 'Y-m-d H:i' );
				$condition ['number'] = $number;
				$return = M ( 'evidence' )->where ( $condition )->find ();
				if ($return == null) {
					$data ['uid'] = $uid;
					$data ['username'] = $username;
					$data ['createtime'] = date ( 'Y-m-d H:i' );
					$data ['imageurl'] = $imageurl;
					$data ['number'] = $number;
					$data ['type'] = 0;
					M ( 'evidence' )->add ( $data );
				} else {
					$data ['imageurl'] = $imageurl;
					$return = M ( 'evidence' )->where ( $condition )->save ( $data );
				}
			}
			/*
			 * $condition['location'] = $_FILES["file"]["name"];
			 * M('location')->where("username = '$username'")->data($condition)->save();
			 */
		}
	}
	public function getMessage($textMessage = null, $uid = null, $number = null) {
		// $textMessage = I('post.textMessage');
		$username = I ( 'post.username' );
		if ($textMessage == null)
			echo 'textMessage_null';
		if ($username == null)
			echo 'username_null';
		if ($uid == null)
			echo 'uid_null';
		
		$condition ['uid'] = $uid;
		$condition ['username'] = $username;
		$condition ['createtime'] = date ( 'Y-m-d H:i' );
		$condition ['number'] = $number;
		$return = M ( 'evidence' )->where ( $condition )->find ();
		if ($return == null) {
			$data ['uid'] = $uid;
			$data ['username'] = $username;
			$data ['createtime'] = date ( 'Y-m-d H:i' );
			$data ['message'] = $textMessage;
			$data ['number'] = $number;
			$data ['type'] = 0;
			M ( 'evidence' )->add ( $data );
		} else {
			$data ['message'] = $textMessage;
			$return = M ( 'evidence' )->where ( $condition )->save ( $data );
		}
		echo '提交成功!';
	}
	/**
	 * 艺术品查询接口
	 */
	public function getAic($keyword = null) {
		$username = session('api_login');
		//echo $username;
		$time = time ();
		$url = "http://themistech.cn/support/valid_puid_and_get_infos.action?puid=$keyword&timestamp=$time&userid=168168&weCid=$username";
		// dump($url);
		$mpostres = json_decode ( file_get_contents ( $url ) );
		
		$web_or_wechat_url = $mpostres->web_or_wechat_url;
		// echo $web_or_wechat_url;die;
		return $web_or_wechat_url;
	}
}
?>