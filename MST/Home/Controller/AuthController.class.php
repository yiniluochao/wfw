<?php
namespace Home\Controller;
use Think\Controller;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestController
 *
 * @author Kuangfuabc
 */
class AuthController extends Controller
{
	
	public function index()
	{
		//显示用户关键信息列表，操作栏修改。
		  if (true == is_login()) {
		$company_user = M('company_user');
		$count = $company_user->count();
		   $Model = new \Think\Model();
		      $Page = new \Think\Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数(25)
//            $Page->rollPage = 8;
            $list =  $Model->query("select * from __PREFIX__company,__PREFIX__company_user where __PREFIX__company.uid=__PREFIX__company_user.uid  order by __PREFIX__company.createtime desc ");
//            $list =  $Model->query("select * from __PREFIX__company,__PREFIX__company_user where __PREFIX__company.uid=__PREFIX__company_user.uid  order by __PREFIX__company.createtime desc limit $Page->firstRow, $Page->listRows");
	 		$this->assign('list', $list); // 赋值数据集
//            $this->assign('page', $show); // 赋值分页输出
            $this->display(); // 输出模板
	}else {
            $this->__userNotLogin();
        }
	}
	
	
  public function search() {

        if (true == is_login()) {
            $this->page_id = 3;

            $search_key = I('post.key');

            if (empty($search_key)) {
                $this->error("关键字为空!");
                return;
            }

    
            $where['company_name'] = array('like', "%$search_key%");
            $where['original_code'] = array('like', "%$search_key%");
            
            $where['_logic'] = 'or';
            $company = M('company');
            
            $list = $company->where($where)->select();
            
            if ($list == NULL||$list==false) {
                
                $this->error("没有找到合适的");
            } else {
                $this->list = $list;
                $this->display('index');
           
            }
        }
    }
	
    public function edit($uid = 0)
    {
    	    if (true == is_login()) 
    	    {
    	    	if(get_user_type()==1)
    	    	{
    	    		$company = M('company');
    	    		$user = M('company_user');
    	    		$company_data = $company->where("uid=$uid")->find();
    	    		$user_data = $user->where("uid=$uid")->find();
    	    		
    	    		$this->uid = $uid ;
					$this->login = $user_data['login'];
    	    		$this->auto_approval = $user_data['auto_approval'];
    	    		$this->state = $user_data['state'];
					 $this->company_name = $company_data['company_name'];
					 $this->display();
    	    	}
    	    }
    }
    
  public function edit_deal($uid = 0)
    {
    	    if (true == is_login()) 
    	    {
    	    	if(get_user_type()==1)
    	    	{
    	    	do {
                    $ok = 1;
    	    		$auto_approval= I('auto_approval');
    	    		$state = I('state');
    	    		$data['auto_approval'] = $auto_approval;
    	    		$data['state'] = $state;
    	    		$company_user = M('company_user');
    	    		$result = $company_user->where("uid=$uid")->save($data);
                 	
                    if(!empty($result))$ok = 0;         
                    redirect( "/auth/index/");
                    return;
                } while ($ok);
    	    		
    	    	$this->auto_approval = $auto_approval;
                $this->display('edit');
    	    	}
    	    }else {
            $this->__userNotLogin();
        }
    }
    
    
}
