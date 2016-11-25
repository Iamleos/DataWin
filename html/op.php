<?php
/**
 * descript:
 * @date
 * @author 
 * @version 1.0
 * @package
 */
$height='700';

$con = mysqli_connect('56a3768226622.sh.cdb.myqcloud.com:4892','root','ctfoxno1','V');
if (!$con)
{
    die('Could not connect: ' . mysqli_connect_error());
}
$sql = "SELECT max(id) FROM tp_upload";
$id = mysql_query($sql,$con);
var_dump($id);
//$id=mysql_insert_id();
mysqli_query($con,"UPDATE tp_upload SET height=$height
WHERE id='$id'");
mysqli_close($con);
?>
