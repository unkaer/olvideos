<?php
if(array_key_exists("wd", $_POST)|array_key_exists("wd", $_GET)){
    if(isset($_POST["wd"])){$wd = $_POST["wd"];}else{$wd = $_GET["wd"];}
    $file="./data/".$wd.".p";
    if(array_key_exists("id", $_POST)|array_key_exists("id", $_GET)){
        if(isset($_POST["id"])){$n = $_POST["id"];}else{$n = $_GET["id"];}
        if(file_exists($file)){
            $handle=fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
            $array=unserialize(fread($handle,filesize($file)));
            if(isset($array[$n]["tag"])){
                for($j=0;$j<sizeof($array[$n]["tag"]);$j++){
                    $urls[0][$j]=$array[$n]["tag"][$j];  // 集数 or 画质
                    $urls[1][$j]=$array[$n]["url"][$j];  // 播放地址
                }
                $name = $array[$n]['title'];
            }else{
                header("Location: ./error.php?error_code=2&wd=".$wd."&id=".$n);
                exit();
            }
        }else{
            header("Location: ./error.php?error_code=1&wd=".$wd);
            exit();
        }
        if(array_key_exists("js", $_POST)|array_key_exists("js", $_GET)){
            if(isset($_POST["js"])){$js = $_POST["js"];}else{$js = $_GET["js"];}
            if(isset($urls[1][$js])){
                $url=$urls[1][$js];
            }else{
                header("Location: ./error.php?error_code=3&wd=".$wd."&id=".$n."&js=".$js);
                exit();
            }
        }
        else{$url=$urls[1][0];$js=0;}
    }
}
else{
    header("Location: ..");
    exit();
}
// 存放播放的数据位置到 cookie dt
$dt = serialize(array($wd,$n));
$expire=time()+60*60*24*30;
setcookie("dt", $dt, $expire);

?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
        <title id="title"><?php
        echo $name.$urls[0][$js];
        ?>
        </title>
        <link rel="stylesheet" href="./css/d.css" type="text/css" />
        <link rel="stylesheet" href="./dplayer/DPlayer.min.css"> 
		<script type="text/javascript"  src="./dplayer/hls.min.js" ></script>
        <script type="text/javascript" src="./dplayer/DPlayer.min.js" ></script> 
        <style type="text/css">
            html,body{
                background-color:#000;
                padding: 10px;
                margin: 0px 100px 5px;
                color:#999;
                overflow:hidden;
            }
        </style>
    </head>
    
    <body>
        <a href="..">回到首页</a><div id="menu"><?php
                for($i=0;$i<sizeof($urls[0]);$i++){
                    echo "<button type=\"button\" onclick=\"player(".$i.")\">".$urls[0][$i]."</button>";
                }
                echo "<script type=\"text/javascript\" >
                function player(n) {";
                for($i=0;$i<sizeof($urls[0]);$i++){
                    echo "
                        if(n==".$i."){
                        dp.switchVideo({
                            url: '".$urls[1][$i]."',
                            type: 'hls'
                        });
                        dp.play();document.getElementById('title').innerHTML ='".$name.$urls[0][$i]."';
                        document.getElementById('jishu').innerHTML ='".$i."';
                        url =changeURLPar(document.URL,'wd','".$wd."');
                        url =changeURLPar(url,'id','".$n."');
                        url =changeURLPar(url,'js',document.getElementById('jishu').innerHTML);
                        var newUrl =url.replace(new RegExp('&amp;','g'),'&');
                        history.pushState(null,null,newUrl)
                        jsc();
                        }";
                }
                echo "}</script><br><button type=\"button\" onclick=\"video_front()\">上一集</button>"; 
                echo "<button type=\"button\" onclick=\"video_next()\">下一集</button>";
                echo "<script type=\"text/javascript\" >
                function video_front() {
                        var i = Number(document.getElementById('jishu').innerHTML)-1;
                        player(i);
                }
                function video_next() {
                    var i = Number(document.getElementById('jishu').innerHTML)+1;
                    player(i);
                }</script>";
                    ?>
        <p id="jishu" style="display: none;"><?php echo $js;?></p></div>
        <div id="dplayer"></div>
        <script type="text/javascript" >
        const dp = new DPlayer({
            container: document.getElementById('dplayer'),
            hotkey: true,
            autoplay: true,
            screenshot: true,
            video: {
                url: <?php
                echo "\"".$url."\",";
                ?>
                type: 'hls',
            },
            contextmenu: [
                {
                    text: '作者博客',
                    link: 'https://zan7l.tk/',
                },
                {
                    text: '本站源码',
                    link: 'https://github.com/unkaer/olvideo/',
                },
            ],   
        });
        dp.on('ended', function () {
            video_next();
        });
        function jsc() {
            var d = new Date();
            var jishu = document.getElementById('jishu').innerHTML
            d.setTime(d.getTime() + (30*24*60*60*1000));
            document.cookie = "jishu =" + jishu + ";expires="+ d.toUTCString() + ";path=/";
        }
        jsc();
        function changeURLPar(destiny, par, par_value){
            var pattern = par+'=([^&]*)';
            var replaceText = par+'='+par_value;
            if (destiny.match(pattern)){
                var tmp = '/\\'+par+'=[^&]*/';
                tmp = destiny.replace(eval(tmp), replaceText);
                return (tmp);
            }
            else{
                if (destiny.match('[\?]')){
                    return destiny+'&'+ replaceText;
                }
                else{
                    return destiny+'?'+replaceText;
                }
            }
            return destiny+'\n'+par+'\n'+par_value;
        }
        </script>
    </body>
    
</html>