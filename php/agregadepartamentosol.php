<?php 
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
include "conexion.php";
$idusuarioagrega=$_GET['idu'];
$iddepartamento=$_GET['idds'];
echo $idusuarioagrega;
$sql = 'BEGIN VAC_PRC_AGREGADPTOSOLICITABLE(:idu,:idds); END;';
$stmt = oci_parse($conn,$sql);
oci_bind_by_name($stmt,':idu',$idusuarioagrega,32);
oci_bind_by_name($stmt,':idds',$iddepartamento,32);
oci_execute($stmt);

 ?>
