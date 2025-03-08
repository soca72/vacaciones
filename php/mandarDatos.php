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
include "conexion.php";
ECHO $idu=$_GET['idu'];
$dias=$_GET['dias'];
$idDepto=$_GET['idDepto'];
$fechaingreso=$_GET['fechaingreso'];
$guardar=$_GET['accion'];
$totalDias=$_GET['totalDias'];
if($guardar=='1'){ 
    
    
 $sql="BEGIN VAC_PRC_GUARDARDATOS(:idu,:idDeptoIn,:usuariomodifica); END;";
 $stmt=oci_parse($conn,$sql);
 oci_bind_by_name($stmt, ":idu",$idu,32);
 oci_bind_by_name($stmt,':idDeptoIn',$idDepto,32);
 oci_bind_by_name($stmt,':usuariomodifica',$idusuario,32);
 $r=oci_execute($stmt);
 
 
 $sql="BEGIN VAC_PRC_DIASLABORABLES(:idu,:dias,:fechaingreso,:usuariomodifica); END;";
 $stmt=oci_parse($conn,$sql);
 oci_bind_by_name($stmt, ":idu",$idu,32);
 oci_bind_by_name($stmt,':dias',$dias,32);
 oci_bind_by_name($stmt,':fechaingreso',$fechaingreso,32);
 oci_bind_by_name($stmt,':usuariomodifica',$idusuario,32);
 $r=oci_execute($stmt);
 
$sql="BEGIN VAC_PRC_AJUSTEDIAS(:idu,:dias,:idreg);END;";
$stmt=oci_parse($conn,$sql);
oci_bind_by_name($stmt,':idu',$idu,32);
oci_bind_by_name($stmt,':dias',$totalDias,32);
oci_bind_by_name($stmt,':idreg',$idusuario,32);
$result=oci_execute($stmt);
 
}
else{

               $sql="BEGIN VAC_PRC_CONTARDIASRESTANTES(:idu ,:nombre,:apaterno,:amaterno,:departamento,:antiguedad,:fechaIngreso,:fechaCumple,:dias,:diasLaborables,:idud,:solPendientes,:direccionOut,:celularOut,:emailOut,:usuarioOut,:claveOut); END;";               
               $stmt=oci_parse($conn,$sql);
               oci_bind_by_name($stmt, ":idu",$idu,32);
               oci_bind_by_name($stmt,':nombre',$nombreq,32);
               oci_bind_by_name($stmt,':apaterno',$apaternoq,32);
               oci_bind_by_name($stmt,':amaterno',$amaternoq,32);
               oci_bind_by_name($stmt,':departamento',$departamentoq,32);
               oci_bind_by_name($stmt,':fechaIngreso',$fechaingreso,32);
               oci_bind_by_name($stmt,':fechaCumple',$fechaCumple,32);
               oci_bind_by_name($stmt,':antiguedad',$antiguedad,32);
               oci_bind_by_name($stmt,':dias',$diasderecho,32);
               oci_bind_by_name($stmt,':diasLaborables',$sinUnso,32);
               oci_bind_by_name($stmt,':idud',$idud,32);
               oci_bind_by_name($stmt,':solPendientes',$solPendientes,32);
               oci_bind_by_name($stmt,':direccionOut',$direccionq,200);
               oci_bind_by_name($stmt,':celularOut',$celularq,32);
               oci_bind_by_name($stmt,':emailOut',$emailq,50);
               oci_bind_by_name($stmt,':usuarioOut',$usuarioq,32);
               oci_bind_by_name($stmt,':claveOut',$claveq,32);
               $r=oci_execute($stmt);
}
echo $diasLaborables."|".$fechaingreso."|".$idDepto;
oci_close($conn);


?>
