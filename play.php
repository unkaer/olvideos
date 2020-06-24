<?php
if(array_key_exists("urls", $_POST)){
    $urls=json_decode($_POST['urls']);
    $url=$urls[1][0];
}else{
    header("Location: ..");
    exit();
}
?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
        <title id="title"><?php
        if(array_key_exists("name", $_POST)){
            echo $urls[0][0].$_POST['name'];
        }else{
            echo "DPlayer视频播放页";
        }
?></title>
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
        <a href="..">回到首页</a><?php
                for($i=0;$i<sizeof($urls[0]);$i++){
                    echo "<button type=\"button\" onclick=\"
                    dp.switchVideo({
                        url: '".$urls[1][$i]."',
                        type: 'hls'
                        });dp.play();document.getElementById('title').innerHTML ='".$urls[0][$i].$_POST['name']."';\">".$urls[0][$i]."
                        </button>";
                }
                    ?>
        <div id="dplayer"></div>
        <script id="play" type="text/javascript" >
        const dp = new DPlayer({
            container: document.getElementById('dplayer'),
            autoplay: true,
            screenshot: true,
            video: {
                url: <?php
                echo "\"".$url."\",";
                ?>
                type: 'hls',
            }
            
        });

        //   //视频就绪回调,用来监控播放开始 
        //   function loadedmetadataHandler() {
        //       if ( seektime===1 && !live && headtime > 0 && player.video.currentTime < headtime) {
        //               player.seek(headtime);
        //               player.notice("继续上次播放");

        //       } else {
        //              player.notice("视频已就绪");
          
        //       }
        //           player.on("timeupdate", function () {
        //               timeupdateHandler();
        //           });
         
        //   }
        //   //播放进度回调  	
        //   function timeupdateHandler() {
        //      setCookie("time_"+ videoUrl,player.video.currentTime,24);
        //  }

        //   //播放结束回调		
        //   function endedHandler() {
        //       setCookie("time_"+ videoUrl,"",-1);
        //       if (xyplay.playlist_array.length > Number(xyplay.part)) {
        //           player.notice("视频已结束,为您跳到下一集");
        //           setTimeout(function () {
        //               video_next();
        //           }, 500);
        //       } else {
        //           player.notice("视频播放已结束");
        //       }
        //   }
        //   //播放下集
        //   function video_next() {
        //       if ("undefined" !== typeof xyplay && "undefined" !== typeof xyplay.playlist_array)
        //           if (Number(xyplay.part) + 1 >= xyplay.playlist_array.length) {
        //               return false;
        //           }
        //       xyplay.part++;
        //       myplay(xyplay.playlist_array[xyplay.part]);
        //   }
        //   //播放上集	
        //   function video_front() {
        //       if ("undefined" !== typeof xyplay && "undefined" !== typeof xyplay.playlist_array)
        //           if (Number(xyplay.part) <= 0) {
        //               return false;
        //           }
        //       xyplay.part--;
        //       myplay(xyplay.playlist_array[xyplay.part]);

        //   }
        //   //调用播放
        //   function myplay(url) { 
        //       videoUrl=url; headtime= Number(getCookie("time_"+ videoUrl));
        //       player.switchVideo({url: url});
        //       player.play();
        //       if ("undefined" !== typeof xyplay) {
        //           if (xyplay.title && !live) {
        //               parent.document.title = "正在播放:【" + xyplay.title + "】part " + (Number(xyplay.part) + 1) + "-- " + xyplay.mytitle;
        //           }

        //       }

        //   } 
        </script>
    </body>

</html>