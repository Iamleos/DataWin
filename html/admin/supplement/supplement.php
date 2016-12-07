<?php

  if($_POST["zzb"]!=NULL){
    shell_exec("php /var/www/html/filmdaily/truing_zzb_movie.php ".$_POST["zzb"]);
    echo '<div style="position:relative; left:40%;"><strong>补采成功！！</strong></div>';
  }
  elseif($_POST["myf"]!=NULL){
    shell_exec("php /var/www/html/filmdaily/maoyanfen/maoyan_download_resource.php ".$_POST["myf"]);
    echo '<div style="position:relative; left:40%;"><strong>补采成功！！</strong></div>';
  }
  elseif($_POST["myjb"]!=NULL){
    shell_exec("php /var/www/html/filmdaily/maoyanpiaofangjianbao/maoyan_download_resource.php ".$_POST["myjb"]);
    echo '<div style="position:relative; left:40%;"><strong>补采成功！！</strong></div>';
  }
  elseif($_POST["mycb"]!=NULL){
    shell_exec("php /var/www/html/filmdaily/maoyanpiaofang/maoyan_download_resource.php ".$_POST["mycb"]);
    echo '<div style="position:relative; left:40%;"><strong>补采成功！！</strong></div>';
  }
  elseif($_POST["yepf"]!=NULL){
    shell_exec("php /var/www/html/filmdaily/yienpiaofang.php ".$_POST["yepf"]);
    echo '<div style="position:relative; left:40%;"><strong>补采成功！！</strong></div>';
  }
  else {
    echo '<div style="position:relative; left:40%;"><strong>请正确输入时间！！</strong></div>';
  }
?>
