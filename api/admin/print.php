<?php
require '../bootstrap.php';

require_once ('../libs/xlsxwriter.class.php');
$writer = new XLSXWriter();

/** 打印： 上传小票审核状态 */
$writer->writeSheetHeader('上传小票审核状态', array('系统编号' => 'integer', 'OpenID' => 'string', '上传图片' => 'string', '状态(1:待审核 2:拒绝  3:通过)' => 'string',  '创建日期' => 'datetime', '更新日期' => 'datetime'  ,'审核完成时间' => 'datetime' , '合成图片' => 'string'),
    $col_options = ['widths'=>[10,15,30,10,20,20,20,30]]);

$users = $db->get("receipts");

foreach ($users as $row) {
    switch ($row['status_id']){
        case 1:
            $row['status_id']='待审核';
            break;
        case 2:
            $row['status_id']='拒绝';
            break;
        case 3:
            $row['status_id']='通过';
            break;
    }

    $writer->writeSheetRow('上传小票审核状态', array($row['id'], $row['openid'], $row['img_url'], $row['status_id'], $row['created_at'], $row['updated_at'], $row['review_completed_at'], $row['photo_url']));
}
$file = time() . '.xlsx';

$writer->writeToFile($file);
//
//header("location:{$file_name}");

if (file_exists($file)) {

    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));

    readfile($file);
    unlink($file);
    exit;
}