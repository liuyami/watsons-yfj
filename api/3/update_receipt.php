<?php
/**
 * 给远程调用更新票据状态
 */

require '../bootstrap.php';


$openid = isset($_GET['openid']) ? trim($_GET['openid']) : null;
$receipt_id = isset($_GET['receipt_id']) ? trim($_GET['receipt_id']) : null;
$status_id = isset($_GET['status_id']) ? trim($_GET['status_id']) : null;


if(!$openid){
    $ret['errcode'] = 1;
    $ret['errmsg'] = 'OpenID不能为空';
    output($ret);
}

if(!$receipt_id){
    $ret['errcode'] = 2;
    $ret['errmsg'] = '票据编号不能为空';
    output($ret);
}

if(!$status_id){
    $ret['errcode'] = 3;
    $ret['errmsg'] = '状态编号不能为空';
    output($ret);
}


// 查找账号是否存在
$db->where("id", $receipt_id);
$db->where("openid", $openid);
$user = $db->getOne("receipts");

if(!$user) {
    $ret['errcode'] = 4;
    $ret['errmsg'] = '数据不存在';
    output($ret);
}

$db->where('id', $user['id']);

if($db->update('receipts', ['status_id' => $status_id])) {
    $ret['errcode'] = 0;
    $ret['errmsg'] = '更新成功';
    output($ret);
} else {
    $ret['errcode'] = 5;
    $ret['errmsg'] = '更新失败，请稍后在试';
    output($ret);
}