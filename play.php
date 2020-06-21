<?php
if(array_key_exists("url", $_POST)){
    $url=$_POST['url'];
}else{
    header("Location: ..");
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
        <title>DPlayer视频播放页</title>
        <link rel="stylesheet" href="./DPlayer.min.css"> 
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
        <a href="..">回到首页</a>
        <div id="dplayer"></div>
        <script type="text/javascript" >
        const dp = new DPlayer({
            container: document.getElementById('dplayer'),
            autoplay: true,
            screenshot: true,
            video: {
                url: '<?php
                echo $url;
                ?>',
                type: 'hls',
                }
        });
        </script>
    </body>

</html>