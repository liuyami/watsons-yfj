<?php
/**
 * 点击绘马  (添加爱心值)
 */
require '../bootstrap.php';


//$_POST = json_decode(file_get_contents('php://input'), true);

$openid  = isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : "oo0SAv2keF4WpbKuAhsl7s1d6Trk";

$id = isset($_GET['id']) ? trim($_GET['id']) : null;

if(!$openid){
    $ret['errcode'] = 1;
    $ret['errmsg'] = 'OpenID不能为空';
    output($ret);
}

if(!$id){
    $ret['errcode'] = 2;
    $ret['errmsg'] = 'ID不能为空';
    output($ret);
}

/*查询此用户是否点击过*/
$results = $db
    ->where('horse_id', $id)
    ->where('openid', $openid)
    ->get('horse_record');


if($results) {
    $ret['errcode'] = 3;
    $ret['errmsg'] = '该用户已经点赞过';
    output($ret);
}
$new_data = [
    'horse_id' => $id,
    'openid' => $openid
];
//   添加点击记录
$result = $db->insert ('horse_record',$new_data);
//   爱心值+1
$users = $db->rawQuery("UPDATE horse SET `spotvalue`=`spotvalue`+ 1 WHERE `id`= $id");

if (!empty($users) || !$result){
    $ret['errcode'] = 4;
    $ret['errmsg'] = '添加记录错误';
    output($ret);
}
//


$ret['errcode'] = 0;
$ret['errmsg'] = '点赞成功';

output($ret);
