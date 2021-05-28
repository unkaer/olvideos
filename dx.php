<?php
include './config.php';
include './src/function.php';

// set_time_limit(0);
// ob_implicit_flush();
if(array_key_exists("wd", $_POST)|array_key_exists("wd", $_GET)){
    if(isset($_POST["wd"])){$name = $_POST["wd"];}else{$name = $_GET["wd"];}
    preg_match_all('/https?:\/\/.*/',$name,$jx);  // 判断输入的是url
    if(isset($jx[0][0])){
        header("Location: ./dx.php?url=".$name);
        exit();
    }
    $js = 0;
}else{
    if(array_key_exists("url", $_POST)|array_key_exists("url", $_GET)){
        if(isset($_POST["url"])){$jx = $_POST["url"];}else{$jx = $_GET["url"];}
        preg_match_all('/https?:\/\/.*/',$jx,$jx1);  // 确保输入的是正确的url
        if(!isset($jx1[0][0])){
            $jx = "https://".$jx;
        }
        $url = array("/iqiyi.com\/.+/","/v.qq.com\/.+/","/v.youku.com\/.+/","/v.pptv.com\/.+/","/mgtv.com\/.+/","/bilibili.com\/.+/");
        for($i=0;$i<sizeof($url);$i++){
            preg_match_all($url[$i],$jx,$py);
            if(isset($py[0][0])){
                $f = 1;
            }
        }
        if($f!=1){
            // url有误或者暂不支持
            header("Location: ./error.php?error_code=5&url=".$jx);
            exit();
        }
        $html = curl_get($jx);
        preg_match_all('/<title>(.*?)<\/title>/',$html,$py1);
        if(isset($py1[1][0])){
            $py1=$py1[1][0];
            $teshu=array("腾讯","爱奇艺","优酷","PP视频","原PPTV聚力视频","芒果TV","高清","全集","完整","卫视版","视频","在线","观看","电影","电视剧","1080P","平台","正版","-","_","：","正片","bilibili","哔哩哔哩","番剧","全片");
            for($i=0;$i<sizeof($teshu);$i++){
                $py1=str_replace($teshu[$i],"",$py1);
            }
            $name = $py1;
            //几种情况  第1季xxx 第1集 第22期xx
            preg_match_all('/(.*?)第/',$py1,$py2);
            if(isset($py2[1][0])){
                $name = $py2[1][0];
            }
            preg_match_all('/第([0-9]*?)集/',$py1,$py2);
            if(isset($py2[1][0])){
                $js = $py2[1][0]-1;
            }
            else{
                $js = 0;
            }
        }
        else{
            // url有误或者暂不支持
            header("Location: ./error.php?error_code=4&url=".$jx);
            exit();
        }
    }
    else{
        header("Location: ..");
        exit();
    }
}
$teshu=array(array(",",":",),array("，","："),array("(",")","普通话","粤语","版","[","]","《","》","\"","\'"," ","-","`","~","·","独家纪录片"));  // 0替换为1，2删除
for($i=0;$i<sizeof($teshu[0]);$i++){
    $name=str_replace($teshu[0][$i],$teshu[1][$i],$name);
}
for($i=0;$i<sizeof($teshu[2]);$i++){
    $name=str_replace($teshu[2][$i],'',$name);
}
if($name==""){
    // url暂不支持
    header("Location: ./error.php?error_code=5&url=".$jx);
    exit();
}
if(array_key_exists("gx", $_POST)|array_key_exists("gx", $_GET)){if(isset($_POST["gx"])){$gx = $_POST["gx"];}else{$gx = $_GET["gx"];}}else{$gx=0;}
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
        <link rel="shortcut icon" href="./src/favicon.ico" type="image/x-icon">
        <link rel="bookmark" href="./src/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="./src/css/d.css" type="text/css" />
        <script>
            var _hmt = _hmt || [];
            (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?<?php echo($baiduid); ?>";
            var s = document.getElementsByTagName("script")[0]; 
            s.parentNode.insertBefore(hm, s);
            })();
        </script>
</head>
<body>
<?php include_once("./src/baidu_js_push.php"); ?>
<div id="head">
    <div id="head1">
        <a href="..">首页</a>
    </div>
    <div id="head2">
        <form action="./dx.php" method='POST' onsubmit="return checkform();">
            <p>本 站 在 线 影 视：<input id="ipt" type="text" name="wd" autofocus value="<?php echo $name;?>">
            <input type="hidden" name="gx" value="1">
            <input type="submit" value="重新搜索"></p>
        </form>
        <form action="./dm.php" method='POST'>
            <input id="ipt" type="hidden" name="wd" autofocus value="<?php echo $name;?>">
            <p>如果没有搜索结果，请减少关键词 或者尝试
            <input type="submit" value="站外搜索"></p>
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
<div>
<?php
include './config.php';

$api=array('https://cj.apibdzy.com/inc/api.php','http://cj.bajiecaiji.com/inc/seacmsapi.php');  // API方式 资源站API
$api1=array('百度云资源','八戒资源');  // API方式 资源站API
$url=array("http://www.okzyw.com/index.php","http://www.zuidazy5.com//index.php");    // 爬虫方式 资源站的搜索页
$url1=array("ok资源爬取","最大资源爬取");    // 爬虫方式 资源站的搜索页
$n = 0;

function img_to_file($imgurl,$file){  //图片保存到本地
    if(!file_exists($file)){
        ob_start();
        readfile($imgurl);
        $img=ob_get_contents();ob_end_clean();
        $imgurl = $file;
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,$img);//写入缓存 
        }
    }
    return $file;
}


// 爬虫资源站页面
function playdetail($detailurl,$url1,$f){
    global $array,$n,$ftp;
    $html = curl_get($detailurl);
    preg_match_all("/<h2>(.*)<\/h2>/",$html,$title); // 标题 $title[1][0]

    preg_match_all("/https?:\/\/.*\.jpe?g/",$html,$cover); // 封面 $cover[0][0]
    if ($ftp) {
        $cover[0][0] = img_to_file($cover[0][0],"./data/img/".$title[1][0].$n.".jpg");
    }

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
    $array[$n]["zy"]=$url1;  // 资源来源
    if($f){
        build($f);
    }
    $n++;
    
}

//API 获取视频id geturl视频信息
function getname($api,$api1,$f){
    global $name;
    $data = curl_get($api."?wd=".$name);
    $xml = simplexml_load_string($data);
    foreach($xml->list->video as $video){
        $id=(string)$video->id;
        geturl($id,$api,$api1,$f);
    }
}

function geturl($id,$api,$api1,$f){
    global $ftp;
    $data = curl_get($api."?ac=videolist&ids=".$id);
    $xml = simplexml_load_string($data);
    foreach($xml->list->video as $video){
        global $array,$n;
        $type=(string)$video->type; //类型
        $year=(string)$video->year; //上映时间
        $des=(string)$video->des; //简介

        $zzt=count($video->dl->dd);
		for($i=0;$i<$zzt;$i++)
		{
            $bfq = (string)$video->dl->dd[$i]['flag']; //播放器标识
            $url = (string)$video->dl->dd[$i];   //播放地址
            // preg_match_all("/https?:\/\/[^#]*\/index.m3u8/",$url,$playurl);
            preg_match_all("/https?:\/\/[^#$]*/",$url,$playurl);
            preg_match_all("/#?([^#$]+)[$]h/",$url,$tag);
		}
        
        $title=(string)$video->name;
        $pic=(string)$video->pic; //封面
        if ($ftp) {
            $pic = img_to_file($pic,"./data/img/".$title.$n.".jpg");
        }

        for($i=0;$i<count($playurl[0]);$i++){
            $array[$n]["tag"][$i]=$tag[1][$i];  // 集数
            $array[$n]["url"][$i]=$playurl[0][$i];  // 播放地址
            $array[$n]["download"][$i]="暂无";
        }
        $array[$n]["title"]=$title;  // 名称
        $array[$n]["type"]=$type;  // 类型
        $array[$n]["year"]=$year;  // 上映时间
        $array[$n]["des"]=$des;  // 简介
        $array[$n]["cover"]=$pic;  // 封面
        $array[$n]["zy"]=$api1;  // 资源来源
        if($f){
            build($f);
        }
        $n++;
    }
}

function build($f){
    global $array,$n,$file,$name;
    $luanma=array("<",">","rgb","(17, 17, 17)","&nbsp","span","style","="," ","color",":","13px","font-family","Helvetica",",","Arial","sans-serif",";","font-size","\"","/","br"); //部分简介乱码
    for($i=0;$i<sizeof($luanma);$i++){
        $array[$n]["des"]=str_replace($luanma[$i],"",$array[$n]["des"]);
    }
    if(isset($array[$n]["title"])){
        $pb = 1;
        if(strstr($array[$n]["type"],"伦理")){$pb = 0;}
        if(strstr($array[$n]["type"],"福利")){$pb = 0;}
        if(strstr($array[$n]["type"],"电影解说")){$pb = 0;}
        if($pb){   // 屏蔽标签 代 “伦理” “福利”的搜索结果
            print_r('<div id="playul"><p>'.$array[$n]["zy"].'</p><div><a id="cover" href="./play.php?wd='.$name.'&id='.$n.'" target="_blank" title="'.$array[$n]["des"].'" style="background-image: url('.$array[$n]["cover"].')">');  // 封面
            print_r("<span class=\"type\" >".$array[$n]["type"]."</span>");
            print_r("<span class=\"year\" >".$array[$n]["year"]."</span></a>");
            // if($array[$n]["download"][0]!="暂无"){
            //     print_r("<<span class=\"p2p\">迅雷p2p下载</span>");
            //     for($j=0;$j<sizeof($array[$n]["tag"]);$j++){
            //         print_r($array[$n]["tag"][$j]."$".$array[$n]["download"][$j]."\n");
            //     }
            // }
            print_r("<div class=\"title\" >".$array[$n]['title']."</div>");
            print_r("</div></div>");
        }
        else{
            // print_r("部分结果违规");
        }
    }
    if($f){
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($array));//写入缓存 
        }
    }
}

function getarray($f){
    global $api,$api1,$url,$url1,$name;
    // 只最大 API   // 节约服务器
    getname($api[1],"路线-1",$f);
    // getname($api[0],"路线-0",$f);

    
    // 只ok 爬取   // 节约服务器  有 爬虫处理 暂时舍弃
    // $html = curl_get($url[0]."?m=vod-search&wd=".$name);   // 爬虫方式
    // preg_match_all("/\?m=vod-detail-id-.+.html/",$html,$detail);
    // foreach($detail[0] as $x=>$x_value){
    //     playdetail($url[0].$x_value,"路线-2",$f);
    // }
}


$file="./data/".$name.".p";
preg_match_all("/\((.*?)\)/",$_SERVER['HTTP_USER_AGENT'],$llq);
$user = "域名:".@$_SERVER['SERVER_NAME']."\$ip:".@$_SERVER['REMOTE_ADDR']."\$".@$_SERVER['HTTP_CF_CONNECTING_IP']."\$用户:".@$_SERVER['USERDOMAIN'].@$_SERVER['USERNAME'].@$_SERVER['HTTP_CF_RAY']."\$浏览器:".$llq[1][0]."\$时间:".date("Y-m-d H:i:s",time())."\$搜索:".$name."\n<br>";  //用户识别码
$log = fopen("./data/.log","a");
fwrite($log,$user);
fclose($log);
//读出缓存 
if(file_exists($file)){
    date_default_timezone_set("Asia/Shanghai");
    $handle=fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
    $array=unserialize(fread($handle,filesize($file)));
    $time=time()-filemtime($file); 
    if($time>86400|$gx==1){    // 缓存文件太久才会更新  86400 24H
        $n=0;
        getarray(false);  // 获取数据（不建立网页）
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($array));//写入缓存 
        }
    }
    for($n=0;$n<sizeof($array);$n++){
        build(false);  // 只建立网页，不更新内容
    }
    echo "<br><p>更新时间：".date("Y-m-d H:i:s",filemtime($file))."</p>";   

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
        </div>
    </body>
</html>