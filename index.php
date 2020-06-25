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
    <p>请输入要看的电影 聚合缓存版：<input id="ipt" type="text" name="wd" value="">
    <input type="submit" value="搜索"></p>
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
    <p>第一次较慢，缓存后秒开。</p><br>
</form>

<p>暂时只支持输入视频名称 url功能待添加</p>
<p>作者 <a href="https://zan7l.tk/">unkaer</a></p>
<p>源码 <a href="https://github.com/unkaer/olvideo">olvideo</a></p>

</body>
</html>