<?php
include './config.php';
include './src/function.php';
ini_set('user_agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36 Edg/88.0.705.68');
print_r($head);  //网页开头

echo '<body>';
include_once("./src/baidu_js_push.php");
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

$url = 'https://www.iqiyi.com/';
$name0=array("爱奇艺电影","爱奇艺电视剧");
$rlue1=array('/<div id="dianying" class="qy-mod-wrap-side">[\s\S]*?<\/p><\/div><\/div><\/li><\/ul>\s*?<\/div><\/div><\/div><\/div>/','/<div id="dianshiju" class="qy-mod-wrap-side">[\s\S]*?<\/p><\/div><\/div><\/li><\/ul>\s*?<\/div><\/div><\/div><\/div>/');
$rlue2='/<img src=".*\.j?pn?g" srcset="[^<]*alt="([^<]*)" class="qy-mod-cover">/';

function updata($url,$rlue1,$rlue2){
    $html = file_get_contents($url);
    preg_match_all($rlue1,$html,$py1);
    preg_match_all($rlue2,$py1[0][0],$py2);
    return $py2[1]; //名称集合
}

date_default_timezone_set("Asia/Shanghai");

for($i=0;$i<sizeof($rlue1);$i++){
    $file="./data/aqy".$i.".dp"; 
    //读出缓存 
    if(file_exists($file)){
        $handle=fopen($file,'r');// 存在 读取内容 
        $aqy[$i]=unserialize(fread($handle,filesize($file)));
        // 是否更新本地内容
        $time=time()-filemtime($file);
        if($time>86400){    // 缓存文件太久才会更新  86400 24H
            $aqy[$i] = updata($url,$rlue1[$i],$rlue2);
            if(false!==fopen($file,'w+')){ 
                file_put_contents($file,serialize($aqy[$i]));//写入缓存 
            }
        }
    }
    else{
        $aqy[$i] = updata($url,$rlue1[$i],$rlue2);
        // 并更新本地内容
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($aqy[$i]));//写入缓存 
        }
    }
}

$url ="https://v.qq.com/";
// $name=array('腾讯电影','腾讯电视剧','腾讯综艺','腾讯动漫');
// $rlue1=array('/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">电影排行榜[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/','/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">电视剧频道排行[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/','/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">综艺排行榜[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/','/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">动漫排行榜[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/');
$name1=array('腾讯电影','腾讯电视剧');
$rlue1=array('/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">电影排行榜[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/','/<div class="mod_hd mod_column_hd">\s*?<h2 class="mod_title">电视剧频道排行[\S\s]*?<\/span>\s*?<\/a>\s*?<\/div>\s*?<\/div>\s*?<\/div>\s*?<\/div>/');
$rlue2='/<span class="rank_title">(.*?)<\/span>\s*?<span class="rank_desc">.*?<\/span>\s*?<span class="rank_update">[\s\S]*?<\/span>/';
for($i=0;$i<sizeof($rlue1);$i++){
    $file="./data/txsp".$i.".dp"; 
    //读出缓存 
    if(file_exists($file)){
        $handle = fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
        $txsp[$i] = unserialize(fread($handle,filesize($file)));

        $time=time()-filemtime($file);
        if($time>604800){    // 缓存文件太久才会更新  86400 24H*7 604800
            $txsp[$i] = updata($url,$rlue1[$i],$rlue2);
            if(false!==fopen($file,'w+')){ 
                file_put_contents($file,serialize($txsp[$i]));//写入缓存 
            }
        }
    }
    else{
        $txsp[$i] = updata($url,$rlue1[$i],$rlue2);
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($txsp[$i]));//写入缓存 
        }
    }
}

function drawlist($title){
    for($i=0;$i<sizeof($title)&$i<10;$i++){
        print_r('<li><a href="./dx.php?wd='.$title[$i].'" onmousemove="red(this)" onmouseout="black(this)" target="_blank" style="color: black;text-decoration:none;">'.($i+1).'.'.$title[$i].'</a></li>');
    }
}

// 电影排行榜  爱奇艺、腾讯
print_r("<div id='movie'><ul id='movie-info' style='width:20px;'>电影热播排行榜</ul><ul id='movie-info'><li style='color: cornflowerblue;'>爱奇艺</li>");
drawlist($aqy[0]);
echo "</ul><ul id='movie-info'><li style='color: cornflowerblue;'>腾讯视频</li>";
drawlist($txsp[0]);
echo "</ul></div>";

// 电视剧排行  爱奇艺、腾讯
print_r("<div id='movie'><ul id='movie-info' style='width:20px;'>电视剧热播排行榜</ul><ul id='movie-info'><li style='color: cornflowerblue;'>爱奇艺</li>");
drawlist($aqy[1]);
echo "</ul><ul id='movie-info'><li style='color: cornflowerblue;'>腾讯视频</li>";
drawlist($txsp[1]);
echo "</ul></div>";

$url = "https://www.bilibili.com/v/popular/rank/guochan";
$rlue1 = '/<a href="\/\/www.bilibili.com\/bangumi\/play\/.*?" target="_blank" class="title">(.*?)<\/a>/';
$file="./data/everyday.dp"; 
//读出缓存 
if(file_exists($file)){
    $handle = fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
    $title = unserialize(fread($handle,filesize($file)));

    $time=time()-filemtime($file);
    if($time>604800){    // 缓存文件太久才会更新  86400 24H*7 604800
        $html = file_get_contents($url);
        preg_match_all($rlue1,$html,$py1);
        $title = $py1[1];
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($title));//写入缓存 
        }
    }
}
else{
    $html = file_get_contents($url);
    preg_match_all($rlue1,$html,$py1);
    $title = $py1[1];
    if(false!==fopen($file,'w+')){ 
        file_put_contents($file,serialize($title));//写入缓存 
    }
}

// 待添加 精彩推荐
print_r("<div id='movie'><ul id='movie-info'style='width:20px;'>其他</ul><ul id='movie-info'><li style='color: cornflowerblue;'>哔哩哔哩动漫推荐</li>");
for($i=0;$i<5;$i++){
    $title5[$i] =  $title[$i];
}
drawlist($title5);
echo "</ul><ul id='movie-info'><li style='color: cornflowerblue;'>官方链接</li>";

// 爱奇艺、腾讯视频链接
print_r('
<li><a href="https://www.bilibili.com/" onmousemove="red(this)" onmouseout="black(this)" target="_blank" style="color: black;text-decoration:none;">哔哩哔哩</a></li>
<li><a href="https://www.iqiyi.com/" onmousemove="red(this)" onmouseout="black(this)" target="_blank" style="color: black;text-decoration:none;">爱奇艺</a></li>
<li><a href="https://v.qq.com/" onmousemove="red(this)" onmouseout="black(this)" target="_blank" style="color: black;text-decoration:none;">腾讯视频</a></li>
<li><a href="https://www.youku.com/" onmousemove="red(this)" onmouseout="black(this)" target="_blank" style="color: black;text-decoration:none;">优酷</a></li>
<li><a href="http://www.pptv.com/" onmousemove="red(this)" onmouseout="black(this)" target="_blank" style="color: black;text-decoration:none;">PPTV</a></li>
</ul></div>');


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