<?php 
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
include "conexion.php";
$idusuario=$_GET['idUsr'];
$idDept=$_GET['idDep'];
$sucursal=$_GET['idSuc'];

echo $idusuario;
echo $idDept;
echo $sucursal;
$sql = 'BEGIN VAC_PRC_ELIMINADPTOAUSUARIO(:idusuario,:idDept,:sucursal); END;';
$stmt = oci_parse($conn,$sql);
oci_bind_by_name($stmt,':idusuario',$idusuario,32);
oci_bind_by_name($stmt,':idDept',$idDept,32);
oci_bind_by_name($stmt,':sucursal',$sucursal,32);
oci_execute($stmt);
 ?>
