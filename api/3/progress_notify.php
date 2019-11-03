<?php
/**
 * 更新解锁进度
 */

require '../bootstrap.php';

$post_data = initPostData();

$progress  = isset($post_data['progress']) && is_numeric($post_data['progress']) ? intval($post_data['progress']) : null;
$timestamp = isset($post_data['timestamp']) && is_numeric($post_data['timestamp']) ? intval($post_data['timestamp']) : null;
$sign      = isset($post_data['sign']) ? trim($post_data['sign']) : null;

if(!$progress){
    $ret['errcode'] = 1;
    $ret['errmsg'] = '进度不能为空';
    output($ret);
}

if(!$timestamp){
    $ret['errcode'] = 2;
    $ret['errmsg'] = '时间戳不能为空且只能是数字';
    output($ret);
}

if(!$sign){
    $ret['errcode'] = 3;
    $ret['errmsg'] = '签名不能为空';
    output($ret);
}

$sign_arr = [
    'progress'  => $progress,
    'timestamp' => $timestamp,
    'secret'    => API_SECRET,
];

$self_sign = strtoupper(md5(http_build_query($sign_arr)));

if($self_sign != $sign) {
    $ret['errcode'] = 4;
    $ret['errmsg'] = '签名不匹配';
    output($ret);
}


// 查找账号是否存在
$db->where("item_key", 'progress');
$progress_item = $db->getOne("proejct_config");
if(!$progress_item) {
    $new_data['item_key'] = 'progress';
    $new_data['item_val'] = $progress;
    
    $db->insert('proejct_config', $new_data);
    
    $ret['errcode'] = 0;
    $ret['errmsg'] = '更新成功';
    output($ret);
    
} else {
    
    $db->where("item_key", 'progress');
    if($db->update('proejct_config', ['item_val' => $progress])) {
        $ret['errcode'] = 0;
        $ret['errmsg'] = '更新成功';
        output($ret);
    } else {
        $ret['errcode'] = 5;
        $ret['errmsg'] = '更新失败，请稍后在试';
        output($ret);
    }
    
    
}

