<?php
/**
 * descript:
 * @date
 * @author  XuJun
 * @version 1.0
 * @package
 */
$height=$_GET['height'];
$con = mysqli_connect('56a3768226622.sh.cdb.myqcloud.com:4892','root','ctfoxno1','V');
if (!$con)
{
    die('Could not connect: ' . mysqli_connect_error());
}
$sql = "SELECT max(id) FROM tp_upload";
$result = mysqli_query($con,$sql);
while($row = mysqli_fetch_row($result)){
	$id = $row[0];
	mysqli_query($con,"UPDATE tp_upload SET height=$height WHERE id=$id");
	var_dump($id);}
?>
