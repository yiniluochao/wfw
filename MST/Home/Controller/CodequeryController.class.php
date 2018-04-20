<?php
namespace Home\Controller;

use Think\Controller;

class CodequeryController extends Controller{
	public function query(){
		$this->display();
	}
	
	/*
	 * 电码查询，此方法为判断是否存在用户存在的电码号；如果存在，则继续执行get_product_message_byPecOrder()【电码查询方法】
	 */
	public function codequery(){
		
		if (true == is_login()) {
			$this->page_id = 3;
		
			$search_key = I('post.key');
		
			if (empty($search_key)) {
				$this->error("关键字为空!");
				return;
			}
				if(strlen($search_key) == 8){
	 			$profix_foure = substr($search_key,0,4);
	 			$profix_six = pec_dataPrefix_code_to_six($profix_foure);
	 			$pec_random_code = substr($search_key, 4,4);
				}
				if(strlen($search_key) == 9){
					$profix_foure = substr($search_key,0,4);
					$profix_six = pec_dataPrefix_code_to_six($profix_foure);
					$pec_random_code = substr($search_key, 4,5);
				}
				if(strlen($search_key) == 10){
					$profix_foure = substr($search_key,0,4);
					$profix_six = pec_dataPrefix_code_to_six($profix_foure);
					$pec_random_code = substr($search_key, 4,6);
				}
				if(strlen($search_key) == 11){
					$profix_foure = substr($search_key,0,4);
					$profix_six = pec_dataPrefix_code_to_six($profix_foure);
					$pec_random_code = substr($search_key, 4,7);
				}
				if(strlen($search_key) == 12){
					$profix_foure = substr($search_key,0,4);
					$profix_six = pec_dataPrefix_code_to_six($profix_foure);
					$pec_random_code = substr($search_key, 4,8);
				}
		//dump($profix_six);die;
		//dump($pec_random_code);die;
		$index = M('company_user');
		$condition['login'] = get_user_name();
		//dump(get_user_name());die;
		$return = $index->where($condition)->getField('pec_name');
		if($return != null){
			//dump($return);die;
			$pec_name = M($return);
			//dump($pec_name);die;
			$condition['pec_dataPrefix_code'] = $profix_six;
			//var_dump($profix_six);die;
			$datas = $pec_name->field('pec_random_code,pec_order_id')->where($condition)->select();
			//var_dump($datas);die;
			foreach ($datas as $val){
				$data = json_decode($val["pec_random_code"],true);
				if(null == $data){
					$this->error("无法获取数据，请重新输入!");
				}
				//var_dump($data);die;
				foreach ($data as $val1){
					//var_dump($val);die;
					if($pec_random_code == $val1)
					{
						//dump("ok");
						//var_dump($val);
						$pec_order_id = $val["pec_order_id"];
						
						$return = get_product_message_byPecOrder_byPage($pec_order_id,$pec_random_code);
						if($return == false){
							$this->error('查无此码');
						}else{
							$product_datas = $return['product'];
							$batch_datas = $return['batch_message'];
							$company_datas = $return['company_datas'];
							foreach ($product_datas as $val1){
								$this->product_name = $val1['product_name'];
								//$this->txt_content = $val1['txt_content'];
							}
							$this->bid = $return['batch_message'];
						foreach ($company_datas as $val2){
								$this->company_name = $val2['company_name'];
								$this->company_content = $val2['company_content'];
							}
						
					
						}
					
							
					
						
						//dump($pec_order_id);
					
				}
			}
		
			
		}
	}
		
	}
	$this->display();
}
}