<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
include 'funciones.php';
include 'conexion.php';
echo $permiso=$_POST['tipoPermiso'];
echo "<br>";
echo $motivo=$_POST['motivoPermiso'];
echo "<br>";
echo $idMotivo=$_POST['idMotivo'];
if($idMotivo!=null && $idMotivo!=""){
    $sql="UPDATE VAC_COMENTARIOSPREESTABLECIDOS SET COMENTARIOPREESTABLECIDO='$motivo',IDPERMISO=$permiso WHERE IDCOMENTARIOPREESTABLECIDO=$idMotivo";
}
else{  
    $sql="INSERT INTO VAC_COMENTARIOSPREESTABLECIDOS(COMENTARIOPREESTABLECIDO,IDPERMISO) VALUES('$motivo',$permiso)";
}
   
   $stmt=oci_parse($conn,$sql);
   $result=oci_execute($stmt);
if($result)header('Location:motivos.php');
else{
    $e = oci_error($stmt);
    $error = explode("\n",$e['message']);
    
    echo $error[1];
    header('Location:motivos.php?error='.$error[0]);
}
    oci_close($conn);

