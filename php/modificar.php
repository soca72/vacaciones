<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
$idusuario=$_POST['idusu'];
$idusuariomod=$_POST['idusuario'];
$iddepartamento=$_POST['iddepartamento'];
$idsolicitud=$_POST['idsolicitud'];
$totaldias=$_POST['diferenciaDias'];
echo $comentario=$_POST['comentario'];
echo "<br>".$diaslaborados=$_POST['diaslaborados'];
echo "<br>FI: ".$fi=$_POST['fi']." ".$_POST['hi'];
echo "<br>FF: ".$ff=$_POST['ff']." ".$_POST['hf'];
$fiAnt=$_POST['fiAnt'];
$ffAnt=$_POST['ffAnt'];
$hi=$_POST['hi'];
$hf=$_POST['hf'];
$idusu=$_POST['idusu'];
echo "<br>".$tp=$_POST['tp'];
echo "<br>".$os=$_POST['os'];
include "conexion.php";
include "funciones.php";
$fechas=new fechas;
$accion="mod";
$fiComparar= str_replace("/", "-", $_POST['fi']);
$ffComparar= str_replace("/", "-", $_POST['ff']);
$cruzaFechas=0;
echo "Session: ".$_SESSION["idusuario"];


//Verificar con fechas anteriores
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
            $periodoAnterior=$fechas->fechascalendario('L.MA.MI.J.V.S',$fiAnt,$ffAnt);
            //echo "<br><br><br>PERIODO DE ".$row[0]." HASTA ".$row[1]."<br><br><br>";
            $z=0;
         while($periodoComparar[$z]){   
             $a=0;
            while ($periodo[$a]) {
                if(($periodoComparar[$z]==$periodo[$a]  || $periodoComparar[$z]==$periodo[$a]) && !in_array($periodoComparar[$z],$periodoAnterior)){
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
//Fin de la comparacion con fechas de otras solicitudes 








$sql="BEGIN VAC_PRC_DIASTOMADOS_ANTIGUEDAD(:idu ,:totald,:ant );END;";
$stmt=oci_parse($conn,$sql);
oci_bind_by_name($stmt,":idu",$idusuario,32);
oci_bind_by_name($stmt,":totald",$diastomados,32);
oci_bind_by_name($stmt,":ant",$antiguedad,32);
oci_execute($stmt);


$sql="BEGIN VAC_PRC_fechareferencia(:idu,:fecharef);END;";
$stmt=oci_parse($conn,$sql);
oci_bind_by_name($stmt,":idu",$idusuario,32);
oci_bind_by_name($stmt,":fecharef",$fechareferencia,32);
oci_execute($stmt);
$fechareferencia;
$fechareferencia=$fechas->formateafecha($fechareferencia);

if($fechareferencia>'2011-03-31')
{
    $diasex=20;
}
else
{
    $diasex=25;
}
if ($antiguedad>16)
{
    $aux=$antiguedad;
    $antiguedad=16;
    $old=true;
}
$fechareferencia=$fechas->formateafechaparadb($fechareferencia);
$sql="BEGIN VAC_PRC_DIASDERECHO(:antiguedad,:fechareferencia,:diasderecho);END;";
$stmt=oci_parse($conn,$sql);
oci_bind_by_name($stmt,":antiguedad",$antiguedad,32);
oci_bind_by_name($stmt,":fechareferencia",$fechareferencia,32);
oci_bind_by_name($stmt,":diasderecho",$diasderecho,32);
oci_execute($stmt);



if ($old)
{
    for ($i=16; $i < $aux ; $i++)
    {
        $diasderecho+=$diasex;
    }
    $antiguedad=$aux;
}
$diasderecho-=$diastomados;
//$fi=$fechas->formateafechaparadb($fi);
//$ff=$fechas->formateafechaparadb($ff);
if ($totaldias>$diasderecho && 0==strcmp($tp,"Vacaciones"))
{
  header('Location:administrar.php');
}
else if($cruzaFechas!=1){
    $sql="BEGIN VAC_PRC_modificarvacaciones(:idsol,:fi,:ff,:idusuario,:td,:tp,:os,:comentario2,:idsolicito);END;";
    echo "<br>Parametros prc<br>";
    echo "<br>".$idsolicitud;
    echo "<br>".$fi;
    echo "<br>".$ff;
    echo "<br>".$idusuariomod;
    echo "<br>".$accion;
    echo "<br>".$totaldias;
    echo "<br>".$tp;
    echo "<br>".$os;
    echo "<br>".$comentario;
    echo "<br>".$_SESSION["idusuario"];
    
    
    $stmt=oci_parse($conn,$sql);
    oci_bind_by_name($stmt,":idsol",$idsolicitud,32);
    oci_bind_by_name($stmt,":fi",$fi,32);
    oci_bind_by_name($stmt,":ff",$ff,32);
    oci_bind_by_name($stmt,":idusuario",$idusuariomod,32);
    //oci_bind_by_name($stmt,":accion",$accion,32);
    oci_bind_by_name($stmt,":td",$totaldias,32);
    oci_bind_by_name($stmt,":tp",$tp,32);
    oci_bind_by_name($stmt,":os",$os,32);
    oci_bind_by_name($stmt,":comentario2",$comentario);
    oci_bind_by_name($stmt,":idsolicito",$_SESSION["idusuario"],32);
    $r=oci_execute($stmt);
    
       /*
    $result=$db->query(" call modificarvacaciones($idsolicitud,'$fi','$ff',$idusuario,'$accion',$totaldias); ");
       */


 
    if ($r)
    {
        echo "Guardado";
        $_SESSION['mensaje']="mc";
    }
    else
    {
        $e = oci_error($stmt);
        echo "<br>error".$e['message'];
        $_SESSION['mensaje']="e";
    }
    
}
else{
    echo "<br>".$_SESSION['mensaje']="cruza";
    
}
header('Location:administrar.php');
