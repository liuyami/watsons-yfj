<?php
/**
 * 创建绘马
 */
require '../bootstrap.php';



$openid  = isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : "oo0SAv2keF4WpbKuAhsl7s1d6Trk";

$content = isset($_REQUEST['content']) ? trim($_REQUEST['content']): null;


$base64  = isset($_REQUEST['img']) ? trim($_REQUEST['img']) : null;

$qr_code  = isset($_REQUEST['qr_code']) ? trim($_REQUEST['qr_code']) : null;


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
if(!$content){
    $ret['errcode'] = 3;
    $ret['errmsg'] = '文案不能为空';
    output($ret);
}
if(!$qr_code){
    $ret['errcode'] = 7;
    $ret['errmsg'] = 'qr_code不能为空';
    output($ret);
}


/**
 * 先判断用户是否存在
 */
$results = $db
    ->where('openid', $openid)
    ->get('horse');
//用户不存在 返回空数组
if(!empty($results)){
    $ret['errcode'] = 4;
    $ret['errmsg'] = '该用户已经绘制过了';
    output($ret);
}

/**
 * 保存图片到七牛
 */

$base64 = str_replace('data:image/png;base64,', '', $base64);
$base64 = str_replace('data:image/jpg;base64,', '', $base64);
$base64 = str_replace('data:image/jpeg;base64,', '', $base64);



$qr_code = str_replace('data:image/png;base64,', '', $qr_code);
$qr_code = str_replace('data:image/jpg;base64,', '', $qr_code);
$qr_code = str_replace('data:image/jpeg;base64,', '', $qr_code);

use \Qiniu\Auth;

$auth = new Auth($cfg['qiniu']['ak'], $cfg['qiniu']['sk']);

$expires = 3600;

$filename = base64_encode('watsons/'.createUnique().'.png');

$filename_qr_code = base64_encode('watsons/'.createUnique().'.png');


$upToken = $auth->uploadToken($cfg['qiniu']['bucket']);

$update_result = request_qiniu_curl($base64, $filename, $upToken);

$update_result_qr_code = request_qiniu_curl($qr_code, $filename_qr_code, $upToken);
// var_dump($update_result);exit;

if(isset($upload_result['error'])) {
    $ret['errcode'] = 5;
    $ret['errmsg'] = '上传失败';
    output($ret);
}

$img_url = 'http://cdn.yscase.com/'.$update_result['key'];

$img_url_qr_code= 'http://cdn.yscase.com/'.$update_result_qr_code['key'];

$new_data = [
    'openid' => $openid,
    'content' => $content,
    'imageUrl' => $img_url,
    'qr_code' => $img_url_qr_code

];


$results = $db->insert ('horse',$new_data);

if(!$results) {
    $ret['errcode'] = 6;
    $ret['errmsg'] = '保存失败，稍后再试';
    output($ret);
}


$ret['errcode'] = 0;
$ret['errmsg'] = '保存成功';

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
