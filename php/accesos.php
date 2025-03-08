<?php 
error_reporting(E_ALL ^ E_NOTICE);
include "conexion.php";
include "funciones.php";
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
$idusuario=$_SESSION["idusuario"];
$idusuarioData=$_SESSION["idusuariodata"];
$diaslaborados=$_POST['diaslaborados'];
$idusuarioagregarpermisos=$_POST['idua'];
$nombreusuarioagrega=$_POST['nombreusuarioagrega'];
$aniof= $_POST['anio'];
$nomuser=$_SESSION["nomuser"];
$todosDepSol="true";
$todosDepAut="true";
$todosPermisos="true";
if(!$aniof)
{$aniof=2012;}
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
if(!$idusuario)
{
  echo "Algo ocurrió, intentelo mas tarde porfavor";
}
else
{
        echo '<html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" /> 
          <title>
            Vacaciones
          </title>
        ';
        
        $impresor->estilosbarranavegacion();
        $impresor->imprimesources();
        echo "
        <script type='application/javascript' src='../js/busquedadinamicaaccesos.js'></script>  
            <script>
                    var idpermisos = '';
                    var iddepsol ='';
                    var iddepaprob='';
            </script>
            <style>
html *{
font-size:102% !important
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

            </style>
        </head>
        <body>
        <div class='panel panel-primary' style=' position:absolute  top:0px;'>
                <div class='panel-heading' style='max-height: 72;'> 
                  <!--Información del asociado  -->
                <table class='table'>
                <tr>";
                    //Este segmento de codigo revisa los permisos del usaurio e imprime los botones necesarios con base en ello, 
                    //el cuerpo de funcion esta en funciones.php. Si vas a modificarlo, solo ten cuidado con no arruinar los estilos.=$width
                    
                    opciones($permisosaimprimir,7,"");
                    
                  echo $nomuser."</a></td>
                    <td width='1%'> 
                          <a href='cerrarsesion.php'>
                            <span class='glyphicon glyphicon-log-out'></span>
                          </a>
                    </td>
                  </tr>
                  </table>
                </div>

                <table >
                <tr>
                        ";
		echo "<td style='text-align: center; font-size: 120%'' width='6%' ></td>";
 		 echo "<td style='text-align: center; font-size: 120%' width='40%'></td>";
            	echo "<td style='text-align: center; font-size: 120%'' width='10%' ></td>";
            	echo "<td style='text-align: center; font-size: 120%'' width='40%'></td>";
 
        echo "
        </tr>
        </table>
</div>
</div>
        
";  

                  if(in_array(7, $permisos) || in_array(8, $permisos))
                  {
                    //Imprime una tabla con un input para poder buscar a un usaurio en especifico
                    //Las funciones javascript llamadas de aqui en adelante se encuentran en busquedadinamicaaccesos.js

                    //nombreusuario
                      echo '
                      <table  class="table table-bordered mytable">
                       <tr>
       <td style="text-align: center">
       <input type="text" style="text-align:center;text-transform:uppercase;" id="busqueda" class=\'\' placeholder="Seleccione un usuario" size="80" onkeyup="showResult(this.value);if(this.value!=\'\'){getElementById(\'livesearch\').style.display=\'block\';}else{getElementById(\'livesearch\').style.display=\'none\'}" value='.$nombreusuarioagrega.'>
           <ul id="livesearch" style="width: 522px;margin:auto;border:0px;display:none;border:1px solid black !important;" ></ul>
             </td>      
                      </tr>
                      </table>
                       
                      ';


                if($idusuarioagregarpermisos)
                {
//PERMISOS
//*******************************************************************************************************************************************************************
//*******************************************************************************************************************************************************************                  

                echo '
                 <table class="table" style="border-collapse: separate">
<tbody>
                           <tr>
                            <th width="1%" style="text-align:center"></th>
			 <th width="20%" style="text-align:center; color: #337ab7">Permisos</th>
                            <th width="20%" style="text-align:center; color: #337ab7">Departamentos solicitables</th>                         
                            <th width="20%" style="text-align:center; color: #337ab7">Departamentos autorizables</th>
                           </tr>
			<tr>
                          <td><input type="checkbox" id="cbxtodospermisos" onchange="javascript:habilitatodoslospermisos(idpermisos);" ></td>
                          <td style="text-align:left"><li class="list-group-item" style="padding-top: 3px;">TODOS</li></td>
                        ';

                                $sql="BEGIN VAC_PRC_RETORNADEPARTAMENTOS(:departamentos); END;";
                                $stmt= oci_parse($conn, $sql);
                                oci_bind_by_name($stmt, ":departamentos", $cursor, -1, OCI_B_CURSOR);
                                $r=oci_execute($stmt);
                                $r=oci_execute($cursor);
                                $todosdepartamentos=array();
                                $nombresdepartamentos=array();
                                $i=0;
                                while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                                              $nombresdepartamentos[$i]=$row[1];
                                              $todosdepartamentos[$i]=$row[0];
                                              $i++;
                                }
                                $sql="BEGIN VAC_PRC_RETORNADPTOSOL(:idu,:departamentossolicitables); END;";
                                $cursor = oci_new_cursor($conn);
                                $stmt= oci_parse($conn, $sql);
                                oci_bind_by_name($stmt,':idu',$idusuarioagregarpermisos,32);
                                oci_bind_by_name($stmt, ":departamentossolicitables", $cursor, -1, OCI_B_CURSOR);
                                $r=oci_execute($stmt);
                                $r=oci_execute($cursor);
                                $departamentossolicitables=array();
                                $i=0;
                                while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                                     $departamentossolicitables[$i]=$row[0];//Guardamos los departamentos que el usuario puede solicitar
                                     $i++;
                                  }
                                echo '
			   <td ROWSPAN=10 style="text-align:left;padding-top: 0px;padding-left: 40px" width="20%">
                                <br>
                              <div  style="float:left; padding-left:20px;   width: 450px; height: 450px; overflow-y: scroll;">';
                                //Obtenemos todos los departamentos
                                echo '
                                <div class="list-group ">
                                <ul class="list-group"> 
                                <li class="list-group-item" style="display: inline; padding-top: 3px; border:0" >
                                <input type="checkbox" id="todosdepsol" onchange=javascript:setAllRequestableDepartments(iddepsol)>
                                TODOS
                                </li>
                                <br>
                                ';
                                $i=0;
                                while ($todosdepartamentos[$i]) {
                                  echo "<script>
                                  iddepsol='depsol_$todosdepartamentos[$i],'+iddepsol;
                                  </script>";  

                                              echo "<li class='list-group-item' style='display: inline; padding-top: 3px; border:0' >
                                              <input type='checkbox' id='depsol_$todosdepartamentos[$i]' onchange=javascript:recibedepartamentosol('depsol_$todosdepartamentos[$i]')
                                              
                                              ";
                                                if(in_array($todosdepartamentos[$i], $departamentossolicitables))
                                                {
                                                  echo " checked ";
                                                }
                                                else{
                                                    $todosDepSol="false";
                                                }
                                                echo "
                                                >
                                              $nombresdepartamentos[$i]</li>
                                              <br>
                                              "; 
                                              $i++;                               
                                            }     
                                            //Cerramos lista, div de lista y div que acomoda a la lista
                                           echo '
                                    </ul> 
                                  </div>
                              </div>
			</td>';
  //Buscamos los departamentos que el usuario puede aprobar
                                $sql="BEGIN VAC_PRC_DEPARTAMENTOSDEUSUARIO(:idu,:departamentos); END;";
                                $cursor = oci_new_cursor($conn);
                                $stmt= oci_parse($conn, $sql);
                                oci_bind_by_name($stmt,':idu',$idusuarioagregarpermisos,32);
                                oci_bind_by_name($stmt, ":departamentos", $cursor, -1, OCI_B_CURSOR);
                                $r=oci_execute($stmt);
                                $r=oci_execute($cursor);
                                $departamentosusuario=array();
                                $i=0;
                                while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                                     $departamentosusuario[$i]=$row[0];//Guardamos los departamentos que el usuario puede aprobar
                                     $i++;
                                  }  
                              echo '
 			  <td ROWSPAN=10 style="text-align:left;padding-top: 0px;padding-left: 40px">
                              <div  style="float:left; margin-top:20px;  width: 450px; height: 450px; overflow-y: scroll;">';
                                //Obtenemos todos los departamentos

                                echo '
                                <div class="list-group ">
                                <ul class="list-group"> 
                                <li class="list-group-item" style="display: inline; padding-top: 3px; border:0" >
                                <input type="checkbox" id="todosdepaprob" onchange=javascript:setAllApprovableDeptos(iddepaprob)
                                              style="display: inline">TODOS
                                              </li>
                                              <br>

                                ';

                                $sql="BEGIN VAC_PRC_RETORNADEPARTAMENTOS(:departamentos); END;";
                                $stmt= oci_parse($conn, $sql);
                                oci_bind_by_name($stmt, ":departamentos", $cursor, -1, OCI_B_CURSOR);
                                $r=oci_execute($stmt);
                                $r=oci_execute($cursor);
  
                                $i=0;
                                while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                                  echo "<script>
                                  iddepaprob='dep_$row[0],'+iddepaprob;
                                  </script>"; 
 

                                              echo "<li class='list-group-item' style='display: inline; padding-top: 3px; border:0' >
                                              <input type='checkbox' id='dep_$row[0]' onchange=javascript:recibedepartamento('dep_$row[0]')
                                              style='display: inline'
                                              ";
                                                if(in_array($row[0], $departamentosusuario))
                                                {
                                                  echo " checked ";
                                                }
                                                else{
                                                  $todosDepAut="false";  
                                                }

                                                echo "
                                              >
                                              $row[1]</li>
                                              <br>
                                              ";                                
                                            }     
                                            //Cerramos lista, div de lista y div que acomoda a la lista
                                           echo '
                                    </ul> 
                                  </div>
                              </div>
			</td>';
                                //Revisamos los permisos que el usuario en cuestion tiene y los guardamos
                                $sql="BEGIN VAC_PRC_permisosusario(:idusuario , :permisos); END;";
                                $cursor = oci_new_cursor($conn);
                                $stmt= oci_parse($conn, $sql);
                                oci_bind_by_name($stmt, ":permisos", $cursor, -1, OCI_B_CURSOR);
                                oci_bind_by_name($stmt,':idusuario',$idusuarioagregarpermisos,32);
                                $r=oci_execute($stmt);
                                $r=oci_execute($cursor);
                                $permisosu=array();
                                $permisosaimprimir=array();
                                $i=0;
                                $auximp=0; 
                                while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                                    $permisosu[$i] = $row[0]; //Guaramos permisos
                                    $i++;
                                }     
                                $sql="BEGIN VAC_PRC_RETORNAPERMISOS( :permisos); END;";//Obtenemos todos los permisos
                                $cursor = oci_new_cursor($conn);
                                $stmt= oci_parse($conn, $sql);
                                oci_bind_by_name($stmt, ":permisos", $cursor, -1, OCI_B_CURSOR);
                                $r=oci_execute($stmt);
                                $r=oci_execute($cursor);
                                $checkboxpermisos=array();
                                $permisosusuario=array();
                                $listitemppermisos=array();
                                $i=0;
                                echo '
                                <div class="list-group">
                                <ul class="list-group"> ';
                                while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                                  echo "<script>
                                  idpermisos=idpermisos+'|'+$row[1];
                                  </script>";                                  
                                  echo "<tr>";
                                  if(in_array($row[1], $permisosu))//Si permiso obtenido del catalogo se encuentra en los permisos que el usaurio ya tiene, lo marcamos como checked
                                          {

                                            //$checkboxpermisos[$i]='<input type="checkbox" id="'.$row[1].'" onchange="javascript:recibepermiso('.$row[1].');" checked>';
                                            echo '<td><input type="checkbox" id="'.$row[1].'" name="chkPermiso" onchange="javascript:recibepermiso('.$row[1].');" checked></td>';
                                          }
                                  else
                                          {
                                              echo '<td><input type="checkbox" id="'.$row[1].'" name="chkPermiso" onchange="javascript:recibepermiso('.$row[1].');"></td>'; 
                                              $todosPermisos="false";
                                          }
                                  //Guardamos los nombres en una lista
                                  //$listitemppermisos[$i]='<li class="list-group-item" style="padding-top: 3px;">'.strtolower( $row[2]).'</li>'; 
                                  echo '<td style="text-align:left"><li class="list-group-item" style="padding-top: 3px;">'.strtoupper( $row[2]).'</li></td>'; 
                                  $i++;
                                  echo "</tr>";
                                }
                              /*
                                for ($i=0; $i <8 ; $i++) { //Imprimimos la lista junto con su checkbox
                                  echo "<tr><td>$checkboxpermisos[$i]</td><td>$listitemppermisos[$i]</td></tr>";
                                }
                                */
                              echo '
                        </ul> 
                      </div>
                     </tr>
	<tr>
<td colspan="2" rowspan="7">
</td>
</tr>
		</tbody>
                </table>
                <div style="float:left  ;padding-left: 10px;" >
                  <table class="table "> 
                  </table>
                  </div>
                ';
//DEPARTAMENTOS SOLICITABLES
//*******************************************************************************************************************************************************************
//*******************************************************************************************************************************************************************     



//*******************************************************************************************************************************************************************                    //*******************************************************************************************************************************************************************    

//DEPARTAMENTOS APROBABLES
//*******************************************************************************************************************************************************************
//*******************************************************************************************************************************************************************                                  
//*******************************************************************************************************************************************************************                    //*******************************************************************************************************************************************************************          

                          }

                  }
echo "
                  <form id='forminfo'   method='POST'>
                  <input type=hidden name=idusuario value=$idusuario>
                  <input type=hidden name=idua id='datausaurio' value='$idusuarioagregarpermisos'>
                  <input   id='nombreusuarioagrega' name='nombreusuarioagrega'  hidden>
                  <input type=hidden name=diaslaborados value=$diaslaborados>
                  <input id='mandarinfo' type=submit value='oculto' hidden>
                  </form>
                  <script>
                  $('#todosdepsol').prop('checked',$todosDepSol);
                  $('#todosdepaprob').prop('checked',$todosDepAut);
                  $('#cbxtodospermisos').prop('checked',$todosPermisos);
                  </script>
                    ";
          }
 ?>
