<?php
if(array_key_exists("urls", $_POST)){
    $urls=json_decode($_POST['urls']);
    if(array_key_exists("jishu", $_POST)){
        $url = $urls[1][$_POST['jishu']];
        $jishu = $_POST['jishu'];
    }
    else{
        $url=$urls[1][0];
        $jishu = 0;
    }
}else{
    header("Location: ..");
    exit();
}
// 存放播放的数据位置到 cookie dt
include_once "./cookie.php";
$dt = json_decode($_POST['dt']);
$dt = passport_encrypt(serialize($dt),$key);  // 加密
$expire=time()+60*60*24*30;
setcookie("dt", $dt, $expire);
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
        <title id="title"><?php
        if(array_key_exists("name", $_POST)){
            echo $urls[0][$jishu].$_POST['name'];
        }else{
            echo "DPlayer视频播放页";
        }
?></title>
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
                            });dp.play();document.getElementById('title').innerHTML ='".$urls[0][$i].$_POST['name']."';document.getElementById('jishu').innerHTML ='".$i."';jsc();
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
        <p id="jishu" style="display: none;"><?php echo $jishu;?></p></div>
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
        </script>
    </body>
    
</html>