<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
$idUsuarioData=$_SESSION["idusuariodata"];
$idusuario=$_SESSION["idusuario"];
$nomuser=$_SESSION["nomuser"];
include "conexion.php";
include "funciones.php";
$fontsize=3;
$impresor=new impresor;
$usuario=new usuario;
               $sql="BEGIN VAC_PRC_CONTARDIASRESTANTES(:idu ,:nombre,:apaterno,:amaterno,:departamento,:antiguedad,:fechaIngreso,:fechaCumple,:dias,:diasLaborables,:idud,:solPendientes,:direccionOut,:celularOut,:emailOut,:usuarioOut,:claveOut); END;";               
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
               $r=oci_execute($stmt);
              
               
               
               
               
        $sql="BEGIN VAC_PRC_permisosusario(:idusuario , :permisos); END;";
        $cursor = oci_new_cursor($conn);
        $stmt= oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":permisos", $cursor, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt,':idusuario',$idUsuarioData,32);
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
    echo '
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        ';
        
        echo"
<title>
  Vacaciones
</title>
<style>
.select2-results__option{
font-size:11.5px;
}
.table{
margin:auto;
border:0px;
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
.tableBody{
width:65%;
margin:auto;
}

          </style>
      ";
        $impresor->estilosbarranavegacion();
        $impresor->imprimesources();
         if(in_array(2, $permisos))
         {
        echo "
        <script type='application/javascript' src='../js/busquedadinamica.js'></script>";
        $busqueda="<select id='busqueda'class='select2' style='width:500px;' onchange=buscar(this.value);><option value='$idusuario' selected>".$apaternoq." ".$amaternoq." ".$nombreq."</option></select></td>";
         }
        else{
            $busqueda="<input type='text' id='busqueda' class='txtBusqueda' readonly=true value='".$apaternoq." ".$amaternoq." ".$nombreq."'>";
        }
        
        echo"
        <script type='application/javascript' src='../js/moment.js'></script>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.css' id='theme_base'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.date.css' id='theme_date'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.time.css' id='theme_time'>
        <script src='../js/datepicker/jquery-3.3.1.min.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.date.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.time.js' type='text/javascript' ></script>
        <link href='../css/select2.min.css' rel='stylesheet'/>
        <script src='../js/select2.min.js'></script>
        </head>
        <body>";
       echo "
        <div class='panel panel-primary' >
                <div class='panel-heading' style='max-height: 72;'> 
                  <!--Información del asociado  -->
                <table class='table'>
                <tr>";
                    opciones($permisosaimprimir,8,"");
                    
                  echo $nomuser."</a></td>
                    <td width='1%'> 
                          <a href='cerrarsesion.php'>
                            <span class='glyphicon glyphicon-log-out'></span>
                          </a>
                    </td>
                  </tr>
                  </table>
                </div>
            </div>";
       echo "
        <div>
        <table class='tableBody' style='margin-bottom:50px;font-size:13px;'>
        <tr>
        <td style='text-align: left;width:600px;'>
        <font size=$fontsize> Usuario: </font><select id='busqueda'class='select2' style='width:500px;' onchange=buscar(this.value)>
        </td>
        <td style='text-align: left;'>
           <font size=$fontsize> Dias Disponibles  :   </font>
             <input type='text' id='datausuario'  readonly=true>
             <input type='hidden' id='datausuario_diasLab'  readonly=true >
        </td>
        <td>
           <font size=$fontsize> Dias Restantes  :    </font>
             <input type='text' id='diasTotales' onkeyup=calculaDias(this.value);>
        </td>
        </tr>
                  <tr id='tr' style='position: relative;' hidden>
                  <td> <ul id='livesearch' ></ul> </td>
                  <td></td>
                </tr>
               </table> 
                
                <script>
                function calculaDias(diasR){
                resta=document.getElementById('datausuario').value-diasR;
                document.getElementById('diasderechocambia').value=document.getElementById('datausuario').value-diasR;
                 if(diasR>=0){
                  document.getElementById('btnSolicitar').disabled=false;
                 }
                 else{
                 document.getElementById('btnSolicitar').disabled=true;
                 }
                }
                  $('.select2').select2({
                        params: { // extra parameters that will be passed to ajax
        contentType: 'application/json; charset=utf-8',
   },
   minimumInputLength: 5,
   delay: 200,
   language: {
                        inputTooShort: function () {
                        return 'Ingresa minimo 5 carácteres...';
                        }
                        },
  ajax: {
    url: 'livesearch2.php',
    dataType: 'json',
    data: function (params) {var query = {q: params.term,idu: $idUsuarioData}
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
}
                 </script>
                 <form id='enviar' method='POST' action='mandarsolicitud2.php'>
                 <input type=hidden name=idusuarioageno id='idusuariocambia' >
                  <input type=hidden name=diasderecho id='diasderechocambia'>
                  <center><input type=submit id='btnSolicitar' value='Guardar' style=' width: 10em;  height: 3em; border-radius:10px; ' disabled></center>
                 </form>
    </div>
                  </form>
                  <form id='forminfo'   method='POST'>
                  <input type=hidden name=idusuario value=$idusuario>
                  <input type=hidden name=diaslaborados value=$diaslaborados>
                  <input id='mandarinfo' type=submit value='oculto' hidden>
                  </form>";
    
 ?>
