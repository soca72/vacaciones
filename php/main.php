<?php
error_reporting(E_ALL ^ E_NOTICE);
//header('Content-Type: text/xml; charset=ISO-8859-1'); 
session_start();
if($_SESSION["idusuario"]!=""){
    $idusuario=$_SESSION["idusuario"];
    $idusuarioData=$_SESSION["idusuariodata"];
}
else{
header("Location:../index.php");
}
 /*if(!$idusuario) //innecesario jr
 {
     $idusuario=$_SESSION["idusuario"];
 }
if($idusuario)
{
  $_SESSION["idusuario"]=$idusuario;
}*/

$iddepartamento=$_SESSION["iddepartamento"];
$accion=$_SESSION["accion"];
$tpe=$_SESSION["tp"];
$nomuser=$_SESSION["nomuser"];
$fontsize=3;
$priv=2;
$btnCancelar="";
$valorSeleccionado=$_SESSION['atributoSeleccionado'];
/*if (!$idusuario) {
    $idusuario=$_POST['idusuario']; 
}
if (!$iddepartamento) {
  $iddepartamento=$_POST['iddepartamento'];
}*/
include "conexion.php";
include "funciones.php";
$impresor=new impresor;
$usuario=new usuario;
$data=explode("|",$usuario->informaciongeneralmain($idusuario,$conn));
$nombre=$data[0];
$iddepartamento=$data[1];
$nombredepartamento=$data[2];
$puesto=$data[3];
$r= $data[5];
$sql="BEGIN VAC_PRC_CONTARDIASRESTANTES(:idu ,:nombre,:apaterno,:amaterno,:departamento,:antiguedad,:fechaIngreso,:fechaCumple,:dias,:diasLaborables,:idud,:solPendientes,:direccionOut,:celularOut,:emailOut,:usuarioOut,:claveOut,:idsucursal); END;";               
               $stmt=oci_parse($conn,$sql);
               oci_bind_by_name($stmt, ":idu",$idusuario,32);
               oci_bind_by_name($stmt,':nombre',$nombreq,32);
               oci_bind_by_name($stmt,':apaterno',$apaternoq,32);
               oci_bind_by_name($stmt,':amaterno',$amaternoq,32);
               oci_bind_by_name($stmt,':departamento',$departamentoq,32);
               oci_bind_by_name($stmt,':fechaIngreso',$fechaingreso,32);
               oci_bind_by_name($stmt,':fechaCumple',$fechaCumple,32);
               oci_bind_by_name($stmt,':antiguedad',$antiguedad,32);
               oci_bind_by_name($stmt,':dias',$diasderecho,32);
               oci_bind_by_name($stmt,':diasLaborables',$sinUnso,32);
               oci_bind_by_name($stmt,':idud',$idud,32);
               oci_bind_by_name($stmt,':solPendientes',$solPendientes,32);
               oci_bind_by_name($stmt,':direccionOut',$direccionq,200);
               oci_bind_by_name($stmt,':celularOut',$celularq,32);
               oci_bind_by_name($stmt,':emailOut',$emailq,50);
               oci_bind_by_name($stmt,':usuarioOut',$usuarioq,32);
               oci_bind_by_name($stmt,':claveOut',$claveq,32);
               oci_bind_by_name($stmt,':idsucursal',$idsucursal,32);
               $r=oci_execute($stmt);


    error_log("¡La base de datos de Oracle no está disponible!", '/var/log/apache2/error.log');
    
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
             //echo gettype($permisos[$i]);
                  $permisosaimprimir[$auximp]=$row[0];
                  $auximp++;
                  
          //ECHO $row[0];
            $i++;
        }
        
        echo '<html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        ';
        
        echo"
<title>
  Vacaciones
</title>
<style>
body{
overflow-x: hidden;
}
.select2-results__option{
font-size:11.5px;
}
.table{
margin:auto;
}
.table tr td a{
font-size:11.5px;
}
.panel-primary table {
font-size:11.5px;
}
.panel-body table {
font-size:12px;
}
hr {
    display: block;
    height: 1px;
    border: 0;
    border-top: 1px solid #ccc;
    margin: 1em 0;
    padding: 0; 
    
}
select{
border-radius:5px;
padding:2px;
padding-left:2px;
border-width:1px;
border-style:solid;
border-color:#BDBDBD;
height:30px;
font-size:20px;
color:#000000;
}

*:focus{
	outline:0px;
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
            .containerDate{
                display: inline-block;
                width: 130px;
            }
            .timepicker_wrap {
            display: inline-flex;
            }
            .time_pick{
                display: inline-block;
            }
            #horaInicio,#horaFin{
                width:100px;      
                text-align: center;
                display: none;
            }
            .timepicker_wrap[style*='display: block;']{
                width: 182px;
            }
            #fechainicio,#fechafinal{
                text-align: center;
            }
          </style>
      ";
        
        $impresor->estilosbarranavegacion();
        $impresor->imprimesources();
         if(in_array(2, $permisos))
         {

        $busqueda="<select id='busqueda'class='select2' style='width:500px;' onchange=buscar(this.value);><option value='$idusuario' selected>".$apaternoq." ".$amaternoq." ".$nombreq."</option></select></td>";
         }
        else{
            $busqueda="<input type='text' id='busqueda' class='txtBusqueda' readonly=true value='".$apaternoq." ".$amaternoq." ".$nombreq."'>";
        }
        
                  echo"
        <script type='application/javascript' src='../js/moment.js'></script>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/classic.css' id='theme_base'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/classic.date.css' id='theme_date'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/classic.time.css' id='theme_time'>
        <script src='../js/datepicker/jquery-3.3.1.min.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.date.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.time.js' type='text/javascript' ></script>
        <script src='../js/timepicki.js'></script>
        <link href='../css/timepicki.css' rel='stylesheet'>
        <link href='../css/select2.min.css' rel='stylesheet'/>
        <script src='../js/select2.min.js'></script>
        </head>
        <body onload='contarPendientes();onload1();'>
         <div id='spin' class='modal'></div>
      ";


        echo "
        <div class='panel panel-primary' >
                <div class='panel-heading' style='max-height: 72;'> 
                  <!--Información del asociado  -->
                <table class='table'>
                <tr>";
                    //Este segmento de codigo revisa los permisos del usuario e imprime los botones necesarios con base en ello, 
                    //el cuerpo de funcion esta en funciones.php. Si vas a modificarlo, solo ten cuidado con no arruinar los estilos.n
                    
                    opciones($permisosaimprimir,1,"");
                    
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
                <tr>";
	    echo "<td style='text-align: center; font-size: 103%' width='25%'>$busqueda";
            echo "<td style='text-align: center; font-size: 103%' width='25%'>Fecha de ingreso laboral  : <font id='fontFechaIngreso' color='OrangeRed'>".$fechaingreso ."</font></td>";
            echo "<td style='text-align: center; font-size: 103%' width='25%' antiguedad='$antiguedad' > Antiguedad  : <font id='fontAntiguedad' color='OrangeRed'>".$antiguedad." años"."</font></td>";
            echo "<td style='text-align: center; font-size: 103%' width='25%'> Dias por tomar  :  <font id='fontDiasD' color='OrangeRed'>".$diasderecho."</font></td>";
             echo "<input id='fontAntiguedad2' type=hidden name=fontAntiguedad2  value='$antiguedad'>";
            echo "
        </tr>
        </table>
</div>
</div>

";


        echo "<form id='enviar' method='POST'>
                  <input type=hidden name=idusuario  value=$idusuario>
                  <input type=hidden name=idusuarioageno id='idusuariocambia' >
                  <input type=hidden name=nombre value='$nombre'>
                  <input type=hidden name=iddepartamento value='$iddepartamento'>
                  <input type=hidden name=diaslaborados value='$diaslaborados'>
                  <input type=hidden name=diasderecho id='diasderechocambia' value='$diasderecho'>
                  <input type=hidden name=fechaCumple id='fechaCumple' value='$fechaCumple'>
                  <input type=hidden name='diferenciaDias' id='diferenciaDias' value='0'>
                  <input type='hidden' id='datausuario'  readonly=true>
                  <input type='hidden' id='datausuario_diasLab'  readonly=true >
                  ";

                  
                  if(in_array(2, $permisos))
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
    data: function (params) {var query = {q: params.term,idu: $idusuarioData,tabla: 'Solicita'}
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
hello(value);
document.getElementById('selectPermiso').value='AAA';permiso('AAA');
}



function onload1(){
showdata($idusuario);
document.getElementById('cambiarSolicitudes').submit();
};
                 </script>
        ";
         $btnCancelar="<input type=button onclick=$('#busqueda').val('$idusuario').trigger('change'); style='width: 10em;  height: 3em; border-radius:10px;' value=Cancelar>";       
                  }
                  
                  else{
                      echo "<input type='hidden' id='datausuario'  readonly=true>";
                     $script= "<script>
                      document.getElementById('idBusqueda').value='$idusuarioData';
                      document.getElementById('cambiarSolicitudes').submit();
                      function onload1(){}
                      </script>  ";
                     }
                  
                  if(in_array(1, $permisos))
                  {
                echo"
                <table class='table'>
                  <tr>
                      <td style='padding-left:16px;' ><font size=$fontsize>Tipo de Permiso: ";
                 
                  $sql="BEGIN VAC_PRC_PERMISOSVACACIONES(:permisosvacaciones);END;";
                          $stmt=oci_parse($conn,$sql);
                          oci_bind_by_name($stmt, ":permisosvacaciones", $cursor, -1, OCI_B_CURSOR);
                          $r=oci_execute($stmt);
                          $r=oci_execute($cursor);
                          $permisos=array();
                          $aux2=0;
                          echo "<select id='selectPermiso' onchange='permiso(this.value);'><option value='AAA' selected>Seleccione...</option>";
                            while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) 
                                  {
                                    echo "<option onclick='permiso(\"".$row[0]."\")' value='$row[0]' idpermiso='$row[1]'>".$row[0]."</option>";
                                  }
                            echo "</select>";
                            
                            $sql="BEGIN VAC_PRC_comPreestablecido(:comentarioPreestablecido);END;";
                          $stmt=oci_parse($conn,$sql);
                          oci_bind_by_name($stmt, ":comentarioPreestablecido", $cursor, -1, OCI_B_CURSOR);
                          $r=oci_execute($stmt);
                          $r=oci_execute($cursor);
                          $permisos=array();
                          $aux2=0;
                          echo "&nbsp&nbsp<span id='Ocultar' style='display: none;'>Motivos de permiso: </span><select style='display: none;' id='selectComen' name='comentarioPreestablecido' ><option value='' selected>Seleccione...</option>";
                            while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) 
                                  {
                                    echo "<option value='$row[3]' idpermiso='$row[0]'>".$row[3]."</option>";
                                  }
                            echo "</select>";
                  echo "
                         &nbspDesde : <div class='containerDate'><input type=text id=fechainicio name=fi  style='width:100%; max-height:30px' readonly='true' onchange= 'enableFF();' disabled></div>
                         <input type='text' id='horaInicio' name='hi' disabled>
                         &nbspHasta : <div class='containerDate'><input type=text id=fechafinal  name=ff  style='width:100%; max-height:30px' readonly='true' onchange= contardias(); disabled></div>
                         <input type='text' id='horaFin' name='hf' disabled>
                        </font>
                 &nbsp
                 &nbsp";
                  
                  
                  
                  
                  
                  
                  
                  
                            
                            
                  
                 

                 
                echo "<select id='cbxFormaPago' hidden>
                    <option value='CONSUELDO'>
                        Con Sueldo
                    </option>
                    
                    <option value='SINSUELDO'>
                        Sin Sueldo
                    </option>
                    
                    <!--<option value='TIEMPO' id='opcTiempo'>
                        Tiempo por tiempo
                    </option>-->

                </select>


                  <input type=checkbox id='cg'  name='sueldo' onclick='opcionsueldo(\"CONSUELDO\")' style='margin-left:auto; margin-right:auto;' hidden>
                       &nbsp
                                <font size=$fontsize><label id='labelconsueldo' hidden>Con goce</label></font>
                  </td>
                  </tr>
                  
                  <tr>
                  <td>
                  <textarea type='text' id='comentario' name='comentario' cols='70' rows='3' placeholder='Comentario' hidden></textarea>
                  </td>
                  </tr>
         <tr>
         <td>
         </td>
         </tr>
                    <tr>
                        <td colspan='100%' style='text-align: center;'>                  
                          <input type=submit value='Solicitar' id='solicitarBoton' style=' width: 10em;  height: 3em; border-radius:10px; ' onclick='validarfechas()'>
                           $btnCancelar
                    </td>
                    <td></td>
                    <td></td>
                  </table>
                  <iframe name='tabla_solicitudes' id='tabla_solicitudes' style='width:100%;height:calc(100% - 330px);' scrolling='auto'>
                  <p>Funcion no soportada por el explorador</p>
                  </iframe>
                  
        
                  ";
                  include "funcionesCalendario.php";
                          echo "
        <script type='application/javascript' src='../js/busquedadinamica.js'></script>";
                  
                  echo "
                  <input type=text id='tipopermiso' name='tp' value='AAA' hidden>
                  <input type=text id='opcionsueldopermiso' name='os' value='BBB' hidden >
                  "; 
             
                  
        echo "
            </form>
                  <form id='forminfo'   method='POST'>
                  <input type=hidden name=idusuario value=$idusuario>
                  <input type=hidden name=diaslaborados value=$diaslaborados>
                  <input id='mandarinfo' type=submit value='oculto' hidden>
                  </form>
        <form id='cambiarSolicitudes' action='permisosTomados.php' method='post' target='tabla_solicitudes'>
        <input type='hidden' name='idBusqueda' id='idBusqueda'>
        </form>
        $script
        <script>
        \$body = \$('body');
            $(document).on({
                ajaxStart: function () {
                    \$body.addClass('loading');
                },
                ajaxStop: function () {
                    \$body.removeClass('loading');
                }
            });
            



            


            var select = document.getElementById('selectPermiso');
            
            select.addEventListener('change', function(event){
                 let selectElement = document.getElementById('selectPermiso');
                let atributoSeleccionado = selectElement.options[selectElement.selectedIndex].getAttribute('idpermiso');
                const opcionesSegundoSelect = document.getElementById('selectComen').options;
                const valorSeleccionado = this.value;
                
                console.log('valorSeleccionado: '+valorSeleccionado);
                console.log('valoir2: '+opcionesSegundoSelect.length);
                
                var antiguedadValor = $('#fontAntiguedad2').val();

                console.log('ValorAnti a : '+antiguedadValor);

                if(valorSeleccionado.includes('Cumple') && antiguedadValor<1 ){
                console.log('Entrando a : '+valorSeleccionado);
                $('#solicitarBoton').prop('disabled', true);
                $('#solicitarBoton').prop('title','No tiene la antiguedad necesaria (1 año minimo) para solicitar el dia de cumpleaños ')
                }
                

if(valorSeleccionado.includes('Horas extras')){


var fechaActual = new Date();
console.log('fechaActual1:'+fechaActual);

// Restar 7 d�as a la fecha actual
fechaActual.setDate(fechaActual.getDate() - 7);
console.log('fechaActual2:'+fechaActual);
          //  var dateTemp = dateFI;
          //  dateTemp.setDate(dateTemp.getDate() + 1);
//            picker.set('min',fechaActual);
            $('#fechainicio').pickadate('picker').set('min', fechaActual);
        }
                

                let contadorMotivo = 0;
                for (let i = 0; i < opcionesSegundoSelect.length; i++) {
                  const opcion = opcionesSegundoSelect[i];
                  console.log('valoir: '+opcion.getAttribute('idpermiso'));
                  if (opcion.getAttribute('idpermiso') !== atributoSeleccionado) {
                    opcion.style.display = 'none';
                  } else {
                    opcion.style.display = 'block';
                    contadorMotivo++;
                  }
                }
                
                if(contadorMotivo<1){
                document.getElementById('selectComen').style.display = 'none';
                document.getElementById('Ocultar').style.display = 'none';
                $('#selectComen').prop('required', false);
                }else{
                document.getElementById('selectComen').style.display = 'inline';
                document.getElementById('Ocultar').style.display = 'inline';
                $('#selectComen').prop('required', true);
                }



            });
        </script>
               </body>
              </html>
              ";

if ($accion==1) {
echo "<script>
alert('Solicitó más dias de los que dispone');
</script>";
}
if ($accion==2) {
echo "<script>
        alert('$tpe solicitado con exito');
      </script>";
}
if ($accion==3) {
        echo "<script>
        alert('La solicitud no se pudo generar');
        </script>";
}
if ($accion==4) {
  echo "<script>
      alert('No puede solicitar más vacaciones ya que todavia tiene unas pendiente de aprobacion');
        </script>";
}
if ($accion==5) {
  echo "<script>
      alert('Las fechas del permiso que solicitó coinciden con las de un permiso que ya tiene');
        </script>";
}
if ($accion==6) {
  echo "<script>
      alert('Ya tiene un cumpleaños asignado en ese año');
        </script>";
}
if ($accion==7) {

      echo "<script>
                alert('Ocurrió uno de los siguientes problemas: \\n\\n1)Solo puedes solicitar 2 días o menos por año\\n2)Tienes más de un año de antigüedad, debes solicitarlo como días de vacaciones');
            </script>";
}
if ($accion==8) {

      echo "<script>
                alert('Las horas extras coinciden con alguna solicitud anterior');
            </script>";
}
        oci_free_cursor($cursor);
        oci_close($conn);
        $_SESSION["accion"]=0;
        //session_destroy();
    
}
 ?>
