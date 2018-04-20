<?php
namespace Home\Controller;
use Think\Controller; 


class UserController extends Controller {
    
   
    //登录界面
    public function login()
    {
        $this->display();
    }
       //注销
     public function loginout() {
         session('logined',null);
         session('userid',null);
         session('username',null);
         session('type',null);
         session('cid',null);
         redirect('/user/login');
    }
    // 用户登录
    // 取数据，验证密码
    public function loginIn()
    {
        // 取数据
       $login = I('post.login');
       $code = I('post.code');
       $password = I('post.password');
       $sha1pw = sha1(sha1($password));
       
       
       if(!check_verify($code))
       {
       		$this->error('验证码错误!','/user/login/');
       	   return false;
       }
       // 验证密码
       $company_user = M('company_user');
       $uid = $result['uid'];
       $result = $company_user->where("login = '$login' and password = '$sha1pw'")->find();
        if ($result != null) {
        	if(1!=$result['state'])
        	{
        	
        		$this->error('该用户尚未通过审核!','/user/login/');
        		return false;
        	}
            session('logined',true);
            session('userid',$result['uid']);
            session('username',$result['login']);
            session('type',$result['type']);
            redirect('/index/index');
            return true;  
        }
        $this->error('用户名或密码错误!','/user/login/');
       
         return false;
    }
  

     // 注册界面
    public function register()
    {
    	
         $this->display();
    }
   
    protected function upload($login)
    {
	     $upload = new \Think\Upload();// 实例化上传类
	     $rand = mt_rand(0, 99);
		$upload->maxSize = 3145728 ;// 设置附件上传大小
		$upload->exts = array('jpg', 'png', 'jpeg');// 设置附件上传类型
		$upload->rootPath = './upload/'; // 设置附件上传根目录
		$upload->savePath = '/info/';
		$upload->saveHash = false;
		$upload->saveName = "login-"."$login".$rand;
		// 上传文件
		$info = $upload->upload();
		//dump($info);die;
		if(!$info) {// 上传错误提示错误信息
		//$this->error($upload->getError());
			$this->error('营业执照未上传');
		return NULL;
		}
	     foreach($info as $file){
			return $file['savepath'].$file['savename'];
		}
    }
    // 用户注册处理
    // 取表单数据、验证合法性、重复性、按照company_user,,company,company_user_info顺序写数据库
      public function register_deal()
    {
       do
       {
       if(!IS_POST)
       {
           $this->error = '非表单post数据';
            break;
       }
       // 取值
       $login = I('post.login');
       $password = I('post.password');
       $repeatpassword = I('post.repeatpassword');
       $english_name = I('post.english_name');
       $company_name = I('post.company_name');
       $adress = I('post.adress');
       $contact_name = I('post.contact_name');
       $phone_num = I('post.phone_num');
       $nick_name = I('post.contact_name');
       $email = I('post.email');
       $original_code = I('post.original_code');
       $code = I('post.code');
    //   dump($login);die;
       if(NULL==($verify_info=$this->upload($login)))
       {
         $this->error='上传出错';
       	  break;
       }
       
       if(!check_verify($code))
       {
       	  $this->error='验证码错误';
       	  break;
       }
      //验证 :合法性
       if($password != $repeatpassword)
       {
           $this->error='两次输入的密码不一致,请重新填写!';
           break;
        }
       $sha1password =  sha1(sha1($password));
   
       // 数据准备
       //conmpany_user
       $company_user = M('company_user');
       // 重复性
       $condition['login'] = $login;
       $result_login = $company_user->where($condition)->find();
     //  dump($result_login);die;
       if($login == $result_login['login'])
       {
            $this->error='用户名已存在，请重新填写!';
           break;
       }
      
       $company_user_info = M('company_user_info');
       $condition['email'] = $email;
       $result_login = $company_user_info->where($condition)->find();
       if($email == $result_login['email'])
       {
       	$this->error='邮箱已存在，请重新填写!';
       	break;
       }
       //dump($login);die;
        $data_company_user['login'] = $login;
        $data_company_user['password'] = $sha1password;
        $data_company_user['type'] = 0;
        $data_company_user['createtime'] = date('Y-m-d H:i:s');
        $data_company_user['state'] = 0;
       
        //company
        $company = M('company');
        
        $data_company['company_name'] = $company_name;
        $data_company['english_name'] = $english_name;
        $data_company['original_code'] = $original_code;
        $data_company['verify_info'] = $verify_info;
        $data_company['createtime'] = date('Y-m-d H:i:s'); 
             
        //company_user_info
        $company_user_info = M('company_user_info');
       
        $data_company_user_info['nick_name'] = $nick_name;
        $data_company_user_info['phone_num'] = $phone_num;
        $data_company_user_info['email'] = $email;
        $data_company_user_info['contact_name'] = $contact_name;
        $data_company_user_info['adress'] = $adress;
        $data_company_user_info['createtime'] = date('Y-m-d H:i:s');
            
        //按需写数据库
        $uid = $company_user->data($data_company_user)->add();
        if( null==$uid)
        {
            
             $this->error = '1数据库出现错误！company_user';
            break;
        }
        $data_company['uid'] = $uid;
        $cid = $company->data($data_company)->add();
        if(null == cid)
        {
          $this->error = '2数据库出现错误！company';
            break;
        }
        $data_company_user_info['uid'] = $uid;
        $cuinfo = $company_user_info->data($data_company_user_info)->add();
        if(null== $cuinfo)
        {
            $this->error = '3数据库出现错误！company_user_info';
            break;
        }
         $this->success('注册成功，待系统管理员审核!', '/user/login');
         return;
           
       }while(0);
       
       $this->display('register');
    }
    
public function findpw()
    {
    	
        
    	
    	$email = I('email');
    	if(null == $email){
    		$this->error('请输入您注册的邮箱账号');
    	}
    	$company_user_info = M('company_user_info');
    	//echo($email);
    	$uid = $company_user_info->where("email='$email'")->getField('uid');
    	if($uid!=null)
    	{
	    	$content=md5(time()); 
         	//$server = C('MAIL_SMTP');
	    	//$user = C('MAIL_LOGINNAME');
	    	//$pw = C('MAIL_PASSWORD');
	    	
		    session($content,$content); 
		   
		   // $content=C('localurl').'/index.php'.U('user/editpw',array('res'=>$content)); 
		   
	    	//$server = C('MAIL_SMTP');
	    	
		   // $mail = new \Org\Smtp($server,$user,$pw,false);
		    
		   // echo $mail;die;
		
    	$content='成都菲普迪斯微防伪系统密码找回,请点击'.'http://pec.weproduct.cn/index.php/user/editpw_display/'.$uid;
    	//dump($content);die;
    	if (think_send_mail($email, "找回密码", $subject = '请点击此链接',$content, $attachment = null)) {
$this->success('发送成功！');
} else {
$this->error('发送失败');
}
    	 //$this->display();
    	
    	}else{
    		$this->error('邮箱不存在!请重新输入');
    	}
    	
    }
    
     public function editpw_display($uid = 0)
    {
    	header("Content-type: text/html; charset=utf-8");  
		//dump($uid);die;
		if(null !=$uid){
	 	$this->userid = $uid;
	 	
		}
		$this->display();
		/*
		echo $res; 
		if(session($res)==$res){ 
		echo '密码找回成功'; 
		session($res,null); 
		}else{ 
		echo '已经过期'; 
    }*/
    }
    /*
     * 重置密码
     */
    function editpw(){
    	$uid = I('userid');
    	//dump($uid);die;
    	$newkey = I('_new_password');
    	$surekey = I('_new_password_ag');
    	//dump($surekey);die;
    	if($newkey == $surekey){
    		$company_user =M('company_user');
    		$data['password'] = sha1(sha1($surekey));
    		$result = $company_user->where("uid=$uid")->data($data)->save();
    		//dump($result);die;
    		if($result != 0){
    			//dump(1);die;
    			$this->success('修改密码成功,请重新登录!','/user/login');
    			
    		}
    	}else{
    		$this->error('两次输入密码不一致，请重新输入');
    	}
    }
}