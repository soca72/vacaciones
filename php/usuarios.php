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
$general=$_POST["general"];
//$opcion1=$_POST["opcion1"];
//$opcion2=$_POST["opcion2"];
//$opcion3=$_POST["opcion3"];
if($_GET["filtro"]){
    $general="ocultos";
}
$ocultar=1;
$textoOcultar="Ocultar";
$idud=$_GET["txtIdud2"];
$priv=2;
$arrayDeps=array();
$arraySucursal=array();
$todaslassucursales=array();
include "conexion.php";
include "funciones.php";
$impresor=new impresor;
$usuario=new usuario;
$imgConf="../images/config.png";
        
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

    $sql="BEGIN VAC_PRC_RETORNADEPARTAMENTOS(:departamentos); END;";
    $stmt= oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ":departamentos", $cursor, -1, OCI_B_CURSOR);
    $r=oci_execute($stmt);
    $r=oci_execute($cursor);
    $todosdepartamentos=array();
    $nombresdepartamentos=array();
    $i=0;
    while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
        $temp=array($row[1] => $row[0]);
        $arrayDeps=array_merge($arrayDeps,$temp);
    }

    $sql="SELECT count(IDSUCURSAL) qty FROM SUCURSALES WHERE ACTIVADA = 'S'";
    $stmt= oci_parse($conn, $sql);
    oci_execute($stmt);
    $cantSucursales = 0;
	while ( ($row = oci_fetch_array($stmt,OCI_ASSOC)) !=false ) {
		$cantSucursales = $row['QTY'];
	}
?>
<html>
    <head>
        <?php
        $impresor->estilosbarranavegacion();
        $impresor->imprimesources();
        ?>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.css' id='theme_base'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.date.css' id='theme_date'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.time.css' id='theme_time'>
        <link rel="stylesheet" href="../css/bootstrap.css">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.css' id='theme_base'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.date.css' id='theme_date'>
        <link rel='stylesheet' href='../js/datepicker/vendor/pickadate/lib/themes/default.time.css' id='theme_time'>
        <link href='../css/select2.min.css' rel='stylesheet'/>
        <script src='../js/datepicker/jquery-3.3.1.min.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.date.js' type='text/javascript' ></script>
        <script src='../js/datepicker/lib/picker.time.js' type='text/javascript' ></script>
        <script src='../js/select2.min.js'></script>
        <script src='../js/bootstrap.min.js' type='text/javascript' ></script>
    </head>
    <style>
        :focus{
            outline:none;
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


        body{
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            margin:0px;
            padding:0px;
        }
        
        label{
            padding-left: 15px;
        }
        option{
            font-size:12px;
            margin-bottom: 5px;
            margin-top: 5px;
        }
        input{
            height: auto;
        }
        
        .contenedor{
            border: 1px #DBDBDB solid;  
            border-radius: 5px;
            margin-left: 20px;
            padding: 10px;
        }
        
        .oculto{
            display:none !important;
        }
        
        .divDia{
            width: 30px;
            height: 30px;
            border: 1px black solid;
            border-radius: 8px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
        }
        
        .diaSelected{
            background-color: green;
            color:white;
        }
        
        .panel-primary table {
            font-size:12px;
        }
        
        .select2-container--open .select2-dropdown--below {
         bottom: -230;
        }
     
        #divPrincipal{
            width:100%;
            height: 88%;
            display: inline-grid;
            grid-template-columns: 25% 75%;
            grid-template-rows: 100%; 
            grid-row-gap: 1em;
        }
        
        #divUsuarios{
            top: 0px;
            position: relative;
            width:100%;
            height:100%;
        }
        
        #comboUsuarios{
            width:100%;
            height:96%;
        }
        
        #txtBusqueda{
            width: 100%;

            text-align: center;
        }
        
        #txtDepartamento{
          width:355px;
        }
        
        #divConfig{
            display: inline-grid;
            grid-template-columns:100%;
            grid-template-rows: 40% 60%;
            min-height: 700px;
        }
        
        #divDatos{
            display: inline-grid;
            grid-template-columns: 69% 30%;
            grid-template-rows: 66% 25% ; 
            grid-row-gap: 1em;
        }
        
        #divDatosGenerales{
            /*
            display: inline-grid;
            grid-template-columns: 115px 200px 110px 200px 110px 200px;
            grid-template-rows: 28px 28px ; 
            grid-row-gap: 1em;
            */
        }
        
        #divDatosGenerales > div{
            display: inline-flex;
            margin-bottom: 10px;
        }
        
        #divDatosGenerales > div > label{
            /*min-width: 80px;*/
        }
        
        #divDatosGenerales > div:nth-child(1){
            width: calc(100%);
            margin-bottom: 0px;
        }
        
        #divDatosGenerales > div:nth-child(2){
            width: calc(50% - 5px);
        }
        
        #divDatosGenerales > div:nth-child(3){
            width: calc(50% - 5px);
        }
        
        #divDatosGenerales > div:nth-child(4){
            width: calc(50% - 5px);
        }
        
        #divDatosGenerales > div:nth-child(5){
            width: calc(50% - 5px);
        }
        
        #divDatosGenerales > div:nth-child(6){
            
        }
        
        #divDatosGenerales > div > input[type='text']{
               width: 100%;
               text-align: center;
        }
        
        #divDatosLaborales{
            /*
            display: inline-grid;
            grid-template-columns: 100%;
            grid-template-rows: 25% 35% 20% 20%; 
            grid-column-gap: .8em;
            */
        }
        #divDatosLaborales>div:first-child{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        #divDatosLaborales>div{
                margin-bottom: 10px;
        }
        #divFechaIngreso{
            /*
            display: inline-grid;
            grid-template-columns: 60% 40%;
            grid-template-rows: 28px; 
            grid-column-gap: .8em;
            */
        }
        #divFechaIngreso > div{
            display: inline-flex;
            width: calc(50% - 5px);
        }
        #divDiasDisponibles{
            /*
            display: inline-grid;
            grid-template-columns: 60% 40%;
            grid-template-rows: 28px; 
            grid-column-gap: .8em;
            */
        }
        
        #divDiasDisponibles > div{
            display: inline-flex;
            width: calc(50% - 5px);
        }
        #divGuardar{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #txtFechaIngreso{
            text-align: center;
            width: 100%;
        }
        #txtDiasDisponibles{
            text-align: center;
            width: 100%;
        }
        
        #divDiasLaborables{
            display: flex;
            justify-content: space-between;
            /*
            display: inline-grid;
            grid-template-columns:  28px 28px 28px 28px 28px 28px 28px;
            grid-template-rows: 28px; 
            grid-column-gap: .8em;
            */
        }
        #divDatosUsuario{
            display: inline-flex;
            align-items: center;
            /*
            display: inline-grid;
            grid-template-columns: 110px 200px 110px 200px 110px 200px;
            grid-template-rows: 28px; 
            grid-row-gap: 1em;
            */
        }
        
        #divPermisos{
            width:95%;
            height:100%;
            display: inline-grid;
            grid-template-columns:40% 60%;
            grid-template-rows: 8% 92%; 
            //margin-top:10px;
        }
        
        #divPermisos>div{
            width:100%;
            margin:0px;
            height:100%;
            overflow-y: auto;
            display:inline-block;
        }
        
        #divPermisosUsuario ul>li>div {
            display: grid;
            width: 100%;
            grid-template-columns:  3% 80% 5%;
            grid-template-rows: 20px;
        }

        #divDepsUsuario ul>li{
            display: inline-flex;
            align-items: center;
        }
        .sucursal{
            margin-left: 10px;
        }
        .close-btn{
            margin-left: 5px;
            cursor: pointer;
            font-size: 12px
        }

        ul>li>div>img{
            cursor:pointer;  
        }
        #divTBPass{
            display: inline-grid;
            grid-template-columns: 75% 15%;
            grid-template-rows: 25px;
            border-radius: 8px;
            border: 1px #9E9E9E solid;
        }
        input[type="text"]{
            border-radius: 8px;
            border: 1px #9E9E9E solid;
            padding: 4px;
                text-align: center;
        }
        #txtPassword{
            background-color: transparent;
            position: relative;
            padding-left: 5px;
            max-width: 100%;
            max-height: 100%;
            border:0px;
            text-align: center;
        }
        #btnImgPass{
            display:none;
            height: 100%;
            border:0px;
            background-color: transparent;
            cursor:pointer;
        }
        #btnGuardar{
            width:60%;
            height:60%;
            min-height:28px;
        }
        
        #ocultarBoton{
        /* width: 30%;
           height:15%;*/
            margin-left: 50px;
        }
        
        #comboDepartamentos{
            width:25%;
            height:100%;
        }
        #comboPermisos{
            width:25%;
            height:100%;
        }
        ul{
            list-style-type:none;
        }        
        #txtnombre{
            text-align: center;
        }
        
        select{
            border-radius:5px;

            padding-left:2px;
            border-width:1px;
            border-style:solid;
            border-color:#BDBDBD;
            height:30px;
            font-size:12px;
            color:#000000;
        }

        .inputUsuarios{
            display:flex;
        }
        #formGeneral{
            width: 20%;
        }
        #txtBusqueda{
            width: 84%;
        }
        body{
            font-size: 12px !important;
        }

        @media(max-width: 1000px){
            #divDatosUsuario, #divDatosGenerales,.inputUsuarios{
                display: block;
            }
            
            #divDatos{
                    display: inline-grid;
                    grid-template-columns: 64% 33%;
                    grid-template-rows: 58% 38% ; 
                    grid-row-gap: 1em;
                }
                #txtDepartamento{
                width:350px;
                }
                .select2 {
                width:200px!important;
                }
                .espaciado{
                    margin-bottom: 10px;
                }
                
                #formGeneral,#txtBusqueda{
                    width: 100%;
                }
                #divPermisos{
                    margin-top:10px;
                }
        }
        
    </style>
    <body>
        <div id="spin" class="modal"></div>
        <div id="modalSucursal" class="modal" role="dialog" >
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title tletra">Agregar Sucursal</h4>
                        <button type="button" class="close" data-dismiss="modal">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="divCondi row col" style="overflow-y:scroll; height: 400px;">
                        <?php
                            $sql="BEGIN VAC_PRC_RETORNASUCURSALES(:sucursales); END;";
                            $stmt= oci_parse($conn, $sql);
                            oci_bind_by_name($stmt, ":sucursales", $cursor, -1, OCI_B_CURSOR);
                            $r=oci_execute($stmt);
                            $r=oci_execute($cursor);
                            $i=0;
                            while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                                $todaslassucursales[$i] = ["idSucursal" => $row[0], "Sucursal" => $row[1], "abrev" => $row[2]];
                                $i++;
                            }

                            echo "<ul>";
                            echo "<li><div><input type='checkbox' id='cbxSuc-33' name='cbxSuc' attr='Todas' value='-33'><label for='cbxSuc-33'>Todas las sucursales</label></div></li>";
                            foreach ($todaslassucursales as $clave =>$valor) {
                                    echo "<li id='Suc".$valor["idSucursal"]."'>";
                                        echo "<div>";
                                            echo "<input type='checkbox' id='cbxSuc".$valor["idSucursal"]."' name='cbxSuc' attr=".$valor["abrev"]." value='".$valor["idSucursal"]."'><label for='cbxSuc".$valor["idSucursal"]."'>".$valor["Sucursal"]."</label>";
                                        echo "</div>";
                                    echo "</li>";
                            }
                            echo "</ul>";
                        ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
            echo "<div class='panel panel-primary' style=' position:absolute  top:0px;'>
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
                </div>";
        ?>
        <?php    
            $pagina='usuarios';
        
            if($general=="no" || $general==""){
                $option1="selected";
                $option2="";
                $option3="";
                $imagen="ocultar";
                $sql = "BEGIN VAC_PRC_USERNMAPROBFILT2(:idu,:pagina,:nombreseids); END ;";
                $stmt=oci_parse($conn,$sql);
                oci_bind_by_name($stmt,':idu',$idusuarioData,32);
                oci_bind_by_name($stmt,':pagina',$pagina,32);
                oci_bind_by_name($stmt, ":nombreseids", $cursor, -1, OCI_B_CURSOR);
            }
            else if ($general=="ocultos"){
                $pagina="ocultos";
                $texto="Mostrar";
                $textoOcultar="Mostrar";
                $ocultar="2";
                $option1="";
                $option2="";
                $option3="selected";
                $imagen="agregar_circulo";
                $sql = "BEGIN VAC_PRC_USERNMAPROBFILT2(:idu,:pagina,:nombreseids); END ;";
                $stmt=oci_parse($conn,$sql);
                oci_bind_by_name($stmt,':idu',$idusuarioData,32);
                oci_bind_by_name($stmt,':pagina',$pagina,32);
                oci_bind_by_name($stmt, ":nombreseids", $cursor, -1, OCI_B_CURSOR);
            }
    
            //    $sql = "BEGIN VAC_PRC_USERNMAPROBFILT2(:idu,:pagina,:nombreseids); END ;";
            //    $stmt=oci_parse($conn,$sql);
            //    oci_bind_by_name($stmt,':idu',$idusuarioData,32);
            //    oci_bind_by_name($stmt,':pagina',$pagina,32);
            //    oci_bind_by_name($stmt, ":nombreseids", $cursor, -1, OCI_B_CURSOR);
            echo "<div id=divPrincipal>
                    <div id=divUsuarios>
                        <div class=inputUsuarios>
                            <input id=txtBusqueda type=text placeholder='Buscar...'  >
                            <form style=margin:0px; id=formGeneral method=POST action=usuarios.php>
                                <select style=width:100% name=general id=general onchange=$('#formGeneral').submit()>
                                    <option value='no' $option1>Visibles</option>
                                    <option value='ocultos' $option3>Ocultos</option>                        
                                </select>                    
                                <input type='hidden' id='txtIdud' name='txtIdud' disabled>
                            </form>
                        </div>
                        
                        <select id=comboUsuarios size=100%>";
                            oci_execute($stmt);
                            oci_execute($cursor);
                            while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false){
                                $idu=$row[0];                                  
                                
                                if($rowAnt!=$row[2]){
                                    echo "<option id='".$row[2]."-". $row[1]."' value='".$row[0]."' class='optionsUsuarios'>";
                                    echo "$row[1]";
                                    echo "</option>";
                                }
                                $rowAnt=$row[2];
                            }
                   echo "</select>";
              echo "</div>";
        ?>
                    <div id="divConfig">
                        <div id="divDatos">
                            <div id="divDatosGenerales" class="contenedor">
                                <div><label hidden for="txtIdud">Idud: </label><input type="hidden" id="txtIdud" name="txtIdud" disabled></div>
                                <div><label for="txtNombre" >Nombre: </label>&nbsp;<input type="text" id="txtNombre" disabled></div>
                                <!--label for="txtApaterno">A. Paterno:</label><input type="text" id="txtApaterno" disabled>
                                <label for="txtAmaterno">A. Materno:</label><input type="text" id="txtAmaterno" disabled-->
                                <div><label for="txtDireccion">Direccion:</label>&nbsp;<input type="text" id="txtDireccion" disabled></div>
                                <div><label for="txtTelefono">Telefono:</label>&nbsp;<input type="text" id="txtTelefono" disabled></div>
                                <div><label for="txtCorreo">Correo:</label>&nbsp;<input type="text" id="txtCorreo" disabled></div>
                                <div><label for="txtDepartamento">Departamento:</label>&nbsp;
                                    <select id="txtDepartamento" disabled>
                                        <option></option>
                                        <?php
                                        foreach ($arrayDeps as $clave =>$valor) {
                                            echo "<option value='".$valor."'>".$clave."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div><label for="txtSucursal">Sucursal:</label>&nbsp;
                                    <select id="txtSucursal" disabled>
                                        <option></option>
                                        <?php
                                        foreach ($todaslassucursales as $clave =>$valor) {
                                            echo "<option value='".$valor["idSucursal"]."'>".$valor["Sucursal"]."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div id="divDatosLaborales" class="contenedor">
                            <div>Dias Laborables</div>
                            <div id="divDiasLaborables">
                                <div class="divDia" nDia="1"><b>L</b></div>
                                <div class="divDia" nDia="2"><b>M</b></div>
                                <div class="divDia" nDia="3"><b>M</b></div>
                                <div class="divDia" nDia="4"><b>J</b></div>
                                <div class="divDia" nDia="5"><b>V</b></div>
                                <div class="divDia" nDia="6"><b>S</b></div>
                                <div class="divDia" nDia="7"><b>D</b></div>
                            </div>
                            <div id="divFechaIngreso">
                            <div>Fecha Ingreso: </div><div><input type="text" id="txtFechaIngreso"></div>
                            </div>
                            <div id="divDiasDisponibles">
                            <div>Dias Disponibles: </div><div><input type="text" id="txtDiasDisponibles"></div>
                            </div>
                            <div>
                                
                            </div>
                            </div>
                            <div id="divDatosUsuario" class="contenedor ">
                                <div class="espaciado"><label for="txtUsuario">Usuario: </label><input type="text" id="txtUsuario"></div>
                            <div>
                            <label for="txtPassword">Password:</label>            
                            <div id="divTBPass" class="espaciado"><input type="password" id="txtPassword" disabled><button id="btnImgPass"><img id="imgPass" src="../images/visible.png"> </button></div>
                            </div>
                            <div class="ms-2">
                            <?php
                                echo "
                                <button class='m-2 ' id='ocultarBoton' disabled>$textoOcultar usuario</button>";
                                ?>
                            </div>
                            </div>
                            <div id="divGuardar">
                            <button id="btnGuardar" disabled>Guardar</button>  
                            </div>
                            
                        </div>
                        <div id="divPermisos" class="contenedor">
                            <label id="tituloPermisos"></label>
                            <label for="" id="tituloDeps"></label>
                            <div id="divPermisosUsuario" class="oculto">
                                <ul>
                                    <li>
                                    <div><input type='checkbox' id='cbxPermisosTodos' name="todosPermisos" value="-44"><label for='cbxPermisosTodos'>Todos</label></div>
                                    </li>
                                </ul>
                                <?php
                                    $sql="BEGIN VAC_PRC_RETORNAPERMISOS( :permisos); END;";//Obtenemos todos los permisos
                                    $cursor = oci_new_cursor($conn);
                                    $stmt= oci_parse($conn, $sql);
                                    oci_bind_by_name($stmt, ":permisos", $cursor, -1, OCI_B_CURSOR);
                                    $r=oci_execute($stmt);
                                    $r=oci_execute($cursor);
                                    while (($row = oci_fetch_array($cursor, OCI_BOTH)) != false) {
                                        
                                        echo "<ul><li>";
                                        echo "<div><input type='checkbox' name='cbxPermiso' id='cbxPermisos".$row[1]."' value='".$row[1]."'><label for='cbxPermisos".$row[1]."' >".$row[2]."</label>";
                                            switch($row[1]){
                                                case 4:
                                                    echo "<img src='".$imgConf."' id='imgAut' onclick='llenarDeps(\"AUT\")'>";
                                                    break;
                                                case 2:
                                                    echo "<img src='".$imgConf."' id='imgSol' onclick='llenarDeps(\"SOL\")'>";
                                                    break;
                                            }
                                            
                                            echo "</div>";
                                            echo " </li></ul>";
                                    }
                                
                                ?>
                            </div>
                            
                            <div id="divDepsUsuario" class="oculto">
                                <ul>
                                    <li>
                                        <div>
                                            <input type='checkbox' id='cbxDepsTodos' name="todosDeps" value="-44"><label for='cbxDepsTodos'>TODOS</label>
                                        </div>
                                    </li>
                                </ul>
                                <?php
                                    foreach ($arrayDeps as $clave =>$valor) {
                                        echo "<ul>";
                                            echo "<li id='divSuc".$valor."'>";
                                                echo "<div>";
                                                    echo "<input type='checkbox' id='cbxDeps".$valor."' name='cbxDep' value='".$valor."'><label for='cbxDeps".$valor."'>".$clave."</label>";
                                                echo "</div>";
                                            echo "</li>";
                                        echo "</ul>";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
        <form id='forminfo' method='POST'>
            <input type=hidden name=idusuario value=<?php echo $idusuario?>>
            
            <input id='mandarinfo' type=submit value='oculto' hidden>
        </form>
        
        <form id='ocultarUsuario' method='POST' action='ocultarUsuarios.php'>
         <input type='hidden' id='idOculto' name='idOculto'>
         <input type='hidden' id='nuevo' name='nuevo'>
        </form>
    </body>
    <script>
        
     var depsSol=[], depsAut=[], sucAut=[], tipoDep, diasLaborables=[],$input, picker,diasDisponibles=0, idOcultoBoton, nuevoBoton;
     let depSelec;
    $("#txtBusqueda").on("keyup",function (){
          var regex=new RegExp($("#txtBusqueda").val().toUpperCase());
          //var regex=new RegExp($("#txtBusqueda").val());
          $(".optionsUsuarios").each(function(){
              if(!regex.test($(this).attr('id').toUpperCase())){
                   $(this).hide();
              }
              else{
                    $(this).show();
                }
       		});
    });
    
    <?php
        echo"function ocultar(id,nuevo){
            if(confirm('$texto usuario con id '+ id + '?')){
            document.getElementById('idOculto').value=id;
            document.getElementById('nuevo').value=nuevo;
            document.getElementById('ocultarUsuario').submit();
            }
            }";
    ?>
    
    $("#btnGuardar").on("click",function(){
        guardarDiasLaborables();
    });
    
    
    $("#comboUsuarios").on("change",consultaDatosUsuario);

    
    function consultaDatosUsuario(){
        $.ajax({
            type:"GET",
            url:"datosUsuarios.php",
            data:{
                idu:$("#comboUsuarios").val()
            },
            datatype:"text",
            success:llenaDatos,
            error: function (xhr,ajaxOptions, thrownError){alert("Error accesando al servidor ");},
            async:true,
            timeout: 60000
        });
    }
    
    
    $("#txtPassword").on("keyup",function (){
        habilitarBtnPassword();
    });
    
    $("#btnImgPass").on("click",function (){
       if($("#imgPass").attr("src")=="../images/visible.png"){
            $("#imgPass").attr("src","../images/invisible.png"); 
            $("#txtPassword").attr("type","text");
       }
       else{
           $("#imgPass").attr("src","../images/visible.png"); 
            $("#txtPassword").attr("type","password");
       }
    }); 
    
    $(".divDia").on("click",function (){
       var ndia=$(this).attr("ndia");
       if(diasLaborables.indexOf(ndia)<0){
           diasLaborables.push(ndia);
           $(this).addClass("diaSelected");
       }
       else {
           diasLaborables.splice(diasLaborables.indexOf(ndia),1);
           $(this).removeClass("diaSelected");
       }
       console.log(diasLaborables.join(","));
    });
       
    $("#cbxPermisos4").on("change",function (){
        
    if($("#cbxPermisos4").prop("checked")){
    $("#imgAut").show();
    }
    else {
        if(tipoDep=="AUT")$("[id^='cbxDeps']").prop('checked', false);
        $("#imgAut").hide();
        
        depsAut=[];
    }
    });  
    
    $("#cbxPermisos2").on("change",function (){
    if($("#cbxPermisos2").prop("checked")){
    $("#imgSol").show();
    }
    else{
        if(tipoDep=="SOL")$("[id^='cbxDeps']").prop('checked', false);
        $("#imgSol").hide();
        depsSol=[];
    }
    });     
    
    $("[id^='cbxPermisos']").on("click",function (){
        if($(this).prop("id")=="cbxPermisosTodos"){
            if($(this).prop("checked")){
            $("#imgAut").show();
            $("#imgSol").show();
            $("[name^='cbxPermiso']").prop("checked",true);
        }
        else{
            depsSol=[];
            depsAut=[];
            $("#imgAut").hide();
            $("#imgSol").hide();
            $("[id^='cbxDeps']").prop('checked', false);
            $("[name^='cbxPermiso']").prop("checked",false);
        }
        }
        
        
        if($(this).prop("checked")){
            chkPermisos();
        $.ajax({
            type:"GET",
            url:"agregapermiso.php",
            data:{
                idu:$("#txtIdud").val(),
                idp:$(this).val()
            },
            datatype:"text",
            error: function (xhr,ajaxOptions, thrownError){alert("Error accesando al servidor ");},
            async:true,
            timeout: 60000

        }); 
                        
                }
        else{
        
        $.ajax({
            type:"GET",
            url:"eliminapermiso.php",
            data:{
                idu:$("#txtIdud").val(),
                idp:$(this).val()
            },
            datatype:"text",
            error: function (xhr,ajaxOptions, thrownError){alert("Error accesando al servidor ");},
            async:true,
            timeout: 60000
        }); 
                        
        }
        

    });
    
    $("[id^='cbxDeps']").on("click",function (){
        depSelec = $(this).val();
        $("ul li button").remove();
        $("input[name='cbxDeps']").prop('checked', false);
        if($(this).prop("checked")){
            $("#modalSucursal").modal({backdrop: false, show: true});
        }
    });

    function agregarSuc(idDep){
        depSelec = idDep;
        $("#modalSucursal").modal({backdrop: false, show: true});
    }
    
    $("[id^='cbxSuc']").on("click",function (){
        let idSucursal = $(this).val();
        let idUsuario = $("#txtIdud").val();
        let abrev = $(this).attr("attr");
        $.ajax({
            type:"GET",
            url:"agregadepartamento.php",
            data:{
                idu: idUsuario,
                idd: depSelec,
                idSucursal: idSucursal
            },
            datatype:"text",
            success: function(){
                if(depSelec === "-44"){
                    $("#divDepsUsuario input[name='cbxDep']").each(function(){
                        let idDep = ""+$(this).val(); 
                        depsAut.push({
                            idDep: idDep,
                            sucursal: ""
                        });
                    });

                    depsAut.forEach(function(elemento){
                        elemento.sucursal = abrev;
                    });

                }else{
                    let index = depsAut.findIndex(item => item.idDep === ""+depSelec);
                    if (index !== -1) {
                        depsAut[index].sucursal = depsAut[index].sucursal +","+ abrev;
                    }else{
                        depsAut.push({
                            idDep: depSelec,
                            sucursal: abrev
                        });
                    }
                }
                llenarDeps("AUT");
            },
            error: function (xhr,ajaxOptions, thrownError){alert("Error accesando al servidor ");},
            async:true,
            timeout: 60000
        });
    });      
      
    function inicializar(){
     $("#txtDepartamento").select2({
         width: 'resolve',
         allowClear: true,
         placeholder: "",
     });
     $("#txtSucursal").select2({
         width: 'resolve',
         allowClear: true,
         placeholder: "",
     });
     $input=$("#txtFechaIngreso").pickadate({
        format: 'dd-mm-yyyy',
        selectMonths: true,
        selectYears: 70,
        min: new Date(1949,1,1),
        max: new Date()
        });

     picker =$input.pickadate('picker');    
    }
    
    function llenaDatos(datos){
        $("#btnGuardar").prop("disabled", false);
        $("#ocultarBoton").prop("disabled",false);
        datos=JSON.parse(datos);
        $("#txtNombre").val(datos.apaterno + " " + datos.amaterno + " " + datos.nombre);
        $("#txtApaterno").val(datos.apaterno);
        $("#txtAmaterno").val(datos.amaterno);
        $("#txtDireccion").val(datos.direccion);
        $("#txtTelefono").val(datos.celular);
        $("#txtCorreo").val(datos.email);
        $("#txtUsuario").val(datos.usuario);
        $("#txtPassword").val(datos.clave);
        $("#txtIdud").val(datos.idud);
        $("#txtIdud2").val(datos.idud);
        $("#txtDiasDisponibles").val(datos.diasDisponibles);
        $("#txtDepartamento").val(datos.departamento).trigger("change");
        $("#txtSucursal").val(datos.sucursal).trigger("change");
        llenarFechaIngreso(datos.fechaIngreso);
        llenarDiasLaborables(datos.diasLaborables);
        depsSol=datos.depsSol.split(",");
        depsAut=datos.depsAut;
        diasDisponibles=datos.diasDisponibles;
        llenarPermisos(datos.permisos);
        idOcultoBoton =datos.idud;
    }
    
    $("#ocultarBoton").on("click",function(){
        $("#idOculto").val(idOcultoBoton);
       $("#nuevo").val(<?php echo $ocultar?>);
        document.getElementById('ocultarUsuario').submit();
        
       
    });
    
    function llenarPermisos(permisos){
        habilitarBtnPassword();
        $("#divPermisosUsuario").removeClass("oculto");
        $("#tituloPermisos").html("<b>Permisos</b>"); 
        $("#tituloDeps").html(""); 
        $("#divDepsUsuario").addClass("oculto");
        $("[id^='cbxPermisos']").prop('checked', false);
        $("[id^='cbxDeps']").prop('checked', false);
        if(permisos!=null){
        permisos=permisos.split(",");
        permisos.forEach(function(dato){
         $("#cbxPermisos"+dato).prop('checked', true);
        });
        }
      if($("#cbxPermisos4").prop("checked")){
       $("#imgAut").show();
      }
      else $("#imgAut").hide();
      if($("#cbxPermisos2").prop("checked")){
       $("#imgSol").show();
      }
      else $("#imgSol").hide();
      chkPermisos();
    }   
    
    function llenarDeps(tipo){
        let cantSucursales = <?php echo json_encode($cantSucursales); ?>;
        $("#divDepsUsuario").removeClass("oculto");
        $("#divDepsUsuario").scrollTop(0);
        $("[id^='cbxDeps']").prop('checked', false);
        $("ul li .sucursal").remove();
        $("ul li button").remove();
        switch(tipo){
            case 'AUT':
                $("#tituloDeps").html("<b>Departamentos Autorizables</b>");
                depsAut.forEach(function(dato){
                    $("#cbxDeps"+dato.idDep).prop('checked', true);
                    let totalSuc = dato.sucursal.split(",").length;
                    let sucu = dato.sucursal.split(",");
                    if(parseInt(cantSucursales) !== totalSuc){
                        sucu.forEach(function(sucurles){
                            $("#divSuc"+dato.idDep).append("<div class='sucursal'><span class='badge text-bg-secondary'>"+ sucurles +" <span class='close-btn' onclick='eliminarSucursal(this,"+dato.idDep+",\"" + sucurles + "\")'>x</span></span></div>");
                        });
                    }else{
                        $("#divSuc"+dato.idDep).append("<div class='sucursal'><span class='badge text-bg-secondary'>Todas<span class='close-btn'>x</span></span></div>");
                    }
                    $("#divSuc"+dato.idDep).append("<div style='margin-left:5px;'><button title='Agregar Sucursal' class='btn diaSelected' onclick='agregarSuc("+dato.idDep+")'><span class='glyphicon glyphicon-plus'></span></button></div>");
                });

                break;
            case 'SOL':
                $("#tituloDeps").html("<b>Departamentos Solicitables</b>"); 
                depsSol.forEach(function(dato){
                    $("#cbxDeps"+dato).prop('checked', true);
                });
                break;
        }
        tipoDep=tipo;
      if($("[name='cbxDep']").prop('checked'))$("#cbxDepsTodos").prop("checked",true);
      
    }

    function eliminarSucursal(dato,idDep,suc){
        let idUsuario = $("#txtIdud").val();
        let componente = 
        $.ajax({
            type:"GET",
            url:"eliminadepartamento.php",
            data:{
                idUsr:idUsuario,
                idDep:idDep,
                idSuc:suc
            },
            datatype:"text",
            success: function(){
                dato.parentElement.parentElement.remove();
                $("cbxDeps"+idDep).prop("checked",false);
            },
            error: function (xhr,ajaxOptions, thrownError){alert("Error accesando al servidor ");},
            async:true,
            timeout: 60000
        });

    }
    
    function llenarFechaIngreso(fecha){
        var date = new Date(fecha);
        picker.set('select', date);
        $("#txtFechaIngreso").val(picker.get('highlight', 'dd-mm-yyyy'));
    }
    
    function llenarDiasLaborables(dias){
        $(".divDia").removeClass("diaSelected");
        diasLaborables=[];
        if(dias!=null){
            diasLaborables=dias.split(",");
            diasLaborables.forEach(function (dia){
                $("[ndia="+dia+"]").addClass("diaSelected");
            });
        }   
    }
    
    function chkPermisos(){
        var todos=true;
      $("[name='cbxPermiso']").each(function(){
          if(!$(this).prop("checked"))todos=false;
      });
      $("#cbxPermisosTodos").prop("checked",todos);
    }
    function chkDeps(){
        var todos=true;
        $("[name='cbxDep']").each(function(){
            if(!$(this).prop("checked"))todos=false;
        });
        $("#cbxDepsTodos").prop("checked",todos);
    }
    
    function habilitarBtnPassword(){
        if($("#txtPassword").val()!=null && $("#txtPassword").val()!="")$("#btnImgPass").show();
        else $("#btnImgPass").hide();
    }
    
    function guardarDiasLaborables(){
        
        $.ajax({
            type:"GET",
            url: "mandarDatos.php",
            data:{
                idu:$("#txtIdud").val(),
                dias:diasLaborables.join(",").toString(),
                fechaingreso:$("#txtFechaIngreso").val(),
                accion:1,
                idDepto:$("#txtDepartamento").val(),
                //totalDias:diasDisponibles-$("#txtDiasDisponibles").val()
                totalDias:$("#txtDiasDisponibles").val()
            },
            success: consultaDatosUsuario,
            datatype:"text",
            error: function (xhr,ajaxOptions, thrownError){alert("Error accesando al servidor ");},
            async:true,
            timeout: 60000

        }); 
    }  
    
    $("body").on("load",inicializar());
    var $body = $("body");
    $(document).on({
        ajaxStart: function () {
            $body.addClass("loading");
        },
        ajaxStop: function () {
            $body.removeClass("loading");
        }
    });
    </script>
</html>
