<?php
error_reporting(E_ALL ^ E_NOTICE);
header('Content-Type: text/html; charset=utf-8'); 
session_start();
if($_SESSION["idusuario"]!=""){
    $idusuario=$_SESSION["idusuario"];
    $idusuarioData=$_SESSION["idusuariodata"];
}
else{
header("Location:../index.php");
}
$iddepartamento=$_SESSION["iddepartamento"];
$accion=$_SESSION["accion"];
$tpe=$_SESSION["tp"];
$nomuser=$_SESSION["nomuser"];
$priv=2;

include "conexion.php";
include "funciones.php";
$impresor=new impresor;
$usuario=new usuario;
$data=explode("|",$usuario->informaciongeneralmain($idusuario,$conn));
$nombre=$data[0];
$iddepartamento=$data[1];
$nombredepartamento=$data[2];
$puesto=$data[3];
$r= $data[4];
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

        echo '<html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
        ';
        
        echo"
<title>
  Vacaciones
</title>
<style>
*:focus{
	outline:0px;
}
.table tr td a{
font-size:11.5px;
}
.panel-primary table {
font-size:11.5px;
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
input{
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
input:focus{
border-width:2px;
border-color:#609EF0;
}
.tablaOrden{
width:30%;
margin:auto;

}
.tablaOrden td{
text-align:center;
padding:5px;
}
.tablaOrden th{
padding:7px;
color:white;
background-color:#337ab7;
text-align:center;
}
.tablaOrden tr:nth-child(even){background-color: #ddd;}

.tablaOrden tr:hover {background-color: #f2f2f2;}

          </style>
      ";
       
        $impresor->estilosbarranavegacion();
        $impresor->imprimesources();
        echo "
        <script type='application/javascript' src='../js/moment.js'></script>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.css' id='theme_base'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.date.css' id='theme_date'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.time.css' id='theme_time'>
        <script src='../js/datepicker/jquery-3.3.1.min.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.date.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.time.js' type='text/javascript' ></script>
        
        </head>
        <body>
   
      ";


        echo "
        


        <div class='panel panel-primary' style=' position:absolute  top:0px;'>
                <div class='panel-heading' style='max-height: 72;'> 
                  <!--InformaciÃ³n del asociado  -->
                <table class='table'>
                <tr>";
                    //Este segmento de codigo revisa los permisos del usuario e imprime los botones necesarios con base en ello, 
                    //el cuerpo de funcion esta en funciones.php. Si vas a modificarlo, solo ten cuidado con no arruinar los estilos.
                    
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
</div>

";



        echo "    <div >
                  <table style=margin:auto;margin-top:50px;>
                  <tr>
                  <td>
                  <form method='POST' action='mandarFestivo.php' >
                  <input type=hidden id=idFecha name=idFecha>
                  <input type=text name=fechaFestivo id=fechaFestivo style=text-align:center; placeholder='Fecha'>
                  <input type=text name=descripcion id=descripcion style=text-align:center; placeholder='Descripcion' required>
                  <input type=submit value=Guardar>
                  <input type=reset value=Limpiar onclick=document.getElementById('idFecha').value='';>
                  </form>
                  <form id='forminfo' method='POST'>
                  <input type=hidden name=idusuario value=$idusuario>
                  <input id='mandarinfo' type=submit value='oculto' hidden>
                  </form>
                  </td>
                  </table>
                  </div>
                  ";
          echo "<div>
                <table class=tablaOrden style='width:40%'>
                <thead>
                <tr>
                <th style=border-top-left-radius:10px;width:120px;>
                Fecha
                </th>
                <th style=width:360px;>
                Descripcion
                </th>
                <th colspan=2 style=width:80px;border-top-right-radius:10px;>
                </th>
                </tr>    
                </thead>
                <tbody>
                ";
$sql="SELECT * FROM VAC_DIAS_NO_HABILES ORDER BY FECHA ASC";///este procedure regresara las solicitudes pintandolos en los calendarios
$cursor = oci_new_cursor($conn);
$stmt= oci_parse($conn, $sql);
oci_execute($stmt);
while (($row = oci_fetch_array($stmt, OCI_BOTH)) != false)
{   
    echo "<tr><td>".$row[1]."</td><td style='text-align:left;'>".$row[2]."</td><td><img src='../images/editar_circulo.png' title='Modificar AsociaciÃ³n' width='30px' height='30px' style='cursor: hand;' onclick=\"editar($row[0],'$row[1]','$row[2]')\"></td><td><img src='../images/eliminar_circulo.png' title='Modificar AsociaciÃ³n' width='30px' height='30px' style='cursor: hand;' onclick='borrar($row[0])'></td></tr>";
}
        echo "
                </tbody>
                </table>  
                </div>
                    ";
$error=$_GET['error'];

        echo "
              <script>
              $('#fechaFestivo').pickadate();
              if(\"$error\"!='')alert(\"$error\");
              function borrar(id){
              document.getElementById('fechaElim').value=id;
              $('#btnEliminar').click();
              }
              function editar(id,fecha,descripcion){
              document.getElementById('idFecha').value=id;
              document.getElementById('fechaFestivo').value=formatoFecha(fecha);
              document.getElementById('descripcion').value=descripcion;
              }
              



              function formatoFecha(fecha){
              
              var d=new Date(fecha);
              return ceroIzquierda(d.getDate())+'/'+ceroIzquierda(d.getMonth()+1)+'/'+(d.getFullYear())
              }
              function ceroIzquierda(fecha){
              if(fecha<10){
              return '0'+fecha;
              }
              else{
              return fecha;
              }
              }
              </script>
              </form>
              <form id='formElim' method=post action='eliminarFestivo.php'>
              <input type=hidden id=fechaElim name=fechaElim>
              <input type=submit hidden id=btnEliminar name=btnEliminar>
              </form>
              </body>
              </html>
              ";
        oci_free_cursor($cursor);
        oci_close($conn);
       $_SESSION["accion"]=0;
    

 ?>
