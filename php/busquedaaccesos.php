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
    if(strlen($metadato)>5){
        // Prepare a select statement
        $sql = "BEGIN VAC_PRC_BUSCAUSAURIOPORNOMBRE(:entnombre,:nombreseids); END ;";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':entnombre',$metadato,32);
        oci_bind_by_name($stmt, ":nombreseids", $cursor, -1, OCI_B_CURSOR);

                        oci_execute($stmt);
                        oci_execute($cursor);
                    // Fetch result rows as an associative array
                        $i=0;
                        $rowAnt="";
                while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                    if($rowAnt!=$row[3]){
                           echo "<a style='color:black' href='javascript:hello(\"$row[3]"." | "."$row[1]\")'>".$row[1]."</a>";
                           echo "<br>";
                    }
                    $rowAnt=$row[3];
                            $i++;
                            if($i>5)
                                break;
                        }
                if($i==0){
                    echo "<p>No se encontraron coincidencias</p>";
                }
    }
    else{
        echo "<p>Minimo 5 caracteres</p>";
    }
oci_close($conn);
?>
