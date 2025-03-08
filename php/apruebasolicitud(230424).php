<?php 
error_reporting(E_ALL ^ E_NOTICE);
session_start();
//if($_SESSION["idusuario"]=="") header('Location:../index.php');
 include "conexion.php";
 include "../Mail/PHPMailerAutoload.php";
 $idusuario=$_SESSION["idusuario"];
 $iddepartamento=$_GET['iddepartamento'];
 $diaslaborados=$_GET['diaslaborados'];
 $idsolicitud=$_GET['idsolicitud'];
 $accion=$_GET['accion'];
 $jefe=$_GET['Jefe'];
 $RH=$_GET['RH'];
 $iddepsol=$_GET['idsolicituddepto'];
 $modo=$_GET['modo'];
 
 $enviarEmail=false;/*
echo "Jefe :".$jefe."<br>";
echo "RH :".$RH."<br>";
echo "idusaurio :".$idusuario."<br>";
echo "idsolicitud :".$idsolicitud."<br>";
echo "diaslaborados :".$diaslaborados."<br>";
echo "accion :".$accion."<br>";
echo "iddepartamento :".$iddepartamento."<br>";
echo "idsol :".$iddepsol."<br>";
*/
$mensaje="";
	$sql="BEGIN VAC_PRC_iddeptousandoidsol(:idsol,:iddep);END;";
        $stmt=oci_parse($conn,$sql);
        oci_bind_by_name($stmt,":idsol",$idsolicitud,32);
        oci_bind_by_name($stmt,":iddep",$iddepsol,32);
        oci_execute($stmt);
       //echo "accion :".$accion."<br>"; 
       
     /*else
     {
             //$mensaje= "permisos jefe area";
            $sql="BEGIN VAC_PRC_APROBARJEFEAREA(:idu,:idsol,:accion);END;";
            $stmt=oci_parse($conn,$sql);
            oci_bind_by_name($stmt,':idu',$idusuario,32);
            oci_bind_by_name($stmt,':idsol',$idsolicitud,32);
            oci_bind_by_name($stmt,':accion',$accion,32);
            $result=oci_execute($stmt);
            //$result=$db->query("call autorizarjefearea($idsolicitud,$idusuario,'$accion');");
            if ($result)
            {
                $mensaje= "Solicitud aprobada por jefe de area";
            }
            else {
                $mensaje= "Error en la aprobacion";
            }
        }*/
      if ($iddepartamento==29)
        {
            $accion='au';
            //echo "RH";
            $sql="BEGIN VAC_PRC_APROBARRECURSOSH(:idu,:idsol,:accion,:correo,:nombre);END;";
            $stmt=oci_parse($conn,$sql);
            oci_bind_by_name($stmt,':idu',$idusuario,32);
            oci_bind_by_name($stmt,':idsol',$idsolicitud,32);
            oci_bind_by_name($stmt,':accion',$accion,32);
            oci_bind_by_name($stmt,':correo',$toEmail,100);
            oci_bind_by_name($stmt,':nombre',$toNombre,100);
            $result=oci_execute($stmt);
            $mail->addAddress($toEmail, $toNombre);
            //$body="Solicitud aprobada por Recursos Humanos";
            if ($result)
            {
              //  $mensaje= "Solicitud aprobada por recursos humanos";
                if($toEmail!="-1")$enviarEmail=true;
            }
            else {
                $mensaje= "Error en la aprobación";
            }
        }
     else 
        {
            $accion='pa';
            $sql="BEGIN VAC_PRC_APROBARJEFEAREA(:idu,:idsol,:accion,:correo,:nombre);END;";
            $stmt=oci_parse($conn,$sql);
            oci_bind_by_name($stmt,':idu',$idusuario,32);
            oci_bind_by_name($stmt,':idsol',$idsolicitud,32);
            oci_bind_by_name($stmt,':accion',$accion,32);
            oci_bind_by_name($stmt,':correo',$toEmail,100);
            oci_bind_by_name($stmt,':nombre',$toNombre,100);
            $result=oci_execute($stmt);
            $mail->addAddress($toEmail, $toNombre);
            //$body="Solicitud aprobada por el jefe de &aacute;rea";
            //$result=$db->query("call autorizarjefearea($idsolicitud,$idusuario,'$accion');");
            if ($result)
            {
               // $mensaje= "Solicitud aprobada por jefe de área";
                 if($toEmail!="-1")$enviarEmail=true;
            }
            else {
                $mensaje= "Error en la aprobación";
            }
        }
        if($enviarEmail){
        $sql="BEGIN VAC_PRC_VERSOLICITUDPORID(:idsol,:solicitud);END;";
            $stmt=oci_parse($conn,$sql);
            $refcur = oci_new_cursor($conn);
            oci_bind_by_name($stmt,':idsol',$idsolicitud,32);
            oci_bind_by_name($stmt,':solicitud',$refcur, -1, OCI_B_CURSOR);
            $result=oci_execute($stmt);
            oci_set_prefetch($refcur, 200);
            oci_execute($refcur);
            if($result){
            $body.= "<style>"
                  . "table{"
                  . "width:100%;"
                  . "border-collapse:collapse;"
                  . "border:0px;"
                  . "}"
                  . "table th{"
                  . "background-color:#337ab7;"
                  . "color:white;"
                  . "padding:5px;"
                  . "font-size:12px;"
                  . "font-weigth:bold;"
                  . "}"
                  . "table th:last-child{"
                  . "border-top-right-radius:10px;"
                  . "}"
                  . "table th:first-child{"
                  . "border-top-left-radius:10px;"
                  . "}"
                  . "table th,table td{"
                  . "text-align:center;"
                  . "}"
                  . "table td{"
                  . "padding:5px;"
                  . "background-color:#ddd"
                  . "}"  
                  . "table td:nth-child(3){"
                  . "text-align:center"
                  . "}"
                  . "</style>"
                  . "<table>"
                  . "<thead>"
                  . "<tr>"
                  . "<th>"
                  . "IDSOL"
                  . "</th>"
                  . "<th>"
                  . "IDUSUARIO"
                  . "</th>"
                  . "<th>"
                  . "USUARIO"
                  . "</th>"
                  . "<th>"
                  . "INICIO"
                  . "</th>"
                  . "<th>"
                  . "FIN"
                  . "</th>"
                  . "<th>"
                  . "DIAS"
                  . "</th>"
                  . "<th>"
                  . "JEFE"
                  . "</th>"
                  . "<th>"
                  . "FECHA"
                  . "</th>"
                  . "<th>"
                  . "RH"
                  . "</th>"
                  . "<th>"
                  . "FECHA"
                  . "</th>"
                  . "<th>"
                  . "TIPO"
                  . "</th>"
                  . "<th>"
                  . "Estado"
                  . "</th>"
                  . "</tr>"
                  . "</thead>";
            $count=0;
            while ($row = oci_fetch_array($refcur, OCI_ASSOC+OCI_RETURN_NULLS)){
                $body.= "<tr>\n";
                foreach ($row as $item) {
                    if(!in_array($count,array(5,7,8,13,15,16,17,19))){
                    $body.= "<td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "")."</td>\n";
                    }
                    $count+=1;
                }
                $body.= "</tr>\n";
            }
            $body.= "</table>\n";
            $mail->msgHTML($body, dirname(__FILE__));
            if (!$mail->send()){
            //echo "Mailer Error: ".$mail->ErrorInfo;
            }
            }
            }
            echo $mensaje;
            $_SESSION['idusuario']=$idusuario;
            $_SESSION['diaslaborados']=$diaslaborados;
            $_SESSION['idsolicitud']=$idsolicitud;
            oci_close($conn);
 ?>



