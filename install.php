<?php 

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
loadcache("gt_cache");
loadcache("gt_mobile");
global $_G;

if (empty($_G['cache']['gt_cache']) || isset($_G['cache']['gt_cache'])) {

      $addurl = 'http://account.geetest.com/api/discuz/add?token='.md5('discuz'.(string)time()).'&random='.rand(1000000000,9999999999);
      $web_result = dfsockopen($addurl);
      $web_result = json_decode($web_result,true);
      file_put_contents("/home/tanxu/www/post.txt", print_r($web_result,true),FILE_APPEND);
      $web_data = array(
            'captchaid' => $web_result['captchaid'],
            'privatekey' => $web_result['privatekey']
        );
      savecache('gt_cache',$web_data);
}
if (empty($_G['cache']['gt_mobile']) || isset($_G['cache']['gt_mobile'])) {
      $mobileurl = 'http://account.geetest.com/api/discuz/mobile?token='.md5('discuz'.(string)time()).'&random='.rand(1000000000,9999999999);
      $mobile_result = dfsockopen($mobileurl);
      $mobile_result = json_decode($mobile_result,true);
      $mobile_data = array(
            'captchaid' => $mobile_result['captchaid'],
            'privatekey' => $mobile_result['privatekey']
        );
      savecache('gt_mobile',$mobile_data);
}


$finish = TRUE;

?>