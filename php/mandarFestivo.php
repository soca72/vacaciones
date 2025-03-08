<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
include 'funciones.php';
include 'conexion.php';
echo $fecha=$_POST['fechaFestivo'];
echo "<br>";
echo $descripcion=$_POST['descripcion'];
echo "<br>";
echo $idFecha=$_POST['idFecha'];
if($idFecha!=null && $idFecha!=""){
    $sql="UPDATE VAC_DIAS_NO_HABILES SET FECHA=to_date('$fecha','DD/MM/YYYY'),DESCRIPCION='$descripcion' WHERE IDFECHA=$idFecha";
}
else{  
    $sql="INSERT INTO VAC_DIAS_NO_HABILES(FECHA,DESCRIPCION) VALUES(to_date('$fecha','DD/MM/YYYY'),'$descripcion')";
}
   
   $stmt=oci_parse($conn,$sql);
   $result=oci_execute($stmt);
if($result)header('Location:festivos.php');
else{
    $e = oci_error($stmt);
    $error = explode("\n",$e['message']);
    
    echo $error[1];
    header('Location:festivos.php?error='.$error[0]);
}
    oci_close($conn);

