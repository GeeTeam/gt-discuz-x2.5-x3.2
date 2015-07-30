<?php  
        // error_reporting(E_ERROR);  

if(!defined('IN_DISCUZ')) {  
    exit('Access Denied');  
}  
loadcache('plugin');
loadcache('gt_cache');
C::import('geetestlib','plugin/geetest/lib');

class plugin_geetest{  
    // public $captcha_allow = false;
    // public $mods = array();
    // public $keyset = array();
    // public $style = array();
    // public $config = array();
    // public $open ;
	
    //     function plugin_geetest() {
    //         global $_G;
    //         //读缓存信息
    //         $this->mods = unserialize($_G['cache']['plugin']['geetest']['mod']);
    //         $this->open = $_G['cache']['plugin']['geetest']['open']; 
    //         if ($_G['cache']['gt_cache'] == "" || $_G['cache']['gt_cache'] == null || !isset($_G['cache']['gt_cache'])) {
	   // $config = @include DISCUZ_ROOT.'source/plugin/geetest/lib/config.php';
    //             $this->keyset = $config['keyset'];
    //         }else{
    //             $this->keyset = $_G['cache']['gt_cache'];
    //         }
    //         $this->style = $_G['cache']['plugin']['geetest'];

    //         //初始化
    //         if( $this->open == '1'){
    //         //登陆注册不需要选择用户组
    //             if(CURMODULE == "logging" || CURMODULE == "register"){
    //                 $this->captcha_allow = true;
    //             }else if(in_array($_G['groupid'], unserialize($_G['cache']['plugin']['geetest']['groupid']))){
    //                 $this->captcha_allow = true;
    //             }else{
	   //     $this->captcha_allow = false;
    //             }
    //         }else{
    //             $this->captcha_allow = false;
    //         }
		
    //         //发帖大于限定数，则不用插件
    //         $post_count = $_G['cookie']['pc_size_c'];
    //         if($post_count == null){
    //             $arr = array('a','b','c','d','e','f');
    //             shuffle($arr);
    //             $post_count = '0'.explode($arr);
    //             dsetcookie('pc_size_c', $post_count, 24*60*60);
    //         }else{
    //             $post_count = intval($post_count);
    //             $post_num = intval($_G['cache']['plugin']['geetest']['post_num']);
    //         if($post_num != 0 && $post_count >= $post_num){
    //             $this->captcha_allow = false;
    //         }
    //     }
    //     // var_dump($_G['group']);
    // }
          

    //初始化get.php
    function global_cpnav_top(){
        global $_G;
        include_once template('geetest:module');
        return tpl_global_cpnav_top();
    }

    function global_login_extra() {
        global $_G;
        include_once template('geetest:module');
        return tpl_global_login_extra();
    }


    public function geetest_validate($challenge, $validate, $seccode){
        $geetest = new geetestlib();
        // $geetest->set_keyset($this->keyset);
        return $geetest->validate($challenge, $validate, $seccode);
    }


}

include('plugin_class/plugin_geetest_member.class.php');

include('plugin_class/plugin_geetest_forum.class.php');

include('plugin_class/plugin_geetest_home.class.php');

include('plugin_class/plugin_geetest_group.class.php');

?>