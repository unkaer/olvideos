<?php
set_time_limit(0);
ob_implicit_flush();
if(array_key_exists("wd", $_POST)){
    $name=trim($_POST['wd']);
    // $teshu=array(array(",","!",":"),array("，","！","："),array("(",")","普通话","粤语"));
    // for($i=0;$i<sizeof($teshu[0]);$i++){
    //     $name=str_replace($teshu[0][$i],$teshu[1][$i],$name);
    // }
    // for($i=0;$i<sizeof($teshu[2]);$i++){
    //     $name=str_replace($teshu[2][$i],'',$name);
    // }
}else{
    header("Location: ..");
    exit();
}
// // 存放搜索记录到 cookie
// include_once "./cookie.php";
// $search = serialize(array($name,time()));
// $search = passport_encrypt($search,$key);
// $expire=time()+60*60*24*30;
// setcookie("search", $search, $expire);    // 加密存放搜索数据


// $searchs = unserialize(passport_decrypt($_COOKIE['search'],$key)); // 读取 [0] 搜索记录  [1] 搜索时间

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title>APCI视频列表</title>
        <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
        <link rel="bookmark" href="./favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="./css/d.css" type="text/css" />
</head>
<body>
    <div class="header__top">
        <div class="container">
                <div class="header__logo">
                    <a href=".."><img src="./favicon.ico" height="60"></a>
                </div>
                <div class="header__search">
                    <form action="https://www.msdm.moe/index.php/vod/search/page/1/wd/">
                        <input type="text" id="wd" name="wd" class="mac_wd form-control" value="" placeholder="请输入关键词...">
                        <button class="submit" id="searchbutton" type="submit" name="submit"></button>
                    </form>
                </div>
        </div>
    </div>
<!-- <form action="./dx.php" method='POST' onsubmit="return checkform();">
    <p>请输入要看的电影 聚合缓存版：<input id="ipt" type="text" name="wd" autofocus value="<?php echo $name;?>">
    <input type="submit" value="搜索"></form>
    <p>如果没有搜到，请适当减少关键词后再试。</p></div>
    <script type="text/javascript" >
    function checkform(){
        if(document.getElementById('ipt').value.length==0){
            alert('输入不能为空！！！');
            document.getElementById('ipt').focus();
            return false;
        }
        else{return true}
    }
    </script> -->

    <div class="container">
        <div class="row">
            <div class="stui-pannel clearfix">
<?php
$dm1=array('http://www.nicotv.me','http://www.zzzfun.com','https://www.agefans.tv');
$dm2=array('/video/search/xxx.html','/vod-search.html?wd=xxx','/search?query=xxx&page=1');
$dm3=array('妮可动漫','zzzfun动漫视频网','AGE动漫');
$rule1=array("/<a href=\"(.*?)\" title=\"(.*?)\">/","/<a href=\"(\/vod-detail.*?)\">(.*?)<\/a>/","/<a href=\"(.*?)\" class=\"cell_imform_name\">(.*?)<\/a>/");
$rule3=array('/<span class="continu">([\S\s]*?)<\/span>/','/<span class="color">([\S\s]*?)<\/span>/','/<span class="newname">(.*?)<\/span>/');
$search1=array('http://www.dm530.net','http://www.tldm.net','http://www.imomoe.in');
$search2=array('风车动漫','天乐动漫','樱花动漫');
$rule2=array("/<a href=\"([^>]*?)\" target=\"_blank\" title=\"(.*?)\">/","/<a href=\"(.*?)\" target=\"_blank\"><img src=\".*?\" alt=\"(.*?)\">/","/<a href=\"([^>]*?)\" target=\"_blank\" title=\"(.*?)\">/");
$rule4=array('/<span>别名：.*?<\/span><span><font color="red">(.*?)<\/font>　类型：/','/<abbr>(.*?)<\/abbr>/','/<span>别名：.*?<\/span><span>(.*?)　类型：/');

$n = 0;
// http://www.zzzfun.com/vod-search.html?wd=%E6%B5%B7%E8%B4%BC%E7%8E%8B
// <a href="/vod-detail-id-18.html">海贼王</a>
function pc($dm1,$dm2,$dm3,$rule,$rule3,$f){
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

// search.asp  方式
function search($search1,$search2,$rule2,$rule4,$f){
    global $array,$n,$name;
    $post_data = array(
        "searchword" => iconv("utf-8", "gbk", $name),
    );
    $html=send_post($search1."/search.asp", $post_data);
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
    global $dm1,$dm2,$dm3,$rule1,$rule3,$search1,$search2,$search3,$rule2,$rule4;
    for($i=0;$i<sizeof($dm1);$i++){    // get 方式
        pc($dm1[$i],$dm2[$i],$dm3[$i],$rule1[$i],$rule3[$i],$f);
    }
    for($i=0;$i<sizeof($search1);$i++){    // search.asp  方式
        search($search1[$i],$search2[$i],$rule2[$i],$rule4[$i],$f);
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
    $handle=fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
    $array=unserialize(fread($handle,filesize($file)));
    for($n=0;$n<sizeof($array);$n++){
        buildlb(false);  // 建立网页
    }
    // print_r($array);
    date_default_timezone_set("Asia/Shanghai");
    $time=time()-filemtime($file);
    echo "<br><p>更新时间：".date("Y-m-d H:i:s",filemtime($file))."</p>";
    if($time>86400){    // 缓存文件太久才会更新  86400 24H
        $n=0;
        getarray(false);  // 获取数据（不建立网页）
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($array));//写入缓存 
        }
    }
    
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