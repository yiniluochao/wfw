<?php

/**
 * 批次控制器，控制批次的添加界面及响应，管理界面及响应，编辑界面及响应
 *
 * @author Kuangfuabc
 */
namespace Home\Controller;

use Think\Controller;

class BatchController extends Controller {
	
	// 未登录[页面跳转]
	protected function __userNotLogin() {
		$this->error ( "亲还没有登录呢!", "/" );
	}
	
	// 未准备[页面跳转]j
	protected function __NotReady() {
		$this->error ( "本功能还没有完工!", "/" );
	}
	
	// 显示首页
	public function index() {
		$this->user_name = get_user_name ();
		if (1 == get_user_type ()) {
			$this->mode = 1;
		} else {
			$this->mode = 0;
		}
		$this->display ();
	}
	
	// 调转到添加批次
	public function newone() {
		if (true == is_login ()) {
			$this->page_id = 4;
			/*
			 * if($pid==0||$pid==NULL)
			 * {
			 * redirect('/batch/notice');
			 * return;
			 * }
			 */
			$uid = get_user_id ();
			$get_template = M ( 'template' );
			$get_template_name = $get_template->where ( "uid='$uid'" )->getField ( 'id,template_name', true );
			// dump($get_template_name);die;
			$content = '';
			// 屏蔽自定义批次模版
			
			  if(null != $get_template_name){
			
			 
			  foreach ($get_template_name as $key => $template_val){
			  $key = $key.$pid;
			  $content = $content . "<a type='button' href='/batch/display_template/$key' class='btn btn-success'>$template_val</a>";
			  $key = '';
			  }
			  //dump($content);die;
			  $this->content = $content;
			  $this->display('display_template_name');
			 
			  }else{
			 
			$this->pid = $pid;
			$product = M ( 'product' );
			
			  $product_code = $product->where("pid=$pid")->getField('product_code');
			  $this->product_code = $product_code;
			 
			$product_name = $product->where ( "cid = '$uid'" )->select ();
			// dump($uid);
			// dump($product_name);
			$this->list = $product_name;
			
			$this->batch_code = 'B' . date ( 'YmdHis' ) . rand ( 1000, 9999 );
			$this->display ();
			 }
		} else {
			$this->__userNotLogin ();
		}
	}
	public function notice() {
		// 界面渲染
		$this->display ();
	}
	
	// 添加批次处理
	public function newone_deal() {
		if (IS_POST) {
			if (true == is_login ()) {
				
				// 取数据
				$product_name = I ( 'post.rtl' );
				
				$batch_code = I ( 'post.batch_code' );
				$startpoint = I ( 'post.startpoint' );
				$sellpoint = I ( 'post.sellpoint' );
				$retail_price = I ( 'post.retail_price' );
				$pid = M ( 'product' )->where ( "product_name = '$product_name'" )->getField ( 'pid' );
				if ($pid == 0) {
					$this->errorinfo = '不能没有产品';
				}
				
				do {
					// 写数据库
					if (empty ( $batch_code )) {
						$this->errorinfo = '批次代码不能为空';
						break;
					}
					/*
					 * $product = M('product');
					 * $cid = $product->where("pid=$pid")->getField('cid');
					 */
					
					$data ['batch_code'] = $batch_code;
					$data ['startpoint'] = $startpoint;
					$data ['sellpoint'] = $sellpoint;
					$data ['retail_price'] = $retail_price;
					$data ['pid'] = $pid;
					$data ['cid'] = get_user_id ();
					$data ['createtime'] = date ( 'Y-m-d H:i:s' ); // 当前服务器时间[默认是北京时间，如果国际化，要进行时区处理
					$batch = M ( 'Batch' );
					$bid = $batch->data ( $data )->add ();
					
					if (null == $bid) {
						$this->errorinfo = '创建出错！';
						break;
					}
					redirect ( "/batch/view/$bid" );
					// $this->success('新增成功!', '/product/view/id/'."$pid");
					return;
				} while ( 0 );
				
				// 界面持久
				$this->product_code = $product_code;
				$this->pid = $pid;
				$this->batch_code = $batch_code;
				$this->startpoint = $startpoint;
				$this->sellpoint = $sellpoint;
				$this->retail_price = $retail_price;
				$this->display ( 'newone' );
			}
		}
	}
	// 批次展示
	public function view($bid = 0) {
		if (true == is_login ()) {
			$this->page_id = 5;
			$batch = M ( 'batch' ); // 实例化
			$result = $batch->where ( "bid=$bid" )->find ();
			if ($result == null) {
				$template_area = M ( 'template_area' );
				$template_area_datas = $template_area->where ( "bid='$bid'" )->field ( 'pid,batch_code,area' )->select ();
				if (null == $template_area_datas) {
					$this->error ( '系统出错，请于管理员联系!' );
				}
				$contents = '';
				foreach ( $template_area_datas as $val ) {
					$this->pid = $val ['pid'];
					$this->batch_code = $val ['batch_code'];
					// dump($val['area']);die;
					$template_name_datas = json_decode ( $val ['area'], true );
					// dump($template_name_datas);die;
					// dump(count($template_name_datas));die;
					// 显示页面
					$template_area = M ( 'template_area' );
					$now_bid = $bid;
					// dump($now_bid);die;
					$pid = $template_area->where ( "bid='$now_bid'" )->getField ( 'pid' );
					$this->pid = $pid;
					// dump($pid);die;
					$template_name = $template_area->where ( "bid='$now_bid'" )->getField ( 'template_name' );
					$json_area = $template_area->where ( "bid='$now_bid'" )->getField ( 'area' );
					if (null == $json_area) {
						$this->error ( '模板为空，请编辑!' );
					}
					$area = json_decode ( $json_area, true );
					// dump($area);die;
					for($i = 1; $i < 11; $i ++) {
						foreach ( $area as $key => $area_val ) {
							if ($i == $key) {
								// dump($area_val);
								$datas [] = $area_val;
							}
						}
					}
					$this->template_name = $template_name;
					// dump($datas);die; //数据按顺序输出
					$template = M ( 'template' );
					$contents = '';
					$template_name_area = $template->where ( "template_name='$template_name'" )->getField ( 'area' );
					$template_name_datas = json_decode ( $template_name_area, true );
					// dump($template_name_datas);die;
					// dump(count($template_name_datas));die;
					for($i = 0; $i < count ( $template_name_datas ); $i ++) {
						$prefix = substr ( $template_name_datas [$i], 0, 3 );
						if ($prefix == 'txt') {
							$content_val = substr ( $template_name_datas [$i], 3 );
							$contents = $contents . "<div class='widget-main'>
            			<!-- Text input-->
            			<label class='control-label' for='startpoint'>{$content_val}</label>
            			<div class='controls'>
            	
            			<input class='form-control'  type='text' value={$datas[$i]}>
            			<p class='help-block'></p>
            			</div>
            			</div>";
						} else {
							$content_val = substr ( $template_name_datas [$i], 3 );
							$contents = $contents . "<div class='widget-main'>
            			<!-- Text input-->
            			<label class='control-label' for='startpoint'>{$content_val}</label>
            			 
            			<div class='controls'>
            			 
            			<img src='/upload{$datas[$i]}' width='100px' height='100px'/>
            			</div>
            	
            			</div>";
						}
					}
					$this->contents = $contents;
					
					$this->display ( 'template_view' );
				}
			} else {
				$batch = M ( 'batch' ); // 实例化
				$result = $batch->where ( "bid=$bid" )->find ();
				$pid = $batch->where ( "bid=$bid" )->getField ( 'pid' );
				$this->pid = $pid;
				if ($result == null) {
					$this->error ( "不存在批次信息" );
				} else {
					$this->batch_code = $result ['batch_code'];
					$this->startpoint = $result ['startpoint'];
					$this->sellpoint = $result ['sellpoint'];
					$this->retail_price = $result ['retail_price'];
					$this->display ();
				}
			}
		}
	}
	// 批次管理
	public function manager($pid = 0) {
		if (true == is_login ()) {
			$this->page_id = 5;
			$this->pid = $pid;
			$template_area = M ( 'template_area' );
			$template_area_bid = $template_area->where ( "pid='$pid'" )->getField ( 'bid', true ); // 自定义批次模板中存在
			                                                                                // dump($template_area_bid);die;
			
			if (null != $template_area_bid) {
				
				$Model = new \Think\Model ();
				$list = $Model->query ( "select * from __PREFIX__template_area,__PREFIX__product where __PREFIX__template_area.pid=  $pid and   $pid = __PREFIX__product.pid  order by __PREFIX__template_area.createtime desc " );
				// dump($list);die;
				$this->assign ( 'list', $list ); // 赋值数据集
				                              // $this->assign('page', $show); // 赋值分页输出
				$this->display ();
				// dump($product_name);die;
			} else {
				
				$batch = M ( 'Batch' ); // 实例化对象
				$product = M ( 'product' );
				
				// 若未指定产品则查询该公司下的所有批次，及其对应的产品名称，产品代码
				$user_type = get_user_type ();
				$arrange = "";
				$sql_ex = "";
				
				// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
				if ($pid == null || $pid == 0) {
					if (0 == $user_type) {
						$cid = get_user_id ();
						$arrange = " cid=$cid ";
						$sql_ex = " and __PREFIX__batch.cid = $cid ";
					}
				} else {
					// 如果指定产品，则向模板输出产品id
					$this->pid = $pid;
					if (0 == $user_type) {
						$cid = get_user_id ();
						$arrange = "pid=$pid and cid=$cid ";
						$sql_ex = " and __PREFIX__product.pid = $pid and __PREFIX__batch.cid = $cid ";
					}
				}
				/*
				 * $page_product = M('product');
				 * $page_product->where("pid='$pid'")->getField('product_name,product_code,create_time');
				 * $page_batch = M('batch');
				 * $page_batch->where("pid='$pid'")->getField('');
				 */
				$Model = new \Think\Model ();
				$count = $batch->where ( "$arrange" )->count (); // 查询满足要求的总记录数
				//dump($count);
				$Page = new \Think\Page ( $count, 8 ); // 实例化分页类 传入总记录数和每页显示的记录数(25)
				$Page->rollPage = 8;
				$show = $Page->show (); // 分页显示输出
				// $list = $trade->where("pid=$pid")->order("createtime desc")->limit($Page->firstRow . ',' . $Page->listRows)->select();
				$list = $Model->query ( "select * from __PREFIX__batch,__PREFIX__product where __PREFIX__batch.pid=__PREFIX__product.pid $sql_ex order by __PREFIX__batch.createtime desc " );
				
				// $list = $Model->query("select * from __PREFIX__batch,__PREFIX__product where __PREFIX__batch.pid=__PREFIX__product.pid $sql_ex order by __PREFIX__batch.createtime desc limit $Page->firstRow, $Page->listRows");
				
				$list = array_slice($list,$Page->firstRow,$Page->listRows);
				foreach ( $list as &$val ) {
					if ($val ['startpoint'] == 'SystemDefault'){
						$val ['startpoint'] = '';
						$val ['sellpoint'] = '';
					$val ['retail_price'] = '';
					$val['batch_code'] = $val['batch_code'].'(auto)';
					}
				}
				// dump($list['startpoint']);
				$this->assign ( 'list', $list ); // 赋值数据集
				$this->assign('page', $show); // 赋值分页输出
				$this->display (); // 输出模板
			}
		} else {
			$this->__userNotLogin ();
		}
	}
	
	// 编辑界面显示
	public function edit($bid = 0) {
		if (true == is_login ()) {
			$this->page_id = 5;
			
			$batch = M ( 'batch' ); // 实例化
			$result = $batch->where ( "bid=$bid" )->find ();
			// 从外部进来会传入id,将id输出到模板，用于提交表单
			$this->bid = $bid;
			
			if ($result == null) {
				// $this->error("不存在产品信息");
				// 进入自定义批次模板中查找
				$template_area = M ( 'template_area' );
				$template_area_datas = $template_area->where ( "bid='$bid'" )->getField ( 'area' );
				if (null == $template_area_datas) {
					$this->error ( "不存在产品信息" );
				} else { // 显示自定批次模板内容
					$template_area = M ( 'template_area' );
					$now_bid = $bid;
					// dump($now_bid);die;
					$pid = $template_area->where ( "bid='$now_bid'" )->getField ( 'pid' );
					$batch_code = $template_area->where ( "bid='$now_bid'" )->getField ( 'batch_code' );
					$this->batch_code = $batch_code;
					$this->pid = $pid;
					// dump($pid);die;
					$template_name = $template_area->where ( "bid='$now_bid'" )->getField ( 'template_name' );
					$json_area = $template_area->where ( "bid='$now_bid'" )->getField ( 'area' );
					if (null == $json_area) {
						$this->error ( '模板为空，请编辑!' );
					}
					$area = json_decode ( $json_area, true );
					// dump($area);die;
					for($i = 1; $i < 11; $i ++) {
						foreach ( $area as $key => $area_val ) {
							if ($i == $key) {
								// dump($area_val);
								$datas [] = $area_val;
							}
						}
					}
					$this->template_name = $template_name;
					// dump($datas);die; //数据按顺序输出
					$template = M ( 'template' );
					$contents = '';
					$template_name_area = $template->where ( "template_name='$template_name'" )->getField ( 'area' );
					$template_name_datas = json_decode ( $template_name_area, true );
					// dump($template_name_datas);die;
					// dump(count($template_name_datas));die;
					$id = 0;
					for($i = 0; $i < count ( $template_name_datas ); $i ++) {
						$prefix = substr ( $template_name_datas [$i], 0, 3 );
						$id = $id + 1;
						if ($prefix == 'txt') {
							
							$content_val = substr ( $template_name_datas [$i], 3 );
							$contents = $contents . "<div class='widget-main'>
 			   			<!-- Text input-->
 			   			<label class='control-label' for='startpoint'>{$content_val}</label>
 			   			<div class='controls'>
 			   	
 			   			<input class='form-control'  type='text' name='$id' value={$datas[$i]}>
 			   			<p class='help-block'></p>
 			   			</div>
 			   			</div>";
						} else {
							$content_val = substr ( $template_name_datas [$i], 3 );
							$contents = $contents . "<div class='widget-main'>
 			   			<!-- Text input-->
 			   			<label class='control-label' for='startpoint'>{$content_val}</label>
 			   			 
 			   			<div class='controls'>
 			   			 
 			   			<input type='file' name='photo$id'/>
 			   			</div>
 			   	
 			   			</div>";
						}
					}
					$this->contents = $contents;
					$this->display ( 'edit_template_area' );
				}
			} else {
				$this->batch_code = $result ['batch_code'];
				if($result ['startpoint'] == 'SystemDefault'){
					$result ['startpoint'] = '';
					$result ['sellpoint'] = '';
					$result ['retail_price'] = '';
				}
				$this->startpoint = $result ['startpoint'];
				$this->sellpoint = $result ['sellpoint'];
				$this->retail_price = $result ['retail_price'];
				$this->display ();
			}
		}
	}
	
	// 编辑处理
	public function edit_deal($bid = -1) {
		if (IS_POST) {
			if (true == is_login ()) {
				$batch_code = I ( 'post.batch_code' );
				$batch_bid = M ( 'batch' )->where ( "batch_code='$batch_code'" )->getField ( 'bid' );
				if (null == $batch_bid) { // 如果$batch_bid为空，则是自定义批次模版的内容
					$template_bid = M ( 'template_area' )->where ( "batch_code='$batch_code'" )->getField ( 'bid' );
					if (null == $template_bid) {
						$this->error ( '系统出错，请于管理员联系!' );
					} else { // 修改自定义批次模板内容
						for($i = 1; $i < 11; $i ++) { // 每个模板最多有10行内容
							$data = I ( "post.$i" );
							if (null != $data) {
								$txt_datas [] = $i . $data;
							}
						}
						// dump($txt_datas);die;
						
						foreach ( $txt_datas as $txt_val ) {
							$i = substr ( $txt_val, 0, 1 );
							$datas [$i] = $txt_val;
						}
						// dump($datas);die;
						$pic_datas = $this->upload ();
						// dump($pic_datas);die;
						foreach ( $pic_datas as $pic_val ) {
							// dump($pic_val);
							$i = substr ( $pic_val, 5, 1 );
							
							$datas [$i] = substr ( $pic_val, 6 );
						}
						// dump($datas);die;
						$json_datas = json_encode ( $datas );
						// dump($json_datas);die;
						$area_datas ['area'] = $json_datas;
						$return = M ( 'template_area' )->where ( "bid='$bid'" )->data ( $area_datas )->save ();
						// dump($return);die;
						if (null != $return) {
							redirect ( "/batch/view/{$bid}" );
						}
					}
				} else {
					$startpoint = I ( 'post.startpoint' );
					$sellpoint = I ( 'post.sellpoint' );
					$retail_price = I ( 'post.retail_price' );
					
					do {
						// 写数据库
						if (empty ( $batch_code )) {
							$this->errorinfo = '批次代码不能为空';
							break;
						}
						
						$data ['batch_code'] = $batch_code;
						$data ['startpoint'] = $startpoint;
						$data ['sellpoint'] = $sellpoint;
						$data ['retail_price'] = $retail_price;
						
						$batch = M ( 'batch' );
						$result = $batch->where ( "bid=$bid" )->save ( $data );
						if ($result === NULL) {
							$this->errorinfo = '修改出现错误！';
							break;
						}
						redirect ( "/batch/view/$bid" );
						// $this->success('修改成功!', "/product/view/id/$id");
						return;
					} while ( 0 );
					
					$this->batch_code = $batch_code;
					$this->startpoint = $startpoint;
					$this->sellpoint = $sellpoint;
					$this->retail_price = $retail_price;
					$this->display ( 'edit' );
				}
			}
		}
	}
	
	// 搜索批次
	public function search() {
		if (true == is_login ()) {
			$this->page_id = 5;
			
			$search_key = I ( 'post.key' );
			
			if (empty ( $search_key )) {
				$this->error ( "关键字为空!" );
				return;
			}
			$uid = get_user_id ();
			$Model = new \Think\Model ();
			$list = $Model->query ( "select * from __PREFIX__batch,__PREFIX__product where __PREFIX__batch.pid=__PREFIX__product.pid and __PREFIX__batch.cid=$uid and __PREFIX__batch.batch_code like '%$search_key%' " );
			if ($list == NULL || $list == false) {
				$list1 = $Model->query ( "select * from __PREFIX__template_area,__PREFIX__product where __PREFIX__template_area.pid=__PREFIX__product.pid and __PREFIX__template_area.cid=$uid and __PREFIX__template_area.batch_code like '%$search_key%' " );
				// $this->error("没有找到合适的");
				// dump($list);die;
				$this->list = $list1;
				// 将查找出的数据向模板输出
				$this->display ( 'manager' );
				if ($list1 == NULL || $list1 == false) {
					$this->error ( "没有找到合适的" );
				}
			} else {
				$this->list = $list;
				// 将查找出的数据向模板输出
				$this->display ( 'manager' );
			}
		}
	}
	/*
	 * 手机格式预览
	 */
	public function preview($bid = 0) {
		$batch = M ( 'batch' );
		$condition ['bid'] = $bid;
		$pid = $batch->where ( $condition )->getField ( 'pid' );
		if (empty ( $pid )) {
			$pid = M ( 'template_area' )->where ( "bid='$bid'" )->getField ( 'pid' );
		}
		$product = M ( 'product' );
		$condition1 ['pid'] = $pid;
		$html_content = $product->where ( $condition1 )->getField ( 'html_content' ); // 产品图文信息
		$cid = $product->where ( $condition1 )->getField ( 'cid' );
		$edit_company = M ( 'edit_company' );
		$condition2 ['cid'] = $cid;
		$specific_content = $edit_company->where ( $condition2 )->getField ( 'specific_content' ); // 公司图文信息
		
		$this->html_content = $html_content;
		if (null == $specific_content) {
			$this->company_content = $specific_content;
		} else {
			$this->company_content = "<p>公司图文信息</p>" . $specific_content;
		}
		$this->display ();
	}
	/*
	 * 显示自定义批次模板
	 */
	public function custom($pid = 0) {
		// dump($pid);die;
		$this->pid = $pid;
		$this->display ();
	}
	/*
	 * 处理自定义批次模板数据,存储用户自定义批次模板数据
	 */
	public function custom_deal($uid = 0) {
		// dump($pid);die;
		$uid = get_user_id ();
		$template_name = I ( 'post.template_name' ); // 获取模板名称
		if (null == $template_name) {
			$this->error ( '模板名称不能为空!' );
			die ();
		}
		// dump($template_name);
		$template = M ( 'template' );
		// 首先查重
		$condition_template ['template_name'] = $template_name;
		$return_uid = $template->where ( $condition_template )->getField ( 'uid' );
		if (null != $return_uid) {
			$this->error ( '模板名重复，请重新输入!' );
			die ();
		}
		// 根据uid存储相应的模板名称及内容
		$area = I ( 'post.area' );
		$area_json = json_encode ( $area );
		// dump($area_json);
		if ($area_json != null) {
			$data ['uid'] = $uid;
			$data ['template_name'] = $template_name;
			$data ['area'] = $area_json;
			$result = $template->add ( $data );
			// dump($result);die;
			if (null != $result) {
				redirect ( '/batch/manager' );
			}
		}
	}
	/*
	 * 获取模板名称
	 */
	public function get_template_name($uid = 0) {
		$uid = get_user_id ();
		$template_name = M ( 'template' );
		$template_names = $template_name->where ( "uid='$uid'" )->getField ( 'id,template_name' );
		$template_names_json = json_encode ( $template_names );
		// dump($template_names_json);die;
		return $template_names_json;
	}
	/*
	 * 点击弹窗上的确认按钮 ,根据选择的模板渲染相应的页面
	 */
	public function display_template($key) {
		// dump(strlen($key));
		if (strlen ( $key ) == 6) {
			$id = substr ( $key, 0, 3 );
			$pid = substr ( $key, 3 );
		}
		if (strlen ( $key ) == 4) {
			$id = substr ( $key, 0, 2 );
			$pid = substr ( $key, 2 );
		}
		// dump($id);
		// dump($pid);die;
		$this->pid = $pid;
		$template = M ( 'template' );
		$template_name = $template->where ( "id='$id'" )->getField ( 'template_name' );
		// dump($template_name);die;
		$result_area = $template->where ( "template_name='$template_name'" )->getField ( 'area' );
		$area = json_decode ( $result_area );
		// dump($area);die;
		$this->template_name = $template_name;
		$content = '';
		$id = 0;
		foreach ( $area as $val ) {
			$prefix = substr ( $val, 0, 3 ); // 截取每个$val值的前缀判断,如果前缀为text,则为文本输入框;如果前缀为pic,则为上传图片按钮
			$id += 1;
			if ($prefix == "txt") {
				// 文本信息
				$content_val = substr ( $val, 3 );
				$content = $content . "<div class='widget-main'>
                  <!-- Text input-->
                  <label class='control-label' for='startpoint'>{$content_val}</label>
                  <div class='controls'>
                  
                    <input class='form-control'  type='text'  name=$id>
                    <p class='help-block'></p>
                  </div>
                                    </div>";
			} else {
				// 图片信息
				$content_val = substr ( $val, 3 );
				$content = $content . "<div class='widget-main'>
			   		<!-- Text input-->
			   		<label class='control-label' >{$content_val}</label>
			   		<div class='controls'>
			   		
			   		<input  type='file'  name='photo$id'>
			   	
			   		<p class='help-block'></p>
			   		</div>
			   		</div>";
			}
		}
		$this->content = $content;
		$this->display ();
	}
	
	/*
	 * 图片上传类
	 */
	function upload() {
		$upload = new \Think\Upload (); // 实例化上传类
		$rand = uniqid () . rand ( 1, 10000 );
		$upload->maxSize = 3145728; // 设置附件上传大小
		$upload->exts = array (
				'jpg',
				'png',
				'jpeg' 
		); // 设置附件上传类型
		
		$upload->rootPath = './upload/'; // 设置附件上传根目录
		$upload->savePath = '/template/';
		// $upload->saveHash = false;
		$upload->saveName = '';
		// 上传文件
		$info = $upload->upload ();
		
		// dump($info);die;
		// 如果没上传就显示系统默认图片 default.png
		if (! $info) { // 上传错误提示错误信息
			$this->error ( $upload->getError () );
			return NULL;
		}
		$files = array ();
		foreach ( $info as $file ) {
			$files [] = $file ['key'] . $file ['savepath'] . $file ['savename'];
		}
		return $files;
		// var_dump($files);die;
	}
	
	/*
	 * 获取模板对应的数据
	 */
	public function get_template_data($pid) {
		$template_name = I ( 'post.template_name' );
		// dump($template_name);die;
		
		for($i = 1; $i < 11; $i ++) { // 每个模板最多有10行内容
			$data = I ( "post.$i" );
			if (null != $data) {
				$txt_datas [] = $i . $data;
			}
		}
		// dump($txt_datas);die;
		
		foreach ( $txt_datas as $txt_val ) {
			$i = substr ( $txt_val, 0, 1 );
			$datas [$i] = $txt_val;
		}
		// dump($datas);die;
		$pic_datas = $this->upload ();
		// dump($pic_datas);die;
		foreach ( $pic_datas as $pic_val ) {
			// dump($pic_val);
			$i = substr ( $pic_val, 5, 1 );
			
			$datas [$i] = substr ( $pic_val, 6 );
		}
		// dump($datas);die;
		$json_datas = json_encode ( $datas );
		// dump($json_datas);die;
		// dump(json_decode($json_datas));die;
		/*
		 * $batch = M('batch');
		 * $bid_max = $batch->max('bid');
		 * dump($bid_max);die;
		 *
		 * $template_area = M('template_area');
		 * $template_area_max_bid = $template_area->max('bid'); //批次最大值
		 */
		$template_area = M ( 'template_area' );
		$area_datas ['cid'] = get_user_id ();
		$area_datas ['pid'] = $pid;
		$area_datas ['template_name'] = $template_name;
		$area_datas ['area'] = $json_datas;
		$area_datas ['createtime'] = date ( 'y-m-d h:m:s' );
		$area_datas ['batch_code'] = 'B' . date ( 'YmdHis' ) . rand ( 1000, 9999 );
		$template_area->data ( $area_datas )->add ();
		$template_name_t = $template_area->where ( "pid='$pid'" )->getField ( 'template_name' );
		/*
		 * if(null == $template_name_t){//第一次修改模板数据
		 * $area_datas['pid'] = $pid;
		 * $area_datas['template_name'] = $template_name;
		 * $area_datas['area'] = $json_datas;
		 * $template_area->data($area_datas)->add();
		 * }
		 * if($template_name != $template_name_t){
		 * $condition_area_datas['pid'] = $pid;
		 * $area_datas['template_name'] = $template_name;
		 * $area_datas['area'] = $json_datas;
		 * $template_area->where($condition_area_datas)->data($area_datas)->save();
		 * }
		 * else{
		 * $area_datas1['area'] = $json_datas;
		 * $template_area->where("template_name='$template_name'")->data($area_datas1)->save();
		 * }
		 */
		$this->display_template_area ();
	}
	/*
	 * 存储数据成功后，显示模板内容
	 */
	public function display_template_area() {
		$template_area = M ( 'template_area' );
		$now_bid = $template_area->max ( 'bid' );
		// dump($now_bid);die;
		$pid = $template_area->where ( "bid='$now_bid'" )->getField ( 'pid' );
		$this->pid = $pid;
		// dump($pid);die;
		$template_name = $template_area->where ( "bid='$now_bid'" )->getField ( 'template_name' );
		$json_area = $template_area->where ( "bid='$now_bid'" )->getField ( 'area' );
		if (null == $json_area) {
			$this->error ( '模板为空，请编辑!' );
		}
		$area = json_decode ( $json_area, true );
		// dump($area);die;
		for($i = 1; $i < 11; $i ++) {
			foreach ( $area as $key => $area_val ) {
				if ($i == $key) {
					// dump($area_val);
					$datas [] = $area_val;
				}
			}
		}
		$this->template_name = $template_name;
		// dump($datas);die; //数据按顺序输出
		$template = M ( 'template' );
		$contents = '';
		$template_name_area = $template->where ( "template_name='$template_name'" )->getField ( 'area' );
		$template_name_datas = json_decode ( $template_name_area, true );
		// dump($template_name_datas);die;
		// dump(count($template_name_datas));die;
		for($i = 0; $i < count ( $template_name_datas ); $i ++) {
			$prefix = substr ( $template_name_datas [$i], 0, 3 );
			if ($prefix == 'txt') {
				$content_val = substr ( $template_name_datas [$i], 3 );
				$contents = $contents . "<div class='widget-main'>
                  <!-- Text input-->
                  <label class='control-label' for='startpoint'>{$content_val}</label>
                  <div class='controls'>
                  
                    <input class='form-control'  type='text' value={$datas[$i]}>
                    <p class='help-block'></p>
                  </div>
                                    </div>";
			} else {
				$content_val = substr ( $template_name_datas [$i], 3 );
				$contents = $contents . "<div class='widget-main'>
        		<!-- Text input-->
        		<label class='control-label' for='startpoint'>{$content_val}</label>
        		   
          <div class='controls'>
           
            <img src='/upload{$datas[$i]}' width='100px' height='100px'/>
          </div>
        
        		</div>";
			}
		}
		$this->content = $contents;
		$this->display ( 'display_template_area' );
	}
	/*
	 * ajax 传值，返回数据
	 */
	public function getDatas() {
		$uid = get_user_id ();
		dump ( $uid );
		die ();
		$rst = D ( "Hospital" )->where ( array (
				'hos_name' => $_REQUEST ['username'] 
		) )->find ();
		if ($rst) {
			echo "有";
		} else {
			echo "无";
		}
	}
}
