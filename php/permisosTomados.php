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
$idBusqueda=$_POST['idBusqueda'];
include 'funciones.php';
include 'conexion.php';
$impresor=new impresor;   
echo "
<html> 
<style>
#tabla_solicitudes th{
    cursor:hand;
    border-bottom: black solid 1px !important;
}
#tabla_solicitudes td{
   
   width:1%;
}
html *{
font-size:102% !important
}
        .centrar{
        margin:auto;
        }
        .espaciado{
        margin-top:10px;
        margin-bottom:10px;
        }
        .espaciado td{
        width:33%; 
        }
        .centrar select{
          width:300px;  
          align:'center';
        }
        
/*Inicio Fondos de tabla*/



.info{
    background-color: #A9E3FF;
}
.warning{
    background-color: #FFF2AB;
}
.success{
    background-color: #C1FFA9;
}
.danger{
    background-color: #F2DEDE;
}
.secondary{
    background-color: #C1B2FF;
}


#divSolicitudes > .row  > .container-fluid > .row,#divEncabezadosSolicitudes > .row  > .container-fluid > .row{
    border-bottom: 1px solid black;
}

#divSolicitudes > .row  > .container-fluid > .row > div{
    padding: 5px;
    padding-top: 15px;
    padding-bottom: 15px;
} 

#divEncabezadosSolicitudes > .row  > .container-fluid > .row > div{
    padding: unset;
    padding-top: 5px;
    padding-bottom: 5px;
}


#divSolicitudes > .row  > .container-fluid > .row > div:nth-child(3),#divEncabezadosSolicitudes > .row  > .container-fluid > .row > div:nth-child(3){
    min-width: 250px;  
}



#divSolicitudes > .row  > .container-fluid > .row > div:nth-child(2), 
#divSolicitudes > .row  > .container-fluid > .row > div:nth-child(4), 
#divSolicitudes > .row  > .container-fluid > .row > div:nth-child(5), 
#divSolicitudes > .row  > .container-fluid > .row > div:nth-child(10), 
#divSolicitudes > .row  > .container-fluid > .row > div:nth-child(12){
   
}

#divSolicitudes > .row  > .container-fluid > .row > div:nth-child(1), 
#divSolicitudes > .row  > .container-fluid > .row > div:nth-child(6), 
#divSolicitudes > .row  > .container-fluid > .row > div:nth-child(7),
#divEncabezadosSolicitudes > .row  > .container-fluid > .row > div:nth-child(1), 
#divEncabezadosSolicitudes > .row  > .container-fluid > .row > div:nth-child(6), 
#divEncabezadosSolicitudes > .row  > .container-fluid > .row > div:nth-child(7){
    max-width: 60px;
}

#divSolicitudes > .row  > .container-fluid > .row > div:nth-child(9), 
#divSolicitudes > .row  > .container-fluid > .row > div:nth-child(11){
    
}


.comentario, .comentarioJefe, .comentarioRH, .comentarioGerente{
    display: none;
}

.esconder{
    display: none;
}
/*Fin Fondos de tabla*/
        </style>
            <head>
            <meta charset=\"utf-8\">
              <title>
                Vacaciones
              </title>
              <script>
              function contarPendientes(){}
              </script>
            ";
$impresor->imprimesources();
$impresor->estilosbarranavegacion();
echo "
            <script src='../js/jquery.js'></script>
            <script src='../js/jquery-ui.js'></script>
            <script>                          
            var jq = jQuery.noConflict();
            </script>
            <script src='../js/jquery.redirect.js'></script>
            <script src='../js/permisosTomados.js'></script>
            <script src='../js/jquery-3.1.0.js'></script>
            <link href='../css/select2.min.css' rel='stylesheet'/>
            <script src='../js/select2.min.js'></script>
             <script type='text/javascript' src='../js/tablesorter/jquery.tablesorter.min.js'></script>
            <script type='text/javascript' src='../js/tablesorter/jquery.tablesorter.min.js'></script>
            <script type='text/javascript' src='../js/tablesorter/jquery.tablesorter.widgets.js'></script>
            





   <meta name='viewport' content='width=device-width, initial-scale=1' charset='utf-8'>
   <meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
        <link rel='stylesheet' href='../css/bootstrap-4.0/bootstrap.min.css'>        
        <link rel='stylesheet' href='../css/jquery-ui.css'>
        <script src='../js/jquery.js'></script>
        <script src='../js/jquery-ui.js'></script>
        <script src='../js/jquery.ui.datepicker-es.js'></script>
        <script src='../js/bootstrap-4.0/bootstrap.min.js'></script>
        <script src='../js/bootstrap-select.min.js'></script>
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
            function administraUsuarios()
            {
                $('#forminfo').attr('action','usuarios.php');
                $('#mandarinfo').click();
            }
             function administrafestivos()
            {
                $('#forminfo').attr('action','festivos.php');
                $('#mandarinfo').click();
            }
                 function informeVacaciones()
            {
                $('#forminfo').attr('action','informe.php');
                $('#mandarinfo').click();
            }
        
            $(document).ready(contarPendientes);
        </script>



            </head>
            ";
echo "<body onload='onload1();'>
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
        $auximp=0;
        while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
            $permisos[$i] = $row[0];
            if($row[0]==1 || $row[0]==3 || $row[0]==4 || $row[0]==7 || $row[0]==8 || $row[0]==9 )
              {
                  $permisosaimprimir[$auximp]=$row[0];
                  $auximp++;
              }
            $i++;
            echo "<script>
            agregapermisodeusaurio($row[0]);
            </script>";
        }
            $contceldas2=0;
      echo 
          "
                    <input type=hidden id=fechainicio name=fi disabled style='width:25%; max-height:30px' onchage='filtrafecha(this.value)'>
                 
               
                   <input type=hidden id=fechafinal name=ff disabled style='width:25% ; max-height:30px' onchage='filtrafecha(this.value)'>
         "; 
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
echo "
<!--table id='tabla_solicitudes' class='table table-bordered table-hover table-condensed tableadmin'>
</table-->
<div id='divEncabezadosSolicitudes' class='container-fluid'>
    <div class='row'>
        <div class='container-fluid'>
            <div class='row'>
                <div class='text-center col encabezado' >ID</div>
                <div class='text-center col encabezado' >FECHA</div>
                <div class='text-center col encabezado' >NOMBRE</div>
                <div class='text-center col encabezado' >INICIO</div>
                <div class='text-center col encabezado'>FINAL</div>
                <div class='text-center col encabezado' >DIAS</div>
                <div class='text-center col encabezado' >HORAS</div>
                <div class='text-center col encabezado'>TIPO</div>
                <div class='text-center col encabezado' >JEFE AREA</div>
                <div class='text-center col encabezado' >FECHA AUTORIZA</div>
                <div class='text-center col encabezado' >GERENTE</div>
                <div class='text-center col encabezado' >FECHA AUTORIZA</div>
                <div class='text-center col encabezado' >RH</div>
                <div class='text-center col encabezado' >FECHA AUTORIZA</div>
                <div class='text-center col encabezado' >PAGO</div>
            </div>
        </div>
    </div>
</div>

<div id='divSolicitudes' class='container-fluid'>
    <div class='row'></div>
</div>
<script>
function onload1(){
mandafiltros($idusuario,$idBusqueda);
}

</script>


</body>
</hmtl>
";
unset($_SESSION['mensaje']);
oci_close($conn);
