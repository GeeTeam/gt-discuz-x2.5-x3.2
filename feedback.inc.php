<?php

// error_reporting(E_ALL);

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
include_once DISCUZ_ROOT . "/source/plugin/geetest/lib/geetestlib.php";

//语言包
$assist = plang('assist');
$website = plang('website');
$tutorial_label = plang('tutorial_label');
$contact = plang('contact');
$QQqun = plang('QQqun');
$about_link_commerce = plang('about_link_commerce');
$about_link_bug = plang('about_link_bug');
$about_link_product = plang('about_link_product');
$about_link_tech = plang('about_link_tech');

$warn = <<<HTML

    <table class="tb tb2 ">
        <tbody>
        <tr><th colspan="15" class="partition">$assist</th></tr>
            <tr>
                <td>$tutorial_label <a href="http://www.geetest.com/install/sections/idx-plugins.html#discuz" target="_Blank">http://www.geetest.com/install/sections/idx-plugins.html#discuz</a>
                </td>   
            </tr>
            <tr>
                <td>$website :<a href="http://www.geetest.com" target="_Blank">http://www.geetest.com</a>
                </td>
                
                
            </tr>
            <tr>
                <td>$contact :contact@geetest.com
                </td>
            </tr>
            <tr>
                <td>$QQqun :162669835
                </td>
            </tr>

        </br>
    
        <tr class="noborder"><td class="vtop tips2" s="1"><a target="_self" href="http://wpa.qq.com/msgrd?v=3&amp;uin=800094077&amp;site=qq&amp;menu=yes">$about_link_product<img src="http://wpa.qq.com/pa?p=2:800094077:44"></a></td></tr>
        
        </tbody>
    </table>

HTML;

echo ($warn);



function plang($str) {
    return lang('plugin/geetest', $str);
}
?>