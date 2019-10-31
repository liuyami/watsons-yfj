<?php

require './bootstrap.php';

$db->where('1', 1);
$data = [
    'username' => null,
    'mobile' => null
];
if($db->update('vanke_user', $data)) {
    echo '用户资料已清空...<br><br>';
}


$db->where('1', 1);
if($db->delete('vanke_sign')) {
    echo '签到已清除...';
}