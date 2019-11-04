<?php
require './bootstrap.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : exit('缺少参数 - id');

$db->where('id',$id);
$result = $db->getOne('receipts');

if(!$result) {
    exit('订单不存在');
}

$ts = time();
//
//var_dump(sendRemoteReview($result['id'], $result['img_url'],$result['openid']));
//
//function sendRemoteReview($id, $img_url, $openid) {
//    $ts = time();
//    $query_str = "DateStamp={$ts}&OpenId={$openid}&OrderCode={$id}&Secret=".API_SECRET."&TicketFileName={$img_url}";
//
//    $sign = strtoupper(md5($query_str));
//
//    $data['DateStamp'] = $ts;
//    $data['OpenId']    = $openid;
//    $data['OrderCode'] = $id;
//    $data['Sign']      = $sign;
//    $data['FileName']  = $img_url;
//
//    $resp = http_post_json('http://yfdsapi.esoshine.com/api/PhotoReturn/PhotoRecord', json_encode($data));
//    $result = json_decode($resp, true);
//    //print_r($result);
//    // return $result;
//    return isset($result['ReturnCode']) && (string)$result['ReturnCode'] == '200000' ? true : false;
//}

//$sign_arr = [
//    'progress'  => 30,
//    'timestamp' => 1572768629,
//    'secret'    => 'ysDFD34iu7JHJS',
//];

$user_arr = [
    'openid'  => $result['openid'],
    'timestamp' => $ts,
    'secret'    => 'ysDFD34iu7JHJS',
];

echo '签名：'.strtoupper(md5(http_build_query($user_arr)));
echo '<hr>';
echo "签名使用时间戳是：".$ts;


//$review_arr = [
//    'openid'        => $result['openid'],
//    'order_id'      => $id,
//    'review_status' => '3',
//    'timestamp'     => $ts,
//    'secret'        => 'ysDFD34iu7JHJS',
//];
//
//echo '签名：'.strtoupper(md5(http_build_query($review_arr)));
//echo '<hr>';
//echo "签名使用时间戳是：".$ts;


//$ts = time();
//$sign_arr = [
//    "DateStamp" => $ts,
//    "OpenId" => "oo0SAv0qOyk8XTVfO0jLkhq3vnRI",
//    "OrderCode" => "10034",
//    "Secret" => API_SECRET,
//    "TicketFileName" => urlencode("http://cdn.yscase.com/watsons/06d0cf1fc2c0c8a978cb0896fc471b2c.png")
//];
//
//$query_str = "DateStamp={$ts}&OpenId=oo0SAv0qOyk8XTVfO0jLkhq3vnRI&OrderCode=10034&Secret=".API_SECRET."&TicketFileName=http://cdn.yscase.com/watsons/8fb25584c98ef9640a0dcde2f9018bf3.png";
//echo '字符串：'.$query_str;
//echo '<hr>';
////echo '签名：'.strtoupper(md5(http_build_query($query_str)));
//echo '签名：'.strtoupper(md5($query_str));
//
//echo '<hr>';
//echo "签名使用时间戳是：".$ts;