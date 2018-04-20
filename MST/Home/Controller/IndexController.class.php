<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller {
	public function index() {
		// 根据会话展示登录界面或者登陆后首页
		if (1 == get_user_type ()) {
			$this->mode = 1;
		} else {
			$this->mode = 0;
		}
		if (is_login ()) {
			$this->page_id = 1;
			$this->user_name = get_user_name ();
			$uid = get_user_id ();
			$photo_url = M ( 'edit_company' )->where ( "cid = '$uid'" )->getField ( 'photo_url' ); // 企业图
			$company_name = M ( 'company' )->where ( "uid = '$uid'" )->getField ( 'company_name' );
			$this->company_name = $company_name;
			$this->photo_url = $photo_url;
			$product = M ( 'product' )->where ( "cid = '$uid'" )->limit ( 3 )->select (); // 产品信息
			                                                                    // $batch = M('batch')->where("cid = '$uid'")->limit(3)->select(); //批次信息
			$sql = "select * from __PREFIX__batch,__PREFIX__product where __PREFIX__batch.pid=__PREFIX__product.pid and __PREFIX__batch.cid = $uid order by __PREFIX__batch.createtime desc limit 3";
			$batch = M ()->query ( $sql );
			// dump($batch);
			foreach ( $batch as &$val ) {
				if ($val ['startpoint'] == 'SystemDefault'){
					$val ['startpoint'] = '';
					$val ['sellpoint'] = '';
					$val ['retail_price'] = '';
					$val['batch_code'] = $val['batch_code'].'(auto)';
				}
			}
			$this->blist = $batch;
			$this->list = $product;
			// dump($product);
			if (1 == get_user_type ()) {
				$this->mode = 1;
			} else {
				$this->mode = 0;
			}
			$this->display ();
		} else {
			redirect ( "/User/login" );
		}
	}
	
	// 产品身份码查询
	public function pec_search() {
		$pec_id = I ( 'post.pec_id' );
		 //dump($search_key);die;
		if (empty ( $pec_id )) {
			
			$this->error ( "关键字为空!" );
			return;
		}
		// dump($search_key);die;
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
			//dump(20);die;
			$pec_random_code = $pec_id;
		}
		// dump($profix_six);die;
		// dump($pec_random_code);die;
		$index = M ( 'company_user' );
		$condition ['login'] = get_user_name ();
		// dump(get_user_name());die;
		$return = $index->where ( $condition )->getField ( 'pec_name' );
		// dump($return);die;
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
					// var_dump($val);die;
					if ($pec_random_code == $val1) {
						// dump("ok");
						// var_dump($val1);die;
						$pec_order_id = $val ["pec_order_id"];
						// dump($pec_order_id);die;
						$return = get_product_message_byPecOrder_byPage ( $pec_order_id, $pec_random_code );
						
						if (null == $return) {
							$this->error ( '查无此码' );
						} else {
							
							$product_datas = $return ['product'];
							$batch_datas = $return ['batch_message'];
							$company_datas = $return ['company_datas'];
							foreach ( $product_datas as $val1 ) {
								$this->product_name = $val1 ['product_name'];
								$this->product_code = $val1 ['product_code']; // 自定义产品代码
							}
							$pec_order = M ( 'pec_order' );
							$pec_order_code = $pec_order->where ( "pec_order_id='$pec_order_id'" )->getField ( 'pec_order_code' );
							$this->pec_order_code = $pec_order_code; // 产品身份码订单号
							                                         // 批次信息
							$bid = $return ['batch_message'];
							$batch = M ( 'batch' );
							$batch_list = $batch->field ( 'batch_code,startpoint,sellpoint,retail_price' )->where ( "bid='$bid'" )->select ();
							// dump($batch_list);die;
							foreach ( $batch_list as $batch_value ) {
								$this->batch_code = $batch_value ['batch_code'];
								$this->startpoint = $batch_value ['startpoint'];
								$this->sellpoint = $batch_value ['sellpoint'];
								$this->retail_price = $batch_value ['retail_price'];
							}
							$this->display ();
							die ();
						}
					}
				}
			}
			$this->error ( '查无此码' );
		}
	}
	
	// 修改密码
	public function changepw() {
		if (true == is_login ()) {
			// 光标锁定
			$this->page_id = 8;
			$this->display ();
		} else {
			$this->__userNotLogin ();
		}
	}
	
	// 处理
	public function changepw_deal() {
		if (IS_POST) {
			if (true == is_login ()) {
				$o_password = I ( 'post._o_password' );
				$new_password = I ( 'post._new_password' );
				$new_password_age = I ( 'post._new_password_ag' );
				
				if (empty ( $o_password ) || empty ( $new_password ) || empty ( $new_password_age )) {
					$this->error ( '密码不能为空!' );
				}
				
				if ($new_password != $new_password_age) {
					$this->error ( "亲~两次密码输入不一致哦!" );
				}
				
				if (checkPassowrd ( get_user_name (), $o_password )) {
					if (renewPassword ( get_user_name (), $new_password )) {
						$this->success ( '修改成功!' );
					} else {
						$this->error ( '修改密码出现错误!' );
					}
				} else {
					$this->error ( '原始密码错误!' );
				}
			}
		}
	}
	
	// 未登录[页面跳转]
	protected function __userNotLogin() {
		$this->error ( "亲还没有登录呢!", "/user/login" );
	}
	
	// 未准备[页面跳转]
	protected function __NotReady() {
		$this->error ( "本功能还没有完工!", "/" );
	}
}