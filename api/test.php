<?php
require './bootstrap.php';


//$sign_arr = [
//    'openid' => 'oo0SAv0qOyk8XTVfO0jLkhq3VnRX',
//    'order_id' => 10000,
//    'review_status' => 3,
//    'timestamp' => 1572763628,
//    'secret' => 'ysDFD34iu7JHJS'
//];
//
//echo http_build_query($sign_arr);

//var_dump(sendRemoteReview(10033, 'http://cdn.yscase.com/watsons/06d0cf1fc2c0c8a978cb0896fc471b2c.png','oo0SAv0qOyk8XTVfO0jLkhq3vnRI'));
//
//function sendRemoteReview($id, $img_url, $openid) {
//    $ts = time();
//
//    $data = [
//        'DateStamp'      => (string)$ts,
//        'OpenId'         => $openid,
//        'OrderCode'      => (string)$id,
//        'Secret'         => API_SECRET,
//        'TicketFileName' => $img_url,
//    ];
//    var_dump(http_build_query($data));
//    $sign = strtoupper(md5(http_build_query($data)));
//    var_dump($sign);
//    unset($data['Secret'], $data['TicketFileName']);
//    $data['Sign']     = $sign;
//    $data['FileName'] = $img_url;
//
//    var_dump(json_encode($data));
//
//    $resp = http_post_json('http://yfdsapi.esoshine.com/api/PhotoReturn/PhotoRecord', json_encode($data));
//    exit($resp);
//    $result = json_encode($resp, true);
//    // var_dump($result);
//    return isset($result['Success']) && $result['Success'] == 'true' ? true : false;
//}

//$sign_arr = [
//    'progress'  => 30,
//    'timestamp' => 1572768629,
//    'secret'    => 'ysDFD34iu7JHJS',
//];

//$sign_arr = [
//    'openid'  => 'oo0SAv8nUf8Q2R_ssIwVgaL_k9H4',
//    'timestamp' => 1572768629,
//    'secret'    => 'ysDFD34iu7JHJS',
//];

$sign_arr = [
    'openid'        => 'oo0SAv8nUf8Q2R_ssIwVgaL_k9H4',
    'order_id'      => '10035',
    'review_status' => '3',
    'timestamp'     => '1572768629',
    'secret'        => 'ysDFD34iu7JHJS',
];

echo strtoupper(md5(http_build_query($sign_arr)));