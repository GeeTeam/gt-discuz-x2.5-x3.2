<?php

/*
 * Copyright (c) 2011 by geetest.com
 * Created: 2011-5-5
 * Function: geetest API php code
*/

define('GT_API_SERVER', 'http://api.geetest.com');
define('GT_SDK_VERSION', 'discuz_1.0');

class geetestlib
{
    public $keyset = array();
    
    public function __construct(&$config) {
        $this->keyset = $config;
        $this->challenge = "";
    }
    
    public static function get_widget_mobile($captcha) {
        return '<script type="text/javascript" src="http://api.geetest.com/get.php?gt=' . $captcha . '&product=embed&width=300" async></script>';
    }
    
    function register() {
        $url = "http://api.geetest.com/register.php?gt=" . $this->keyset['captchaid'];
        $this->challenge = $this->_send_request($url);
        
        if (strlen($this->challenge) != 32) {
            return 0;
        }
        return 1;
    }
    
    function validate($challenge, $validate, $seccode) {
        
        if (!$this->_check_validate($challenge, $validate)) {
            return FALSE;
        }
        $query = 'seccode=' . $seccode . "&gt_sdk_version=" . GT_SDK_VERSION;
        $codevalidate = $this->_http_post('api.geetest.com', '/validate.php', $query);
        if (strlen($codevalidate) > 0 && $codevalidate == md5($seccode)) {
            return 1;
        } 
        else if ($codevalidate == "false") {
            return 0;
        } 
        else {
            return $codevalidate;
        }
        return -1;
    }
    
    function _check_validate($challenge, $validate) {
        if (strlen($validate) != 32) {
            return FALSE;
        }
        if (md5($this->keyset['privatekey'] . 'geetest' . $challenge) != $validate) {
            return FALSE;
        }
        return TRUE;
    }
    
    private function _http_post($host, $path, $data, $port = 80) {
        if (function_exists("curl_exec")) {
            $url = "http://api.geetest.com/validate.php";
            $p = '/seccode=(.*?)&gt/';
            preg_match($p, $data, $seccode);
            $post_data = array("seccode" => $seccode[1], 'gt_sdk_version' => GT_SDK_VERSION,);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            $output = curl_exec($ch);
            curl_close($ch);
            return $output;
        } 
        else {
            $http_request = "POST $path HTTP/1.0\r\n";
            $http_request.= "Host: $host\r\n";
            $http_request.= "Content-Type: application/x-www-form-urlencoded\r\n";
            $http_request.= "Content-Length: " . strlen($data) . "\r\n";
            $http_request.= "\r\n";
            $http_request.= $data;
            $response = '';
            if (($fs = @fsockopen($host, $port, $errno, $errstr, 10)) == false) {
                die('Could not open socket! ' . $errstr);
            }
            fwrite($fs, $http_request);
            while (!feof($fs)) $response.= fgets($fs, 1160);
            fclose($fs);
            $response = explode("\r\n\r\n", $response, 2);
            return $response[1];
        }
    }
    
    public function send_post($url, $post_data) {
        
        $postdata = http_build_query($post_data);
        $options = array('http' => array('method' => 'POST', 'header' => 'Content-type:application/x-www-form-urlencoded', 'content' => $postdata, 'timeout' => 15 * 60
        
        // 超时时间（单位:s）
        ));
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
    
    private function _send_request($url) {
        if (function_exists('curl_exec')) {
            $ch = curl_init();
            $timeout = 2;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            $data = curl_exec($ch);
            curl_close($ch);
        } 
        else {
            $opts = array('http' => array('method' => "GET", 'timeout' => 2,));
            $context = stream_context_create($opts);
            $data = file_get_contents($url, false, $context);
        }
        return $data;
    }
    
    /**
     *解码随机参数
     *
     * @param $challenge
     * @param $string
     * @return
     */
    public function decode_response($challenge, $string) {
        if (strlen($string) > 100) {
            return 0;
        }
        $key = array();
        $chongfu = array();
        $shuzi = array("0" => 1, "1" => 2, "2" => 5, "3" => 10, "4" => 50);
        $count = 0;
        $res = 0;
        $array_challenge = str_split($challenge);
        $array_value = str_split($string);
        for ($i = 0; $i < strlen($challenge); $i++) {
            $item = $array_challenge[$i];
            if (in_array($item, $chongfu)) {
                continue;
            } 
            else {
                $value = $shuzi[$count % 5];
                array_push($chongfu, $item);
                $count++;
                $key[$item] = $value;
            }
        }
        
        for ($j = 0; $j < strlen($string); $j++) {
            $res+= $key[$array_value[$j]];
        }
        $res = $res - $this->decodeRandBase($challenge);
        return $res;
    }
    
    /**
     *
     * @param $x_str
     * @return
     */
    public function get_x_pos_from_str($x_str) {
        if (strlen($x_str) != 5) {
            return 0;
        }
        $sum_val = 0;
        $x_pos_sup = 200;
        $sum_val = base_convert($x_str, 16, 10);
        $result = $sum_val % $x_pos_sup;
        $result = ($result < 40) ? 40 : $result;
        return $result;
    }
    
    /**
     *
     * @param full_bg_index
     * @param img_grp_index
     * @return
     */
    public function get_failback_pic_ans($full_bg_index, $img_grp_index) {
        $full_bg_name = substr(md5($full_bg_index), 0, 9);
        $bg_name = substr(md5($img_grp_index), 10, 9);
        
        $answer_decode = "";
        
        // 通过两个字符串奇数和偶数位拼接产生答案位
        for ($i = 0; $i < 9; $i++) {
            if ($i % 2 == 0) {
                $answer_decode = $answer_decode . $full_bg_name[$i];
            } 
            elseif ($i % 2 == 1) {
                $answer_decode = $answer_decode . $bg_name[$i];
            }
        }
        $x_decode = substr($answer_decode, 4, 5);
        $x_pos = $this->get_x_pos_from_str($x_decode);
        return $x_pos;
    }
    
    /**
     * 输入的两位的随机数字,解码出偏移量
     *
     * @param challenge
     * @return
     */
    public function decodeRandBase($challenge) {
        $base = substr($challenge, 32, 2);
        $tempArray = array();
        for ($i = 0; $i < strlen($base); $i++) {
            $tempAscii = ord($base[$i]);
            $result = ($tempAscii > 57) ? ($tempAscii - 87) : ($tempAscii - 48);
            array_push($tempArray, $result);
        }
        $decodeRes = $tempArray['0'] * 36 + $tempArray['1'];
        return $decodeRes;
    }
    
    /**
     * 得到答案
     *
     * @param validate
     * @return
     */
    public function get_answer($validate) {
        if ($validate) {
            $value = explode("_", $validate);
            $challenge = $_SESSION['challenge'];
            $ans = $this->decode_response($challenge, $value['0']);
            $bg_idx = $this->decode_response($challenge, $value['1']);
            $grp_idx = $this->decode_response($challenge, $value['2']);
            $x_pos = $this->get_failback_pic_ans($bg_idx, $grp_idx);
            $answer = abs($ans - $x_pos);
            if ($answer < 4) {
                return 1;
            } 
            else {
                return 0;
            }
        } 
        else {
            return 0;
        }
    }
}

