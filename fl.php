<?php
include './config.php';
ini_set('user_agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.150 Safari/537.36 Edg/88.0.705.68');

// set_time_limit(0);
// ob_implicit_flush();
if(array_key_exists("fw", $_POST)|array_key_exists("fw", $_GET)){
    if(isset($_POST["fw"])){$fw = $_POST["fw"];}else{$fw = $_GET["fw"];}
    if(array_key_exists("cid", $_POST)|array_key_exists("cid", $_GET)){
        if(isset($_POST["cid"])){$cid = $_POST["cid"];}else{$cid = $_GET["cid"];}
    }
    if(array_key_exists("gx", $_POST)|array_key_exists("gx", $_GET)){
        if(isset($_POST["gx"])){$gx = $_POST["gx"];}else{$gx = $_GET["gx"];}
    }
    if(array_key_exists("pg", $_POST)|array_key_exists("pg", $_GET)){
        if(isset($_POST["pg"])){$pg = $_POST["pg"];}else{$pg = $_GET["pg"];}
    }else{$pg=1;}
}
else{
    // 未选择服务器
    header("Location: ./error.php?error_code=6");
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
        <title><?php echo $name;?>·搜索结果</title>
        <link rel="shortcut icon" href="./src/favicon.ico" type="image/x-icon">
        <link rel="bookmark" href="./src/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="./src/css/d.css" type="text/css" />
        <script>
            var _hmt = _hmt || [];
            (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?<?php echo($baiduid); ?>";
            var s = document.getElementsByTagName("script")[0]; 
            s.parentNode.insertBefore(hm, s);
            })();
        </script>
</head>
<body>
<?php include_once("./src/baidu_js_push.php"); ?>
<div id="head">
    <div id="head1">
        <a href="..">首页</a>
        <?php 
        if(isset($cid)){
            echo '<a href="./fl.php?fw='.$fw.'">返回分类页</a>';
        }
        ?>
    </div>
    <div id="head2">
        <form action="./dx.php" method='POST' onsubmit="return checkform();">
            <p>本 站 在 线 影 视：<input id="ipt" type="text" name="wd" autofocus value="">
            <input type="submit" value="搜索"></p>
        </form>
        <form action="./fl.php" method='POST'>
            <input type="hidden" name="fw" autofocus value="<?php echo $fw;?>">
            <input type="hidden" name="cid" autofocus value="<?php echo $cid;?>">
            <input type="hidden" name="pg" autofocus value="<?php echo $pg;?>">
            <input type="hidden" name="gx" value="1">
            <p>如果没有搜索结果，请减少关键词
            <input type="submit" value="更新本页面"></p>
        </form>
    </div>
</div>
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
<div>
<?php

$api=array('http://api.iokzy.com/inc/apickm3u8.php','http://www.zdziyuan.com/inc/api.php');  // API方式 资源站API
$api1=array('ok资源API','最大资源API');  // API方式 资源站API  'ok资源API','最大资源API'


//API 获取视频分类 
function getcid($api,$api1){
    global $name,$file;
    $array_list = array();
    $data = file_get_contents($api);
    $xml = simplexml_load_string($data);
    $key = 0;
    foreach($xml->class->ty as $list){
        $array_list[$key]['list_id'] = (int)$xml->class->ty[$key]['id'];
        $array_list[$key]['list_name'] = (string)$list;
        $key++;
    }
    if(false!==fopen($file,'w+')){ 
        file_put_contents($file,serialize($array_list));//写入缓存 
    }
    return $array_list;
}

//API 获取视频id geturl视频信息
function getlist($api,$t,$f){
    global $array,$pg;
    $data = file_get_contents($api."?ac=list&t=".$t."&pg=".$pg);
    // print_r($api."?ac=list&t=".$t."&pg=".$pg);
    $xml = simplexml_load_string($data);
    foreach($xml->list->attributes() as $a => $b){
        // echo $a."=".$b."<br>";
        $array['list'][$a]=(string)$b;
    }
    if($f){build1();}
    // print_r($array['list']);
    foreach($xml->list->video as $video){
        $id=(string)$video->id;
        geturl($id,$api,"",$f);
    }
}

function geturl($id,$api,$api1,$f){
    $data = file_get_contents($api."?ac=videolist&ids=".$id);
    $xml = simplexml_load_string($data);
    foreach($xml->list->video as $video){
        global $array,$n;
        $type=(string)$video->type; //类型
        $year=(string)$video->year; //上映时间
        $des=(string)$video->des; //简介
        $pic=(string)$video->pic; //封面
        $url=(string)$video->dl->dd;   //播放地址
        preg_match_all("/https?:\/\/[^#]*\/index.m3u8/",$url,$playurl);
        preg_match_all("/#?([^#]+)[$]/",$url,$tag);
        $title=(string)$video->name;
        for($i=0;$i<sizeof($playurl[0]);$i++){
            $array[$n]["tag"][$i]=$tag[1][$i];  // 集数
            $array[$n]["url"][$i]=$playurl[0][$i];  // 播放地址
            $array[$n]["download"][$i]="暂无";
        }
        $array[$n]["title"]=$title;  // 名称
        $array[$n]["type"]=$type;  // 类型
        $array[$n]["year"]=$year;  // 上映时间
        $array[$n]["des"]=$des;  // 简介
        // $pic=str_replace("https","http",$pic);    // 解决部分封面
        $array[$n]["cover"]=$pic;  // 封面
        $array[$n]["zy"]=$api1;  // 资源来源
        if($f){
            build($f);
        }
        $n++;
    }
}

function build($f){
    global $array,$n,$file,$name,$fw,$cid,$pg;
    $luanma=array("<",">","rgb","(17, 17, 17)","&nbsp","span","style","="," ","color",":","13px","font-family","Helvetica",",","Arial","sans-serif",";","font-size","\"","/","br"); //部分简介乱码
    for($i=0;$i<sizeof($luanma);$i++){
        $array[$n]["des"]=str_replace($luanma[$i],"",$array[$n]["des"]);
    }
    if(isset($array[$n]["title"])){
        if( (!strstr($array[$n]["type"],"伦理")) && (!strstr($array[$n]["type"],"福利")) ){   // 屏蔽部分搜索结果
            print_r('<div id="playul"><p>'.$array[$n]["zy"].'</p><div><a id="cover" href="./play.php?wd=api'.$fw.'id'.$cid.'pg'.$pg.'&f=1&id='.$n.'" target="_blank" title="'.$array[$n]["des"].'" style="background-image: url('.$array[$n]["cover"].')">');  // 封面
            print_r("<span class=\"type\" >".$array[$n]["type"]."</span>");
            print_r("<span class=\"year\" >".$array[$n]["year"]."</span></a>");
            // if($array[$n]["download"][0]!="暂无"){
            //     print_r("<<span class=\"p2p\">迅雷p2p下载</span>");
            //     for($j=0;$j<sizeof($array[$n]["tag"]);$j++){
            //         print_r($array[$n]["tag"][$j]."$".$array[$n]["download"][$j]."\n");
            //     }
            // }
            print_r("<div class=\"title\" >".$array[$n]['title']."</div>");
            print_r("</div></div>");
        }
    }
    if($f){
        if(false!==fopen($file,'w+')){ 
            file_put_contents($file,serialize($array));//写入缓存 
        }
    }
}

function build1(){
    global $array,$fw,$cid;
    // print_r($array['list']);
    $i = (int)$array['list']['page'] +1;
    $j = (int)$array['list']['page'] -1;
    if($array['list']['page']=="1"){ //首页
        echo '<div><p><a href="./fl.php?fw='.$fw.'&cid='.$cid.'&pg='.$i.'">下一页 </a>视频总数'.$array['list']['recordcount'].'总页数'.$array['list']['page'].'/'.$array['list']['pagesize'].'</p></div>';
    }elseif($array['list']['page']==$array['list']['pagesize']){ // 尾页
        echo '<div><p><a href="./fl.php?fw='.$fw.'&cid='.$cid.'&pg='.$j.'">上一页 </a>视频总数'.$array['list']['recordcount'].'总页数'.$array['list']['page'].'/'.$array['list']['pagesize'].'</p></div>';
    }else{
        echo '<div><p><a href="./fl.php?fw='.$fw.'&cid='.$cid.'&pg='.$j.'">上一页 </a><a href="./fl.php?fw='.$fw.'&cid='.$cid.'&pg='.$i.'">下一页 </a>视频总数'.$array['list']['recordcount'].'总页数'.$array['list']['page'].'/'.$array['list']['pagesize'].'</p></div>';
    }
}

function getarray($f){
    global $api,$api1,$url,$url1,$name;
    // 只最大 API   // 节约服务器
    getname($api[1],"路线-1",$f);
    // 只ok 爬取   // 节约服务器
    $html = file_get_contents($url[0]."?m=vod-search&wd=".$name);   // 爬虫方式
    preg_match_all("/\?m=vod-detail-id-.+.html/",$html,$detail);
    foreach($detail[0] as $x=>$x_value){
        playdetail($url[0].$x_value,"路线-2",$f);
    }
}

$list=array();

if(!isset($api[$fw])){
    echo "服务器不存在";
    // 未选择服务器
    header("Location: ./error.php?error_code=6");
    exit();
}

if(isset($cid)){   // 某分类的视频  2
    $file = "./data/api".$fw."id".$cid."pg".$pg.".dp";
    //  判断是否更新
    if(file_exists($file)){
        $handle=fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
        $array=unserialize(fread($handle,filesize($file)));
        if($time>86400|$gx==1){    // 缓存文件太久才会更新  86400 24H
            getlist($api[$fw],$cid,false);  // 获取数据（不建立网页）
            if(false!==fopen($file,'w+')){ 
                file_put_contents($file,serialize($array));//写入缓存 
            }
        }
        build1(); // 下页
        for($n=0;$n<sizeof($array);$n++){
            build(false);  // 只建立网页，不更新内容
        }
    }
    else{
        getlist($api[$fw],$cid,true);  // 不存在， 更新内容 保存 构建
    }
}
else{  // 服务器分类页  1
    $file = "./data/api".$fw."id.dp";
    //  判断是否更新
    if(file_exists($file)){
        $handle=fopen($file,'r');// 存在 读取内容 只建立网页  只API 只爬取 
        $array=unserialize(fread($handle,filesize($file)));
    }
    else{
        $array=getcid($api[$fw],$api1[$fw]);  // 更新并保存
    }
    // 构建 页面
    foreach($array as $key=>$value){
        echo '<a href="./fl.php?fw='.$fw.'&cid='.$value["list_id"].'">'.$value["list_name"].'   </a>  ';
    }

}


?>
        </div>
    </body>
</html>