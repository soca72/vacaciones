<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
   include "conexion.php";

 
// Check connection
if($link === false){
    echo "error connecting";
}
 
 
    // Prepare a select statement
    $idusuario=strtoupper($_GET['q']);
    


        // Bind variables to the prepared statement as parameters
        //echo $_GET['q'];
        $data=explode("|", $_GET['q']);
        $idusuario=$data[0];
               $sql="BEGIN VAC_PRC_CONTARDIASRESTANTES(:idu ,:nombre,:apaterno,:amaterno,:departamento,:antiguedad,:fechaIngreso,:fechaCumple,:dias,:diasLaborables,:idud,:solPendientes,:direccionOut,:celularOut,:emailOut,:usuarioOut,:claveOut); END;";               
               $stmt=oci_parse($conn,$sql);
               oci_bind_by_name($stmt, ":idu",$idusuario,32);
               oci_bind_by_name($stmt,':nombre',$nombreq,32);
               oci_bind_by_name($stmt,':apaterno',$apaternoq,32);
               oci_bind_by_name($stmt,':amaterno',$amaternoq,32);
               oci_bind_by_name($stmt,':departamento',$departamentoq,32);
               oci_bind_by_name($stmt,':fechaIngreso',$fechaingreso,32);
               oci_bind_by_name($stmt,':fechaCumple',$fechaCumple,32);
               oci_bind_by_name($stmt,':antiguedad',$antiguedad,32);
               oci_bind_by_name($stmt,':dias',$diasderecho,32);
               oci_bind_by_name($stmt,':diasLaborables',$diasLaborables,32);
               oci_bind_by_name($stmt,':idud',$idud,32);
               oci_bind_by_name($stmt,':solPendientes',$solPendientes,32);
               oci_bind_by_name($stmt,':direccionOut',$direccionq,200);
               oci_bind_by_name($stmt,':celularOut',$celularq,32);
               oci_bind_by_name($stmt,':emailOut',$emailq,50);
               oci_bind_by_name($stmt,':usuarioOut',$usuarioq,32);
               oci_bind_by_name($stmt,':claveOut',$claveq,32);
               $r=oci_execute($stmt);
        echo $diasderecho."|".$diasLaborables."|".$nombreq." ".$apaternoq." ".$amaternoq."|".$fechaingreso."|".$antiguedad."|".$idud."|".$fechaCumple;
       


 
oci_close($conn);
?>
