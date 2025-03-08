<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 3/10/16
 * Time: 05:59 PM
 */
include 'funciones.php';
include 'conexion.php';
echo $motivoEliminar=$_POST['motivoElim'];
echo "<br>";
// $periodopermiso=$fechas->fechascalendario('L.MA.MI.J.V.S',$fi,$ff);

$sql="DELETE FROM VAC_COMENTARIOSPREESTABLECIDOS WHERE idcomentariopreestablecido=".$motivoEliminar;
$stmt=oci_parse($conn,$sql);
//oci_bind_by_name($stmt,':totaldias',$totaldias,32);
$result=oci_execute($stmt);

if($result) header('Location:motivos.php');
else{
    $e = oci_error($stmt);
    $error = explode("$", $e['message']);
    echo "<script>alert('".$error[1]."')</script>";
    header('Location:motivos.php?error='.$error[1]);
}

    oci_close($conn);

