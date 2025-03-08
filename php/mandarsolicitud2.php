<?php
error_reporting(E_ALL ^ E_NOTICE);
include 'funciones.php';
include 'conexion.php';
echo "ID Usuario:".$idusuario=$_POST['idusuarioageno'];
echo "<br>";
echo "DIAS DERECHO:".$totaldias=$_POST['diasderecho'];
echo "<br>";
echo $_SESSION['idusuario'];
echo "<br>";
echo $sql="BEGIN VAC_PRC_AJUSTEDIAS(:idu,:dias,:idreg);END;";
$stmt=oci_parse($conn,$sql);
oci_bind_by_name($stmt,':idu',$idusuario,32);
oci_bind_by_name($stmt,':dias',$totaldias,32);
oci_bind_by_name($stmt,':idreg',$_SESSION['idusuario'],32);
echo $result=oci_execute($stmt);
echo oci_error($conn);
oci_close($conn);
header('Location:Inicializar.php');
?>
