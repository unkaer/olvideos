<?php
if(array_key_exists("url", $_POST)|array_key_exists("url", $_GET)){
    if(isset($_POST["url"])){$url = $_POST["url"];}else{$url = $_GET["url"];}
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
        <title id="title">网络测试</title>
        <link rel="stylesheet" href="../src/css/d.css" type="text/css" />
        <link rel="stylesheet" href="../src/dplayer/DPlayer.min.css"> 
		<script type="text/javascript"  src="../src/dplayer/hls.min.js" ></script>
        <script type="text/javascript" src="../src/dplayer/DPlayer.min.js" ></script> 
        <style type="text/css">
            html,body{
                background-color:rgba(28,28,28,.8);
                padding: 10px;
                margin: 0px 100px 5px;
                color:#999;
                border-radius: 8px;
            }
        </style>
    </head>
    
    <body>

    
<form action="./play.php" method='POST'>
    <p>url m3u8播放：<input type="text" name="url" value="">
    <input type="submit" value="搜索"></p>
</form>
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
            // contextmenu: [
            //     {
            //         text: '作者博客',
            //         link: 'https://zan7l.tk/',
            //     },
            //     {
            //         text: '本站源码',
            //         link: 'https://github.com/unkaer/olvideo/',
            //     },
            // ],   
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