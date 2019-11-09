<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="_csrf" content="${_csrf.token}"/>
    <title>
        屈臣氏后台管理
    </title>
    <link rel="icon" type="image/x-icon" href="/admin/static/favicon.ico">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/ionicons.min.css">
    <link rel="stylesheet" href="css/AdminLTE.min.css">
    <link rel="stylesheet" href="css/blue.css">

    <style>
        body {
            margin: 50px 0;
            text-align: center;
            font-family: ""Source Sans Pro",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol"", "Open Sans", Arial, "Hiragino Sans GB", "Microsoft YaHei", "STHeiti", "WenQuanYi Micro Hei", SimSun, sans-serif;
        }

        .show {
            display: block;
        }
        .hide {
            display: none;
        }
        #notice{
            color: red;
        }

    </style>

    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

    <!-- 引入 gt.js，既可以使用其中提供的 initGeetest 初始化函数 -->

</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#">屈臣氏后台管理</a>
    </div>

    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

            <form action="login.php" method="post" id="loginform">

                <div class="form-group has-feedback">
                    <input type="text" name="username" id="accounts" class="form-control" placeholder="Username">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>

                <div class="form-group has-feedback">
                    <input type="Password" name="password" id="password" class="form-control" placeholder="Password">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

                <input class="btn btn-primary btn-block btn-flat" id="embed-submit" type="submit" value="提交">
            </form>

    </div>
</div>
<script src="js/jquery-1.8.3.min.js"></script>
<script>


<?php
    require '../bootstrap.php';
    if(!empty($_SESSION['error']) && $_SESSION['error'] != " "){
        //echo alert($_SESSION['error']);
        $error = $_SESSION['error'];
        echo alert($error);
    }

    function alert($error){
        echo "alert('登录失败')";
    }
    ?>


</script>
<script>



</script>
</body>
</html>
