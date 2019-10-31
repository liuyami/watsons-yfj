<?php
/**
 * 给远程调用设置用户已经有心愿
 */

require '../bootstrap.php';


$openid = isset($_GET['openid']) ? trim($_GET['openid']) : null;


if(!$openid){
    $ret['errcode'] = 1;
    $ret['errmsg'] = 'OpenID不能为空';
    output($ret);
}


// 查找账号是否存在
$db->where("openid", $openid);
$user = $db->getOne("users");

if(!$user) {
    $ret['errcode'] = 2;
    $ret['errmsg'] = '账户不存在';
    output($ret);
}

$db->where('id', $user['id']);

if($db->update('users', ['has_wish' => 1])) {
    $ret['errcode'] = 0;
    $ret['errmsg'] = '更新成功';
    output($ret);
} else {
    $ret['errcode'] = 3;
    $ret['errmsg'] = '更新失败，请稍后在试';
    output($ret);
}