<?php
require '../bootstrap.php';

$openid  = isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : "";

if(!$openid){
    $ret="<script>alert('未授权')</script>";
    echo $ret;
    return;
}

/*
 *  yfdsdemo.esoshine.com?openID=
    yfds.esoshine.com?openID=
*/
$url="http://yfdsdemo.esoshine.com/?openID=".$openid;

header("location:$url");
