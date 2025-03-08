<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 3/10/16
 * Time: 05:59 PM
 */
include 'funciones.php';
include 'conexion.php';
$fechas=new fechas();
$pendiente=-40;
echo "FI:".$fi=$_POST['fi']." ".$_POST['hi'];
echo "<br>";
echo "FF:".$ff=$_POST['ff']." ".$_POST['hf'];
echo "<br>";
echo "HI:".$hi=$_POST['hi'];
echo "<br>";
echo "HF:".$hf=$_POST['hf'];
echo "<br>";
echo "NOMBRE:".$nombre=$_POST['nombre'];
echo "<br>";
echo "ID:".$idusuario=$_POST['idusuario'];
echo "<br>";
echo "ID DEP:".$iddepartamento=$_POST['iddepartamento'];
echo "<br>";
echo "DIAS LAB:".$diaslaborados=$_POST['diaslaborados'];
echo "<br>";
echo "DIAS DERECHO:".$diasderecho=$_POST['diasderecho'];
echo "<br>";
echo "TIPO PERM:".$tipopermiso=$_POST['tp'];
echo "<br>";
echo "CON GOCE:".$opcionsueldo=$_POST['os'];
echo "<br>";
echo "Comentario:".$comentario=$_POST['comentario'];
echo "<br>";
echo "Comentario Preestablecido:".$comentarioPreesta=$_POST['comentarioPreestablecido'];
echo "<br>";
$_SESSION["tp"]=$tipopermiso;
$idusuarioageno=$_POST['idusuarioageno'];
$diferenciaDias=$_POST['diferenciaDias'];
if($tipopermiso == "Horas extras") $diferenciaDias = 0;
$fiComparar= str_replace("/", "-", $_POST['fi']);
$ffComparar= str_replace("/", "-", $_POST['ff']);
echo "FI Comparar".$fiComparar."<br>";
echo "FF Comparar".$ffComparar."<br>";
$cruzaFechas=0;
$accion=0;
 if($idusuarioageno!="")
 {
    $idusuariooriginal=$idusuario;
    $idusuario=$idusuarioageno;

 }

// $periodopermiso=$fechas->fechascalendario('L.MA.MI.J.V.S',$fi,$ff);
$error=false;

        $sql="BEGIN VAC_PRC_COMPROBARFECHAS(:idu ,:fechas); END;";
        $cursor = oci_new_cursor($conn);
        $stmt= oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":fechas", $cursor, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt,':idu',$idusuario,32);
        $r=oci_execute($stmt);
        $r=oci_execute($cursor);
        while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
            $periodo=$fechas->fechascalendario('L.MA.MI.J.V.S',$row[0],$row[1]);
            $periodoComparar=$fechas->fechascalendario('L.MA.MI.J.V.S',$fiComparar,$ffComparar);
            //echo "<br><br><br>PERIODO DE ".$row[0]." HASTA ".$row[1]."<br><br><br>";
            $z=0;
         while($periodoComparar[$z]){   
             $a=0;
            while ($periodo[$a]) {
                if($periodoComparar[$z]==$periodo[$a] && $tipopermiso != "Horas extras" || $periodoComparar[$z]==$periodo[$a] && $tipopermiso != "Horas extras"){
                  //  echo "<br><br>coincide!!".$periodo[$a]."<br><br>";
                    $cruzaFechas=1;
                    echo "<br>Se cruzan fechas<br>";
                }
                
                //echo "Periodo Normal ---".$periodo[$a]."<br>";
                //echo "Periodo Comparar ---".$periodoComparar[$z]."<br>";
                
                $a++;
                
            }
            $z++;
        }
            $i=0;
            while ($periodopermiso[$i]) {
                //echo "Periodo Permiso****".$periodopermiso[$i]."<br>";
                if(in_array($periodopermiso[$i],$periodo))
                {
                 $error=true;
                }
                $i++;
            }
        }
 

if(0==strcmp($tipopermiso,"Vacaciones"))
{
$sql="BEGIN VAC_PRC_SOLICITUDESPENTIENDES(:idu,:idsol) ; END;";
echo $sql;
$stmt=oci_parse($conn,$sql);
oci_bind_by_name($stmt,':idu',$idusuario,32);
oci_bind_by_name($stmt,':idsol',$pendiente,32);
oci_execute($stmt);
}
if($pendiente!=-40 || $error )
    {
        $_SESSION["accion"]=4;
        if($error)
            {
            $_SESSION["accion"]=5;
            }
        if($idusuarioageno!=""){
                $idusuario=$idusuariooriginal;
            }  
        header('Location:main.php');
    }
else {
    $fiParaDias=$fechas->formateafecha($_POST['fi']);
    $ffParaDias=$fechas->formateafecha($_POST['ff']);
     echo "despues de formatea fecha";
    echo "FI: ".$fiParaDias." -> ".$_POST['fi'];
    echo"<br>";
    echo "FF: ".$ffParaDias." -> ".$_POST['ff'];
    $totaldias=$fechas->totaldias($diaslaborados,$fiParaDias,$ffParaDias);
    //$fi=$fechas->formateafechaparadb($fi);
    //$ff=$fechas->formateafechaparadb($ff);
    echo "despues de formatea fecha para db";
    echo "FI: ".$fi;
    echo"<br>";
    echo "FF: ".$ff;
    if ($totaldias>$diasderecho && 0==strcmp($tipopermiso,"Vacaciones"))
    {
        $_SESSION["accion"]=1;
        if($idusuarioageno!=""){
                $idusuario=$idusuariooriginal;
            }  
    }
    
    else
        {
        if($cruzaFechas!=1){
        $sql="BEGIN VAC_PRC_ALTASOLICITUD(:idu,:fi,:ff,:totaldias,:iddepartamento,:tipopermiso,:opcionsueldo,:comentario,:error,:idsolicito,:comentarioPreestablecido) ;END;";
        echo "<br>Para el alta de la solicitud";
        echo "idusuario ".$idusuario."<br>";
        echo "fi ".$fi."<br>";
        echo "ff ".$ff."<br>";
        echo "totaldias ".$totaldias."<br>";
        echo "iddepartamento ".$iddepartamento."<br>";
        echo "tipopermiso ".$tipopermiso."<br>";
        echo "opcionsueldo ".$opcionsueldo."<br>";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':idu',$idusuario,32);
        oci_bind_by_name($stmt,':fi',$fi,32);
        oci_bind_by_name($stmt,':ff',$ff,32);
        //oci_bind_by_name($stmt,':totaldias',$totaldias,32);
        oci_bind_by_name($stmt,':totaldias',$diferenciaDias,32);
        oci_bind_by_name($stmt,':iddepartamento',$iddepartamento,32);
        oci_bind_by_name($stmt,':tipopermiso',$tipopermiso,32);
        oci_bind_by_name($stmt,':opcionsueldo',$opcionsueldo,32);
        oci_bind_by_name($stmt,':comentario',$comentario);
        oci_bind_by_name($stmt,':comentarioPreestablecido',$comentarioPreesta);
        oci_bind_by_name($stmt,':error',$errorProcedure,32);
        oci_bind_by_name($stmt,':idsolicito',$_SESSION["idusuario"],32);
        
        $result=oci_execute($stmt);
        
        if ($result) {
            if($errorProcedure=='' && $tipopermiso != "Horas extras"){
            $_SESSION["accion"]=2;
            }
            else if($errorProcedure=='error cumpleaños'){
            $_SESSION["accion"]=6;
            }
            else if($errorProcedure=='error ausencia'){
            $_SESSION["accion"]=7;
            }
            else if($errorProcedure=='error horasExtras'){
            $_SESSION["accion"]=8;
            }
            if($idusuarioageno!=""){
                $idusuario=$idusuariooriginal;
            }  
            
        }
        else
            {
                $_SESSION["accion"]=3;
                if($idusuarioageno!=""){
                $idusuario=$idusuariooriginal;
            }  
        }
        }
        else{
          $_SESSION["accion"]=5;
        }
    }
    $e = oci_error($stmt);
    echo $e['message'];
    oci_close($conn);
    
    header('Location:main.php');
}
