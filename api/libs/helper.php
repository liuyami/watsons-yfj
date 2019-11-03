<?php
/**
 * 自定义函数
 */


/**
 * 获取 post 参数; 在 content_type 为 application/json 时，自动解析 json
 * @return array
 */
function initPostData()
{
    if (empty($_POST) && false !== strpos($_SERVER['CONTENT_TYPE'], 'application/json')) {
        $content = file_get_contents('php://input');
        $post    = (array)json_decode($content, true);
    } else {
        $post = $_POST;
    }
    return $post;
}


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

/**
 * 是否为一个合法的email
 * @param sting $email
 * @return boolean
 */
function is_email($email){
    if (filter_var ($email, FILTER_VALIDATE_EMAIL )) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否为一个合法的url
 * @param string $url
 * @return boolean
 */
function is_url($url){
    if (filter_var ($url, FILTER_VALIDATE_URL )) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否为整数
 * @param int $number
 * @return boolean
 */
function is_inter($number){
    if(preg_match('/^[-\+]?\d+$/',$number)){
        return true;
    }else{
        return false;
    }
}

/**
 * 是否为正整数
 * @param int $number
 * @return boolean
 */
function is_positive_number($number){
    if(ctype_digit ($number)){
        return true;
    }else{
        return false;
    }
}

/**
 * 验证日期格式是否正确
 * @param string $date
 * @param string $format
 * @return boolean
 */
function is_date($date,$format='Y-m-d'){
    $t = date_parse_from_format($format,$date);
    if(empty($t['errors'])){
        return true;
    }else{
        return false;
    }
}

/**
 * 是否为小数
 * @param float $number
 * @return boolean
 */
function is_decimal($number){
    if(preg_match('/^[-\+]?\d+(\.\d+)?$/',$number)){
        return true;
    }else{
        return false;
    }
}
/**
 * 是否为正小数
 * @param float $number
 * @return boolean
 */
function is_positive_decimal($number){
    if(preg_match('/^\d+(\.\d+)?$/',$number)){
        return true;
    }else{
        return false;
    }
}

/**
 * curl POST
 *
 * @param   string  url
 * @param   array   数据
 * @return  string
 */
function curlPost($url, $data = []) {
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    curl_close($ch);
    
    return $output;
}

/**
 * PHP发送Json对象数据
 *
 * @param $url 请求url
 * @param $jsonStr 发送的json字符串
 * @return array
 */
function http_post_json($url, $jsonStr)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                       'Content-Type: application/json; charset=utf-8',
                       'Content-Length: ' . strlen($jsonStr)
                   )
    );
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $response;
}