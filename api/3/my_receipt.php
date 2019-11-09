<?php
include '../bootstrap.php';

$openid = isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : null;


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
    $ret['errmsg'] = '账户不存在（未经微信授权）';
    output($ret);
}

$has_wish = $user['has_wish'];

/** 所有的上传 */
$db->where('openid', $openid);
$db->orderBy('id','DESC');

$ret['errcode'] = 0;
$ret['errmsg'] = 'success';
$ret['data']['has_wish'] = $has_wish;
$ret['data']['receipts'] = $db->get('receipts', null, 'id, img_url, status_id, photo_url');

output($ret);