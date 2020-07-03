<?php

function passport_encrypt($txt, $key) {  
    srand((double)microtime() * 1000000);  
    $encrypt_key = md5(rand(0, 32000));  
    $ctr = 0;  
    $tmp = '';  
    for($i = 0;$i < strlen($txt); $i++) {  
    $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;  
    $tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);  
    }  
    return urlencode(base64_encode(passport_key($tmp, $key)));  
}  
 
function passport_decrypt($txt, $key) {  
    $txt = passport_key(base64_decode(urldecode($txt)), $key);  
    $tmp = '';  
    for($i = 0;$i < strlen($txt); $i++) {  
    $md5 = $txt[$i];  
    $tmp .= $txt[++$i] ^ $md5;  
    }  
    return $tmp;  
}  
 
function passport_key($txt, $encrypt_key) {  
    $encrypt_key = md5($encrypt_key);  
    $ctr = 0;  
    $tmp = '';  
    for($i = 0; $i < strlen($txt); $i++) {  
    $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;  
    $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];  
    }  
    return $tmp;  
}
$key = "testkey";  
 
// $txt = "95619651da4s56d16a51f6asdddd12e1d12d21de5qw";  
// $encrypt = passport_encrypt($txt,$key);  
// $decrypt = passport_decrypt($encrypt,$key);  
 
// setcookie("user", "runoob", time()+3600);
// print_r($_COOKIE);

// echo $encrypt."<br>";  
// echo $decrypt."<br>";  
?>