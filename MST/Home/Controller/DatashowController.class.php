<?php

namespace Home\Controller;

use Think\Controller;

class DatashowController extends Controller {
	
	/**
	 * 配置页面
	 */
	public function index() {
		if(!is_login()) redirect('/user/login');
		// 加载产品
		$product = M ( 'product' );
		$uid = get_user_id ();
		$return = $product->where ( "cid = '$uid'" )->select ();
		$this->assign ( 'list', $return );
		// 加载省
		$provicial = M ( 'provincial' );
		$result = $provicial->select ();
		$this->assign ( 'plist', $result );
		$this->display ();
	}
	/**
	 * 获取批次代码
	 */
	public function getBatchcode($pid = null) {
		$batch = M ( 'batch' );
		$result = $batch->where ( "pid = $pid" )->getField ( 'batch_code', true );
		$result = json_encode ( $result );
		echo $result;
	}
	/**
	 * @todo 前端条件数据接口
	 */
	public function getCondition_data($data = null){
		if($data == 'product'){
			$result = $this->getProduct();
			echo $result;
		}else if($data == 'create_place_province'){
			$result = $this->getProvince();
			echo $result;
		}else if($data == 'sell_place_province'){
			$result = $this->getProvince();
			echo $result;
		}else{
			echo 'false';
		}
		
	}
	/**
	 * @todo 获取产品
	 */
	public function getProduct(){
		//$uid = get_user_id();
		$uid = 15;
		$product = M('product');
		$return = $product -> where("cid = '$uid'") -> select();
		
		foreach ($return as &$val){
			unset($val['cid']);
			unset($val['product_code']);
			unset($val['createtime']);
			unset($val['txt_content']);
			unset($val['html_content']);
			unset($val['pic_url']);
			unset($val['company_html_content']);
		}
		
		for ($i = 0;$i < count($return); $i++){
			$r[$i]['id'] = $return[$i]['pid'];
			$r[$i]['name'] = $return[$i]['product_name'];
		}
		
		return json_encode($r);
	}
	/**
	 * @todo 获取省份
	 */
	public function getProvince(){
		$tb_province = M('tb_province');
		$return = $tb_province -> select();
		return json_encode($return);
		
	}
	/**
	 * 获取城市
	 */
	public function getCity($province_id = null) {
		$tb_city = M ( 'tb_city' );
		$return = $tb_city->where ( "provinceid = '$province_id'" )->getField ( 'name', true );
		echo json_encode ( $return );
	}
	
	/**
	 * 读取配置文件
	 */
	public function config($diagram_type = null) {
		$url = APP_PATH . 'Home/Conf/business-config.xml';
		if (file_exists ( $url )) {
			$xml = simplexml_load_file ( $url );
			dump($xml);
		} 
		else {
			exit ( 'Error.' );
		}
	}
	
	/**
	 * 数据查询接口
	 */
	public function commondatarequest($datas = null) {
// 		// 模拟json测试
		$file = APP_PATH . 'Home/Conf/json.txt';
		if (! file_exists ( $file )) {
			echo 'file not exist';
			exit ();
		}
		$content = file_get_contents ( $file );
		
		
		$content = json_decode ( $datas, true );
		
	//	dump($content);die;
		//echo json_encode($content);die;
		 //dump($content);die;
		 
		 $uid = get_user_id ();
		foreach ( $content as $k => $v ) {
			if ($k == "subject") {
				//dump($v[0]['bid']);
				if($v[0]['bid'] != null){
					foreach ($v[0]['bid'] as &$val){
							
						$val = M(pec_order) -> where("batch_code = '$val'") ->getField('bid');
							
					}
				}
				
				foreach ( $v [0] as $k1 => $v1 ) {
					 
					$value = array ();
					$i = 0;
					foreach ( $v1 as $v2 ) {
						
						$value [$i] = $v2;
						$i += 1;
						
					}
					
					$condition [$k1] = array (
							'in',
							$value 
					);
				}
				//删除 数组中值null 
				$condition = array_delelet_null($condition);
				
				 //dump($condition);
				//$condition ['uid'] = 15; // 测试
				//dump($condition);
				
//   				  $result = M('consumers')->where($condition)->select();
//   				  dump($result);die;
				 
			} else if ($k == "condition") {
				
				foreach ( $v [0] as $kc1 => $vc1 ) {
					$valuec = array ();
					$i = 0;
					foreach ( $vc1 as $vc2 ) {
						
						$valuec [$i] = $vc2;
						$i += 1;
					}
					
					$condition [$kc1] = array (
							'in',
							$valuec 
					);
				}
				$condition = array_delelet_null($condition);
				// dump($condition);
			//	$result = M ( 'consumers' )->where ( $condition )->select ();
				// dump($result);die;
			} else if ($k == "key") {
				
				$val = $content ["value"];
				if ($val == "y1") // 有效查询次数
				{
					//dump ( $v );
					if ($v [0] ["year"] != null && null == $v [0] ["province"]) { // 年not null 省null
						$i = 0;
						foreach ( $v as $vm ) {
							foreach ( $vm ["month"] as $valm ) {
								$time = $vm ["year"] . '-' . $valm;
								$condition ['createtime'] = array (
										'like',
										'%' . $time . '%' 
								);
								$result = M ( 'consumers' )->where ( $condition )->select ();
								if ($result != null) {
									$i = 0;
									foreach ( $result as $rv ) {
										$i += $rv ["querycount"];
									}
								}
								$result_year [$time] = $i;
							}
						}
						//dump($result_year);die;
						echo json_encode($result_year);die;
					} else if ($v [0] ["year"] == null && null != $v [0] ["province"]) {
						foreach ( $v as $vm ) {
							foreach ( $vm ["province"] as $valm ) {
								
								$condition ['province'] = array (
										'like',
										'%' . $valm . '%' 
								);
								$result = M ( 'consumers' )->where ( $condition )->select ();
								// dump($result);die;
								if ($result != null) {
									$i = 0;
									foreach ( $result as $rv ) {
										$i += $rv ["querycount"];
									}
								}
								$result_province [$valm] = $i;
							}
						}
						//dump($result_province);
						echo json_encode($result_province);die;
					} else if (null != $v [0] ["province"] && null != $v [0] ["year"]) {
						
						foreach ( $v as $vm ) {
							foreach ( $vm ["month"] as $valm ) {
								$time = $vm ["year"] . '-' . $valm;
								$condition ['createtime'] = array (
										'like',
										'%' . $time . '%' 
								);
								foreach ($vm['province'] as $valp){
								//dump($valp);
								if ($valp != null) {
									$condition ['province'] = array (
											'like',
											'%' . $valp . '%' 
									);
									//dump($condition);
									
									$result = M ( 'consumers' )->where ( $condition )->select ();
								//	dump($result);
									//if ($result != null) {
										$i = 0;
										foreach ( $result as $rv ) {
											$i += $rv ["querycount"];
										}
									//}
									
									$result_province_year [$valp][$time] = $i;
								}
							}
							}
							
							
						}
					}
					$answer = add_key($result_province_year);
					//dump($answer);die;
					//dump($result_province_year);die;
					echo (json_encode($answer));
				} else if ($val == "y3") { // 红包领取量
				}
			}
		}
	}
	/**
	 * @todo 数据处理接口,无维度
	 * @version V1
	 */
	public function commondatarequestv1(){
		//	模拟json测试
		$file = APP_PATH . 'Home/Conf/json1.txt';
		if (! file_exists ( $file )) {
			echo 'file not exist';
			exit ();
			}
		$batch = M('batch');
		$pec_order = M('pec_order');
		$content = file_get_contents ( $file );
		$content = json_decode ( $content, true );
		//dump($content);
		//平凑$condition
		foreach ($content['legend'] as $k => $v){
			foreach ($v as $v_k => $v_v){
				$condition[$k] = $v_v;    //支解图列条件,每一个条件对应一组附件条件
				//处理附件条件,入$condition,用户batch表(批次)
			//	dump($condition);
				$return = getAttachment_condition($content);
			//	dump($return);
				$end_condition = array_merge($condition,$return);
			//	dump($end_condition);die;
			//去除createtime,下订单createtime,而非批次createtime
				$creatime = $end_condition['createtime']; //保存createtime,pec_order查询使用
				if(null != $end_condition['createtime']){
				unset($end_condition['createtime']);
				}
			//	dump($end_condition);
				//根据条件，获取batch表中符合条件的bid
				$bid = $batch -> where($end_condition) -> getField('bid',true);
			//	dump($bid);
				//pec_order表中查询符合bid的且符合createtime的sum
				$order_condition['bid'] = array ('in',$bid);
				$order_condition['createtime'] = $creatime;
			//	dump($order_condition);
				$sums = $pec_order -> where($order_condition) ->getField('sum',true);
			//	dump($sums);
				$sum = 0;
				foreach ($sums as $val){
					$sum = $sum + intval($val);
				}
				//dump($sum);die;
				$result[$k][$v_v] = $sum;
			//	dump($result);
			}
		}
		$result = json_encode($result);
		dump($result);
// 		dump($condition);
// 		
// 		dump($bid);
	}
	/**
	 * @todo 数据处理接口,有维度,无维度统一接口
	 * @version V2
	 */
	public function commondatarequestv2($content = null){
	
// 		if(null == $content)
// 		{
// 			echo false;die;
// 		}
		include_once '\ThinkPHP\Library\Vendor\Json\json.php';
// 		//	模拟json测试
// 		$file = APP_PATH . 'Home/Conf/json6.txt';
// 		if (! file_exists ( $file )) {
// 			echo 'file not exist';
// 			exit ();
// 		}
		$batch = M('batch');
		$pec_order = M('pec_order');
		//$content = file_get_contents ( $file );
		$content = json_decode ( $content, true );
		if(null == $content){
			echo 'data_error';die;
		}
		//dump($content);
		//平凑$condition
		foreach ($content['legend'] as $k => $v){
			foreach ($v as $v_k => $v_v){ //产地，销售地特殊处理
				if('startpoint' == $k || 'sellpoint' == $k){
					$condition[$k] = $v_v['city'];
				}else{
				$condition[$k] = $v_v;
				}    //支解图列条件,每一个条件对应一组附件条件
				//dump($condition);
				//处理附件条件,入$condition,用户batch表(批次)
				if(null != $content['legendx']) //如果维度不为空.处理维度，一 一对应
				{
					//dump($v_v);
					foreach ($content['legendx'] as $led_k => $led_v){
						//dump($led_v);
						$i = 0;
						foreach ($led_v as $led_v_v){	//平凑维度条件
						//	dump($led_v_v);die;
						if('retail_price' == $led_k){ 
							//dump($led_v);
							$price_deal_return = retail_price_deal($led_k,$led_v_v);
							//dump($price_deal_return);die;
						}else if('startpoint' == $led_k || 'sellpoint' == $led_k){
							//dump($led_v_v);dump(count($led_v_v));
							if(count($led_v_v) == 1){ //只有省
								$condition[$led_k] = $led_v_v;
							}else{
							if(null != $led_v_v['city']){ //简单处理
								$condition[$led_k] = $led_v_v['city'];
							}else{
								//dump($led_v_v['province']);dump(1);
								$led_v_v['city'] =$led_v_v['province'];
								$condition[$led_k] = $led_v_v['city'];
							}
						}
							
							//dump($led_k);dump($led_v_v);die;
						}else{
						$condition[$led_k] = $led_v_v;
						} 
						//dump($condition);
						if(null != $price_deal_return){   
						$condition = array_merge($condition,$price_deal_return);
						}
					//	dump($content);
						//dump($content['condition']);
						$return = getAttachment_condition($content['condition']); //处理condition
						//dump($return);die;
						if($return){ //没有condition
							$end_condition = array_merge($condition,$return);
						}else{
							$end_condition = $condition;
						}
					//	$end_condition = array_merge($condition,$return);
						//dump($end_condition);
						//去除createtime,下订单createtime,而非批次createtime
						$creatime = $end_condition['createtime']; //保存createtime,pec_order查询使用
						if(null != $end_condition['createtime']){
							unset($end_condition['createtime']);
						}
						//dump($end_condition);die;
						//根据条件，获取batch表中符合条件的bid
						$bid = $batch -> where($end_condition) -> getField('bid',true);
						//dump($bid);
						//pec_order表中查询符合bid的且符合createtime的sum
						if(null != $bid){
							$order_condition['bid'] = array ('in',$bid);
							if(null != $creatime){
								$order_condition['createtime'] = $creatime;
							}
							//dump($order_condition);
							$sums = $pec_order -> where($order_condition) ->getField('sum',true);
							//dump($sums);
							$sum = 0;
							foreach ($sums as $val){
								$sum = $sum + intval($val);
							}
						}else{
							$sum = 0;
						}
						
 //						dump($sum);
// 						dump($k);

						if('startpoint' == $k || 'sellpoint' == $k){ //图例，产地，销售地处理
// 							dump($k);
 							//dump($v_v);

							if(count($led_v_v) == 1){ //只有省
								$v_v1 =$led_v_v;
								//dump($led_v_v);
							}else{
							$v_v1 =$v_v['province'].'-'. $v_v['city'];
							}
							
						}
						//dump($v_v1);
						if('retail_price' == $led_k){   // 维度，零售价处理,产地，销售地处理
							$led_v_v = 'retail_price'.$i;
							if('startpoint' == $k || 'sellpoint' == $k){
								$result[$k][$v_v1][$led_k][$led_v_v] = $sum;
							}else{
							$result[$k][$v_v][$led_k][$led_v_v] = $sum;
							}
							$i += 1;
						}else if('startpoint' == $led_k || 'sellpoint' == $led_k){
							if(count($led_v_v) == 1){ //只有省
								$led_v_v1 =$led_v_v;
								//dump($led_v_v);
							}else{
							$led_v_v1 = $led_v_v['city'];
							}
							$result[$k][$v_v][$led_k][$led_v_v1] = $sum;
						}
						else{
							if('startpoint' == $k || 'sellpoint' == $k){ //图例产地，销售地
								$result[$k][$v_v1][$led_k][$led_v_v] = $sum;
							}else{
							$result[$k][$v_v][$led_k][$led_v_v] = $sum;
							}
						}
						//dump($result);
						}
					
				}
				}else{
					$result = $this->commondatarequest_x_null($content);
				}
			}
		}
		//原生数据
		//dump($result);die;
		//可使用图形类型拼凑,即添加type
		$graphics_return = getGraphics_type($content);
	//	dump($graphics_return);
		$result = array_merge($graphics_return,$result);
		//dump($result);
		//方便前端性数据处理
		$result = getFront_deal_json($result);
		//dump($result);
		$result = array_merge($graphics_return,$result); //getFront_deal去掉了type 这里加上
		//dump($result);die;
		//返回前端数据最后json处理
		//$result = \Util::json_encode($result);
 		$result = json_encode($result,JSON_UNESCAPED_UNICODE);
 		//dump(($result));die; //stripslashes(),去掉json反斜杠
 		//dump($content);
 		echo $result;
		//dump($condition);
		//dump($bid);
	}
	/**
	 * @todo 数据处理接口，无维度
	 */
	public function commondatarequest_x_null($content){
		$batch = M('batch');
		$pec_order = M('pec_order');
		foreach ($content['legend'] as $k => $v){
			foreach ($v as $v_k => $v_v){
				$condition[$k] = $v_v;    //支解图列条件,每一个条件对应一组附件条件
				//处理附件条件,入$condition,用户batch表(批次)
				//	dump($condition);
				$return = getAttachment_condition($content['condition']); 
				//	dump($return);
				if($return){ //没有condition
					$end_condition = array_merge($condition,$return);
				}else{
					$end_condition = $condition;
				}
				//	dump($end_condition);die;
				//去除createtime,下订单createtime,而非批次createtime
				$creatime = $end_condition['createtime']; //保存createtime,pec_order查询使用
				if(null != $end_condition['createtime']){
					unset($end_condition['createtime']);
				}
					//dump($end_condition);die;
				//根据条件，获取batch表中符合条件的bid
				$bid = $batch -> where($end_condition) -> getField('bid',true);
				//dump($bid);
				//pec_order表中查询符合bid的且符合createtime的sum
				if(null != $bid){
				$order_condition['bid'] = array ('in',$bid);
				if(null != $creatime){
				$order_condition['createtime'] = $creatime;
				}
				//dump($order_condition);dump($creatime);die;
				$sums = $pec_order -> where($order_condition) ->getField('sum',true);
				//dump($sums);die;
				$sum = 0;
				foreach ($sums as $val){
					$sum = $sum + intval($val);
				}
				}else{
					$sum = 0;
				}
				//dump($sum);die;
				if('startpoint' == $k || 'sellpoint' == $k){ //图例，产地，销售地处理
					// 							dump($k);
					//dump($v_v);
					$v_v1 =$v_v['province'].'-'. $v_v['city'];
					$result[$k][$v_v1] = $sum;
						
				}else{
				$result[$k][$v_v] = $sum;
				}
				//	dump($result);
			}
		}
		return $result;
	}
}