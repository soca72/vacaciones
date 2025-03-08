<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]!=""){
    $idusuarioLogeado=$_SESSION["idusuario"];
    $idusuarioData=$_SESSION["idusuariodata"];
}
else{
header("Location:../index.php");
}
$idusuario=$_POST['idusuario'];
$idusuarioSol=$_POST['idusuarioSol'];
$iddepartamento=$_POST['iddepartamento'];
$diaslaborados=$_POST['diaslaborados'];
$idsolicitud=$_POST['idsolicitud'];
$accion=$_POST['accion'];
$nomuser=$_SESSION["nomuser"];
include "conexion.php";
include "funciones.php";

$sql="BEGIN VAC_PRC_CONTARDIASRESTANTES(:idu ,:nombre,:apaterno,:amaterno,:departamento,:antiguedad,:fechaIngreso,:fechaCumple,:dias,:diasLaborables,:idud,:solPendientes,:direccionOut,:celularOut,:emailOut,:usuarioOut,:claveOut); END;";               
               $stmt=oci_parse($conn,$sql);
               oci_bind_by_name($stmt, ":idu",$idusuarioSol,32);
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







 $sql='BEGIN VAC_PRC_DIASTOMADOS_ANTIGUEDAD(:idu,:totald,:ant);END;';
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':idu',$idusuarioSol,32);
        oci_bind_by_name($stmt,':totald',$totaldias,32);
        oci_bind_by_name($stmt,':ant',$antiguedad,32);
        $r=oci_execute($stmt);
        if ($antiguedad>16) {
            $aux = $antiguedad;
            $antiguedad = 16;
            $old = true;
        }
        if(!$totaldias)$totaldias=0;
        $sql="BEGIN VAC_PRC_FECHAREFERENCIA(:idu,:fecharef);END;";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':idu',$idusuarioSol,32);
        oci_bind_by_name($stmt,':fecharef',$fechareferencia,32);
        oci_execute($stmt);
        //echo $fechareferencia;
        //if( (date_diff('2011-03-31',$fechareferencia))>0 );
        //if($fechareferencia>'2011-03-31'){
        $ftemp=explode("/", $fechareferencia);
        $ftemp="20".$ftemp[2]."-".$ftemp[1]."-".$ftemp[0];
        if( $ftemp=='2011-03-31'){
            $diasex=25;
          }
        else{
          $diasex=18;
            
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
        //echo $diasderecho."|||<br>";
        $sql="BEGIN VAC_PRC_revisar_fi_dl_pr(:idu,:fi,:diaslaborados);end;";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':idu',$idusuarioSol,32);
        oci_bind_by_name($stmt,':fi',$fechaingreso,32);
        oci_bind_by_name($stmt,':diaslaborados',$diaslaborados,32);
        oci_execute($stmt);
        $diasderecho-=$totaldias;
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
            
                  $permisosaimprimir[$auximp]=$row[0];
                  $auximp++;
             
 //ECHO "PermisoPrueba".$row[0];
            $i++;
        }

switch ($accion) {
    case 'a':

        $sql="BEGIN VAC_PRC_iddeptousandoidsol(:idsol,:iddep);END;";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,":idsol",$idsolicitud,32);
        oci_bind_by_name($stmt,":iddep",$iddepsol,32);
        oci_execute($stmt);
        $accion="au";
        if ($iddepartamento==29)
        {
            echo "RH";
            $sql="BEGIN VAC_PRC_APROBARRECURSOSH(:idu,:idsol,:accion);END;";
            $stmt=oci_parse($conn,$sql);
            oci_bind_by_name($stmt,':idu',$idusuario,32);
            oci_bind_by_name($stmt,':idsol',$idsolicitud,32);
            oci_bind_by_name($stmt,':accion',$accion,32);
            $result=oci_execute($stmt);
            //$result=$db->query("call autorizarjefearea($idsolicitud,$idusuario,'$accion');");
            if ($result)
            {
                $_SESSION['mensaje']="ac";
            }
            else
                {
                    $_SESSION['mensaje']="e";
                }
        }
        
        
        if ($iddepartamento==30)
        {
            echo "Gerente";
            $sql="BEGIN VAC_PRC_APROBARGERENTE(:idu,:idsol,:accion);END;";
            $stmt=oci_parse($conn,$sql);
            oci_bind_by_name($stmt,':idu',$idusuario,32);
            oci_bind_by_name($stmt,':idsol',$idsolicitud,32);
            oci_bind_by_name($stmt,':accion',$accion,32);
            $result=oci_execute($stmt);
            //$result=$db->query("call autorizarjefearea($idsolicitud,$idusuario,'$accion');");
            if ($result)
            {
                $_SESSION['mensaje']="ac";
            }
            else
                {
                    $_SESSION['mensaje']="e";
                }
        }
        

         if($permisos[2] && $iddepartamento==$iddepsol){
             ECHO "permisos jefe area";
            $sql="BEGIN VAC_PRC_APROBARJEFEAREA(:idu,:idsol,:accion);END;";
            $stmt=oci_parse($conn,$sql);
            oci_bind_by_name($stmt,':idu',$idusuario,32);
            oci_bind_by_name($stmt,':idsol',$idsolicitud,32);
            oci_bind_by_name($stmt,':accion',$accion,32);
            $result=oci_execute($stmt);
            //$result=$db->query("call autorizarjefearea($idsolicitud,$idusuario,'$accion');");
            if ($result)
            {
                $_SESSION['mensaje']="ac";
            }
            else {
                $_SESSION['mensaje']="e";
            }
        }
        $_SESSION['idusuario']=$idusuario;
        $_SESSION['diaslaborados']=$diaslaborados;
        $_SESSION['idsolicitud']=$idsolicitud;
        oci_close($conn);
        header('Location:administrar.php');
        break;
    case 'm':
        $impresor=new impresor;
        $fechas=new fechas;
//        $result=$db->query(" call informacionusuario($idusuario,$iddepartamento);");
        $usuario=new usuario;
        $data=explode("|",$usuario->informaciongeneralmain($idusuario,$conn));
        $nombre=$data[0];
        $iddepartamento=$data[1];
        $nombredepartamento=$data[2];
        $puesto=$data[3];
        $r= $data[4];


/*
        while($row=$result->fetch_row())
        {
            $priv=$row[5];
            $nombre=$row[1];
            $departamento=$row[9];
        }
        $result->close();
        $db->next_result();

        $result=$db->query(" call versolicitudporid($idsolicitud);");
*/
        $cursor=oci_new_cursor($conn);
        $sql="BEGIN VAC_PRC_HISTORIALSOLICITUD(:idsol,:historial);END;";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,':idsol',$idsolicitud,32);
        oci_bind_by_name($stmt,':historial',$cursor,-1,OCI_B_CURSOR);
        oci_execute($stmt);
        oci_execute($cursor);
        echo "<html>

";
echo "                <head>
                  <title>
                    Vacaciones
                  </title>
<style>
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
a:link, a:visited {
    color: white;
    padding: 8px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 120%;
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
*:focus{
	outline:0px;
}
.panel-heading .table tr td{
height:30px;
}
.panel-body .table{
font-size:11.5px;
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
        <link href='../css/timepicki.css' rel='stylesheet'>
  
        <script src='../js/timepicki.js'></script>
        <script src='../js/datepicker/lib/picker.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.date.js' type='text/javascript'></script>
        <script src='../js/datepicker/lib/picker.time.js' type='text/javascript'></script>
                <script>
                var dateToday=new Date();
                function versolicitud()
                  {
                    $('#forminfo').attr('action','estadovacaciones.php');
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
                    function validarmodificacion()
                      {
                        document.getElementById('fechainicio').disabled=false;
                        document.getElementById('fechafinal').disabled=false;
                        var fi=document.getElementById('fechainicio').value;
                        var ff=document.getElementById('fechafinal').value;
                        var today = new Date();
                        var dd = today.getDate();
                        var mm = today.getMonth()+1; //January is 0!
                        var yyyy = today.getFullYear();
                        if(dd<10) {
                            dd='0'+dd
                        }
                        if(mm<10) {
                            mm='0'+mm
                        }
                        today = mm+'/'+dd+'/'+yyyy;
               if(fi>ff||!fi||!ff|| document.getElementById('tipopermiso').value=='AAA'   )
                  {
                    if(document.getElementById('tipopermiso').value=='AAA')
                      {
                        alert('Porfavor seleccione que tipo de permiso quiere');
                      }
 
                      if(fi>ff)
                      {
                        alert('La fecha inicial no puede ser mayor a la final');
                      }
                     if(!fi)
                         {
                             alert('No ha seleccionado una fecha de inicio');
                         }
                     if(!ff)
                         {
                             alert('No ha seleccionado una fecha final');
                         }
                  }   
                            else
                              {
                                 document.getElementById('opcionsueldopermiso').value='SINSUELDO';
                          if(document.getElementById('cg').checked)
                          {
                            document.getElementById('opcionsueldopermiso').value='CONSUELDO';
                          }
                                  $('#btnmod').click();
                              }
                      }

                

                   function opcionsueldo(b)
                         {
                              document.getElementById('opcionsueldopermiso').value=b;
                         }
                  </script>
                </head>
                <body>
                ";
                       echo '
        <style>

     body {
  margin: 2;
  padding: 2;
  border: none;
  }


.containerDate{
                display: inline-block;
                width: 130px;
            }
#horaInicio,#horaFin{
                width:100px;      
                text-align: center;
                display: none;
            }
.time_pick{
                display: inline-block;
            }

.timepicker_wrap[style*=\'display: block;\']{
                width: 182px;
            }
            #fechainicio,#fechafinal{
                text-align: center;
            }
        </style>
        ';
        echo "<div class='panel panel-primary'>
                 <div class='panel-heading' style='max-height: 72;'> 
                        <!--Información del asociado  -->
                        <table class='table'>
                        <tr>
                          ";
                    
                    opciones($permisosaimprimir,5,"");
                    

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

        echo "<div class='panel panel-success'>
                <div class='panel-heading'>Originales</div>
                <div class='panel-body' style='max-height:80px;'>
                        
                      <table class='table '>
                      <thead><tr>";
        $impresor->cabsolicitudes(0);
        echo "<th class='text-center'>Tipo</thead><tbody>";
        while(($row=oci_fetch_array($cursor,OCI_BOTH))!=false)
        {  
            echo "<tr>";
            for($i=0;$i<7;$i++)
            {
                if($i!=1)
                {
                    echo "<td>$row[$i]</td>";
                }
            }
            echo "<td>$row[23]</td>";
              echo "<td>$row[7]</td>";
             echo "<td>$row[8]</td>";
              echo "<td>$row[9]</td>";
               echo "<td>$row[10]</td>";
                echo "<td>$row[21]</td>";
                 echo "<td>$row[22]</td>";
                 echo "<td>$row[11]</td>";
                 echo "<td>$row[12]</td>";
            
            $idusu=$row[1];
            $tp=$row[14];
            $os=$row[15];
            //$diasLaborables=$row[19];
            $diasLaborables="";
            $fiAnt=date("Y-m-d", strtotime($row[3]));
            $ffAnt= date("Y-m-d", strtotime($row[4]));
        }
        echo "
        <td class='text-center'>$tp</td>
                  </tr>
                  </tbody>
                </table>
                  </div>
                  </div>
                  ";
  
        echo "<form id='fechas' action='modificar.php' method='POST'>
                        <table class=table style='margin:auto;'>
                        <thead><tr>";
        //$impresor->cabsolicitudes(0);
        echo "</thead><tbody>";
       
            echo "<tr>";
                        echo "<td width='50%' style='text-align:center;'>
                               ";
                      

                                       
                    
                    
        
        echo "
                  <input type=hidden name='idusuario' value=$idusuario>
                  <input type=hidden name='iddepartamento' value=$iddepartamento>
                  <input type=hidden name='fiAnt' value=$fiAnt>
                  <input type=hidden name='ffAnt' value=$ffAnt>
                  <input type=hidden name='idsolicitud' value=$idsolicitud>
                  <input type=hidden name='idusu' value=$idusu>
                  <input id='btnmod' type=submit value='MOdificar' hidden>
                  <input type=hidden name=diaslaborados value=$diaslaborados>
                  <input type=hidden name=diasderecho value=$diasderecho>
                  <input type=text id='tipopermiso' name='tp' value='AAA' hidden>
                  <input type=hidden name='diferenciaDias' id='diferenciaDias' value='0'>
                  <input type='hidden' id='datausuario'  readonly=true>
                  <input type='hidden' id='datausuario_diasLab'  readonly=true >
                  <font>Tipo de Permiso: 
                  ";
                          $sql="BEGIN VAC_PRC_PERMISOSVACACIONES(:permisosvacaciones);END;";
                          $stmt=oci_parse($conn,$sql);
                          oci_bind_by_name($stmt, ":permisosvacaciones", $cursor, -1, OCI_B_CURSOR);
                          $r=oci_execute($stmt);
                          $r=oci_execute($cursor);
                          $permisos=array();
                            /*while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) 
                                  {
                                    echo "<td style='text-align: center; font-size: 120%'>$row[0]</td>";
                                    array_push($permisos, $row[0]);
                                  }
                        echo "</tr>";*/
                        $aux2=0;
                        
                         echo "<select id='selectPermiso' onchange='permiso(this.value);'>"
                        //echo "<select id='selectPermiso' onchange=''>"
                          . "<option value='AAA' selected>Seleccione...</option>";
                            while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) 
                                  {
                                    echo "<option value='$row[0]'>".$row[0]."</option>";
                                  }
                            echo "</select>    Desde : <div class='containerDate'><input type=text id='fechainicio' name='fi'  style='width:100%; max-height:30px' readonly='true' onchange= enableFF();contardias(); disabled></div>
                                <input type='text' id='horaInicio' name='hi' disabled>
                                Hasta : <div class='containerDate'><input type=text id='fechafinal'  name='ff'  style='width:100%; max-height:30px' readonly='true' onchange= contardias(); disabled></div>
                                <input type='text' id='horaFin' name='hf' disabled>
                                </font>
                                &nbsp
                 &nbsp
                 
                <select id='cbxFormaPago' hidden>
                    <option value='CONSUELDO'>
                        Con Sueldo
                    </option>
                    
                    <option value='SINSUELDO'>
                        Sin Sueldo
                    </option>
                    
                    <option value='TIEMPO' id='opcTiempo'>
                        Tiempo por tiempo
                    </option>

                </select>
                                  ";
                            
                          /*while ($permisos[$aux2]) {
                            echo "<td style='text-align: center; font-size: 120%'>
                                            <input type=radio name=a onclick='permiso(\"".$permisos[$aux2]."\")'>
                                        </td>";
                                        $aux2++;
                          }*/
                  echo "
                  
                    
                  <input type=checkbox id='cg'  name='sueldo' onclick='opcionsueldo(\"CONSUELDO\")' hidden>
                  <label id='labelconsueldo' hidden>Con goce</label>
                  <input type=text id='opcionsueldopermiso' name='os' value='BBB'  hidden>
                  </td>
                  </tr>
                  <tr>
                  <td>
                  <textarea type='text' id='comentario' name='comentario' cols='70' rows='3' placeholder='Comentario' hidden></textarea>
                  </td>
                  </tr>
                  <tr>
                  <td colspan=100%> 
                  <input type=button value='Modificar' onclick='validarmodificacion()' style='margin-top:20px;width:10em; height:3em;'>
                  </td>
                  </tr>
                  </table>
                  </form>
                  <form id='forminfo' method='POST'>
                  <input type=hidden name='idusuario' value=$idusuario>
                  <input type=hidden name='iddepartamento' value=$iddepartamento>
                  <input type=submit id='mandarinfo' hidden>
                </form>
                <form id='formmodificar' action='revisar.php' method='POST'>
                <input type=hidden name='accion' value='m'>
                <input type=submit id='modificar' hidden>
                <input type=hidden name=fechaCumple id='fechaCumple' value='$fechaCumple'>
              </form>
              <script>

              </script>
              </body>";
include "funcionesCalendario.php";
        echo"
              </html>
";

        oci_close($conn);
        break;
    case 'd':
        session_start();
        $accion="den";
        $sql="BEGIN VAC_PRC_DENEGAR(:idusuario,:idsol,:accion);END;";
        $stmt=oci_parse($conn,$sql);
        echo $diaslaborados;
        oci_bind_by_name($stmt,":idusuario",$idusuario,32);
        oci_bind_by_name($stmt,":idsol",$idsolicitud,32);
        oci_bind_by_name($stmt,":accion",$accion,32);
        $result=oci_execute($stmt);
        echo $diaslaborados;
        if ($result)
        {
            $_SESSION['mensaje']="dc";
        }
        else {
            $_SESSION['mensaje']="e";
        }
        oci_close($conn);
        header('Location:administrar.php');
        
        break;
    default:
        break;
}
