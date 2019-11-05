<?php
/*
 *  查询出点赞过的绘马
 */
require '../bootstrap.php';


$openid  = isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : null;

if(!$openid){
    $ret['errcode'] = 1;
    $ret['errmsg'] = 'OpenID不能为空';
    output($ret);
}

$result = $db->rawQuery("SELECT  horse_id FROM `horse_record` where `openid` ='$openid'");

//$user 保存用户点击过的绘马id

if(!$result){
    $ret['errcode'] = 1;
    $ret['errmsg'] = '该用户还未点赞过绘马';
    output($ret);
}

$ret['errcode'] = 0;
$ret['errmsg'] = '获取成功';
//返回已经点击过的id
$ret['horse'] = $result;


output($ret);