<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
include "conexion.php";
$idusuarioagrega=$_GET['idu'];
$idpermiso=$_GET['idp'];

echo $idusuarioagrega;
echo $idpermiso;
$sql = 'BEGIN VAC_PRC_ELIMINAPERMISOAUSUARIO(:idu,:idp); END;';
$stmt = oci_parse($conn,$sql);
oci_bind_by_name($stmt,':idu',$idusuarioagrega,32);
oci_bind_by_name($stmt,':idp',$idpermiso,32);
oci_execute($stmt);



 ?>
