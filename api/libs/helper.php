<?php
/**
 * 自定义函数
 */

function output($data, $format='json') {
    
    if($format == 'json') {
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($data);
        exit;
        
    } else if($format == 'jsonp') {
        header("Content-Type: application/javascript; charset=UTF-8");
        $callback = $_GET['callback'];
        echo $callback.'('.json_encode($data).')';
        exit;
    }
}


function redirect($url) {
    header('location:'. $url);
    exit;
}

/**
 * 打印变量
 */
function dump($vars)
{
    ob_start();
    var_dump($vars);
    $output = ob_get_clean();
    $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
    if (PHP_SAPI == 'cli') {
        $output = PHP_EOL . $output . PHP_EOL;
    } else {
        $output = '<pre>' . $output . '</pre>';
    }

    echo $output;    
}


/**
 * 创建一个随机字符串
 *
 * @return string
 */
function createUnique() {
    
    $data = time().$_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . microtime(true) . mt_rand();
    
    return md5($data);
}


/**
 * 加密
 * @param $data 待加密数据，可以是字符串、数组
 * @param $encryption_key 秘钥
 *
 * @return bool|string
 */
function aes_encode ($data, $encryption_key) {
    $seeds = '0123456789abcdefghijklmnopqrstuvwxyz';
    
    $length = mb_strlen($encryption_key, '8bit');
    
    if (!$length === 16) {
        return false;
    }
    
    $iv = substr(str_shuffle(str_repeat($seeds, $length)), 0, $length);
    
    $value = openssl_encrypt(serialize($data), 'aes-256-cbc', $encryption_key, 0, $iv);
    
    if ($value === false) {
        return false;
    }
    
    $iv = base64_encode($iv);
    
    $mac = hash_hmac('sha256', $iv.$value, $encryption_key);
    
    $json = json_encode(compact('iv', 'value', 'mac'));
    
    if (! is_string($json)) {
        return false;
    }
    
    return base64_encode($json);
}

/**
 * 解密
 *
 * @param $payload 待解密的数据
 * @param $encryption_key 秘钥
 *
 * @return bool|mixed
 */
function aes_decode($payload, $encryption_key) {
    $length = mb_strlen($encryption_key, '8bit');
    
    if (!$length === 16) {
        return false;
    }
    
    $payload = json_decode(base64_decode($payload), true);
    
    if (! $payload || ! is_array($payload) || ! isset($payload['iv']) || ! isset($payload['value']) || ! isset($payload['mac'])) {
        return false;
    }
    
    $seeds  = '0123456789abcdefghijklmnopqrstuvwxyz';
    $length = 16;
    
    $bytes   = substr(str_shuffle(str_repeat($seeds, $length)), 0, $length);
    $hash    = hash_hmac('sha256', $payload['iv'].$payload['value'], $encryption_key);
    $calcMac = hash_hmac('sha256', $hash, $bytes, true);
    
    if (! hash_equals(hash_hmac('sha256', $payload['mac'], $bytes, true), $calcMac)) {
        return false;
    }
    
    $iv = base64_decode($payload['iv']);
    
    $decrypted = openssl_decrypt($payload['value'], 'aes-256-cbc', $encryption_key, 0, $iv);
    
    if ($decrypted === false) {
        return false;
    }
    
    return unserialize($decrypted);
}

/**
 * 生成签名
 * @param $client_id
 * @param $encryption_key
 *
 * @return bool|string
 */
function createSign($client_id, $encryption_key) {
    $timestamp = time();
    
    $data = [
        'client_id'    => $client_id,
        'timestamp' => $timestamp
    ];
    
    $http_build_str = http_build_query($data);
    $sign = aes_encode($http_build_str, $encryption_key);
    
    return $sign;
}

/**
 * 解析签名
 *
 * @param $sign
 * @param $encryption_key
 *
 * @return array ['client_id', 'timestamp']
 */
function decodeSign($sign, $encryption_key) {
    $http_build_str = aes_decode($sign, $encryption_key);
    
    parse_str($http_build_str, $data);
    
    return $data;
}

/**
 * 图像转换成base64
 */
function imgToBase64($url, $filetype='jpeg') {
    
    $image = file_get_contents($url);

    if ($image !== false){
        return 'data:image/'.$filetype.';base64,'.base64_encode($image);
    } 

    return false;
}