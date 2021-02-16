<?php
include './config.php';


// 页眉
$head = '
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>'.$SiteName.'</title>
    <link rel="shortcut icon" href="./src/favicon.ico" type="image/x-icon">
    <link rel="bookmark" href="./src/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./src/css/d.css" type="text/css" />
    <script>
        var _hmt = _hmt || [];
        (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?'.$baiduid.'";
        var s = document.getElementsByTagName("script")[0]; 
        s.parentNode.insertBefore(hm, s);
        })();
    </script>

</head>
';

// 顶端导航
$header = '
<div id="header">
    <div id="header-info">
        <ul class="nav___14cq3">
            <li><a href="..">首页</a></li>
            <li><a href="./fl.php?fw=0">路线1</a></li>
            <li><a href="./fl.php?fw=1">路线2</a></li>
            <li><a href="./admin">后台</a></li>
        <form action="./dx.php" method="POST" onsubmit="return checkform(this);">
            本 站 在 线 影 视：<input id="ipt" type="text" name="wd" style="" autofocus value="">
            <button class="submit" type="submit" name="submit">
                <img style="height: 25px;" src="./src/ss.svg">
            </button>
        </form>
        </ul>
    </div>
</div>
';

// 页脚
$footer = '
<div id="footer">
    <div id="footer-info">
        <p>作者：<a href="https://zan7l.tk/" target="_blank">unkaer</a> | 源码：<a href="https://github.com/unkaer/olvideos" target="_blank">olvideos</a> | <a rel="nofollow" target="_blank" href="https://tongji.baidu.com/web/10000045193/overview/index?siteId=14297073"> 网站统计</a></p>
        <p>免责声明:'.$SiteName.'所有视频均来自互联网收集而来，版权归原创者所有，如果侵犯了你的权益，请通知我们，我们会及时删除侵权内容，谢谢合作。</p>
        <p>版权声明:'.$SiteName.'是一个免费看电影非赢利性的网站，本站所有内容均来源于互联网相关站点自动搜索采集信息，相关链接已经注明来源。</p>
        <p>Copyright © 2020-'.date("Y").' · <a href="'.$SiteUrl.'">'.$SiteName.'</a> 版权所有 · 版权反馈邮箱:<a href="mailto:ababwbq@qq.com" target="_blank">ababwbq@qq.com</a></p>
        <p><span><a id="sitetime"></a></span>
        <script src="./src/js/settime.js"></script></p>
    </div>
</div>
';

?>