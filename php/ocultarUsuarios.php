<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]!=""){
    $idusuario=$_SESSION["idusuario"];
    $idusuarioData=$_SESSION["idusuariodata"];
}
else{
header("Location:../index.php");
}
include 'funciones.php';
include 'conexion.php';
echo $idOculto=$_POST['idOculto'];
echo $nuevo=$_POST['nuevo'];
if($nuevo!='1'){
       $filtro="?filtro=1";
}
    $sql="BEGIN VAC_PRC_OCULTAR(:idu ,:idOcultar, :accion); END;";
    $stmt=oci_parse($conn,$sql);
    oci_bind_by_name($stmt,':idOcultar',$idOculto,32);
    oci_bind_by_name($stmt,':idu',$idusuarioData,32);
    oci_bind_by_name($stmt,':accion',$nuevo,32); 
    $result=oci_execute($stmt);
echo $sql;
echo "<br>".$idOculto;
echo "<br>".$nuevo;
if($result)header('Location:usuarios.php'.$filtro);
else{
    $e = oci_error($stmt);
    $error = explode("\n",$e['message']);
    
    echo $error[1];
    header('Location:usuarios.php');
}
    oci_close($conn);

