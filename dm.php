<?php
include './config.php';
if($fdm){
    set_time_limit(0);  
    ob_end_clean();  
    ob_implicit_flush();
}

if(array_key_exists("wd", $_POST)|array_key_exists("wd", $_GET)){
    if(isset($_POST["wd"])){$name = $_POST["wd"];}else{$name = $_GET["wd"];}
    preg_match_all('/https?:\/\/.*/',$name,$jx);  // 判断输入的是url
    if(isset($jx[0][0])){
        header("Location: ./dm.php?url=".$name);
        exit();
    }
    $js = 0;
}else{
    if(array_key_exists("url", $_POST)|array_key_exists("url", $_GET)){
        if(isset($_POST["url"])){$jx = $_POST["url"];}else{$jx = $_GET["url"];}
        preg_match_all('/https?:\/\/.*/',$jx,$jx1);  // 确保输入的是正确的url
        if(!isset($jx1[0][0])){
            $jx = "https://".$jx;
        }
        $url = array("/iqiyi.com\/.+/","/v.qq.com\/.+/","/v.youku.com\/.+/","/v.pptv.com\/.+/","/mgtv.com\/.+/");
        for($i=0;$i<sizeof($url);$i++){
            preg_match_all($url[$i],$jx,$py);
            if(isset($py[0][0])){
                $f = 1;
            }
        }
        if($f!=1){
            // url有误或者暂不支持
            header("Location: ./error.php?error_code=5&url=".$jx);
            exit();
        }
        $html = file_get_contents($jx);
        preg_match_all('/<title>(.*?)<\/title>/',$html,$py1);
        if(isset($py1[1][0])){
            $py1=$py1[1][0];
            $teshu=array("腾讯","爱奇艺","优酷","PP视频","原PPTV聚力视频","芒果TV","高清","全集","完整","卫视版","视频","在线","观看","电影","电视剧","1080P","平台","正版","-","_");
            for($i=0;$i<sizeof($teshu);$i++){
                $py1=str_replace($teshu[$i],"",$py1);
            }
            $name = $py1;
            //几种情况  第1季xxx 第1集 第22期xx
            preg_match_all('/(.*?)第/',$py1,$py2);
            if(isset($py2[1][0])){
                $name = $py2[1][0];
            }
            preg_match_all('/第([0-9]*?)集/',$py1,$py2);
            if(isset($py2[1][0])){
                $js = $py2[1][0]-1;
            }
            else{
                $js = 0;
            }
        }
        else{
            // url有误或者暂不支持
            header("Location: ./error.php?error_code=4&url=".$jx);
            exit();
        }
    }
    else{
        header("Location: ..");
        exit();
    }
}
$teshu=array(array(",","!",":"),array("，","！","："),array("(",")","普通话","粤语","版","[","]","《","》","\"","\'"," "));  // 0替换为1，2删除
for($i=0;$i<sizeof($teshu[0]);$i++){
    $name=str_replace($teshu[0][$i],$teshu[1][$i],$name);
}
for($i=0;$i<sizeof($teshu[2]);$i++){
    $name=str_replace($teshu[2][$i],'',$name);
}
if($name==""){
    // url暂不支持
    header("Location: ./error.php?error_code=5&url=".$jx);
    exit();
}
if(array_key_exists("gx", $_POST)|array_key_exists("gx", $_GET)){if(isset($_POST["gx"])){$gx = $_POST["gx"];}else{$gx = $_GET["gx"];}}
// 存放搜索记录到 cookie
$search = serialize(array($name,time()));
$expire=time()+60*60*24*30;
setcookie("search1", $search, $expire);    // 存放搜索数据


?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>视频站聚和列表</title>
        <link rel="shortcut icon" href="./src/favicon.ico" type="image/x-icon">
        <link rel="bookmark" href="./src/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="./src/css/d.css" type="text/css" />
</head>
<body>
<div id="head">
    <div id="head1">
        <a href="..">首页</a>
    </div>
    <div id="head2">
        <form action="./dm.php" method='POST' onsubmit="return checkform();">
            <p>视 频 站 搜 索：<input id="ipt" type="text" name="wd" autofocus value="<?php echo $name;?>">
            <input type="hidden" name="gx" value="1">
            <input type="submit" value="搜索">
        </p>
        </form>
            <p>如果没有搜索结果，请减少关键词</p>
    </div>
</div>
<script type="text/javascript" >
    function checkform(x){
        if(x.value.length==0){
            alert('输入不能为空！！！');
            x.focus();
            return false;
        }
        else{return true}
    }
</script>

    <div class="container">
        <div class="row">
            <div class="stui-pannel clearfix">
<?php

$n = 0;
// http://www.zzzfun.com/vod-search.html?wd=%E6%B5%B7%E8%B4%BC%E7%8E%8B
// <a href="/vod-detail-id-18.html">海贼王</a>
function get_html($dm1,$dm2,$dm3,$rule,$rule3,$f){
    global $array,$n,$name;
    $url=str_replace("xxx",$name,$dm2);
    // print_r($dm1.$url);
    $html = file_get_contents($dm1.$url);
    // print_r($rule);
    preg_match_all($rule,$html,$cms);
    for($i=0;$i<sizeof($cms[0]);$i++){
        $array[$n]["url"][$i]=$dm1.$cms[1][$i];
        $array[$n]["title"][$i]=$cms[2][$i];
    }
    preg_match_all($rule3,$html,$state);
    for($i=0;$i<sizeof($state[1]);$i++){
        $array[$n]["state"][$i]=$state[1][$i];
    }
    $array[$n]['name']=$dm3;
    // print_r($array);
    buildlb(true);
    $n++;
}

// post 发送
function send_post($url, $post_data) {
    $postdata = http_build_query($post_data);
    $options = array(
      'http' => array(
        'method' => 'POST',
        'header' => 'Content-type:application/x-www-form-urlencoded',
        'content' => $postdata,
        'timeout' => 15 * 60 // 超时时间（单位:s）
      )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    return $result;
}



function post_html($search1,$search2,$search3,$rule2,$rule4,$f){
    global $array,$n,$name;
    $post_data = array(
        "wd" => $name,
    );
    $html=send_post($search1.$search2, $post_data);
    preg_match_all($rule2,$html,$cms);
    for($i=0;$i<sizeof($cms[0]);$i++){
        $array[$n]["url"][$i]=$search1.$cms[1][$i];
        $array[$n]["title"][$i]=$cms[2][$i];
    }
    preg_match_all($rule4,$html,$state);
    for($i=0;$i<sizeof($state[1]);$i++){
        $array[$n]["state"][$i]=$state[1][$i];
    }
    $array[$n]['name']=$search3;
    buildlb(true);
    $n++;
}

// search.asp  方式
function search($search1,$search2,$rule2,$rule4,$f){
    global $array,$n,$name;
    $post_data = array(
        "searchword" => iconv("utf-8", "gbk", $name),
    );
    $html=send_post($search1.'/search.asp', $post_data);
    $html = iconv("gbk", "utf-8", $html);
    preg_match_all($rule2,$html,$cms);
    for($i=0;$i<sizeof($cms[0]);$i++){
        $array[$n]["url"][$i]=$search1.$cms[1][$i];
        $array[$n]["title"][$i]=$cms[2][$i];
    }
    preg_match_all($rule4,$html,$state);
    for($i=0;$i<sizeof($state[1]);$i++){
        $array[$n]["state"][$i]=$state[1][$i];
    }
    $array[$n]['name']=$search2;
    // print_r($array);
    buildlb(true);
    $n++;
}



function buildlb($f){
    global $array,$n,$file;
    if(isset($array[$n]['url'])){
        for($i=0;$i<sizeof($array[$n]['url']);$i++){
        print_r('<div id="li"><a href="'.$array[$n]["url"][$i].'" target="_blank">['.$array[$n]['name'].']'.$array[$n]["title"][$i].'</a>');  // 封面
        if(isset($array[$n]["state"][$i])){
            print_r("<font color=\"red\">".$array[$n]["state"][$i]."</font>");
        }
        print_r('</div id="li">');
        }
        if($f){
            if(false!==fopen($file,'w+')){ 
                file_put_contents($file,serialize($array));//写入缓存 
            }
        }
    }
}

function getarray($f){
    include './config.php';

    // get 方式
    if($fget_html){
        for($i=0;$i<sizeof($dm1);$i++){
            get_html($dm1[$i],$dm2[$i],$dm3[$i],$rule1[$i],$rule2[$i],$f);
        }
    }

    // post  方式
    if($fpost_html){
        for($i=0;$i<sizeof($post1);$i++){
            post_html($post1[$i],$post2[$i],$post3[$i],$rule3[$i],$rule4[$i],$f);
        }
    }

    // search.asp  方式
    if($fsearch){
        for($i=0;$i<sizeof($search1);$i++){
            search($search1[$i],$search2[$i],$rule5[$i],$rule6[$i],$f);
        }
    }

}


$file="./data/".$name.".dp";
// preg_match_all("/\((.*?)\)/",$_SERVER['HTTP_USER_AGENT'],$llq);
// $user = "ip:".@$_SERVER['REMOTE_ADDR'].@$_SERVER['HTTP_CF_CONNECTING_IP']."\$用户:".@$_SERVER['USERDOMAIN'].@$_SERVER['USERNAME'].@$_SERVER['HTTP_CF_RAY'].$llq[1][0]."\$时间:".date("Y-m-d H:i:s",time())."\$视频:".$name;  //用户识别码
// // echo $user;
// $log = fopen("./data/.log","a");
// fwrite($log,$user);
// fclose($log);
//读出缓存 
if(file_exists($file)){
    date_default_timezone_set("Asia/Shanghai");
    $handle=fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
    $array=unserialize(fread($handle,filesize($file)));
    // print_r($array);
    $time=time()-filemtime($file);
    if($time>86400|$gx==1){    // 缓存文件太久才会更新  86400 24H
        $n=0;
        getarray(false);  // 获取数据（不建立网页）
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($array));//写入缓存 
        }
    }
    for($n=0;$n<sizeof($array);$n++){
        buildlb(false);  // 建立网页
    }
    echo "<br><p>更新时间：".date("Y-m-d H:i:s",filemtime($file))."</p>";
    
}
else{//不存在 第一次  边API 边爬取 边建立网页 边存  因为完整太慢 每一组数据存一次   
    if(!isset($_COOKIE['count'])){
        setcookie('count',1,time()+15);
        getarray(true);  // 获取数据（并建立网页）
    } else if ($_COOKIE['count'] < 2){
        setcookie('count',$_COOKIE['count']+1,time()+15);
        getarray(true);  // 获取数据（并建立网页）
    }else{echo '新提交太频繁，15秒内只能提交两次，请等待15秒后在试。';}  // 防止恶意 浪费服务器资源
}
?>
        <div>
    </div>
</div>
</body>
</html>