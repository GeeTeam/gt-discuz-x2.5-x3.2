<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

$url = "http://www.geetest.com/install/sections/idx-plugins.html#discuz";
$html = <<<HTML
    <iframe src="{$url}" style="height:820px;width:1200px;border:none;background:white"></iframe>

HTML;
echo $html;







 ?>