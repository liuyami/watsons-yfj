<?php

require_once '../bootstrap.php';

/*$db->where("item_key", 'progress');
$progress_item = $db->getOne("proejct_config");
$ret['errcode'] = 0;
$ret['errmsg'] = '获取成功';
$ret['data']['num'] = (int)$progress_item['item_val'];
*/


    /*向下取整 只返回百分位*/
    $db->where('has_wish','1');
    $count = $db->getOne ("users", "count(*) count");
    $percentage = floor($count['count']/50);

    if($percentage<20){
        $percentage=0;
    }elseif ($percentage<40){
        $percentage=20;
    }elseif ($percentage<60){
        $percentage=60;
    }elseif ($percentage<80){
        $percentage=80;
    }elseif ($percentage<100){
        $percentage=100;
    }elseif ($percentage=100){
    }


    $ret['errcode'] = 0;
    $ret['errmsg'] = '获取成功';
    $ret['count'] = $count['count'];


    $ret['percentage'] = $percentage;

    output($ret);
