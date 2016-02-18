<?php 
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}
date_default_timezone_set("UTC"); 
require_once DISCUZ_ROOT.'source/plugin/geetest/lib/geetestlib.php';
$config = include DISCUZ_ROOT . '/source/plugin/geetest/lib/config.php';

$geetestlib = new geetestlib();
loadcache('gt_cache');
loadcache('gt_mobile');


$web_id_key = plang("web_id_key");
$mobile_id_key = plang("mobile_id_key");
$web_captcha = plang("web_captcha");
$web_private = plang("web_private");
$mobile_captcha = plang("mobile_captcha");
$mobile_private = plang("mobile_private");

$relevance_geetest_account = plang("relevance_geetest_account");
$id_note = plang("id_note");
$key_note =plang("key_note");
$web_button_note = plang("web_button_note");
$mobile_button_note = plang("mobile_button_note");
$modify_id_key = plang("modify_id_key");
$click_modify = plang("click_modify");
$remind_1 = plang("remind_1");
$remind_2 = plang("remind_2");
$remind_3 = plang("remind_3");
$remind_4 = plang("remind_4");
$remind_5 = plang("remind_5");
$html = <<<HTML
<script src="http://code.jquery.com/jquery-1.6.min.js" type="text/javascript"></script>
<script src="./source/plugin/geetest/js/web_keyset.js" type="text/javascript"></script>
<script src="./source/plugin/geetest/js/mobile_keyset.js" type="text/javascript"></script>

    <form action="" method="post">


    <table class="tb tb2 ">
        <tbody>
        <tr>
            <th colspan="15" class="partition">$web_id_key
            </th>
        </tr>
        <tr>
            <td class="td27" s="1">$web_captcha</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">     

                <input name="id" value="{$_G['cache']['gt_cache']['captchaid']}" type="text" class="txt" id="web_captcha" style="border:none;" disabled="disabled">

            </td>
            <td class="vtop tips2" s="1" ><span id="msg_web_id"></span><span id="label_web_id">$id_note</span>
            </td>
        </tr>
        <tr>
            <td class="td27" s="1">$web_private<span></span></td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <input name="key" value="{$_G['cache']['gt_cache']['privatekey']}" type="text" class="txt" id="web_private" style="border:none;" disabled="disabled">
            </td>
            <td class="vtop tips2" s="1"><span id="msg_web_key"></span><span id="label_web_key">$key_note</span>
            </td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
            <div id="web_btn" style="width: 113px;">
                    <div class="web_set1" style="">$modify_id_key</div>
                    <div class="web_set2" style="display:none;">$click_modify</div>
            </div>

            </td>
                <td class="vtop tips2" s="1">$web_button_note
            </td>
        </tr>
        </tbody>
    </table>
    </form>

     <form action="" method="post">
        <div style="width:780px;color:red;">
            <br>
            <p>$remind_1</p>
            <p>$remind_2</p>
            <p>$remind_3</p><br>
        </div>
    <table class="tb tb2 ">
        <tbody>
        <tr>
            <th colspan="15" class="partition">$mobile_id_key
            </th>
        </tr>
        <tr>
            <td class="td27" s="1">$mobile_captcha</td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">     

                <input name="id" value="{$_G['cache']['gt_mobile']['captchaid']}" type="text" class="txt" id="mobile_captcha" style="border:none;" disabled="disabled">

            </td>
            <td class="vtop tips2" s="1" ><span id="msg_mobile_id"></span><span id="label_mobile_id">$id_note</span>
            </td>
        </tr>
        <tr>
            <td class="td27" s="1">$mobile_private<span></span></td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
                <input name="key" value="{$_G['cache']['gt_mobile']['privatekey']}" type="text" class="txt" id="mobile_private" style="border:none;" disabled="disabled">
            </td>
            <td class="vtop tips2" s="1"><span id="msg_mobile_key"></span><span id="label_mobile_key">$key_note</span>
            </td>
        </tr>
        <tr class="noborder">
            <td class="vtop rowform">
            <div id="mobile_btn" style="width: 113px;">
                    <div class="mobile_set1" style="">$modify_id_key</div>
                    <div class="mobile_set2" style="display:none;">$click_modify</div>
            </div>

            </td>
                <td class="vtop tips2" s="1">$mobile_button_note
            </td>
        </tr>
        </tbody>
    </table>
    </form>
            <div style="width:780px;color:red;">
            <br>
            <p>$remind_4</p>
    
            <p>$remind_5</p><br>

        </div>
    <style type="text/css">
    .web_set1,.web_set2,.mobile_set1,.mobile_set2,.rele{
  background-color: #00b7f1;
  color: #fff !important;
  padding: 0 20px;
  height: 22px;
  line-height: 22px;
  position: relative;
  cursor:pointer;
    }
    </style>
HTML;
echo $html;


if (!empty($_POST['web_keyset'])) {

    $web_keyset = $_POST['web_keyset'];
    $gt_cache = check($web_keyset);
    $token = md5('discuz' . (string)time());
    $post_data = array('captchaid' => $gt_cache['captchaid'], 'privatekey' => $gt_cache['privatekey'], 'token' => $token);
    $result_cache = $geetestlib->send_post("http://account.geetest.com/api/discuz/get", $post_data);
    $result1 = json_decode($result_cache, true);
    savecache('is_md5',$result1['challenge']); 
    if (($result1['res'] == 0 || $result1['res'] == 1) && $result1['mobile'] == 0) {
        savecache('gt_cache',$gt_cache); 
        $config['keyset'] = $gt_cache;
        file_put_contents(DISCUZ_ROOT . '/source/plugin/geetest/lib/config.php', "<?php\n" . " return " . var_export($config, true) . ";?>");
    }
}
if (!empty($_POST['mobile_keyset'])) {
    $mobile_keyset = $_POST['mobile_keyset'];
    $gt_mobile = check($mobile_keyset);
    $token = md5('discuz' . (string)time());
    $post_data = array('captchaid' => $gt_mobile['captchaid'], 'privatekey' => $gt_mobile['privatekey'], 'token' => $token);
    $result_mobile = $geetestlib->send_post("http://account.geetest.com/api/discuz/get", $post_data);
    $result2 = json_decode($result_mobile,true);
    savecache('is_md5',$result1['challenge']); 
    if (($result2['res'] == 0 || $result2['res'] == 1) && $result2['mobile'] == 1) {
        savecache('gt_mobile',$gt_mobile); 
        $config['mobile'] = $gt_mobile;
        file_put_contents(DISCUZ_ROOT . '/source/plugin/geetest/lib/config.php', "<?php\n" . " return " . var_export($config, true) . ";?>");
    }
}

function check($data){
    if ($data != "" || $data != null ) {
        $keyset = explode("/", $data);
        $keyset['0'] = trim($keyset['0']);
        $keyset['1'] = trim($keyset['1']);
        $geetest_key = array(
                'captchaid'=>$keyset['0'],
                'privatekey'=>$keyset['1'],
            );
        return $geetest_key;
    }
}


$geetest_account = plang("geetest_account");
$id_and_key_error = plang("id_and_key_error");
$not_relevance = plang("not_relevance");
$relevance = plang("relevance");
$commission_withdrawal = plang("commission_withdrawal");
$acquisition = plang("acquisition");
$acquisition_note = plang("acquisition_note");
$yes = plang('yes');
$no = plang('no');

$post_data = array('captchaid' => $_G['cache']['gt_cache']['captchaid'], 'privatekey' => $_G['cache']['gt_cache']['privatekey'], 'token' => md5('discuz' . (string)time()));
$result = $geetestlib->send_post('http://account.geetest.com/api/discuz/get', $post_data);

$result = json_decode($result, true);
if ($result['res'] == - 1) {
    $html = <<<HTML
        <table class="tb tb2 ">
            <tbody>

            <tr>
                <th colspan="15" class="partition">$geetest_account
                </th>
            </tr>
            <tr>
                <td class="td27" s="1" style="color:red;">$id_and_key_error</td>
            </tr>
            
            </tbody>
        </table>
HTML;
    echo $html;
} 
elseif ($result['res'] == 0) {
    $privatekey = md5($_G['cache']['gt_cache']['privatekey']);
    $html = <<<HTML
        <table class="tb tb2 ">
            <tbody>
            <tr>
                <th colspan="15" class="partition">$geetest_account
                </th>
            </tr>
            <tr>
                <td class="td27" s="1">$not_relevance</td>
            </tr>
            <tr>
                <td>
                    <div class = "rele" style="width:65px;"><a style="  color: white;text-decoration: none;" target="view_window" href="http://account.geetest.com/discuz/{$_G['cache']['gt_cache']['captchaid']}/{$privatekey}">
                    $relevance_geetest_account
                </a>
                    </div>
                </td>

            </tr>
            </tbody>
        </table>
HTML;
    echo $html;
} 
elseif ($result['res'] == 1) {
    $html = <<<HTML
    <table class="tb tb2 ">
        <tbody>
        <tr>
            <th colspan="15" class="partition">$geetest_account
            </th>
        </tr>
        <tr>
            <td class="td27" s="1">$relevance:{$result['loginname']}</td>
        </tr>
        </tbody>
    </table>

    <table class="tb tb2 ">
        <tbody>
        <tr>
            <th colspan="15" class="partition">
            </th>
        </tr>
      
        
        </tbody>
    </table>
HTML;
    echo $html;
}

function plang($str) {
    return lang('plugin/geetest', $str);
}
?>