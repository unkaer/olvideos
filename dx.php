<?php
set_time_limit(0);
ob_implicit_flush();
if(array_key_exists("wd", $_POST)|array_key_exists("wd", $_GET)){
    if(isset($_POST["wd"])){$name = $_POST["wd"];}else{$name = $_GET["wd"];}
    $teshu=array(array(",","!",":"),array("，","！","："),array("(",")","普通话","粤语","版","[","]","\"","\'"));  // 0替换为1，2删除
    for($i=0;$i<sizeof($teshu[0]);$i++){
        $name=str_replace($teshu[0][$i],$teshu[1][$i],$name);
    }
    for($i=0;$i<sizeof($teshu[2]);$i++){
        $name=str_replace($teshu[2][$i],'',$name);
    }
}else{
    header("Location: ..");
    exit();
}
// 存放搜索记录到 cookie
$search = serialize(array($name,time()));
$expire=time()+60*60*24*30;
setcookie("search", $search, $expire);    // 存放搜索数据

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title><?php echo $name;?>·搜索结果</title>
        <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
        <link rel="bookmark" href="./favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="./css/d.css" type="text/css" />
</head>
<body>
<div id="head">
    <div id="head1">
        <a href="..">首页</a>
    </div>
    <div id="head2">
        <form action="./dx.php" method='POST' onsubmit="return checkform();">
            <p>本 站 在 线 影 视：<input id="ipt" type="text" name="wd" autofocus value="<?php echo $name;?>">
            <input type="submit" value="搜索"></p>
        </form>
    </div>
</div>
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

// 爬虫资源站页面
function playdetail($detailurl,$f){
    global $array,$n;
    $html = file_get_contents($detailurl);
    preg_match_all("/https?:\/\/.*\.jpe?g/",$html,$cover); // 封面 $cover[0][0]
    preg_match_all("/<h2>(.*)<\/h2>/",$html,$title); // 标题 $title[1][0]
    preg_match_all("/([^>]+)[$](https?.*\/index.m3u8)/",$html,$playurl);  // 播放地址
    preg_match_all("/上映：<span>(.*?)</",$html,$year);  // 上映时间 上映：<span>2016</span>
    preg_match_all("/类型：<span>(.*?)</",$html,$type);  // 类型：<span>恐怖片 <
    preg_match_all("/<div class=\"vodplayinfo\">(.*)\s*<\/div>/",$html,$des);  // ok 简介 des[1][0]
    if($des[1][0]="\n"){
        preg_match_all("/txt=\"(.*?)\"/",$html,$des);  // 最大 简介 des[1][0]
    }
    preg_match_all("/[^>]+[$](https?.*mp4)/",$html,$download);  // 下载名称及地址
    for($i=0;$i<sizeof($playurl[2]);$i++){
        $array[$n]["tag"][$i]=$playurl[1][$i];  // 集数
        $array[$n]["url"][$i]=$playurl[2][$i];  // 播放地址
        if($download[1]==Array()){
            $array[$n]["download"][$i]="暂无";
        }
        else{
            $array[$n]["download"][$i]=$download[1][$i];  // 下载地址
        }
    }
    $array[$n]["cover"]=$cover[0][0];  // 封面
    $array[$n]["title"]=$title[1][0];  // 名称
    $array[$n]["year"]=$year[1][0];  // 上映时间
    $array[$n]["type"]=$type[1][0];  // 类型
    $array[$n]["des"]=$des[1][0];  // 简介
    if($f){
        build();
    }
    $n++;
    
}

//API 获取视频id geturl视频信息
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
        $type=(string)$video->type; //类型
        $year=(string)$video->year; //上映时间
        $des=(string)$video->des; //简介
        $pic=(string)$video->pic; //封面
        $url=(string)$video->dl->dd;   //播放地址
        preg_match_all("/http?:\/\/[^#]*\/index.m3u8/",$url,$playurl);
        preg_match_all("/#?([^#]+)[$]/",$url,$tag);
        $title=(string)$video->name;
        for($i=0;$i<sizeof($playurl[0]);$i++){
            $array[$n]["tag"][$i]=$tag[1][$i];  // 集数
            $array[$n]["url"][$i]=$playurl[0][$i];  // 播放地址
            $array[$n]["download"][$i]="暂无";
        }
        $array[$n]["title"]=$title;  // 名称
        $array[$n]["type"]=$type;  // 类型
        $array[$n]["year"]=$year;  // 上映时间
        $array[$n]["des"]=$des;  // 简介
        $array[$n]["cover"]=$pic;  // 封面
        if($f){
            build();
        }
        $n++;
    }
}

function build(){
    global $array,$n,$file,$name;
    $luanma=array("<",">","rgb","(17, 17, 17)","&nbsp","span","style","="," ","color",":","13px","font-family","Helvetica",",","Arial","sans-serif",";","font-size","\"","/","br"); //部分简介乱码
    for($i=0;$i<sizeof($luanma);$i++){
        $array[$n]["des"]=str_replace($luanma[$i],"",$array[$n]["des"]);
    }
    if(isset($array[$n]["title"])){
        print_r('<div id="playul"><div><a id="cover" href="./play.php?wd='.$name.'&id='.$n.'" target="_blank" title="'.$array[$n]["des"].'" style="background-image: url('.$array[$n]["cover"].')">');  // 封面
        print_r("<span class=\"type\" >".$array[$n]["type"]."</span>");
        print_r("<span class=\"year\" >".$array[$n]["year"]."</span></a>");
        print_r("<form action=\"./play.php\" method='POST'>");
        print_r("<input type=\"hidden\" name=\"wd\" value=".$name.">");
        print_r("<input type=\"hidden\" name=\"id\" value=".$n.">");
        print_r("<input type=\"submit\" value=播放·".$array[$n]['title']."></form>");
        if($array[$n]["download"][0]!="暂无"){
            print_r("<P>迅雷p2p下载：<textarea>");
            for($j=0;$j<sizeof($array[$n]["tag"]);$j++){
                print_r($array[$n]["tag"][$j]."$".$array[$n]["download"][$j]."\n");
            }
            print_r("</textarea></p>");
        }
        print_r("</div></div>");
    }
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
preg_match_all("/\((.*?)\)/",$_SERVER['HTTP_USER_AGENT'],$llq);
$user = "域名:".@$_SERVER['SERVER_NAME']."\$ip:".@$_SERVER['REMOTE_ADDR']."\$".@$_SERVER['HTTP_CF_CONNECTING_IP']."\$用户:".@$_SERVER['USERDOMAIN'].@$_SERVER['USERNAME'].@$_SERVER['HTTP_CF_RAY']."\$浏览器:".$llq[1][0]."\$时间:".date("Y-m-d H:i:s",time())."\$搜索:".$name."\n<br>";  //用户识别码
$log = fopen("./data/.log","a");
fwrite($log,$user);
fclose($log);
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
        getarray(false);  // 获取数据（不建立网页）
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($array));//写入缓存 
        }
    }
    
}
else{//不存在 第一次  边API 边爬取 边建立网页 边存  因为完整太慢 每一组数据存一次   
    if(!isset($_COOKIE['count'])){
        setcookie('count',1,time()+15);
        getarray(true);  // 获取数据（并建立网页）
    } else if ($_COOKIE['count'] < 2){
        setcookie('count',$_COOKIE['count']+1,time()+15);
        getarray(true);  // 获取数据（并建立网页）
    }else{echo '新提交太频繁，15秒内只能提交两次，请等待15秒后在试。';}  // 防止恶意 浪费服务器资源
}

?>
</body>
</html>