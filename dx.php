<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>APCI视频列表</title>
        <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
        <link rel="bookmark" href="./favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="./css/d.css" type="text/css" />
</head>
<body>
<div id="head"><ul class="active"><a href="..">首页</a></ul></div>
<?php

$api=array('http://cj.wlzy.tv/inc/api_mac_m3u8.php','http://api.iokzy.com/inc/apickm3u8.php');  // API方式 资源站API
$url=array("http://www.zuidazy3.net/index.php","http://www.okzyw.com/index.php");    // 爬虫方式 资源站的搜索页
$n = 0;
if(array_key_exists("wd", $_POST)){
    $name=$_POST['wd'];
}else{
    header("Location: ..");
    exit();
}

// 只API
function playdetail($detailurl){
    global $array,$n;
    $html = file_get_contents($detailurl);
    preg_match_all("/https?:\/\/.*\.jpe?g/",$html,$cover); // 封面 $cover[0][0]
    preg_match_all("/<h2>(.*)<\/h2>/",$html,$title); // 标题 $title[1][0]
    preg_match_all("/([^>]+)[$](https?.*\/index.m3u8)/",$html,$playurl);  // 播放地址
    for($i=0;$i<sizeof($playurl[2]);$i++){
        $array[$n]["tag"][$i]=$playurl[1][$i];  // 集数
        $array[$n]["url"][$i]=$playurl[2][$i];  // 播放地址
    }
    $array[$n]["title"]=$title[1][0];  // 名称
    $array[$n]["cover"]=$cover[0][0];  // 封面
    $n++;
    
}

//获取视频id  只爬虫
function getname($name,$api){
    $data = file_get_contents($api."?wd=".$name);
    $xml = simplexml_load_string($data);
    foreach($xml->list->video as $video){
        $id=(string)$video->id;
        geturl($id,$api);
    }
}

function geturl($id,$api){
    $data = file_get_contents($api."?ac=videolist&ids=".$id);
    $xml = simplexml_load_string($data);
    foreach($xml->list->video as $video){
        global $array,$n;
        $pic=(string)$video->pic; 
        $url=(string)$video->dl->dd;   //播放地址
        preg_match_all("/http?:\/\/[^#]*\/index.m3u8/",$url,$playurl);
        preg_match_all("/#?([^#]+)[$]/",$url,$tag);
        $title=(string)$video->name;
        for($i=0;$i<sizeof($playurl[0]);$i++){
            $array[$n]["tag"][$i]=$tag[1][$i];  // 集数
            $array[$n]["url"][$i]=$playurl[0][$i];  // 播放地址
        }
        $array[$n]["title"]=$title;  // 名称
        $array[$n]["cover"]=$pic;  // 封面
        $n++;
    }
}

function build(){
    global $array;
    for($i=0;$i<sizeof($array);$i++){
        print_r('<li id="play"><img id="cover" src='.$array[$i]["cover"].'>');  // 封面
        print_r("<form action=\"./play.php\" method='POST'>");
        for($j=0;$j<sizeof($array[$i]["tag"]);$j++){
            $urls[0][$j]=$array[$i]["tag"][$j];  // 集数
            $urls[1][$j]=$array[$i]["url"][$j];  // 播放地址
        }
        print_r("<input type=\"hidden\" name=\"urls\" value=".json_encode($urls).">");
        print_r("<input type=\"hidden\" name=\"name\" value=".$array[$i]['title'].">");
        print_r("<input type=\"submit\" value=播放·".$array[$i]['title']."></form>");
        print_r("</li>");
    }
    
}



$file="./data/".$name.".p"; 
//读出缓存 
if(file_exists($file)){
    $handle=fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
    $array=unserialize(fread($handle,filesize($file))); 
    build();  // 建立网页
    date_default_timezone_set("Asia/Shanghai");
    $time=time()-filemtime($file);
    echo "<br><p>更新时间：".date("Y-m-d H:i:s",filemtime($file))."</p>";
    if($time>86400){    // 缓存文件太久才会更新  86400 24H
        for($i=0;$i<sizeof($api);$i++){    // API 方式
            getname($name,$api[$i]);
        }
        for($i=0;$i<sizeof($url);$i++){   // 爬虫方式
            $html = file_get_contents($url[$i]."?m=vod-search&wd=".$name);
            preg_match_all("/\?m=vod-detail-id-.+.html/",$html,$detail);
            foreach($detail[0] as $x=>$x_value){
                playdetail($url[$i].$x_value);
             }
        }
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($array));//写入缓存 
          }
    }
    
}
else{
    //不存在 第一次  只API 只爬取 只建立网页
    for($i=0;$i<sizeof($api);$i++){ 
        getname($name,$api[$i]);
    }
    for($i=0;$i<sizeof($url);$i++){   // 爬虫方式
        $html = file_get_contents($url[$i]."?m=vod-search&wd=".$name);
        preg_match_all("/\?m=vod-detail-id-.+.html/",$html,$detail);
        foreach($detail[0] as $x=>$x_value){
            playdetail($url[$i].$x_value);
         }
    }
    build();  // 建立网页
    if(false!==fopen($file,'w+')){ 
        file_put_contents($file,serialize($array));//写入缓存 
      }
}

?>
</body>
</html>