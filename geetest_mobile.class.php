<?php
/**
 *  [极验手机版验证码(geetest.{modulename})] (C)2015-2099 Powered by geetest Inc..
 *  Version: 1.0
 *  Date: 2015-3-12 17:43
 */

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
loadcache('plugin');
C::import('geetestlib','plugin/geetest/lib');

// include DISCUZ_ROOT.'source/plugin/geetest/template/extend_module.htm';
class mobileplugin_geetest {
    public $captcha_allow = false;
    public $mobile ;  
    public $mod = array();
    public $captcha = '';
    public $private = '';
    public $config =array();
    public function mobileplugin_geetest(){
        global $_G;
        //读缓存信息
        $config = include DISCUZ_ROOT.'data/plugindata/geetest/config.php';
        $this->mod = unserialize($_G['cache']['plugin']['geetest']['mod']);
        $this->mobile = $_G['cache']['plugin']['geetest']['mobile']; 
        $this->keyset = $this->config['mobileset'];
        if(in_array($_G['groupid'], unserialize($_G['cache']['plugin']['geetest']['groupid'])) && $this->mobile ){
            $this->captcha_allow = true;
        }

        $post_count = $_G['cookie']['pc_size_c'];
        if($post_count == null){
            $arr = array('a','b','c','d','e','f');
            shuffle($arr);
            $post_count = '0'.explode($arr);
            dsetcookie('pc_size_c', $post_count, 24*60*60);
        }else{
            $post_count = intval($post_count);
            $post_num = intval($_G['cache']['plugin']['geetest']['post_num']);
            
            if(($post_num != 0 && $post_count >= $post_num)){
                $this->captcha_allow = false;
            }
        }

    }


    public function _cur_mod_is_valid(){
        $cur = CURMODULE;
        switch(CURMODULE){
            case "logging":
                $cur = "2";
                break;
            case "register":
                $cur = "1";
                break;
            case "post": //论坛模块
                if($_GET["action"] =="reply"){
                    $cur = "4";
                }else if($_GET["action"] =="newthread"){
                    $cur = "3";
                }else if($_GET["action"] =="edit"){
                    $cur = "5";
                }
                break;
            case "forumdisplay":
            case "viewthread":
                $cur = "4";
                break;
        }
        return in_array($cur, $this->mod);
    }


    public function _code_output(){
        if( !($this->_cur_mod_is_valid() && $this->captcha_allow ) ){
            return ;
        }
        global $_G;
            $output = '';
            $output = '<div class="geetest" style="display: none;position: fixed;left: 0;top: 0;width: 100%;height: 100%;">
            <div class="gt_bg" style="position: absolute;left: 0;top: 0;width: 100%;height: 100%;background-color: gray;opacity: 0.7;"></div><div class="wrap" style="width: 300px;margin: 10px auto;text-align: center;background-color: #fff;z-index: 2;position: relative;
"><div class="top" style="padding: 0 8px;height: 44px;z-index: 1;position: relative;text-align: center;font-weight: 500;"><a class="exit" id="close" href="javascript:;"></a><div class="title" style=" line-height: 45px;width: 200px;margin: 0 auto;">&#35831;&#36890;&#36807;&#39564;&#35777;
        </div></div>';
            $output .= geetestlib::get_widget_mobile($this->keyset['captchaid']);
            $output .= '</div>';
            return $output;
    }


    public function fix_register(){
        return '<script id="testScript" type="text/javascript" src="source/plugin/geetest/js/geetest_mobile.js" data-btn="btn_register" data-form="registerform"></script>';

    }
    
    public function global_footer_mobile(){
        if (CURMODULE == 'register' &&  $this->_cur_mod_is_valid() && $this->captcha_allow ) {
            return $this->_code_output().$this->fix_register(); 
        }else{
            return ;
        }
    }
    public function geetest_validate($challenge, $validate, $seccode){
        $geetest = new geetestlib($this->keyset);
        // $geetest->set_keyset($this->keyset);
        return $geetest->validate($challenge, $validate, $seccode);
    }

}

class mobileplugin_geetest_member  extends mobileplugin_geetest{

    public function fix_login(){
        return '<script id="testScript" type="text/javascript" src="source/plugin/geetest/js/geetest_mobile.js" data-btn="btn_login" data-form="loginform"></script>';
    }

    public function logging_bottom_mobile(){
        if( !($this->_cur_mod_is_valid() && $this->captcha_allow ) ){
            return ;
        }else{
            return $this->_code_output().$this->fix_login(); 
        }

    }


    // public function temp_geetest_validate($challenge, $validate, $seccode){
    //     $geetest = new geetestlib();
    //     $geetest->set_keyset($this->keyset);
    //     return $geetest->temp_geetest_validate($challenge, $validate, $seccode);
    // }


    function logging_code() {
        global $_G;
            if($_GET['action'] == "logout"){
            return;
        }

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
    function register_code(){
        global $_G;
        if($this->_cur_mod_is_valid() && $this->captcha_allow) {
            if(submitcheck('regsubmit', 0, $seccodecheck, $secqaacheck)){
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
}

class mobileplugin_geetest_forum extends mobileplugin_geetest {

    public function fix_viewthread(){
        return '<script id="testScript" type="text/javascript" src="source/plugin/geetest/js/geetest_mobile.js" data-btn="fastpostsubmit" data-form="fastpostsubmitline"></script>';
    }
    //手机底部回复
    public function viewthread_fastpost_button_mobile(){
        if ( !($this->_cur_mod_is_valid() && $this->captcha_allow ) ) {
            return;
        }else{
            return $this->_code_output().$this->fix_viewthread();   
        }
    }   
    
         //手机跳转回复及发帖
    public function post_bottom_mobile(){
        if (CURMODULE == "post" && $this->_cur_mod_is_valid() && $this->captcha_allow) {
            return $this->_code_output().$this->fix_post();     
        }else{
            return;
        }
    }

    public function fix_post(){
        return '<script id="testScript" type="text/javascript" src="source/plugin/geetest/js/geetest_mobile.js" data-btn="postsubmit" data-form="y"></script>';
    }

        function post_rccode() {
        
        global $_G;
        $success = 0;
        if($this->_cur_mod_is_valid() && $this->captcha_allow) {
            if(submitcheck('topicsubmit', 0, $seccodecheck, $secqaacheck) || submitcheck('replysubmit', 0, $seccodecheck, $secqaacheck) || submitcheck('editsubmit', 0, $seccodecheck, $secqaacheck) ) {
                $response = $this->geetest_validate($_GET['geetest_challenge'], $_GET['geetest_validate'], $_GET['geetest_seccode']);
                if($response != 1){
                    if($response == -1){
                        showmessage(lang('plugin/geetest', 'seccode_invalid'));
                    }else if($response == 0){
                        showmessage( lang('plugin/geetest', 'seccode_expired') );
                    }
                }else{
                    $success == 1;
                }
            }
        }
        
        if($success == 1){
            $post_count = $_G['cookie']['pc_size_c'];
            $post_count = intval($post_count);
            $post_count = ($post_count + 1);
            $arr = array('a','b','c','d','e','f');
            shuffle($arr);
            $post_count = $post_count.implode("",$arr);
            dsetcookie('pc_size_c',  $post_count);
        }
    }
}



?>  