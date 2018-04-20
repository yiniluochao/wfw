<?php

/**
 * 批次控制器，控制批次的添加界面及响应，管理界面及响应，编辑界面及响应
 *
 * @author Kuangfuabc
 */
namespace Home\Controller;

use Think\Controller;
class PecorderController extends Controller{
    //put your code here
      //未登录[页面跳转]
    protected function __userNotLogin() {
        $this->error("亲还没有登录呢!", "/");
    }

    //未准备[页面跳转]
    protected function __NotReady() {
        $this->error("本功能还没有完工!", "/");
    }

    // 显示首页
    public function index() {
    	$this->user_name = get_user_name(); 
        $this->display();
    }
    // 订单展示
    public function view($pec_order_id = 0) {
    	// 如果登录状态则向模板输出pid,bid
        if (true == is_login()) {
            $this->page_id = 5;
            if(1 == get_user_type())
	        {
	        	$this->mode = "";
	        }
	        else 
	        {
	        	$this->mode = "disabled";
	        }  
	        
             $pec_order = M('pec_order'); // 实例化
             $result  = $pec_order->where("pec_order_id=$pec_order_id")->find();
             $bid = $result['bid'];
             
              $batch = M('batch'); // 实例化
              $batch_code = $batch->where("bid = $bid")->getField('batch_code');
              if(null == $batch_code){
              	$batch_code = M('template_area')->where("bid='$bid'")->getField('batch_code');
              	if(null == $batch_code){
              		$this->error('系统出错，请与管理员联系!');
              	}
              }
              $this->batch_code = $batch_code;
              $this->bid= $bid;
              $this->pid = $batch->where("bid = $bid")->getField('pid');
            if ($result == null) {
                $this->error("不存在订单信息");
            }
            else {
            	$this->pec_order_id = $pec_order_id;
            	$this->createtime = $result['createtime'];
            	$this->sum = $result['sum'];
            	$this->pec_order_code =  $result['pec_order_code'];
            	if($result['state'] == 0){
            		$this->state = "未审核";
            	}else if($result['state'] == 2){
            		$this->state = "已生成,可下载";
            	}else if($result['state'] == 1){
            		$this->state = "已生成,可下载";
            	}
            	
            	$this->pec_order_id = $result['pec_order_id'];
            	$this->number = $result['number'];
				$this->hongbao_state = ($result['type'] == 1)?"红包功能开启":"红包功能关闭";
                $this->display();
            }
        }
    }
    
    
    public function edit($pec_order_id = 0)
    {
    // 如果登录状态则向模板输出pid,bid
        if (true == is_login()) {
            $this->page_id = 5;
	            
             $pec_order = M('pec_order'); // 实例化
             $result  = $pec_order->where("pec_order_id=$pec_order_id")->find();
             //dump($result);
             $bid = $result['bid'];
            // dump($bid);die;
              $batch = M('batch'); // 实例化
              $batch_code = $batch->where("bid = $bid")->getField('batch_code');
             // dump($batch_code);die;
              $this->batch_code = $batch_code;
              $this->bid= $bid;
              $this->pid = $batch->where("bid = $bid")->getField('pid');
            if ($result == null) {
                $this->error("不存在批次信息");
            }
            else {
            	$this->pec_order_id = $pec_order_id;
            	$this->createtime = $result['createtime'];
            	$this->sum = $result['sum'];
            	$this->pec_order_code =  $result['pec_order_code'];
            	$this->state = $result['state'];
            	$this->pec_order_id = $result['pec_order_id'];
            	if(1 == $result['type']){
            		$this->repair_on = "checked";
            	}else{
            		$this->repair_off = "checked";
            	}
            	      if(1 == get_user_type())
	        {
	        	$this->mode = "";
	        	
	        }else 
	        {
	        	/*
	        	 * 此处修改，解除修改后   产品身份码数量及订单状态归0的状态
	        	 */
	        	//$this->mode = "disabled";
	        	$state = $result['state'];
            	/*if($state > 0)
            	{
					$can_edit_sum="disabled";      	
            	}else 
            	{
            		$can_edit_sum="";
            	}*/
            	//$this->can_edit_sum = $can_edit_sum;
	        }  
                $this->display();
            }
        }
    }
    
    public function  edit_deal($pec_order_id=0)
    {
    	
         if (IS_POST) {
            if (true == is_login()) {
               
                // 取数据         
				$sum = I('post.sum');                
				$state = I('post.state');
				$ison = I('post.ison');
				
				$ison = substr($ison, 0,1);
				//dump($ison);die;
				//dump($sum);                        
				//dump($state);die;
				
                if($pec_order_id == 0)
                {
                     $this->errorinfo = '必须对应订单';
                }          
               
                do {
                    //写数据库
                    $ok = 1;
                 	
                 	
                    $data['sum'] = $sum;
                    $data['state'] = $state;
                    $data['type'] = ($ison == 1)?1:0;
                    //dump($data['type']);die;
                	$pec_order = M('pec_order');
                    $result = $pec_order->where("pec_order_id=$pec_order_id")->save($data);   
                    if(!empty($result))$ok = 0;         
                    redirect( "/pecorder/view/$pec_order_id");
                    return;
                } while ($ok); 

                // 模板输出
 
                $this->sum = $sum;
                $this->state = $state;
                $this->display('edit');
            }
        }
    	
    }
    /**
     * @deprecated 根据产品名称获得批次代码
     */
    public function getBatchCodeByProductCode($product_name = null){
    	$cid = get_user_id();
    	$pid = M('product')->where("product_name = '$product_name' and cid = '$cid'")->getField("pid");
    	$return = M('batch')->where("pid = '$pid'")->getField('batch_code',true);
    	echo json_encode($return);
    }
     // 调转到添加批次
    public function newone() {
        if (true == is_login()) {
            $this->page_id = 6;
          /*  if($bid==0||$bid==NULL)
            {
                redirect('/pecorder/notice');  //屏蔽，修改为允许跳转
                return;
            }
            $this->bid = $bid;
            $batch = M('batch');
            $batch_code = $batch->where("bid=$bid")->getField('batch_code');
            if(null == $batch_code){//自定义批次模板 bid,暂时屏蔽
            	$batch_code = M('template_area')->where("bid=$bid")->getField('batch_code');
            }*/
            $uid = get_user_id();
            $result = M('product')->where("cid = '$uid'")->select();
           // dump($result);
            $this->plist = $result;
            $record_number = M('record_number');
            $condition['uid'] = get_user_id();
            $uid = get_user_id();
            $date_result = $record_number->where("uid=$uid")->getField('date');
            //dump($date_result);die;
            $now_date = date("Y-m-d");
            //dump($now_date);die;
            $remain_number = M('remain_number');
            $remain_uid = $remain_number->where("date='$now_date'")->getField('uid',true);
            //dump($remain_uid);die;
            $result_equal = is_equal_uid($remain_uid,$uid);         
            if(null == $result_equal){//没有相同的uid
            if($now_date != $date_result){//初始化位数余量记录表
            //dump(1);die;
            $remain_number = M('remain_number');
            $data['uid'] = get_user_id();
            $data['eight'] = 1000;
            $data['nine'] = 10000;
            $data['ten'] = 100000;
            $data['eleven'] = 1000000;
            $data['twelve'] = 10000000;
            $data['date'] = date("Y-m-d");
            $error = $remain_number->data($data)->add();
            //dump($error);die;
            $condition_else['uid'] = get_user_id();
            $condition_else['date'] = date("Y-m-d");
            $list = $remain_number->where($condition_else)->getField('eight,nine,ten,eleven,twelve');
            $this->assign('list',$list);
            }
        }
            else{
            	$condition_else['uid'] = get_user_id();
            	$condition_else['date'] = date("Y-m-d");
            	//dump($condition_else);die;
            	$remain_number = M('remain_number');
            	$list = $remain_number->where($condition_else)->getField('eight,nine,ten,eleven,twelve');
            	//dump($result_array);die;
            }
            $this->assign('list',$list);
            $this->batch_code = $batch_code;
            
            $this->display();
        } else {
            $this->__userNotLogin();
        }
    }
    //上传组件
    function upload()
    {
    	$upload = new \Think\Upload();// 实例化上传类
    	$rand = uniqid().rand(1, 10000);
    	$upload->maxSize = 3145728 ;// 设置附件上传大小
    	$upload->exts = array('txt');// 设置附件上传类型
    	$upload->rootPath = './upload/'; // 设置附件上传根目录
    	$upload->savePath = '/pecorder/';
    	$upload->saveHash = false;
    	$upload->saveName = "file-".$rand;
    	// 上传文件
    	$info = $upload->upload();
    
    	//dump($info);die;
    	
    
    	foreach($info as $file){
    		return $file['savepath'].$file['savename'];
    	}
    }
    
// 添加订单处理
    public function newone_deal() {
    	
    	

        if (IS_POST) {
            if (true == is_login()) {
            	//判断
            	$product_name = I('product_code');
            	if($product_name == null){
            		$this->error('请选择产品');
            	}
            	$batch_code = I('batch_code');
            	//dump($batch_code);die;
            	$uid = get_user_id();
            	if($batch_code == 'auto'){
            		$pid = M('product')->where("cid = '$uid' and product_name = '$product_name'")->getField('pid');
            		dump($pid);
            		$batch_code = 'auto_' . date('YmdHis') . rand(1000, 9999);
            		$ddata['pid'] = $pid;
            		$ddata['batch_code'] = $batch_code;
            		$ddata['createtime'] = date("Y-m-d H:i:s");
            		$ddata['cid'] = $uid;
            		$ddata['startpoint'] = 'SystemDefault';
            		$ddata['sellpoint'] = 'SystemDefault';
            		$ddata['retail_price'] = 'SystemDefault';
            		M('batch')->add($ddata);
            		
            	}
            	$sum = I('post.sum'); //下载身份码数量
            	//dump($sum);die;
            	$number = I('post.rtl'); //用户下载的电码位数
            	$ison = I('ison');
            	
            	if($ison == 'on'){
            		$type = 1;
            	}else{
            		$type = 0;
            	}
            	$file_url = $this->upload();
            	//dump($file_url);
            	if($file_url != null){ //上传订单号
            		//dump(1);die;
            		$uid = get_user_id();
            		$pid = M('product')->where("cid = '$uid' and product_name = '$product_name'")->getField('pid');
            		//dump($pid);
            		$bid = M('batch')->where("pid = '$pid' and batch_code = '$batch_code'" )->getField('bid');
            		//dump($bid);
            		$data['cid'] = get_user_id();
            		$data['bid'] = $bid;
            		$data['batch_code'] = $batch_code;
            		$data['createtime'] = date("Y-m-d H:i:s");
            		$data['type'] = $type;
            		$data['file_url'] = $file_url;
            		$a = mt_rand(10000000,99999999);
            		$b = mt_rand(10000000,99999999);
            		$pec_order_code = $a.$b;
            		$data['pec_order_code'] = $pec_order_code;
            		$pec_order = M('pec_order');
            		$pec_order_id = $pec_order->data($data)->add();
            		
            		$content = file_get_contents('./upload'.$file_url);
            		//echo $content;
            		
            		$array = explode("\r\n", $content);
            		
            		foreach( $array as $k=>$v){
            			if( !$v )
            				unset( $array[$k] );
            		}
            		$uid = get_user_id();
            		$table_name="__PREFIX__pec".$uid;
            		
            		//判断是否存在$uid此用户的表，如果没有，则创建,并将表名放入company_user中
            		if(!is_exist_table($table_name)){
            			dynamic_create_table($uid);
            		}
            		
            		$table = "pec".$uid;
            		$random_code = M($table)->where("pec_dataPrefix_code = 0")->getField('pec_random_code',true);
            		//dump($random_code);
            		foreach ($random_code as $val){
            			$arr = array_merge(json_decode($val),$array);
            			//dump($arr);
            			$num1 = count($arr);//提取该数组的数目
            			$arr2 = array_unique($arr);//合并相同的元素
            			$num2 = count($arr2);//提取合并后数组个数
            			if($num1>$num2)//判断下大小
            			$this->error("该文件与已上传文件含有重复元素");
            					
            		}
            		$values['pec_dataPrefix_code'] = 0;
            		$values['pec_order_id'] = $pec_order_id;
            		$values['pec_random_code'] = json_encode($array);
            		M($table)->add($values);
            		
            		if(!empty($pec_order_id))
            		redirect( "/pecorder/manager");
            		
            	}
            	//dump($product_name);
            	//dump($batch_code);
            	//dump($sum);
            	//dump($number);
            	$uid = get_user_id();
            	$pid = M('product')->where("cid = '$uid' and product_name = '$product_name'")->getField('pid');
            	$bid = M('batch')->where("pid = '$pid'")->getField('bid');
            	
            	if(!is_numeric($sum) || $sum<0){
            		$this->error('你输入的数量类型不合法，请重新输入!');
            	}
            	$date = date("Y-m-d");
            	$uid = get_user_id();
            	$remain_number = M('remain_number');
            	$condition_panduan['uid'] = $uid;
            	$condition_panduan['date'] = $date;
            	if($number == 8){
            		$remian_result = $remain_number->where($condition_panduan)->getField('eight');
            		//dump($remian_result);die;
            		if($sum>$remian_result){
            			$this->error('抱歉，您的订单8位数量已超过限制，请重新填写或更换产品身份码位数!');die;
            		}
            	}
            	if($number == 9){
            		$remian_result = $remain_number->where($condition_panduan)->getField('nine');
            		if($sum>$remian_result){
            			$this->error('抱歉，您的订单数量已超过限制，请重新填写或更换产品身份码位数!');die;
            		}
            	}
            	if($number == 10){
            		$remian_result = $remain_number->where($condition_panduan)->getField('ten');
            		if($sum>$remian_result){
            			$this->error('抱歉，您的订单数量已超过限制，请重新填写或更换产品身份码位数!');die;
            		}
            	}
            	if($number == 11){
            		$remian_result = $remain_number->where($condition_panduan)->getField('eleven');
            		if($sum>$remian_result){
            			$this->error('抱歉，您的订单数量已超过限制，请重新填写或更换产品身份码位数!');die;
            		}
            	}
            	if($number == 12){
            		$remian_result = $remain_number->where($condition_panduan)->getField('twelve');
            		if($sum>$remian_result){
            			$this->error('抱歉，您的订单数量已超过限制，请重新填写或更换产品身份码位数!');die;
            		}
            	}
              
               
				
				
				//dump($number);die;               
				$cid = get_user_id();
                if($bid == 0)
                {
                     $this->errorinfo = '必须对应批次';
                }          
				$batch = M('batch');
				$pid = $batch->where("bid = $bid")->getField('pid');
				if(null == $pid){//自定义批次模板 pid
					$pid = M('template_area')->where("bid='$bid'")->getField('pid');
					if(empty($pid)){
						$this->errorinfo = '所属产品不能为空';
						$ok = 0;
						break;
					}
				}
				$ison = I('ison');
				//dump($ison);die;
				if("on" == $ison){
					$type = 1;
				}else{
					$type = 0;
				}
				
               
                do {
                    //写数据库
                    $ok = 1;
                    if (empty($batch_code)) {
                        $this->errorinfo = '批次代码不能为空';
                        $ok = 0;
                        break;
                    }
                    if (empty($pid)) {
                        $this->errorinfo = '所属产品不能为空';
                        $ok = 0;
                        break;
                    }
                 	
               	
                    $data['sum'] = $sum;
                    $data['cid'] = $cid;
                    $data['pid'] = $pid;
                    $data['batch_code'] = $batch_code;
                    $data['number'] = $number;
                    $data['type'] = $type;
                	$uid = get_user_id();
                	$company_user = M('company_user');
			    	$auto_approval = $company_user->where("uid = $uid")->getField('auto_approval');
			    	//1则自动生成，0否则什么也不做
			    	if(1 ==  $auto_approval)
			    	{
                    	$data['state'] = 2;
			    	}else 
			    	{
			    		$data['state'] = 0;
			    	}
                    $data['bid'] = $bid;
                    $data['createtime'] = date('Y-m-d H:i:s'); //当前服务器时间[默认是北京时间，如果国际化，要进行时区处理                    
                    
                    
                    $a = mt_rand(10000000,99999999);
                	$b = mt_rand(10000000,99999999);
                	$pec_order_code = $a.$b;
                	$data['pec_order_code'] = $pec_order_code;
                	$pec_order = M('pec_order');
                    $pec_order_id = $pec_order->data($data)->add();   
                    if(!empty($pec_order_id))$ok = 0;         
                    redirect( "/pecorder/view/$pec_order_id");
                    return;
                } while ($ok);

                // 模板输出
                $this->batch_code = $batch_code;
                $this->sum = $sum;
                $this->display('newone');
            }
        }
    }
    
    // 产品身份码订单管理
    public function manager($bid = 0) {
            // 是否登录
            //dump($bid);
          //  dump(I('get.'));
            //$var = I('get.');
           //dump($var['1']);
           //$bid = $var['1'];
            $bid = I('get.bid');//get方法
            //die();
            if (true == is_login()) {
            //设置光标
            $this->page_id = 7;
            // 实例化对象
            $batch = M('Batch'); 
            $pec_order = M('pec_order');
            $product = M('product');
            $user_type = get_user_type();
			$uid = get_user_id();        
                    if(0 == $user_type)
		        	{
		        		$sql_ex = " and __PREFIX__pec_order.cid = $uid";
		        	}else
		        	{
		        		$sql_ex = "";
		        	}	
            // 如果直接进入则查询该公司下所有产品身份码订单
		        	
            if($bid==null||$bid==0)
            {
           			//
		        	// 查询个数，分页显示
                    $Model = new \Think\Model();
                    $count = $pec_order->where("cid='$uid'")->count();
                    //dump($uid);die;
                    $Page = new \Think\Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数(25)
           			$Page->rollPage = 8;
           			$show = $Page->show(); // 分页显示输出
           			
           			//select * from mst_batch,mst_pec_order,mst_product where mst_product.pid = mst_batch.pid and mst_batch.bid = mst_pec_order.bid and mst_batch.cid = 1
                    $list =  $Model->query("select * from __PREFIX__batch,__PREFIX__product,__PREFIX__pec_order where __PREFIX__product.pid = __PREFIX__batch.pid and __PREFIX__batch.bid = __PREFIX__pec_order.bid $sql_ex order by __PREFIX__pec_order.createtime desc");
//                    $list =  $Model->query("select * from __PREFIX__batch,__PREFIX__product,__PREFIX__pec_order where __PREFIX__product.pid = __PREFIX__batch.pid and __PREFIX__batch.bid = __PREFIX__pec_order.bid $sql_ex order by __PREFIX__pec_order.createtime desc limit $Page->firstRow , $Page->listRows");
                 // dump($list);die;
                    $count=count($list);//得到数组元素个数
                    
                    $Page= new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数
                    $list = array_slice($list,$Page->firstRow,$Page->listRows);
                  //  dump($list);die;
                    foreach ($list as &$val){
                    	if($val['file_url'] != null){
                    		$val['sum'] = '外部导入文件';
                    	}
                    	if($val['startpoint'] == 'SystemDefault'){
                    	//	dump($val['startpoint']);die;
                    		$val['batch_code'] = $val['batch_code'].'(auto)';
                    	}
                    }
                    $this->assign('list', $list); // 赋值数据集
                    $this->assign('page', $show); // 赋值分页输出
                    $this->display(); // 输出模板
            }else
            {
           		 //如果提供了批次数据，则整理好批次，产品数据，进行查询分页显示
                $batch_code = M('batch')->where("bid='$bid'")->getField('batch_code');
                if(null == $batch_code){//不是默认模板的数据,是自定义批次模板的数据
                	$this->bid = $bid;
                	
                	$Model = new \Think\Model();
                	$count = $pec_order->where("bid = '$bid' and cid = '$uid' ")->count(); // 查询满足要求的总记录数
                	// dump($count);die;
                	$Page = new \Think\Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数(25)
                	$Page->rollPage = 8;
                	$show = $Page->show(); // 分页显示输出
                	//dump($show);die;//等一下   不是这里  
                	// $sql = 'select a.id,a.title,b.content from think_test1 as a, think_test2 as b where a.id=b.id '.$map.' order by a.id '.$sort.' limit '.$p->firstRow.','.$p->listRows;
                	//模板内容联查
                	$sql =  'select * from __PREFIX__template_area,__PREFIX__product,__PREFIX__pec_order where __PREFIX__product.pid = __PREFIX__template_area.pid and __PREFIX__template_area.bid = __PREFIX__pec_order.bid and __PREFIX__template_area.bid ='. $bid . ' order by __PREFIX__pec_order.createtime desc limit'.' '. $Page->firstRow .','. $Page->listRows ;
                	
                	$list =  $Model->query($sql);
                	//dump($list);
                	// $list =  $Model->query("select * from __PREFIX__batch,__PREFIX__product,__PREFIX__pec_order where __PREFIX__product.pid = __PREFIX__batch.pid and __PREFIX__batch.bid = __PREFIX__pec_order.bid and __PREFIX__batch.bid = $bid $sql_ex order by __PREFIX__pec_order.createtime desc ");
                	//dump($list);die;
                	foreach ($list as &$val){
                		if($val['file_url'] != null){
                			$val['sum'] = '外部导入文件';
                		}
                		if($val['startpoint'] == 'SystemDefault' ){
                			$val['batch_code'] = $val['batch_code'].'(auto)';
                		}
                	}
                	//dump($show);die;
                	$this->assign('list', $list); // 赋值数据集
                	$this->assign('page', $show); // 赋值分页输出
                	$this->display(); // 输出模板
                }else{
            	
            	$this->bid = $bid;
              
                $Model = new \Think\Model();
               // $count = $pec_order->where("bid = '$bid' and cid = '$uid' ")->count(); // 查询满足要求的总记录数
               // dump($count);die;
             //   $Page = new \Think\Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数(25)
           //		$Page->rollPage = 8;
                //$show = $Page->show(); // 分页显示输出
                //dump($show);die;
               
               // $sql =  'select * from __PREFIX__batch,__PREFIX__product,__PREFIX__pec_order where __PREFIX__product.pid = __PREFIX__batch.pid and __PREFIX__batch.bid = __PREFIX__pec_order.bid and __PREFIX__batch.bid ='. $bid . ' order by __PREFIX__pec_order.createtime desc limit'.' '. $Page->firstRow .','. $Page->listRows ;
                $sql =  'select * from __PREFIX__batch,__PREFIX__product,__PREFIX__pec_order where __PREFIX__product.pid = __PREFIX__batch.pid and __PREFIX__batch.bid = __PREFIX__pec_order.bid and __PREFIX__batch.bid ='. $bid . ' order by __PREFIX__pec_order.createtime desc';
                //dump($sql);die;
                $list =  $Model->query($sql);
          		//dump($list);die;
          		$count=count($list);//得到数组元素个数
          		
          		//dump($count);die;
          		$Page= new \Think\Page($count,8);// 实例化分页类 传入总记录数和每页显示的记录数
          		$list = array_slice($list,$Page->firstRow,$Page->listRows);
          		//dump($list);
          		//dump($Page->firstRow);
          		//dump($list);die;
          		//dump(I('get.'));die();
          		foreach ($list as &$val){
          			if($val['file_url'] != null){
          				$val['sum'] = '外部导入文件';
          			}
          			if($val['startpoint'] == 'SystemDefault'){
          				$val['batch_code'] = $val['batch_code'].'(auto)';
          			}
          		}
          		$show= $Page->show();// 分页显示输出﻿
          		//dump($show);die;
          		$this->page = $show;
          		$this->list = $list; // 赋值数据集
           	    // 赋值分页输出
            	$this->display(); // 输出模板*/
                }
        }
            } else {
            $this->__userNotLogin();
        }
    }
    // 搜索产品身份码订单
    public function search() {

        if (true == is_login()) {
            $this->page_id = 5;

            $search_key = I('post.key');

            if (empty($search_key)) {
                $this->error("关键字为空!");
                return;
            }
            $uid = get_user_id();
            $Model = new \Think\Model();     
            $list = $Model->query("select * from __PREFIX__batch,__PREFIX__product ,__PREFIX__pec_order where __PREFIX__batch.bid=__PREFIX__pec_order.bid and   __PREFIX__batch.pid=__PREFIX__product.pid and __PREFIX__batch.cid=$uid and __PREFIX__pec_order.pec_order_code  like '%$search_key%' ");
            
            if ($list == NULL||$list==false) {
                $this->error("没有找到合适的");
            } else {
                $this->list = $list;
                $this->display('manager');
            }
        }
    }
    
    public function download($sum = 0,$pec_order_id = 0,$number = 0)
    {
    	
    	 if (true == is_login()) {
    	 	$api_uid = get_user_id();
    	 	//dump($api_uid);die;
    	 	$api_list = M('api_list');
    	 	$condition_api['api_uid'] = $api_uid;
    	 	$api_id = $api_list->where($condition_api)->getField('api_id');
    	 	if(null == $api_id){
    	 		$this->error("请配置接口管理");
    	 	}
	  	 	$temp=0;
    	 	while($temp==0){
    	 	  $type = get_user_type();
    	 	  $uid = get_user_id();
    	 	  $table_name="__PREFIX__pec".$uid;
    	 	 
    	 	  //判断是否存在$uid此用户的表，如果没有，则创建,并将表名放入company_user中
    	 	  if(!is_exist_table($table_name)){
    	 	  	if(dynamic_create_table($uid)){
    	 	  		break;
    	 	  	}
    	 	  	
    	 	  }//die('此表已存在');
    	// 判断该订单的state是否为2，为2则进行生成
    	  $pec_order = M('pec_order'); // 实例化
          $result  = $pec_order->where("pec_order_id=$pec_order_id")->find();
          if($result== null)
          {
           $this->error("订单异常");
          }
          // 若非系统管理员，该用户是否具有自动审批权限有则该state为2，没有则需等待管理员手动审批
          if($result['state'] > 0||$type==1)
          {
          $bid = $result['bid'];//取出批次号
          $batch = M('batch'); // 实例化
          $pid = $batch->where("bid = $bid")->getField('pid');//通过批次号  取出产品号
          if(null == $pid){
          	$pid = M('template_area')->where("bid='$bid'")->getField('pid');
          }
          //trace($sum.'--'.$pid.'--'.$pec_order_id);
         // $this->display('view');
        /* if($sum > 500000)
         {
         	 $this->error("单次申请上限500000，若量大请多次申请");
         	 $this—>display('view');
         }*/
         	
         	
         	
         //尝试生成产品身份码
//         $thread = new \Think\thread();
//         $thread->addThread('get_pec','$sum', '$pid', '$pec_order_id');
//         $thread->runthread();
			//dump($sum);dump($pid);dump($pec_order_id);dump($uid);dump($number);die;
 	      $gen_result = get_pec_file($sum, $pid, $pec_order_id,$uid,$number);
 	      if(!$gen_result)
 	      {
 	      	 $this->error("生成失败！今日申请电码数已超过1000000,请明天再申请");
         	 $this—>display('view');
 	      }else{
 	      
 	      
 	      }
          }else 
          {
          	 $this->error("订单尚未通过审核，请耐心等待");
         	 $this—>display('view');
          }
    	 	}
    	 }
    	 
    	 else {
            $this->__userNotLogin();
        }
    }
    
   
}
