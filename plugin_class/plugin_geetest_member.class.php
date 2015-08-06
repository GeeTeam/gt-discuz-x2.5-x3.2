<?php
class plugin_geetest_member  extends plugin_geetest{  

    function register_input_output(){       
        global $_G;
        return $this->return_captcha("tpl_register_input_output","member");
    }

    function logging_input_output(){
        global $_G;
        return $this->return_captcha("tpl_logging_input_output","member");
    }

    function register_code(){
        global $_G;
        $cur = CURMODULE;
        session_start();
        if($this->captcha_allow && $cur == "register") {
            if(submitcheck('regsubmit', 0, $seccodecheck, $secqaacheck)){
                if($_SESSION['gtserver'] ==1){
                    $response = $this->geetest->validate($_GET['geetest_challenge'], $_GET['geetest_validate'], $_GET['geetest_seccode']);
                    if($response != 1){
                        if($response == -1){
                            showmessage( lang('plugin/geetest', 'seccode_expired') );
                        }else if($response == 0){
                            showmessage(lang('plugin/geetest', 'seccode_invalid'));
                        }
                    }
                }else{
                    $validate = $_POST['geetest_validate'];
                    if ($validate) {
                        $value = explode("_",$validate);
                        $challenge = $_SESSION['challenge'];
                        $ans = $this->geetest->decode_response($challenge,$value['0']);
                        $bg_idx = $this->geetest->decode_response($challenge,$value['1']);
                        $grp_idx = $this->geetest->decode_response($challenge,$value['2']);
                        $x_pos = $this->geetest->get_failback_pic_ans($bg_idx ,$grp_idx);
                        if (abs($ans - $x_pos) < 4) {
                            $success=1;
                        }else{
                            showmessage( lang('plugin/geetest', 'seccode_expired') );
                        }
                    }else{
                        showmessage(lang('plugin/geetest', 'seccode_invalid'));
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
        session_start();
        if ($this->captcha_allow && $cur == "logging") {
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
        if($cur == "logging" && $this->captcha_allow) {
            if(submitcheck('loginsubmit', 1, $seccodestatus) && empty($_GET['lssubmit'])) {//
                if($_SESSION['gtserver'] ==1){
                    $response = $this->geetest->validate($_GET['geetest_challenge'], $_GET['geetest_validate'], $_GET['geetest_seccode']);
                    if($response != 1){//
                        if($response == -1){
                            showmessage(lang('plugin/geetest', 'seccode_expired'));
                        }else if($response == 0){
                            showmessage( lang('plugin/geetest', 'seccode_invalid'));
                        }
                    }
                }else{
                    $validate = $_POST['geetest_validate'];
                    if ($validate) {
                        $value = explode("_",$validate);
                        $challenge = $_SESSION['challenge'];
                        $ans = $this->geetest->decode_response($challenge,$value['0']);
                        $bg_idx = $this->geetest->decode_response($challenge,$value['1']);
                        $grp_idx = $this->geetest->decode_response($challenge,$value['2']);
                        $x_pos = $this->geetest->get_failback_pic_ans($bg_idx ,$grp_idx);
                        if (abs($ans - $x_pos) < 4) {
                            $success=1;
                        }else{
                            showmessage( lang('plugin/geetest', 'seccode_expired') );
                        }
                    }else{
                        showmessage(lang('plugin/geetest', 'seccode_invalid'));
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
   
    function has_authority(){
        //针对掌上论坛不需要验证
        if( $_GET['mobile'] == 'no' && $_GET['submodule'] == 'checkpost' ){
            return false;
        }
        return true;
    }

}
