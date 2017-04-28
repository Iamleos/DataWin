<?php
//去除亿，万，并且转化
function yi2wan($data){
    if (strstr($data,'亿')) {
        return (string)(str_ireplace('亿','',$data)*10000);
    }
    else {
        return str_ireplace('万','',$data);
    }
}
//去处%
function del_percent($data){
    return str_ireplace('%','',$data);
}

 ?>
