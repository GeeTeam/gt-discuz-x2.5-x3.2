<?php


   
class plugin_geetest_member  extends plugin_geetest{  

    function register_input_output(){    
        if ($this->_cur_mod_is_valid()) {
            $cur_mod = "register";
            if($_GET["infloat"] == "yes"){
                $gt_geetest_id = "gt_float_register_input";
                $page_type = "register_float";
            }else{
                $gt_geetest_id = "gt_page_register_input";
                $page_type = "register";
            }
            return $this->_code_output($cur_mod, $gt_geetest_id, $page_type);
       }   

    }
        
    function logging_input_output() {
        if ($this->_cur_mod_is_valid()) {
            $cur_mod = "logging";
            if($_GET["infloat"] == "yes"){
                $gt_geetest_id = "gt_float_logging_input";
                $page_type = "logging_float";
            }else{
                $gt_geetest_id = "gt_page_logging_input";
                $page_type = "logging";
            }
            return $this->_code_output($cur_mod, $gt_geetest_id, $page_type);
        }
    }

    

    function register_code(){
        global $_G;
        $cur = CURMODULE;
        if($this->_cur_mod_is_valid() && $this->captcha_allow && $cur == "register") {
            if(submitcheck('regsubmit', 0, $seccodecheck, $secqaacheck)){
                $response = $this->geetest_validate($_GET['geetest_challenge'], $_GET['geetest_validate'], $_GET['geetest_seccode']);
                if($response != 1){
                    if($response == -1){
                        showmessage(lang('plugin/geetest', 'seccode_invalid'));
                    }else if($response == 0){
                        showmessage( lang('plugin/geetest', 'seccode_expired') );
                    }
                }
            }       
        }
    }
    function logging_code() {
        if($_GET['action'] == "logout"){
            return;
        }
        $cur = CURMODULE;
        if ($this->open && $this->logging_mod_valid()) {
            if($_GET['username'] != "" && $_GET['password'] != "" && $_GET['lssubmit'] == "yes"){
                if(( $_GET['geetest_validate'] == null && $_GET['geetest_seccode'] == null) || 
                    ($_GET['geetest_validate'] == "" && $_GET['geetest_seccode'] == "")){
                    $this->_show();
                    return;
                }
            }
        }else{
            return;
        }

        if( ! $this->has_authority() ){
            return;
        }

        global $_G;
        if($this->_cur_mod_is_valid() && $this->captcha_allow) {
            if(submitcheck('loginsubmit', 1, $seccodestatus) && empty($_GET['lssubmit'])) {//
                $response = $this->geetest_validate($_GET['geetest_challenge'], $_GET['geetest_validate'], $_GET['geetest_seccode']);
                if($response != 1){//
                    if($response == -1){
                        showmessage(lang('plugin/geetest', 'seccode_invalid'));
                    }else if($response == 0){
                        showmessage( lang('plugin/geetest', 'seccode_expired') );
                    }
                }
            }
        }
    }
  
    public function _show(){
         include template('common/header_ajax');
         $js = <<<JS
        <script type="text/javascript" reload="1">
            var btn=document.getElementById("header-loggin-btn");
            btn.click();
         </script>
JS;
        echo($js);
         include template('common/footer_ajax');
         dexit();
    }
 

     public function _init(){
         include template('common/header_ajax');
         $js = <<<JS
 <script type="text/javascript" reload="1">
    var handler = function (captchaObj) {
         captchaObj.appendTo(document.body);
         captchaObj.bindOn("#header-loggin-btn");
     };
    var xmlHttp;
    function createxmlHttpRequest() {
        if (window.ActiveXObject) {
            xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
        } else if (window.XMLHttpRequest) {
            xmlHttp = new XMLHttpRequest();
        }
    }
    createxmlHttpRequest();
    xmlHttp.open("GET", "./source/plugin/geetest/gt_check_server.php");
    xmlHttp.send(null);
    xmlHttp.onreadystatechange = function(result) {
        if ((xmlHttp.readyState == 4) && (xmlHttp.status == 200)) {
                // var obj = JSON.parse(xmlHttp.responseText);
                var obj = eval('(' + result.target.response + ')');            
                console.log(obj);
                    initGeetest({
                        gt: obj.gt,
                        challenge: obj.challenge,
                        product: "popup", // 产品形式
                        offline: !obj.success
                    }, handler);
        }
    }
         </script>
JS;
        echo($js);
         include template('common/footer_ajax');
         dexit();
    }


    function has_authority(){
        //针对掌上论坛不需要验证
        if( $_GET['mobile'] == 'no' && $_GET['submodule'] == 'checkpost' ){
            return false;
        }
        return true;
    }

}
