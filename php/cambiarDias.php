<?php 
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
include "conexion.php";
$idusuario=$_GET['idu'];
$dias=$_GET['dias'];
if($_GET['accion']!=null && $_GET['accion']!="")$accion=$_GET['accion'];
else $accion=1;
echo $idusuarioagrega;
echo $iddepartamento;
$sql = 'BEGIN VAC_PRC_AGREGADIAS(:idu,:dias); END;';
$stmt = oci_parse($conn,$sql);
oci_bind_by_name($stmt,':idu',$idusuarioagrega,32);
oci_bind_by_name($stmt,':dias',$dias,32);
oci_bind_by_name($stmt,':accion',$accion,32);
oci_execute($stmt);

 ?>
