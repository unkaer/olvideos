<?php
set_time_limit(0);
ob_end_clean();
ob_implicit_flush(); // 1
if(array_key_exists("wd", $_POST)){
    $teshu=array(",","!",":","(",")","第1季","第2季","第3季","普通话","粤语");
    $name=trim($_POST['wd']);
    for($i=0;$i<sizeof($teshu);$i++){
        $name=str_replace($teshu[$i],'',$name);
    }
}else{
    header("Location: ..");
    exit();
}
echo str_repeat(" ",1024);//部分浏览器需要多于1024字节才开始输出因此这里先产生1024个空格
?>
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
<div id="head"><ul class="active"><a href="..">首页</a></ul>
<form action="./dx.php" method='POST' onsubmit="return checkform();">
    <p>请输入要看的电影 聚合缓存版：<input id="ipt" type="text" name="wd" autofocus value="<?php echo $name;?>">
    <input type="submit" value="搜索"></form>
    <p>如果没有搜到，请适当减少关键词后再试。</p></div>
    <script type="text/javascript" >
    function checkform(){
        if(document.getElementById('ipt').value.length==0){
            alert('输入不能为空！！！');
            document.getElementById('ipt').focus();
            return false;
        }
        else{return true}
    }
    </script>
<?php

$api=array('http://cj.wlzy.tv/inc/api_mac_m3u8.php','http://api.iokzy.com/inc/apickm3u8.php');  // API方式 资源站API
$url=array("http://www.zuidazy3.net/index.php","http://www.okzyw.com/index.php");    // 爬虫方式 资源站的搜索页
$n = 0;

//边API 边打印
function getname1($api){
    $data = file_get_contents($api."?wd=".$_POST['wd']);
    $xml = simplexml_load_string($data);
    foreach($xml->list->video as $video){
        $id=(string)$video->id;
        geturl1($id,$api);
    }
}

function geturl1($id,$api){
    global $array,$file,$n;
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
            $array[$n]["tag"][$i]=$tag[1][$i];  // 集数
            $array[$n]["url"][$i]=$playurl[0][$i];  // 播放地址
        }
        print_r("<input type=\"hidden\" name=\"urls\" value=".json_encode($urls).">");
        print_r("<input type=\"hidden\" name=\"name\" value=".$title.">");
        print_r("<input type=\"submit\" value=播放·".$title."></form>");
        print_r("</li>");
    }
    $array[$n]["title"]=$title;  // 名称
    $array[$n]["cover"]=$pic;  // 封面
    if(false!==fopen($file,'w+')){ 
        file_put_contents($file,serialize($array));//写入缓存 
    }
    $n++;
}

function playdetail1($detailurl){
    global $array,$file,$n;
    $html = file_get_contents($detailurl);
    preg_match_all("/https?:\/\/.*\.jpe?g/",$html,$cover); // 封面 $cover[0][0]
    print_r('<li id="play"><img id="cover" src='.$cover[0][0].'>');  // 封面
    preg_match_all("/<h2>(.*)<\/h2>/",$html,$title); // 标题 $title[1][0]
    preg_match_all("/([^>]+)[$](https?.*\/index.m3u8)/",$html,$playurl);  // 播放地址
    print_r("<form action=\"./play.php\" method='POST'>");
    for($i=0;$i<sizeof($playurl[2]);$i++){
        $urls[0][$i]=$playurl[1][$i];  // 集数
        $urls[1][$i]=$playurl[2][$i];  // 播放地址
        $array[$n]["tag"][$i]=$playurl[1][$i];  // 集数
        $array[$n]["url"][$i]=$playurl[2][$i];  // 播放地址
    }
    print_r("<input type=\"hidden\" name=\"urls\" value=".json_encode($urls).">");
    print_r("<input type=\"hidden\" name=\"name\" value=".$title[1][0].">");
    print_r("<input type=\"submit\" value=播放·".$title[1][0]."></form>");
    print_r("</li>");
    $array[$n]["title"]=$title[1][0];  // 名称
    $array[$n]["cover"]=$cover[0][0];  // 封面
    if(false!==fopen($file,'w+')){ 
        file_put_contents($file,serialize($array));//写入缓存 
    }
    $n++;
    
}

// 只API
function playdetail($detailurl,$f){
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
    if($f){
        build();
    }
    $n++;
    
}

//获取视频id  只爬虫
function getname($api,$f){
    global $name;
    $data = file_get_contents($api."?wd=".$name);
    $xml = simplexml_load_string($data);
    foreach($xml->list->video as $video){
        $id=(string)$video->id;
        geturl($id,$api,$f);
    }
}

function geturl($id,$api,$f){
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
        if($f){
            build();
        }
        $n++;
    }
}

function build(){
    global $array,$n,$file;
    print_r('<li id="play"><img id="cover" src='.$array[$n]["cover"].'>');  // 封面
    print_r("<form action=\"./play.php\" method='POST'>");
    $urls=array();
    for($j=0;$j<sizeof($array[$n]["tag"]);$j++){
        $urls[0][$j]=$array[$n]["tag"][$j];  // 集数
        $urls[1][$j]=$array[$n]["url"][$j];  // 播放地址
    }
    print_r("<input type=\"hidden\" name=\"urls\" value=".json_encode($urls).">");
    print_r("<input type=\"hidden\" name=\"name\" value=".$array[$n]['title'].">");
    print_r("<input type=\"submit\" value=播放·".$array[$n]['title']."></form>");
    print_r("</li>");
    if(false!==fopen($file,'w+')){ 
        file_put_contents($file,serialize($array));//写入缓存 
    }
}

function getarray($f){
    global $api,$url,$name;
    for($i=0;$i<sizeof($api);$i++){    // API 方式
        getname($api[$i],$f);
    }
    for($i=0;$i<sizeof($url);$i++){   // 爬虫方式
        $html = file_get_contents($url[$i]."?m=vod-search&wd=".$name);
        preg_match_all("/\?m=vod-detail-id-.+.html/",$html,$detail);
        foreach($detail[0] as $x=>$x_value){
            playdetail($url[$i].$x_value,$f);
        }
    }
}


$file="./data/".$name.".p"; 
//读出缓存 
if(file_exists($file)){
    $handle=fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
    $array=unserialize(fread($handle,filesize($file)));
    for($n=0;$n<sizeof($array);$n++){
        build();  // 建立网页
    }
    date_default_timezone_set("Asia/Shanghai");
    $time=time()-filemtime($file);
    echo "<br><p>更新时间：".date("Y-m-d H:i:s",filemtime($file))."</p>";
    if($time>86400){    // 缓存文件太久才会更新  86400 24H
        $n=0;
        getarray(false);  // 获取数据
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($array));//写入缓存 
        }
    }
    
}
else{
    //不存在 第一次  边API 边爬取 边建立网页 边存  因为完整太慢 每一组数据存一次
    getarray(true);  // 获取数据 建立网页
}

?>
</body>
</html>