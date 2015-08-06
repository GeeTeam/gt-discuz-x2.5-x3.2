<?php  

if(!defined('IN_DISCUZ')) {  
    exit('Access Denied');  
}  
loadcache('plugin');
C::import('geetestlib','plugin/geetest/lib');

class plugin_geetest{  
    public $captcha_allow = false;//当前模块是否开启验证码验证
    public $mods = array();//需要开启验证的位置
    public $style = array();
    public $config = array();
    public $open ;//验证是否开启
    public $geetest;
	
    function plugin_geetest() {
        $this->geetest=new geetestlib();
        global $_G;
        //读缓存信息
        $this->mods = unserialize($_G['cache']['plugin']['geetest']['mod']);
        $this->open = $_G['cache']['plugin']['geetest']['open']; 
        $this->style = $_G['cache']['plugin']['geetest'];

        //初始化
        if(($this->open == '1')&&($this->_cur_mod_is_valid())){
            //登陆注册不需要选择用户组
            if(CURMODULE == "logging" || CURMODULE == "register"){
                $this->captcha_allow = true;
            }else if(in_array($_G['groupid'], unserialize($_G['cache']['plugin']['geetest']['groupid']))){
                $this->captcha_allow = true;
            }else{
                $this->captcha_allow = false;
            }
        }else{
            $this->captcha_allow = false;
        }
	
        //发帖大于限定数，则不用插件
        $post_count = $_G['cookie']['pc_size_c'];
        $post_num = intval($_G['cache']['plugin']['geetest']['post_num']);
        if($post_count == null){
            $arr = array('a','b','c','d','e','f');
            shuffle($arr);
            $post_count = '0'.implode("",$arr);
            dsetcookie('pc_size_c', $post_count, 24*60*60);
        }else{
            $post_count = intval($post_count);
        }
        if($post_num != 0 && $post_count >= $post_num){
            $this->captcha_allow = false;
        }
    }
          

    //初始化get.php
    function global_cpnav_top(){
        global $_G;
        $javascript=<<<JS
        <script type="text/javascript" src="source/plugin/geetest/js/gt_init.js"></script>
        <script type="text/javascript" src="source/plugin/geetest/js/gt_core.js"></script>
JS;
        return $javascript;
        //return $this->return_captcha("tpl_global_cpnav_top","module");
    }

    function global_login_extra() {
        global $_G;
        $html=<<<HTML
        <script type="text/javascript">
            var lsform = document.getElementById('lsform');
            var o = document.createElement("button");  
            o.id = "header-loggin-btn";       
            o.setAttribute('type', 'submit');                               
            o.value = ""; 
            o.style.display="none";
            lsform.appendChild(o);
        </script>
        <div><table><tbody><tr><th style="width:80px;"><div></div></th><td id="index_login">
        </td></tr></tbody></table></div>
        <script type="text/javascript">
            getCaptcha("#index_login","popup","#header-loggin-btn");
        </script>
HTML;
        return $html;
        //return $this->return_captcha("tpl_global_login_extra","module");
    }
    
    function global_header(){
        global $_G;
        $cur=CURMODULE;
        if($cur=="connect"){
            $html=<<<HTML
            <div><table><tbody><tr><th style="width:80px;"><div></div></th><td id="global_header">
            </td></tr></tbody></table></div>
            <script type="text/javascript">
                getCaptcha("#global_header","popup","button#registerformsubmit.pn.pnc");
                getCaptcha("#global_header","popup","button[name='loginsubmit']");
            </script> 
HTML;
            return $html;
            //return $this->return_captcha("tpl_global_header","module");
        }
    }

    public function _cur_mod_is_valid(){
        $cur = CURMODULE;
        switch(CURMODULE){
            case "logging":
                $mod = "2";
                break;
            case "register":
                $mod = "1";
                break;
            case "post":
                if($_GET["action"] =="reply"){
                    $mod = "4";
                }else if($_GET["action"] =="newthread"){
                    $mod = "3";
                }else if($_GET["action"] =="edit"){
                    $mod = "5";
                }
                break;
            case "forumdisplay":                
                $mod = "3";             
                break;
            case "viewthread":
                $mod = "4";
                break;
            case "follow": 
                $mod = "6";
                break;
            case "spacecp":
                if($_GET["ac"] =="blog"){
                    $mod = "7";
                }
                if($_GET["ac"] =="comment"){
                    $mod = "8";
                }
                if($_GET["ac"] =="follow"){
                    $mod = "6";
                }
                if ($_GET["ac"] == "credit") {
                    $mod = "9";
                }
                break;
            case "space":
                if($_GET["do"] =="wall"){
                    $mod = "8";
                }
                if($_GET["do"] == "blog" || $_GET["do"] == "index"){
                    $mod = "7";
                }else{
                    $mod = "4";
                }
                break;
            case "connect":         
                $mod = "1";         
                break;
            case "index":
                $mod = "2";
                break;
            default:
                return 1;
        }
        return in_array($mod, $this->mods);
    }

    public function return_captcha($temp,$module){
        if($this->captcha_allow){
            include_once template('geetest:'.$module);
            return call_user_func($temp); 
        }
    }

}

include('plugin_class/plugin_geetest_member.class.php');

include('plugin_class/plugin_geetest_forum.class.php');

include('plugin_class/plugin_geetest_home.class.php');

include('plugin_class/plugin_geetest_group.class.php');

?>