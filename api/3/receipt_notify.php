<?php
/**
 * 给远程调用更新票据状态
 */

require '../bootstrap.php';

$post_data = initPostData();

$openid        = isset($post_data['openid']) ? trim($post_data['openid']) : null;
$order_id      = isset($post_data['order_id']) && is_numeric($post_data['order_id']) ? intval($post_data['order_id']) : null; // 小票ID
$review_status = isset($post_data['review_status']) && is_numeric($post_data['review_status']) ? intval($post_data['review_status']) : null;
$timestamp     = isset($post_data['timestamp']) && is_numeric($post_data['timestamp']) ? intval($post_data['timestamp']) : null;
$sign          = isset($post_data['sign']) ? trim($post_data['sign']) : null;


if(!$openid){
    $ret['errcode'] = 1;
    $ret['errmsg'] = 'OpenID不能为空';
    output($ret);
}

if(!$order_id){
    $ret['errcode'] = 2;
    $ret['errmsg'] = '票据编号不能为空且只会是数字';
    output($ret);
}

if(!$review_status || ($review_status !=2 && $review_status != 3)){
    $ret['errcode'] = 3;
    $ret['errmsg'] = '状态编号不能为空或值不正确';
    output($ret);
}

if(!$sign){
    $ret['errcode'] = 4;
    $ret['errmsg'] = '签名不能为空';
    output($ret);
}

if(!$timestamp){
    $ret['errcode'] = 5;
    $ret['errmsg'] = '时间戳不能为空且只能是数字';
    output($ret);
}
$sign_arr = [
    'openid'        => $openid,
    'order_id'      => $order_id,
    'review_status' => $review_status,
    'timestamp'     => $timestamp,
    'secret'        => API_SECRET,
];

$self_sign = strtoupper(md5(http_build_query($sign_arr)));

if($self_sign != $sign) {
    $ret['errcode'] = 6;
    $ret['errmsg'] = '签名不匹配';
    output($ret);
}



// 查找账号是否存在
$db->where("id", $order_id);
$db->where("openid", $openid);
$data = $db->getOne("receipts");

if(!$data) {
    $ret['errcode'] = 7;
    $ret['errmsg'] = '订单不存在';
    output($ret);
}

$db->where('id', $data['id']);

if($db->update('receipts', ['status_id' => $review_status])) {
    $ret['errcode'] = 0;
    $ret['errmsg'] = '更新成功';
    output($ret);
} else {
    $ret['errcode'] = 8;
    $ret['errmsg'] = '更新失败，请稍后在试';
    output($ret);
}