<?php
error_reporting(E_ALL ^ E_NOTICE);
include "conexion.php";
if($_SESSION["idusuario"]=="") header('Location:../index.php');
/* Attempt MySQL server connection. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
   

 
// Check connection
if($conn === false){
    echo "error connecting";
}
 
 
    // Prepare a select statement
    $idusuario=trim(strtoupper($_GET['idu']));
    


        // Bind variables to the prepared statement as parameters
        //echo $_GET['q'];
       $sql="BEGIN VAC_PRC_CONTARDIASRESTANTES(:idu ,:nombre,:apaterno,:amaterno,:departamento,:antiguedad,:fechaIngreso,:fechaCumple,:dias,:diasLaborables,:idud,:solPendientes,:direccionOut,:celularOut,:emailOut,:usuarioOut,:claveOut,:idsucursal); END;";               
        $cursor = oci_new_cursor($conn);
        $stmt= oci_parse($conn, $sql);
               oci_bind_by_name($stmt, ":idu",$idusuario,50);
               oci_bind_by_name($stmt,':nombre',$nombreq,32);
               oci_bind_by_name($stmt,':apaterno',$apaternoq,32);
               oci_bind_by_name($stmt,':amaterno',$amaternoq,32);
               oci_bind_by_name($stmt,':departamento',$departamentoq,32);
               oci_bind_by_name($stmt,':fechaIngreso',$fechaingreso,32);
               oci_bind_by_name($stmt,':fechaCumple',$fechaCumple,32);
               oci_bind_by_name($stmt,':antiguedad',$antiguedad,32);
               oci_bind_by_name($stmt,':dias',$diasderecho,32);
               oci_bind_by_name($stmt,':diasLaborables',$diaslaborablesq,32);
               oci_bind_by_name($stmt,':idud',$idud,32);
               oci_bind_by_name($stmt,':solPendientes',$solPendientes,32);
               oci_bind_by_name($stmt,':direccionOut',$direccionq,200);
               oci_bind_by_name($stmt,':celularOut',$celularq,32);
               oci_bind_by_name($stmt,':emailOut',$emailq,50);
               oci_bind_by_name($stmt,':usuarioOut',$usuarioq,32);
               oci_bind_by_name($stmt,':claveOut',$claveq,32);
               oci_bind_by_name($stmt,'idsucursal',$idsucursal,32);
               $r=oci_execute($stmt);
               
               
        $sql="BEGIN VAC_PRC_permisosusario(:idusuario , :permisos); END;";  
        $cursor = oci_new_cursor($conn);
        $stmt= oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":permisos", $cursor, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt,':idusuario',$idud);
        $r=oci_execute($stmt);
        $r=oci_execute($cursor);
        $i=0;
        while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
        $permisos[$i] = $row[0];
        $i++;
        }
        
        
        
        $sql="BEGIN VAC_PRC_RETORNADPTOSOL(:idu,:depsSol); END;";
                                $cursor = oci_new_cursor($conn);
                                $stmt= oci_parse($conn, $sql);
                                oci_bind_by_name($stmt,':idu',$idud,32);
                                oci_bind_by_name($stmt, "depsSol", $cursor, -1, OCI_B_CURSOR);
                                $r=oci_execute($stmt);
                                $r=oci_execute($cursor);
                                $depsSol=array();
                                $i=0;
                                while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                                     $depsSol[$i]=$row[0];//Guardamos los departamentos que el usuario puede solicitar
                                     $i++;
                                  }
        
        $sql="BEGIN VAC_PRC_DEPARTAMENTOSDEUSUARIO(:idu,:depsAut); END;";
                                $cursor = oci_new_cursor($conn);
                                $stmt= oci_parse($conn, $sql);
                                oci_bind_by_name($stmt,':idu',$idud,32);
                                oci_bind_by_name($stmt, ":depsAut", $cursor, -1, OCI_B_CURSOR);
                                $r=oci_execute($stmt);
                                $r=oci_execute($cursor);
                                $depsAut=array();
                                $sucAut=array();
                                $i=0;
                                while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                                    //Guardamos los departamentos que el usuario puede aprobar
                                    $depsAut[$i] = ["idDep" => $row[0], "sucursal" => $row[2]];
                                    $i++;
                                }  
                                  
                                  
                                  
                                  
                                  
                $json = array(
                      "diasLaborables"=>$diaslaborablesq, 
                      "nombre"=>$nombreq, 
                      "apaterno"=>$apaternoq,
                      "amaterno"=>$amaternoq, 
                      "antiguedad"=>$antiguedad, 
                      "fechaIngreso"=>$fechaingreso, 
                      "departamento"=>$departamentoq,
                      "idud"=>$idud,
                      "direccion"=>$direccionq,
                      "celular"=>$celularq,
                      "email"=>$emailq,
                      "usuario"=>$usuarioq,
                      "clave"=>$claveq,
                      "sucursal"=>$idsucursal,
                      "permisos"=>implode(",",$permisos),
                      "depsSol"=>implode(",",$depsSol),
                      "depsAut"=>$depsAut,
                      "diasDisponibles" => $diasderecho
                    );       
                       
                       
                       
                       
                       
                       
                       
       echo json_encode($json);
       
oci_close($conn);
?>
