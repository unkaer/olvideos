<?php

if(array_key_exists("url", $_POST)|array_key_exists("url", $_GET)){
    if(isset($_POST["url"])){$url = $_POST["url"];}else{$url = $_GET["url"];}
}

$html = file_get_contents($url);
print_r($html);


?>