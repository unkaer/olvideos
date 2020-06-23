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
<?php

$api=array('http://cj.wlzy.tv/inc/api_mac_m3u8.php','http://api.iokzy.com/inc/apickm3u8.php');
if(array_key_exists("name", $_POST)){
    $name=$_POST['name'];
}else{
    header("Location: ..");
    exit();
}

//获取视频id  并打印
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
        $pic=(string)$video->pic;
        print_r('<li id="play"><img id="cover" src='.$pic.'>');  // 封面
        $url=(string)$video->dl->dd;   //播放地址
        preg_match_all("/http?:\/\/[^#]*\/index.m3u8/",$url,$playurl);
        preg_match_all("/#?([^#]+)[$]/",$url,$tag);
        $title=(string)$video->name;
        print_r("<form action=\"./play.php\" method='POST'>");
        for($i=0;$i<sizeof($playurl[0]);$i++){
            $urls[0][$i]=$tag[1][$i];  // 集数
            $urls[1][$i]=$playurl[0][$i];  // 播放地址
        }
        print_r("<input type=\"hidden\" name=\"urls\" value=".json_encode($urls).">");
        print_r("<input type=\"hidden\" name=\"name\" value=".$title.">");
        print_r("<input type=\"submit\" value=播放·".$title."></form>");
        print_r("</li>");
    }
}

print_r('<div id="head"><ul class="active"><a href="..">首页</a></ul></div>');
for($i=0;$i<sizeof($api);$i++){ 
    print_r('<ul><p>接口'.($i+1)).'</p>';
    getname($name,$api[$i]);
    print_r("</ul>");
}
?>
</body>
</html>