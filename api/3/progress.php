<?php

require_once '../bootstrap.php';

/*$db->where("item_key", 'progress');
$progress_item = $db->getOne("proejct_config");
$ret['errcode'] = 0;
$ret['errmsg'] = '获取成功';
$ret['data']['num'] = (int)$progress_item['item_val'];
*/


    /*向下取整 只返回百分位*/
    //$db->where('has_wish','1');
    $count = $db->getOne ("receipts", "count(distinct openid) count");
    $percentage = floor($count['count']/50);

    if(!empty($percentage)){
        if($percentage<20){
            $percentage=0;
        }elseif ($percentage<40){
            $percentage=20;
        }elseif ($percentage<60){
            $percentage=40;
        }elseif ($percentage<80){
            $percentage=60;
        }elseif ($percentage<100){
            $percentage=80;
        }else{
            $percentage=100;
        }
    }




    $ret['errcode'] = 0;
    $ret['errmsg'] = '获取成功';
    $ret['count'] = $count['count'];


    $ret['percentage'] = $percentage;

    output($ret);
