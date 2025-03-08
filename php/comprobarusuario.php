<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
include "conexion.php";
$nombre=$_POST['nombreusuario'];
$contra=$_POST['contrasena'];
if($_POST['idUsuario']!='' && $_POST['idUsuario']!=null){
    $idu=$_POST['idUsuario'];
    $sql = 'BEGIN VAC_PRC_comprobarusuario2(:idu, :iddep, :nombre, :apaterno, :amaterno, :diaslaborables,:idusuariodata); END;';
    $stmt = oci_parse($conn,$sql);
    oci_bind_by_name($stmt,':idu',$idu,32);
}
else{
$sql = 'BEGIN VAC_PRC_comprobarusuario(:nu, :cn, :idu, :iddep, :nombre, :apaterno, :amaterno, :diaslaborables, :idusuariodata); END;';
$stmt = oci_parse($conn,$sql);
oci_bind_by_name($stmt,':nu' ,$nombre,32);
oci_bind_by_name($stmt,':cn' ,$contra,32);
oci_bind_by_name($stmt,':idu',$idu,32);
}
oci_bind_by_name($stmt,':iddep',$iddep,32);
oci_bind_by_name($stmt,':nombre',$username,32);
oci_bind_by_name($stmt,':apaterno',$apaterno,32);
oci_bind_by_name($stmt,':amaterno',$amaterno,32);
oci_bind_by_name($stmt,':diaslaborables',$diaslaborables,32);
oci_bind_by_name($stmt,':idusuariodata',$idusuariodata,32);
oci_execute($stmt); 
if($diaslaborables=="" || $diaslaborables==null){$diaslaborables="1,2,3,4,5,6,7";}



if (($idu!=0 && $idu!='') && ($idusuariodata!='' && $idusuariodata!=0))
{    
  session_start();
  echo $_SESSION['idusuario']=$idu;
  echo $_SESSION['idusuariodata']=$idusuariodata;
  echo $_SESSION['iddepartamento']=$iddep;
  echo $_SESSION['username']=$nombre;
  echo $_SESSION['nomuser']=$username." ".$apaterno." ".$amaterno;
  echo $_SESSION['diasLaborables']=$diaslaborables;
} 
else{
$sql = 'BEGIN VAC_PRC_bloqueousuario(:nu,:idu,:mensaje); END;';
$stmt = oci_parse($conn,$sql);
oci_bind_by_name($stmt,':nu',$nombre,32);
oci_bind_by_name($stmt,':idu',$idu,32);
oci_bind_by_name($stmt,':mensaje',$mensaje,32);
oci_execute($stmt);
if($mensaje!=null && $mensaje!=""){
  echo  $_SESSION['badUser']="Usuario bloqueado, por favor comuniquese con RH";
}
else echo  $_SESSION['badUser']='Datos Incorrectos, intentelo de nuevo';
}
oci_close($conn);
 ?>
