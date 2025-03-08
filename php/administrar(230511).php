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

$diaslaborados=$_POST['diaslaborados'];
$filtrodepartamento=$_POST['departamento'];
$jefeareaorecursos=$_POST['jefeareaorecursos'];
$filtradoestado=$_POST['filtradoestado'];
$rowAnt="";
$diaslaborados=$_SESSION['diaslaborados'];
$iddepartamento=$_SESSION['iddepartamento'];
$nomuser=$_SESSION["nomuser"];
$mensaje=$_SESSION['mensaje'];
include 'funciones.php';
include 'conexion.php';
$impresor=new impresor;
$usuario=new usuario;
$filtrado=1;
$data=explode("|",$usuario->informaciongeneralmain($idusuario,$conn));
$nombre=$data[0];
$iddepartamento=$data[1];
$nombredepartamento=$data[2];
$puesto=$data[3];
$r= $data[4];
$pagina='administrar';
$comentarioSuperiores=$_POST['comentarioSuperiores'];


if($iddepartamento==29)
{$filtrado=0;}

   
echo "
<html>
<style>
.secondary{
    background-color: #C1B2FF;
}
tr{
    width:100%;
}

#tabla_solicitudes th{
    cursor:hand;
    border-bottom: black solid 1px;
}
#tabla_solicitudes td{
   border:none;
   border-bottom: black solid 1px ;

}
#tabla_solicitudes th:last-child{
   width:15px;
}
.tableDrop td{
text-align:left;
padding-left:10px;
}
.tableDrop a{
font-size:95%;
text-align:left;
}
html *{
font-size:102% !important
}
.picker__frame{
font-size:170% !important
}
.datepicker{
text-align:center;
}
.dropdown-menu{
left:25% !important;
}
   .dropdown-menu-administrar {
        right: 0;
        left: initial !important;
      }
  
        .centrar{
        margin:auto;
        }
        .espaciado{
            margin:15px;
        }
        .espaciado td:first-child{
            
            max-width: 500px;
        }
        .espaciado td{
            /*width:5%;*/
        }
        .espaciado td > .dropdown{
            margin-left: 15px;
            margin-right: 15px;
        }
        .espaciado td:nth-child(5){
            min-width: 400px;
        }
        
        .espaciado td:last-child{
            max-width: 400px;
            min-width: 200px;
        }
        
        .select2{
            text-align: center;
        }
        .centrar select{
          width:300px;  
          align:'center';
        }
        
        .comentario, .comentarioJefe, .comentarioRH, .comentarioGerente {
        
        font-size:11px !important;
        width:100%;
        
        /*background-color: #74A8EE !important;*/
        /*color: #F0FF00;*/
        //color:#0061E4;
        }
        .comentario:hover, .comentarioJefe:hover, .comentarioRH:hover, .comentarioGerente:hover{
        //background-color:white !important;
        /*background-color: #74A8EE !important;*/
        }
        .comentario td,.comentarioJefe td, .comentarioRH td, .comentarioGerente td{
        text-align:left;
        padding-top:15px !important;
        padding-bottom:15px !important;
        }
        .comentario, .comentarioJefe, .comentarioRH, .comentarioGerente{
            display: none;
            
        }
        .sinBorde td{
        border-bottom:none !important;
        /*background-color: #74A8EE !important;*/
        color: black;
        }
         #spin.modal {
                display:    none;
                position:   fixed;
                z-index:    1000;
                top:        0;
                left:       0;
                height:     100%;
                width:      100%;
                background: rgba( 255, 255, 255, .8 ) 
                    url('../images/progresoazul.gif')
                    50% 50% 
                    no-repeat;
            }
            body.loading {
                overflow: hidden;   
            }


            body.loading #spin.modal {
                display: block;
            }
            
            .divScrollTabla{
                overflow-y: auto;
                height: calc(100vh - 222px);
                padding: 10px;
            }
            .divBotonesAccion{
                display: flex;
                justify-content: flex-end;
                padding: 10px;
            }
            
            #btnCancelarSolicitudes, #btnAutorizarSolicitudes{
                font-size: 15px !important;
                margin-right: 15px;
            }
            
            #btnCancelarSolicitudes:focus, 
            #btnAutorizarSolicitudes:focus{
                outline: none;
            }
            


           /*Inicio estilo tabla*/
           #tabla_solicitudes > tbody td:first-child,#tabla_solicitudes > thead th:first-child,
           #tabla_solicitudes > tbody td:nth-child(2),#tabla_solicitudes > thead th:nth-child(2){
                max-width: 60px;
           }
           
           
           #tabla_solicitudes > tbody td:nth-child(4),#tabla_solicitudes > thead th:nth-child(4){
                max-width: 150px;
           }

           #tabla_solicitudes > tbody td:nth-child(7),#tabla_solicitudes > thead th:nth-child(7),
           #tabla_solicitudes > tbody td:nth-child(8),#tabla_solicitudes > thead th:nth-child(8){
                max-width: 50px;
           }
           

           
           #tabla_solicitudes > tbody td:nth-child(3),#tabla_solicitudes > thead th:nth-child(3),
           #tabla_solicitudes > tbody td:nth-child(5),#tabla_solicitudes > thead th:nth-child(5),
           #tabla_solicitudes > tbody td:nth-child(6),#tabla_solicitudes > thead th:nth-child(6),
           #tabla_solicitudes > tbody td:nth-child(11),#tabla_solicitudes > thead th:nth-child(11),
           #tabla_solicitudes > tbody td:nth-child(13),#tabla_solicitudes > thead th:nth-child(13){
                max-width: 50px;
           }
           
           #tabla_solicitudes > tbody td{
                
           }
           /*Fin estilo tabla*/
        </style>
            <head>
            <meta charset=\"utf-8\">
              <title>
                Vacaciones
              </title>
            ";
$impresor->imprimesources();
$impresor->estilosbarranavegacion();
echo "
        <script src='../js/jquery.redirect.js'></script>
        <script src='../js/funcionesadministrar.js'></script>
        <script src='../js/datepicker/jquery-3.3.1.min.js' type='text/javascript' ></script>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.css' id='theme_base'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.date.css' id='theme_date'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.time.css' id='theme_time'>
        <script src='../js/datepicker/lib/picker.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.date.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.time.js' type='text/javascript' ></script>
            <link href='../css/select2.min.css' rel='stylesheet'/>
            <script src='../js/select2.min.js'></script>
            <script type='text/javascript' src='../js/tablesorter/jquery.tablesorter.min.js'></script>
            <script type='text/javascript' src='../js/tablesorter/jquery.tablesorter.min.js'></script>
            <script type='text/javascript' src='../js/tablesorter/jquery.tablesorter.widgets.js'></script>
            <script src='../js/bootstrap5/bootstrap.min.js' ></script>
            <link href='../css/bootstrap5/bootstrap.min.css' rel='stylesheet'>
            <script>
              
            idusuario=".$idusuario.";".
            "iddepartamento=".$iddepartamento.";
            
            function administraraccesos()
            {
                $('#forminfo').attr('action','accesos.php');
                $('#mandarinfo').click();
            }


            function revisar(i)
              {
                var a=i;
                    $('#inforevision'+a).attr('action','revisar.php')

              }
            function versolicitud()
              {

                $('#forminfo').attr('action','estadovacaciones.php');
                $('#mandarinfo').click();
              }
            function main()
              {
                  $('#forminfo').attr('action','main.php')
                  $('#mandarinfo').click();
              }


 

              function filtrar(a)
                {
                  document.getElementById('dep').value=a;
                  $('#mandarinfo').click();
                }
                function filtradoporjefearea()
                  {
                    document.getElementById('jaor').value='ja';
                    $('#botonfiltrado').click();
                  }

                function filtradoporrecursosh()
                  {
                    document.getElementById('jaor').value='rh';
                    $('#botonfiltrado').click();
                  }
                  function filtradoporautorizacioncompleta()
                  {
                    document.getElementById('jaor').value='complete';
                    $('#botonfiltrado').click();
                  }
                  function filtradoporningunaautorizacion()
                  {
                    document.getElementById('jaor').value='noautorizada';
                    $('#botonfiltrado').click();
}


                


            </script>

            </head>
            ";
echo "<body onload='onload1();'>
    <div id='spin' class='modal'></div>
            <div class='panel panel-primary'>
                <div class='panel-heading' style='max-height: 72;'> 
                <table class='table'>
      ";
if ($mensaje=="ac") {
    echo "<script>alert('Aprobado con exito');
          </script>";
    $mensaje="";
}
if ($mensaje=="dc") {
    echo "<script>alert('Cancelado con exito');
          </script>";
    $mensaje="";
}
if ($mensaje=="mc") {
    echo "<script>alert('Modificado con exito');
          </script>";
    $mensaje="";
}
if ($mensaje=="e") {
    echo "<script>alert('Algo ocurrio, porfavor intentelo más tarde');
            </script>";
    $mensaje="";
}
if ($mensaje=="cruza") {
    echo "<script>alert('Las fechas coinciden con las de otro permiso');
            </script>";
    $mensaje="";
}



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
            $i++;
            echo "<script>
            agregapermisodeusaurio($row[0]);
            </script>";
        }
            $contceldas2=0;

                    opciones($permisosaimprimir,4," padding-top: 8px; ");
echo $nomuser."</a></td>
                    <td width='1%'> 
                          <a href='cerrarsesion.php'>
                            <span class='glyphicon glyphicon-log-out'></span>
                          </a>
                    </td>
                  </tr>
                  </table>
            </div>
            
            <div class='modal ' id='modalComentariosSuperiores' tabindex='-1' role='dialog' aria-labelledby='modalComentariosSuperioresLabel' aria-hidden='true'>
                <div class='modal-dialog ' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h1 class='modal-title' id='modalComentariosSuperioresLabel' >Nuevo titulo</h1>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'>
                                
                            </button>
                        </div>
                        <div class='modal-body'>
                            <textarea type='text' id='comentarioSuperiores' name='comentarioSuperiores' cols='50' rows='3' placeholder='Comentarios (Opcional)' ></textarea>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-primary modal-button' id='btncomentarioSuperiores'></button>
                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cerrar</button>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class='modal ' id='modalComentariosRH' role='dialog' aria-labelledby='modalComentariosRHLabel' aria-hidden='true'>
                <div class='modal-dialog ' role='document'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h1 class='modal-title' id='modalComentariosRHLabel' style='font-size:20px;'> Comentarios de Recursos Humanos </h1>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'>
                                
                            </button>
                        </div>
                        <div class='modal-body'>
                            <textarea type='text' id='comentarioRH' name='comentarioRH' cols='50' rows='3' placeholder='Comentarios de Recursos Humanos (Opcional)' ></textarea>
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-primary' id='aceptarComentarioRH'>Aceptar</button>
                            <button type='button' class='btn btn-danger' data-bs-dismiss='modal'>Cerrar</button>
                            
                        </div>
                    </div>
                </div>
            </div>
                        

            <!--table class='table table-bordered'>
            <tr>
            <td width='25%' style='background-color:#C1FFA9; height: 46px; padding-top: 15px'><b>Aprobadas</b></td>
            <td width='25%' style='background-color:#f2dede; padding-top: 15px'><b>Canceladas</b></td>
            <td width='25%' style='background-color:#FFF2AB; padding-top: 15px'><b>Pendientes</b></td>
            <td width='25%' style='background-color:#A9E3FF; padding-top: 15px'><b>Parcialmente Aprobadas</b></td>
            </tr>
            </table-->
            
            </div>
            
      ";


      //Panel para los filtros
      echo 
          "
                    <input type=hidden id=fechainicio name=fi disabled style='width:25%; max-height:30px' onchage='filtrafecha(this.value)'>
                 
               
                   <input type=hidden id=fechafinal name=ff disabled style='width:25% ; max-height:30px' onchage='filtrafecha(this.value)'>
         ";                                     //Barra de busqueda de usaurios
              //*******************************************************************************************************************************************
              //*******************************************************************************************************************************************
          
    
    
     
    
echo "<table class='centrar espaciado' style='width: calc(100% - 20px);'>
       <tr>
             <td>    <div class='' style='text-align: left;'>
                            <select id='selectusuarios' class='select2' onchange=filtrousuario(this.value,getElementById('chkTodos').value); data-live-search='true' placeholder='Nombre de usuario ...'>
                                  ";
                                          echo "<option></option><option value='TODOS'>TODOS</option>";
                                          $sql = "BEGIN VAC_PRC_USERNMAPROBFILT2(:idu,:pagina,:nombreseids); END ;";
                                          $cursor = oci_new_cursor($conn);
                                          $stmt=oci_parse($conn,$sql);
                                          oci_bind_by_name($stmt,':idu',$idusuarioData,32);
                                          oci_bind_by_name($stmt,':pagina',$pagina,32);
                                          oci_bind_by_name($stmt, ":nombreseids", $cursor, -1, OCI_B_CURSOR);
                                          oci_execute($stmt);
                                          oci_execute($cursor);
                                          while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                                              $row[1]= str_replace("Ñ", "ñ", $row[1]);
                                              $row[1]= ucwords(strtolower($row[1]));
                                                      if($rowAnt!=$row[2])echo "<option value='$row[2]'>$row[1]</option>";
                                                       $rowAnt=$row[2];
                                          }

                                  echo "
                            </select>
                       </div>
                       <input type='hidden' id='txtConsulta' >
                </td>
<td>
                <div class='dropdown'  > 
                <button class='btn btn-default btn-md dropdown-toggle' type='button' data-toggle='dropdown'>
                <span class='glyphicon glyphicon-search'></span> Departamento<span class=''></span>
                </button>
                <ul class='dropdown-menu' style='width: 330px;height: 220px;max-height: 200px; overflow-x: hidden;' role='menu' >
                <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='dep_-44' onclick=\"checkTodos(document.getElementById('chkTodos').value)\"></td><td>Todos</td></tr></table></li>";
       $cursor=oci_new_cursor($conn);
    $sql="BEGIN VAC_PRC_ACCESODPTO(:idu,:departamentos);End;";
    $stmt=oci_parse($conn,$sql);
    oci_bind_by_name($stmt,":idu",$idusuarioData,32);
    oci_bind_by_name($stmt, ":departamentos", $cursor, -1, OCI_B_CURSOR);
    oci_execute($stmt);
    oci_execute($cursor);
    $departamentos=array(0=>"COMODIN",);
    $idCheckBox="";
    $i=1;  
    while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
        array_push($departamentos,$row[0]);
        //array_push($idCheckBox,$row[1]);
        $idCheckBox=$idCheckBox.$row[1].";";
        echo "<li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='dep_$row[1]' onchange=agregafiltrodepartamento($row[1],document.getElementById('chkTodos').value)></td><td>$departamentos[$i]</td></tr></table></li>";
        echo "<script>
        objetodepartamento.m$row[1]='$row[0]';
        </script>";
        $i++;
    }  
    echo "
                </ul>
                </div>
                <input type='hidden' name='chkTodos' id='chkTodos' value='$idCheckBox'>
              </td>
              <td>
                <div class='dropdown'>
                  <button class='btn btn-default btn-md dropdown-toggle' type='button' data-toggle='dropdown'>
                  <span class='glyphicon glyphicon-adjust'></span> Estado
                  <span class=''></span></button>
                  <ul class='dropdown-menu'>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxestado-44' onchange='agregafiltroestado(-44);'></td><td>Sin Filtro</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxestado1' onchange='agregafiltroestado(1);'></td><td>Autorizadas</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxestado2' onchange='agregafiltroestado(2);'></td><td>Aprobadas por Jefe Inmediato</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxestado7' onchange='agregafiltroestado(7);'></td><td>Aprobadas por Gerente</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxestado3' onchange='agregafiltroestado(3);'></td><td>Aprobadas por RH</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxestado5' onchange='agregafiltroestado(5);'></td><td>Pendientes de Aprobacion</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxestado4' onchange='agregafiltroestado(4);'></td><td>Canceladas</td></tr></table></li>
                  </ul>
                </div>
              </td>
              <td>
              <div class='dropdown'>
                  <button class='btn btn-default btn-md dropdown-toggle' type='button' data-toggle='dropdown'>
                  <span class='glyphicon glyphicon-filter'></span> Tipo
                  <span class=''></span></button>
                  <ul class='dropdown-menu'>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxTipo-44' onchange=\"agregafiltroTipo(-44,'');\"></td><td>Sin Tipo</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxTipo3' onchange=\"agregafiltroTipo(3,'Ausencia');\"></td><td>Ausencia</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxTipo6' onchange=\"agregafiltroTipo(6,'Cumpleaños');\"></td><td>Cumpleaños</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxTipo7' onchange=\"agregafiltroTipo(7,'Descanso');\"></td><td>Descanso</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxTipo5' onchange=\"agregafiltroTipo(5,'Falta injustificada');\"></td><td>Falta Injustificada</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxTipo8' onchange=\"agregafiltroTipo(8,'Horas extras');\"></td><td>Horas extras</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxTipo4' onchange=\"agregafiltroTipo(4,'Incapacidad');\"></td><td>Incapacidad</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxTipo2' onchange=\"agregafiltroTipo(2,'Permiso');\"></td><td>Permiso</td></tr></table></li>
                    <li><table class='tableDrop'><tr><td style='width:2%;'><input type='checkbox' id='cbxTipo1' onchange=\"agregafiltroTipo(1,'Vacaciones');\"></td><td>Vacaciones</td></tr></table></li>
                  </ul>
                </div>
              </td>
              <td>
              <b>De:</b> <input id='fecha_ini' class='datepicker' onchange=\"changeFechaIni();agregaFechas($(this).val(),$('#fecha_fin').val())\">
              <b>Hasta:</b> <input id='fecha_fin' class='datepicker' onchange=agregaFechas($('#fecha_ini').val(),$(this).val());>
              </td>
              <td>
              <b>Tipo de Fecha:</b>
              <select class='select2' id='cbxTipoFecha' disabled>
              <option value='permiso'>Fecha Permiso</option>
              <option value='alta'>Fecha Alta</option>
              </select>
              </td>
           

    </tr>
      </table>";
    
    
   echo "<table class='table' style='margin: unset;'>
            <tr>
            <td width='20%' style='background-color:#C1FFA9;'><b>Aprobadas</b></td>
            <td width='20%' style='background-color:#f2dede;'><b>Canceladas</b></td>
            <td width='20%' style='background-color:#FFF2AB;'><b>Pendientes</b></td>
            <td width='20%' style='background-color:#A9E3FF;'><b>Aprobadas por jefe de área</b></td>
            <td width='20%' style='background-color:#C1B2FF;'><b>Aprobadas por gerente</b></td>
            
            </tr>
            </table>"; 
    

echo "
<div class='divScrollTabla'>
<table id='tabla_solicitudes' class='table  table-hover table-condensed tableadmin'>
</table>
</div>
<div class='divBotonesAccion'>
    <button class='btn btn-danger' id='btnCancelarSolicitudes' disabled>Cancelar</button>
    <button class='btn btn-success' id='btnAutorizarSolicitudes' disabled>Autorizar</button>
</div>
<input type='hidden' id='idDep' value='$iddepartamento'>
";

//echo "</tbody> </table></div>";
echo "<form id='forminfo' method='POST' style='display:none;'>
                    <input type=hidden name='idusuario' value=$idusuario>
                    <input type=hidden name='diaslaborados' value=$diaslaborados>
                    <input type=hidden name='comentarioSuperiores' value=$comentarioSuperiores>
                    <input type=hidden name=departamento id='dep' value='nodep'>
                    <input type=submit id='mandarinfo' hidden>
                  </form>";
echo "
              <form id='formjaor'  method='POST' style='display:none;'>
                      <input type=hidden name='idusuario' value=$idusuario>
                      <input type=hidden name='diaslaborados' value=$diaslaborados>
                      <input type=hidden name='comentarioSuperiores' value=$comentarioSuperiores>
                      <input type=hidden name='filtradoestado' id='jaor' value='A'>
                      <input type=submit id='botonfiltrado' hidden>
                      
              </form>
<script>
var countSolicitudes = 0;


$('#selectusuarios').select2({
    width: '500px',
    placeholder: 'Seleccione un usuario...'
});

$('#cbxTipoFecha').select2({
    width: '120px',
    placeholder: 'Seleccione un usuario...'
});

$('#cbxTipoFecha').on('change', function(){
    mandafiltros();
});


var \$input1=$('#fecha_ini').pickadate({

});
var \$input2=$('#fecha_fin').pickadate({

});
var picker1= \$input1.pickadate('picker');
var picker2= \$input2.pickadate('picker');
function onload1(){
filtrosestado.push(5);"; 
if($iddepartamento==29){
echo"
filtrosestado.push(2);
filtrosestado.push(7);
document.getElementById('cbxestado2').checked=true;
document.getElementById('cbxestado7').checked=true;
";
}
if($iddepartamento==30){
echo"
filtrosestado.push(2);
document.getElementById('cbxestado2').checked=true;

";
}
echo "document.getElementById('cbxestado5').checked=true;


mandafiltros();
}

\$body = \$('body');
            $(document).on({
                ajaxStart: function () {
                    \$body.addClass('loading');
                },
                ajaxStop: function () {
                    \$body.removeClass('loading');
                },
                ajaxError: function () {
                    \$body.removeClass('loading');
                }
            });
            


var miModal = document.getElementById('modalComentariosSuperiores')
                miModal.addEventListener('show.bs.modal', function (event) {
                  // Botón que activó el modal
                  var button = event.relatedTarget
                  // Extraer información de los atributos data-bs-*
                  var titulo = button.getAttribute('data-bs-whatever')
                  var tituloBoton = button.getAttribute('data-bs-whatever1')
                  var colorBoton = button.getAttribute('data-bs-color')
                  // Si es necesario, puedes iniciar una solicitud AJAX aquí
                  // y luego realiza la actualización en una devolución de llamada.
                  //
                  // Actualizar el contenido del modal.
                  var modalTitle = miModal.querySelector('.modal-title')
                  var modalButton = miModal.querySelector('.modal-button')
                   var botonModal = miModal.querySelector('.modal-footer button');

                  botonModal.classList.remove('btn-primary', 'btn-secondary', 'btn-success', 'btn-danger', 'btn-warning', 'btn-info', 'btn-light', 'btn-dark'); // Quitar todas las clases de color del botón
                  botonModal.classList.add('btn-' + colorBoton);


                  modalTitle.textContent = titulo
                  modalButton.textContent = tituloBoton

                })
</script>


</body>


</hmtl>
              ";
            
unset($_SESSION['mensaje']);
oci_close($conn);

