<?php
//  表单提交后...
$posts = $_POST;
//  清除一些空白符号
foreach ($posts as $key => $value) {
    $posts[$key] = trim($value);
}
$password = md5($posts["password"]);  // 默认密码123456  e10adc3949ba59abbe56e057f20f883e
$username = $posts["username"];  // 默认用户名  admin

if ($username=="admin"&$password=="e10adc3949ba59abbe56e057f20f883e") {
    //  当验证通过后，启动 Session
    session_start();
    //  注册登陆成功的 admin 变量，并赋值 true
    $_SESSION["admin"] = true;
    header("Location: .");
    exit();
} else {
    echo "<script>alert('用户名密码错误');</script>";
    header("Location: .");
    exit();
}
?>