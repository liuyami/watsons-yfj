<?php
/**
 * 微信授权回调
 */
require 'bootstrap.php';

$scene = isset($_GET['scene']) ? trim($_GET['scene']) : '';
$openid = isset($_GET['openid']) ? trim($_GET['openid']) : null;
$avatar = isset($_GET['avatar']) ? trim($_GET['avatar']) : null;
$nickname = isset($_GET['nickname']) ? trim($_GET['nickname']) : null;


if(!$openid) {
    $ret['errcode'] = 1;
    $ret['errmsg'] = '错误的OPENID';
    output($ret);
}
if(!$avatar) {
    $ret['errcode'] = 1;
    $ret['errmsg'] = '错误的头像';
    output($ret);
}
if(!$nickname) {
    $ret['errcode'] = 1;
    $ret['errmsg'] = '错误的昵称';
    output($ret);
}


#查找用户
$db->where('openid', $openid);
$user = $db->getOne('users');

//var_dump($user);exit;

if($user) {
    $ret['errcode'] = 0;
    $ret['errmsg'] = 'success';
    $ret['data'] = $user;
    //output($ret);
    
    $_SESSION[SESSION_PREFIX.'userid'] = $user['id'];
    $_SESSION[SESSION_PREFIX.'openid'] = $user['openid'];
    $_SESSION[SESSION_PREFIX.'avatar'] = $user['avatar'];
    $_SESSION[SESSION_PREFIX.'nickname'] = $user['nickname'];
    
    redirect('../index.html?scene='.$scene);

} else {
    /**
     * 由于要用到CANVAS合成图像，修改微信头像替换成自己的域名，
     */
    $avatar = str_replace('thirdwx.qlogo.cn', 'watsons.yscase.com/wechat_image', $avatar);

    $data['openid'] = $openid;
    $data['avatar'] = $avatar;
    $data['nickname'] = $nickname;
    $data['created_at'] = date('Y-m-d H:i:s');

    $id = $db->insert ('users', $data);
    
    $_SESSION[SESSION_PREFIX.'userid'] = $id;
    $_SESSION[SESSION_PREFIX.'openid'] = $openid;
    $_SESSION[SESSION_PREFIX.'avatar'] = $avatar;
    $_SESSION[SESSION_PREFIX.'nickname'] = $nickname;


    //var_dump($id);exit;

    redirect('../index.html?scene='.$scene);
}