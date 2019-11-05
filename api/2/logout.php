<?php


require '../bootstrap.php';

$_SESSION[SESSION_PREFIX.'openid'] = "";

$ret["errcode"] = 0;
$ret["errmsg"] = "退出模拟登陆";

output($ret);
