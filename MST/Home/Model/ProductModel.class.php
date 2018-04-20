<?php

namespace Home\Model;
use Think\Model;

class ProductModel extends Model
{
       //增加新产品
    public function newProduct($data){
        $data['state'] = 1;
       $data['createtime'] = date('Y-m-d H:i:s'); //当前服务器时间[默认是北京时间，如果国际化，要进行时区处理]
       return $this->data($data)->add();
    }
    
      //是否存在
     public  function isExist($pid) {
          $result = $this->where("pid = $pid")->count();
          dump($result);
          return ($result == 1) ? true : false;
     }
     
    
}
