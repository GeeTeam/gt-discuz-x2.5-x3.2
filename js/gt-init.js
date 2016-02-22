var getCaptcha = function(data_ele, data_style, data_btn, callback) {
    var xmlHttp;

    function createxmlHttpRequest() {
        if (window.ActiveXObject) {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        } else if (window.XMLHttpRequest) {
            xmlHttp = new XMLHttpRequest();
        }
    }
    // var ele = "#" + data_ele;
    createxmlHttpRequest();
    xmlHttp.open("GET", "./source/plugin/geetest/gt_check_server.php");
    xmlHttp.send(null);
    xmlHttp.onreadystatechange = function(result) {
        if ((xmlHttp.readyState == 4) && (xmlHttp.status == 200)) {
                var obj = JSON.parse(xmlHttp.responseText);
             // var obj = eval('(' + result.target.response + ')');
            // console.log(obj);
            // if (obj.success == 1) {
            //     loadGeetest(obj);
            // }else{
            //     gtFailbackFrontInitial(obj);
            // }
            check(obj);
        }
    }
    var check = function(obj) {
        if (window.Geetest) {
            loadGeetest(obj);
        } else {
            setTimeout(function() {
                if (!window.Geetest) {
                    gtFailbackFrontInitial(obj);
                } else {
                    loadGeetest(obj);
                }
            }, 1000);
        }
    };

    var loadGeetest = function(config) {
        //1. use geetest capthca
        var gt_captcha_obj = new window.Geetest({
            gt: config.gt,
            challenge: config.challenge,
            product: data_style,
            offline: !config.success
        });
        if (data_style == "popup" || data_btn != "") {
            gt_captcha_obj.appendTo(data_ele).bindOn(data_btn);
        } else {
            gt_captcha_obj.appendTo(data_ele);
        }
        if (callback) {
            callback(gt_captcha_obj);
        };
    }
    var gtFailbackFrontInitial = function(result) {
        var gt_failback = document.createElement('script');
        gt_failback.id = 'gt_lib';
        gt_failback.src = 'http://static.geetest.com/static/js/geetest.0.0.0.js';
        gt_failback.charset = 'UTF-8';
        gt_failback.type = 'text/javascript';
        document.getElementsByTagName('head')[0].appendChild(gt_failback);
        var loaded = false;
        gt_failback.onload = gt_failback.onreadystatechange = function() {
            if (!loaded && (!this.readyState || this.readyState === 'loaded' || this.readyState === 'complete')) {
                loadGeetest(result);
                loaded = true;
            }
        };
    }
};

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
