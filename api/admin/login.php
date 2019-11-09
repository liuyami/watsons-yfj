<?php
require '../bootstrap.php';
//$_POST
$username  =       isset($_POST['username']) ? $_POST['username'] : "";
$password  =       isset($_POST['password']) ? $_POST['password'] : "";

if (empty($username)){
    $_SESSION['error'] = "用户名不能为空";
    //hint();
    back();
/*    $ret="<script>
     alert('未授权')
    'location=http://watsons-yfj.test/api/admin/index.php';
    </script>";
    exit;*/

/*    return;
    $url="http://watsons-yfj.test/api/admin/index.php";
    header('location:'. $url);*/


}
if (empty($password)){
    $_SESSION['error'] = "密码不能为空";
    back();
}

$db->where ('username', $username);
$admin = $db->getOne('admin');

if(empty($admin)){
    $_SESSION['error']= "用户名错误";
    back();
}

if(password_verify ($password,$admin['password'])){
    $_SESSION['admin'] = $username;
    $_SESSION['error']="";
    header('location:'. "http://watsons.yscase.com/api/admin/data.php");
    exit;
}
else{
    $_SESSION['error'] = "密码错误";
    back();
}

function back(){

    $url="http://watsons.yscase.com/api/admin/index.php";
    header('location:'. $url);
    exit;
}

function hint(){
    echo"<script language='javascript'>
          alert('未授权')";
    echo "location='http://watsons-yfj.test/api/admin/index.php';";
    echo "</script>";
    return;
/*    $ret="<script>alert('未授权')"."location.href=http://watsons-yfj.test/api/admin/index.php"."</script>";
    echo $ret;*/

}



