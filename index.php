<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>在线视频站</title>
        <link rel="shortcut icon" href="./src/favicon.ico" type="image/x-icon">
        <link rel="bookmark" href="./src/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="./src/css/d.css" type="text/css" />
</head>
<body>
<div id="head">
    <div id="head1">
        <a href="..">首页</a>
        <a href="./admin">后台</a>   
    </div>
    <div id="head2">
        <form action="./dx.php" method='POST' onsubmit="return checkform(this);">
            <p>本 站 在 线 影 视：<input id="ipt" type="text" name="wd" autofocus value="">
            <input type="submit" value="搜索"></p>
        </form>
        <form action="./dx.php" method='POST' onsubmit="return checkform(this);">
            <p>url视频解析：<input id="ipt" type="text" name="url" autofocus value="">
            <input type="submit" value="解析"></p>
        </form>
<?php
date_default_timezone_set("Asia/Shanghai");
if(isset($_COOKIE['search'])){
    $searchs = unserialize($_COOKIE['search']);
    print_r("<div><form action='./dx.php' method='POST'><p>继续上一次搜素<input type='hidden' type='text' name='wd' value=".$searchs[0]."> <input id='ipt' onmousemove='red(this)' onmouseout='black(this)' type='submit' value=".$searchs[0].">".date('Y/m/d/H:i',$searchs[1])."</p></form></div>");
}
if(isset($_COOKIE['dt'])){
    $dt = unserialize($_COOKIE['dt']);
    $n = $dt[1];
    $file="./data/".$dt[0].".p";  //读出缓存 
    if(file_exists($file)){
        $handle=fopen($file,'r');
        $array=unserialize(fread($handle,filesize($file)));
        print_r("<div><form action=\"./play.php\" method='POST'><p>继续上一次观看");
        print_r("<input type=\"hidden\" name=\"wd\" value=".$dt[0].">");
        print_r("<input type=\"hidden\" name=\"id\" value=".$n.">");
        if(isset($_COOKIE['jishu'])){
            print_r("<input type=\"hidden\" name=\"js\" value=".$_COOKIE['jishu'].">");
        }
        print_r("<input id='ipt' onmousemove='red(this)' onmouseout='black(this)' type=\"submit\" value=".$array[$n]['title'].@$array[$n]["tag"][$_COOKIE['jishu']]."></p></form></div>");
        
    }
}
?>
    <form action="./dm.php" method='POST' onsubmit="return checkform1();">
    <p>动 漫 视 频 站：<input id="ipt1" type="text" name="wd" value="">
    <input type="submit" value="搜索"></p>
    </form>
    <script type="text/javascript" >
    function checkform(x){
        if(x.value.length==0){
            alert('输入不能为空！！！');
            x.focus();
            return false;
        }
        else{return true}
    }
    function checkform1(){
        if(document.getElementById('ipt1').value.length==0){
            alert('输入不能为空！！！');
            document.getElementById('ipt1').focus();
            return false;
        }
        else{return true}
    }
    </script>
    <p>第一次较慢，缓存后秒开。</p>
    <p>如果没有搜到，请减少关键词。</p>
    <p>下方排行榜，每次随机排序</p>
</div></div>
<div id='playlist'>

<?php
$url=array("https://list.iqiyi.com/www/1/-------------11-1-1-iqiyi--.html","https://list.iqiyi.com/www/2/-------------11-1-1-iqiyi--.html");
$name=array("爱奇艺电影","爱奇艺电视剧");
for($i=0;$i<sizeof($url);$i++){
    $file="./data/aqy".$i.".p"; 
    //读出缓存 
    if(file_exists($file)){
        date_default_timezone_set("Asia/Shanghai");
        $time=time()-filemtime($file);
        if($time>86400){    // 缓存文件太久才会更新  86400 24H
            $html = file_get_contents($url[$i]);
            preg_match_all('/<a\s*?title=\"(.*?)\"\s*?class="link-txt"/',$html,$title);  // 播放地址
            if(false!==fopen($file,'w+')){ 
                file_put_contents($file,serialize($title[1]));//写入缓存 
            }
        }
        $handle=fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
        $title[1]=unserialize(fread($handle,filesize($file)));
    }
    else{
        $html = file_get_contents($url[$i]);
        preg_match_all('/<a\s*?title=\"(.*?)\"\s*?class="link-txt"/',$html,$title);  // 播放地址
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($title[1]));//写入缓存 
        }
    }
    print_r("<div id='playul'>".$name[$i]);
    $px=$title[1];
    shuffle($px);
    for($j=0;$j<sizeof($title[1])&$j<10;$j++){
        print_r("<li><form action='./dx.php' method='POST'>
        <input id='ipt'  type='hidden' type='text' name='wd' value=".$px[$j].">
        <input id='button' onmousemove='red(this)' onmouseout='black(this)' style='color=\"black\";background-color:rgb(255, 255, 255);' type='submit' value=".($j+1).".".$px[$j]."></form>");
    }
    echo "</li></div>";
}
$url ="https://v.qq.com/";
$name=array('腾讯电影','腾讯电视剧','腾讯综艺','腾讯动漫');
$rlue1=array('/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">电影排行榜[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/','/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">电视剧频道排行[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/','/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">综艺排行榜[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/','/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">动漫排行榜[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/');
$rlue2='/<span class="rank_title">(.*?)<\/span>\s*?<span class="rank_desc">.*?<\/span>\s*?<span class="rank_update">([\s\S]*?)<\/span>/';
for($i=0;$i<sizeof($rlue1);$i++){
    $file="./data/txsp".$i.".p"; 
    //读出缓存 
    if(file_exists($file)){
        date_default_timezone_set("Asia/Shanghai");
        $time=time()-filemtime($file);
        if($time>86400){    // 缓存文件太久才会更新  86400 24H
            $html = file_get_contents($url);
            preg_match_all($rlue1[$i],$html,$py1);
            preg_match_all($rlue2,$py1[0][0],$py2);
            if(false!==fopen($file,'w+')){ 
                file_put_contents($file,serialize($py2));//写入缓存 
            }
        }
        $handle=fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
        $py2=unserialize(fread($handle,filesize($file)));
    }
    else{
        $html = file_get_contents($url);
        preg_match_all($rlue1[$i],$html,$py1);
        preg_match_all($rlue2,$py1[0][0],$py2);
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($py2));//写入缓存 
        }
    }
    print_r("<div id='playul'>".$name[$i]);
    for($j=0;$j<sizeof($py2[1])&$j<10;$j++){
        print_r("<li><form action='./dx.php' method='POST'>
        <input id='ipt'  type='hidden' type='text' name='wd' value=".$py2[1][$j].">
        <input id='button' onmousemove='red(this)' onmouseout='black(this)' style='color=\"black\";background-color:rgb(255, 255, 255);' type='submit' value='".($j+1).".".$py2[1][$j].$py2[2][$j]."'></form>");
    }
    echo "</li></div>";
}
// 待添加 每日推荐
echo "<div id='playul'></div>";
// 爱奇艺、腾讯视频链接
print_r('<div id="playul">
<p><a href="https://www.iqiyi.com/" target="_blank">爱奇艺</a></p>
<p><a href="https://v.qq.com/" target="_blank">腾讯视频</a></p>
<p><a href="https://www.youku.com/" target="_blank">优酷</a></p>
<p><a href="http://www.pptv.com/" target="_blank">PPTV</a></p></div>');

// 鼠标移动选择 变红特效
echo '<script>
function red(x){
x.style.color="red";
}

function black(x){
x.style.color="black";
}
</script>';
?>
</div>
<div>
<p>暂时只支持输入视频名称 url功能待添加</p>
<p>作者 <a href="https://zan7l.tk/" target="_blank">unkaer</a></p>
<p>源码 <a href="https://github.com/unkaer/olvideos" target="_blank">olvideo</a></p>
</div>

</body>
</html>