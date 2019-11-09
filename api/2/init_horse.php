<?php
/**
 *   初始化绘马
 */
require '../bootstrap.php';
require 'Page.php';
//$_POST = json_decode(file_get_contents('php://input'), true);

$openid  =    isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : "oo0SAv2keF4WpbKuAhsl7s1d6Trk";
$pageindex  = isset($_GET['pageindex']) ? trim($_GET['pageindex']) : null;

if(!$openid){
    $ret['errcode'] = 1;
    $ret['errmsg'] = '未授权';
    output($ret);
}

//是否创建绘马
$horse = $db
    ->where('openid', $openid)
    ->get('horse');

if(empty($horse)) {
    $horse = '500';
}

$huahua=$db->getOne('horse_huahua');
//判断是否点击过花花的绘马
$bool_click_huahua = $db
    ->where('openid', $openid)
    ->get('horse_huahua_record');

//有数据的时候  status = 1
if(!empty($bool_click_huahua)) {
    $huahua['status'] = 1;
}else{
    $huahua['status'] = 0;
}

/*  获取总数 */
//spotvalue

/*$db->where("spotvalue", '1');
$count = $db->getOne ("horse", "count(*) count")->where('');*/

$count = $db->rawQuery("SELECT count(*) as count  FROM `horse` WHERE opentype= 1");

//每页展示的数据
$pageSize=9;
$page = new Page($count[0]['count'],$pageSize);

if(!empty($pageindex)){
    $page->setPage($pageindex);
}


$pagecount=$page->getPagecount();
$startRow=$page->getStartRow();


$result = $db->rawQuery("SELECT *FROM `horse` WHERE opentype=1  ORDER BY `spotvalue` desc  LIMIT $startRow,$pageSize");


if(!$page->last && $page->getPage() == $pagecount){
    $result = $db->rawQuery("SELECT *FROM `horse` WHERE opentype=1  ORDER BY `spotvalue` desc  LIMIT $page->needLastRow,$pageSize");

}

/* 查询出用户点赞过的 绘马id */
$horse_id = $db->rawQuery("SELECT  horse_id FROM `horse_record` where `openid` ='$openid'");

//$ret['horse_id'] = $horse_id;
//对用户点赞的绘马进行 status = 1  未点赞的绘马 status = 0
if(!empty($horse_id)){
    for ($i=0;$i<count($result);$i++){
        for ($y=0;$y<count($horse_id);$y++){
            if($result[$i]['id'] == $horse_id[$y]['horse_id']){
                $result[$i]['status']=1;
            }
        }
    }
}


array_unique();


for ($i=0;$i<count($result);$i++){
     if(empty($result[$i]['status'])){
         $result[$i]['status']=0;
     };
}

$ret['errcode'] = 0;
$ret['errmsg'] = '获取成功';
$ret['count'] = $count[0]['count'];
// 总页数
$ret['pagecount']=$pagecount;
// 当前页数
$ret['page']=$page->page;

// 花花的绘马
$ret['huahua']=$huahua;
// 用户的绘马
$ret['horse']=$horse;
// 绘马展示 9条数据
$ret['result']=$result;
output($ret);
