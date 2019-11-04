<?php
/**
 * 给远程调用设置用户已经有心愿
 */

require '../bootstrap.php';


$post_data = initPostData();

$openid    = isset($post_data['openid']) ? trim($post_data['openid']) : null;
$timestamp = isset($post_data['timestamp']) && is_numeric($post_data['timestamp']) ? intval($post_data['timestamp']) : null;
$sign      = isset($post_data['sign']) ? trim($post_data['sign']) : null;

if(!$openid){
    $ret['errcode'] = 1;
    $ret['errmsg'] = 'OpenID不能为空';
    output($ret);
}

if(!$timestamp){
    $ret['errcode'] = 2;
    $ret['errmsg'] = '时间戳不能为空且只能是数字';
    output($ret);
}

if(!$sign){
    $ret['errcode'] = 3;
    $ret['errmsg'] = '签名不能为空';
    output($ret);
}

$sign_arr = [
    'openid'    => $openid,
    'timestamp' => $timestamp,
    'secret' => API_SECRET
];

$self_sign = strtoupper(md5(http_build_query($sign_arr)));

if($self_sign != $sign) {
    $ret['errcode'] = 4;
    $ret['errmsg'] = '签名不匹配';
    output($ret);
}


if (time() - $timestamp > 120) {
    $ret['errcode'] = 5;
    $ret['errmsg'] = '时间戳超时';
    output($ret);
}


// 查找账号是否存在
$db->where("openid", $openid);
$user = $db->getOne("users");
if(!$user) {
    $ret['errcode'] = 6;
    $ret['errmsg'] = '账户不存在';
    output($ret);
}

$db->where('id', $user['id']);
if($db->update('users', ['has_wish' => 1])) {
    $ret['errcode'] = 0;
    $ret['errmsg'] = '更新成功';
    output($ret);
} else {
    $ret['errcode'] = 7;
    $ret['errmsg'] = '更新失败，请稍后在试';
    output($ret);
}