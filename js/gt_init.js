gt_init = document.createElement('script');
gt_init.src = 'http://api.geetest.com/get.php?callback=gtcallback';
document.getElementById("toptb").appendChild(gt_init);
function changeCaptcha(){
	var captcha=document.getElementById("captcha");
	var p=document.getElementsByClassName("ptm pnpost");
	if(typeof(p[1])!="undefined"){
		var parent=p[1].parentNode;
		parent.insertBefore(captcha,p[1]);
	}else{
		var parent=p[0].parentNode;
		parent.insertBefore(captcha,p[0]);
	}
	
}
