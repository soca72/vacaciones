<?php
error_reporting(E_ALL ^ E_NOTICE);
$userLogdiasLaborables=$_SESSION['diasLaborables'];
        echo "
             <script>
             var diasDisable=[];
             var diasString=[]
             var festivos=[];
             var titleString=[];
             var sinBloqueo = [false];";
             
$sql="SELECT * FROM VAC_DIAS_NO_HABILES";///este procedure regresara las solicitudes pintandolos en los calendarios
$stmt= oci_parse($conn, $sql);
oci_execute($stmt);
while (($row = oci_fetch_array($stmt, OCI_BOTH)) != false)
{   
    echo "festivos.push(new Date('$row[1]'));
           titleString.push('$row[2]');";
}


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
             //echo gettype($permisos[$i]);
                  $permisosaimprimir[$auximp]=$row[0];
                  $auximp++;
          //ECHO $row[0];
            $i++;
        }





        echo "
         
         diasDisable=festivos;
         diasDisable=diasDisable.concat(diasNoLaborables('$userLogdiasLaborables'));
         diasDisable.push(new Date(document.getElementById('fechaCumple').value));
         titleString=titleString.concat().concat(diasNoLaborables('$userLogdiasLaborables')).push('Cumpleaños');
         recargarDiasDisable();
         function recargarDiasDisable(){
         var i=0;
         while(diasDisable[i]){
            diasString[i]=diasDisable[i].toString();
            i++;
         }
         }
         
         var dateToday=new Date();
         var arrayDeshabilitados = document.getElementsByClassName('picker__day picker__day--infocus picker__day--disabled');
         
         $('#horaInicio,#horaFin').timepicki({
                show_meridian:false,
		min_hour_value:0,
		max_hour_value:23,
		step_size_minutes:15,
		overflow_minutes:true,
		increase_direction:'up',
                start_time: ['07', '00', 'AM']
         });
         $('#horaInicio').on('change',function(){
            //console.log($(this).val());
            
            $('#horaFin').val($(this).val()).attr('data-timepicki-tim',$(this).attr('data-timepicki-tim')).attr('data-timepicki-mini',$(this).attr('data-timepicki-mini'));
         });
         

         var \$input2=  $( '#fechainicio' ).pickadate({
          disable:diasDisable,
          ";
        if(!in_array(10,$permisos)){
         echo "min:dateToday";
        }
         echo "
          });
          




         var picker2= \$input2.pickadate('picker');
        picker2.on({
                    set: function() {
                        //console.log($('#fechainicio').val());
                        //if($antiguedad!=1)$('#fechafinal').val($('#fechainicio').val());
                        contardias();
                    }
        });
        
        
        var \$input= $('#fechafinal').pickadate({
        disable: diasDisable,
        });
        
        
        var picker = \$input.pickadate('picker');
        
        
        function cambiarDiasLaborables(){
        picker.set('disable', false);
        picker2.set('disable', false);
        if('$diasLaborables'==''){
        if(document.getElementById('datausuario').value!=''){
        
        if(document.getElementById('datausuario_diasLab').value!=''){
        diasDisable=festivos.concat(diasNoLaborables(document.getElementById('datausuario_diasLab').value));
        }
        else{
        diasDisable=festivos.concat(diasNoLaborables('1,2,3,4,5,6,7'));
        }
        }
        else{
        diasDisable=festivos.concat(diasNoLaborables('$userLogdiasLaborables'));
        }
        }
        else{
        diasDisable=festivos.concat(diasNoLaborables('$diasLaborables'));
        }
        
        var fechaCumpleSplit = document.getElementById('fechaCumple').value.split('-');
       
        //diasDisable.splice(diasDisable.length, 0, new Date(fechaCumpleSplit[0]+'-'+fechaCumpleSplit[1]+'-'+dateToday.getFullYear().toString().substring(2,4)));
        //diasDisable.splice(diasDisable.length, 0, new Date(fechaCumpleSplit[0]+'-'+fechaCumpleSplit[1]+'-'+(dateToday.getFullYear()+1).toString().substring(2,4)));
        
        picker.set('disable', diasDisable);
         //console.log('Hasta aqui llega');
        picker2.set('disable', diasDisable);
        //console.log('Hasta aqui llega2');
              
        recargarDiasDisable();
        
         }
         
        function enableAllDays(){
            picker.set({'disable': false});
            picker2.set({'disable': false});
            picker.set({'max': new Date()});
            picker2.set({'max': new Date()});
            ";
                if(!in_array(10,$permisos)){
                echo "
                      var dateTemp = new Date(dateToday);
                      dateTemp.setDate(dateTemp.getDate() - 75);
                      picker.set({'min':dateTemp});
                      picker2.set({'min':dateTemp});";
               }
            echo 
            "
        }
        

if($('#tipopermiso').val() == 'Horas extras'){


var fechaActual = new Date();

// Restar 7 d�as a la fecha actual
fechaActual.setDate(fechaActual.getDate() - 7);

          //  var dateTemp = dateFI;
          //  dateTemp.setDate(dateTemp.getDate() + 1);
            picker.set('min',fechaActual);
        }


        function enableFF(){
        

        
        document.getElementById('fechafinal').value='';
        var fechainicio=document.getElementById('fechainicio').value;
        var fechainicioSplit=fechainicio.split('/');
        var dateFF=new Date(fechainicioSplit[2],fechainicioSplit[1]-1,fechainicioSplit[0]);
        var dateFI=new Date(fechainicioSplit[2],fechainicioSplit[1]-1,fechainicioSplit[0]);
        console.log('dateFI:'+dateFI);
        picker.set('min',dateFI);
        if(document.getElementById('datausuario') && document.getElementById('datausuario').value!=''){
        var diasderecho=parseInt(document.getElementById('datausuario').value);
        }
        else {
        
        var diasderecho=$diasderecho;
        }                
        if($('#tipopermiso').val()=='Vacaciones'){
            picker.set('max',diasHabilitados(diasderecho,dateFF));
            //if($antiguedad==1){
            //    picker.set('min',picker.get('max', 'dd/mm/yyyy'));
            //    document.getElementById('fechafinal').value=picker.get('max', 'dd/mm/yyyy');
            //    contardias();
                //$('#fechafinal').prop('disabled', true);
            //}
        }
        else if($('#tipopermiso').val()=='Cumpleaños'){
            document.getElementById('fechafinal').value=document.getElementById('fechainicio').value
            $('#fechafinal').prop('disabled', true);
        }
        else if($('#tipopermiso').val() == 'Horas extras'){
            var dateTemp = dateFI;
            dateTemp.setDate(dateTemp.getDate() + 1);
            picker.set('max',dateTemp);
        }
        else{
            picker.set('max','');
        }
        
        }
        
        picker.on('render', function() {
         pintarFestivos();
         
     });
     picker2.on('render', function() {
         pintarFestivos();
     });
          picker.on('open', function() {
         //cambiarDiasLaborables();
         
     });
     picker2.on('open', function() {
         //cambiarDiasLaborables();
     });
     
        
        function diasHabilitados(maxDias,fecha){
        var count=0;
        var dias=0;
        while(count<maxDias){
        if(diasString.includes(fecha.toString()) || diasDisable.includes( diaSemana( fecha.getDay() ) ) ){
        dias++;
        count--;
        
        }
        count++;
        fecha.setDate(fecha.getDate()+1);
        
        }
        
        fecha.setDate(fecha.getDate()-1)
        return fecha;
        }
        

        function diaSemana(date){
        if(date==0){
        return 7;
        }
        else{
        return date;
        }
        }
        

        function resetForm(){
        //console.log('asd');
                $('#fechainicio,#fechafinal').val('').attr('disabled',true);
                $('#horaInicio,#horaFin').val('').hide();
                $('#diferenciaDias').val('');
                $('#comentario').attr('required',false).val('').hide();
                $('#horaInicio,#horaFin').attr('disabled','disabled').val('').hide();
                $('#cbxFormaPago').hide().prop('disabled',true).val('');
                $('#opcTiempo').prop('disabled',true).hide();
                //$('#tipopermiso').val('');
                 picker.set({'max': false});
                 picker2.set({'max': false});
                $('#opcionsueldopermiso').val('');";
                    if(!in_array("10",$permisos)){
                     echo"picker2.set('min',dateToday);";
                   }  
    echo "
        }
        
        function permiso(a)
        {
          resetForm();
          console.log(a);
          $('#tipopermiso').val(a);
          if(a!='AAA'){
            $('#fechainicio,#fechafinal').val('').attr('disabled',false);
          }
          if(a=='Descanso'){
           $('#cbxFormaPago').val('CONSUELDO').prop('disabled',true).show();
          }
          else if(a=='Vacaciones'){
              
              $('#cbxFormaPago').val('CONSUELDO').prop('disabled',true).show();
          } 
          else if(a=='Cumpleaños'){
              $('#cbxFormaPago').val('CONSUELDO').prop('disabled',true).show();
          } 
          else if(a=='Permiso'){
              $('#comentario').show();
              $('#cbxFormaPago').val('SINSUELDO').prop('disabled',false).show();
            }
          else if(a == 'Horas extras'){
              $('#comentario').show().attr('required',true);
              $('#horaInicio,#horaFin').show().removeAttr('disabled');
              $('#cbxFormaPago').prop('disabled',false).val('CONSUELDO').show();
              $('#opcTiempo').prop('disabled',false).show();
              enableAllDays();
            } 
          else if(a == 'Ausencia'){
              $('#cbxFormaPago').val('SINSUELDO').prop('disabled',true).show();
              $('#comentario').attr('required',true).show();
            } 
          else if(a == 'Falta injustificada'){
              $('#cbxFormaPago').val('SINSUELDO').prop('disabled',true).show();
              $('#comentario').attr('required',true).show();
            } 
            
            if(a == 'Horas extras')enableAllDays();
            else cambiarDiasLaborables();
              //console.log('Fin funcion permiso');
        }

        function opcionsueldo(b)
          {
          /*
            if(document.getElementById('cg').checked)
                document.getElementById('opcionsueldopermiso').value='CONSUELDO';
            else
                document.getElementById('opcionsueldopermiso').value='SINSUELDO';
          */
            $('#opcionsueldopermiso').val(b);
          }
          


          function validarfechas()
            {
            $('form').submit(function(e){
                    e.preventDefault();
            });
            if($('#fechainicio').val()!='' && $('#fechafinal').val()!=''){
                
                document.getElementById('fechainicio').disabled=false;
                document.getElementById('fechafinal').disabled=false;
                var fechainicio=document.getElementById('fechainicio').value;
                var fechafinal=document.getElementById('fechafinal').value;
                var fechainicioSplit=fechainicio.split('/');
                var fechafinalSplit=fechafinal.split('/');
                var horaIni = document.getElementById('horaInicio').value.trim()!=''?document.getElementById('horaInicio').value:'00:00';
                var horaFin = document.getElementById('horaFin').value.trim()!=''?document.getElementById('horaFin').value:'00:00';
                var horaIniSplit = horaIni.split(':');
                var horaFinSplit = horaFin.split(':');
                var dateFI = new Date(fechainicioSplit[2],fechainicioSplit[1]-1,fechainicioSplit[0],horaIniSplit[0],horaIniSplit[1],0);
                var dateFF = new Date(fechafinalSplit[2],fechafinalSplit[1]-1,fechafinalSplit[0], horaFinSplit[0],horaFinSplit[1],0);
                //console.log(dateFI);
                //console.log(dateFF);
                var today = new Date();
                var dd = today.getDate();
                var mm = today.getMonth()+1; //January is 0!
                var yyyy = today.getFullYear();
                if(dd<10)
                {
                    dd='0'+dd
                }
                if(mm<10)
                {
                    mm='0'+mm
                }
                today = dd+'/'+mm+'/'+yyyy;
                if(dateFF<dateFI || $('#tipopermiso').val()==''  )
                  {
                    if($('#tipopermiso').val()=='')
                      {
                        alert('Seleccione que permiso quiere');
                      }
                  
                      if(dateFF<dateFI)
                      {
                        alert('Error en el periodo de fechas');
                        
                      }
                  }
                   if ($('#selectComen').css('display') !== 'none' && $('#selectComen').val() ==='') {
                    alert('Seleccione el motivo del permiso ');
                  }
                  else
                  {

                    if(confirm('Solicitar '+document.getElementById('tipopermiso').value+' ? De '+fechainicio+'  Hasta '+fechafinal))
                        {
                          $('form').unbind('submit');
                          opcionsueldo(document.getElementById('cbxFormaPago').value)
                          $('#enviar').attr('action','mandarsolicitud.php');
                              //console.log('La solicitud se ha enviado para ser evaluada');
                        }
                        else{
                          $('#enviar').attr('action');
                        }
                      }
                      
                }
                else {
                    alert('Debe seleccionar las fechas primero');
                    return false;
                }



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


            function administraraccesos()
            {
                $('#forminfo').attr('action','accesos.php');
                $('#mandarinfo').click();
            }
            function contardias(){
            if(document.getElementById('fechainicio').value!='' && document.getElementById('fechafinal').value!=''){
            var splitFechaI=document.getElementById('fechainicio').value.split('/');
            var splitFechaF=document.getElementById('fechafinal').value.split('/');
            var fechaI=moment(splitFechaI[2]+'-'+splitFechaI[1]+'-'+splitFechaI[0]);
            var fechaF=moment(splitFechaF[2]+'-'+splitFechaF[1]+'-'+splitFechaF[0]);
            var diferencia=fechaF.diff(fechaI,'days')+1;
            var fechaI2=new Date(fechaI);
            var fechaF2=new Date(fechaF);
            while(fechaI2.toString()!=fechaF2.toString()){
            if(diasString.includes(fechaI2.toString()) || diasDisable.includes(diaSemana(fechaI2.getDay())) ){
            diferencia--;
            }
            fechaI2.setDate(fechaI2.getDate()+1);
            
            }
            document.getElementById('diferenciaDias').value=diferencia;
            }
            }
 


function pintarFestivos(){
        $('.Festivo').removeClass('Festivo');
        let diasDisableFormat=[];  
        let count=0;
        let dia,mes,anio,fechaPintar;
        while(diasDisable[count]){
          if(typeof(diasDisable[count])!='number'){
            diasDisable[count]=new Date(diasDisable[count]);
            diasDisableFormat[count]=diasDisable[count].getDate();
            dia=String(diasDisable[count].getDate());
            mes=String(diasDisable[count].getMonth()+1);
            anio=String(diasDisable[count].getFullYear());
            if(dia.length<2)dia='0' + dia;
            if(mes.length<2)mes='0' + mes;
            fechaPintar=dia+'/'+mes+'/'+anio;
            $( \"div[aria-label='\"+fechaPintar+\"']\" ).addClass( \"Festivo\" );//pintar Festivos
            $( \"div[aria-label='\"+fechaPintar+\"']\" ).prop('title', titleString[count]);//pintar Festivos
          }
          count++;
        }
        pintarCumple();
}


function pintarCumple(){
        $('.Cumpleanos').removeClass('Cumpleanos');
        let fechaCumpleFormat=[]; 
        let fechaCumple = document.getElementById('fechaCumple').value.split('-');
        let count = 0;
        let dia,mes,fechaPintar;
        fechaCumple = new Date(fechaCumple);
        console.log(fechaCumple);
        fechaCumpleFormat[count] = fechaCumple.getDate();
        dia = String(fechaCumple.getDate());
        mes = String(fechaCumple.getMonth()+1);
        if(dia.length<2)dia='0' + dia;
        if(mes.length<2)mes='0' + mes;
        fechaPintar=dia+'/'+mes+'/';
        $( \"div[aria-label^='\"+fechaPintar+\"']\" ).addClass( \"Cumpleanos\" );//pintar Cumpleanos
        $( \"div[aria-label^='\"+fechaPintar+\"']\" ).prop('title', 'Cumpleaños');//pintar Cumpleanos
          
}


function diasNoLaborables(dias){
var diasSplit=dias.split(',');
var count=1;
var diasNoHabiles = [];
while(count<=7){
if(!diasSplit.includes(count.toString())){
diasNoHabiles.push(count);
}
count++;
}
return diasNoHabiles;
}





        </script>
<style>
.Festivo{
}
.Cumpleanos{
color:white !important;
background-color:#8f7193 !important;
}
</style>";
 ?>
