<?php
require '../bootstrap.php';

require_once ('../libs/xlsxwriter.class.php');

$writer = new XLSXWriter();
/** 打印： 绘马墙上传信息 */
$writer->writeSheetHeader('绘马墙上传信息', array('系统编号' => 'integer', 'OpenID' => 'string', '内容' => 'string', '点赞数' => 'integer',  '创建日期' => 'datetime', '发布日期' => 'string' , '绘马图片' => 'string'),
    $col_options = ['widths'=>[10,20,30,10,20,20,60]]);

$users = $db->get("horse");

//echo "<pre>";print_r($users);echo "<pre>";

foreach ($users as $row) {

    if (empty($row['updated_at']) || $row['updated_at'] == null){
        $row['updated_at'] = "未发布";

    }

    $writer->writeSheetRow('绘马墙上传信息', array($row['id'], $row['openid'], $row['content'], $row['spotvalue'], $row['created_at'], $row['updated_at'], $row['imageUrl']));
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