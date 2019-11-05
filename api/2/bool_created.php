<?php
/*
 *  是否创建过绘马
 *
 * */
require '../bootstrap.php';

$openid  = isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : null;

if(!$openid){
    $ret['errcode'] = 1;
    $ret['errmsg'] = 'OpenID不能为空';
    output($ret);
}

$results = $db
    ->where('openid', $openid)
    ->get('horse');

if(!$results) {
    $ret['errcode'] = 1;
    $ret['errmsg'] = '该用户还未绘制绘马';
    output($ret);
}

//用户自己创建的绘马

$ret['errcode'] = 0;
$ret['errmsg'] = '获取成功';
$ret['horse'] = $results;

output($ret);