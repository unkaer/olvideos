<?php
//  防止全局变量造成安全隐患
$admin = false;
//  启动会话，这步必不可少
session_start();

print_r('
<a href="..">首页</a>
<a href="../admin">后台</a><br>');

if (isset($_SESSION["admin"]) && $_SESSION["admin"] === true) {
    if ($_FILES["file"]["error"] > 0)
    {
        echo "错误：: " . $_FILES["file"]["error"] . "<br>";
    }
    else
    {
        echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
        echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
        echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
        move_uploaded_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
        echo "上传文件存储在: ./" . $_FILES["file"]["name"];
        $dir1 = "./".$_FILES["file"]["name"];
        $dir2 = "../".$_FILES["file"]["name"];
        $config=fopen($dir1,'r');// 读取设置文件
        echo "<br><textarea rows=\"10\" cols=\"80\">";
        echo fread($config,filesize($dir1));
        echo "</textarea><br>请检查文件是否正确";
        if(file_exists($dir2)){  // 如果文件存在，判读是否相同。不同的才改变
            $md51 = md5_file($dir1);
            $md52 = md5_file($dir2);
            if($md51==$md52){
                echo "<br>已经安装相同文件 or 上传文件未改变!!!";
                echo "<br>已经安装相同文件 or 上传文件未改变!!!";
                echo "<br>已经安装相同文件 or 上传文件未改变!!!";
            }else{
                echo "<br><textarea rows=\"10\" cols=\"80\">";
                $config=fopen($dir2,'r');// 读取设置文件
                echo fread($config,filesize($dir2));
                echo "</textarea><br>以上是已安装的文件";
            }
        }
        print_r('
            <form action="./down.php?id=9" method="POST">
                <input type="hidden" name="dir1" value="'.$dir1.'">
                <input type="hidden" name="dir2" value="'.$dir2.'">
                <input type="submit" value="安装">
            </form>');
    }
}else{
    echo("您无权访问，请登录");
    print_r('
    <form action="./login.php" method="POST">
    <p>用户：<input id="ipt" type="text" name="username" autofocus value="">
    <p>密码：<input id="ipt" type="password" name="password" autofocus value="">
        <input type="submit" value="登录"></p>
    </form>');
}

?>