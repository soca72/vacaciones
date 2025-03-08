
<?php
error_reporting(E_ALL ^ E_NOTICE);
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
$usuarioSesion=$_SESSION["idusuario"];
class impresor
  {
    function estilosbarranavegacion()
    {
        $usuarioSesion=$_SESSION["idusuario"];
echo "
          <style>
          .panel-primary > .panel-heading {
    color: #fff;
    background-color: #337ab7;
    border-color: #337ab7;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
.panel-heading {
    padding: 10px 15px;
    border-bottom: 1px solid transparent;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
}
.glyphicon {
    position: relative;
    top: 1px;
    display: inline-block;
    font-family: 'Glyphicons Halflings';
    font-style: normal;
    font-weight: normal;
    line-height: 1;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.table > thead > tr > td.warning, .table > tbody > tr > td.warning,
.table > tfoot > tr > td.warning, .table > thead > tr > th.warning,
.table > tbody > tr > th.warning, .table > tfoot > tr > th.warning,
.table > thead > tr.warning > td, .table > tbody > tr.warning > td,
.table > tfoot > tr.warning > td, .table > thead > tr.warning > th,
.table > tbody > tr.warning > th, .table > tfoot > tr.warning > th {
    background-color: #FFF2AB;
}
          label{
          font-weight:normal !important;
          }
            .contadorPendientes{
            background-color: #D62C2C;
             border: 4px solid #D62C2C;
             color: white;
             border-radius:7px;
                }
        .table .opcion:hover{
        background-color:#3B6D91 !important;
            }
            
        .panel-heading a:link, .panel-heading a:visited,.panel-heading a:any-link,.panel-heading a:-webkit-any-link
            {
              color: white !important;
              padding:8px 10px;
              text-align: center;
              text-decoration: none !important;
              display: inline-block;
            }
            .panel-body table {
                font-size:12px;
             }
            #livesearch a {
              padding:8px 10px;
              text-align: center;
              text-decoration: none;
              display: inline-block;
         }
         

         td 
            {
                text-align: center;
            }

        .panel 
            {
                margin-bottom: 0px;
            }
         
        #rcorners1 
            {
                border-radius: 15px;
            }

        .table 
            {
                border-bottom:0px !important;
            }

        .table th, .table td 
            {
               
            }
        .table-bordered > thead > tr > th,.table > tbody > tr > td{
        border:none;
        border-top:none !important;
        }
     

        .fixed-table-container 
            {
                border:0px !important;
            }
        .tablaInfo{
                width:100%;
            }
            .vacaciones a, .vacaciones span, .vacaciones
            {
            opacity: 1 !important;
              background-color: #77dd77 !important;
              border: 1px solid #77dd77 !important;
              background-image :none !important;
              color: #000000 !important;
            }
             .faltainjustificada a,.faltainjustificada span,.faltainjustificada
            {
            opacity: 1 !important;
              background-color: #ff6961 !important;
              border: 1px solid #ff6961 !important;
              background-image :none !important;
              color: #000000 !important;
            }

            .incapacidad a,.incapacidad span,.incapacidad
            {
            opacity: 1 !important;
              background-color: #FDFD96 !important;
              border: 1px solid #FDFD96 !important;
              background-image :none !important;
              color: #000000 !important;
            }

            .ausencia a, .ausencia span,.ausencia 
            {
            opacity: 1 !important;
              background-color: #ffaf6b !important;
              border: 1px solid #ffaf6b !important;
              background-image :none !important;
              
            }

            
            .birthday a, .birthday span, .birthday 
            {
            opacity: 1 !important;
              background-color: #8f7193 !important;
              border: 1px solid #8f7193 !important;
              background-image :none !important;
              
            }

            .permiso a, .permiso span, .permiso 
            {
            opacity: 1 !important;
              background-color: #a0f3f3 !important;
              border: 1px solid #a0f3f3 !important;
              background-image :none !important;
              color: #000000 !important;
            }
            .festivo a,.festivo span,.festivo 
            {
               opacity: 1 !important;
                background-color: #e00000 !important;
                border: 1px solid #e00000 !important;
                background-image: none !important;
                font-weight: bold !important;
                color: white !important;
            }
            .pendiente a,.pendiente span,.pendiente 
            {
            opacity: 1 !important;
              background-color: #1c4e98 !important;
              border: 1px solid #1c4e98 !important;
              background-image :none !important;
              color: white !important;
            }
            .horasextras a,.horasextras span,.horasextras 
            {
              opacity: 1 !important;
              background-color: #f47edd !important;
              border: 1px solid #f47edd !important;
              background-image :none !important;
            }
            .descanso a, .descanso span,.descanso 
            {
            opacity: 1 !important;
              background-color: #E3B1C8 !important;
              border: 1px solid #E3B1C8 !important;
              background-image :none !important;
             
            }
            .ui-datepicker-next,.ui-datepicker-prev{
              display:none;
            }
            .txtBusqueda{
background-color: white;
width:500px;
text-align:center;
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

.fondo{
display:none;
position: fixed;
width: 100%;
height: 100%; 	
background-color: rgb(0, 0, 0);
opacity: 0.60;
color: #000;
z-index:1000 !important;
}
.modal2{
display:none;
position: fixed;
top:25%;
left:25%;
width: 50%;
height: 40%; 	
border-radius:20px;
background-color: rgb(255, 255, 255);
opacity: 1;
color: #000;
z-index:1001 !important;
}
.modal2 table{
width:100%;
height:100%;
}

.modal2 .modalTitulo p{
font-size:22px;
color:white;
margin:10px 0px 10px 0px;
font-weight: bold;
}
.modal2 .modalTitulo{
width:100%;
position:absolute;
top:0px;
text-align:center;
background-color:#337ab7;
color:white;
border-top-left-radius:20px;
border-top-right-radius:20px;
}
.divInLine {
  display: inline-block;
  top:25%;
  left:25%;
  width:   100px;
  height:  100px;
  border:  1px solid black;
  margin:6px;
  border-radius:10px;

}
.divInLine table{
height:100%;
top:25%;
margin:auto;
cursor:hand;  font-size:20px;
}
.verde{
  background-color:green;
  color:white;
}
.verde table{
color:white;
}
#lblNombre{
font-size:28px;
color:#0360A0;
}
                         .desplegable, .desplegable ul {
                         z-index:5000;
				list-style:none;
                                padding:0px;
                                margin:0px;
			}
			
			.desplegable > li {
                        height:100%;
                                width:100%;
			}
			
			.desplegable li a {
				color:#fff;
				text-decoration:none;
				padding:10px 12px;
				display:block;
                                height:100%;
                                width:100%;
			}
			
			.desplegable li a:hover {
				background-color:#3B6D91;
			}
			
			.desplegable li ul {
				display:none;
				position:absolute;
                                width:11.9%;
			}
			
			.desplegable li:hover > ul {
				display:block;
			}
			.desplegable li ul li {
				position:relative;
                                background-color: #3B6D91;
			}
                        .desplegable li ul li a:hover {
				background-color:#5187AF;
			}
			
			.desplegable li ul li ul {
				right:-190px;
				top:0px;
                                width:100%;
			}
                        .desplegable li:last-child, .desplegable li:last-of-type a:last-of-type{
                        border-bottom-left-radius:10px;
                        border-bottom-right-radius:10px;
                        }
.picker__frame{
font-size:70%;
}
.picker__select--month, .picker__select--year {
height: 2.35em !important;
}
.picker--opened .picker__frame {
    bottom: 18% !important;
}
          </style>
        
<script>
function contarPendientes(){
    
	
    $.ajax({
				type:'GET',
				url:'../php/filtrasolicitudes.php?idu=$usuarioSesion&dptos=&estados=6&fi=&ff=',
				datatype:'text',
				success:cuenta,
				error: function (xhr,ajaxOptions, thrownError){alert('Error accesando al servidor ');},
				async:true,
				timeout: 60000

			});

    
    
}
function cuenta(data){
    var lineas= JSON.parse(data);
    if(lineas.length>0 && $('#contadorPendientes').length>0){
    $('#contadorPendientes').html(lineas.length);
    document.getElementById('contadorPendientes').classList.add('contadorPendientes');
    }
}

</script>
          ";
    }
    function cabusumain()/*Imprime la cabecera del la tabla que hace
                          display de la informacion general del usario*/
      {
        echo "
              <th>id</th>
              <th>nombre</th>
              <th>Apellido</th>
              <th>Dias laborados</th>
              <th>Fecha ingreso</th>
              <th>Departamento</th>
              <th>Antiguedad</th>
              <th>Dias derecho</th>
              ";
      }
      function cabsolicitudes($dep)
        {
          echo "<th class='text-center'>Id Solicitud</th>";
          echo "<th class='text-center'>Nombre</th>";
          echo "<th class='text-center'>Fecha inicio</th>";
          echo "<th class='text-center'>Fecha final</th>";
          echo "<th class='text-center'>Fecha de solicitud</th>";
          echo "<th class='text-center'>Dias tomados</th>";
          echo "<th class='text-center'>Horas</th>";
          echo "<th class='text-center'>Modifico</th>";
          echo "<th class='text-center'>Fecha</th>";
          echo "<th class='text-center'>Jefe</th>";
          echo "<th class='text-center'>Fecha</th>";
          echo "<th class='text-center'>Gerente</th>";
          echo "<th class='text-center'>Fecha</th>";
          echo "<th class='text-center'>RH</th>";
          echo "<th class='text-center'>Fecha</th>";
          if($dep==1)
          {
          echo "<th>Departamento</th>";
          }
        }

      function cabsolicitudesadmin()
        {
          echo "<th class='text-center'>Id Solicitud</th>";
          echo "<th class='text-center' >Nombre</th>";
          echo "<th class='text-center'><a data-toggle='tooltip' title='Fecha inicio'><kbd>FI</kbd></a></th>";
          echo "<th class='text-center'><a data-toggle='tooltip' title='Fecha final'><kbd>FF</kbd></a></th>";
          echo "<th class='text-center' ><a disabled data-toggle='tooltip' title='Fecha de solicitud'><kbd>FA</kbd></a></th>";
          echo "<th class='text-center' ><a data-toggle='tooltip' title='Dias tomados'><kbd>DT</kbd></a></th>";
          echo "<th class='text-center'><a data-toggle='tooltip' title='Tipo de permiso'><kbd>TP</kbd></a></th>";
          echo "<th class='text-center'><a data-toggle='tooltip' title='Sueldo'><kbd>OS</kbd></a></th>";
          echo "<th class='text-center'><a data-toggle='tooltip' title='Jefe de area'><kbd>AUTOJA</kbd></a></th>";
          echo "<th class='text-center'><a data-toggle='tooltip' title='Fecha en la que el jefe de area aprobo'><kbd>FAUTOJA</kbd></a></th>";
          echo "<th class='text-center'><a data-toggle='tooltip' title='Recursos Humanos que autorizo'><kbd>RH</kbd></a></th>";
          echo "<th class='text-center'><a data-toggle='tooltip' title='Fecha en la que recursos humanos autorizo'><kbd>FAUTORH</kbd></a></th>";
          if($dep==1)
          {
          echo "<th><a data-toggle='tooltip' title='Departamento'>IDDEP</a></th>";
          }
        }
      function cabsolituddetalle()
        {
          echo "<th>Acción</th>";
          echo "<th>Solicitó</th>";
          echo "<th>Nombre</th>";
          echo "<th>Inicio</th>";
          echo "<th>Final</th>";
          echo "<th>Alta</th>";
          echo "<th>Dias</th>";
          echo "<th>Modifico</th>";
          echo "<th>Fecha</th>";
          echo "<th>Jefe</th>";
          echo "<th>Fecha</th>";
          
          echo "<th>Gerente</th>";
          echo "<th>Fecha</th>";
          
          echo "<th>RH</th>";
          echo "<th>Fecha</th>";
          echo "<th>Tipo</th>";
          echo "<th>Sueldo</th>";
        }
      function tabusumain($result)
      {
        while ($row=$result->fetch_row()) {
            echo "<td>".$row[0]."</td>";
            echo "<td>".$row[1]."</td>";
            echo "<td>".$row[2]."</td>";
            echo "<td>".$row[4]."</td>";
            echo "<td>".$row[8]."</td>";
            echo "<td>".$row[9]."</td>";
        }
      }
      function uncampo($result)
     {
       while ($row=$result->fetch_row())
           {
             echo "<td>".$row[0]."</td>";
           }
     }
     function uncamporetorno($result)
      {
        while($row=$result->fetch_row())
        {
        echo "<td>".$row[0]."</td>";
        $valor=$row[0];
        }
        return $valor;
      }
      function imprimesources()
        {
          echo "
        <meta name='viewport' content='width=device-width, initial-scale=1' charset='utf-8'>
        <link rel='stylesheet' href='../css/bootstrap.css'>        
        <link rel='stylesheet' href='../css/jquery-ui.css'>
        <script src='../js/jquery.js'></script>
        <script src='../js/jquery-ui.js'></script>
        <script src='../js/jquery.ui.datepicker-es.js'></script>
        <script src='../js/bootstrap.min.js'></script>
        <link rel='stylesheet' href='../css/bootstrap-select.min.css'>
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
            function administraMotivos()
            {
                $('#forminfo').attr('action','motivos.php');
                $('#mandarinfo').click();
            }
        
            $(document).ready(contarPendientes);
        </script>


 
          ";
        }
  }
/*
 */
class fechas
{
       function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d' )
       {
           $dates = array();
           $current = strtotime($first);
           $last = strtotime($last);
           while( $current <= $last )
           {
               $dates[] = date($output_format, $current);
               $current = strtotime($step, $current);
           }
           return $dates;
       }
function diaMas($fecha1,$fecha2){
    /*$fechasArray="[";
    for($i=$fecha1;$i<=$fecha2;$i = date("d-m-Y", strtotime($i ."+ 1 days"))){
     $fechasArray=$fechasArray."\"".$i."\",";
}
    $fechasArray=$fechasArray."\"\"]";

return $fechasArray;
    */
    
$fechaInicio=strtotime($fecha1);
$fechaFin=strtotime($fecha2);
$fechasArray="[";
//for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
for($i=$fecha1; strtotime($i)<=strtotime($fecha2); $i=date('d-m-Y',strtotime($i.'+1 days'))){
     $fechasArray=$fechasArray."\"".date("d-m-Y", strtotime($i))."\",";
}
 $fechasArray=$fechasArray."]";
 return $fechasArray;
}

function formateafecha2($fechasinformato)  /*Toma una fecha de la base de datos oracle con formado dd-mesenletras-yy y la convierte a YYYY-MM-DD*/
{
    //echo $fechasinformato . "|<br>";
    $aux = explode("-", $fechasinformato);
    //echo $aux[0];
    $fechasinformato = $aux[0] . "-" . $aux[1] . "-" . $aux[2];
    $aux = date('d-m-Y', strtotime($fechasinformato));
    return $aux;
}

function formateafechaCumple($fechasinformato,$anio)  /*Toma una fecha de la base de datos oracle con formado dd-mesenletras-yy y la convierte a YYYY-MM-DD*/
{
    //echo $fechasinformato . "|<br>";
    $aux = explode("-", $fechasinformato);
    //echo $aux[0];
    $fechasinformato = $aux[0] . "-" . $aux[1] . "-" . $anio;
    $aux = date('d-m-Y', strtotime($fechasinformato));
    return $aux;
}
    function formateafechaparamodificacion($fechasinformato,$del)  /*Toma una fecha de la base de datos oracle con formado dd-mm-yy y la convierte a YYYY-MM-DD*/
    /*REcibe un delimitador para la funcion explode, asi se puede cambiar segun la necedad*/
    {
        //echo $fechasinformato . "|<br>";
        $aux = explode($del, $fechasinformato);
        //echo $aux[0];
        $fechasinformato = $aux[2] . "-" . $aux[1] . "-" . $aux[0];
        $aux = date('d-m-Y', strtotime($fechasinformato));
        return $aux;
    }
  function formateafecha($fecha)
    {
      $fp=explode("/",$fecha);
      $aux=$fp[2]."-".$fp[1]."-".$fp[0];
      $fecha=$aux;
      //echo "string".$fecha;
/*echo "<script>
alert('$fecha');
</script>";*/
      return $fecha;
    }



    function formateafechaparadb($fecha)
    {
        $fp=explode("-",$fecha);
        $aux=$fp[2]."/".$fp[1]."/".$fp[0];
        $fecha=$aux;
        return $fecha;
    }
    function totaldias($dl,$fechain,$fechafin)
    {
        $i = 0;
        $af = explode(".", $dl);
        $diasconteo = 0;
        $dt = array();
        do {
            switch ($af[$i]) {
                case "L":
                    array_push($dt, "Monday");
                    $diasconteo++;
                    break;
                case "MA":
                    array_push($dt, "Tuesday");
                    $diasconteo++;
                    break;
                case "MI":
                    array_push($dt, "Wednesday");
                    $diasconteo++;
                    break;
                case "J":
                    array_push($dt, "Thursday");
                    $diasconteo++;
                    break;
                case "V":
                    array_push($dt, "Friday");
                    $diasconteo++;
                    break;
                case "S":
                    array_push($dt, "Saturday");
                    $diasconteo++;
                    break;
                case "D":
                    array_push($dt, "Sunday");
                    $diasconteo++;
                    break;
            }
            $i++;
        } while ($af[$i]);
        $b = 0;
        do {
            echo "<br>" . $dt[$b];
            $b++;
        } while ($dt[$b]);
        echo "<br>";
        $totaldias = 0;
        $fechaInicio = strtotime($fechain);
        $fechaFin = strtotime($fechafin);
        for ($i = $fechaInicio; $i <= $fechaFin; $i += 86400) {
            $fechaconteo = date("l", $i);
            $x = 0;
            while ($dt[$x]) {
                if ($fechaconteo == $dt[$x]) {
                    $totaldias++;
                }
                $x++;
            }
        }
        return $totaldias;

    }


    /**********************************************************************************/

    function fechascalendario($diaslab,$fechain,$fechafin)
    {
        $i = 0;
        $af = explode(".", $diaslab);
        $diasconteo = 0;
        $dt = array();
        do {
            switch ($af[$i]) {
                case "L":
                    array_push($dt, "Monday");
                    $diasconteo++;
                    break;
                case "MA":
                    array_push($dt, "Tuesday");
                    $diasconteo++;
                    break;
                case "MI":
                    array_push($dt, "Wednesday");
                    $diasconteo++;
                    break;
                case "J":
                    array_push($dt, "Thursday");
                    $diasconteo++;
                    break;
                case "V":
                    array_push($dt, "Friday");
                    $diasconteo++;
                    break;
                case "S":
                    array_push($dt, "Saturday");
                    $diasconteo++;
                    break;
                case "D":
                    array_push($dt, "Sunday");
                    $diasconteo++;
                    break;
            }
            $i++;
        } while ($af[$i]);
        //echo $diaslab;
        $fechas = array();
        $dates = array();
        $current = strtotime($fechain);
        $last = strtotime($fechafin);
        while( $current <= $last )
        {
            $dates[] = date('d-m-Y', $current);
            //echo "".date('d-m-Y', $current)."<br>  ";
            $current = strtotime('+ 1 day', $current);
        }
        /*
        echo "<br>  ";
        echo implode('<br>--',$dates);
        */
        /*
        $datetime1 = new DateTime('2009-10-11');
        $datetime2 = new DateTime('2009-10-13');
        $interval = $datetime1->diff($datetime2);
        echo $interval->format('%R%a días');
        echo "<br>";
        */
        $d1 = new DateTime($fechain);
        $d2 = new DateTime($fechafin);
        $interval = $d1->diff($d2);
        $a=$interval->format('%a');
        $a++;
        /*
        echo "<br>Interval: ".$a."<br>";
        echo "<br>Fecha ini: ".date('d-m-Y',strtotime($fechain.'+1 days'))."<br>";
        echo "<br>Fecha fin: ".date('d-m-Y',strtotime($fechafin))."<br>";
        */
        $fechaInicio=strtotime($fechain);
        $fechaFin=strtotime($fechafin);
        $b=0;
        //for($i=$fechaInicio; $i<=$fechaFin; $i+=86400)
        for($i=$fechain; strtotime($i)<=strtotime($fechafin); $i=date('d-m-Y',strtotime($i.'+1 days'))){
            //$fechaconteo= date("l", $i);
            $fechaconteo= date("l", strtotime($i));
            $x=0;
            /*
            echo "<br>date: ".$i;
            echo " - i: ".$i." fechaConteo: ".$fechaconteo;
            */
            while ($x<$diasconteo)
            {
                if ($fechaconteo==$dt[$x])
                {
                    array_push($fechas,$dates[$b]);
                    //echo date('d-m-Y', $dates[$b])." * ";
                }
                $x++;
            }
            $b++;
        }
        
        //echo "<br><br>Fechas: ".implode(",",$fechas);
        return $fechas;
    }
    
    
    
    
    
        function fechascalendarioCumple($diaslab,$fechain,$fechafin)
    {
        $i = 0;
        $af = explode(".", $diaslab);
        $diasconteo = 0;
        $dt = array();
        do {
            switch ($af[$i]) {
                case "L":
                    array_push($dt, "Monday");
                    $diasconteo++;
                    break;
                case "MA":
                    array_push($dt, "Tuesday");
                    $diasconteo++;
                    break;
                case "MI":
                    array_push($dt, "Wednesday");
                    $diasconteo++;
                    break;
                case "J":
                    array_push($dt, "Thursday");
                    $diasconteo++;
                    break;
                case "V":
                    array_push($dt, "Friday");
                    $diasconteo++;
                    break;
                case "S":
                    array_push($dt, "Saturday");
                    $diasconteo++;
                    break;
                case "D":
                    array_push($dt, "Sunday");
                    $diasconteo++;
                    break;
            }
            $i++;
        } while ($af[$i]);
        //echo $diaslab;
        $fechas = array();
        $dates = array();
        $current = strtotime($fechain);
        $last = strtotime($fechafin);
        while( $current <= $last )
        {
            $dates[] = date('d-m-Y', $current);
            $current = strtotime('+ 1 day', $current);
        }
        /*
        $datetime1 = new DateTime('2009-10-11');
        $datetime2 = new DateTime('2009-10-13');
        $interval = $datetime1->diff($datetime2);
        echo $interval->format('%R%a días');
        echo "<br>";
        */
        $d1 = new DateTime($fechain);
        $d2 = new DateTime($fechafin);
        $interval = $d1->diff($d2);
        $a=$interval->format('%a');
        $a++;
        //echo "<br>".$a."<br>";
        $fechaInicio=strtotime($fechain);
        $fechaFin=strtotime($fechafin);
        $b=0;
        for($i=$fechaInicio; $i<=$fechaFin; $i+=86400)
        {
            $fechaconteo= date("l", $i);
            $x=0;
            while ($x<$diasconteo)
            {
                
                    array_push($fechas,$dates[$b]);
                
                $x++;
            }
            $b++;
        }
        return $fechas;
    }
    
    
    
    
    
    
    function mesesPermiso($fechaI,$fechaF){
        $contador=$fechaI;
        $final=$fechaF;
        $arrayMeses=array();
        $prueba="";
            while($contador<=$final){
            array_push($arrayMeses, $contador);
            $prueba=$prueba."->".$contador;
            if($contador==12)$contador=0;
            $contador++;
            }
            return $arrayMeses;
    }
    
    
}

/**
 *
 */
class usuario
{
  function retornounvalor($result,$i)
    {
      while($row=$result->fetch_row())
        {
          $valor=$row[$i];
        }
        return $valor;
    }

    function informaciongeneralmain($idusuario,$conn)
        {

$sql='BEGIN VAC_PRC_informaciongeneralmain(:idu,:nom,:idd,:nomde,:puesto); END;';
$stmt=oci_parse($conn,$sql);
oci_bind_by_name($stmt,':idu',$idusuario,32);
oci_bind_by_name($stmt,':nom',$nombre,50);
oci_bind_by_name($stmt,':idd',$iddepartamento,32);
oci_bind_by_name($stmt,':nomde',$nombredepartamento,50);
oci_bind_by_name($stmt,':puesto',$puesto,32);
$r=oci_execute($stmt);
$data=$nombre."|".$iddepartamento."|".$nombredepartamento."|".$puesto."|".$r;
return $data;
        }

}

/*
function opciones(&$p)
{
                        if(in_array(1, $p))
                            {
                              echo "Solicitud";
                              unset($p[array_search(1, $p)]);
                              return;
                            }

                      if(in_array(3, $p))
                            {
                               echo "Histórico";
                              unset($p[array_search(3, $p)]);
                              return;
                            }

                      if(in_array(4, $p))
                            {
                              echo "Autorizaciones";
                              unset($p[array_search(4, $p)]);
                              return;
                            }
                       
} */
function opciones($p,$paginaactual,$padding){
    $tdprincipalabre="<td  width='10%' style='background-color: grey; ".$padding." ' id='rcorners1'><a href='#'>";
    $tdgenericoabre="<td width='10%' style='text-align: center;  cursor:hand;' id='rcorners1' class='opcion'".$padding." onclick=";
    $tdgenericoabreUL="<td width='10%' style='text-align: center;  cursor:hand; padding:8px 0px 0px 0px;' id='rcorners1' class='opcion'>";
    $tdprincipalabreUL="<td width='10%' style='text-align: center;  cursor:hand; background-color: grey; padding:8px 0px 0px 0px;' id='rcorners1' class='opcion'>";
    $tdgenericocierra="</td>";
    $tdprincipalcierraUL="</td>";
    $tdprincipalcierra="</a></td>";
    $menu="";
    $width=80;

    if(in_array(1, $p)){
        $width-=10;
        if(1==$paginaactual){
            $menu=$menu.$tdprincipalabre."Solicitud".$tdprincipalcierra;
        }
        else{
            $menu=$menu.$tdgenericoabre."'main()'><a href='#'>"."Solicitud"."</a>".$tdgenericocierra;
        }
        unset($p[array_search(1, $p)]);
    }

    if(in_array(3, $p)){
        $width-=10;
        if(3==$paginaactual){
            $menu=$menu.$tdprincipalabre."Historico".$tdprincipalcierra;
        }
        else{
            $menu=$menu.$tdgenericoabre."'versolicitud()'"."'><a href='#'>"."Historico"."</a>".$tdgenericocierra;
        }
        unset($p[array_search(3, $p)]);
    }

    if(in_array(4, $p)){
        $width-=10;
        if(4==$paginaactual){
           $menu=$menu.$tdprincipalabre."Autorizaciones "."<span id=contadorPendientes class=contadorPendientes>0</span></a>".$tdprincipalcierra;
        }
        else{
            $menu=$menu.$tdgenericoabre."'administrarsolicitudes()'"."'><a href='#'>"."Autorizaciones "."<span id=contadorPendientes></span></a>".$tdgenericocierra;
        }
        unset($p[array_search(4, $p)]);
    }

    if(in_array(9, $p)){
        $width-=10;
        $menuReporte="
            <ul class=desplegable>
                <li>
                    <a href='#'>
                    Reportes
                    </a>
                    <ul>
                    <li>
                        <a onclick='informeVacaciones()'>
                        D&iacute;as de vacaciones
                        </a>
                    </li>
                    </ul>
                </li>
                </ul>";
        if(9==$paginaactual){
            $menu=$menu.$tdprincipalabreUL.$menuReporte.$tdgenericocierra;
        }
        else{
            $menu=$menu.$tdgenericoabreUL.$menuReporte.$tdprincipalcierraUL;
        }
        unset($p[array_search(9, $p)]);
    }
            
    if(in_array(7, $p) || in_array(8, $p) || in_array(12, $p) ){
        $width-=10;
        $menuConfigInicio="
            <ul class=desplegable>
                <li>
                    <a href='#'>Configuraci&oacuten</a>
                    <ul>";
        
        $menuConfigFin="</ul>
                </li>
                </ul>";
        
        if(in_array(8, $p)){
            $menuConfigInicio=$menuConfigInicio."
                        <li>
                            <a onclick='administrafestivos()'>"."Festivos"."</a>
                        </li>
                        
                    ";
            unset($p[array_search(8, $p)]);
        }
        if(in_array(10,$p)){
            $menuConfigInicio=$menuConfigInicio."
                    <li>
                    <a onclick='administraMotivos()'>"."Motivos"."</a></li>";
            unset($p[array_search(10,$p)]);
        }
        if(in_array(7, $p)){
            $menuConfigInicio=$menuConfigInicio."
                        <li>
                            <a onclick='administraUsuarios()'>Usuarios</a>
                        </li>
                    ";
            unset($p[array_search(7, $p)]);
        }
        $menuConfigInicio=$menuConfigInicio.$menuConfigFin;
        if(8==$paginaactual || 7==$paginaactual){
            $menu=$menu.$tdprincipalabreUL.$menuConfigInicio.$tdprincipalcierraUL;
        }
        else{
            $menu=$menu.$tdgenericoabreUL.$menuConfigInicio.$tdgenericocierra;

        }
    }
            echo $menu."<td width='".$width."%' style='text-align: right'><a href=\"\">";                
 }   



?>
