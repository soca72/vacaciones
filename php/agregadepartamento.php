<?php 
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
include "conexion.php";
$idusuarioagrega=$_GET['idu'];
$iddepartamento=$_GET['idd'];
$idsucursal=$_GET['idSucursal'];
echo $idusuarioagrega;
echo $iddepartamento;
echo $idsucursal;
$sql = 'BEGIN VAC_PRC_AGREGADPTOAUSUARIO(:idUsuario,:idDepartamento,:idSucursal); END;';
$stmt = oci_parse($conn,$sql);
oci_bind_by_name($stmt,':idUsuario',$idusuarioagrega,32);
oci_bind_by_name($stmt,':idDepartamento',$iddepartamento,32);
oci_bind_by_name($stmt,':idSucursal',$idsucursal,32);
oci_execute($stmt);

 ?>
