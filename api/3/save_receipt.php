<?php
/**
 * 保存票据
 */
require '../bootstrap.php';
// var_dump($_SESSION);
$base64 = isset($_REQUEST['img']) ? trim($_REQUEST['img']) : null;
$openid = isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : null;


if(!$openid){
    $ret['errcode'] = 1;
    $ret['errmsg'] = 'OpenID不能为空';
    output($ret);
}

if(!$base64){
    $ret['errcode'] = 2;
    $ret['errmsg'] = '图像地址不正确';
    output($ret);
}

/**
 * 先判断用户是否存在
 */
$db->where('openid', $openid);
$user = $db->getOne('users');

if(!$user) {
    $ret['errcode'] = 3;
    $ret['errmsg'] = '用户不存在';
    output($ret);
}

/**
 * 保存图片到七牛
 */

$base64 = str_replace('data:image/png;base64,', '', $base64);
$base64 = str_replace('data:image/jpg;base64,', '', $base64);
$base64 = str_replace('data:image/jpeg;base64,', '', $base64);


use \Qiniu\Auth;

$auth = new Auth($cfg['qiniu']['ak'], $cfg['qiniu']['sk']);

$expires = 3600;
$filename = base64_encode('watsons/'.createUnique().'.png');
$upToken = $auth->uploadToken($cfg['qiniu']['bucket']);


$update_result = request_qiniu_curl($base64, $filename, $upToken);
// var_dump($update_result);exit;
if(isset($upload_result['error'])) {
    $ret['errcode'] = 4;
    $ret['errmsg'] = '上传失败';
    output($ret);
}

$img_url = 'http://cdn.yscase.com/'.$update_result['key'];


$new_data = [
    'openid' => $openid,
    'img_url' => $img_url,
    'created_at' => date('Y-m-d H:i:s')
];

$newid = $db->insert('receipts', $new_data);

if(!$newid) {
    $ret['errcode'] = 5;
    $ret['errmsg'] = '保存失败，稍后再试';
    output($ret);
}

// TODO  传递到第三方审核平台


$ret['errcode'] = 0;
$ret['errmsg'] = '保存成功';
$ret['data']['id'] = $newid;
$ret['data']['avatar'] = $user['avatar'];
$ret['data']['nickname'] = $user['nickname'];
$ret['data']['img_url'] = $img_url;

output($ret);

/**
 * 传送到七牛
 * @param $post_string
 * @param $filename
 * @param $upToken
 *
 * @return mixed
 */
function request_qiniu_curl($post_string, $filename, $upToken) {

    $headers = array();
    $headers[] = 'Content-Type:image/png';
    $headers[] = 'Authorization:UpToken '.$upToken;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'http://upload.qiniu.com/putb64/-1/key/'.$filename);
    //curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER ,$headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $data = curl_exec($ch);

    curl_close($ch);

    return json_decode($data, true);
}


function sendRemoteReview($id, $img_url, $openid) {
    $ts = time();
    
    $data = [
        'DateStamp'      => $ts,
        'OpenId'         => $openid,
        'OrderCode'      => $id,
        'Secret'         => API_SECRET,
        'TicketFileName' => $img_url,
    ];
    
    $sign = strtoupper(md5(http_build_query($data)));
    
    unset($data['Secret'], $data['TicketFileName']);
    $data['Sign']     = $sign;
    $data['FileName'] = $img_url;
    
    //print_r($data);exit;
    
    $resp = curlPost('http://yfdsapi.esoshine.com/api/PhotoReturn/PhotoRecord', $data);
    // exit($resp);
    $result = json_encode($resp, true);
    // var_dump($result);
    return isset($result['Success']) && $result['Success'] == 'true' ? true : false;
}