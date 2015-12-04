<?php

// error_reporting(E_ERROR);

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
loadcache('plugin');
loadcache('gt_cache');
C::import('geetestlib', 'plugin/geetest/lib');

class plugin_geetest
{
    public $captcha_allow = false;
    public $mods = array();
    public $keyset = array();
    public $style = array();
    public $config = array();
    public $open;
    
    function plugin_geetest() {
        global $_G;
        
        //读缓存信息
        $this->mods = unserialize($_G['cache']['plugin']['geetest']['mod']);
        $this->open = $_G['cache']['plugin']['geetest']['open'];
        if ($_G['cache']['gt_cache'] == "" || $_G['cache']['gt_cache'] == null || !isset($_G['cache']['gt_cache'])) {
            $config = @include DISCUZ_ROOT . 'source/plugin/geetest/lib/config.php';
            $this->keyset = $config['keyset'];
        } 
        else {
            $this->keyset = $_G['cache']['gt_cache'];
        }
        $this->style = $_G['cache']['plugin']['geetest'];
        
        //初始化
        if ($this->open == '1') {
            
            //登陆注册不需要选择用户组
            if (CURMODULE == "logging" || CURMODULE == "register") {
                $this->captcha_allow = true;
            } 
            else if (in_array($_G['groupid'], unserialize($_G['cache']['plugin']['geetest']['groupid']))) {
                $this->captcha_allow = true;
            } 
            else {
                $this->captcha_allow = false;
            }
        } 
        else {
            $this->captcha_allow = false;
        }
        
        //发帖大于限定数，则不用插件
        $post_count = $_G['cookie']['pc_size_c'];
        if ($post_count == null) {
            $arr = array('a', 'b', 'c', 'd', 'e', 'f');
            shuffle($arr);
            $post_count = '0' . explode($arr);
            dsetcookie('pc_size_c', $post_count, 24 * 60 * 60);
        } 
        else {
            $post_count = intval($post_count);
            $post_num = intval($_G['cache']['plugin']['geetest']['post_num']);
            if ($post_num != 0 && $post_count >= $post_num) {
                $this->captcha_allow = false;
            }
        }
        
        // var_dump($_G['group']);
        
    }
    
    //修复QQ互联注册
    function _fix_register($gt_geetest_id) {
        $output = <<<JS
    <script type="text/javascript">
        function move_fast_geetest_before_submit() {
            var registerformsubmit = $('registerformsubmit');
            var geetest = $('$gt_geetest_id');
            registerformsubmit.parentNode.insertBefore(geetest, registerformsubmit);
        }
        _attachEvent(window, 'load', move_fast_geetest_before_submit);

    </script>
JS;
        return $output;
    }
    
    //QQ互联注册嵌入点
    public function global_header() {
        $cur = CURMODULE;
        if ($cur == "connect") {
            $cur_mod = "popup";
            $gt_geetest_id = "gt_ global_header";
            $btn_id = "registerformsubmit";
            return $this->_code_output($cur_mod, $gt_geetest_id, '', $btn_id) . $this->_fix_register($gt_geetest_id);
        }
    }
    
    function _fix_header_login($btn_id, $gt_geetest_id) {
        $output = <<<JS
    <script type="text/javascript">
    function add_botton(){
        //add submit button
        var lsform = $('lsform');
        var o = document.createElement("button");  
        o.id = "$btn_id";       
        o.setAttribute('type', 'submit');                               
        o.value = ""; 
        o.style.display="none";
        lsform.appendChild(o);
        var geetest = $('$gt_geetest_id');
        o.parentNode.insertBefore(geetest, o);
    }
    _attachEvent(window, 'load', add_botton);

    </script>
JS;
        return $output;
    }
    
    public function global_footer() {
        global $_G;
        
        // echo $_G['uid'];
        if ($_G['uid'] == '1') {
            return;
        } 
        else if ($_G['uid'] == '0') {
            $cur_mod = "popup";
            $gt_geetest_id = "gt_header_logging_input";
            $btn_id = "header-loggin-btn";
            return $this->_fix_header_login($btn_id, $gt_geetest_id) . $this->login_captcha($cur_mod, $gt_geetest_id, '', $btn_id);
        }
    }
    
    public function login_captcha($cur_mod, $geetest_id, $page_type, $param) {
        $geetestlib = new geetestlib();
        global $_G;
        if ($geetestlib->register($this->keyset['captchaid'])) {
            $captcha = "<div id='$geetest_id'>";
            $captcha.= $geetestlib->get_widget($this->keyset['captchaid'], 'popup', $param,1);
            $captcha.= '</div>';
            return $captcha;
        } 
        else {
            return;
        }
    }
    
    public function _code_output($cur_mod = '', $geetest_id = 'gt_geetest', $page_type = '', $param = '') {
        
        if (!($this->_cur_mod_is_valid())) {
            return;
        }
        if (!$this->captcha_allow) {
            return;
        }
        global $_G;
        
        $output = '';
        $cur_mod = empty($cur_mod) ? CURMODULE : $cur_mod;
        $style = $this->getStyle($page_type);
        $geetestlib = new geetestlib();
        if ($geetestlib->register($this->keyset['captchaid'])) {
            
            switch ($cur_mod) {
                case 'register':
                case 'logging':
                    $output = " <div id='$geetest_id' class='rfm' style='$style'><table><tbody><tr><th><div>*&#28369;&#21160;&#39564;&#35777;:</div></th><td>";
                    
                    $output.= $geetestlib->get_widget($this->keyset['captchaid'], 'float');
                    $output.= '</td></tr></tbody></table></div>';
                    break;

                case 'newthread':
                case 'reply':
                case 'edit':
                    $output = "<div id='$geetest_id' class='' style='$style'><table><tbody><tr><th style='width:80px;'><div id='gt_tx'>*&#28369;&#21160;&#39564;&#35777;:</div></th><td>";
                    
                    $output.= $geetestlib->get_widget($this->keyset['captchaid'], 'float');
                    $output.= '</td></tr></tbody></table></div>';
                    break;

                case 'blog':
                case 'follow':
                case 'comment':
                    $output = "<div id='$geetest_id' class='' style='$style'><table><tbody><tr><th style='width:80px;'><div>*&#28369;&#21160;&#39564;&#35777;:</div></th><td>";
                    
                    $output.= $geetestlib->get_widget($this->keyset['captchaid'], 'float');
                    $output.= '</td></tr></tbody></table></div>';
                    break;

                case 'popup':
                    $output = "<div id='$geetest_id'>";
                    $output.= $geetestlib->get_widget($this->keyset['captchaid'], 'popup', $param);
                    $output.= '</div>';
                    break;
            }
            
            return $output;
        } 
        else {
            return;
        }
    }

    
    public function geetest_validate($challenge, $validate, $seccode) {
        $geetest = new geetestlib();
        $geetest->set_keyset($this->keyset);
        return $geetest->validate($challenge, $validate, $seccode);
    }
    
    public function logging_mod_valid() {
        $mod = "2";
        return in_array($mod, $this->mods);
    }
    
    public function _cur_mod_is_valid() {
        $cur = CURMODULE;
        switch (CURMODULE) {
            case "logging":
                $mod = "2";
                break;

            case "register":
                $mod = "1";
                break;

            case "post":
                if ($_GET["action"] == "reply") {
                    $mod = "4";
                } 
                else if ($_GET["action"] == "newthread") {
                    $mod = "3";
                } 
                else if ($_GET["action"] == "edit") {
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
                if ($_GET["ac"] == "blog") {
                    $mod = "7";
                }
                if ($_GET["ac"] == "comment") {
                    $mod = "8";
                }
                if ($_GET["ac"] == "follow") {
                    $mod = "6";
                }
                if ($_GET["ac"] == "credit") {
                    $mod = "9";
                }
                break;

            case "space":
                if ($_GET["do"] == "wall") {
                    $mod = "8";
                }
                if ($_GET["do"] == "blog" || $_GET["do"] == "index") {
                    $mod = "7";
                } 
                else {
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
        
        private function getStyle($page_type) {
            $style_str = $style_str = $this->style[$page_type];
            
            $style_arr = explode(" ", $style_str);
            $top = $style_arr[0] == "auto" ? "auto" : $style_arr[0] . 'px ';
            $bottom = $style_arr[1] == "auto" ? "auto" : $style_arr[1] . 'px ';
            $left = $style_arr[2] == "auto" ? "auto" : $style_arr[2] . 'px ';
            $right = $style_arr[3] == "auto" ? "auto" : $style_arr[3] . 'px';
            $margin = "margin:" . $top . ' ' . $right . ' ' . $bottom . ' ' . $left;
            return $margin;
        }
}

include ('plugin_class/plugin_geetest_member.class.php');

include ('plugin_class/plugin_geetest_forum.class.php');

include ('plugin_class/plugin_geetest_home.class.php');

include ('plugin_class/plugin_geetest_group.class.php');
?>