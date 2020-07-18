<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>后台管理</title>
    </head>
    <body>
        <div><a href="..">回到首页</a></div>
        <div>
<?php
//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();
//  判断是否登陆
if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
    echo "<p>您已经成功登陆</p>";

    // 更新系统
    echo '<div><a href="./down.php?id=1">更新系统</a></div>';

    // <h3>用户访问记录：</h3>
    echo "<h3>用户访问记录：</h3>";
    $file="../data/.log";
    if(file_exists($file)){
        $handle=fopen($file,'r');
        $log=fread($handle,filesize($file));
        print_r($log);
    }
} else {
    //  验证失败，将 $_SESSION["admin"] 置为 false
    $_SESSION["admin"] = false;
    echo("您无权访问，请登录");
    print_r('
    <form action="./login.php" method="POST">
    <p>用户：<input id="ipt" type="text" name="username" autofocus value="">
    <p>密码：<input id="ipt" type="text" name="password" autofocus value="">
        <input type="submit" value="登录"></p>
    </form>');
}
?>
        </div>
    </body>
</html>