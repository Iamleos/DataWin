<?php
$file = fopen('/home/leos/work/maoyan_city_supplement/result15.txt','r');

$result = fread($file, filesize('/home/leos/work/maoyan_city_supplement/result15.txt'));
preg_match_all('/<div class="t-row">\s*<div class="t-col">(.*)<\/div>\s*<\/div>/' ,$result, $city);
preg_match_all('/<div class="t-col">(.*)<\/div>/' ,$result, $data);
$city =$city[1];
$data = $data[1];
array_shift($data);
array_shift($data);
for($i = 0; $i < 54; $i++){
    array_shift($data);
}
var_dump($data);
//var_dump(array_diff_assoc($data,$city));
?>
