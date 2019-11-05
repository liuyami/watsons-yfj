<?php
/*
 *
 *  点击花花的绘马
 */
require '../bootstrap.php';
$openid  = isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : "oCQj_wvYa4jOMoNdF7WPtCWqD85U";

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
//   添加点击记录
$results = $db->insert ('horse_huahua_record',Array ("openid" => $openid));
//   爱心值+1
$users = $db->rawQuery("UPDATE horse_huahua SET `spotvalue`=`spotvalue`+ 1");

if (!empty($users) || !$results){
    $ret['errcode'] = 2;
    $ret['errmsg'] = '添加记录错误';
    output($ret);
}

$ret['errcode'] = 0;
$ret['errmsg'] = '点赞成功';

output($ret);
