<?php
namespace Home\Controller;
use Think\Controller;



class ApiconfigController extends Controller{
	
	/**
	 * 用户登录时接口列表页面显示
	 */
	public function manager()
	{
	     if (true == is_login()) {
	     	$uid = get_user_id();
	     	$user_type = get_user_type();
            $this->page_id = 10;
            $api_list = M('api_list');
	     	if(0 == $user_type)
        	{
        		$arrange = "api_uid=$uid";
        	}else
        	{
        		$arrange = "";
        	}
            $list = $api_list->where($arrange)->order("createtime desc")->select();
            $this->assign('list', $list); // 赋值数据集
            
            $this->display();
        } else {
            $this->__userNotLogin();
        }
	}
	
	/**
	 * 用户登录时编辑页面显示
	 * @param unknown_type $api_id
	 */
	public function edit($api_id=0)
	{
	   if (true == is_login())
	    {
		// 登录情况下
	    	if(0 == $api_id)
	    	{
			// api_id异常
	    		$this->display('manager');
	    		return;
	    	}
			// 数据加载
	    	$api = M('api_list');
	    	$api_list = $api->where("api_id=$api_id")->find();
	    	$this->api_id = $api_list['api_id'];
	    	$this->api_uid = $api_list['api_uid'];
	    	$this->api_login = $api_list['api_login'];
	    	$this->api_weixin = $api_list['api_weixin'];
	    	$this->api_password = $api_list['api_password'];
	    	$this->api_encodemode = $api_list['api_encodemode'];
	    	$this->api_encode_key = $api_list['api_encode_key'];
	    	$this->api_weixin_appid = $api_list['api_weixin_appid'];
	    	$this->api_weixin_token = $api_list['api_weixin_token'];
	    	$this->api_weixin_aes_key = $api_list['api_weixin_aes_key'];
	    	$this->api_weixin_pay_account = $api_list['api_weixin_pay_account'];
	    	$this->api_weixin_pay_key = $api_list['api_weixin_pay_key'];
	    	$this->api_weixin_client_ip = $api_list['api_weixin_client_ip'];
	    	
	    	$this->api_other_url = $api_list['api_other_url'];
	    	$this->api_user_ips = $api_list['api_user_ips'];
	    	$this->createtime = $api_list['createtime'];
			// 最后做数据渲染
	    	$this->display();
        } else 
        {
            $this->__userNotLogin();
        }
	}
	
	  // 添加接口配置
    public function newone_deal() 
    {
    	  if (IS_POST) 
    	  {
            if (true == is_login()) 
            {
	    	//初始化接口id，不可修改。系统随机
    		$api_id= I('post.api_id');
    		$api_login= I('post.api_login');
    		$api_password = I('post.api_password');
    		$api_weixin_appid = I('post.api_weixin_appid');
    		
    		$api_encodemode = I('post.api_encodemode');
    		$api_weixin_token = I('post.api_weixin_token');
    		$api_weixin_aes_key = I('post.api_weixin_aes_key');
    		$api_other_url = I('post.api_other_url');
    		$api_weixin_pay_account = I('post.api_weixin_pay_account');
    		$api_weixin_pay_key = I('post.api_weixin_pay_key');
    		$api_weixin_client_ip = I('api_weixin_client_ip');
    		
    		do 
    		{
    		$api = M('api_list');
    		// 获取当前用户id
    		$api_id = mt_rand(10000,99999);
    		$data['api_uid'] = get_user_id();
    		$data['api_id'] = $api_id;
    		$data['api_login'] =get_user_name();
    		$data['api_password'] = $api_password;
    		$data['api_encodemode'] = $api_encodemode;
    		$data['api_weixin_appid'] = $api_weixin_appid;
    		
    		$data['api_weixin_token'] = $api_weixin_token;
    		$data['api_weixin_aes_key'] = $api_weixin_aes_key;
    		$data['api_other_url'] = $api_other_url;
    		$data['createtime'] = date('Y-m-d H:i:s');
    		$data['api_weixin_pay_account'] = $api_weixin_pay_account;
    		$data['api_weixin_pay_key'] = $api_weixin_pay_key;
    		$data['api_weixin_client_ip'] = $api_weixin_client_ip;
    		
    		$result = $api->data($data)->add();
    		
            if (null == $result) 
            {
                        $this->errorinfo = '创建出错！';
                        break;
            }
    		redirect('/apiconfig/manager');
                    return;
    		}while(0);
    		
    		$this->api_id = $api_id ;
    		$this->api_login = $api_login;
    		$this->api_password = $api_password;
    		$this->api_encodemode = $api_encodemode;
    		$this->api_weixin_appid = $api_weixin_appid;
    		
    		$this->api_weixin_token = $api_weixin_aes_key;
    		$this->api_weixin_aes_key = $api_weixin_aes_key;
    		$this->api_other_url = $api_other_url;
    		$this->api_weixin_pay_account = $api_weixin_pay_account;
    		$this->api_weixin_pay_key = $api_weixin_pay_key;
    		$this->api_weixin_client_ip = $api_weixin_client_ip;
    		
    		$this->display('newone');
            }
    	  }
    }
    
	  // 修改接口配置
    public function edit_deal($api_id = 0) 
    {
    	  if (IS_POST) 
    	  {
            if (true == is_login()) 
            {
	    	//初始化接口id，不可修改。系统随机
     		$api_id= I('post.api_id');
    		$api_login= I('post.api_login');
    		$api_password = I('post.api_password');
    		$api_weixin_appid = I('post.api_weixin_appid');
    		
    		$api_encodemode = I('post.api_encodemode');
    		$api_weixin_token = I('post.api_weixin_token');
    		$api_weixin_aes_key = I('post.api_weixin_aes_key');
    		$api_other_url = I('post.api_other_url');
    		$api_weixin_pay_account = I('post.api_weixin_pay_account');
    		$api_weixin_pay_key = I('post.api_weixin_pay_key');
    		$api_weixin_client_ip = I('post.api_weixin_client_ip');
    		
    		$api = M('api_list');
    		// 获取当前用户id
    		
    		$data['api_id'] = $api_id;
    		$data['api_uid'] = get_user_id();
    		$data['api_login'] = get_user_name();
    		$data['api_password'] = $api_password;
    		$data['api_encodemode'] = $api_encodemode;
    		$data['api_weixin_appid'] = $api_weixin_appid;
    		
    		$data['api_weixin_token'] = $api_weixin_token;
    		$data['api_weixin_aes_key'] = $api_weixin_aes_key;
    		$data['api_other_url'] = $api_other_url;
    		$data['createtime'] = date('Y-m-d H:i:s');
    		$data['api_weixin_pay_account'] = $api_weixin_pay_account;
    		$data['api_weixin_pay_key'] = $api_weixin_pay_key;
    		$data['api_weixin_client_ip'] = $api_weixin_client_ip;
    		
    		$api_id= I('post.api_id');
    		$condition['api_id'] = $api_id;
    		$result = $api->where($condition)->save($data);
                    if (null == $result) {
                        $this->error('修改出错');
                        
                    }else{
    					redirect('/apiconfig/manager');
                    }
                   
            }
    	  }
    }
    
	
    public function newone() {
        if (true == is_login()) {
        	// 进入添加页面时随机api_id并向页面输出
	    	
            $this->api_id = $api_id;
            $this->api_login = get_user_name();
            $this->display();
        } else {
            $this->__userNotLogin();
        }
    }
	
}
?>