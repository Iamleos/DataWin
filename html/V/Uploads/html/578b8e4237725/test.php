<?php
/**
 * descript:
 * @date
 * @author  XuJun
 * @version 1.0
 * @package
 */
$height=$_GET['height'];
file_put_contents("a.txt",$height);
echo file_get_contents("a.txt");
chmod("a.txt", 0777);
?>
