<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>在线视频站</title>
        <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
        <link rel="bookmark" href="./favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="./css/d.css" type="text/css" />
</head>
<body>
    <form action="./dx.php" method='POST' onsubmit="return checkform();">
    <p>请输入要看的电影 聚合缓存版：<input id="ipt" type="text" name="wd" autofocus value="">
    <input type="submit" value="搜索"></p>
    </form>
<?php
date_default_timezone_set("Asia/Shanghai");
if(isset($_COOKIE['search'])){
    include_once "./cookie.php";
    $searchs = unserialize(passport_decrypt($_COOKIE['search'],$key));
    print_r("<div><form action='./dx.php' method='POST'><p>继续上一次搜素 ".date('m.d-H:i',$searchs[1])."<input id='ipt'  type='hidden' type='text' name='wd' value=".$searchs[0]."><input onmousemove='red(this)' onmouseout='black(this)' style='border:none;color=\"black\";background-color:rgb(230, 230, 230);' type='submit' value=".$searchs[0]."></p></form></div>");
}
?>
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
    <p>第一次较慢，缓存后秒开。</p>
    <p>如果没有搜到，请减少关键词。 </p>
    

<?php
$url=array("https://list.iqiyi.com/www/1/-------------11-1-1-iqiyi--.html","https://list.iqiyi.com/www/2/-------------11-1-1-iqiyi--.html");
$name=array("热播电影","热播电视剧");
for($i=0;$i<sizeof($url);$i++){
    $file="./data/index".$i.".p"; 
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
    print_r("<ul>".$name[$i]);
    for($j=0;$j<sizeof($title[1]);$j++){
        print_r("<div><form action='./dx.php' method='POST'>
        <input id='ipt'  type='hidden' type='text' name='wd' value=".$title[1][$j].">
        <input id='button' onmousemove='red(this)' onmouseout='black(this)' style='color=\"black\";background-color:rgb(255, 255, 255);' type='submit' value=".($j+1).".".$title[1][$j]."></form></div>");
    }
    echo "</ul>";
}
echo '<script>
function red(x){
x.style.color="red";
}

function black(x){
x.style.color="black";
}
</script>';
?>

<p>暂时只支持输入视频名称 url功能待添加</p>
<p>作者 <a href="https://zan7l.tk/" target="_blank">unkaer</a></p>
<p>源码 <a href="https://github.com/unkaer/olvideo" target="_blank">olvideo</a></p>

</body>
</html>