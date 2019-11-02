<?php

require_once '../bootstrap.php';

$num = isset($_GET['num']) ? intval($_GET['num']) : 20;


$ret['errcode'] = 0;
$ret['errmsg'] = '获取成功';
$ret['data']['num'] = $num;

output($ret);