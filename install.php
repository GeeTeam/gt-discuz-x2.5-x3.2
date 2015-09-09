<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

global $_G;
if (!is_dir(DISCUZ_ROOT . '/data/plugindata/geetest')) {
    mkdir(DISCUZ_ROOT . '/data/plugindata/geetest', 0777);
    @chmod(DISCUZ_ROOT . '/data/plugindata/geetest', 0777);
}
$addurl = 'http://account.geetest.com/api/discuz/add?token=' . md5('discuz' . (string)time()) . '&random=' . rand(1000000000, 9999999999);
$web_result = dfsockopen($addurl);
$web_result = json_decode($web_result, true);
$web_data = array('captchaid' => $web_result['captchaid'], 'privatekey' => $web_result['privatekey']);
$mobileurl = 'http://account.geetest.com/api/discuz/mobile?token=' . md5('discuz' . (string)time()) . '&random=' . rand(1000000000, 9999999999);
$mobile_result = dfsockopen($mobileurl);
$mobile_result = json_decode($mobile_result, true);
$mobile_data = array('captchaid' => $mobile_result['captchaid'], 'privatekey' => $mobile_result['privatekey']);
$config = array('webset' => $web_data, 'mobileset' => $mobile_data);
$str = "<?php\n";
$str.= 'return ' . var_export($config, true) . '; ?>';
file_put_contents(DISCUZ_ROOT . '/data/plugindata/geetest/config.php', $str);
@chmod(DISCUZ_ROOT . '/data/plugindata/geetest/config.php', 0777);
$finish = TRUE;
?>