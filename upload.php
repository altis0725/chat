<?php
require_once 'HTTP/Request2.php';
define("FLICKR_REST",   "http://api.flickr.com/services/rest/");
define("FLICKR_AUTH",   "http://flickr.com/services/auth/");
define("FLICKR_UPLOAD", "http://api.flickr.com/services/upload/");

$auth_token = "72157629710703762-35a96dfb60262fbe";
$frob = "72157629710632608-7b2cbdf68f83e044-125233";
$apikey = "6e94c98a77a1135eef4cf30cb8da4492"; # api key
$secret = "07d9f618dc4bd826"; # api key と一緒に取得できるsecretキー

function getSig($secret, $args)
{
    ksort($args);
    $s = "";
    foreach($args as $k => $v){
        $s .= $k . $v;
    }
    return md5($secret . $s);
}
if($_FILES["upfile"]["error"]==0){
$args = array(
    'api_key' => $apikey,
    'frob' => $frob,
    'auth_token' => $auth_token,
);
$args['api_sig'] = getSig($secret, $args);
$Req = new HTTP_Request2();
$Req->setMethod(Http_Request2::METHOD_POST);
$Req->setUrl(FLICKR_UPLOAD);
$Req->addPostParameter($args);
$Req->addUpload("photo",$_FILES["upfile"]["tmp_name"]) or die("Error");
$res = $Req->send();

$photoxml = simplexml_load_string($res->getBody());
$photoid = $photoxml->photoid;

//API パラメータ
$params = array(
    'api_key' => $apikey,
    'method' => 'flickr.photos.getInfo',
    'photo_id' => $photoid,
);

foreach ($params as $k => $v){
    $encoded_params[] = urlencode($k).'='.urlencode($v);
}

$url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);

// 初期化
$session = curl_init();
// アクセスするURL
curl_setopt($session, CURLOPT_URL, $url);
// ヘッダ文字列の出力
curl_setopt($session, CURLOPT_HEADER, false);
// curl_execの返り値を文字列にする
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
// 実行
$url = curl_exec($session);
// 終了
curl_close($session);

$photodata = simplexml_load_string($url);

$photourl = "http://static.flickr.com/"
. $photodata->photo->attributes()->server ."/"
. $photodata->photo->attributes()->id . "_"
. $photodata->photo->attributes()->secret;

echo $photourl;//写真のURL*/
}else{
    echo null;
}
?>