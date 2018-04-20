/**
 * 
 */
verifyURL = '/Verify/verifier/';
//定义验证码路径
function change_code(obj){
 $("#code").attr("src",verifyURL+Math.random());
 return true;
}