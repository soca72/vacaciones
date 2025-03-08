 <?php 
 error_reporting(E_ALL ^ E_NOTICE);
 include "conexion.php";
 include "../Mail/PHPMailerAutoload.php";
 
  $solicitudes = json_decode($_POST['solicitudes']) ;
 
 
 
 foreach ($solicitudes as $value) {
 
 $idsolicitud=$value;
 $idusuario=$_SESSION["idusuario"];
 $accion="den";
 $enviarEmail=false;
 $sql="BEGIN VAC_PRC_DENEGAR(:idusuario,:idsol,:accion,:correo,:nombre);END;";
 $stmt=oci_parse($conn,$sql);
 oci_bind_by_name($stmt,":idusuario",$idusuario,100);
 oci_bind_by_name($stmt,":idsol",$idsolicitud,100);
 oci_bind_by_name($stmt,":accion",$accion,100);
 oci_bind_by_name($stmt,':correo',$toEmail,200);
 oci_bind_by_name($stmt,':nombre',$toNombre,100);
 $result=oci_execute($stmt);
 $e=oci_error($stmt);
 $mail->addAddress($toEmail, $toNombre);
 $body="Solicitud Cancelada";
 if ($result)
 {
     $mensaje="Solicitud cancelada ";
     $enviarEmail=true;
 }
 else {
     $mensaje="No se pudo cancelar la Solicitud, intentelo mas tarde";
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
                  . "USUARIO"
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
            while ($row = oci_fetch_array($refcur, OCI_ASSOC+OCI_RETURN_NULLS)) {
                
                $body.= "<tr>\n";
                foreach ($row as $item) {
                    if(!in_array($count,array(5,9,10,11,12,13,15,16,17,19))){
                    $body.= "<td>".($item !== null ? htmlentities($item, ENT_QUOTES) : "")."</td>\n";
                    }
                    $count+=1;
                }
                $body.= "</tr>\n";
            }
            $body.= "</table>\n";
            $mail->msgHTML($body, dirname(__FILE__));
           // if (!$mail->send()){
            //echo "Mailer Error: " . $mail->ErrorInfo;
            //}
            }
            }

 echo $mensaje." ".$e['message'];
 }
 oci_close($conn);
 ?>

