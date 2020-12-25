<?php
include './config.php';
include './src/function.php';

print_r($head);  //网页开头

echo '<body>';
print_r($header);  //顶端导航

?>

<div id='content'>
    <div id='head2'>
<?php


date_default_timezone_set("Asia/Shanghai");
if(isset($_COOKIE['search'])){
    $searchs = unserialize($_COOKIE['search']);
    print_r("<div><form action='./dx.php' method='POST'><p>继续上一次搜索<input type='hidden' type='text' name='wd' value=".$searchs[0]."> <input id='ipt' onmousemove='red(this)' onmouseout='black(this)' type='submit' value=".$searchs[0].">".date('Y/m/d/H:i',$searchs[1])."</p></form></div>");
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
if(isset($_COOKIE['search1'])){
    $searchs = unserialize($_COOKIE['search1']);
    print_r("<div><form action='./dm.php' method='POST'><p>继续上一次搜索<input type='hidden' type='text' name='wd' value=".$searchs[0]."> <input id='ipt' onmousemove='red(this)' onmouseout='black(this)' type='submit' value=".$searchs[0].">".date('Y/m/d/H:i',$searchs[1])."</p></form></div>");
}
?>
    </div>

<?php

$url=array("https://list.iqiyi.com/www/1/-------------11-1-1-iqiyi--.html","https://list.iqiyi.com/www/2/-------------11-1-1-iqiyi--.html");
$name0=array("爱奇艺电影","爱奇艺电视剧");
for($i=0;$i<sizeof($url);$i++){
    $file="./data/aqy".$i.".dp"; 
    //读出缓存 
    if(file_exists($file)){
        date_default_timezone_set("Asia/Shanghai");
        $time=time()-filemtime($file);
        if($time>86400){    // 缓存文件太久才会更新  86400 24H
            $html = file_get_contents($url[$i]);
            preg_match_all('/<a\s*?title=\"(.*?)\"\s*?class="link-txt"/',$html,$title);  // 匹配排行榜上的名称
            if(false!==fopen($file,'w+')){ 
                file_put_contents($file,serialize($title[1]));//写入缓存 
            }
        }
        $handle=fopen($file,'r');// 存在 读取内容 
        $title[1]=unserialize(fread($handle,filesize($file)));
    }
    else{
        $html = file_get_contents($url[$i]);
        preg_match_all('/<a\s*?title=\"(.*?)\"\s*?class="link-txt"/',$html,$title);  // 匹配排行榜上的名称
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($title[1]));//写入缓存 
        }
    }
    $aqy[$i] = $title[1];
}

$url ="https://v.qq.com/";
// $name=array('腾讯电影','腾讯电视剧','腾讯综艺','腾讯动漫');
// $rlue1=array('/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">电影排行榜[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/','/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">电视剧频道排行[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/','/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">综艺排行榜[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/','/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">动漫排行榜[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/');
$name1=array('腾讯电影','腾讯电视剧');
$rlue1=array('/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">电影排行榜[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/','/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">电视剧频道排行[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/');
$rlue2='/<span class="rank_title">(.*?)<\/span>\s*?<span class="rank_desc">.*?<\/span>\s*?<span class="rank_update">([\s\S]*?)<\/span>/';
for($i=0;$i<sizeof($rlue1);$i++){
    $file="./data/txsp".$i.".dp"; 
    //读出缓存 
    if(file_exists($file)){
        date_default_timezone_set("Asia/Shanghai");
        $time=time()-filemtime($file);
        if($time>604800){    // 缓存文件太久才会更新  86400 24H*7 604800
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
    $txsp[$i] =  $py2[1];
}

function drawlist($title){
    for($i=0;$i<sizeof($title)&$i<10;$i++){
        print_r("<li><form action='./dx.php' method='POST'>
        <input id='ipt'  type='hidden' type='text' name='wd' value=".$title[$i].">
        <input id='button' onmousemove='red(this)' onmouseout='black(this)' style='color=\"black\";background-color:rgb(255, 255, 255);' type='submit' value='".($i+1).".".$title[$i].$title[$i]."'></form></li>");
    }
}

// 电影排行榜  爱奇艺、腾讯
print_r("<div id='movie'>电影排行榜<ul id='movie-info'>爱奇艺");
drawlist($aqy[0]);
echo "</ul><ul id='movie-info'>腾讯视频";
drawlist($txsp[0]);
echo "</ul></div>";

// 电视剧排行  爱奇艺、腾讯
print_r("<div id='movie'>电视剧排行榜<ul id='movie-info'>爱奇艺");
drawlist($aqy[1]);
echo "</ul><ul id='movie-info'>腾讯视频";
drawlist($txsp[1]);
echo "</ul></div>";


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
echo '</div>';

print_r($footer);
?>


</body>
</html>