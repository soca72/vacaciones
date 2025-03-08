<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]!=""){
    $idusuario=$_SESSION["idusuario"];
    $idusuarioData=$_SESSION["idusuariodata"];
}
else{
header("Location:../index.php");
}
/*
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 4/10/16
 * Time: 04:26 PM
*/
date_default_timezone_set('America/Mexico_City');

//ini_set('display_errors', 1);
//error_reporting(E_ALL ^ E_NOTICE);
$splitFechaDia=array();
$splitFechaMes=array();
$splitFechaAnio=array();
$arreglosaverificar=array();
//$diasLaborables=$_SESSION['diasLaborables'];
//$idusuario=$_POST['idusuario'];
if($_POST['idHistorico']!=null && $_POST['idHistorico']!="")$idu=$_POST['idHistorico'];
else $idu=$idusuario;
$diaslaborados=$_POST['diaslaborados'];
$nomuser=$_SESSION["nomuser"];
$aniof= $_POST['anio'];
if(!$aniof)
{
    $aniof=date('Y');
}
//echo $aniof;

include "conexion.php";
include "funciones.php";
include  "permisocal.php";
$usuario = new usuario;
$data=explode("|",$usuario->informaciongeneralmain($idusuario,$conn));
$nombre=$data[0];
$iddepartamento=$data[1];
$nombredepartamento=$data[2];
$puesto=$data[3];
$r= $data[4];
$impresor=new impresor;
$fechas=new fechas;
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
if(!$r)
{
  echo "Algo ocurrió, intentelo mas tarde porfavor";
}
else
{
    

                                          
               $sql="BEGIN VAC_PRC_CONTARDIASRESTANTES(:idu ,:nombre,:apaterno,:amaterno,:departamento,:antiguedad,:fechaIngreso,:fechaCumple,:dias,:diasLaborables,:idud,:solPendientes,:direccionOut,:celularOut,:emailOut,:usuarioOut,:claveOut); END;";               
               $stmt=oci_parse($conn,$sql);
               oci_bind_by_name($stmt, ":idu",$idu,32);
               oci_bind_by_name($stmt,':nombre',$nombreq,32);
               oci_bind_by_name($stmt,':apaterno',$apaternoq,32);
               oci_bind_by_name($stmt,':amaterno',$amaternoq,32);
               oci_bind_by_name($stmt,':departamento',$departamento,32);
               oci_bind_by_name($stmt,':fechaIngreso',$fechaingreso,32);
               oci_bind_by_name($stmt,':fechaCumple',$fechaCumple,32);
               oci_bind_by_name($stmt,':antiguedad',$antiguedad,32);
               oci_bind_by_name($stmt,':dias',$dias,32);
               oci_bind_by_name($stmt,':diasLaborables',$diasLaborables,32);
               oci_bind_by_name($stmt,':idud',$idud,32);
               oci_bind_by_name($stmt,':solPendientes',$solPendientes,32);
               oci_bind_by_name($stmt,':direccionOut',$direccionq,200);
               oci_bind_by_name($stmt,':celularOut',$celularq,32);
               oci_bind_by_name($stmt,':emailOut',$emailq,50);
               oci_bind_by_name($stmt,':usuarioOut',$usuarioq,32);
               oci_bind_by_name($stmt,':claveOut',$claveq,32);
               
               $r=oci_execute($stmt);

echo "<html>
        <head>
        <title>
            Vacaciones
          </title>
        
        ";

        $impresor->estilosbarranavegacion();

        echo "
          
          
          <style>
html *{

font-size:102% !important
}
  

 td {
    text-align: center;
}

.panel {
    margin-bottom: 0px;
}
  #rcorners1 {
border-radius: 15px;
}

.panel-body table {
font-size:12px;
}

.table {
    border-bottom:0px !important;
}
.table th, .table td {
    border: 1px !important;
}
.fixed-table-container {
    border:0px !important;
}
#modalComentarios #modalComentariosTitle{
    display: inline-block;
    font-size: 17px !important;
    font-weight: bold;
}

[class*='comentario']{
    cursor: pointer;
}
.tablaColores td{
    width: 11%;
}
          </style>
      ";

$impresor->imprimesources();
   if(in_array(4, $permisos))
                  {
        echo "
        <script type='application/javascript' src='../js/busquedadinamica.js'></script>
        <link href='../css/select2.min.css' rel='stylesheet'/>
        <script src='../js/select2.min.js'></script>";
        
        $busqueda="<select id='busqueda'class='select2' style='width:500px;' onchange=buscar(this.value)><option>".$apaternoq." ".$amaternoq." ".$nombreq."</option></select></td>";
                  }
        else{
            $busqueda="<input type='text' id='busqueda' class='txtBusqueda' readonly=true value='".$apaternoq." ".$amaternoq." ".$nombreq."'>";
        }
echo "
      <script>
      var comentariosJSON = {};
      var fechasJSON = {};
            function administraraccesos()
            {
                $('#forminfo').attr('action','accesos.php');
                $('#mandarinfo').click();
            }
        function main()
          {
              $('#forminfo').attr('action','main.php');
              $('#mandarinfo').click();
          }
          function administrarsolicitudes()
            {
              $('#forminfo').attr('action','administrar.php');
              $('#mandarinfo').click();
            }
function filtraporanio()
{
                document.getElementById('ianio').value=document.getElementById('year').value;
                $('#forminfo').attr('action','estadovacaciones.php');
              $('#mandarinfo').click();

}
        </script>
        </head>
        <body>
        




<div class='modal fade' id='modalComentarios' tabindex='-1' role='dialog' aria-labelledby='modalComentariosTitle' aria-hidden='true'>
  <div class='modal-dialog modal-dialog-centered' role='document'>
    <div class='modal-content'>
      <div class='modal-header'>
        <h5 class='modal-title' id='modalComentariosTitle'></h5>
        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
          <span aria-hidden='true'>&times;</span>
        </button>
      </div>
      <div class='modal-body'>
        
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cerrar</button>
      </div>
    </div>
  </div>
</div>







        ";

        echo "<div class='panel panel-primary' style='margin-bottom: 0px;'>
                <div class='panel-heading' style='max-height: 72;'> 
                
                <table class='table'>
                <tr>";
                    opciones($permisosaimprimir,3,"");

                  echo $nomuser."</a></td>
                    <td width='1%'> 
                          <a href='cerrarsesion.php'>
                            <span class='glyphicon glyphicon-log-out'></span>
                          </a>
                    </td>
                  </tr>
                  </table>
                </div>

                <div class='panel-body'>
                
                <table class='tablaInfo'>
                <tr>
                        ";
     
            echo "<td style='text-align: center; font-size: 103%' width='25%'>$busqueda";
            echo "<td style='text-align: center; font-size: 103%' width='25%'>Fecha de ingreso laboral  : <font id='fontFechaIngreso' color='OrangeRed'>".$fechaingreso ."</font></td>";
            echo "<td style='text-align: center; font-size: 103%' width='25%' > Antiguedad  : <font id='fontAntiguedad' color='OrangeRed'>".$antiguedad." años"."</font></td>";
            echo "<td style='text-align: center; font-size: 103%' width='25%'> Dias por tomar  :  <font id='fontDiasD' color='OrangeRed'>".$dias."</font></td>";
            
if(in_array(4, $permisos))
                  {

                echo "
                <script>
                  $('.select2').select2({
                        params: { // extra parameters that will be passed to ajax
        contentType: 'application/json; charset=utf-8',
   },
   minimumInputLength: 4,
   delay: 100,
   language: {
                        inputTooShort: function () {
                        return 'Ingresa minimo 4 carácteres...';
                        }
                        },
  ajax: {
    url: 'livesearch2.php',
    dataType: 'json',
    data: function (params) {var query = {q: params.term,idu: $idusuarioData,tabla:'Autoriza'}
      return query;
    },
    processResults: function (data) {
                                  //alert(data);
                                // Tranforms the top-level key of the response object from 'items' to 'results'
                                return {
                                  results: data
                                };
                              }
   
  }
});


function buscar(value){
//hello(value);
document.getElementById('idHistorico').value=value;
document.getElementById('formHistorico').submit();
}
function onload1(){
showdata($idusuario)
};
</script>
<form id='formHistorico' name='formHistorico' action='estadovacaciones.php' method='POST'>
<input type='hidden' id='idHistorico' name='idHistorico'></input>
<input type='hidden' id='idusuariocambia'> 
<input type='hidden' id='datausuario'> 
<input type='hidden' id='diasderechocambia'> 
<input type='hidden' id='datausuario_diasLab'>
</form>";

                  }            
            
echo "
         </tr>
        </table>
</div>
</div>
  <table class='table tablaColores'>
        <tr>
                       <td  class='ausencia'>Ausencia</td>
                       <td  class='birthday'>Cumpleaños</td>
                       <td  class='descanso'>Descanso</td>
                       <td  class='faltainjustificada'>Falta Injustificada</td>
                       <td  class='horasextras'>Horas Extras</td>
                       <td  class='incapacidad'>Incapacidad</td>
                       <td  class='permiso'>Permiso</td>
                       <td  class='pendiente'>Por Autorizar</td>
                       <td  class='vacaciones'>Vacaciones</td>
                    </tr>
        </table>

<div style='text-align:center'>
<select id='year' onchange='javascript:filtraporanio()' >

</div>
<script>
var start = 2019;
var end = new Date().getFullYear();
var options = '';
for(var year = start ; year <=end+1; year++){
  options += '<option value='+ year + '>'+ year + '</option>';
}

document.getElementById('year').innerHTML = options;
</script>

</select>


                   <form id='formdetalle' method='POST' action='detalles.php'>
                  <input type=hidden name=idusuario value=$idusuario>
                  <input type=hidden name=iddepartamento value=$iddepartamento>
                  <input type=hidden name='idsolicitud' id='idsol' value=>
                  <input type=submit id='btnformdetalle'  hidden>
                  </form>


        <form id='forminfo' method='POST'>
              <input type=hidden name=idHistorico value=$idu>
              <input type=hidden name=diaslaborados value=$diaslaborados>
              <input type=submit id='mandarinfo' hidden>
              <input type=hidden name=anio id='ianio' value='$aniof'>
              </form>
";
$sql="BEGIN VAC_PRC_VERSOLICITUDPORUSUARIO(:idu, :anio,:solicitudes); END;";///este procedure regresara las solicitudes pintandolos en los calendarios
$cursor = oci_new_cursor($conn);
$stmt= oci_parse($conn, $sql);
oci_bind_by_name($stmt,':idu',$idu,32);
oci_bind_by_name($stmt,':anio',$aniof,32);
oci_bind_by_name($stmt, ":solicitudes", $cursor, -1, OCI_B_CURSOR);
$r=oci_execute($stmt);
$r=oci_execute($cursor);
$calendario=0;
$fechasfinal="";
$fechasinicio=array();
$fechasfinal=array();
$colorcalendario= array();
$solicitudes= array();
$tiposdepermisos= array();
$icolores=0;
$Permisos=array();
$tipoAccion=array();
$count=0;
//$diaslaborados='L.MA.MI.J.V.S';
while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false)
{
    
    $calendario++;
    $permiso= new Permiso();
    $fi=$fechas->formateafecha2($row[3]);
    $ff=$fechas->formateafecha2($row[4]);
    $permiso->fechainicial=$fi;
    $permiso->fechafinal=$ff;
    $permiso->fechainicial;
    $permiso->fechafinal;
    $provdata=explode("-",$fi);
    $provdata2=explode("-",$ff);
    $permiso->mes=$provdata[1];
    $permiso->dia=$provdata[2];
    $permiso->mes2=$provdata2[1];
    $permiso->dia2=$provdata2[2];
    $permiso->tipopermiso=$row[14];

    if(strpos($row[14], "lea"))//Revisamos si el tipo de permiso es un cumpleaños, y le asginamos un nombre sin ñ
    {
      $permiso->tipopermiso="birthday";
    }
    if($row[14] == "Horas extras")$permiso->periodo=$fechas->fechascalendarioCumple($diaslaborados,$fi,$ff);
    else $permiso->periodo=$fechas->fechascalendario($diaslaborados,$fi,$ff);
    $permiso->idsolicitud=$row[0];
    $permiso->comentario=$row[23];
    array_push($fechasinicio, $fi);
    array_push($fechasfinal, $ff);
    array_push($tiposdepermisos,$row[14]);
    array_push($Permisos, $permiso);
    array_push($tipoAccion, $row[18]);
    
   /*
    echo "<script>alert('inicio $fechasinicio[$count]    final.$fechasfinal[$count]  Tipo: $tiposdepermisos[$count] Dias Laborables: $diasLaborables')</script>";
     list($splitFechaDia[$count],$splitFechaMes[$count],$splitFechaAnio[$count])=split('-',$fechasinicio[$count]);
    echo "<script>alert('$splitFechaDia[$count],$splitFechaMes[$count],$splitFechaAnio[$count]')</script>";
    */
    
    
    
}
$calendario++;
    $permiso= new Permiso();
    $fi=$fechas->formateafechaCumple($fechaCumple,$aniof);
    $ff=$fechas->formateafechaCumple($fechaCumple,$aniof);
    $periodoFechaCumple = array();
    array_push($periodoFechaCumple,date('d-m-Y', strtotime($fi)));
    $permiso->fechainicial=$fi;
    $permiso->fechafinal=$ff;
    $permiso->fechainicial;
    $permiso->fechafinal;
    $provdata=explode("-",$fi);
    $provdata2=explode("-",$ff);
    $permiso->mes=$provdata[1];
    $permiso->dia=$provdata[2];
    $permiso->mes2=$provdata2[1];
    $permiso->dia2=$provdata2[2];
    $permiso->tipopermiso="birthday";
    $permiso->periodo= $periodoFechaCumple;
    $permiso->idsolicitud=0;
    $permiso->comentario="";
    array_push($fechasinicio, $fi);
    array_push($fechasfinal, $ff);
    array_push($tiposdepermisos,"birthday");
    array_push($Permisos, $permiso);
    array_push($tipoAccion, "au");







  $tienepermiso=false;
  $meses=array("","","","","","","","","","","","","");
  $permisosmeses=array();
  echo "<script>";
  echo "
  var diasDisable=[];
   var fechasDisable=[];
   var titleString=[];
   var fechasDisableFormat=[];
  ";
$sql="SELECT * FROM VAC_DIAS_NO_HABILES Order By fecha asc";///este procedure regresara las solicitudes pintandolos en los calendarios
$cursor = oci_new_cursor($conn);
$stmt= oci_parse($conn, $sql);
oci_execute($stmt);
while (($row = oci_fetch_array($stmt, OCI_BOTH)) != false)
{   
    echo "fechasDisable.push(new Date('".$row[1]."'));titleString.push('$row[2]');";
}

echo"
diasDisable=diasNoLaborables('$diasLaborables');
function diasNoLaborables(dias){
var diasSplit=dias.split(',');
var count=1;
var diasNoHabiles = [];
while(count<=7){
if(!diasSplit.includes(count.toString())){
diasNoHabiles.push(count);
}
count++;
}
return diasNoHabiles;
}
  </script>"; $a=0;
      $contpermisosadicionales=0;
  for($i=0;$i<12;$i++) //Genera los scripts para los 12 calendarios
  {
      
      $a=0;
      $contpermisosadicionales=0;
    while ($Permisos[$a]) { //Recorremos todos los permisos
       //EN caso de que un mes tenga mas de un permiso, este contador entra en accion
        
      if(($Permisos[$a]->mes2==$i+1 || $Permisos[$a]->mes==$i+1) || ($Permisos[$a]->mes2>$i+1 && $Permisos[$a]->mes<$i+1)) //Los calendarios jquery empiezan desde el mes 0
      //if(in_array(i+1,$fechas->mesesPermiso($Permisos[$a]->mes,$Permisos[$a]->mes2)))
          {
        $period=$Permisos[$a]->periodo;//Si el mes en el que estamos es igual a el mes del permiso que estamos revisando

                    echo  "<script>var fechas$i".$contpermisosadicionales." =[";   //Esto crea un array con el identificador del calendario junto con incrementador
                        //$meses[$i]=$meses[$i]."fechas$i".$contpermisosadicionales."/".$Permisos[$a]->tipopermiso."/".$Permisos[$a]->idsolicitud."|";
                       $meses[$i]=$meses[$i]."$fechasinicio[$a]/$fechasfinal[$a]/".$Permisos[$a]->tipopermiso."/".$Permisos[$a]->idsolicitud."/$tipoAccion[$a]"."/".$Permisos[$a]->comentario."|";
                       
                        $contpermisosadicionales++;

              for ($b=0; $b < count($period); $b++)
              {
                  
                  if ($b==count($period)-1) {
                      echo "'$period[$b]'";
                  }
                  else
                  {
                      echo "'$period[$b]',";
                  }
              }
            echo "];
                       </script>";
            
                       $tienepermiso=true;
      }
      //else echo "<script>console.log(".sizeof($fechas->mesesPermiso($Permisos[$a]->mes,$Permisos[$a]->mes2)).");</script>";
      $a++;
    }

  echo "
   <script>
   var count=0; 
    
function blurcalendar(date)
  {
  var fechaenturno=$.datepicker.formatDate('dd-mm-yy', date );
   if($.inArray(fechaenturno,fechasDisableFormat)!=-1){
                              return [false,'Festivo',titleString[fechasDisableFormat.indexOf(fechaenturno)]];
                              ;
    }
    else{
        return [false,'blur',''];
        }
  }
    
  $( function() {
    $( '#datepicker$i' ).datepicker( { 
      defaultDate: new Date($aniof,$i,01),
      onSelect: diapresionado,
      disable:diasDisable,
    days: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
    daysShort: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
    daysMin: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
    months: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    monthsShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sept','Oct','Nov','Dic'],
                                 
";

if($tienepermiso) 
        echo "beforeShowDay: validafechas$i,";
  else
        echo "beforeShowDay: blurcalendar,";

echo "      
    });
  } );  
  ";
    if($meses[$i]!=''){
      $arreglosaverificar=explode("|",$meses[$i]);
  }   
      echo "
         
           while(fechasDisable[count]){
              fechasDisableFormat[count]=$.datepicker.formatDate('dd-mm-yy', new Date(fechasDisable[count]) );
               count++;
               }
               
          function validafechas$i(date)
          {
               var fechaenturno=$.datepicker.formatDate('dd-mm-yy', date );
                
              
                  ";
                  $aux=0;
                  
                  while (!is_null($arreglosaverificar[$aux])){
                    $arreglo=explode("/", $arreglosaverificar[$aux]);             
                    $aux=$aux+1;
                    $title = $arreglo[2];
                    if($arreglo[2] == "birthday") $title = "Cumpleaños";
                    
                    
                    if($arreglo[4]=="au"){
                        $clase=str_replace(' ', '', strtolower($arreglo[2]));
                    }
                    else $clase="pendiente";
                      echo "
                             
                             var fechaSplit=fechaenturno.split('-');
                             var fechaenturnoformat=new Date(fechaSplit[2]+'-'+fechaSplit[1]+'-'+fechaSplit[0]);
                             console.log(fechaenturno+'$fechas->diaMas($arreglo[0],$arreglo[1])');
                             if(($.inArray(fechaenturno,".$fechas->diaMas($arreglo[0],$arreglo[1]).")!=-1) && ($.inArray(fechaenturnoformat.getDay()+1,diasDisable)==-1 || '$arreglo[2]' == 'birthday' || '$arreglo[2]' == 'Horas extras' ) )
                             {
                               return [false,'$clase comentario$arreglo[3] ','$title'];
                             }
                             if('$arreglo[5]' != ''){
                              comentariosJSON.comentario$arreglo[3] = '$arreglo[5]';
                              fechasJSON.comentario$arreglo[3] = '$arreglo[0]' != '$arreglo[1]'?'$arreglo[2] ".str_replace('-','/',$arreglo[0])." - ".str_replace('-','/',$arreglo[1])."': '$arreglo[2] ".str_replace('-','/',$arreglo[0])."';
                             }
                        
                        
                      ";
                     $contadorTemp++;
                  }                    
                   echo"  
                            if($.inArray(fechaenturno,fechasDisableFormat)!=-1){
                              return [false,'Festivo',titleString[fechasDisableFormat.indexOf(fechaenturno)]];
                            }

                            
                            
                          return [false,'blur',''];
                        
                   }
                   

  </script>
";
 
$tienepermiso=false;
  }
  echo 
  "
</head>
<body >
  <table class='table table-bordered'>
  <tr>
 ";
for ($i=0; $i <12 ; $i++) { 
  echo "<td>";
  echo "<div id='datepicker$i' style='maxheight:30px'></div></td>";
  if($i==3 || $i==7)
    echo "</tr><tr>";
}
 echo "
 </tr>

 <style>
 div.ui-datepicker{
 font-size:35px;
}
 </style>
<script>

            function my_in_array(needle, haystack)
{
   var i=0;
   for(i=0;i<haystack.length;i++)
   {
    if(needle==haystack[i])
      return true;
   }

    return false;
}

                  function diapresionado(date,a)
                      {
                        mostrarComentario(extractIDComentario($(a.dpDiv).find('.ui-state-active').parent().attr('class')));
                        var splitfecha=date.split('/');
                        var fecha=splitfecha[0]+'-'+splitfecha[1]+'-'+splitfecha[2];
                        ";
                        

for ($i=0; $i < 12; $i++) 
          {
            # code...
                if($meses[$i])
                {
                  $mesfechas=explode("|",$meses[$i]);
                  $aux=0;
                  while ($mesfechas[$aux]) 
                    {
                        //echo "<br>".$mesfechas[0]."/////////////////////";
                        $temporal=explode("/", $mesfechas[$aux]);
                        $arreglo=$temporal[0];
                        $solicitudbusca=$temporal[3];

                        //$solicitudbusca=explode("/", $mesfechas[$aux][2]);
                        //echo $arreglo;
                        //echo $solicitudbusca."**********";

                         echo "
                         if (my_in_array(fecha,'$arreglo'))
                          {
                            
                            document.getElementById('idsol').value='$solicitudbusca';
                            document.getElementById('btnformdetalle').click();
                            return;
                          }
                      ";
                  
                      $aux++;
                    }
                  }
          }
    echo "
  }
  document.getElementById('year').value = $aniof;
      



function extractIDComentario(str){
    var index = str.indexOf('comentario');
    var indexSpace = str.indexOf(' ', index)!=-1?str.indexOf(' ', index):str.length;
    str.substr(index,indexSpace-index);
    return  str.substr(index,indexSpace-index);
}


function mostrarComentario(id){
    if(comentariosJSON[id]){
        console.log(fechasJSON);
        console.log(id);
        console.log(fechasJSON[id]);
        $('#modalComentarios .modal-header h5').html(fechasJSON[id]);
        $('#modalComentarios .modal-body').html(comentariosJSON[id]);
        $('#modalComentarios').modal('show');
    }
}


$(document).ready(function(){
    $('.ui-datepicker-unselectable').removeClass('ui-datepicker-unselectable').removeClass('ui-state-disabled');
    $(\"[class*='comentario']\").on('click',function(){
        mostrarComentario(extractIDComentario($(this).attr('class')));
    });
});
             </script>

</body>
</html>
";
oci_free_statement($stmt); // close procedure call
oci_free_statement($cursor); // close cursor
oci_close($conn);
}
?>
