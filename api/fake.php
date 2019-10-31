
<?php


require './bootstrap.php';

$openid  = isset($_GET['openid']) ? $_GET['openid'] : 'oo0SAv8nUf8Q2R_ssIwVgaL_k9H4';

$db->where("openid", $openid);
$user = $db->getOne("users");

if(!$user) {
    $ret['errcode'] = 2;
    $ret['errmsg'] = '账户不存在（未经微信授权）';
    output($ret);
}

//session_destroy();

$_SESSION[SESSION_PREFIX.'userid'] = $user['id'];
$_SESSION[SESSION_PREFIX.'openid'] = $user['openid'];
$_SESSION[SESSION_PREFIX.'avatar'] = $user['avatar'];
$_SESSION[SESSION_PREFIX.'nickname'] = $user['nickname'];

print_r($_SESSION);
