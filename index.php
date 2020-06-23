<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <link rel="stylesheet" href="./css/d.css" type="text/css" />
</head>
<body>
    <form action="./pc.php" method='POST'>
    <p>请输入要看的电影或者url 爬虫方式：<input type="text" name="name" value=""></p>
    <p><input type="submit" value="提交"></p>
</form>
    <form action="./ap.php" method='POST'>
    <p>请输入要看的电影或者url API方式：<input type="text" name="name" value=""></p>
    <p><input type="submit" value="提交"></p>
</form>

<p>暂时只支持输入视频名称 url功能待添加</p>
<p>作者 <a href="https://zan7l.tk/">unkaer</a></p>
<p>源码 <a href="https://github.com/unkaer/olvideo">olvideo</a></p>

</body>
</html>