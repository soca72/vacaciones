<?php
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
//ini_set('display_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);
ini_set('display_errors', 0);
date_default_timezone_set('America/Chicago');
$conn= oci_connect("siodev","radeon","192.168.1.34/dbsio","UTF8");

//$conn= oci_connect("siodev","radeon","192.9.200.8/siodev","UTF8");

$cursor = oci_new_cursor($conn);
if(!$conn)
{
   $conn = oci_error();
   echo $_SESSION["DBConnect"]="No se puede conectar a la base de datos. ".$conn['message'], "\n";
   exit;
   echo "Error en la conexion... verifica con el administrador del servidor en dado caso de que te marque el error ORA-24408 o referido al servidor";
}
else
{
 //echo "Conexion establecida...";
}

?>
