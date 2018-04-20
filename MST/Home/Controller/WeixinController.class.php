<?php
namespace Home\Controller;
use Think\Controller;
use Com\WeinxinApp;
use Think\Crypt\Driver\Think;
define("TOKEN", "pecthemistech");
define("DEBUG", FALSE);

class WeixinController extends Controller{

   public function index() 
   {

   		if(isset($_GET["echostr"]))
		{
			if($this->checkSignature())
			{
				exit($_GET['echostr']);
			}
		}
		$this->responseMsg();
	}

 		public function responseMsg()
        {
                //get post data, May be due to the different environments
                $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

                //extract post data
                if (!empty($postStr)){
                        /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                         the best way is to check the validity of xml by yourself */
                        libxml_disable_entity_loader(true);
                        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                        $fromUsername = $postObj->FromUserName;
                        $toUsername = $postObj->ToUserName;
                        $event = $postObj->Event;
                        $keyword = $event=='CLICK'?$postObj->EventKey : trim($postObj->Content);
                        $time = time();
                        $textTpl = "<xml>
                                                        <ToUserName><![CDATA[%s]]></ToUserName>
                                                        <FromUserName><![CDATA[%s]]></FromUserName>
                                                        <CreateTime>%s</CreateTime>
                                                        <MsgType><![CDATA[%s]]></MsgType>
                                                        <Content><![CDATA[%s]]></Content>
                                                        <FuncFlag>0</FuncFlag>
                                                </xml>";
                        if(!empty( $keyword ))
                        {
                                $msgType = "text";
                                $contentStr = $this->pec_search($keyword);
                                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                                echo $resultStr;
                                exit;
                        }else{
                                echo "Input something...";
                                exit;
                        }

                }else {
                        echo "non post";
                        exit;
                }
        }
  		 private function checkSignature()
       	 {
                $signature = $_GET["signature"];
                $timestamp = $_GET["timestamp"];
                $nonce = $_GET["nonce"];

                $token = TOKEN;
                $tmpArr = array($token, $timestamp, $nonce);
                // use SORT_STRING rule
                sort($tmpArr, SORT_STRING);
                $tmpStr = implode( $tmpArr );
                $tmpStr = sha1( $tmpStr );

                if( $tmpStr == $signature ){
                        return true;
                }else{
                        return false;
                }
         }
         
          private function write_log($keypoint,$data)
          {
          	$gen_prefix = $_SERVER['DOCUMENT_ROOT']."/MST/Home/Download/Debug/";
            $postfix = ".txt";
            $gen_path = $gen_prefix."$keypoint".$postfix;
             if(!file_exists($gen_path))
            {
              if(!$fso=fopen($gen_path,'w')){
	                
	                echo '无法打开文件'.$gen_path;//trigger_error
	                return false;
	             }
           	  if(!flock($fso,LOCK_EX)){//LOCK_NB,排它型锁定
	                echo '无法锁定文件.';//trigger_error
	                return false;
	             }

	               if(!fwrite($fso,$data))
                 {
                     echo '无法写入文件.';//trigger_error
                     return false;
                 };
                 flock($fso,LOCK_UN);//释放锁定
            	 fclose($fso);
            }
            }
    public function weixin_display($pec_number){
     $separator = "<br>";
	//dump($pec_number);
		$api_id = substr($pec_number,0,5);
	//dump($api_id);
		$pec_number1 = substr($pec_number, 5);
	//dump($pec_number);die;
		/*if(strlen($pec_number) == 28){ //修改位数规则
			$pec_order_id = substr($pec_number,0,3);
			dump($pec_order_id);
			$pec_random_code = substr($pec_number,3,8);
			dump($pec_random_code);
			$key = substr($pec_number,16);
		}*/
	 if(strlen($pec_number1) == 13 ){
		$pec_order_id = substr($pec_number1,0,3);
		$pec_random_code = substr($pec_number1,3,2);
		$key = substr($pec_number1,5,8);
		//exit($key);die;
		}else{
	/*$pec_order_id = substr($pec_number,0,3);
	$pec_random_code = substr($pec_number,3,6);
	$key = substr($pec_number,9,12);*/
		if(strlen($pec_number1) == 15){
			$pec_order_id = substr($pec_number1,0,3);
			$pec_random_code = substr($pec_number1,3,4);
			//exit($pec_number);die;
			$key = substr($pec_number1,7,8);
		}
		if(strlen($pec_number1) == 17){
			$pec_order_id = substr($pec_number1,0,3);
			$pec_random_code = substr($pec_number1,3,5);
			//exit($pec_number);die;
			$key = substr($pec_number1,8,9);
		}
		if(strlen($pec_number1) == 19){
			$pec_order_id = substr($pec_number1,0,3);
			$pec_random_code = substr($pec_number1,3,6);
			//exit($pec_number);die;
			$key = substr($pec_number1,9,10);
		}
		if(strlen($pec_number1) == 21){
			$pec_order_id = substr($pec_number1,0,3);
			$pec_random_code = substr($pec_number1,3,7);
			//exit($pec_number);die;
			$key = substr($pec_number1,10,11);
		}
		if(strlen($pec_number1) == 23){ //规则改变
			$pec_order_id = substr($pec_number1,0,3);
			//dump($pec_order_id);
			$pec_random_code = substr($pec_number1,3,8);
			//exit($pec_number);die;
			$key = substr($pec_number1,11,12);
		}
		if(strlen($pec_number1) == 43){
			$pec_order_id = substr($pec_number1,0,3);
			//dump($pec_order_id);
			$pec_random_code = substr($pec_number1,3,20);
			//exit($pec_number);die;
			$key = $pec_random_code;
		}
	}
	//dump($key);die;
	$returns = get_product_message_byPecOrder_topage_fpds($pec_order_id,$key);//此方法为公用方法，即菲普蒂斯与企业均用
	$product_datas = $returns['product'];//产品信息
	 //dump($product_datas);die;
	foreach ($product_datas as $value){
		$this->html_content = $value['html_content'];
		$this->txt_content  = $value['txt_content'];
		if($value['company_html_content'] != null){
		$this->company_html = "<h4>企业信息</h4>".$value['company_html_content'];
		}
		$product_name = $value['product_name'];
		
	}
	//dump($returns['batch_message']);die;
	if($returns['batch_message'] != null && $returns['batch_message'] != ' ')
	$batch_message = "<h4>批次信息</h4>".$returns['batch_message'];//批次信息
	
	$company_datas = $returns['company_datas'];//公司信息
	
	if($company_datas != null && $company_datas != ' '){
	//dump($company_datas);die;
	foreach ($company_datas as $value1){
	 if(null == $value1['specific_content']){
	 $this->company_html = " ";
	 }else{
	 $this->company_html = "<h4>企业信息</h4>".$value1['specific_content'];
	 }
	 }
	}
	 
	//dump($batch_message);die;
	$this->batch_content = $batch_message;
	$type = "hongbao";
	$return = wei_hg($api_id,$key,$pec_order_id,$type);
	
	//$return = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx520c15f417810387&redirect_uri=http%3A%2F%2Fchong.qq.com%2Fphp%2Findex.php%3Fd%3D%26c%3DwxAdapter%26m%3DmobileDeal%26showwxpaytitle%3D1%26vb2ctag%3D4_2030_5_1194_60&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
	//dump($return);die;
	if($return == false ){
		
		$textContent = '出错了,请联系管理人员';
		$this->hongbao_content = $textContent;
	}else{
		
		//$return = $return."&api_id={$api_id}";
		//dump($key);
		$pec_dataPrefix_code = substr($key,1,1).substr($key,3,1).substr($key,5,1).substr($key,7,1);
		$pec_dataPrefix_code = pec_dataPrefix_code_to_six($pec_dataPrefix_code);
		//dump($pec_dataPrefix_code);
		$pec_random_code = substr($key,0,1).substr($key, 2,1).substr($key, 4,1).substr($key, 6,1).substr($key, 8);
		//dump($pec_random_code);
		$uid = M('api_list')->where("api_id = '$api_id'")->getField('api_uid');
		//dump($uid);
		$table = 'pec'.$uid;
		//dump($table);
		//$pec_order_id = M($table)->where("pec_dataPrefix_code = '$pec_dataPrefix_code'")->getField("pec_order_id");
		//dump($pec_order_id);die;
		$type = M('pec_order')->where("cid = '$uid' and pec_order_id = '$pec_order_id'")->getField('type');
		//dump($type);
		if($type == '1'){  //此产品身份码对应的订单开启红包
			$textContent = $return;
			
			$this->hongbao_content = "<img class='hb_bg' src='http://pec.weproduct.cn/assets/images/hongbao_background.jpg'><a class='hb-button' href={$textContent}></a>
<img class='hb-look' src='http://pec.weproduct.cn/assets/images/hongbao_logo.png'>";
		}
		
		$type = "quzheng";
		$return_openid = wei_hg($api_id,$key,$pec_order_id,$type); //获取openid,取证上传
		$this->uid = $api_id;
		$this->key = $key;
		$this->return_openid = $return_openid;
	}
	//dump($str);die;
	
	$this->display();
	
}
     /*
      * 案例特殊需求，微信特殊展示页,可产旬产品对应的企业信息，菲普蒂斯专用通道
      */       	
public function weixin_display_special($pec_number = null){
/*	$state = $_GET['state'];

	dump($state);
	dump($appid);
	dump($appsecret);die;*/
	$separator = "<br>";
	//dump($pec_number);
	$api_id = substr($pec_number,0,5);
	//dump($api_id);
	$pec_number1 = substr($pec_number, 5,13);
	//dump($pec_number1);die;
	if(strlen($pec_number) == 28){
		$pec_order_id = substr($pec_number,0,3);
		$pec_random_code = substr($pec_number,3,8);
		//dump($pec_number);
		$key = substr($pec_number,16);
	}
	else if(strlen($pec_number1) == 13 ){
		$pec_order_id = substr($pec_number1,0,3);
		$pec_random_code = substr($pec_number1,3,2);
		$key = substr($pec_number1,5,8);
		//exit($key);die;
	}else{
	/*$pec_order_id = substr($pec_number,0,3);
	$pec_random_code = substr($pec_number,3,6);
	$key = substr($pec_number,9,12);*/
		if(strlen($pec_number1) == 15){
			$pec_order_id = substr($pec_number1,0,3);
			$pec_random_code = substr($pec_number1,3,4);
			//exit($pec_number);die;
			$key = substr($pec_number1,7,8);
		}
		if(strlen($pec_number1) == 17){
			$pec_order_id = substr($pec_number1,0,3);
			$pec_random_code = substr($pec_number1,3,5);
			//exit($pec_number);die;
			$key = substr($pec_number1,8,9);
		}
		if(strlen($pec_number1) == 19){
			$pec_order_id = substr($pec_number1,0,3);
			$pec_random_code = substr($pec_number1,3,6);
			//exit($pec_number);die;
			$key = substr($pec_number1,9,10);
		}
		if(strlen($pec_number1) == 21){
			$pec_order_id = substr($pec_number1,0,3);
			$pec_random_code = substr($pec_number1,3,7);
			//exit($pec_number);die;
			$key = substr($pec_number1,10,11);
		}
		if(strlen($pec_number1) == 23){
			$pec_order_id = substr($pec_number1,0,3);
			$pec_random_code = substr($pec_number1,3,8);
			//dump($pec_number);
			$key = substr($pec_number1,11,12);
		}
		
	}
	//dump($key);
	$returns = get_product_message_byPecOrder_topage_fpds($pec_order_id,$key);//此方法为公用方法，即菲普蒂斯与企业均用
	$product_datas = $returns['product'];//产品信息
	 //dump($product_datas);die;
	foreach ($product_datas as $value){
		$this->html_content = $value['html_content'];
		$this->txt_content  = $value['txt_content'];
		$this->company_html = "<h4>企业信息</h4>".$value['company_html_content'];
		$product_name = $value['product_name'];
		
	}
	//$company_datas = $returns['company_datas'];//公司信息
	 


	/*foreach ($company_datas as $value1){
		if(null == $value1['specific_content']){
			$this->company_html = " ";
		}else{
			$this->company_html = "<h4>企业信息</h4>".$separator.$value1['specific_content'];
		}
	}*/
	 
	$batch_message = $returns['batch_message'];//批次信息
	 
	//dump($batch_message);die;
	$this->batch_content = $batch_message;
	$type = "hongbao";
	$return = wei_hg($api_id,$key,$pec_order_id,$type);
	//$return = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx520c15f417810387&redirect_uri=http%3A%2F%2Fchong.qq.com%2Fphp%2Findex.php%3Fd%3D%26c%3DwxAdapter%26m%3DmobileDeal%26showwxpaytitle%3D1%26vb2ctag%3D4_2030_5_1194_60&response_type=code&scope=snsapi_base&state=123#wechat_redirect';
	//dump($return);die;
	if($return == false ){
		
		$textContent = '出错了,请联系管理人员';
		$this->hongbao_content = $textContent;
	}else{
		
		//$return = $return."&api_id={$api_id}";
		$textContent = $return;
		$this->hongbao_content = $textContent;
	}
	//dump($str);die;
	if($product_name == "铁皮石斛枫斗"){//产品名称不能随意改变
		if($return == false ){
		
			$textContent = '出错了,请联系管理人员';
			$this->hongbao_content = $textContent;
		}else{
		
			//$return = $return."&api_id={$api_id}";
			$textContent = $return;
			$this->hongbao_content = $textContent;
		}
		$this->display('weixin_display_special_shihu');
	}else{
		$type = "quzheng";
		$return_openid = wei_hg($api_id,$key,$pec_order_id,$type); //获取openid,取证上传
		$this->uid = $api_id;
		$this->key = $key;
		$this->return_openid = $return_openid;
	$this->display();
	}
}
	
/*
 * 案例特殊需求，微信特殊展示页,转为老坑洮砚设计
 */
public function weixin_display_special_laokengtaoyan($pec_number){
	$separator = "<br>";
	$pec_order_id = substr($pec_number,0,3);
	$pec_random_code = substr($pec_number,3,6);
	//exit($pec_random_code);die;
	$returns = get_product_message_byPecOrder_topage_laokengtaoyan($pec_order_id,$pec_random_code);
	$product_datas = $returns['product'];//产品信息
	 
	foreach ($product_datas as $value){
		$this->html_content = $value['html_content'];
		$this->txt_content  = $value['txt_content'];
	}
	$company_datas = $returns['company_datas'];//公司信息
	 
	
	
	foreach ($company_datas as $value1){
		if(null == $value1['specific_content']){
			$this->company_html = " ";
		}else{
			$this->company_html = "<h4>企业信息</h4>".$value1['specific_content'];
		}
	}
	 
	$batch_message = $returns['batch_message'];//批次信息
	 
	//dump($str_batch_message);die;
	$this->batch_content = $batch_message;
	//dump($str);die;
	$this->display();
	
}
/*
 * 图片上传类
 */
function upload()
{
	$upload = new \Think\Upload();// 实例化上传类
	$rand = uniqid().rand(1, 10000);
	$upload->maxSize = 3145728 ;// 设置附件上传大小
	$upload->exts = array('jpg', 'png', 'jpeg');// 设置附件上传类型
	$upload->rootPath = './upload/'; // 设置附件上传根目录
	$upload->savePath = '/hongbao_company/';
	$upload->saveHash = false;
	$upload->saveName = "hongbao-".$rand;
	// 上传文件
	$info = $upload->upload();

	//dump($info);die;
	//如果没上传就显示系统默认图片 default.png
	if(!$info) {// 上传错误提示错误信息
		$this->error($upload->getError());
		return NULL;
	}
	foreach($info as $file){
		return $file['savepath'].$file['savename'];
	}
}
/*
 * 微信红包展示页面，渲染
 */
 public function hongbao_display(){
 	$this->display();
 }
/*
 * 微信红包展示页面动态加载，数据处理
 *
 */ 
 public function hongbao_display_edit(){
 	$uid = get_user_id();
 	$company_name = I('company_name');
 	$send_name = I('company_name');
 	$company_logo = $this->upload();
 	$onece_money = I('onece_money');
 	$bless_message = I('bless_message');
 	$uid = get_user_id();
 	
 	$return = M('hongbao_default')->where("uid='$uid'")->select();
 	//dump($return);die;
 	if(null == $return){//第一次配置
 		$datas['uid'] = $uid;
 		$datas['send_name'] = $send_name;
 		$datas['bless_message'] = $bless_message;
 		$datas['onece_money'] = $onece_money;
 		$return = M('hongbao_default')->add($datas);
 		//empty($return)?$this->error('配置成功!'):$this->success('配置成功!','/weixin/hongbao_default_display');
 			
 	}else{
 		$datas['send_name'] = $send_name;
 		$datas['bless_message'] = $bless_message;
 		$datas['onece_money'] = $onece_money;
 		$return = M('hongbao_default')->where("uid='$uid'")->data($datas)->save();
 		//empty($return)?$this->error('配置成功!'):$this->success('配置成功!','/weixin/hongbao_default_display');
 	}
 	//dump($company_name);
 	//dump($company_logo);
 	//dump($uid);die;
 	if(!empty($company_name) && !empty($company_logo)){
 		$hongbao_return = M('hongbao_display')->where("uid='$uid'")->select();
 		//dump($hongbao_return);die;
 		
 		if(null == $hongbao_return){//第一次上传
 			$datas['uid'] = $uid;
 			$datas['company_name'] = $company_name;
 			$datas['company_logo'] = $company_logo;
 			$reuturn = M('hongbao_display')->data($datas)->add();
 			//dump($reuturn);die;
 			if(null != $reuturn){
 				//$this->hongbao_success_display();
 				$this->success('上传成功','/weixin/hongbao_success_display');
 			}else{
 				$this->error('上传失败!');
 			}
 			//null != $return?$this->success('上传成功!'):$this->error('上传失败!');
 		}else{//非首次上传，即修改
 			$datas['company_name'] = $company_name;
 			$datas['company_logo'] = $company_logo;
 			$reuturn = M('hongbao_display')->where("uid='$uid'")->save($datas);
 			if(null != $reuturn){
 				//$this->hongbao_success_display();
 				$this->success('上传成功!','/weixin/hongbao_success_display');
 			}else{
 				$this->error('上传失败!');
 			}
 		}
 	}else{
 		$this->error('企业名称企业logo不能为空!');
 	}
 }
 /*
  * 红包展示页面上传成功,显示页面
  */
 public function hongbao_success_display(){
 	$uid = get_user_id();
 	$return = M('hongbao_display')->where("uid='$uid'")->select();
 	//dump($return);die;
 	foreach ($return as $val){
 		$this->company_name = $val['company_name'];
 		$this->company_logo = $val['company_logo'];
 	}
 	$uid = get_user_id();
 	$return = M('hongbao_default')->where("uid='$uid'")->select();
 	foreach ($return as $val){
 		$this->send_name = $val['send_name'];
 		$this->onece_money = $val['onece_money'];
 		$this->bless_message = $val['bless_message'];
 	}
 	$this->display();
 }
 /*
  * 红包默认配置，渲染
  */
 public function hongbao_default_display(){
 	$uid = get_user_id();
 	$return = M('hongbao_default')->where("uid='$uid'")->select();
 	foreach ($return as $val){
 		$this->send_name = $val['send_name'];
 		$this->onece_money = $val['onece_money'];
 		$this->bless_message = $val['bless_message'];
 	}
 	$this->display();
 }
 /*
  * 红包默认配置，数据处理
  */
 public function hongbao_default_edit(){
 	
 	$send_name = I('send_name');
 	$onece_money = I('onece_money');
 	$bless_message = I('bless_message');
 	$uid = get_user_id();
 	
 	$return = M('hongbao_default')->where("uid='$uid'")->select();
 	//dump($return);die;
 	if(null == $return){//第一次配置
 	    $datas['uid'] = $uid;
 		$datas['send_name'] = $send_name;
 		$datas['bless_message'] = $bless_message;
 		$datas['onece_money'] = $onece_money;
 		$return = M('hongbao_default')->add($datas);
 	    empty($return)?$this->error('配置成功!'):$this->success('配置成功!','/weixin/hongbao_default_display');
 		
 	}else{
 		$datas['send_name'] = $send_name;
 		$datas['bless_message'] = $bless_message;
 		$datas['onece_money'] = $onece_money;
 		$return = M('hongbao_default')->where("uid='$uid'")->data($datas)->save();
 		empty($return)?$this->error('配置成功!'):$this->success('配置成功!','/weixin/hongbao_default_display');
 	}
 	
 	
 }
 /*
  * 开启红包功能的订单列表，首页渲染
  */
 public function hongbao_pecorder_display(){
 	$uid = get_user_id();
 	if(null == $uid){
 		$this->error('出错了,请重新登陆!');
 	}
 	//dump($uid);
 	$this->hongbao_sum_statistic();
 	$return = M('pec_order')->where("cid='$uid' and type='1'")->select();
 	//dump($return);die;
 	$hongbao_return = M('hongbao_sum_statistic')->where("uid='$uid'")->getField('hongbao_sum',true);
 	//dump($hongbao_return);
 	$hongbao_sum = 0;
 	foreach ($hongbao_return as $val){
 		$hongbao_sum += $val;
 	}
 	//dump($hongbao_sum);die;
 	$this->hongbao_sum = $hongbao_sum;//此用户的所需红包总数
 	$hongbao_send_return = M('hongbao_sum_statistic')->where("uid='$uid'")->getField('hongbao_send_sum',true);
 	//dump($hongbao_send_return);
 	$hongbao_send = 0;
 	foreach ($hongbao_send_return as $val){
 		$hongbao_send += $val;
 	}
 	$this->hongbao_send_sum = $hongbao_send;//此用户已领取红包总数
 	//dump($return);
 	$sql = 'select * from __PREFIX__pec_order,__PREFIX__hongbao_sum_statistic where __PREFIX__pec_order.pec_order_id = __PREFIX__hongbao_sum_statistic.pec_order_id and __PREFIX__pec_order.cid = __PREFIX__hongbao_sum_statistic.uid and __PREFIX__pec_order.type=1 and __PREFIX__hongbao_sum_statistic.uid='.$uid;
   // dump($sql);
 	$model = new \Think\Model;
    $return_list = $model->query($sql);
    //dump($return_list);
    foreach ($return_list as &$val){
    	if(1 == $val['type']){
    		$val['type'] = "红包功能已开启";
    	}else{
    		$val['type'] = "红包功能已关闭";
    	}
    }
    
 	$this->assign('list',$return_list);//此用户每个订单对应的红包
 	$this->display();
 }
 /*
  * 动态计算每个用户(每个订单)还剩多少红包，余额多少
  */
 public function hongbao_sum_statistic(){
 	$uid = get_user_id();
 	//dump($uid);die;
 	if(null == $uid){
 		$this->error('请重新登陆!','/user/login');break;
 	}
 	$return = M('pec_order')->where("cid='$uid' and type=1 and isdownload=1 ")->getField('pec_order_id',true);// 当前登陆用户且开启红包功能的订单
 	if(null == $return){
 		$this->error('没有订单开启红包功能或开启红包功能的订单没有被下载!');die;
 	}
 	$table_name = "pec".$uid; //获取每个用户的表名
 	//dump($table_name);
 	$table = M($table_name);
 	$hongbao_sum_statistic = M('hongbao_sum_statistic'); //红包数量统计表
 	//dump($return);
 	foreach ($return as $val){
 		$pec_order_id = $val;
 		//dump($pec_order_id);
 		//dump($table_name);
 		$random_return = $table->where("pec_order_id='$pec_order_id'")->getField('pec_random_code');
 		//dump($random_return);
 		$pec_count = count(json_decode($random_return));//每个开启红包功能订单的数量
 		//dump($pec_count);
 		//dump($hongbao_sum_statistic->where("uid='$uid'")->getField('pec_order_id'));die;
 		if(null == ($hongbao_sum_statistic->where("uid='$uid' and pec_order_id='$pec_order_id'")->select())){//该用户该订单  第一次记录
 			$datas['uid'] = $uid;
 			$datas['pec_order_id'] = $pec_order_id;
 			$datas['hongbao_sum'] = $pec_count;
 			//$datas['hongbao_send_sum'] = 0; //红包发送 初始化0
 			$hongbao_sum_statistic->add($datas);
 			
 		}else{
 			//dump($pec_order_id);
 			//dump($hongbao_sum_statistic->where("uid='$uid' and pec_order_id='$pec_order_id'")->getField('hongbao_send_sum'));
 			//dump($uid);die;
 			$datas['hongbao_sum'] = $pec_count - ($hongbao_sum_statistic->where("uid='$uid' and pec_order_id='$pec_order_id'")->getField('hongbao_send_sum'));//红包总数-已领取的红包数
 			$hongbao_sum_statistic->where("uid='$uid' and pec_order_id='$pec_order_id'")->save($datas);
 			
 		}
 		
 		
 		
 	}
 	
 }
 
 /*
  * 红包详细配置，针对每个用户具体的每个订单  配置
  */
 public function hongbao_pecorder_configure($pec_order_id = 0){
 	
 	$uid = get_user_id();
 	$this->uid = $uid;
 	$this->pec_order_id =  $pec_order_id;
 	
 	$return = M('hongbao_pec_order')->where("uid='$uid' and pec_order_id='$pec_order_id'")->select();
 	foreach ($return as $val){
 		$this->send_name = $val['send_name'];
 		$this->onece_money = $val['once_money'];
 		$this->bless_message = $val['bless_message'];
 	}
 	
 	$this->display();
 	
 }
 /*
  * 红包详细配置处理
  */
 public function hongbao_pecorder_configure_edit(){
 	$uid = I('uid');
 	$pec_order_id = I('pec_order_id');
 	
 	$send_name = I('send_name');
 	$onece_money = I('onece_money');
 	$bless_message = I('bless_message');
 	$hongbao_pec_order = M('hongbao_pec_order');
 	$return_pecorder = $hongbao_pec_order->where("uid='$uid' and pec_order_id='$pec_order_id'")->select();
 	//dump($return_uid);die;
 	$datas['uid'] = $uid;
 	$datas['pec_order_id'] = $pec_order_id;
 	$datas['send_name'] = $send_name;
 	$datas['once_money'] = $onece_money;
 	$datas['bless_message'] = $bless_message;
 	$data['send_name'] = $send_name;
 	$data['once_money'] = $onece_money;
 	$data['bless_message'] = $bless_message;
 	(null == $return_pecorder)?((null != $hongbao_pec_order->add($datas))?redirect("/weixin/hongbao_pecorder_configure/{$pec_order_id}"):$this->error('请修改后提交!')):
 	((null != $hongbao_pec_order->where("uid='$uid' and pec_order_id='$pec_order_id'")->save($data))?redirect("/weixin/hongbao_pecorder_configure/{$pec_order_id}"):$this->error('请修改后提交!'));
 }
 /*
  * 微信拍照
  */
 public function photo_upload(){
 	//dump($_GET);die;
 	$state = $_GET['state'];
 	//dump($state);
 	$api_id = substr($state,0,5);
 	$pec_order_id = substr($state, 5,3);
 	$key = substr($state, 8);
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
 		$this->username = $user_info['openid'];
 		$this->key = $key; //身份数字号
 		$result = M('api_list')->where("api_id = '$api_id'")->find();
 		$this->uid = $result['api_uid'];
		$this->display();
 }
 
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
 }
 /**
  * 取证信息
  * @author lc
  */
 public function edit_evidence_display(){
 	if (!is_login()) {
 		$this->error('请重新登录!');die;
 	}
 	
 	$uid = get_user_id();
 	
 	$result = M('evidence')->where("uid = '$uid'")->order("createtime desc")->select();
 	
 	foreach ($result as &$val){
 		$username = $val['username'];
 		$result_list = M('hongbao_user_isget')->where("openid = '$username'")->find();
 		//dump($result_list);
 		if($result_list['nickname'] != null)
 		$val['username'] = $result_list['nickname'];
 		if($val['number'] == "undefined")
 		$val['number'] = "误码";
 		if($val['location'] == null)
 		$val['location'] = "无";
 		if($val['type'] == 0){
 			$val['type'] = "未处理";
 		}else{
 			$val['type'] = "已处理";
 		}
 		$val['openid'] = $username;
 	}
 		//dump($result);
 	$this->list = $result;
 	
 	$this->display();
 }
 /**
  * 编辑取证
  * @author lc
  */
 public function edit_evidence($id = null,$openid = null){
 	$uid = get_user_id();
 	/*$condition['uid'] = $uid;
 	$condition['username'] = $openid;*/
 	$condition['id'] = $id;
 	$result = M('evidence')->where($condition)->find();
 	
 	$return = M('hongbao_user_isget')->where("openid = '$openid'")->find();
 	
 	if($return['sex'] == '1'){
 		$result['sex'] = '男';
 	}else{
 		$result['sex'] = '女';
 	}
 	$result['username'] = $return['nickname'];
 	
 	$this->assign = $result;
 	$this->display();
 }
 /**
  * 反馈取证
  * @author lc
  */
 public function feedback_evidence(){
 	
 }
 

 }

