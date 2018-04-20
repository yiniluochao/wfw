/**
 * 
 */
verifyURL = 'http://localhost/Verify/verifier';
//定义验证码路径
function change_code(obj){
 $("#code").attr("src",verifyURL);
 //动态生成验证码方法，有兴趣的朋友可以深入研究下jq方法
 return true;
}