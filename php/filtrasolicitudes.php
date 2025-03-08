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
$departamentos=$_GET['dptos'];
$estados=$_GET['estados'];
$estados2=$_GET['estados'];
$idu=$_GET['idFiltro'];
$fi=$_GET['fi'];
$ff=$_GET['ff'];
$tipos=$_GET['tipo'];
$orden=$_GET['orden'];
$tipoFecha=$_GET['tipoFecha'];
//echo "Departamentos :",$departamentos ."<br>";
//echo "Estados :".$estados ."<br>";
//echo "usuario :".$idu ."<br>";
//echo "idusuario :".$idusuario;
//echo "<br>fi :",$fi ."<br>";
//echo "ff :",$ff ."<br>";
$filtroaplicado=false;
$sql="
    SELECT 
    vsv.IDSOLICITUD, 
    vsv.IDUSUARIO, 
	s.abrev AS sucursal,
    vsv.NOMBRE, 
    TO_CHAR(vsv.FECHAINICIO,'DD/MON/YYYY HH24:MI','NLS_DATE_LANGUAGE=SPANISH') AS FECHAINICIO, 
    TO_CHAR(vsv.FECHAFIN,'DD/MON/YYYY HH24:MI','NLS_DATE_LANGUAGE=SPANISH')    AS FECHAFIN, 
    TO_CHAR(vsv.FECHAALTA,'DD/MON/YYYY hh24:mi:ss', 'NLS_DATE_LANGUAGE=SPANISH')  AS FECHAALTA, 
    CASE 
        WHEN tipodepermiso = 'Horas extras' 
        THEN 0 
        ELSE vsv.TOTALDIAS 
    END as totaldias, 
    CASE 
        WHEN tipodepermiso = 'Horas extras' 
        THEN lpad(trunc(vsv.horas),2,'0') || ':' || lpad(trunc((vsv.horas - trunc(vsv.horas))*60),2,'0') 
        ELSE '00:00' 
    END as horas, 
    um.usuario as UsuarioModifico, 
    TO_CHAR(vsv.FECHAMODIFICO,'DD/MON/YYYY hh24:mi:ss','NLS_DATE_LANGUAGE=SPANISH') AS FECHAMODIFICO, 
    ua.usuario as JefeAprobo, 
    TO_CHAR(vsv.FECHAAUTORIZOJEFEAREA,'DD/MON/YYYY hh24:mi:ss','NLS_DATE_LANGUAGE=SPANISH') AS FECHAAUTORIZOJEFEAREA, 
    uRH.usuario as RHAprobo, 
    TO_CHAR(vsv.FECHAAUTORIZORECURSOSH,'DD/MON/YYYY hh24:mi:ss','NLS_DATE_LANGUAGE=SPANISH') AS FECHAAUTORIZORECURSOSH, 
    udi.USUARIO AS GerenteAprobo,
    to_char(vsv.FECHAAUTORIZOGERENTE,'DD/MON/YYYY hh24:mi:ss','NLS_DATE_LANGUAGE=SPANISH') AS FECHAAUTORIZOGERENTE,
    vsv.IDDEPARTAMENTO, 
    vsv.TIPODEPERMISO, 
    vsv.OPCIONPAGO, 
    vsv.IDACTUADOR, 
    vsv.FECHAACCION, 
    vsv.TIPOACCION,
    replace(replace(nvl(vsv.COMENTARIO,' '),CHR(10),'<br>'),chr(13),'') AS COMENTARIO,
    TO_CHAR(vsv.FECHAINICIO,'YYYYMMDD HH24MI') as ordenFECHAINICIO,
    TO_CHAR(vsv.FECHAFIN,'YYYYMMDD HH24MI') as ordenFECHAFIN,
    TO_CHAR(vsv.FECHAALTA,'YYYYMMDD HH24MI') as ordenFECHAALTA,
    TO_CHAR(vsv.FECHAMODIFICO,'YYYYMMDD HH24MI') as ordenFECHAMODIFICO,
    TO_CHAR(vsv.FECHAAUTORIZOJEFEAREA,'YYYYMMDD HH24MI') as ordenFECHAAUTORIZOJEFEAREA,
    TO_CHAR(vsv.FECHAAUTORIZORECURSOSH,'YYYYMMDD HH24MI') as ordenFECHAAUTORIZORECURSOSH,
    TO_CHAR(vsv.FECHAAUTORIZOGERENTE,'YYYYMMDD HH24MI') AS ordenFECHAAUTORIZOGERENTE,
    replace(replace(nvl(vsv.COMENTARIOJEFE,' '),CHR(10),'<br>'),chr(13),'') AS COMENTARIOJEFE,
    replace(replace(nvl(vsv.COMENTARIORH,' '),CHR(10),'<br>'),chr(13),'') AS COMENTARIORH,
    REPLACE(REPLACE(nvl(vsv.COMENTARIOGERENTE,' '),chr(10),'<br>'),chr(13),'') AS COMENTARIOGERENTE,
    vsv.comentarioPreestablecido,
	s.idsucursal  
FROM 
    vac_solicitudesvacaciones vsv
    inner join usuarios u on u.idusuario = vsv.idusuario
    inner join usuariosdata ud on ud.idusuariodata  = u.idusuariodata
    left outer join usuarios um on um.idusuario = vsv.IDMODIFICO
    left outer join usuarios ua on ua.idusuario = vsv.IDAUTORIZOJEFEAREA
    left outer join usuarios uRH on uRH.idusuario = vsv.IDAUTORIZORECURSOSH
    LEFT OUTER JOIN usuarios udi ON udi.IDUSUARIO = vsv.IDAUTORIZOGERENTE 
	INNER JOIN sucursales s ON u.idsucursal = s.idsucursal
	left JOIN VAC_USRAUTDPTO vu ON  vu.iddpto = vsv.IDDEPARTAMENTO  AND vu.idsucursal = s.idsucursal
WHERE 
     vsv.FECHAINICIO  > to_date('31-12-1990','dd-mm-yyyy') AND vu.idusuario = ".$idusuarioData."
      ";

//Si recibimos un nombre de usuario, ignoramos los departamentos y filtramos por el usuario, tomando
//en cuenta los estados y las fechas, de haberlas
if( strlen($idu)>0 ) 
		{
			//echo "Filtro por usuario "."<br>";

				
				////echo $idu;
				$sql= $sql." and vsv.idusuariodata=$idu ";
				////echo $sql."<br>";
				
				////echo $sql;
				//ejecutaconsulta($sql);
				$filtroaplicado=true;
		}

//Si recibimos departamentos, filtramos con base en ellos y tomamos en cuenta las fechas
if(strlen($departamentos)>0)
		{
			//echo "Filtro por departamento "."<br>";
			$sql=$sql." and id_depto in ";
                                        $sql=anexadepartamentos($departamentos,$sql);
                        
                                        if( strpos($departamentos, -44) !==false || $departamentos==-44)
                                        {
                                        $sql=$sql;
                                        }
			//$sql=anexaestados($estados,$sql);
			//if(strlen($fi) >0 || strlen($ff)>0)$sql=anexafechas($fi,$ff,$sql);
			$filtroaplicado=true;

		}

if(strlen($tipos)>0)
		{
			//echo "Filtro por departamento "."<br>";
			$sql=$sql." and vsv.TIPODEPERMISO in";
                                        $sql=anexaTipos($tipos,$sql);
                                        if( strpos($tipos, -44) !==false || $tipos==-44)
                                        {
                                        /*$sql="select * from usuariosdata,usuarios,vac_solicitudesvacaciones where  usuarios.idusuariodata=usuariosdata.idusuariodata
                                        and vac_solicitudesvacaciones.idusuario=usuarios.idusuario and area  in (select iddpto from VAC_USRAUTDPTO where idusuario=$idusuario)";
                                        }*/
                                        $sql=$sql;
                            
                        }
			
			//$sql=anexaestados($estados,$sql);
			//if(strlen($fi) >0 || strlen($ff)>0)$sql=anexafechas($fi,$ff,$sql);
			$filtroaplicado=true;

		}

if( (!$filtroaplicado && (strlen($fi) >0 || strlen($ff)>0 )) || ($idusuario && !$filtroaplicado))
{
			//echo "Filtro por fechas "."<br>";
			$sql=$sql." and id_depto in  (select iddpto from VAC_USRAUTDPTO where idusuario=(select idusuariodata from usuarios where idusuario=$idusuario)) ";
			//if(strlen($fi) >0 || strlen($ff)>0)$sql=anexafechas($fi,$ff,$sql);
                        //$sql=anexaestados($estados,$sql);
			////echo $sql."<br>";
			$filtroaplicado=true;
}

/*if($idusuario && !$filtroaplicado)
{
			//echo "Filtro por idusuario "."<br>";
			$sql=$sql1." and id_depto in  (select iddpto from VAC_USRAUTDPTO where idusuario=(select idusuariodata from usuarios where idusuario=$idusuario)) ";
			$sql=anexaestados($estados,$sql);
			////echo $sql."<br>";
			$filtroaplicado=true;
}*/



$sql=anexaestados($estados,$sql);
if(strlen($fi) >0 || strlen($ff)>0)$sql=anexafechas($fi,$ff,$sql);



if($filtroaplicado)
{   
        //echo $tipoFecha;
        //echo $sql;
	ejecutaconsulta($sql);
}




function ejecutaconsulta($sql)			
{
    include "conexion.php";

	
	//echo "<br> <b> Consulta a bd : ".$sql."</b><br>";
	//$searchconnection= oci_connect("siodev","radeon","192.9.200.8/siodev");
	//echo "<br> Informacion sobre la conexion :";
	//var_dump($searchconnection);
	//echo "<br>";
  
        if($_GET['orden']=="id")$sql=$sql." order by idsolicitud desc";
        else $sql=$sql." order by nombre";
       //ECHO $sql;
		$parsed=oci_parse($conn, $sql);
		oci_execute($parsed);
	//$cantidadregistros=oci_fetch_all($parsed, $registros);
	////echo "Cantidad de registros obtenidos : ".$cantidadregistros."<br>";
	//var_dump($registros);


	//****************************************
	$arregloregistros= array();
	while ( ($row = oci_fetch_array($parsed,OCI_ASSOC)) !=false ) {
		array_push($arregloregistros, $row);
	}
			$json=json_encode($arregloregistros);
			echo $json;

	//****************************************

	oci_free_statement($parsed);
	oci_close($conn);
	
}





function  anexafechas($fi,$ff,$sql)
{
    
    
    //echo "<br><br>Entro a anexaFechas <br><br>";
    //echo $_GET['tipoFecha']."<br><br>";
    if($_GET['tipoFecha'] == "permiso"){
        //echo "<br><br>Entro a Permiso <br><br>";
	if( $fi && $ff) //Tomamos las dos fechas y retornamos las solicitudes dentro de ese rango
			{
				//$anexo=" and fechainicio>=TO_DATE('$fi','dd-mm-yyyy') and fechafin<=TO_DATE('$ff','dd-mm-yyyy')";
                                $anexo=   "and ("
                                        . "(fechainicio>=TO_DATE('$fi','dd-mm-yyyy') and (fechainicio<=TO_DATE('$ff','dd-mm-yyyy') or fechafin<=TO_DATE('$ff','dd-mm-yyyy'))) or"
                                        . "(fechafin>=TO_DATE('$fi','dd-mm-yyyy') and fechafin<=TO_DATE('$ff','dd-mm-yyyy')) or"
                                        . "(fechainicio<=TO_DATE('$fi','dd-mm-yyyy') and fechafin>=TO_DATE('$ff','dd-mm-yyyy'))"
                                        . ")";
				return $sql.$anexo;
			}
	if( $fi && !$ff) //Tomamos las soliciutes cuya fecha de inicio es mayor o igual a la $fi
			{
				$anexo="and (fechainicio>=TO_DATE('$fi','dd-mm-yyyy') or fechafin>=TO_DATE('$fi','dd-mm-yyyy'))";
				
                                return $sql.$anexo;
			}

	if(!$fi && $ff ) //Tomamos las solicitudes cuya fecha final es menor o igual a la $ff
			{
				$anexo=" and (fechafin<=TO_DATE('$ff','dd-mm-yyyy') or fechainicio<=TO_DATE('$ff','dd-mm-yyyy'))";
				
				return $sql.$anexo;
			}
    }
    else if($_GET['tipoFecha'] == "alta"){
        //echo "<br><br>Entro a Alta <br><br>";
        if( $fi && $ff){
            $anexo=" and (trunc(fechaalta) between TO_DATE('$fi','dd-mm-yyyy') and TO_DATE('$ff','dd-mm-yyyy'))";
            return $sql.$anexo;
        }
        if( $fi && !$ff){
            $anexo=" and (trunc(fechaalta) between TO_DATE('$fi','dd-mm-yyyy') and TO_DATE('$fi','dd-mm-yyyy'))";
            return $sql.$anexo;
        }
        if(!$fi && $ff ){
            $anexo=" and (trunc(fechaalta) between TO_DATE('$ff','dd-mm-yyyy') and TO_DATE('$ff','dd-mm-yyyy'))";
            return $sql.$anexo;
        }
    }
	return $sql;
}


function anexadepartamentos($departamentos,$sql)
{
	$sql=$sql."($departamentos)";
	return $sql;
}
function anexaTipos($tipos,$sql)
{ 
    $tipos= str_replace(",","','", $tipos);
	$sql=$sql."('$tipos')";
	return $sql;
}

function anexaestados($estados,$sql)
{
	if(strlen($estados)==0)
		return $sql;


	$arregloestados=explode(",", $estados);
	if (in_array(-44, $arregloestados)) {
		return $sql;
	}
	$estadosrestantes=count($arregloestados);
	/*
	Estados
	-44 Sin filtro
	1 Autorizadas
	2 Aprobadas por jefe inmediato   
	3 Aprobadas por recursos humanos
	4 Canceladas 
	5 Pendientes de revisar
	*/
	$sql = $sql." and (";

	if (in_array(1, $arregloestados)) {

		//$sql=$sql." (nvl(IDAUTORIZORECURSOSH,-44)!=-44 and nvl(IDAUTORIZOJEFEAREA,-44)!=-44 )";
		$sql=$sql." (vsv.tipoaccion = 'au' and vsv.fechaautorizorecursosh is not null)";

		$estadosrestantes--;

			if ($estadosrestantes>0) {
			$sql=$sql." or ";
		}

	}

	if (in_array(2, $arregloestados)) {
				//$sql=$sql." (nvl(IDAUTORIZOJEFEAREA,-44)!=-44  and nvl(IDAUTORIZORECURSOSH,-44)=-44)";
				$sql=$sql." (vsv.tipoaccion = 'pa' and vsv.fechaautorizojefearea is not null)";
			$estadosrestantes--;
			if ($estadosrestantes>0) 
				{
					$sql=$sql." or ";
				}

	}

	if (in_array(3, $arregloestados)) {

			//$sql=$sql."  (nvl(IDAUTORIZORECURSOSH,-44)!=-44 and nvl(IDAUTORIZOJEFEAREA,-44)=-44)";
                       // $sql=$sql."  (nvl(IDAUTORIZORECURSOSH,-44)!=-44)";
			$sql=$sql." (vsv.tipoaccion = 'au' and vsv.fechaautorizorecursosh is not null)";	
			
			$estadosrestantes--;

			if ($estadosrestantes>0) 
				{
					$sql=$sql." or ";
				}
		
	}
        
        if(in_array(7, $arregloestados)){
            $sql=$sql." (vsv.tipoaccion='ag' and vsv.fechaautorizogerente is not null)";
            $estadosrestantes--;
            if ($estadosrestantes>0) 
				{
					$sql=$sql." or ";
				}
        }


	if (in_array(4, $arregloestados)) {

			$sql=$sql." (TIPOACCION='den')";

			$estadosrestantes--;

			if ($estadosrestantes>0) 
				{
					$sql=$sql." or ";
				}
		
	}

			if (in_array(5, $arregloestados)) {

			//$sql=$sql." (nvl(IDAUTORIZORECURSOSH,-44)=-44 and nvl(IDAUTORIZOJEFEAREA,-44)=-44 and TIPOACCION!='den') ";
			$sql=$sql." (vsv.tipoaccion = 'alta' or (vsv.tipoaccion = 'pa'and vsv.fechaautorizojefearea is  null) or (vsv.tipoaccion = 'ag'and vsv.fechaautorizogerente is null)) ";

			$estadosrestantes--;

			if ($estadosrestantes>0) 
				{
					$sql=$sql." or ";
				}
		
	}
        		if (in_array(6, $arregloestados)) {

			$sql=$sql."TIPOACCION!='au' and TIPOACCION!='den'";
                         if($_SESSION['iddepartamento']!=29){
                             $sql=$sql." and nvl(vsv.IDAUTORIZOJEFEAREA,-44)=-44";
                         }
		
		
	}
        
        

        

	$sql= $sql.")";
        //echo $sql;
	return $sql;

}
//echo "<br><br>".$sql;

 ?>
