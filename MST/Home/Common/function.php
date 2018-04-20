<?php

// 用户是否登录
function is_login() {
	if (session ( 'logined' ) == false && session ( 'logined' ) == null)
		return false;
	else
		return true;
}
function get_user_id() {
	return session ( 'userid' );
}
function check_verify($code, $id = '') {
	$verify = new \Think\Verify ();
	return $verify->check ( $code, $id );
}
function get_user_type() {
	return session ( 'type' );
}
function get_user_name() {
	return session ( 'username' );
}
// 循环写数据库，成功后进行查询输出
function get_pec_file($sum, $pid, $pec_order_id, $uid, $number) {
	// 判断存在性，存在直接读并提供下载
	// 不存在则进行生成
	$pec_dataPrefix_code = get_date_number (); // 获取时间，并通过时间索引数据库
	
	$table_name = "pec" . $uid;
	// dump($table_name);die();
	$gen_prefix = $_SERVER ['DOCUMENT_ROOT'] . "/MST/Home/Download/Pec/";
	$postfix = ".txt";
	$separator = "\r\n";
	$rand = sha1 ( sha1 ( sha1 ( $pec_order_id ) ) );
	$gen_path = $gen_prefix . $rand . $postfix;
	$str = "";
	$buffer = 1024;
	$str_count = 0;
	// 若不存在订单文本则写数据库并在服务器生成文本
	// var_dump(file_exists($gen_path));die;
	if (! file_exists ( $gen_path )) {
		/**
		 * 记录用户每天下载数，并判断 *
		 */
		$count_query = M ( 'count_query' );
		
		$this_time = Date ( 'Y-m-d' );
		$condition ['time'] = $this_time;
		$uid = $count_query->where ( $condition )->getField ( 'uid' );
		if (null == $uid) {
			$datas ['uid'] = get_user_id ();
			$datas ['count'] = $sum;
			$datas ['time'] = Date ( 'Y-m-d' );
			$count_query->data ( $datas )->add ();
		} else {
			$this_time = Date ( 'Y-m-d' );
			$where ['uid'] = $uid;
			$where ['time'] = $this_time;
			$count = $count_query->where ( $where )->getField ( 'count' );
			$temp_count = $count + $sum;
			if ($temp_count > 1000000) {
				return false;
				
			} else {
				
				$datas ['count'] = $temp_count;
				$count_query->where ( $where )->save ( $datas );
				$temp_count = 0;
			}
		}
		/**
		 * *
		 */
		$pec = M ( $table_name );
		// dump($pec);die();
		$is_exist_pec_order = $pec->where ( "pec_dataPrefix_code = $pec_dataPrefix_code" )->getField ( 'pec_order_id', true );
		// var_dump($is_exist_pec_order);die();
		$bool = is_exist_pec_order_id ( $is_exist_pec_order ); // 判断数据库中是否已存在此pec_order_id
		                                                    // var_dump($bool);
		if (! $bool) {
			if (false == gen_pec ( $sum, $table_name, $pid, $pec_order_id, $number )) // 产生随机数，并存入数据库
{
				echo 'error';exit;
				//return false;
			}
		}
		// var_dump($bool);die;
		if (! $fso = fopen ( $gen_path, 'w' )) {
			
			echo '无法打开文件' . $gen_path; // trigger_error
			return false;
		}
		if (! flock ( $fso, LOCK_EX )) { // LOCK_NB,排它型锁定
			echo '无法锁定文件.'; // trigger_error
			
			return false;
		}
		// 数据库查询,返回订单号集
		
		$result = matching_pec_order_id ( $pec_dataPrefix_code, $pec_order_id, $table_name );
		// var_dump($result);die;
		// dump($pid);die;
		$info = getPecInfo ( $sum, $pid, $pec_order_id ); // 获取产品相关信息
		
		if (null != $result) {
			// 一开始写文件说明
			$str = "/*" . $info . "*/" . $separator;
			
			if (! fwrite ( $fso, $str )) {
				
				echo '无法写入文件.'; // trigger_error
				
				return false;
			}
			;
			
			foreach ( $result as $value ) {
				$val = "$separator" . $value . "$separator";
				if (! fwrite ( $fso, $val )) {
					echo '无法写入文件.'; // trigger_error
					
					return false;
				}
				;
				$val = null;
			}
		}
		
		flock ( $fso, LOCK_UN ); // 释放锁定
		fclose ( $fso );
	} else {
		$pec_order = M ( 'pec_order' );
		$pec_order_code = $pec_order->where ( "pec_order_id= $pec_order_id" )->getField ( 'pec_order_code' );
		$file_name = "订单号：$pec_order_code.txt";
		now_download ( $gen_path, $file_name, $pec_order_id );
	}
	// 存在文本则读取文本内容
	
	// 为订单的download_url字段赋值。
	$download_url = "http://pec.weproduct.cn/MST/Home/Download/Pec/$rand.txt";
	// $download_url = "http://pec.weproduct.cn/MST/Home/Download/Pec/$rand.txt";
	$pec_order = M ( 'pec_order' );
	$pec_order->where ( "pec_order_id= $pec_order_id" )->setField ( 'download_url', $download_url );
	$pec_order->where ( "pec_order_id=$pec_order_id" )->setField ( 'state', 2 );
	
	return true;
}
function getPecInfo($sum, $pid, $pec_order_id) {
	// dump($sum);dump($pid);dump($pec_order_id);die;
	$separator = "\r\n";
	$product = M ( 'Product' );
	$name = $product->where ( "pid=$pid" )->getField ( 'product_name' );
	$code = $product->where ( "pid=$pid" )->getField ( 'product_code' );
	$pec_order = M ( 'pec_order' );
	$pec_order_code = $pec_order->where ( "pec_order_id= $pec_order_id" )->getField ( 'pec_order_code' );
	$bid = $pec_order->where ( "pec_order_id= $pec_order_id" )->getField ( 'bid' );
	
	$batch = M ( 'batch' );
	$batch_code = $batch->where ( "bid=$bid" )->getField ( 'batch_code' );
	$info = "产品名称：" . $name . $separator . "产品代码：" . $code . $separator . "批次号：" . $batch_code . $separator . "订单号：" . $pec_order_code;
	return $info;
}
// 产生随机数，并写入数组
function gen_pec($sum, $table_name, $pid, $pec_order_id, $number) {
	session ( 'pec_sum', $sum );
	
	/*if ($sum < 0 || $sum > 500000)
		return false;*/
	$sql = "";
	$uid = get_user_id ();
	
	$data_number = get_date_number (); // 获取日期随机数
	
	$random_number = get_random_number ( $sum, $data_number, $table_name, $number ); // 产生后六位随机数
	                                                                           
	// var_dump($random_number);die();
	$random_number1 = json_encode ( $random_number );
	
	// var_dump($random_number1);die();
	
	$data = array (
			'pec_order_id' => $pec_order_id,
			'pec_dataPrefix_code' => $data_number,
			'pec_random_code' => $random_number1 
	);
	// dump($dataList);die();
	$pec = M ( $table_name );
	
	$return = $pec->add ( $data );
	// dump($return);die();
	
	if (null != $return) {
		// 更新remain_number数据库
		
		$uid = get_user_id ();
		
		$time = date ( "Y-m-d" );
		// dump($time);die;
		$remain_number = M ( 'remain_number' );
		$record_number = M ( 'record_number' );
		$condition ['date'] = $time;
		$condition ['uid'] = $uid;
		if ($number == 8) {
			$condition ['date'] = $time;
			$condition ['uid'] = $uid;
			$result = $remain_number->where ( $condition )->getField ( 'eight' );
			// dump($result);die;
			
			if (1000 == $result) {
				$condition1 ['uid'] = $uid;
				$condition1 ['date'] = $time;
				// dump($condition1);die;
				$data ['eight'] = 1000 - $sum;
				$remain_number->where ( $condition1 )->data ( $data )->save (); // 每天第一次写入数据
				$record_uid = $record_number->where ( "date='$time'" )->getField ( 'uid', true );
				$return_equal = is_equal_uid ( $record_uid, $uid );
				if (null == $return_equal) {
					$data1 ['eight'] = $sum;
					$data1 ['uid'] = $uid;
					$data1 ['date'] = $time;
					// dump($condition1);die;
					$record_number->data ( $data1 )->add (); // 写入位数记录表
				} else {
					$data1 ['eight'] = $sum;
					$condition_record ['uid'] = $uid;
					$condition_record ['date'] = $time;
					// dump($condition1);die;
					$record_number->where ( $condition_record )->data ( $data1 )->save (); // 更新位数记录表
				}
			} else {
				
				$result1 = $record_number->where ( $condition )->getField ( 'eight' );
				// dump($result1);die;
				$remain = 1000 - ($result1 + $sum); // 剩余量
				$data2 ['eight'] = $remain;
				// dump($data2);die;
				$remain_number->where ( $condition )->data ( $data2 )->save (); // 更新剩余量数据
				$temp = $result1 + $sum;
				// dump($temp);die;
				// dump($time);die;
				$record_uid = $record_number->where ( "date='$time'" )->getField ( 'uid', true );
				// dump($record_uid);die;
				$return_equal = is_equal_uid ( $record_uid, $uid );
				// dump($return_equal);die;
				if (null == $return_equal) {
					$result_temp ['uid'] = $uid;
					$result_temp ['date'] = $time;
					$result_temp ['eight'] = $temp;
					$record_number->where ( $condition )->data ( $result_temp )->add (); // 保存位数记录表
				} else {
					$result_temp ['eight'] = $temp;
					$record_number->where ( $condition )->data ( $result_temp )->save (); // 更新位数记录表
				}
			}
		}
		if ($number == 9) {
			$condition ['date'] = $time;
			$condition ['uid'] = $uid;
			$result = $remain_number->where ( $condition )->getField ( 'nine' );
			// dump($result);die;
			
			if (10000 == $result) {
				$condition1 ['uid'] = $uid;
				$condition1 ['date'] = $time;
				// dump($condition1);die;
				$data ['nine'] = 10000 - $sum;
				$remain_number->where ( $condition1 )->data ( $data )->save (); // 每天第一次写入数据
				$record_uid = $record_number->where ( "date='$time'" )->getField ( 'uid', true );
				$return_equal = is_equal_uid ( $record_uid, $uid );
				if (null == $return_equal) {
					$data1 ['nine'] = $sum;
					$data1 ['uid'] = $uid;
					$data1 ['date'] = $time;
					// dump($condition1);die;
					$record_number->data ( $data1 )->add (); // 写入位数记录表
				} else {
					$data1 ['nine'] = $sum;
					$condition_record ['uid'] = $uid;
					$condition_record ['date'] = $time;
					// dump($condition1);die;
					$record_number->where ( $condition_record )->data ( $data1 )->save (); // 更新位数记录表
				}
			} else {
				
				$result1 = $record_number->where ( $condition )->getField ( 'nine' );
				// dump($result1);die;
				$remain = 10000 - ($result1 + $sum); // 剩余量
				$data2 ['nine'] = $remain;
				// dump($data2);die;
				$remain_number->where ( $condition )->data ( $data2 )->save (); // 更新剩余量数据
				$temp = $result1 + $sum;
				// dump($temp);die;
				$record_uid = $record_number->where ( "date='$time'" )->getField ( 'uid', true );
				// dump($record_uid);die;
				$return_equal = is_equal_uid ( $record_uid, $uid );
				// dump($return_equal);die;
				if (null == $return_equal) {
					$result_temp ['uid'] = $uid;
					$result_temp ['date'] = $time;
					$result_temp ['nine'] = $temp;
					$record_number->where ( $condition )->data ( $result_temp )->add (); // 保存位数记录表
				} else {
					$result_temp ['nine'] = $temp;
					$record_number->where ( $condition )->data ( $result_temp )->save (); // 更新位数记录表
				}
			}
		}
		if ($number == 10) {
			$condition ['date'] = $time;
			$condition ['uid'] = $uid;
			$result = $remain_number->where ( $condition )->getField ( 'ten' );
			// dump($result);die;
			
			if (100000 == $result) {
				$condition1 ['uid'] = $uid;
				$condition1 ['date'] = $time;
				// dump($condition1);die;
				$data ['ten'] = 100000 - $sum;
				$remain_number->where ( $condition1 )->data ( $data )->save (); // 每天第一次写入数据
				$record_uid = $record_number->where ( "date='$time'" )->getField ( 'uid', true );
				$return_equal = is_equal_uid ( $record_uid, $uid );
				if (null == $return_equal) {
					$data1 ['ten'] = $sum;
					$data1 ['uid'] = $uid;
					$data1 ['date'] = $time;
					// dump($condition1);die;
					$record_number->data ( $data1 )->add (); // 写入位数记录表
				} else {
					$data1 ['ten'] = $sum;
					$condition_record ['uid'] = $uid;
					$condition_record ['date'] = $time;
					// dump($condition1);die;
					$record_number->where ( $condition_record )->data ( $data1 )->save (); // 更新位数记录表
				}
			} else {
				
				$result1 = $record_number->where ( $condition )->getField ( 'ten' );
				// dump($result1);die;
				$remain = 100000 - ($result1 + $sum); // 剩余量
				$data2 ['ten'] = $remain;
				// dump($data2);die;
				$remain_number->where ( $condition )->data ( $data2 )->save (); // 更新剩余量数据
				$temp = $result1 + $sum;
				// dump($temp);die;
				$record_uid = $record_number->where ( "date='$time'" )->getField ( 'uid', true );
				// dump($record_uid);die;
				$return_equal = is_equal_uid ( $record_uid, $uid );
				// dump($return_equal);die;
				if (null == $return_equal) {
					$result_temp ['uid'] = $uid;
					$result_temp ['date'] = $time;
					$result_temp ['ten'] = $temp;
					$record_number->where ( $condition )->data ( $result_temp )->add (); // 保存位数记录表
				} else {
					$result_temp ['ten'] = $temp;
					$record_number->where ( $condition )->data ( $result_temp )->save (); // 更新位数记录表
				}
			}
		}
		if ($number == 11) {
			$condition ['date'] = $time;
			$condition ['uid'] = $uid;
			$result = $remain_number->where ( $condition )->getField ( 'eleven' );
			// dump($result);die;
			
			if (1000000 == $result) {
				$condition1 ['uid'] = $uid;
				$condition1 ['date'] = $time;
				// dump($condition1);die;
				$data ['eleven'] = 1000000 - $sum;
				$remain_number->where ( $condition1 )->data ( $data )->save (); // 每天第一次写入数据
				$record_uid = $record_number->where ( "date='$time'" )->getField ( 'uid', true );
				$return_equal = is_equal_uid ( $record_uid, $uid );
				if (null == $return_equal) {
					$data1 ['eleven'] = $sum;
					$data1 ['uid'] = $uid;
					$data1 ['date'] = $time;
					// dump($condition1);die;
					$record_number->data ( $data1 )->add (); // 写入位数记录表
				} else {
					$data1 ['eleven'] = $sum;
					$condition_record ['uid'] = $uid;
					$condition_record ['date'] = $time;
					// dump($condition1);die;
					$record_number->where ( $condition_record )->data ( $data1 )->save (); // 更新位数记录表
				}
			} else {
				
				$result1 = $record_number->where ( $condition )->getField ( 'eleven' );
				// dump($result1);die;
				$remain = 1000000 - ($result1 + $sum); // 剩余量
				$data2 ['eleven'] = $remain;
				// dump($data2);die;
				$remain_number->where ( $condition )->data ( $data2 )->save (); // 更新剩余量数据
				$temp = $result1 + $sum;
				// dump($temp);die;
				$record_uid = $record_number->where ( "date='$time'" )->getField ( 'uid', true );
				// dump($record_uid);die;
				$return_equal = is_equal_uid ( $record_uid, $uid );
				// dump($return_equal);die;
				if (null == $return_equal) {
					$result_temp ['uid'] = $uid;
					$result_temp ['date'] = $time;
					$result_temp ['eleven'] = $temp;
					$record_number->where ( $condition )->data ( $result_temp )->add (); // 保存位数记录表
				} else {
					$result_temp ['eleven'] = $temp;
					$record_number->where ( $condition )->data ( $result_temp )->save (); // 更新位数记录表
				}
			}
		}
		if ($number == 12) {
			$condition ['date'] = $time;
			$condition ['uid'] = $uid;
			$result = $remain_number->where ( $condition )->getField ( 'twelve' );
			// dump($result);die;
			
			if (10000000 == $result) {
				$condition1 ['uid'] = $uid;
				$condition1 ['date'] = $time;
				// dump($condition1);die;
				$data ['twelve'] = 10000000 - $sum;
				$remain_number->where ( $condition1 )->data ( $data )->save (); // 每天第一次写入数据
				$record_uid = $record_number->where ( "date='$time'" )->getField ( 'uid', true );
				$return_equal = is_equal_uid ( $record_uid, $uid );
				if (null == $return_equal) {
					$data1 ['twelve'] = $sum;
					$data1 ['uid'] = $uid;
					$data1 ['date'] = $time;
					// dump($condition1);die;
					$record_number->data ( $data1 )->add (); // 写入位数记录表
				} else {
					$data1 ['twelve'] = $sum;
					$condition_record ['uid'] = $uid;
					$condition_record ['date'] = $time;
					// dump($condition1);die;
					$record_number->where ( $condition_record )->data ( $data1 )->save (); // 更新位数记录表
				}
			} else {
				
				$result1 = $record_number->where ( $condition )->getField ( 'twelve' );
				// dump($result1);die;
				$remain = 10000000 - ($result1 + $sum); // 剩余量
				$data2 ['twelve'] = $remain;
				// dump($data2);die;
				$remain_number->where ( $condition )->data ( $data2 )->save (); // 更新剩余量数据
				$temp = $result1 + $sum;
				// dump($temp);die;
				$record_uid = $record_number->where ( "date='$time'" )->getField ( 'uid', true );
				// dump($record_uid);die;
				$return_equal = is_equal_uid ( $record_uid, $uid );
				// dump($return_equal);die;
				if (null == $return_equal) {
					$result_temp ['uid'] = $uid;
					$result_temp ['date'] = $time;
					$result_temp ['twelve'] = $temp;
					$record_number->where ( $condition )->data ( $result_temp )->add (); // 保存位数记录表
				} else {
					$result_temp ['twelve'] = $temp;
					$record_number->where ( $condition )->data ( $result_temp )->save (); // 更新位数记录表
				}
			}
		}
		return true;
	}
}

// 输出到服务器文件
function pec_write($pec_order_id) {
	$gen_prefix = $_SERVER ['DOCUMENT_ROOT'] . "/MST/Home/Download/Pec/";
	$postfix = '.txt';
	$separator = '\r\n';
	$rand = sha1 ( sha1 ( sha1 ( $pec_order_id ) ) );
	$gen_path = $gen_prefix . $rand . $postfix;
	
	if (file_exists ( $gen_path )) {
		echo '已存在该订单';
		return false;
	}
	if (! $fso = fopen ( $gen_path, 'w' )) {
		
		echo '无法打开文件' . $gen_path; // trigger_error
		return false;
	}
	if (! flock ( $fso, LOCK_EX )) { // LOCK_NB,排它型锁定
		echo '无法锁定文件.'; // trigger_error
		return false;
	}
	
	$pec = M ( 'pec' );
	
	// 数据库查询
	try {
		$result = $pec->where ( "pec_order_id = $pec_order_id" )->getField ( 'pec_id', true );
	} catch ( Exception $ex ) {
		E ( 'order_id数据库查询出错' . $ex );
	}
	
	$temp = "";
	foreach ( $result as $value ) {
		$temp = $temp . "\n" . $value;
	}
	if (! fwrite ( $fso, $temp )) {
		echo '无法写入文件.'; // trigger_error
		return false;
	}
	;
	flock ( $fso, LOCK_UN ); // 释放锁定
	fclose ( $fso );
	$download_url = "http://dianma.weproduct.cn/MST/Home/Download/Pec/$rand.txt";
	$pec_order = M ( 'pec_order' );
	$pec_order->where ( "pec_order_id= $pec_order_id" )->setField ( 'download_url', $download_url );
	header ( "Expires: " . gmdate ( "D, d M Y H:i:s", time () + 3600000 ) . " GMT" );
	header ( 'Content-type:application/octet-stream', true );
	header ( "Content-type: txt" );
	$filename = '文档名称';
	header ( "Content-Disposition: attachment; filename=" . $filename );
	header ( "Content-type: text/plain" );
	exit ( $temp );
	return true;
}

// 效验密码
function checkPassowrd($login, $pw) {
	$_login = $login;
	$password = sha1 ( sha1 ( $pw ) );
	
	$company_user = M ( 'company_user' );
	$result = $company_user->where ( "login='$_login' and password='$password'" )->find ();
	if ($result != null) {
		return true;
	}
	
	return false;
}

// 重写密码
function renewPassword($login, $newpw) {
	$password = sha1 ( sha1 ( $newpw ) );
	$data ['password'] = $password;
	$company_user = M ( 'company_user' );
	$result = $company_user->where ( "login='$login'" )->save ( $data );
	if ($result !== false) {
		return true;
	}
	return false;
}
function DeleteHtml($str) {
	$str = trim ( $str ); // 清除字符串两边的空格
	$str = strip_tags ( $str, "" ); // 利用php自带的函数清除html格式
	return $str; // 返回字符串
}

// 判断数据库中是否已存在$uid用户的表
function is_exist_table($table_name) {
	$Model = new \Think\Model ();
	$result = $Model->execute ( "SHOW TABLES LIKE '$table_name' " );
	// dump($result);die;
	if ($result == 1) {
		return true;
	} else {
		return false;
	}
}
// 动态创建表
function dynamic_create_table($uid) {
	$table_name = "__PREFIX__pec" . $uid;
	$data ['pec_name'] = "pec" . $uid;
	;
	
	// create table
	$sql = "create table $table_name (pec_order_id  int not null auto_increment  primary  key,pec_dataPrefix_code int unsigned not null,pec_random_code longtext not null)";
	// dump($sql);
	$Model = new \Think\Model ();
	$result = $Model->execute ( $sql );
	// dump($result);die;
	if ($result == 0) {
		// 将动态表名存入company_user表中
		$index = M ( 'company_user' );
		
		$condition ['login'] = get_user_name ();
		
		$return = $index->where ( $condition )->save ( $data );
		// dump($return);die;
		if ($return == 1) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
		// echo "创建数据表出错，错误号：".mysql_errno()." 错误原因：".mysql_error();
	}
	
	// end
	mysql_close ();
}

// 随机数产生算法
function get_random_number($sum, $data_number, $table_name, $number) {
	$temp = 0;
	$data_number = $data_number;
	$table_name = $table_name;
	$return = array ();
	// range 是将1到2000000 列成一个数组
	if ($number == 8) {
		
		$numbers = range ( 1, 10000 );
	}
	if ($number == 9) {
		$numbers = range ( 1, 100000 );
	}
	if ($number == 10) {
		$numbers = range ( 1, 1000000 );
	}
	if ($number == 11) {
		$numbers = range ( 1, 2000000 );
	}
	if ($number == 12) {
		$numbers = range ( 1, 2000000 );
	}
	// shuffle 将数组顺序随即打乱
	shuffle ( $numbers );
	// array_slice 取该数组中的某一段
	$no = $sum; // 产生多少个随机数
	$result = array_slice ( $numbers, 0, $no );
	if ($number == 8) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%04d', $result [$i] );
			$return [$i] = $a;
			// echo $a."</br>";
		}
	}
	if ($number == 9) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%05d', $result [$i] );
			$return [$i] = $a;
			// echo $a."</br>";
		}
	}
	if ($number == 10) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%06d', $result [$i] );
			$return [$i] = $a;
			// echo $a."</br>";
		}
	}
	if ($number == 11) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%07d', $result [$i] );
			$return [$i] = $a;
			// echo $a."</br>";
		}
	}
	if ($number == 12) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%08d', $result [$i] );
			$return [$i] = $a;
			// echo $a."</br>";
		}
	}
	// var_dump($return);die();
	// 查重
	$return_only = remove_repeat_number_half ( $return, $data_number, $table_name, $number );
	
	if (null == $return_only) {
		return $return;
	} else {
		return $return_only;
	}
}

// 随机数产生算法_查重专用,只产生一个6位随机数
function get_random_number_repeat($sum, $number1) {
	$return = array ();
	// range 是将1到1000000 列成一个数组
	if ($number1 == 8) {
		
		$numbers = range ( 1, 10000 );
	}
	if ($number1 == 9) {
		$numbers = range ( 1, 100000 );
	}
	if ($number1 == 10) {
		$numbers = range ( 1, 1000000 );
	}
	if ($number1 == 11) {
		$numbers = range ( 1, 2000000 );
	}
	if ($number1 == 12) {
		$numbers = range ( 1, 2000000 );
	}
	// shuffle 将数组顺序随即打乱
	shuffle ( $numbers );
	// array_slice 取该数组中的某一段
	$no = $sum; // 产生多少个随机数
	$result = array_slice ( $numbers, 0, $no );
	if ($number1 == 8) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%04d', $result [$i] );
			
			// echo $a."</br>";
		}
	}
	if ($number1 == 9) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%05d', $result [$i] );
			
			// echo $a."</br>";
		}
	}
	if ($number1 == 10) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%06d', $result [$i] );
			
			// echo $a."</br>";
		}
	}
	if ($number1 == 11) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%07d', $result [$i] );
			
			// echo $a."</br>";
		}
	}
	if ($number1 == 12) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%08d', $result [$i] );
			
			// echo $a."</br>";
		}
	}
	// var_dump($return);die();
	return $a;
	// dump($a);die;
}
// 随机数产生算法_查重专用,只产生一个6位随机数
function get_random_number_repeat_n($sum, $number) {
	$return = array ();
	// range 是将1到1000000 列成一个数组
	if ($number == 8) {
		
		$numbers = range ( 1, 10000 );
	}
	if ($number == 9) {
		$numbers = range ( 1, 100000 );
	}
	if ($number == 10) {
		$numbers = range ( 1, 1000000 );
	}
	if ($number == 11) {
		$numbers = range ( 1, 2000000 );
	}
	if ($number == 12) {
		$numbers = range ( 1, 2000000 );
	}
	// shuffle 将数组顺序随即打乱
	shuffle ( $numbers );
	// array_slice 取该数组中的某一段
	$no = $sum; // 产生多少个随机数
	$result = array_slice ( $numbers, 0, $no );
	if ($number == 8) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%04d', $result [$i] );
			$result [$i] = $a;
			// echo $a."</br>";
		}
	}
	if ($number == 9) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%05d', $result [$i] );
			$result [$i] = $a;
			// echo $a."</br>";
		}
	}
	if ($number == 10) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%06d', $result [$i] );
			$result [$i] = $a;
			// echo $a."</br>";
		}
	}
	if ($number == 11) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%07d', $result [$i] );
			$result [$i] = $a;
			// echo $a."</br>";
		}
	}
	if ($number == 12) {
		for($i = 0; $i < $no; $i ++) {
			// echo $result[$i]."<br>";
			$a = sprintf ( '%08d', $result [$i] );
			$result [$i] = $a;
			// echo $a."</br>";
		}
	}
	// var_dump($return);die();
	return $result;
}
// 获取年份日数
function get_date_number() {
	$date = date ( 'Y-m-d', time () );
	// dump($date);die;
	$year = (( int ) substr ( $date, 2, 4 ));
	// dump($year);die;
	$month = (( int ) substr ( $date, 5, 2 ));
	$month = sprintf ( "%02d", $month );
	
	// dump($month);die;
	$day = (( int ) substr ( $date, 8, 2 ));
	$day = sprintf ( "%02d", $day );
	// dump($day);die;
	$data = $year . $month . $day;
	// dump($data);die;
	return $data;
}

// 根据时间前缀取出的订单id，判断时候存在当前id
function is_exist_pec_order_id($is_exist_pec_order) {
	foreach ( $is_exist_pec_order as $val ) {
		if ($pec_order_id != $val) {
			return false;
		} else {
			return true;
			break;
		}
	}
}
// 更具时间前缀，查询pec_order_id 并匹配是否已存在此pec_order_id,如果存在返回相应的pec_random_code
function matching_pec_order_id($pec_dataPrefix_code, $pec_order_id, $table_name) {
	
	// var_dump($pec_dataPrefix_code);
	// var_dump($is_exist_pec_order);
	// var_dump($table_name);die();
	$sum_random = array ();
	$pec = M ( $table_name );
	try {
		$result = $pec->where ( "pec_dataPrefix_code = $pec_dataPrefix_code" )->getField ( 'pec_order_id', true );
		// dump($result);die();
		foreach ( $result as $result0 ) {
			if ($pec_order_id == $result0) {
				$result_json = $pec->where ( "pec_order_id = $result0" )->getField ( 'pec_random_code', true );
				// var_dump($result_json);die();
				$result1 = json_decode ( $result_json [0], true );
				// var_dump($result1);die();
				// dump($pec_dataPrefix_code);die;
				$pec_dataPrefix_code_to_four = pec_dataPrefix_code_to_four ( $pec_dataPrefix_code );
				// dump(strlen($pec_dataPrefix_code_to_four));die;
				// 1022 011548397 ,插位
				foreach ( $result1 as $val ) {
					$j = 1;
					for($i = 0 ;$i < strlen($pec_dataPrefix_code_to_four);$i++) {
						$char = substr($pec_dataPrefix_code_to_four,$i,1);
						$val = str_insert( $val, $j, $char);
						$j += 2;
					}
					// $sum_random[] = $pec_dataPrefix_code_to_four.$val;
					$sum_random[] = $val;
				}
			}
		}
		// var_dump($sum_random);die();
		return $sum_random;
		// dump($result);die();
	} catch ( Exception $ex ) {
		E ( 'order_id数据库查询出错' . $ex );
		die ();
	}
}
// 在指定位置插入
function str_insert($str, $i, $substr) {
	// 指定插入位置前的字符串
	$startstr = "";
	for($j = 0; $j < $i; $j ++) {
		$startstr .= $str [$j];
	}
	
	// 指定插入位置后的字符串
	$laststr = "";
	for($j = $i; $j < strlen ( $str ); $j ++) {
		$laststr .= $str [$j];
	}
	
	// 将插入位置前，要插入的，插入位置后三个字符串拼接起来
	$str = $startstr . $substr . $laststr;
	
	// 返回结果
	return $str;
}
function now_download($gen_path, $file_name, $pec_order_id) {
	
	// 首先要判断给定的文件存在与否
	$file_path = $gen_path;
	if (! file_exists ( $file_path )) {
		echo "没有该文件文件";
		return;
	}
	$fp = fopen ( $file_path, "r" );
	$file_size = filesize ( $file_path );
	// dump($file_path);die;
	// 下载文件需要用到的头
	Header ( "Content-type: application/octet-stream" );
	Header ( "Accept-Ranges: bytes" );
	Header ( "Accept-Length:" . $file_size );
	
	Header ( "Content-Disposition: attachment; filename=" . $file_name );
	$buffer = 2048;
	$file_count = 0;
	// 向浏览器返回数据
	while ( ! feof ( $fp ) && $file_count < $file_size ) {
		$file_con = fread ( $fp, $buffer );
		$file_count += $buffer;
		echo $file_con;
	}
	fclose ( $fp );
	// 更新下载状态
	$uid = get_user_id ();
	$data ['isdownload'] = 1;
	if (null == M ( 'pec_order' )->where ( "cid='$uid' and pec_order_id='$pec_order_id'" )->getField ( 'isdownload' )) {
		M ( 'pec_order' )->where ( "cid='$uid' and pec_order_id='$pec_order_id'" )->save ( $data );
	}
	exit ();
}
/*
 * 电码微信查询，根据用户输入的电码，查询相应的数据
 */
function get_product_message_byPecOrder($pec_order_id, $pec_id) { // 注意:此处的$pec_random_code就是前面传进来的$pec_id
	$separator = "\r\n";
	
	$mst_pec_order = M ( 'pec_order' );
	$condition ['pec_order_id'] = $pec_order_id;
	$bid = $mst_pec_order->where ( $condition )->getField ( 'bid' );
	
	// dump($data);die;
	// 获取批次相关信息
	$batch = M ( 'batch' );
	$condition1 ['bid'] = $bid;
	$pid = $batch->where ( $condition1 )->getField ( 'pid' );
	if (empty ( $pid )) { // 自定义批次模版中
		$pid = M ( 'template_area' )->where ( "bid='$bid'" )->getField ( 'pid' );
		if (empty ( $pid )) {
			return false;
		}
	}
	// dump($pid);die;
	// 获取产品相关信息
	$product = M ( 'product' );
	$condition2 ['pid'] = $pid;
	$datas = $product->field ( 'product_name,cid,txt_content,html_content,pic_url' )->where ( $condition2 )->select ();
	// dump($datas);die;
	if (null == $datas) {
		return false;
	}
	foreach ( $datas as $val ) {
		$cid = $val ['cid'];
	}
	// exit($cid);
	// 查询记录次数
	$pec_query_record = M ( 'pec_query_record' );
	// exit($pec_id);die;
	$condition_record ['pec_id'] = $pec_id;
	// exit($condition_record['pec_random_code']);die;
	$count = $pec_query_record->where ( $condition_record )->getField ( 'count' );
	// exit($count);die;
	if (null == $count) {
		$anti_result = "恭喜，您所查询的商品为正品！" . "$separator";
		$count = 0;
		// 第一次查询,保存数据
		$data ['pec_id'] = $pec_id;
		
		$data ['first_time'] = date ( 'Y-m-d H:i:s' );
		$data ['last_time'] = date ( 'Y-m-d H:i:s' );
		$first_time = date ( 'Y-m-d H:i:s' );
		$data ['count'] = $count + 1;
		$pec_query_record->data ( $data )->add (); // 保存
		                                       // exit($pec_id);die;
		$query_strr = "$anti_result" . $query_str . "第1次查询时间:" . $first_time . $separator;
		foreach ( $datas as &$val ) {
			$val ['txt_content'] = $query_strr;
		}
	} else {
		
		if ($count < 3) {
			$anti_result = "恭喜，您所查询的商品为正品！" . "$separator";
			$and_so_on = "$separator" . "......" . "$separator";
			
			$counts = $count + 1;
			$query_str = "查询总次数:" . "$counts" . "$separator";
			$first_time = $pec_query_record->where ( $condition_record )->getField ( 'first_time' );
			$last_time = $pec_query_record->where ( $condition_record )->getField ( 'last_time' );
			
			$end_time = date ( 'Y-m-d H:i:s' );
			$data ['last_time'] = date ( 'Y-m-d H:i:s' );
			$data ['count'] = $count + 1;
			$pec_query_record->where ( $condition_record )->save ( $data ); // 更新
			if ($count == 1) {
				$query_strr = "$anti_result" . $query_str . "第1次查询时间:" . $first_time . $separator . "第" . "$counts" . "次查询时间:" . $end_time . $separator;
				foreach ( $datas as &$val ) {
					$val ['txt_content'] = $query_strr;
				}
			} else {
				
				$query_strr = "$anti_result" . $query_str . "第1次查询时间:" . $first_time . $separator . $and_so_on . "第" . "$count" . "次查询时间:" . $last_time . $separator . "第" . "$counts" . "次查询时间:" . $end_time . $separator;
				
				foreach ( $datas as &$val ) {
					$val ['txt_content'] = $query_strr;
				}
			}
			$count = 0;
		} else {
			
			$counts = $count + 1;
			$query_str = "您所查询的产品身份码已查询了:" . "$counts" . "次,谨防假冒！" . "$separator";
			$and_so_on = "$separator" . "......" . "$separator";
			$first_time = $pec_query_record->where ( $condition_record )->getField ( 'first_time' );
			$last_time = $pec_query_record->where ( $condition_record )->getField ( 'last_time' );
			
			$end_time = date ( 'Y-m-d H:i:s' );
			$data ['last_time'] = date ( 'Y-m-d H:i:s' );
			$data ['count'] = $count + 1;
			$pec_query_record->where ( $condition_record )->save ( $data ); // 更新
			$query_strr = "$anti_result" . $query_str . "第1次查询时间:" . $first_time . $separator . $and_so_on . "第" . "$count" . "次查询时间:" . $last_time . $separator . "第" . "$counts" . "次查询时间:" . $end_time . $separator;
			foreach ( $datas as &$val ) {
				$val ['txt_content'] = $query_strr;
			}
			$count = 0;
		}
	}
	
	// var_dump($cid);die;
	// 获取相关公司信息
	$edit_company = M ( 'edit_company' );
	$condition3 ['cid'] = $cid;
	
	$company_datas = $edit_company->field ( 'company_name,specific_content,photo_url' )->where ( $condition3 )->select ();
	// var_dump($company_datas);die;
	/*
	 * if(null == $company_datas){
	 * return false;
	 * }
	 */
	$returns ['product'] = $datas; // 产品信息
	$returns ['company_datas'] = $company_datas; // 公司信息
	$returns ['batch_message'] = $bid; // 批次信息，目前就只有批次号
	$id = session ( 'id' );
	$datass ['bid'] = $bid;
	$datass ['pid'] = $pid;
	$pec_order_code = M ( 'pec_order' )->where ( "pec_order_id = '$pec_order_id'" )->getField ( 'pec_order_code' );
	$datass ['pec_order_code'] = $pec_order_code;
	M ( 'consumers' )->where ( "id='$id'" )->save ( $datass );
	return $returns;
}

/*
 * 微信自定义菜单开发,功能方法
 */
/**
 * 获取ACCESS TOKEN
 */
function getAccessToken($AppId, $AppSecret) {
	$cfg ['ssl'] = true;
	$result = curlOpen ( "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$AppId&secret=$AppSecret", $cfg );
	return json_decode ( $result, true );
}

/**
 * 添加菜单
 */
function createMenu($ACCESS_TOKEN, $data) {
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $ACCESS_TOKEN );
	curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "POST" );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
	curl_setopt ( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)' );
	curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 );
	curl_setopt ( $ch, CURLOPT_AUTOREFERER, 1 );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	$tmpInfo = curl_exec ( $ch );
	if (curl_errno ( $ch )) {
		return curl_error ( $ch );
	}
	curl_close ( $ch );
	return $tmpInfo;
}

/*
 * 微信自定义菜单开发,工具方法
 */
function curlOpen($url, $cfg) {
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $ch, CURLOPT_HEADER, 0 );
	if ($cfg ['ssl']) {
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );
	}
	$result = curl_exec ( $ch );
	curl_close ( $ch );
	return $result;
}

/*
 * 电码网页查询，根据用户输入的电码，查询相应的数据
 */
function get_product_message_byPecOrder_byPage($pec_order_id, $pec_random_code) {
	$separator = "\r\n";
	
	$mst_pec_order = M ( 'pec_order' );
	$condition ['pec_order_id'] = $pec_order_id;
	$bid = $mst_pec_order->where ( $condition )->getField ( 'bid' );
	// dump($bid);die;
	// 获取批次相关信息
	$batch = M ( 'batch' );
	$condition1 ['bid'] = $bid;
	$pid = $batch->where ( $condition1 )->getField ( 'pid' );
	if (null == $pid) {
		$pid = M ( 'template_area' )->where ( "bid='$bid'" )->getField ( 'pid' );
		if (empty ( $pid )) {
			return false;
		}
	}
	// dump($pid);die;
	// 获取产品相关信息
	$product = M ( 'product' );
	$condition2 ['pid'] = $pid;
	$datas = $product->field ( 'product_name,cid,txt_content,html_content,product_code' )->where ( $condition2 )->select ();
	// var_dump($datas);die;
	if (null == $datas) {
		return false;
	}
	foreach ( $datas as $val ) {
		$cid = $val ['cid'];
	}
	// var_dump($cid);die;
	// 获取相关公司信息
	$edit_company = M ( 'edit_company' );
	$condition3 ['cid'] = $cid;
	$company_datas = $edit_company->field ( 'company_name,company_content,photo_url' )->where ( $condition3 )->select ();
	// var_dump($company_datas);die;
	/*
	 * if(null == $company_datas){
	 * return false;
	 * }
	 */
	$returns ['product'] = $datas; // 产品信息
	$returns ['company_datas'] = $company_datas; // 公司信息
	$returns ['batch_message'] = $bid; // 批次信息，目前就只有批次号
	
	return $returns;
	
	// var_dump($company_datas);die;
}
// 老啃洮砚
function weixin_menu_laokentaoyan($APPID, $APPSECRET, $tokenarr) {
	$data = ' {
     "button":[
  
      {
           "name":"时讯",
           "sub_button":[
    		{
               "name":"互动专区",
		  		 "type":"view",
          		 "url":"http://m.wsq.qq.com/263350729"
            },
			{
               "type":"view",
               "name":"公司资质",
               "url":"http://base.weixinhai.com.cn/3G/index/detail/14695/114126"
            },
			{
               "type":"view",
               "name":"公司简介",
               "url":"http://base.weixinhai.com.cn/3G/index/detail/14695/114125"
            }
    				]
       },
	   {
           "name":"微商城",
           "type":"view",
		   "url":"http://base.weixinhai.com.cn/3G/microMart/index?wxid=14695"
       },
	   {
           "name":"互动专区",
		   "type":"view",
           "url":"http://m.wsq.qq.com/263350729"
       }
]
 }';
	return createMenu ( $tokenarr ['access_token'], $data );
}
//数据样列
$data3 = '{
     "button":[
   
     {
           "name":"品牌故事",
           "sub_button":[
   
    		{
               "type":"click",
               "name":"企业荣誉",
               "key":"a"
            },
    		{
               "type":"click",
               "name":"变通研发故事",
               "key":"b"
            },
    		{
               "type":"click",
               "name":"蜂胶配紫苏",
               "key":"bb"
            },
    		{
               "type":"click",
               "name":"补钙要讲究",
               "key":"bbb"
            },
    		{
               "type":"click",
               "name":"联系我们",
               "key":"bbbb"
            }
    				]
       },
      {
           "name":"看剧赢大奖",
           "type":"click",
		   "key":"c"
       },

       {
           "name":"验证真伪",
           "type":"click",
		   "key":"cc"
       }
]
 }';
// 御之林
function weixin_menu_yuzhilin($APPID, $APPSECRET, $tokenarr) {
	$data3 = '{
     "button":[
    	
     {
           "name":"品牌故事",
           "sub_button":[
    	
    		{
               "type":"click",
               "name":"企业荣誉",
               "key":"yzl_qyry"
            },
    		{
               "type":"click",
               "name":"变通研发故事",
               "key":"yzl_btyfgs"
            },
    		{
               "type":"click",
               "name":"蜂胶配紫苏",
               "key":"yzl_fjpzs"
            },
    		{
               "type":"click",
               "name":"补钙要讲究",
               "key":"yzl_bgyjj"
            },
    		{
               "type":"click",
               "name":"联系我们",
               "key":"yzl_lxwm"
            }
    				]
       },
      {
           "name":"微商城",
           "type":"view",
		   "url":"http://mobile.fanxiangds.com/shop_default/index?shop_id=xpJdX9eXy8%2FJX3A"
       },
	
       {
           "name":"验证真伪",
           "type":"click",
		   "key":"yzl_yzzw"
       }
]
 }';
	return createMenu ( $tokenarr ['access_token'], $data3 );
}
//甘肃洮砚协会
function weixin_menu_GSTYXH($APPID, $APPSECRET, $tokenarr){
	$data = '{
     "button":[
   
     {
           "name":"洮砚协会",
           "sub_button":[
   
    		{
               "type":"click",
               "name":"行业动态",
               "key":"hydt"
            },
    		{
               "type":"click",
               "name":"协会章程",
               "key":"xhzc"
            },
    		{
               "type":"click",
               "name":"协会简介",
               "key":"xhjj"
            }
    				]
       },
       {
           "name":"会员服务",
           "sub_button":[
   
    		{
               "type":"click",
               "name":"入会须知",
               "key":"rhxz"
            }
    				]
       },
	
       {
           "name":"联系我们",
           "type":"click",
		   "key":"gsty_lxwm"
       }
]
 }';
	return createMenu ( $tokenarr ['access_token'], $data );
}

// 菲普迪斯
function weixin_menu_fpds($APPID, $APPSECRET, $tokenarr) {
	$data2 = '{
     "button":[
  
     {
           "name":"商品信息云",
		   "type":"view",
           "url":"http://www.cdfpds.com/mobile/product1/index.html"
       },
	  {
           "name":"珍彩码",
		   "type":"view",
           "url":"http://www.cdfpds.com/mobile/product2/index.html"
       },
    				{
           "name":"微防伪",
           "sub_button":[
    		{
               "type":"view",
               "name":"首页",
               "url":"http://www.cdfpds.com/mobile/product3/index.html"
            },
    		{
               "type":"click",
               "name":"点击对话",
               "key":"lianxikefu"
            }
    				]
       }
]
 }';
	
	return createMenu ( $tokenarr ['access_token'], $data2 );
}

/*
 * 电码微信查询，根据用户输入的电码，查询相应的数据,点击后微信网页显示
 */
function get_product_message_byPecOrder_topage($pec_order_id, $pec_random_code, $pec_id) {
	$separator = "<br>";
	
	$mst_pec_order = M ( 'pec_order' );
	$condition ['pec_order_id'] = $pec_order_id;
	$bid = $mst_pec_order->where ( $condition )->getField ( 'bid' );
	
	// dump($data);die;
	// 获取批次相关信息
	$batch = M ( 'batch' );
	$condition1 ['bid'] = $bid;
	$pid = $batch->where ( $condition1 )->getField ( 'pid' );
	$startpoint = $batch->where ( $condition1 )->getField ( 'startpoint' ); // 问题
	$sellpoint = $batch->where ( $condition1 )->getField ( 'sellpoint' );
	$retail_price = $batch->where ( $condition1 )->getField ( 'retail_price' );
	$batch_message ['bid'] = $bid;
	$batch_message ['startpoint'] = $startpoint;
	$batch_message ['sellpoint'] = $sellpoint;
	$batch_message ['retail_price'] = $retail_price;
	$str_batch_message = " ";
	$str_batch_message = "批次号:" . $batch_message ['bid'] . $separator . "生产地:" . $batch_message ['startpoint'] . $separator . "销售地:" . $batch_message ['sellpoint'] . $separator . "零售价:" . $batch_message ['retail_price'] . $separator;
	// dump($str_batch_message);die;
	// 获取产品相关信息
	$product = M ( 'product' );
	$condition2 ['pid'] = $pid;
	$datas = $product->field ( 'product_name,cid,txt_content,html_content,company_html_content' )->where ( $condition2 )->select ();
	// var_dump($datas);die;
	if (null == $datas) {
		return false;
	}
	foreach ( $datas as $val ) {
		$cid = $val ['cid'];
		$product_name = $val ['product_name'];
	}
	
	// exit($cid);
	// 查询记录次数
	$pec_query_record = M ( 'pec_query_record' );
	$condition_record ['pec_id'] = $pec_id;
	$count = $pec_query_record->where ( $condition_record )->getField ( 'count' );
	if (null == $count) {
		$anti_result = "恭喜，您所查询的商品为正品！" . "$separator";
		$count = 0;
		// 第一次查询,保存数据
		$data ['pec_id'] = $pec_id;
		
		$data ['first_time'] = date ( 'Y-m-d H:i:s' );
		$data ['last_time'] = date ( 'Y-m-d H:i:s' );
		$first_time = date ( 'Y-m-d H:i:s' );
		$data ['count'] = $count + 1;
		$pec_query_record->data ( $data )->add (); // 保存
		$query_strr = "$anti_result" . $query_str . "第1次查询时间:" . $first_time . $separator;
		foreach ( $datas as &$val ) {
			$val ['txt_content'] = $query_strr;
		}
	} else {
		
		if ($count < 4) {
			$anti_result = "查询成功,您所查询的商品是：" . $product_name . "$separator" . "您所查询的产品身份码是:" . $pec_id . "$separator";
			$and_so_on = "$separator" . "......" . "$separator";
			
			$counts = $count + 1;
			$query_str = "查询总次数:" . "$count" . "次" . "$separator";
			$first_time = $pec_query_record->where ( $condition_record )->getField ( 'first_time' );
			$last_time = $pec_query_record->where ( $condition_record )->getField ( 'last_time' );
			
			$end_time = date ( 'Y-m-d H:i:s' );
			$data ['last_time'] = date ( 'Y-m-d H:i:s' );
			$data ['count'] = $count + 1;
			// $pec_query_record->where($condition_record)->save($data);//更新
			if ($count == 1) {
				$query_strr = "$anti_result" . $query_str . "第1次查询时间:" . $first_time . $separator;
				foreach ( $datas as &$val ) {
					$val ['txt_content'] = $query_strr;
				}
			} else {
				
				$query_strr = "$anti_result" . $query_str . "第1次查询时间:" . $first_time . $separator . "第" . "$count" . "次查询时间:" . $last_time;
				
				foreach ( $datas as &$val ) {
					$val ['txt_content'] = $query_strr;
				}
			}
			$count = 0;
		} else {
			
			$counts = $count - 1;
			$query_str = "该产品身份码已查询了:" . "$count" . "次,谨防假冒！" . "$separator";
			$string = "查询成功，您所查询的产品是：" . $product_name . "$separator" . "您所查询的产品身份码是:" . $pec_id . "$separator" . $query_str;
			$and_so_on = "$separator" . "......" . "$separator";
			$first_time = $pec_query_record->where ( $condition_record )->getField ( 'first_time' );
			$last_time = $pec_query_record->where ( $condition_record )->getField ( 'last_time' );
			
			$end_time = date ( 'Y-m-d H:i:s' );
			$data ['last_time'] = date ( 'Y-m-d H:i:s' );
			$data ['count'] = $count + 1;
			// $pec_query_record->where($condition_record)->save($data);//更新
			$query_strr = "$string" . $separator . "第1次查询时间:" . $first_time . $separator . $and_so_on . "第" . "$counts" . "次查询时间:" . $last_time . $separator . "第" . "$count" . "次查询时间:" . $end_time . $separator;
			foreach ( $datas as &$val ) {
				$val ['txt_content'] = $query_strr;
			}
			$count = 0;
		}
	}
	
	// var_dump($cid);die;
	// 获取相关公司信息
	$edit_company = M ( 'edit_company' );
	$condition3 ['cid'] = $cid;
	
	$company_datas = $edit_company->field ( 'company_name,specific_content,photo_url' )->where ( $condition3 )->select ();
	// var_dump($company_datas);die;
	/*
	 * if(null == $company_datas){
	 * return false;
	 * }
	 */
	
	$returns ['product'] = $datas; // 产品信息
	
	$returns ['company_datas'] = $company_datas; // 公司信息
	
	$returns ['batch_message'] = $str_batch_message; // 批次信息，目前就只有批次号
	
	return $returns;
}

/*
 * 电码微信查询，根据用户输入的电码，查询相应的数据,点击后微信网页显示,专为老坑洮砚设计
 */
function get_product_message_byPecOrder_topage_laokengtaoyan($pec_order_id, $pec_random_code) {
	$separator = "<br>";
	
	$mst_pec_order = M ( 'pec_order' );
	$condition ['pec_order_id'] = $pec_order_id;
	$bid = $mst_pec_order->where ( $condition )->getField ( 'bid' );
	
	// dump($data);die;
	// 获取批次相关信息
	$batch = M ( 'batch' );
	$condition1 ['bid'] = $bid;
	$pid = $batch->where ( $condition1 )->getField ( 'pid' );
	$startpoint = $batch->where ( $condition1 )->getField ( 'startpoint' ); // 问题
	$sellpoint = $batch->where ( $condition1 )->getField ( 'sellpoint' );
	$retail_price = $batch->where ( $condition1 )->getField ( 'retail_price' );
	$batch_message ['bid'] = $bid;
	$batch_message ['startpoint'] = $startpoint;
	$batch_message ['sellpoint'] = $sellpoint;
	$batch_message ['retail_price'] = $retail_price;
	$str_batch_message = " ";
	$str_batch_message = "【批次号】" . "G150323" . $batch_message ['bid'] . $separator . "【生产地】" . $batch_message ['startpoint'] . $separator . "【销售地】" . $batch_message ['sellpoint'] . $separator . "【零售价】" . $batch_message ['retail_price'] . $separator;
	// dump($str_batch_message);die;
	// 获取产品相关信息
	$product = M ( 'product' );
	$condition2 ['pid'] = $pid;
	$datas = $product->field ( 'product_name,cid,txt_content,html_content,company_html_content' )->where ( $condition2 )->select ();
	// var_dump($datas);die;
	if (null == $datas) {
		return false;
	}
	foreach ( $datas as $val ) {
		$cid = $val ['cid'];
	}
	// exit($cid);
	foreach ( $datas as $val ) {
		$product_name = $val ['product_name'];
	}
	
	$anti_result = "您好，您所查询的商品是" . $product_name . "!" . "$separator";
	foreach ( $datas as &$val ) {
		$val ['txt_content'] = $anti_result;
	}
	
	// var_dump($cid);die;
	// 获取相关公司信息
	$edit_company = M ( 'edit_company' );
	$condition3 ['cid'] = $cid;
	
	$company_datas = $edit_company->field ( 'company_name,specific_content,photo_url' )->where ( $condition3 )->select ();
	// var_dump($company_datas);die;
	/*
	 * if(null == $company_datas){
	 * return false;
	 * }
	 */
	
	$returns ['product'] = $datas; // 产品信息
	
	$returns ['company_datas'] = $company_datas; // 公司信息
	
	$returns ['batch_message'] = $str_batch_message; // 批次信息，目前就只有批次号
	
	return $returns;
}

/*
 * 电码微信查询，根据用户输入的电码，查询相应的数据,案例特殊需求,老坑洮砚
 */
function get_product_message_byPecOrder_special($pec_order_id, $pec_random_code) {
	$separator = "\r\n";
	
	$mst_pec_order = M ( 'pec_order' );
	$condition ['pec_order_id'] = $pec_order_id;
	$bid = $mst_pec_order->where ( $condition )->getField ( 'bid' );
	
	// dump($data);die;
	// 获取批次相关信息
	$batch = M ( 'batch' );
	$condition1 ['bid'] = $bid;
	$pid = $batch->where ( $condition1 )->getField ( 'pid' );
	
	// dump($pid);die;
	// 获取产品相关信息
	$product = M ( 'product' );
	$condition2 ['pid'] = $pid;
	$datas = $product->field ( 'product_name,cid,txt_content,html_content,pic_url' )->where ( $condition2 )->select ();
	// var_dump($datas);die;
	if (null == $datas) {
		return false;
	}
	foreach ( $datas as $val ) {
		$cid = $val ['cid'];
	}
	// exit($cid);
	foreach ( $datas as $val ) {
		$product_name = $val ['product_name'];
	}
	
	$anti_result = "您好，您所查询的商品是" . $product_name . "!" . "$separator" . "鉴别真伪请点击入内，比对产品实物与高清细图中的节特征是否一致!" . "$separator";
	foreach ( $datas as &$val ) {
		$val ['txt_content'] = $anti_result;
	}
	
	// var_dump($cid);die;
	// 获取相关公司信息
	$edit_company = M ( 'edit_company' );
	$condition3 ['cid'] = $cid;
	
	$company_datas = $edit_company->field ( 'company_name,specific_content,photo_url' )->where ( $condition3 )->select ();
	// var_dump($company_datas);die;
	/*
	 * if(null == $company_datas){
	 * return false;
	 * }
	 */
	$returns ['product'] = $datas; // 产品信息
	$returns ['company_datas'] = $company_datas; // 公司信息
	$returns ['batch_message'] = $bid; // 批次信息，目前就只有批次号
	
	return $returns;
}

/*
 * 电码微信查询，根据用户输入的电码，查询相应的数据,点击后微信网页显,转为菲普睇死设计
 */
function get_product_message_byPecOrder_topage_fpds($pec_order_id, $key) {
	$separator = "<br>";
	
	$mst_pec_order = M ( 'pec_order' );
	$condition ['pec_order_id'] = $pec_order_id;
	$bid = $mst_pec_order->where ( $condition )->getField ( 'bid' );
	
	// dump($data);die;
	// 获取批次相关信息
	$batch = M ( 'batch' );
	$condition1 ['bid'] = $bid;
	$pid = $batch->where ( $condition1 )->getField ( 'pid' );
	if (null == $pid) { // 自定义批次模板
		$pid = M ( 'template_area' )->where ( "bid='$bid'" )->getField ( 'pid' );
	}
	$batch_code = $batch->where ( $condition1 )->getField ( 'batch_code' );
	$startpoint = $batch->where ( $condition1 )->getField ( 'startpoint' ); // 问题
	$sellpoint = $batch->where ( $condition1 )->getField ( 'sellpoint' );
	$retail_price = $batch->where ( $condition1 )->getField ( 'retail_price' );
	$batch_message ['batch_code'] = $batch_code;
	$batch_message ['startpoint'] = $startpoint;
	$batch_message ['sellpoint'] = $sellpoint;
	$batch_message ['retail_price'] = $retail_price;
	$str_batch_message = " ";
	//dump($batch_message ['batch_code']);die;
	if($batch_message ['startpoint'] != null && $batch_message ['startpoint'] != 'SystemDefault'){
		$str_batch_message = "【生产地】" . $batch_message ['startpoint'].$separator;
	}if($batch_message ['sellpoint'] != null && $batch_message ['sellpoint'] != 'SystemDefault'){
		$str_batch_message = $str_batch_message. "【销售地】" . $batch_message ['sellpoint'].$separator;
	}if($batch_message ['retail_price'] != null && $batch_message ['retail_price'] != 'SystemDefault'){
		$str_batch_message = $str_batch_message. "【零售价】" . $batch_message ['retail_price'] . $separator;
	}
	//$str_batch_message = "【批次号】" . $batch_message ['batch_code'] . $separator . "【生产地】" . $batch_message ['startpoint'] . $separator . "【销售地】" . $batch_message ['sellpoint'] . $separator . "【零售价】" . $batch_message ['retail_price'] . $separator;
	// dump($str_batch_message);die;
	// 获取产品相关信息
	$product = M ( 'product' );
	$condition2 ['pid'] = $pid;
	$datas = $product->field ( 'product_name,cid,txt_content,html_content,company_html_content' )->where ( $condition2 )->select ();
	// var_dump($datas);die;
	if (null == $datas) {
		return false;
	}
	foreach ( $datas as $val ) {
		$cid = $val ['cid'];
		$product_name = $val ['product_name'];
	}
	
	// exit($cid);
	// 查询记录次数
	$pec_query_record = M ( 'pec_query_record' );
	$condition_record ['pec_id'] = $key;
	$count = $pec_query_record->where ( $condition_record )->getField ( 'count' );
	if (null == $count) {
		$anti_result = "恭喜，您所查询的商品为正品！" . "$separator";
		$count = 0;
		// 第一次查询,保存数据
		$data ['pec_id'] = $key;
		
		$data ['first_time'] = date ( 'Y-m-d H:i:s' );
		$data ['last_time'] = date ( 'Y-m-d H:i:s' );
		$first_time = date ( 'Y-m-d H:i:s' );
		$data ['count'] = $count + 1;
		$pec_query_record->data ( $data )->add (); // 保存
		$query_strr = "$anti_result" . $query_str . "第1次查询时间:" . $first_time . $separator;
		foreach ( $datas as &$val ) {
			$val ['txt_content'] = $query_strr;
		}
	} else {
		
		if ($count < 4) {
			$anti_result = "产品身份码查询成功！" . "$separator" . "您所查询的商品名称是：" . $product_name . "$separator" . "您所查询的产品身份码是:" . $key . "$separator";
			$and_so_on = "$separator" . "......" . "$separator";
			
			$counts = $count + 1;
			$query_str = "查询总次数:" . "$count" . "次" . "$separator";
			$first_time = $pec_query_record->where ( $condition_record )->getField ( 'first_time' );
			$last_time = $pec_query_record->where ( $condition_record )->getField ( 'last_time' );
			
			$end_time = date ( 'Y-m-d H:i:s' );
			$data ['last_time'] = date ( 'Y-m-d H:i:s' );
			$data ['count'] = $count + 1;
			// $pec_query_record->where($condition_record)->save($data);//更新
			if ($count == 1) {
				$query_strr = "$anti_result" . $query_str . "第1次查询时间:" . $first_time . $separator;
				foreach ( $datas as &$val ) {
					$val ['txt_content'] = $query_strr;
				}
			} else {
				
				$query_strr = "$anti_result" . $query_str . "第1次查询时间:" . $first_time . $separator . "第" . "$count" . "次查询时间:" . $last_time;
				
				foreach ( $datas as &$val ) {
					$val ['txt_content'] = $query_strr;
				}
			}
			$count = 0;
		} else {
			
			$counts = $count - 1;
			$query_str = "该产品身份码已查询了:" . "$count" . "次,谨防假冒！" . "$separator";
			$string = "查询成功，您所查询的产品是：" . $product_name . "$separator" . "您所查询的产品身份码是:" . $key . $separator . $query_str;
			$and_so_on = "$separator" . "......" . "$separator";
			$first_time = $pec_query_record->where ( $condition_record )->getField ( 'first_time' );
			$last_time = $pec_query_record->where ( $condition_record )->getField ( 'last_time' );
			
			$end_time = date ( 'Y-m-d H:i:s' );
			$data ['last_time'] = date ( 'Y-m-d H:i:s' );
			$data ['count'] = $count + 1;
			// $pec_query_record->where($condition_record)->save($data);//更新
			$query_strr = "$string" . $separator . "第1次查询时间:" . $first_time . $separator . $and_so_on . "第" . "$counts" . "次查询时间:" . $last_time . $separator . "第" . "$count" . "次查询时间:" . $end_time;
			foreach ( $datas as &$val ) {
				$val ['txt_content'] = $query_strr;
			}
			$count = 0;
		}
	}
	
	// var_dump($cid);die;
	// 获取相关公司信息
	$edit_company = M ( 'edit_company' );
	$condition3 ['cid'] = $cid;
	
	$company_datas = $edit_company->field ( 'company_name,specific_content,photo_url' )->where ( $condition3 )->select ();
	// var_dump($company_datas);die;
	/*
	 * if(null == $company_datas){
	 * return false;
	 * }
	 */
	
	$returns ['product'] = $datas; // 产品信息
	
	$returns ['company_datas'] = $company_datas; // 公司信息
	
	$returns ['batch_message'] = $str_batch_message; // 批次信息，目前就只有批次号
	
	return $returns;
}

/*
 * 递归,是否相等判断,辅助函数
 */
function only_equal($val, $val1) {
	if ($val == $val1) {
		$val1 = get_random_number_repeat ( 1 );
		return only_equal ( $val, $val1 );
	} else {
		return $val1;
	}
}
function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null) {
	$config = C ( 'THINK_EMAIL' );
	
	// vendor('PHPMailer.class#PHPMailer'); //从PHPMailer目录导class.phpmailer.php类文件
	include_once '\ThinkPHP\Library\Vendor\PHPMailer\class.phpmailer.php';
	// require_once '\ThinkPHP\Library\Vendor\PHPMailer\class.smtp.php';
	// require_once '\ThinkPHP\Library\Vendor\PHPMailer\class.pop3.php';
	// require 'phpMailerAutoload.php';
	$mail = new \PHPMailer (); // PHPMailer对象
	
	$mail->CharSet = 'UTF-8'; // 设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
	
	$mail->IsSMTP (); // 设定使用SMTP服务
	
	$mail->SMTPDebug = 0; // 关闭SMTP调试功能
	                      
	// 1 = errors and messages
	                      
	// 2 = messages only
	
	$mail->SMTPAuth = true; // 启用 SMTP 验证功能
	
	$mail->SMTPSecure = 'ssl'; // 使用安全协议
	
	$mail->Host = $config ['SMTP_HOST']; // SMTP 服务器
	
	$mail->Port = $config ['SMTP_PORT']; // SMTP服务器的端口号
	
	$mail->Username = $config ['SMTP_USER']; // SMTP服务器用户名
	
	$mail->Password = $config ['SMTP_PASS']; // SMTP服务器密码
	
	$mail->SetFrom ( $config ['FROM_EMAIL'], $config ['FROM_NAME'] );
	
	$replyEmail = $config ['REPLY_EMAIL'] ? $config ['REPLY_EMAIL'] : $config ['FROM_EMAIL'];
	
	$replyName = $config ['REPLY_NAME'] ? $config ['REPLY_NAME'] : $config ['FROM_NAME'];
	
	$mail->AddReplyTo ( $replyEmail, $replyName );
	
	$mail->Subject = $subject;
	
	$mail->AltBody = "为了查看该邮件，请切换到支持 HTML 的邮件客户端";
	
	$mail->MsgHTML ( $body );
	
	$mail->AddAddress ( $to, $name );
	
	if (is_array ( $attachment )) { // 添加附件
		
		foreach ( $attachment as $file ) {
			
			is_file ( $file ) && $mail->AddAttachment ( $file );
		}
	}
	
	return $mail->Send () ? true : $mail->ErrorInfo;
}

/*
 * 把6位如期代码转化成4位
 */
function pec_dataPrefix_code_to_four($pec_dataPrefix_code) {
	// dump($pec_dataPrefix_code);die;
	$y = substr ( $pec_dataPrefix_code, 0, 2 );
	// dump($y);die;
	// 15 10 22 00404736
	if ($y < 24) { // 15年~23年
		if ($y == 15) {
			$d = substr ( $pec_dataPrefix_code, 2, 2 );
			$prefix_two = $d; // 前两位
		}
		if ($y == 16) {
		}
		$last_two = substr ( $pec_dataPrefix_code, 4, 2 );
		$pec_dataPrefix_code_to_four = $prefix_two . $last_two;
	}
	return $pec_dataPrefix_code_to_four;
}
/*
 * 4位前缀转换成6位日期代码
 */
function pec_dataPrefix_code_to_six($pec_code) {
	$two = substr ( $pec_code, 0, 2 );
	if ($two < 13) {
		$pec_code_two = "15" . $two;
	}
	$last = substr ( $pec_code, 2, 2 );
	$pec_dataPrefix_code = $pec_code_two . $last;
	return $pec_dataPrefix_code;
}

/*
 * 判断在新的一天是否有相同的uid
 */
function is_equal_uid($remain_uid, $uid) {
	foreach ( $remain_uid as $val_uid ) {
		if ($uid == $val_uid) {
			return true;
			break;
		}
	}
}
/*
 *
 * $return 生成的电码
 * $date_number 日期代码
 * $table_name 动态表名
 * $number 电码位数
 */
function remove_repeat_number_half($return, $data_number, $table_name, $number1) {
	$index = M ( $table_name );
	$condition ['pec_dataPrefix_code'] = $data_number;
	$count = $index->where ( $condition )->count (); // 统计行数
	if ($count == 0) {
		return null;
	} else {
		// dump($return);
		$pec_order_id = $index->where ( $condition )->getField ( 'pec_order_id', true );
		// dump($pec_order_id);die;
		$array = null;
		for($i = 0; $i < $count; $i ++) {
			$condition_pec_order_id ['pec_order_id'] = $pec_order_id [$i];
			$json_number = $index->where ( $condition_pec_order_id )->getField ( 'pec_random_code' );
			// var_dump($json_number);die;
			$number = json_decode ( $json_number, true );
			// var_dump($number);die;
			if ($i == 0) {
				$array = $number;
			} else {
				$array = array_merge ( $array, $number );
			}
		} // dump($array);die;//把n个数组合并
		
		$return_number = count ( $return ); // 生成的数组长度
		                                 // dump($return_number);die;
		$temp = array_diff1 ( $return, $array ); // 返回数组$return中不同的数
		                                     // dump($temp);die;
		$temp_number = count ( $temp );
		$number_count = $return_number - $temp_number; // 想同数的长度
		                                               // dump($number_count);//重复的个数
		
		if ($number_count != 0) {
			$n_number = get_random_number_repeat_n ( $number_count, $number1 );
			// dump($n_number);
			// dump(count($n_number));die;
			
			foreach ( $n_number as &$val ) {
				
				// dump(1);die;
				foreach ( $array as $array1 ) { // 再次和数据库比较，查重
				                             // dump(1);die;
					if ($array1 == $val) {
						// dump(1);
						// dump($val);
						// dump(1);die;
						$val1 = get_random_number_repeat ( 1, $number1 ); // 获取一个随机数
						                                              // dump($val1);
						$answer = only_equal ( $val, $val1 ); // 返回唯一的数
						                                   // dump($answer);
						$val = $answer;
						// dump($val);
					}
				}
			}
			// dump($n_number);
		}
		$no_repeat = array_merge ( $temp, $n_number );
		// dump($no_repeat);die;
		
		return $no_repeat;
	}
}
/*
 * @二分法查找
 */
function search($array, $k, $low = 0, $high = 0) {
	if (count ( $array ) != 0 and $high == 0) // 判断是否为第一次调用
{
		$high = count ( $array );
	}
	if ($low <= $high) // 如果还存在剩余的数组元素
{
		$mid = intval ( ($low + $high) / 2 ); // 取$low和$high的中间值
		if ($array [$mid] == $k) // 如果找到则返回
{
			return $mid;
		} elseif ($k < $array [$mid]) // 如果没有找到，则继续查找
{
			return search ( $array, $k, $low, $mid - 1 );
		} else {
			return search ( $array, $k, $mid + 1, $high );
		}
	}
	return - 1;
}
function array_diff1($array_1, $array_2) {
	$array_2 = array_flip ( $array_2 );
	foreach ( $array_1 as $key => $item ) {
		if (isset ( $array_2 [$item] )) {
			unset ( $array_1 [$key] );
		}
	}
	
	return $array_1;
}

/*
 * 微信红包方法
 */
function wei_hg($api_id, $key, $pec_order_id, $type) {
	include_once '\ThinkPHP\Library\Vendor\weichat\wechatauth.class.php';
	// $api_id = session('api_id');
	// dump($api_id);die;
	$Wechat = new wechatauth ();
	$token = session ( 'token' ); // 查看是否已经授权
	                           // dump($token);
	if (! empty ( $token )) {
		// dump(1);die;
		// dump($token);die;
		// return false;
		// print_r($token);
		// dump($token['access_token']);
		// dump($token['openid']);die;
		$state = $Wechat->check_access_token ( $token ['access_token'], $token ['openid'] ); // 检验token是否可用(成功的信息："errcode":0,"errmsg":"ok")
			                                                                               // print_r($state);
			                                                                               // dump($state);die;
	}
	// return false;
	
	// dump(session('api_id'));
	$api_id_key = $api_id . $pec_order_id . $key;
	// dump($api_id);die;
	if ($type == "quzheng") {
		$url = $Wechat->get_authorize_url ( 'http://pec.weproduct.cn/weixin/photo_upload', $api_id_key );
	} else {
		$url = $Wechat->get_authorize_url ( 'http://pec.weproduct.cn/weixintest/wxrurl', $api_id_key ); // 此处链接授权后，会跳转到下方处理
	}
	// echo '<a href='.$url.'>授权</a>';
	return $url;
}

// 微信返回字符串
function wxrurl() {
	include_once '\ThinkPHP\Library\Vendor\weichat\wechat.class.php';
	$Wechat = new wechatauth ();
	print_r ( $_GET ); // 授权成功后跳转到此页面获取的信息
	$token = $Wechat->get_access_token ( '', '', $_GET ['code'] ); // 确认授权后会，根据返回的code获取token
	print_r ( $token );
	session ( 'token', $token ); // 保存授权信息
	$user_info = $Wechat->get_user_info ( $token ['access_token'], $token ['openid'] ); // 获取用户信息
	print_r ( $user_info );
}
/*
 * 获取appid，appsecret，商户号，商户支付密钥
 */
function get_weixin_configuration($api_id) {
	$api_list = M ( 'api_list' );
	$return_api = $api_list->where ( "api_id='$api_id'" )->getField ( 'api_weixin_appid,api_weixin_pay_account,api_weixin_pay_key,api_weixin_aes_key,api_weixin_client_ip', true ); // 获取appid,商户号，商户支付密钥,app_secret
	
	return $return_api;
}
/*
 * 获取商户企业名称及logo
 */
function get_hongbao_display($api_id) {
	$uid = M ( 'api_list' )->where ( "api_id='$api_id'" )->getField ( 'api_uid' );
	// dump($return_api_uid);die;
	$return_hongbao_display = M ( 'hongbao_display' )->where ( "uid='$uid'" )->select ();
	// dump($return_hongbao_display);die;
	return $return_hongbao_display;
}
/*
 * 获取红包配置
 */
function get_hongbao_configuration($api_id, $pec_order_id) {
	$uid = M ( 'api_list' )->where ( "api_id='$api_id'" )->getField ( 'api_uid' );
	$conditions ['uid'] = $uid;
	$conditions ['pec_order_id'] = $pec_order_id;
	
	$return_hongbao = M ( 'hongbao_pec_order' )->where ( $conditions )->select ();
	if (null == $return_hongbao) { // 使用默认配置
		$return_hongbao = M ( 'hongbao_default' )->where ( "uid='$uid'" )->select ();
	}
	return $return_hongbao;
}
/**
 * 数组加“key”
 */
function add_key($data){
	$arr = array();
	
	foreach ($data as $key => $val){

	    foreach ($val as $k => $v){
	    	
	    	$arr['province'][$key]['time'][$k]['num'] = $v;
	    	
		}
		
}
	return $arr;
}
/**
 * 删除数组中为空的值
 */
function  array_delelet_null($condition = null){
	foreach ($condition as $key => $value){
			
		if($value[1] == null){
			unset($condition[$key]);
		}
			
	}
	return $condition;
}
/**
 * @todo 获取当前 年-月
 */
 function getYm(){
 	$date_reg=date("Y-m-d H:i:s",time());
 	
 	$date=explode(" ",$date_reg);
 	
 	$date1=explode("-",$date[0]);
 	
 	$year = $date1[0];
 	
 	$month = $date1[1];
 	return $year.'-'.$month;
	
}
/**
 * @todo 数据可视化 处理附件条件
 */
function getAttachment_condition($content = null){
	if(null != $content){
		//dump($content);
		foreach ($content as $v => $k){
			//dump($v);
			if("retail_price" == $v){
				$return = retail_price_deal($v,$k); //retail_price 零售价区间处理
				$temp_condition [$v] = array ('in',$k);
				$temp_condition = array_merge($temp_condition,$return);
			}else if("sellpoint" == $v || "startpoint" == $v){
				//dump($k);die;
				foreach ($k as $k1 => $v1){
					foreach ($v1 as $k2 => $v2){
						$temp_condition [$v] = array ('in',$v2);
					}
				}
			}
			else{
			$temp_condition [$v] = array ('in',$k);
			}
		}
		//dump($temp_condition);die;
		return $temp_condition;
	}else{
		return false;
	}
}
/**
 * @todo 数据可视化，图类型判断
 */
 function getGraphics_type($content){
 	if($content['legendx'] != null){ //有维度
 		foreach ($content['legendx'] as $key => $val){
 			if($key == "retail_price" || $key == "createtime"){ //柱状图或折线图
 				$result['type'][0] = "Histogram";
 				$result['type'][1]  = "linechart";
 			}else if($key == "startpoint" || $key == "sellpoint"){
 				$result['type'] = "map";
 			}
 		}
 	}else{
 		$result['type'] = "standardpiechart";
 	}
 	//dump($result);
 	return $result;
 }
 /**
  * @todo 数据可视化，方便前端数据处理,手工拼凑，弃用
  */
function getFront_deal($result){
	if($result['type'][0] == "Histogram"){ //柱状图或折线图
		unset($result['type']);
		foreach ($result as $key => $val){
			//dump($val);
			foreach ($val as $k => $v){
				foreach ($v as $v_k => $v_v){
					$i = 0;
					foreach ($v_v as $v_v_v){
					$temp[$i] = $v_v_v;
					$i += 1;
					}
				}
				$return[$k] = $temp;
			}
		}
	}else if($result['type'] == "map"){
		unset($result['type']);
		foreach ($result as $key => $val){
			//dump($val);
			foreach ($val as $k => $v){
				foreach ($v as $v_k => $v_v){
					foreach ($v_v as $v_v_k => $v_v_v_v){
					$temp1 = $temp1.'{name:'." ' ".$v_v_k." ' ".','.'value:'.$v_v_v_v.'}'.',';
				}
				
				$temp2[$k] = substr($temp1, 0, -1);
				$temp1 = null;
			}
			}
		}
		//dump($temp2);
		$return['data'] = $temp2;
		//dump($return);
	}else if($result['type'] == "standardpiechart"){
		unset($result['type']);
		//dump($result);
		foreach ($result as $st_val){
			foreach ($st_val as $val_k => $val_v){
				$temp1 = $temp1.'{value:'.$val_v.','.'name:'.$val_k.'}'.',';
			}
			
		}
		$temp = substr($temp1, 0, -1);
		$return['data'] = $temp;
	}
	//dump($return);
	return $return;
} 
/**
 * 
 * @param  $result
 * @return string
 * @todo	数据可视化，方便前端数据处理,json格式
 */
function getFront_deal_json($result){
	include_once '\ThinkPHP\Library\Vendor\Json\json.php';
	//dump($result['type']);
	if($result['type'][0] == "Histogram"){ //柱状图或折线图
		unset($result['type']);
		foreach ($result as $key => $val){
			//dump($val);
			$j = 0;
			foreach ($val as $k => $v){
				
				foreach ($v as $v_k => $v_v){
					$i = 0;
					foreach ($v_v as $v_v_v){
						
						$temp[$j][$i] = $v_v_v;
						$i += 1;
					}
				}
				//$return['data'] = array_values($temp);
				$j += 1; 
				$return['data'] = $temp;
			}
		}
		//$return = array_values($return);
	}else if($result['type'] == "map"){
		
		unset($result['type']);
		foreach ($result as $key => $val){
			//dump($val);
			foreach ($val as $k => $v){
				$i = 0;
				foreach ($v as $v_k => $v_v){
					$i_i = 0;
					foreach ($v_v as $v_v_k => $v_v_v_v){
						//$temp1 = $temp1.'{name:'." ' ".$v_v_k." ' ".','.'value:'.$v_v_v_v.'}'.',';
						$temp1[$i_i]['name'] = $v_v_k;
						$temp1[$i_i]['value'] = $v_v_v_v;
						$i_i += 1;
					}
					$product_name = getProduct_name($k);
					if(null != $product_name){
					$k_name = $product_name; //获取产品名称
					}else{
						$k_name = $k;
					}
					//dump($k_name);
					$temp[$k_name] = $temp1;
					
					$i += 1;
				}
			}
		}
	//	$temp = array_values($temp);
		//dump(json_encode($temp));die;
		//Util::json_encode($temp);
		$return['data'] = (json_encode($temp,JSON_UNESCAPED_UNICODE));
		//dump($return);
	}else if($result['type'] == "standardpiechart"){
		unset($result['type']);
		//dump($result);
		foreach ($result as $st_val){
			//dump($st_val);die;
			$i_i = 0;
			foreach ($st_val as $val_k => $val_v){
						$temp1[$i_i]['value'] = $val_v;
						$temp1[$i_i]['name'] = getProduct_name($val_k); //获取产品名称
						$i_i += 1;
			}
				
		}
		//dump(json_encode($temp1));die;
		//$temp1 = array_values($temp1);
		$return['data'] = json_encode($temp1,JSON_UNESCAPED_UNICODE);
	}

	//dump(($return));die;

	//dump(($return));die;

	return $return;
}
/**
 * @todo 数据可视化，根据pid获取产品名称
 */
function getProduct_name($val_k = null){
	$product = M('product');
	$product_name = $product -> where("pid = '$val_k'") ->getField('product_name');
	return $product_name;
}
/**
 * @todo 数据可视化，零售价数据验证判断
 */
function retail_price_deal($v = null,$k = null){
	if(null != $k){
		//dump($k[0]);//dump($v);
		if('less' == $k[0]){
			$temp_condition[$v]  = array('lt',$k[1]); //<=
		}else if('than' == $k[0]){
			$temp_condition[$v]  = array('gt',$k[1]); //>=
		}else if(intval($K[0]) == intval($k[1])){
			$temp_condition [$v] = array ('in',$k[0]); //=
		}else if(intval($K[0]) < intval($k[1])){
			$temp_condition[$v]  = array('between',array($k[0],$k[1]));
		}else if(intval($k[0]) > intval($k[1])){
			$temp_condition[$v]  = array('between',array($k[1],$k[0]));
		}
		//dump($temp_condition);
		return $temp_condition;
	}
}

?>