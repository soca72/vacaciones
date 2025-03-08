<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
include "conexion.php";
$idusuarioagrega=$_GET['idu'];
$iddpto=$_GET['idds'];

echo $idusuarioagrega;
//echo $idpermiso;
$sql = 'BEGIN VAC_PRC_ELIMINADPTOSOL(:idu,:idds); END;';
$stmt = oci_parse($conn,$sql);
oci_bind_by_name($stmt,':idu',$idusuarioagrega,32);
oci_bind_by_name($stmt,':idds',$iddpto,32);
oci_execute($stmt);



 ?>
