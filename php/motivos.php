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

body{
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif !important;
}
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
.divLargo {
height:38px;
font-size:12px;

}
.btn{
    border:0px;
    font-weight: bold;
    font-size: 13px;
    border-radius: 5px;
    padding:5px;
    cursor:pointer;
}
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
        <script src='../js/bootstrap5/bootstrap.min.js' type='text/javascript'></script>
        <link href='../css/bootstrap5/bootstrap.min.css' media='all' rel='Stylesheet' type='text/css' />
        <script src='../js/datepicker/lib/picker.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.date.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.time.js' type='text/javascript' ></script>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
        <script src='https://kit.fontawesome.com/b23865824b.js' crossorigin='anonymous'></script>
        <link href='http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.css' rel='stylesheet'  type='text/css'>
        
        </head>
        <body>
   
      ";


        echo "
        


        <div class='panel panel-primary' style=' position:absolute  top:0px;'>
                <div class='panel-heading' style='max-height: 72;'> 
                  <!--Información del asociado  -->
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
                            echo "<div class='container' >
                                <div class=' row py-2 text-white text-center bg-secondary divLargo ' >
                                <div class='text-center py-2 col-3 d-flex align-items-end justify-content-center'>Permiso</div>
                                <div class='text-center py-2 col-5 d-flex align-items-end justify-content-center'>Motivo</div>
                                <div class='col-1 py-2 d-flex align-items-end justify-content-center'>Editar</div>
                                <div class='col-1 py-2 d-flex align-items-end justify-content-center'>Guardar</div>
                                <div class='col-1 py-2 d-flex align-items-end justify-content-center'>Eliminar</div>
                                <div class='col-1 py-1 text-white d-flex align-items-end justify-content-center'><button onclick='mostrarOcultarArea();' class='btn' style='width:100%; font-size:12px;' type='button' title='Agregar nueva area'><i class='fa-solid fa-plus'></i></button></div>
                            </div>
                            </div>";
                            
                            
                            $sql="BEGIN VAC_PRC_PERMISOSVACACIONES(:permisosvacaciones);END;";
                          $stmt=oci_parse($conn,$sql);
                          oci_bind_by_name($stmt, ":permisosvacaciones", $cursor, -1, OCI_B_CURSOR);
                          $r=oci_execute($stmt);
                          $r=oci_execute($cursor);
                          $permisos=array();
                          $aux2=0;
                          
                          
                            echo "
                                <div class='container' id='ocultableArea' style='display:none'>
                                <form method='POST' action='mandarMotivo.php' >
                                <input type=hidden id=idMotivo name=idMotivo>
                                 <div class=' row  ' >
                                    <div class='text-center py-2 col-3 d-flex align-items-end justify-content-center' >";
                                    echo "<select style='width:100%; font-size:12px;' id='tipoPermiso' name=tipoPermiso ><option value='0' selected>Permiso...</option>";
                                    while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) 
                                  {
                                    echo "<option  value='$row[1]' idpermiso='$row[1]'>".$row[0]."</option>";
                                  }
                            echo "</select>";
                                    echo "</div>
                                        <div class='text-center py-2 col-5 '>
                                        <input style='width:100%; font-size:12px;' type=text name=motivoPermiso id=motivoPermiso style=text-align:center; placeholder='Motivo' required>
                                        </div>
                                        <div class='col-1 text-center py-2 '>
                                         
                                        </div>
                                        <div class='col-1 text-center py-2 '>
                                         <button class='btn'  style='width:100%; font-size:12px;' type=submit value=Guardar><i class='fa-solid fa-floppy-disk' style='color:green'></i></button>
                                        </div>
                                        <div class='col-1 text-center py-2 '>
                                          <button class='btn' style='width:100%; font-size:12px;' type=reset value=Limpiar onclick=document.getElementById('idMotivo').value='';><i class='fa-solid fa-xmark' style='color:red; '></i></button>
                                        </div>
                                         </div>
                                    </form>   
                                    <form id='forminfo' method='POST'>
                  <input type=hidden name=idusuario value=$idusuario>
                  <input id='mandarinfo' type=submit value='oculto' hidden>
                  </form>
                                    
                                </div>
                            ";
                            
                                    echo "
                                        <div class='container' >
                                 <div class=' row  ' >";
                                 $sql="SELECT * FROM VAC_COMENTARIOSPREESTABLECIDOS vd INNER JOIN VAC_CATALOGOPERMISOS vc ON vc.IDPERMISO = vd.IDPERMISO order by descripcion, comentariopreestablecido";///este procedure regresara las solicitudes pintandolos en los calendarios
$cursor = oci_new_cursor($conn);
$stmt= oci_parse($conn, $sql);
oci_execute($stmt);
while (($row = oci_fetch_array($stmt, OCI_BOTH)) != false)
{   
    echo"<div class=' col-3 py-2 ' style='font-size:12px;' >$row[4] </div>";
    echo"<div class='col-5 py-2' style='font-size:12px;'>$row[1] </div>";
    
    echo"<div class='text-center  col-1 py-2' ><button  class='btn' style='width:100%; font-size:12px;' title='Modificar Asociación'  style='cursor: hand;' onclick=\"editar($row[0],'$row[2]','$row[1]'), mostrarInput()\"><i class='fa-solid fa-edit '></i></button></div>";
    echo"<div class='text-center  col-1 py-2' > </div>";
    echo"<div class='text-center  col-1 py-2' ><button class='btn' style='width:100%; font-size:12px;'  title='Modificar Asociación'   onclick='borrar($row[0])'><i class='fa-solid fa-xmark' style='color:red; '></i></button> </div>";
//    echo "<tr><td>".$row[4]."</td><td style='text-align:left;'>".$row[1]."</td><td><img src='../images/editar_circulo.png' title='Modificar Asociación' width='30px' height='30px' style='cursor: hand;' onclick=\"editar($row[0],'$row[2]','$row[1]')\"></td><td><img src='../images/eliminar_circulo.png' title='Modificar Asociación' width='30px' height='30px' style='cursor: hand;' onclick='borrar($row[0])'></td></tr>";
}
                                 echo "</div>
                                 </div>
                                     ";
                                    
                            


//        echo "    <div id='ocultableArea' style='display:none'>
//                  <table style=margin:auto;margin-top:50px;>
//                  <tr>
//                  <td>
//                  <form method='POST' action='mandarMotivo.php' >
//                  <input type=hidden id=idMotivo name=idMotivo>";
//                  
//
//$sql="BEGIN VAC_PRC_PERMISOSVACACIONES(:permisosvacaciones);END;";
//                          $stmt=oci_parse($conn,$sql);
//                          oci_bind_by_name($stmt, ":permisosvacaciones", $cursor, -1, OCI_B_CURSOR);
//                          $r=oci_execute($stmt);
//                          $r=oci_execute($cursor);
//                          $permisos=array();
//                          $aux2=0;
//                          echo "<select id='tipoPermiso' name=tipoPermiso ><option value='0' selected>Permiso...</option>";
//                            while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) 
//                                  {
//                                    echo "<option  value='$row[1]' idpermiso='$row[1]'>".$row[0]."</option>";
//                                  }
//                            echo "</select>";

                            
                            
                            
                            
                            
                            


//                  echo "<!--<input type=text name=tipoPermiso id=tipoPermiso style=text-align:center; placeholder='Permiso'>-->
//                  <input type=text name=motivoPermiso id=motivoPermiso style=text-align:center; placeholder='Motivo' required>
//                  <input type=submit value=Guardar>
//                  <input type=reset value=Limpiar onclick=document.getElementById('idMotivo').value='';>
//                  </form>
//                  <form id='forminfo' method='POST'>
//                  <input type=hidden name=idusuario value=$idusuario>
//                  <input id='mandarinfo' type=submit value='oculto' hidden>
//                  </form>
//                  </td>
//                  </table>
//                  </div>
//                  ";
//          echo "<div>
//                <table class=tablaOrden style='width:40%'>
//                <thead>
//                <tr>
//                <th style=border-top-left-radius:10px;width:120px;>
//                Permiso
//                </th>
//                <th style=width:360px;>
//                Motivo
//                </th>
//                <th colspan=2 style=width:80px;border-top-right-radius:10px;>
//                </th>
//                </tr>    
//                </thead>
//                <tbody>
//                ";
//$sql="SELECT * FROM VAC_COMENTARIOSPREESTABLECIDOS vd INNER JOIN VAC_CATALOGOPERMISOS vc ON vc.IDPERMISO = vd.IDPERMISO order by descripcion, comentariopreestablecido";///este procedure regresara las solicitudes pintandolos en los calendarios
//$cursor = oci_new_cursor($conn);
//$stmt= oci_parse($conn, $sql);
//oci_execute($stmt);
//while (($row = oci_fetch_array($stmt, OCI_BOTH)) != false)
//{   
//    echo "<tr><td>".$row[4]."</td><td style='text-align:left;'>".$row[1]."</td><td><img src='../images/editar_circulo.png' title='Modificar Asociación' width='30px' height='30px' style='cursor: hand;' onclick=\"editar($row[0],'$row[2]','$row[1]')\"></td><td><img src='../images/eliminar_circulo.png' title='Modificar Asociación' width='30px' height='30px' style='cursor: hand;' onclick='borrar($row[0])'></td></tr>";
//}
//        echo "
//                </tbody>
//                </table>  
//                </div>
//                    ";
$error=$_GET['error'];

        echo "
              <script>
              
              if(\"$error\"!='')alert(\"$error\");
              
              



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
              function mostrarOcultarArea(){
                element = document.getElementById('ocultableArea');
                estado = element.style.display;
                if(estado == 'none'){
                element.style.display='block';
                }else{
                element.style.display = 'none'; 
                }

            }function borrar(id){
              document.getElementById('motivoElim').value=id;
              $('#btnEliminar').click();
              }
              function editar(id,permiso,motivo){
              console.log(id,permiso,motivo);
              document.getElementById('idMotivo').value=id;
              document.getElementById('tipoPermiso').value=permiso;
              document.getElementById('motivoPermiso').value=motivo;
              }
              
              function mostrarInput(){
              element = document.getElementById('ocultableArea');
                estado = element.style.display;
              element.style.display='block';
              }
              </script>
              </form>
              <form id='formElim' method=post action='eliminarMotivo.php'>
              <input type=hidden id=motivoElim name=motivoElim>
              <input type=submit hidden id=btnEliminar name=btnEliminar>
              </form>
              </body>
              </html>
              ";
        oci_free_cursor($cursor);
        oci_close($conn);
       $_SESSION["accion"]=0;
    

 ?>
