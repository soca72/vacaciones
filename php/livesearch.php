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
     $metadato=strtoupper($_GET['q']);
     if(!$metadato)
     {
         echo "<p>No se encontraron coincidencias</p>";
        return;
     }
        
     $idusuario=$_GET['idu'];
     if($_GET['tabla']!='' && $_GET['tabla']!=null)$tabla=$_GET['tabla'];
     else $tabla='Solicita';
        $sql = "BEGIN VAC_PRC_RETUSRPORNOMFILT(:idu, :entnombre,:tabla,:nombreseids); END ;";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':idu',$idusuario,32);
        oci_bind_by_name($stmt,':entnombre',$metadato,32);
        oci_bind_by_name($stmt,':tabla',$tabla,32);
        oci_bind_by_name($stmt, ":nombreseids", $cursor, -1, OCI_B_CURSOR);

                        oci_execute($stmt);
                        oci_execute($cursor);
                    // Fetch result rows as an associative array
                        $i=0;
                while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                           echo "<a style='color:black' href='javascript:hello(\"$row[0]"." | "."$row[1]\")'>".$row[0]." ".$row[1]."</a>  ";
                           echo "<br>";
                            $i++;
                            if($i>4)
                                break;
                        }


                if($i==0){

                    echo "<p>El usuario no esta registrado en la tabla de usuarios</p>";

                }


 
oci_close($conn);
?>
