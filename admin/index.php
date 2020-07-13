<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>用户搜索记录</title>
    </head>
    <body>
        <div><a href="..">回到首页</a></div>
        <div>
<?php
$file="../data/.log";
if(file_exists($file)){
    $handle=fopen($file,'r');
    $log=fread($handle,filesize($file));
    print_r($log);
}
?>
        </div>
    </body>
</html>