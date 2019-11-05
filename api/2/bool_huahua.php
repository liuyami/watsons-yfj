<?php
/*
 * 判断是否点击过花花的绘马
 *
 */
require '../bootstrap.php';

$openid  = isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : null;

if(!$openid){
    $ret['errcode'] = 1;
    $ret['errmsg'] = 'OpenID不能为空';
    output($ret);
}

$results = $db
    ->where('openid', $openid)
    ->get('horse_huahua_record');


if($results) {
    $ret['errcode'] = 1;
    $ret['errmsg'] = '该用户已经点击过花花的绘马';
    output($ret);
}


$ret['errcode'] = 0;
$ret['errmsg'] = '该用户没有点击花花的绘马';


output($ret);