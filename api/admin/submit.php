<?php
require '../bootstrap.php';


//$_POST = json_decode(file_get_contents('php://input'), true);

$openid  =    isset($_SESSION[SESSION_PREFIX.'openid']) ? trim($_SESSION[SESSION_PREFIX.'openid']) : "oo0SAv2keF4WpbKuAhsl7s1d6Trk";
$ids  =       isset($_POST['ids']) ? $_POST['ids'] : "";

for ($i = 0; $i < count($ids); $i++){

    $db->where("id", $ids[$i]);

    if($db->update('horse', ['opentype' => '1','updated_at' => date('Y-m-d H:i:s')])){

        $url="http://watsons.yscase.com/api/admin/data.php";
        header("location:$url");

    }else{
        $ret="<script>alert('修改失败')</script>";
        echo $ret;
        return;
    }


}
