<a href="..">回到首页</a>
<?php
// 错误页
set_time_limit(0);
ob_implicit_flush();
if($_GET['error_code']=='1'){
    echo '<h2>wd未搜索过</h2><p>请重新搜索 影视名称</p>
    <form action="./dx.php" method="POST" onsubmit="return checkform();">
        <p>本 站 在 线 影 视：<input id="ipt" type="text" name="wd" autofocus value='.$_GET['wd'].'>
        <input type="submit" value="搜索"></p>
    </form>';
}
if($_GET['error_code']=='2'){
    echo '<h2>id不存在</h2><p>请检查 是第几个搜索结果</p>
    <form action="./dx.php" method="POST" onsubmit="return checkform();">
        <p>本 站 在 线 影 视：<input id="ipt" type="text" name="wd" autofocus value='.$_GET['wd'].'>
        <input type="submit" value="搜索"></p>
    </form>';
}
if($_GET['error_code']=='3'){
    echo '<h2>js不存在</h2><p>请检查 是第几集</p>
    <form action="./dx.php" method="POST" onsubmit="return checkform();">
        <p>本 站 在 线 影 视：<input id="ipt" type="text" name="wd" autofocus value='.$_GET['wd'].'>
        <input type="submit" value="搜索"></p>
    </form>';
}
if($_GET['error_code']=='4'){
    echo '<h2>url有误</h2><p>请检查 输入的是否是正确的url</p><p>可能是暂不支持，有问题请反馈</p>
    <form action="./dx.php" method="POST" onsubmit="return checkform();">
        <p>url解析：<input id="ipt" type="text" name="url" autofocus value='.$_GET['url'].'>
        <input type="submit" value="搜索"></p>
    </form>';
}
if($_GET['error_code']=='5'){
    echo '<h2>url 不 支 持</h2><p>你输入的url 暂不支持</p><p>暂时只支持腾讯视频、爱奇艺</p>
    <form action="./dx.php" method="POST" onsubmit="return checkform();">
        <p>url解析：<input id="ipt" type="text" name="url" autofocus value='.$_GET['url'].'>
        <input type="submit" value="搜索"></p>
    </form>';
}
?>