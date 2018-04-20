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
class TestController extends Controller{
    //put your code here
    public function test_gen()
    {
        $result = gen_pec(3, 1, 1);
               $this->display();
    }
    
}
