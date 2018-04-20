<?php

namespace Home\Controller;

use Think\Controller;

class ProductController extends Controller {

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

    // 调转到添加产品界面
    public function newone() {
        if (true == is_login()) {
            $this->page_id = 2;
            $this->product_code = 'P' . date('YmdHis') . rand(1000, 9999);
            $this->display();
        } else {
            $this->__userNotLogin();
        }
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
    	$upload->savePath = '/product/';
    	$upload->saveHash = false;
    	$upload->saveName = "product-".$rand;
    	// 上传文件
    	$info = $upload->upload();
    	 
    	//dump($info);die;
    	//如果没上传就显示系统默认图片 default.png
    	if(!$info) {// 上传错误提示错误信息
    		$this->error('未上传产品缩略图');
    		return NULL;
    	}
    	foreach($info as $file){
    		return $file['savepath'].$file['savename'];
    	}
    }
    
    
    // 添加产品处理
    public function newone_deal() {

        if (IS_POST) {
            if (true == is_login()) {
                // 取数据
                $product_name = I('post.product_name');
                $product_code = I('post.product_code');                
                $html_content = I('post.html_content', '', '');
                $txt_content = I('post.txt_content');
                
                //dump($product_name);die;
                do {
                    //写数据库
                    if (empty($product_name)) {
                        $this->errorinfo = '产品名称不能为空';
                        break;
                    }
                    if (empty($product_code)) {
                        $this->errorinfo = '产品代码不能设置为空';
                        break;
                    }
                    
                  
                    
                    $uid = get_user_id();
                    if($uid == null)
                    {
                         $this->errorinfo = '账号异常，请重新登录';
                        break;
                    }
                    
                    $company =  M('company');
                    $cid = $company->where("uid='$uid'")->getField('cid');
                    if($cid == null)
                    {
                         $this->errorinfo = '没有对应公司';
                        break;
                    }
                    $data['product_name'] = $product_name;
                    $data['product_code'] = $product_code;
                    $data['html_content'] = $html_content;
                    $data['txt_content'] = $txt_content;
                    $data['pic_url'] = $this->upload();
                    //dump($data['pic_url']);die;
                    //$data['txt_content']  = DeleteHtml($html_content);
                    $data['cid']  = $cid;
                    
                   
                    $product = D('Product');
                    $pid = $product->newProduct($data);
                    if (null == $pid) {
                        $this->errorinfo = '数据库出现错误！';
                        break;
                    }
                    redirect( "/product/view/$pid");
                    //$this->success('新增成功!', '/product/view/id/'."$pid");
                    return;
                } while (0);

                // 界面持久
                $this->product_name = $product_name;
                $this->product_code = $product_code;
                $this->html_content = $html_content;
                $this->txt_content = $txt_content;
                $this->display('newone');
            }
        }
    }
    // 产品展示
    public function view($pid = 0) {

        if (true == is_login()) {
            $this->page_id = 3;
            
            $product = M('product'); // 实例化
            $user_type = get_user_type();
            $uid = get_user_id();
        	if(0 == $user_type)
        	{
        		$arrange = " and cid=$uid ";
        	}else
        	{
        		$arrange = "";
        	}
            $result = $product->where("pid=$pid $arrange")->find();
            if ($result == null) {
                $this->error("不存在产品信息");
            }
            else {
                $this->product_name = $result['product_name'];
                $this->product_code=$result['product_code'];
                $this->html_content = $result['html_content'];
                $this->txt_content =  $result['txt_content'];
                $this->photo_url = $result['pic_url'];
                $this->display();
            }
        }
    }
    
    // 产品管理
    public function manager() {

        if (true == is_login()) {
            $this->page_id = 3;
            
            $product = M('product'); // 实例化对象
            $company = M('company');
            $uid = get_user_id();
            $user_type = get_user_type();
            $cid =  $company->where("uid=$uid")-> getField('cid');
            $uid = get_user_id();
        	if(0 == $user_type)
        	{
        		$arrange = "cid=$uid";
        	}else
        	{
        		$arrange = "";
        	}
            $count = $product->where("$arrange")->count(); // 查询满足要求的总记录数
            $page = new \Think\Page($count, 8);
//            $Page = new \Think\Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数(25)
//            $page->route = '/index';
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $product->where("$arrange")->order("createtime desc")->select();
            $this->assign('list', $list); // 赋值数据集
            $this->assign('page', $show); // 赋值分页输出
            $this->display(); // 输出模板
            
        } else {
            $this->__userNotLogin();
        }
    }

    

    // 编辑界面显示
    public function edit($pid = 0) {
        
        if (true == is_login()) {
            $this->page_id = 3;
         	$user_type = get_user_type();
         	$uid = get_user_id();
        	if(0 == $user_type)
        	{
        		$arrange = " and cid=$uid ";
        	}else
        	{
        		$arrange = "";
        	}
            $product = M('product'); // 实例化
            $result = $data = $product->where("pid=$pid $arrange")->find();
            $this->pid = $pid;
            if ($result == null) {
                $this->error("不存在产品信息");
            }
            else 
            {
                
                $this->product_name = $result['product_name'];
                $this->product_code=$result['product_code'];
                $this->html_content = $result['html_content'];
                $this->txt_content =  $result['txt_content'];
                $this->display();
            }
        }
    }

    // 编辑处理
    public function edit_deal($pid = 0) {
        if (IS_POST) {
            if (true == is_login()) {
                $product_name = I('post.product_name');
                $product_code = I('post.product_code');                
                $html_content = I('post.html_content', '', '');
                $txt_content = I('post.txt_content');
                
                
                do {
                    //写数据库
                    if (empty($product_name)) {
                        $this->errorinfo = '产品名称不能为空';
                        break;
                    }
                    if (empty($product_code)) {
                        $this->errorinfo = '产品代码不能设置为空';
                        break;
                    }
;
                    $data['product_name'] = $product_name;
                    $data['product_code'] = $product_code;
                    $data['html_content'] = $html_content;
                    $data['txt_content'] = $txt_content;
                	$data['pic_url'] = $this->upload();
                    $product = M('Product');
                    if (null === $product->where("pid = $pid")->save($data)) {
                        $this->errorinfo = '数据库出现错误！';
                        break;
                    }
                    redirect("/product/view/$pid");
                    //$this->success('修改成功!', "/product/view/id/$id");
                    return;
                } while (0);

                $this->product_name = $product_name;
                $this->product_code=$product_code;
                $this->html_content =$html_content;
                $this->txt_content =  $txt_content;
                $this->display('edit');
            }
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

    
            $where['product_name'] = array('like', "%$search_key%");
            $where['product_code'] = array('like', "%$search_key%");
            
            $where['_logic'] = 'or';
            $uid = get_user_id();
            $company =  M('company');
            $cid = $company->where("uid='$uid'")->getField('cid');
            if($cid == null)
            {
                 $this->errorinfo = '没有对应公司';
                
            }
            $product = M('product');
            
            $map['_complex'] = $where;
            $map['_string'] = "cid=$cid";
            $product = M("Product"); // 实例
            $list = $product->where($map)->select();
            
            if ($list == NULL||$list==false) {
                
                $this->error("没有找到合适的");
            } else {
                $this->list = $list;
                $this->display('manager');
           
            }
        }
    }

}
