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
class VerifyController extends Controller{

public function verifier(){
    $Verify = new \Think\Verify();
    $Verify->length   = 3;
    $Verify->fontSize = 30;
    $Verify->useNoise = false;
    $Verify->entry();
   }

}
