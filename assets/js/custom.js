function addtext () {
var addt=document.createElement("input");
addt.setAttribute('type','text');
addt.setAttribute('name','area[]');
addt.setAttribute('class','textarea');
addt.setAttribute('value','请输入文本标题');
var addtmod=document.createElement("textarea");
addtmod.setAttribute('disabled','true');
addtmod.setAttribute('class','textareamod');
var delbutton1=document.createElement("img");
delbutton1.setAttribute('src','/assets/images/del.png');
delbutton1.setAttribute('class','delbutton');
delbutton1.setAttribute('onclick','del(this)');
var area1=document.createElement("div");
area1.insertBefore(addt,null);
area1.insertBefore(addtmod,null);
area1.insertBefore(delbutton1,null);
var area0=document.getElementById("area");
area0.insertBefore(area1,null);
}
function addpic () {
var addp=document.createElement("input");
addp.setAttribute('type','text');
addp.setAttribute('name','area[]');
addp.setAttribute('class','picarea');
addp.setAttribute('value','请输入图片标题');
var addpmod=document.createElement("img");
addpmod.setAttribute('src','/assets/images/picmod.png');
addpmod.setAttribute('class','picareamod')
var delbutton2=document.createElement("img");
delbutton2.setAttribute('src','/assets/images/del.png');
delbutton2.setAttribute('class','delbutton');
delbutton2.setAttribute('onclick','del(this)');
var area2=document.createElement("div")
area2.insertBefore(addp,null);
area2.insertBefore(addpmod,null);
area2.insertBefore(delbutton2,null);
var area0=document.getElementById("area");
area0.insertBefore(area2,null);
}

function del (obj) {
	var delthisdiv=obj.parentNode.parentNode;
	delthisdiv.removeChild(obj.parentNode);
}

$(".submitit").click(function(){
	var a=$("[name='area[]']");
	$.each(a,function(i){
		if(a[i].className=="textarea")
			{
			var textvalue=a[i].value;
			a[i].value="txt"+textvalue;
			}
			else{
				var picvalue=a[i].value;
				a[i].value="pic"+picvalue;
			    }
		});
});

