<?php
error_reporting(E_ALL ^ E_NOTICE);
/**
 * Created by PhpStorm.
 * User: desarrollo
 * Date: 5/10/16
 * Time: 06:52 PM
 */
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
$idusuario=$_POST['idusuario'];
if (!$idusuario)
{
    $idusuario=$_SESSION['idusuario'];
}
$diaslaborados=$_POST['diaslaborados'];
if (!$diaslaborados) {
    $diaslaborados=$_SESSION['diaslaborados'];
}
$idsolicitud=$_POST['idsolicitud'];
if (!$idsolicitud) {
    $idsolicitud=$_SESSION['idsolicitud'];
}

$mensaje=$_SESSION['mensaje'];
if ($mensaje=="ac")
{
    echo "<div class='alert alert-success'>
          <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
            <strong>Exito!</strong> Las vacaciones han sido aprobadas con exito.
          </div>";
}
if ($mensaje=="e")
{
    echo "<div class='alert alert-danger'>
                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Error!</strong> Ocurrio un error, intentelo más tarde.
                  </div>";
}
if ($mensaje=="ex")
{
    echo "<div class='alert alert-danger'>
                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Error!</strong> LA modificacion abarcaba más dias de los que el usuario tiene permitidos
                  </div>";
}
if ($mensaje=="mc")
{
    echo "<div class='alert alert-success'>
                  <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                    <strong>Exito!</strong> Las vacaciones se modificaron con extio.
                  </div>";
}

if ($mensaje=="dc")
{
    echo "<div class='alert alert-success'>
                      <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                      <strong>Exito!</strong> Las vacaciones denegaron con exito.
                      </div>";
}
//session_unset();
//session_destroy();
include 'funciones.php';
include 'conexion.php';
$impresor=new impresor;
$usuario=new usuario;
$data=explode("|",$usuario->informaciongeneralmain($idusuario,$conn));
$nombre=$data[0];
$iddepartamento=$data[1];
$nombredepartamento=$data[2];
$puesto=$data[3];
$r= $data[4];


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
        //echo $fechareferencia."<br> ";
        //echo $antiguedad."<br> ";
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
        //echo $diasderecho."|||<br>";
        $sql="BEGIN VAC_PRC_revisar_fi_dl_pr(:idu,:fi,:diaslaborados);end;";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':idu',$idusuario,32);
        oci_bind_by_name($stmt,':fi',$fechaingreso,32);
        oci_bind_by_name($stmt,':diaslaborados',$diaslaborados,32);
        oci_execute($stmt);
        $diasderecho-=$totaldias;
                $sql="BEGIN VAC_PRC_permisosusario(:idusuario , :permisos); END;";
        $cursor = oci_new_cursor($conn);
        $stmt= oci_parse($conn, $sql);
        oci_bind_by_name($stmt, ":permisos", $cursor, -1, OCI_B_CURSOR);
        oci_bind_by_name($stmt,':idusuario',$idusuario,32);
        $r=oci_execute($stmt);
        $r=oci_execute($cursor);
        $permisos=array();
        $i=0;
        while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
            $permisos[$i] = $row[0];
//            echo $permisos[$i]."<br>";
            $i++;
        }
echo "<html>
<style>
* {
 font-size: 98%;
 font-family: Arial;
 
}
.navbar-nav > li > a 
    {
        padding-top:1px !important; padding-bottom:1px !important;
    }
.navbar 
    {
        min-height:32px !important
    }
    br {
    line-height: 11px;
 }
</style>
            <head>
              <title>
                Vacaciones
              </title>

            ";
$impresor->imprimesources();
echo "
            <script>
                          function administrar()
            {
              $('#forminfo').attr('action','administrar.php');
              $('#mandarinfo').click();
            }

            function revisar()
              {
                if(confirm('Desea revisar estas vacaciones'))
                  {
                    $('#inforevision').attr('action','revisar.php')
                  }
                    else
                    {
                    }
              }
            function versolicitud()
              {
                $('#forminfo').attr('action','estadovacaciones.php');
                $('#mandarinfo').click();
              }
            function solicitar()
              {
                  $('#forminfo').attr('action','main.php');
                  $('#mandarinfo').click();
              }
              function administrar()
                {
                  $('#forminfo').attr('action','administrar.php');
                  $('#mandarinfo').click();
                }
              </script>
              <script>

          function aprobar()
            {
              if(confirm('Seguro desea aprobar estas vacaciones ?'))
                {
                $('#formaprobar').attr('action','acciones.php');
                $('#aprobar').click();
                }
            }

          function modificar()
            {
              if(confirm('Seguro desea modificar estas vacaciones ?'))
                {
                  $('#formmodificar').attr('action','acciones.php');
                  $('#modificar').click();
                }
            }
          function denegar()
            {
              if(confirm('Seguro desea denegar estas vacaciones ?'))
                  {
                    $('#formdenegar').attr('action','acciones.php');
                    $('#denegar').click();
                  }
            }

            function detalles()
              {
                if(confirm('Revisar detalles de solicitud?'))
                  {
                    $('#formdetalles').attr('action','detalles.php');
                    $('#detalles').click();
                  }
              }
              </script>
            </head>
            <body >
            ";

/*

echo "
            <nav class='navbar navbar-inverse'>
            <div class='container-fluid'>
            <ul class='nav navbar-nav'>
            <li class='active'><a>$idusuario</a></li>
            <li class='active'><a>$nombre</a></li>
            <li class='active'><a>$nombredepartamento </a></li>
            <li class='active'><a>$puesto</a></li>
            </ul>
            <br>
            <br>
            <ul class='nav navbar-nav'>
            <li><a href='javascript:solicitar()'>Solicitud</a></li>
            <li><a href='javascript:versolicitud()'>Histórico</a></li>
            <li><a href='javascript:administrar()'>Autorizaciones</a></li>
            <li class='active'><a href='#'>Revisión</a></li>
            </ul>
             <ul class='nav navbar-nav navbar-right'>
    <li><a href='../index.html'><span class='glyphicon glyphicon-log-out'></span>Salir</a></li>
    </ul>
            </nav>
            </div>
";

*/
    //-----------------------------------------------------------------------------------------------------------------------

        echo '
        <style>
         /* unvisited link */

a:link, a:visited {
    color: white;
    padding: 14px 25px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
}


        </style>
        ';
        echo "<div class='panel panel-primary'>
                <div class='panel-heading'> 
                  <!--Información del asociado  -->

                  <a href='javascript:solicitar()' >Solicitud</a>
                  <a  href='javascript:versolicitud()'>Histórico</a>
                  ";
                     if ($permisos[2]) {echo "<a href='javascript:administrar()'>Autorizaciones</a>";}
                  echo "
                  <a href='#' style='background-color: red'>Revisión</a>
                </div>
                <div class='panel-body'>
";


















    /*-------------------------------------------------------------------------------------------------------------------------*/
    $cursor=oci_new_cursor($conn);
    $sql="BEGIN VAC_PRC_SOLICITUDPORID(:idsol,:solicitud);END;";
    $stmt=oci_parse($conn,$sql);
    oci_bind_by_name($stmt,':idsol',$idsolicitud,32);
    oci_bind_by_name($stmt,':solicitud',$cursor,-1,OCI_B_CURSOR);
    oci_execute($stmt);
    oci_execute($cursor);


echo "
                  <div class='table-responsive'>
                  <table class='table   table-striped table-hover  '>
                  <thead><tr>";
$impresor->cabsolicitudes(0);
echo "<th><a href='#'>Acciones</a></th></tr>
            </thead>
            <tbody>";
while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false)
{
    echo "<tr>";
    for($i=0;$i<13;$i++)
    {
        if($i==1)
        {}
        else
        {
            if($row[$i]==-44)
                echo "<td></td>";
            else
            echo "<td>$row[$i]</td>";
        }
    }
}
echo "
              <td>
              <div class='dropdown'>
                 <button class='btn btn-info dropdown-toggle' type='button' data-toggle='dropdown'>Acciones
                 <span class='caret'></span></button>
                 <ul class='dropdown-menu'>
                   <li>
                       <a href='javascript:aprobar();'>
                       <button type='button' class='btn btn-success'>
                       <span class='glyphicon glyphicon-ok'></span>
                       Aprobar &nbsp;</button>
                       </a>
                   </li>
                   <li>
                       <a href='javascript:modificar()'>
                       <button type='button' class='btn btn-warning'>
                       <span class='glyphicon glyphicon-warning-sign'>
                       </span> Modificar
                       </button>
                       </a>
                   </li>
                   <li>
                         <a href='javascript:denegar()'>
                         <button type='button' class='btn btn-danger'>
                         <span class='glyphicon glyphicon-remove'></span>
                         Cancelar
                         </button>
                         </a>
                   </li>

                   <li>
                   <a href='javascript:detalles()'>
                   <button type='button' class='btn btn-info'>
                   <span class='glyphicon glyphicon-folder-open'></span>
                     &nbsp; Detalles
                   </button>
                   </a>
                   </li>

                 </ul>
                </div>
              </td>
              </tr>
              </tbody>
            </table>
            </div>
</div>
                  ";

echo "<form id='forminfo'  class='form-inline' method='POST' hidden>
                    <input  name='idusuario' value=$idusuario>
                    <input name='diaslaborados' value=$diaslaborados>
                    <input type=submit id='mandarinfo' hidden>
                  </form>
                  <form id='formaprobar' class='form-inline' method='POST' hidden>
                          <input name=accion value='a'>
                    <input name='idusuario' value=$idusuario>
                    <input type=hidden name='diaslaborados' value=$diaslaborados>
                    <input type='hidden' name='iddepartamento' value=$iddepartamento> 
                    <input type=hidden name='idsolicitud' value=$idsolicitud>
                    
                          <input type=submit id='aprobar' hidden>
                  </form>
                  <form id='formmodificar' class='form-inline'  method='POST' hidden>
                          <input name=accion value='m'>
                          <input name='idusuario' value=$idusuario>
                          <input name='diaslaborados' value=$diaslaborados>
                          <input name='idsolicitud' value=$idsolicitud>
                          <input type=submit id='modificar' hidden>
                  </form>
                  <form id='formdenegar' class='form-inline' method='POST' hidden>
                          <input name=accion value='d'>
                          <input name='idusuario' value=$idusuario>
                          <input name='diaslaborados' value=$diaslaborados>
                          <input name='idsolicitud' value=$idsolicitud>
                          <input type=submit id='denegar' hidden>
                  </form>

                  <form id='formdetalles' method='POST' hidden>
                  <input  name='idusuario' value=$idusuario>
                  <input name='diaslaborados' value=$diaslaborados>
                  <input name='idsolicitud' value=$idsolicitud>
                  <input name='origen' value='revisar'>
                  <input type=submit id='detalles' hidden>
                  </form>
                  ";
echo "
            <script>
                        $('.table-responsive').on('show.bs.dropdown', function ()
                        {
                          $('.table-responsive').css( 'overflow', 'inherit' );
                        });

            $('.table-responsive').on('hide.bs.dropdown', function () {
                 $('.table-responsive').css( 'overflow', 'auto' );
            })
            </script>

            </body></html>";
session_unset($accion);
oci_close($conn);
