<?php 
/**
 * 使用Get的方式返回：challenge和capthca_id 此方式以实现前后端完全分离的开发模式 专门实现failback
 * @author Tanxu
 */
// error_reporting(0);
require_once dirname(__FILE__) . '/lib/geetestlib.php';
$config = include dirname(dirname(dirname(dirname(__FILE__)))).'/data/plugindata/geetest/config.php';
$keyset=$config['webset'];
$GtSdk = new geetestLib($keyset);
session_start();
$_SESSION['gtsdk'] = $GtSdk;
$return = $GtSdk->register();
if ($return) {
    $_SESSION['gtserver'] = 1;
    $result = array(
            'success' => 1,
            'gt' => $keyset['captchaid'],
            'challenge' => $GtSdk->challenge
        );
    echo json_encode($result);
}else{
    $_SESSION['gtserver'] = 0;
    $rnd1 = md5(rand(0,100));
    $rnd2 = md5(rand(0,100));
    $challenge = $rnd1 . substr($rnd2,0,2);
    $result = array(
            'success' => 0,
            'gt' => $keyset['captchaid'],
            'challenge' => $challenge
        );
    $_SESSION['challenge'] = $result['challenge'];
    echo json_encode($result);
}
       
 ?>