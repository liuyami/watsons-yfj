<?php
include 'bootstrap.php';

include_once("libs/xlsxwriter.class.php");

$writer = new XLSXWriter();

/** 用户列表 */
$writer->writeSheetHeader('User', array('系统编号'=>'integer','来源'=>'string','来源标识'=>'string', 'OpenID'=>'string','姓名'=>'string','手机号码'=>'string','创建日期'=>'datetime','更新日期'=>'datetime') );

$users = $db->get("vanke_user");

foreach ($users as $row) {
    $writer->writeSheetRow('User', array($row['id'], $row['channel'], $row['source'], $row['openid'], $row['username'], $row['mobile'], $row['created_at'], $row['updated_at']) );
}

/** 签到列表 */
$writer->writeSheetHeader('Sign', ['系统编号'=>'integer','OpenID'=>'string','签到时间'=>'datetime', '签到楼盘'=>'string'] );

$datas = $db->get("vanke_sign");

foreach ($datas as $row) {
    $writer->writeSheetRow('Sign', [$row['id'], $row['openid'], $row['created'], $row['property']] );
}


$file = time().'.xlsx';

$writer->writeToFile($file);
//
//header("location:{$file_name}");

if (file_exists($file)) {

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));

    readfile($file);
    unlink($file);
    exit;
}