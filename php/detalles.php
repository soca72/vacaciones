<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]!=""){
    $idusuarioLogueado=$_SESSION["idusuario"];
    $idusuarioData=$_SESSION["idusuariodata"];
}
else{
header("Location:../index.php");
}
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 4/10/16
 * Time: 06:27 PM
 */
$idusuario=$_POST['idusuario'];
$diaslaborados=$_POST['diaslaborados'];
//$iddepartamento=$_POST['iddepartamento'];
$idsolicitud=$_POST['idsolicitud'];
$origen=$_POST['origen'];
$priv=1;
//echo "idsolicitud".$idsolicitud;
include "conexion.php";
include "funciones.php";
$impresor=new impresor;
$usuario=new usuario;
$data=explode("|",$usuario->informaciongeneralmain($idusuario,$conn));
$nombre=$data[0];
$iddepartamento=$data[1];
$nombredepartamento=$data[2];
$puesto=$data[3];
$nomuser=$_SESSION["nomuser"];
$r= $data[4];
if(!$r)
    {
        echo "Algo ocurrio, porfavor inténtelo más tarde";
    }
    else {


        $sql='BEGIN VAC_PRC_DIASTOMADOS_ANTIGUEDAD(:idu,:totald,:ant);END;';
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':idu',$idusuario,32);
        oci_bind_by_name($stmt,':totald',$totaldias,32);
        oci_bind_by_name($stmt,':ant',$antiguedad,32);
        $r=oci_execute($stmt);
        if ($antiguedad>16) {
            $aux = $antiguedad;
            $antiguedad = 16;
            $old = true;
        }
        if(!$totaldias)
          $totaldias=0;


        $sql="BEGIN VAC_PRC_FECHAREFERENCIA(:idu,:fecharef);END;";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':idu',$idusuario,32);
        oci_bind_by_name($stmt,':fecharef',$fechareferencia,32);
        oci_execute($stmt);
        if($fechareferencia>'2011-03-31'){
              $diasex=20;
          }
        else{
            $diasex=25;
        }
        $sql="BEGIN VAC_PRC_DIASDERECHO(:antiguedad,:fechareferencia,:diasderecho); END;";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':antiguedad',$antiguedad,32);
        oci_bind_by_name($stmt,':fechareferencia',$fechareferencia,32);
        oci_bind_by_name($stmt,':diasderecho',$diasderecho,32);
        oci_execute($stmt);
        if ($old){
            for ($i=16; $i < $aux ; $i++){
                $diasderecho+=$diasex;
            }
            $antiguedad=$aux;
        }
        $sql="BEGIN VAC_PRC_revisar_fi_dl_pr(:idu,:fi,:diaslaborados);end;";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':idu',$idusuario,32);
        oci_bind_by_name($stmt,':fi',$fechaingreso,32);
        oci_bind_by_name($stmt,':diaslaborados',$diaslaborados,32);
        oci_execute($stmt);
        $diasderecho-=$totaldias;
        
$cursor=oci_new_cursor($conn);


echo "<html>

        <head>
          <title>
            Vacaciones
          </title>
      ";
$impresor->estilosbarranavegacion();
                $impresor->imprimesources();
echo "

<style>


</style>
        <style>
         /* unvisited link */
         
     body {
  margin: 2;
  padding: 2;
  border: none;
  }

a, a {
    color: white;
    padding: 8px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size:12px;
}

 
 


 td , th {
  
    text-align: center;
}

  /*Esto cambia el color que toman las filas de las tablas cuando se pasa el cursor por arriba de ellas*/
            .table-hover tbody tr:hover,
            .table-hover tbody tr:hover td,
            .table-hover tbody tr:hover th{
                background:#ff8000 !important;
                color:black !important;
            }
            .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td  {
                vertical-align: middle;
              }
        </style>








        <script>
        function main()
          {
              $('#forminfo').attr('action','main.php');
              $('#mandarinfo').click();
          }
        function versolicitud()
          {
              $('#forminfo').attr('action','estadovacaciones.php');
              $('#mandarinfo').click();
          }
          function administrarsolicitudes()
            {
              $('#forminfo').attr('action','administrar.php');
              $('#mandarinfo').click();
            }
        </script>
        <script>
        $(document).ready(function()
        {
    $('[data-toggle=\'tooltip\']').tooltip();
      });
        </script>
            <script type='text/javascript' src='../js/tablesorter/jquery.tablesorter.min.js'></script>
            <script type='text/javascript' src='../js/tablesorter/jquery.tablesorter.min.js'></script>
            <script type='text/javascript' src='../js/tablesorter/jquery.tablesorter.widgets.js'></script>
        </head>
        <body>
        ";
 


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
        $auximp=0; //
        while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
            $permisos[$i] = $row[0];
          
                  $permisosaimprimir[$auximp]=$row[0];
                  $auximp++;
              
//              ECHO $row[0];
            $i++;
        }


       
        echo "<div class='panel panel-primary'>
                <div class='panel-heading' style='max-height: 72;'> 
                  <!--Información del asociado  -->
                  <table class='table'>
                <tr>";
                    opciones($permisosaimprimir,5,"");

                  echo "
                    
                 <td width='".$width."%' style='text-align: right'><a href=\"\">".$nomuser."</a></td>
                    <td width='1%'> 
                          <a href='cerrarsesion.php'>
                            <span class='glyphicon glyphicon-log-out'></span>
                          </a>
                    </td>
                  </tr>
                  </table>
                </div>
                <div class='panel-body'>
                          <table>
                <tr>
";
   echo "<td style='text-align: center; ' width='40%'> Fecha de ingreso laboral  : <font color='OrangeRed'>".$fechaingreso ."</font></td>";
            echo "<td style='text-align: center; ' width='10%' > Antiguedad  : <font color='OrangeRed'>".$antiguedad." años"."</font></td>";
            echo "<td style='text-align: center; ' width='40%'> Dias por tomar  :  <font color='OrangeRed'>".$diasderecho."</font></td>";
        echo "
        </tr>
        </table>
</div>
</div>
";
$sql='BEGIN VAC_PRC_HISTORIALSOLICITUD(:idsol,:historial); END;';
$stmt=oci_parse($conn,$sql);
oci_bind_by_name($stmt,':idsol',$idsolicitud,32);
oci_bind_by_name($stmt,':historial',$cursor, -1, OCI_B_CURSOR);
oci_execute($stmt);
oci_execute($cursor);
echo '
<br>
<div class="alert alert-info">
  <strong>Solicitud : </strong> '.$idsolicitud.'
</div>
';

echo "<div class='table-responsive'>";
echo "<table id='tabla_solicitudes' class='table table-bordered table-hover table-condensed tableadmin'>";
echo "<thead><tr>";
$impresor->cabsolituddetalle();
$comentarios="";
echo "

</thead></tr>";
while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false)
{
    $count=0;
    while($count<21){
        if($row[$count]=="")$row[$count]='-';
            $count++;
    }
    $accion="Alta";
    $comentario=$row[20];
    if($row[19]=="au"){$accion="Aprobación";}
    if($row[19]=="den"){$accion="Cancelacion";}
    if($row[19]=="mod"){$accion="Modificacion";}
    
    echo "<tr id=tr".$row[0]." onclick=mostrarComentario(".$row[0].")>";
    echo "<td><b>$accion<b></td>";
    echo "<td><b>$row[16]<b></td>";
    echo "<td style='text-align: center;'>$row[2]</td>";
    echo "<td class='info' style='text-align: center;'>$row[3]</td>";
    echo "<td class='info' style='text-align: center;'>$row[4]</td>";
    echo "<td  style='text-align: center;'>$row[5]</td>";
    echo "<td  style='text-align: center;'>$row[6]</td>";
    echo "<td  style='text-align: center;'>$row[7]</td>";
    echo "<td  style='text-align: center;'>$row[8]</td>";
    echo $row[9]==-44?"<td class='success' style='text-align: center;'></td>":"<td class='success' style='text-align: center;'>$row[9]</td>";
    echo "<td  style='text-align: center;'>$row[10]</td>";
    echo $row[21]==-44?"<td class='success' style='text-align: center;'></td>":"<td class='success' style='text-align: center;'>$row[21]</td>";
    echo "<td  style='text-align: center;'>$row[22]</td>";
    echo $row[11]==-44?"<td class='success' style='text-align: center;'></td>":"<td class='success' style='text-align: center;'>$row[11]</td>";
    echo "<td  style='text-align: center;'>$row[12]</td>";
    echo "<td  style='text-align: center;'>$row[20]</td>";
    echo $row[15] == "CCC"?"<td>N/A</td>":($row[15] == "CONSUELDO"?"<td>Con sueldo</td>":($row[15] == "SINSUELDO"?"<td>Sin sueldo</td>":"<td>-</td>"));
    
    
                
               
    echo "</tr>";
    
//    echo "<tr>";
//    echo "<td  style='text-align: center;'>$row[0]</td>";
//    echo "<td  style='text-align: center;'>$row[1]</td>";
//    echo "<td  style='text-align: center;'>$row[2]</td>";
//    echo "<td  style='text-align: center;'>$row[3]</td>";
//    echo "<td  style='text-align: center;'>$row[4]</td>";
//    echo "<td  style='text-align: center;'>$row[5]</td>";
//    echo "<td  style='text-align: center;'>$row[6]</td>";
//    echo "<td  style='text-align: center;'>$row[7]</td>";
//    echo "<td  style='text-align: center;'>$row[8]</td>";
//    echo "<td  style='text-align: center;'>$row[9]</td>";
//    echo "<td  style='text-align: center;'>$row[10]</td>";
//    echo "<td  style='text-align: center;'>$row[11]</td>";
//    echo "<td  style='text-align: center;'>$row[12]</td>";
//    echo "<td  style='text-align: center;'>$row[13]</td>";
//    echo "<td  style='text-align: center;'>$row[14]</td>";
//    echo "<td  style='text-align: center;'>$row[15]</td>";
//    echo "<td  style='text-align: center;'>$row[16]</td>";
//    echo "<td  style='text-align: center;'>$row[17]</td>";
//    echo "<td  style='text-align: center;'>$row[18]</td>";
//    echo "<td  style='text-align: center;'>$row[19]</td>";
//    echo "<td  style='text-align: center;'>$row[20]</td>";
//   
//    
//    
//    
//    
//    echo "</tr>";

    $comentarios = $comentarios."<tr class='comentario' id='comentario".$row[0]."' hidden>
                            <td colspan='100%'>Comentario:".$row[19]."</td>
                            </tr>";


    }
echo $comentarios;
echo "</table>";
echo "<table id=tblComentarios>".$comentarios."</table>";
echo "
    
</div>
";

        
        
if($origen=='revisar')
{
    echo "<form id='forminfo' method='POST' action='administrar.php'>
      <input type=hidden name=idusuario value=$idusuario>
      <input type=hidden name=idsolicitud value=$idsolicitud>
      <input type=submit id='mandarinfo' value='Regresar' hidden>
      </form>";
}
else {
    echo "<form id='forminfo' method='POST' action='estadovacaciones.php'>
      <input type=hidden name=idusuario value=$idusuario>
      <input type=hidden name=iddepartamento value=$iddepartamento>
      <input type=hidden name=diaslaborados value=$diaslaborados>
      <input type=submit id='mandarinfo' value='Regresar'>
      </form>";
}
echo "<script>
     $('#tabla_solicitudes').tablesorter();
    function mostrarComentario(idsolicitud){
    if(!$('#tabla_solicitudes').find('#comentario'+idsolicitud).length > 0 ){
       $('#tabla_solicitudes').find('.comentario').remove();
       $('#tr'+idsolicitud).after($('#comentario'+idsolicitud).clone()); 
       $('#tabla_solicitudes').find('.comentario').show();
    }
    else{
        $('#tabla_solicitudes').find('.comentario').remove();
    }
    
}</script>";
}
oci_close($conn);

