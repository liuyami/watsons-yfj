<?php

$scene = isset($_GET['scene']) && is_numeric($_GET['scene']) ? intval($_GET['scene']) : '';

$callback_url = urlencode("http://watsons.yscase.com/api/wechat.php?scene={$scene}");

$url = 'https://wechat.yscase.com/platform/ys/oauth?client_id=1dfcc42c-f073-40e6-9311-1466319d7d4d&scope=snsapi_userinfo&return_url='.$callback_url;

header("location:$url");
