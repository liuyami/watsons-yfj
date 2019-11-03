<?php

require_once '../bootstrap.php';

$db->where("item_key", 'progress');
$progress_item = $db->getOne("proejct_config");

$ret['errcode'] = 0;
$ret['errmsg'] = '获取成功';
$ret['data']['num'] = (int)$progress_item['item_val'];

output($ret);
