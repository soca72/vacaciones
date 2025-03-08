<?php 
error_reporting(E_ALL ^ E_NOTICE);
include "conexion.php";
include "funciones.php";
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
$idusuario=$_SESSION["idusuario"];
$idusuarioData=$_SESSION["idusuariodata"];
$accion = $_SESSION["accion"];


        $sql="BEGIN VAC_PRC_permisosusario(:idusuario , :permisos); END;";
        $cursor = oci_new_cursor($conn);
        $stmt= oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":permisos", $cursor, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt,':idusuario',$idusuarioData,32);
        $r=oci_execute($stmt);
        $r=oci_execute($cursor);
        $permisos=array();
        $permisosaimprimir=array();
        $i=0;
        $auximp=0;
        while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
            $permisos[$i] = $row[0];
            
                  $permisosaimprimir[$auximp]=$row[0];
                  $auximp++;
            $i++;
        }
if(!$idusuario)
{
  echo "Algo ocurrió, intentelo mas tarde porfavor";
}
else
{
    $arregloregistros= array();
    switch($accion){
                    case 'REPORTE_HORASEXTRAS':
                                                $sql="select 
                                                        idusuariodata, 
                                                        nombre, 
                                                        nvl(sum(case when upper(opcionpago) = 'CONSUELDO' then horas else 0 end),0) as horasPagar, 
                                                        nvl(sum(case when upper(opcionpago) = 'TIEMPO' then horas else 0 end),0) as horasTiempo 
                                                        from vac_solicitudesvacaciones
                                                        where fechaalta between to_date(:fechaIni, 'dd/mm/yyyy') and trunc(to_date(:fechaFin, 'dd/mm/yyyy') + 1) - 0.00001
                                                        group by idusuariodata, nombre";
                                                $stmt= oci_parse($conn, $sql);
                                                oci_bind_by_name($stmt, ":permisos", $cursor, -1, OCI_B_CURSOR);
                                                oci_bind_by_name($stmt,':idusuario',$idusuarioData,32);
                                                oci_execute($stmt);
                                                while (($row = oci_fetch_array($stmt, OCI_BOTH)) != false) {
                                                    array_push($arregloregistros,$row);
                                                }
                                                echo json_encode($arregloregistros);
                                                break;
    }
}
 ?>
