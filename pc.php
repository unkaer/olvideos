<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>爬取视频列表</title>
        <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
        <link rel="bookmark" href="./favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="./css/d.css" type="text/css" />
</head>
<body>
<?php

$url=array("http://www.zuidazy3.net/index.php","http://www.okzyw.com/index.php");
if(array_key_exists("name", $_POST)){
    $name=$_POST['name'];
}else{
    header("Location: ..");
    exit();
}

function playdetail($detailurl)
{
    $html = file_get_contents($detailurl);
    preg_match_all("/https?:\/\/.*\.jpe?g/",$html,$cover); // 封面 $cover[0][0]
    print_r('<li id="play"><img id="cover" src='.$cover[0][0].'>');  // 封面
    preg_match_all("/<h2>(.*)<\/h2>/",$html,$title); // 标题 $title[1][0]
    preg_match_all("/([^>]+)[$](https?.*\/index.m3u8)/",$html,$playurl);  // 播放地址
    print_r("<form action=\"./play.php\" method='POST'>");
    for($i=0;$i<sizeof($playurl[2]);$i++){
        $urls[0][$i]=$playurl[1][$i];  // 集数
        $urls[1][$i]=$playurl[2][$i];  // 播放地址
    }
    print_r("<input type=\"hidden\" name=\"urls\" value=".json_encode($urls).">");
    print_r("<input type=\"hidden\" name=\"name\" value=".$title[1][0].">");
    print_r("<input type=\"submit\" value=播放·".$title[1][0]."></form>");
    print_r("</li>");
    
}

print_r('<div id="head"><ul class="active"><a href="..">首页</a></ul></div>');
for($i=0;$i<sizeof($url);$i++){
    print_r("<ul><p>爬取".($i+1)."</p>");
    $html = file_get_contents($url[$i]."?m=vod-search&wd=".$name);
    preg_match_all("/\?m=vod-detail-id-.+.html/",$html,$detail);
    foreach($detail[0] as $x=>$x_value){
        playdetail($url[$i].$x_value);
    }
    print_r("</ul>");
}
?>
</body>
</html>