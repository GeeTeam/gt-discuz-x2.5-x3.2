<?php 
class plugin_geetest_group  extends plugin_geetest{  
    
    function post_middle(){
        global $_G;
        return $this->return_captcha("tpl_post_middle","forum");
    }


    function post_infloat_middle() { 
        global $_G;
        return $this->return_captcha("tpl_post_infloat_middle","forum");
    }

    //页面底部发帖
    function forumdisplay_fastpost_btn_extra() {
        global $_G;
        return $this->return_captcha("tpl_forumdisplay_fastpost_btn_extra","forum");
    }

    //页面底部回复
    function viewthread_fastpost_btn_extra() {
        global $_G;
        return $this->return_captcha("tpl_viewthread_fastpost_btn_extra","forum");
    }

    //处理发帖/恢复/编辑验证
    function post_recode() {
        global $_G;
        if( ! $this->has_authority() ){
            return;
        }
        $success = 0;
        session_start();
        if($this->captcha_allow) {
            if(submitcheck('topicsubmit', 0, $seccodecheck, $secqaacheck) || submitcheck('replysubmit', 0, $seccodecheck, $secqaacheck) || submitcheck('editsubmit', 0, $seccodecheck, $secqaacheck) ) {
                if($_SESSION['gtserver'] ==1){
                    $response = $this->geetest->validate($_GET['geetest_challenge'], $_GET['geetest_validate'], $_GET['geetest_seccode']);
                    if($response != 1){
                        if($response == -1){
                            showmessage(lang('plugin/geetest', 'seccode_expired'));
                        }else if($response == 0){
                            showmessage( lang('plugin/geetest', 'seccode_invalid') );
                        }
                    }else{
                        $success = 1;
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

    //判断是否有权限发帖留言，或者其他
    function has_authority(){
        //2.5版本不存在快速回复
        global $_G;
        include_once(DISCUZ_ROOT.'/source/discuz_version.php');
        if(DISCUZ_VERSION == "X2.5" && $_GET['handlekey'] == "vfastpost"){
             return false ;
        }
        //针对掌上论坛不需要验证
        if( $_GET['mobile'] == 'no' && $_GET['module'] == 'sendreply' ){
            return false;
        }
        if( $_GET['mobile'] == 'no' && $_GET['module'] == 'newthread' ){
            return false;
        }
        //针对广播，回复不好验证。有三处回复
        if( $_GET['action'] == 'reply' && $_GET['inajax'] == '1' &&  $_GET['handlekey'] != 'reply' &&  $_GET['infloat'] != 'yes'){
            return false;
        }
        $action = $_GET['action'];
        //快速回复是否开启,并且有发帖权限,日志
        if($action == 'newthread' && $_G['group']['allowpost'] != 1 ){//发帖
            return false;
        }else if($action == 'reply' && $_G['group']['allowreply'] != 1 ){//回复
            return false;
        }
        return true;
    }
}

 ?>