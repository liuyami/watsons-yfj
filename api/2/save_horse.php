<?php
/**
 * 创建绘马
 */
require '../bootstrap.php';

$_POST = json_decode(file_get_contents('php://input'), true);

$openid  = isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : null;

$content = isset($_POST['content']) ? trim($_POST['content']): null;
$type    = isset($_POST['type']) ? trim($_POST['type']): null;

$base64  = isset($_POST['img']) ? trim($_POST['img']) : null;

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
if(!$type){
    $ret['errcode'] = 3;
    $ret['errmsg'] = '相框类型不能为空';
    output($ret);
}


/**
 * 先判断用户是否存在
 */

$results = $db
    ->where('openid', $openid)
    ->get('horse');

if($results) {
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


use \Qiniu\Auth;

$auth = new Auth($cfg['qiniu']['ak'], $cfg['qiniu']['sk']);

$expires = 3600;
$filename = base64_encode('watsons/'.createUnique().'.png');
$upToken = $auth->uploadToken($cfg['qiniu']['bucket']);


$update_result = request_qiniu_curl($base64, $filename, $upToken);
// var_dump($update_result);exit;
if(isset($upload_result['error'])) {
    $ret['errcode'] = 5;
    $ret['errmsg'] = '上传失败';
    output($ret);
}

$img_url = 'http://cdn.yscase.com/'.$update_result['key'];


$new_data = [
    'openid' => $openid,
    'content' => $content,
    'imageUrl' => $img_url,
    'type' => $type,
    'created_at' => date('Y-m-d H:i:s')
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
