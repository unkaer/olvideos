<?php
include './config.php';
include './src/function.php';

if(array_key_exists("wd", $_POST)|array_key_exists("wd", $_GET)){
    if(isset($_POST["wd"])){$wd = $_POST["wd"];}else{$wd = $_GET["wd"];}
    if(array_key_exists("f", $_POST)|array_key_exists("f", $_GET)){
        $file = "./data/".$wd.".dp";
    }else{
        $file = "./data/".$wd.".p";
    }
    if(array_key_exists("id", $_POST)|array_key_exists("id", $_GET)){
        if(isset($_POST["id"])){$n = $_POST["id"];}else{$n = $_GET["id"];}
        if(file_exists($file)){
            $handle=fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
            $array=unserialize(fread($handle,filesize($file)));
            if(isset($array[$n]["tag"])){
                for($j=0;$j<sizeof($array[$n]["tag"]);$j++){
                    $urls[0][$j]=$array[$n]["tag"][$j];  // 集数 or 画质
                    $urls[1][$j]=$array[$n]["url"][$j];  // 播放地址
                    $urls[2]=$array[$n]["des"];  // 简介
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
    if(array_key_exists("url", $_POST)|array_key_exists("url", $_GET)){
        if(isset($_POST["url"])){$url = $_POST["url"];}else{$url = $_GET["url"];}
        $name = "测试视频" ;
        $urls[0][0] = "测试集数" ;  // 集数 or 画质
        $urls[2] = "测试视频简介" ;  // 简介
        $js = 0 ;
    }
    else{
        header("Location: ..");
        exit();
    }
}
// 存放播放的数据位置到 cookie dt
$dt = serialize(array($wd,$n));
$expire=time()+60*60*24*30;
setcookie("dt", $dt, $expire);

// http 播放地址加密为 https    由于cloudflare不是https访问的服务器，所以无效 ，改为js方式
// if($_SERVER['HTTPS'] == 'on'){
//     // print('是加密连接');
//     $url = str_replace('http','https',$url);
//     for($i=0;$i<sizeof($urls[1]);$i++){
//         $urls[1][$i] = str_replace('http','https',$urls[1][$i]);
//     }
// }

?>
<!DOCTYPE html>
<html lang="zh-cmn-Hans">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
        <title id="title"><?php
        echo $name.$urls[0][$js];
        ?>
        </title>
        <link rel="stylesheet" href="./src/css/d.css" type="text/css" />
        <!-- <link rel="stylesheet" href="./src/dplayer/DPlayer.min.css">  -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dplayer@1.25.0/dist/DPlayer.min.css">
		<!-- <script type="text/javascript"  src="./src/dplayer/hls.min.js" ></script>
        <script type="text/javascript" src="./src/dplayer/DPlayer.min.js" ></script> -->
        <style type="text/css">
            html,body{
                background-color:rgba(28,28,28,.8);
                padding: 10px;
                margin: 0px 100px 5px;
                color:#999;
                border-radius: 8px;
            }
        </style>
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
        <div ><p>
        <form action="./dx.php" method='POST' onsubmit="return checkform(this);">
        <a href="..">回到首页</a><img style="height: 25px;" src="./src/ss.svg"><input id="ipt" type="text" name="wd" style="background-color:#999;" autofocus value="">
        </form></p><p id="jishu" style="display: none;"><?php echo "$js"; ?></p>
        </div>

        </div>
        <div id="dplayer"></div>
        <div id="stats"></div>
        <script src="https://cdn.jsdelivr.net/npm/cdnbye@latest"></script>
        <script src="https://cdn.jsdelivr.net/npm/dplayer@1.25.0"></script>
        <script >
    <?php echo "
    var name = \"".$name."\";
    var urls1 = new Array();
    var urls2 = new Array();";
    for($i=0;$i<sizeof($urls[0]);$i++){echo "urls1[$i] = \"".$urls[0][$i]."\";urls2[$i] = \"".$urls[1][$i]."\";";}  //var urls1 存播放集数 urls2 存播放地址
    echo "
    var ishttps = 'https:' == document.location.protocol ? true: false;
    if(ishttps){
        for (x in urls2){
            urls2[x] = urls2[x].replace(/http/, \"https\");
        };
    };";
    ?>
        </script>
        <script>
        var _peerId = '', _peerNum = 0, _totalP2PDownloaded = 0, _totalP2PUploaded = 0;
        var type = 'normal';
        if(Hls.isSupported() && Hls.WEBRTC_SUPPORT) {
            type = 'customHls';
        }
        const dp = new DPlayer({
            container: document.getElementById('dplayer'),
            hotkey: true,
            autoplay: true,
            screenshot: true,
            video: {
                url: urls2[document.getElementById('jishu').innerHTML],
                type: type,
                customType: {
                    'customHls': function (video, player) {
                        const hls = new Hls({
                            debug: false,
                            // Other hlsjsConfig options provided by hls.js
                            p2pConfig: {
                                live: false,        // 如果是直播设为true
                                // Other p2pConfig options provided by CDNBye
                            }
                        });
                        hls.loadSource(video.src);
                        hls.attachMedia(video);
                        hls.p2pEngine.on('stats', function (stats) {
                            _totalP2PDownloaded = stats.totalP2PDownloaded;
                            _totalP2PUploaded = stats.totalP2PUploaded;
                            updateStats();
                        }).on('peerId', function (peerId) {
                            _peerId = peerId;
                        }).on('peers', function (peers) {
                            _peerNum = peers.length;
                            updateStats();
                        });
                    }
                }
            },
            danmaku: {
            },
            // contextmenu: [
            //     {
            //         text: '作者博客',
            //         link: 'https://zan7l.tk/',
            //     },
            //     {
            //         text: '本站源码',
            //         link: 'https://github.com/unkaer/olvideos/',
            //     },
            // ],   
        });
        function updateStats() {
            var text = 'P2P正在为您加速' + (_totalP2PDownloaded/1024).toFixed(2)
                + 'MB 已分享' + (_totalP2PUploaded/1024).toFixed(2) + 'MB' + ' 连接节点' + _peerNum + '个';
            document.getElementById('stats').innerText = text
        }
        dp.on('loadeddata', function () {
            dp.danmaku.draw({
                text: '视频初始化完成',
                color: '#b7daff',
                type: 'right',
            });
            dp.danmaku.draw({
                text: '请不要相信视频中的水印广告',
                color: '#ee204d',
                type: 'top',
            });
            dp.danmaku.draw({
                text: '！！！！！！！！！！！！！',
                color: '#ee204d',
                type: 'top',
            });
            dp.danmaku.draw({
                text: '请不要相信视频中的水印广告',
                color: '#ee204d',
                type: 'top',
            });
            dp.danmaku.draw({
                text: '！！！！！！！！！！！！！',
                color: '#ee204d',
                type: 'top',
            });
            dp.danmaku.draw({
                text: '请不要相信视频中的水印广告',
                color: '#ee204d',
                type: 'top',
            });
            dp.danmaku.draw({
                text: '！！！！！！！！！！！！！',
                color: '#ee204d',
                type: 'top',
            });
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
        
        <div id="menu">
        <div class="title">《<?php echo $name."》</div>";
            echo '<div class="des">'.$urls[2].'</div>';
                for($i=0;$i<sizeof($urls[0]);$i++){
                    echo "<button type=\"button\" onclick=\"player(".$i.")\">".$urls[0][$i]."</button>";
                }
                echo "<script type=\"text/javascript\" >
    function player(n) {
            dp.switchVideo({
                url: urls2[n],
                type: type,
            })
            document.getElementById('title').innerHTML = name+urls1[n];
            document.getElementById('jishu').innerHTML = n;
            url =changeURLPar(document.URL,'wd','".$wd."');
            url =changeURLPar(url,'id','".$n."');
            url =changeURLPar(url,'js',document.getElementById('jishu').innerHTML);
            var newUrl =url.replace(new RegExp('&amp;','g'),'&');
            history.pushState(null,null,newUrl)
            jsc();
            dp.play();
        };";
                echo "</script><br><button type=\"button\" onclick=\"video_front()\">上一集</button>"; 
                echo "<button type=\"button\" onclick=\"video_next()\">下一集</button>";
                if($array[$n]["download"][0]!="暂无"&$array[$n]["download"][0]!=null){
                    print_r("<p>迅雷p2p下载:<br>");
                    for($j=0;$j<sizeof($array[$n]["tag"]);$j++){
                        print_r($array[$n]["tag"][$j]."$".$array[$n]["download"][$j]."\n</p>");
                    }
                }
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
        </div>
        <?php print_r($footer);?>
    </body>
    
</html>