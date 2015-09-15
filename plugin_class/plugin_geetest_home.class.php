<?php
class plugin_geetest_home extends plugin_geetest
{
    
    //支付
    function spacecp_credit_bottom() {
        global $_G;
        return $this->return_captcha("tpl_spacecp_credit_bottom", "home");
    }
    
    //广播
    function follow_top() {
        global $_G;
        return $this->return_captcha("tpl_follow_top", "home");
    }
    
    //日志
    function spacecp_blog_middle() {
        global $_G;
        return $this->return_captcha("tpl_spacecp_blog_middle", "home");
    }
    
    //日志评论
    function space_blog_face_extra() {
        global $_G;
        return $this->return_captcha("tpl_space_blog_face_extra", "home");
    }
    
    //留言板
    function space_wall_face_extra() {
        global $_G;
        return $this->return_captcha("tpl_space_wall_face_extra", "home");
    }
    
    //处理广播、日志验证
    function spacecp_follow_recode() {
        $this->spacecp_recode();
    }
    function spacecp_blog_recode() {
        $this->spacecp_recode();
    }
    function spacecp_comment_recode() {
        $this->spacecp_recode();
    }
    
    function spacecp_recode() {
        if (!$this->has_authority()) {
            return;
        }
        global $_G;
        $success = 0;
        session_start();
        if ($this->captcha_allow) {
            if (submitcheck('topicsubmit', 0, $seccodecheck, $secqaacheck) || submitcheck('blogsubmit', 0, $seccodecheck, $secqaacheck) ) {
                if ($_SESSION['gtserver'] == 1) {
                    $response = $this->geetest->validate($_GET['geetest_challenge'], $_GET['geetest_validate'], $_GET['geetest_seccode']);
                    if ($response != 1) {
                        if ($response == - 1) {
                            showmessage(lang('plugin/geetest', 'seccode_expired'));
                        }else if ($response == 0) {
                            showmessage(lang('plugin/geetest', 'seccode_invalid'));
                        }
                    }else {
                        $success = 1;
                    }
                }else {
                    if (!$this->geetest->get_answer($_POST['geetest_validate'])) {
                        showmessage(lang('plugin/geetest', 'seccode_invalid'));
                    } 
                    else {
                        $success = 1;
                    }
                }
            }
        }
        if ($success == 1) {
            $post_count = $_G['cookie']['pc_size_c'];
            $post_count = intval($post_count);
            $post_count = ($post_count + 1);
            $arr = array('a', 'b', 'c', 'd', 'e', 'f');
            shuffle($arr);
            $post_count = $post_count . implode("", $arr);
            dsetcookie('pc_size_c', $post_count);
        }
    }
    
    //判断是否有权限发帖留言，或者其他
    function has_authority() {
        
        //针对掌上论坛不需要验证
        if ($_GET['mobile'] == 'no' && $_GET['submodule'] == 'checkpost') {
            return false;
        }
        global $_G;
        
        $action = $_GET['ac'];
        
        //快速回复是否开启,并且有发帖权限,日志
        if ($action == 'follow' && $_G['group']['allowpost'] != 1) {
            
            //发帖
            return false;
        } 
        else if ($action == 'blog' && $_G['group']['allowblog'] != 1) {
            
            //回复
            return false;
        } 
        else if ($action == 'comment' && $_G['group']['allowcomment'] != 1) {
            
            //回复
            return false;
        }
        return true;
    }
}
