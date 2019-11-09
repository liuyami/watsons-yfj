<?php
require '../bootstrap.php';
require '../2/Page.php';
//$_POST = json_decode(file_get_contents('php://input'), true);

$openid  =    isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : "oo0SAv2keF4WpbKuAhsl7s1d6Trk";
$pageindex  = isset($_GET['pageindex']) ? trim($_GET['pageindex']) : null;

/*  获取总数 */
$count = $db->getOne ("horse", "count(*) count");
//每页展示的数据
$pageSize=12;

$page = new Page($count['count'],$pageSize);
if(!empty($pageindex)){

    $page->setPage($pageindex);

}

$pagecount=$page->getPagecount();
$startRow=$page->getStartRow();

$result = $db->rawQuery("SELECT * FROM `horse` order by created_at desc  LIMIT $startRow,$pageSize");


$ret['horse']=$result;
// 总页数
$ret['pagecount']=$pagecount;

$ret['count']=$count['count'];

// 当前页数
$ret['page']=$page->page;

output($ret);
