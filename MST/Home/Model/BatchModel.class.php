<?php

namespace Home\Model;
use Think\Model;

class BatchModel extends Model{
    //put your code here

    
      //是否存在
     public  function isExist($bid) {
          $result = $this->where("bid = $bid")->count();
          
          return ($result == 1) ? true : false;
     }
}
