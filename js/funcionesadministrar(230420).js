
var filtrosestado = []; //Se agregan los id de los estados, solo existen aqui y en el webservice, todavia no en la bd
var filtradodepartamentos =[]; //Guarda id de departamentos, existen aqui y en bd
var filtrosTipo = [];
var usuario="" ; //Variable para hacer mas legible el codigo
var idusuario;
var fechainicio="";
var fechafinal="";
var permisosusuario= [];
var iddepartamento;
var depPermitidos=0;
var url="";
var splitFechasAlta=[];
var splitFechasJefe=[];
var splitFechasRH=[];
var checks=[];
var tipoFecha;
//Objeto que contiene los miembros estados
var objetoestado={};
objetoestado.m1='AUTORIZADAS';
objetoestado.m2='APROBADAS POR JEFE INMEDIATO';
objetoestado.m3='APROBADAS POR RECURSOS HUMANOS';
objetoestado.m4='CANCELADAS';
objetoestado.m5='PENDIENTES DE APROBACION';
var objetodepartamento={};



															//Funciones auxiliares, redireccion y validaciones de fechas, operaciones con arreglos
//****************************************************************************************************************************************************************************** 
//************************************************ *****************************************************************************************************************************
//****************************************************************************************************************************************************************************** 
//************************************************ *****************************************************************************************************************************
 	//Esta funcion permite redirigir de POST
     function redirectPost(location, args)
    {
        var form = $('<form></form>');
        form.attr("method", "post");
        form.attr("action", location);

        $.each( args, function( key, value ) {
            var field = $('<input></input>');

            field.attr("type", "hidden");
            field.attr("name", key);
            field.attr("value", value);

            form.append(field);
        });
        $(form).appendTo('body').submit();
    }

$( "div[data-pick^='"+1553234400000+"']" ).addClass( "Festivo" );

    function my_in_array(needle, haystack)
{
   var i=0;
   for(i=0;i<haystack.length;i++)
   {
    if(needle==haystack[i])
      return true;
   }

    return false;
}


function agregapermisodeusaurio(permiso)
{
permisosusuario.push(permiso);
}

function setFechas() {
	//HAblitamos los inputs de fecha para poder tomar sus valores
 document.getElementById('fechainicio').disabled=false;
 document.getElementById('fechafinal').disabled=false;

//Formateamos fecha de inicio, pasamos del formato del calendario al de la bas de datos i.e. mm/dd/yyyy -->>  yyyy-mm-dd
 if(document.getElementById('fechainicio').value != ""){
 fitemp=document.getElementById('fechainicio').value;
 fitemp=fitemp.split("/");
 fechainicio=fitemp[2]+"-"+fitemp[0]+"-"+fitemp[1];
}


  	//Formateamos fecha final, pasamos del formato del calendario al de la bas de datos i.e. mm/dd/yyyy -->>  yyyy-mm-dd
  if(document.getElementById('fechafinal').value !="")
  {
 fitemp=document.getElementById('fechafinal').value;
 fitemp=fitemp.split("/");
 fechafinal=fitemp[2]+"-"+fitemp[0]+"-"+fitemp[1];
}


 //fechainicio=document.getElementById('fechainicio').value;
 //fechafinal=document.getElementById('fechafinal').value;

 	//Deshabilitamos los inputs de nuevo
 document.getElementById('fechainicio').disabled=true;
 document.getElementById('fechafinal').disabled=true;
}



//DEtermina si las fechas fueron seleccionadas, posteriormente usada para saber si se deben de formatear dichas fechas
function siFechasSeleccionadas() {
	if(fechafinal!="" && fechainicio!="")return true;

	return false;
}


 

															//Funciones de aprobacion, modificacion y cancelacion
//****************************************************************************************************************************************************************************** 
//************************************************ *****************************************************************************************************************************
//****************************************************************************************************************************************************************************** 
//************************************************ *****************************************************************************************************************************




          function aprobar(idsolicitud,idsolicituddepto,jefea,rh)
            {
            	/*
            	<form id='formmodificar$idsolicitud' class='form-inline'  method='POST' hidden>
                          <input name=accion value='m' hidden>
                          <input name='idusuario' value=$idusuario hidden>
                          <input name='diaslaborados' value=$diaslaborados hidden>
                          <input name='idsolicitud' value=$idsolicitud hidden>
                          <input type=submit id='modificar$idsolicitud' hidden>
                  </form>

            	*/

	        /*   if(confirm('Seguro desea aprobar el permiso con id ' + idsolicitud +' ?'))
                {*/


	            	$.get("apruebasolicitud.php", //url
	            			{idsolicitud:idsolicitud,idsolicituddepto:idsolicituddepto, Jefe:jefea, RH:rh , idusuario: idusuario ,accion:'a',iddepartamento:iddepartamento}, //datos
	            			 function( data ) { //funcion si la peticion es exitosa
                                            //alert(data);
                                            mandafiltros();
					});
	               
                //}
            }
            
            function denegar(idsolicitud)
            {
                  if(confirm('Seguro desea cancelar el permiso con id ' + idsolicitud +' ?'))
		                {
			            	$.get( "cancelasolicitud.php", //url
			            				{
			            					idsolicitud:idsolicitud , idusuario: idusuario ,accion:'a',iddepartamento:iddepartamento
			            				}, //datos
			            			 function( data ) 
			            			 { //funcion si la peticion es exitosa
			  							//alert(data);
										});
			               mandafiltros();	
		     	      }
            }


        function modificar(idsolicitud,idusuarioSol)
            {
			         if(confirm('Seguro desea modificar el permiso con id '+  idsolicitud +' ?'))
			                {
			                                   redirectPost('acciones.php', {accion: 'm', idusuario: idusuario, idsolicitud:idsolicitud, idusuarioSol:idusuarioSol });
			                }
            }

        function detalles(idsolicitud)
              {
			         if(confirm('Seguro desea revisar el historial  del permiso con id '+  idsolicitud +' ?'))
			                {
			                                   redirectPost('detalles.php', {accion: 'm', idusuario: idusuario, idsolicitud:idsolicitud,origen:'revisar' });
			                }
              }


//****************************************************************************************************************************************************************************** 
//************************************************ ***************************************************************************************************************************** 
//****************************************************************************************************************************************************************************** 
//************************************************ *****************************************************************************************************************************             









											//Agrega y quita filtros
//****************************************************************************************************************************************************************************** 
//************************************************ *****************************************************************************************************************************
//****************************************************************************************************************************************************************************** 
//************************************************ *****************************************************************************************************************************


//Esta funcion agrega los estados al arreglo de filtraestado, cada vez que se agrege o elimine un estado
//Se debe de mandar a llamar una funcion que refresque la vista de las solicitudes basado en el/los estado existentes
//en el arrelgo dinamic0, que en rrealidad no es un arrelgo, pasa a ser una pila
function agregafiltroestado(estado) {
var count=0;
$("[id^='cbxestado']").each(function(){
    if($(this).prop("checked"))count++;
});
if(count==0){
    $("#cbxestado-44").prop("checked",true);
    filtrosestado.push(-44);
}
	if (document.getElementById('cbxestado'+estado).checked ){
           if(estado==-44){
               $("[id^='cbxestado']").prop( "checked", false );
               $("#cbxestado-44").prop("checked",true);
               filtrosestado = [];
           }
           else{
               if($("#cbxestado-44").prop("checked")){
                   $("#cbxestado-44").prop("checked",false);
                   filtrosestado = [];
               }
           }
           filtrosestado.push(estado);
	}
	else{
	 var index= filtrosestado.indexOf(estado);
	 filtrosestado.splice(index,1);
	}
        //console.log(filtrosestado);
	mandafiltros();
}

function agregafiltroTipo(tipo,nombreTipo) {
var count=0;
console.log(tipo);
$("[id^='cbxTipo']").each(function(){
    if($(this).prop("checked"))count++;
});
if(count==0){
    $("#cbxTipo-44").prop("checked",true);
    filtrosTipo=[];
}

	if (document.getElementById('cbxTipo'+tipo).checked){
           if(tipo==-44){
               $("[id^='cbxTipo']").prop( "checked", false );
               $("#cbxTipo-44").prop("checked",true);
               filtrosTipo = [];
           }
           else{
               if($("#cbxTipo-44").prop("checked")){
                   $("#cbxTipo-44").prop("checked",false);
                   filtrosTipo = [];
               }
           }
           filtrosTipo.push(nombreTipo);
	}
	else{
	 var index= filtrosTipo.indexOf(nombreTipo);
	 filtrosTipo.splice(index,1);
	}
        //console.log(filtrosTipo.toString());
	mandafiltros();
}



function agregaFechas(fechaIni,fechaFin){
fechainicio=fechaIni;
fechafinal=fechaFin;
mandafiltros();
}

function changeFechaIni(){
    $('#fecha_fin').val('');
    picker2.set('min', $('#fecha_ini').val());
}



function filtrousuario(nombre,deptos) {
    if(nombre!='Seleccione un usuario'){
	usuario=nombre;
        if(usuario=='TODOS'){
        filtradodepartamentos=deptos.split(';');
        filtradodepartamentos.pop();
        }
        else{
            filtradodepartamentos=[];
        }
        
	mandafiltros();
    }
}







//Esta funcion agrega los ids de los departamentos que se busca filtrar a la pila filtradodepartamentos
function agregafiltrodepartamento(iddepartamento, datos) {
    var splitIds=datos.split(';');
    var count=0;
    //document.getElementById('selectusuarios').selectedIndex=0;
    //$('.select2').trigger('change');
    document.getElementById("dep_-44").checked=true;
    filtradodepartamentos=[];
    //console.log(splitIds.length-1);
    while(count<splitIds.length-1){
       if(document.getElementById("dep_"+splitIds[count]).checked){
           filtradodepartamentos.push(parseInt(splitIds[count]));
       }
       else{
           document.getElementById("dep_-44").checked=false;
       }
         count++;
    }
    
    
    /*
       if(document.getElementById('dep_'+iddepartamento).checked){
           
           
		filtradodepartamentos.push(iddepartamento);
	}
        
	else{
        if (document.getElementById('dep_-44').checked){
            document.getElementById('dep_-44').checked=false;
        }
        var index = filtradodepartamentos.indexOf(iddepartamento);   
        filtradodepartamentos.splice(index, 1);
        
        
        //filtradodepartamentos.pop(iddepartamento);
    }
       /* if(filtradodepartamentos.length<1){
           filtradodepartamentos.push(-1000);
        }*/
            
   
	mandafiltros();
	//muestrafiltrodepartamento();
	
}


/*function muestrafiltrodepartamento()
{
	document.getElementById('displaydepartamentos').innerHTML="";
	if(my_in_array(-44,filtradodepartamentos)  )
	{
		return;
	}
	
	for (var i = 0; i < filtradodepartamentos.length; i++) {
		document.getElementById('displaydepartamentos').innerHTML+=
		'<span class="badge" >' + objetodepartamento['m'+filtradodepartamentos[i]] +'</span>'  ;
		//if(i+1 < filtradodepartamentos.length)
		//	document.getElementById('displaydepartamentos').innerHTML+=', ' ;

	}

}*/


														//Funciones de interaccion con el servidor y manejo de datos para la vista

//****************************************************************************************************************************************************************************** 
//************************************************ *****************************************************************************************************************************
//****************************************************************************************************************************************************************************** 
//************************************************ *****************************************************************************************************************************



//Esta funcion es larga, no desesperes, todo esta comentado-
function mandafiltros()
{
        setFechas();
  	url="../php/filtrasolicitudes.php?idu="+idusuario+"&dptos="+filtradodepartamentos.toString()+"&estados="+filtrosestado.toString()+"&fi="+fechainicio+"&ff="+fechafinal+"&tipo="+filtrosTipo.toString()+"&tipoFecha="+$("#cbxTipoFecha").val();
		    	
	if(usuario.length==0 || usuario=="TODOS" ){//Manda filtros de departamento y estado con todos los usuarios
		        $.ajax({
				type:"GET",
				url:url,
				datatype:"text",
				success:llenatabla,
				error: function (xhr,ajaxOptions, thrownError){alert("Error accesando al servidor");},
				async:true,
				timeout: 60000

			});

									
		}

	else{
            //Ignora los filtros por departamento y solo toma en cuenta los filtros de estado
            url=url+"&idFiltro="+usuario;
				$.ajax({
				type:"GET",
				url:url,
				datatype:"text",
				success:llenatabla,
				error: function (xhr,ajaxOptions, thrownError){alert("Error accesando al servidor");},
				async:true,
				timeout: 60000
                                });



	}
         validaBotones();
	//*************************************************************************************
  	//*************************************************************************************
//alert("http://192.9.200.8/vacaciones/php/filtrasolicitudes.php?idu="+idusuario+"&dptos="+filtradodepartamentos.toString()+"&estados="+filtrosestado.toString()+"&fi="+fechainicio+"&ff="+fechafinal);
}



function llenatabla(data, textStatus, 	xhr) {

	
								var lineas= JSON.parse(data);
								var comentarios="";
								var tr="\
								<thead>\\n\
						          <th class='text-center' ><input type='checkbox' id='chk-All'></th>\
						          <th class='text-center' >ID</th>\
						          <th class='text-center' data-sorter='shortDate' data-date-format='ddmmyyyy'>FECHA</th>\
						          <th class='text-center' >NOMBRE</th>\
						          <th class='text-center' data-sorter='shortDate' data-date-format='ddmmyyyy'>INICIO</th>\
						          <th class='text-center' data-sorter='shortDate' data-date-format='ddmmyyyy'>FINAL</th>\
						          <th class='text-center' >DIAS</th>\
						          <th class='text-center' >HORAS</th>\
						          <th class='text-center'>TIPO</th>\
						          <th class='text-center'>PAGO</th>\
						          <th class='text-center' >JEFE AREA</th>\
						          <th class='text-center' data-sorter='shortDate' data-date-format='ddmmyyyy'>FECHA AUTORIZA</th>\
						          <th class='text-center' >RH</th>\
						          <th class='text-center' data-sorter='shortDate' data-date-format='ddmmyyyy'>FECHA AUTORIZA</th>\
						          <th class='text-center'>ACCIONES</th>\\n\
						          </thead>\
								<tbody>";
								var clase ="";
							    for (var i=0; i<lineas.length; i++) 
							    		{


							    				//          DETERMINAMOS DE QUE COLOR SE DEBE DE IMPRIMIR EL RENGLON
							    				//****************************************************************************************		
							    				//****************************************************************************************
							    			    if ( typeof lineas[i].JEFEAPROBO != 'undefined' ) {
											        //clase = "<tr class='info' id='tr"+lineas[i].IDSOLICITUD+"' onclick=mostrarComentario("+lineas[i].IDSOLICITUD+")>";
                                                                                                clase= "info";
											    }
											    if ( typeof lineas[i].RHAPROBO!= 'undefined') {
											        //clase = "<tr class='info' id='tr"+lineas[i].IDSOLICITUD+"' onclick=mostrarComentario("+lineas[i].IDSOLICITUD+")>";
                                                                                                clase= "info";
											    }
											    if ( (typeof lineas[i].JEFEAPROBO!= 'undefined' && typeof lineas[i].RHAPROBO!= 'undefined') || lineas[i].TIPOACCION=='au') {
											        //clase = "<tr class='success' id='tr"+lineas[i].IDSOLICITUD+"' onclick=mostrarComentario("+lineas[i].IDSOLICITUD+")>";
                                                                                                clase= "success";
											    }
											    if (lineas[i].TIPOACCION=="den") {
											        //clase = "<tr class='danger' id='tr"+lineas[i].IDSOLICITUD+"' onclick=mostrarComentario("+lineas[i].IDSOLICITUD+")>";
                                                                                                clase= "danger";
											    }
											    if ( typeof lineas[i].JEFEAPROBO=='undefined' && typeof lineas[i].RHAPROBO=='undefined' &&  lineas[i].TIPOACCION!="den") {
											        //clase = "<tr class='warning' id='tr"+lineas[i].IDSOLICITUD+"' onclick=mostrarComentario("+lineas[i].IDSOLICITUD+")>";
                                                                                                clase= "warning";
											    }
											   



											 //INFORMACION GENERAL DE LA SOLICITUD
							    			//******************************************************************
							    			//******************************************************************
                                                                                splitFechasAlta=lineas[i].FECHAALTA.split(" ");
							    			
                                                                                
                                                                                tr = tr + "<tr class='" + clase + "' id='tr"+lineas[i].IDSOLICITUD+"' onclick=mostrarComentario("+lineas[i].IDSOLICITUD+")>" +
								            "<td style=' padding-top: 15px; '>" + (clase!='success' && clase!='danger'?"<input type='checkbox' class='"+(($("#idDep").val() != "29" && typeof lineas[i].JEFEAPROBO=='undefined') || ($("#idDep").val() == "29" && typeof lineas[i].RHAPROBO=='undefined')?"chkAprobar":"")+" " + (typeof lineas[i].RHAPROBO=='undefined'?"chkCancelar":"") + "' idSolicitud = '" + lineas[i].IDSOLICITUD + "' name='chkSolicitud' id='chk-" + lineas[i].IDSOLICITUD + "'>":"") + "</td>" +
								            "<td style=' padding-top: 15px; '>" + lineas[i].IDSOLICITUD + "</td>" +
								            "<td style=' padding-top: 15px; '>" + splitFechasAlta[0] + "<br>"+splitFechasAlta[1]+"</td>" +
								            "<td style=' padding-top: 15px; text-align: left;'>" + lineas[i].NOMBRE + "</td>" +
								            "<td style=' padding-top: 15px;'>" + lineas[i].FECHAINICIO + "</td>" +
								            "<td style=' padding-top: 15px;'>" + lineas[i].FECHAFIN + "</td>" +
								            "<td style=' padding-top: 15px;'>" + lineas[i].TOTALDIAS + "</td>" +
								            "<td style=' padding-top: 15px;'>" + lineas[i].HORAS + "</td>" +
								            "<td style=' padding-top: 15px;'>" + lineas[i].TIPODEPERMISO + "</td>"+
								            "<td style=' padding-top: 15px;'>" + (lineas[i].OPCIONPAGO=='CONSUELDO'?'Con sueldo':(lineas[i].OPCIONPAGO=='SINSUELDO'?'Sin sueldo':'Tiempo')) + "</td>";
                                                                            



								            //PARA IMPRIMIR UN - ENVEZ DE UNDEFINED CUANDO LOS CAMPOS DE IDAUTORIZA* NO ESTEN LLLENOS
								            //******************************************************************
								            //******************************************************************
								            	if(lineas[i].JEFEAPROBO || "")
								            		{
								            			tr = tr + "<td style=' padding-top: 15px; '>" + lineas[i].JEFEAPROBO + "</td>" ;
								            			
								            		}
								        		else
								        			{
								        				tr = tr + "<td style=' padding-top: 15px;'>" + "-" + "</td>" ;
								        			}

												if(lineas[i].FECHAAUTORIZOJEFEAREA || "")
								            		{
                                                                                            splitFechasJefe=lineas[i].FECHAAUTORIZOJEFEAREA.split(" ");
								            			tr = tr + "<td style=' padding-top: 15px;'>" + splitFechasJefe[0] + "<br>"+splitFechasJefe[1]+ "</td>" ;
								            		}
								        		else
								        			{
								        				
								        				tr = tr + "<td style=' padding-top: 15px;'>" + "-" + "</td>" ;
								        			}

								        			if(lineas[i].RHAPROBO || "")
								            		{
								            			tr = tr + "<td style=' padding-top: 15px;'>" + lineas[i].RHAPROBO + "</td>" ;
								            		}
								        		else
								        			{
								        				
								        				tr = tr + "<td style=' padding-top: 15px;'>" + " -" + "</td>" ;
								        			}

								        			if(lineas[i].FECHAAUTORIZORECURSOSH || "")
								            		{
                                                                                            splitFechasRH=lineas[i].FECHAAUTORIZORECURSOSH.split(" ");
								            			tr = tr + "<td style=' padding-top: 15px;'>" + splitFechasRH[0] + "<br>"+splitFechasRH[1]+ "</td>" ;
								            		}
								        		else
								        			{
								        				
								        				tr = tr + "<td style=' padding-top: 15px;'>" + " -" + "</td>" ;
								        			}
								        			


				//Boton de acciones
				//**************************************************************************************************
				//**************************************************************************************************

				tr = tr + "<td><div class='dropdown'>"
       					+ "<button class='btn btn-info dropdown-toggle' type='button' data-toggle='dropdown'>Acciones"
                        + "<span class='caret'></span></button><ul class='dropdown-menu dropdown-menu-administrar'>";


                 if(my_in_array(4,permisosusuario))
                 {
        if ((typeof lineas[i].JEFEAPROBO=='undefined' || typeof lineas[i].RHAPROBO=='undefined') &&  lineas[i].TIPOACCION!="den" && lineas[i].TIPOACCION!="au") {
                if(($("#idDep").val() != "29" && typeof lineas[i].JEFEAPROBO=='undefined') || ($("#idDep").val() == "29" && typeof lineas[i].RHAPROBO=='undefined')){    
                tr = tr + " <li> \
                       <a id='linkAprobar-" + lineas[i].IDSOLICITUD + "' href=javascript:aprobar("+lineas[i].IDSOLICITUD+","+lineas[i].IDDEPARTAMENTO+",'"+lineas[i].JEFEAPROBO+"','"+lineas[i].RHAPROBO+"');> \
                       <button type='button' class='btn btn-success'>\
                       <span class='glyphicon glyphicon-ok'></span>\
                       Aprobar &nbsp;\
                       </button>\
                       </a> \
                   </li>";
                    
                    
                }
                if(typeof lineas[i].RHAPROBO=='undefined'){
                    tr = tr + "<li> \
                         <a href='javascript:denegar("+lineas[i].IDSOLICITUD+")'> \
                         <button type='button' class='btn btn-danger'>\
                         <span class='glyphicon glyphicon-remove'></span> \
                         Cancelar </button> </a> </li>";
                    
                    tr = tr + "<li> \
                       <a href='javascript:modificar("+lineas[i].IDSOLICITUD+","+lineas[i].IDUSUARIO+")'> \
                       <button type='button' class='btn btn-warning'>\
                       <span class='glyphicon glyphicon-warning-sign'>\
                       </span> Modificar </button> </a></li>";
                    }
                 }
             }
                 /* if(my_in_array(5,permisosusuario))
                 {
                  tr = tr + "<li> \
                       <a href='javascript:modificar("+lineas[i].IDSOLICITUD+")'> \
                       <button type='button' class='btn btn-warning'>\
                       <span class='glyphicon glyphicon-warning-sign'>\
                       </span> Modificar </button> </a></li>";
                 }*/

                   /*if(my_in_array(6,permisosusuario))
                 {
                  tr = tr + "<li> \
                         <a href='javascript:denegar("+lineas[i].IDSOLICITUD+")'> \
                         <button type='button' class='btn btn-danger'>\
                         <span class='glyphicon glyphicon-remove'></span> \
                         Cancelar </button> </a> </li>";
                 }*/


                tr = tr + " <li>\
                   <a href='javascript:detalles(" +  lineas[i].IDSOLICITUD  +")'>\
                   <button type='button' class='btn btn-info'>\
                   <span class='glyphicon glyphicon-folder-open'></span>\
                     &nbsp; Detalles\
                   </button>\
                   </a>\
                   </li></ul></div>";
                tr = tr + "</td></tr>";
                    comentarios=comentarios +"<tr class='comentario " + clase +"' id='comentario"+  lineas[i].IDSOLICITUD  +"' hidden>\
                            <td colspan='1'></td><td colspan='12'>Comentario: "+lineas[i].COMENTARIO+"</td>\
                            <td colspan='2'></td></tr>"
                    

		tr = tr + "\
		  <form id='formaprobar"+lineas[i].IDSOLICITUD+"' class='form-inline' method='POST' hidden>\
                    <input name=accion value='a' hidden>\
                    <input name='idusuario' value="+idusuario+" hidden>\
                    <input type=hidden name='diaslaborados' value=$diaslaborados hidden>\
                    <input type='hidden' name='iddepartamento' value="+iddepartamento+" hidden> \
                    <input type=hidden name='idsolicitud' value="+lineas[i].IDSOLICITUD+" hidden>\
                    <input type=submit id='aprobar"+lineas[i].IDSOLICITUD+"' hidden>\
                  </form>";

			
							   			 }; 
           
	    $("#tabla_solicitudes").html(tr+"</tbody>");
            $("body .comentario").remove();
            $("body").append(comentarios);
            $("#tabla_solicitudes").trigger('updateAll');
            $("#tabla_solicitudes th").on("click",function(){
                $('#tabla_solicitudes').find('.comentario').remove();
                $('#tabla_solicitudes .sinBorde').removeClass('sinBorde');
            });
            $('#tabla_solicitudes').tablesorter();
            $('#tabla_solicitudes').tablesorter({
    dateFormat : "ddmmyyyy", // set the default date format

    // or to change the format for specific columns, add the dateFormat to the headers option:
    headers: {
      0: { sorter: "shortDate" } //, dateFormat will parsed as the default above
      // 1: { sorter: "shortDate", dateFormat: "ddmmyyyy" }, // set day first format; set using class names
      // 2: { sorter: "shortDate", dateFormat: "yyyymmdd" }  // set year first format; set using data attributes (jQuery data)
    }

  });       $("[name='chkSolicitud'],#chk-All").parent().unbind("click");
            $("[name='chkSolicitud'],#chk-All").parent().on("click", function(e){
                e.stopPropagation();
                $(this).find("input[type='checkbox']").click();
            });
            $("[name='chkSolicitud']").unbind("click");
            $("[name='chkSolicitud']").on("click", function(e){
               e.stopPropagation();
            });
            $("#chk-All").unbind("click");
            $("#chk-All").on("click", function(e){
                 e.stopPropagation();
                $("[name='chkSolicitud']").prop("checked", $(this).prop("checked"));
            });
            $("[name='chkSolicitud'], #chk-All").unbind("change");
            $("[name='chkSolicitud'], #chk-All").on("change", function(){
                validaBotones();
            });
            validaBotones();
}
                                                                        

$(document).ready(function(){
    asignaFuncionBotones();
    $('#fecha_ini').on('change', function(){
        $('#cbxTipoFecha').prop('disabled', ($('#fecha_ini').val()==''));
    });
    
    $('#fecha_fin').on('change', function(){
        $('#cbxTipoFecha').prop('disabled', ($('#fecha_ini').val()==''));
    });
});


function asignaFuncionBotones(){
    quitarFuncionBotones();

    $("#btnCancelarSolicitudes:not(':disabled')").on('click',function(){
        var solicitudes = $("[name='chkSolicitud']:checked").map(function(){return $(this).attr("idsolicitud");}).get();
        
         $.ajax({
                type:"POST",
                url: "../php/cancelasolicitudMasiva.php",
                datatype:"text",
                success:mandafiltros,
                error: function (xhr,ajaxOptions, thrownError){alert("Error accesando al servidor");},
                data: {
                    solicitudes:JSON.stringify(solicitudes)
                    }, 
                async:true,
                timeout: 60000
        });
    });
    
    $("#btnAutorizarSolicitudes:not(':disabled')").on('click',function(){
        var solicitudes = $("[name='chkSolicitud']:checked").map(function(){return $(this).attr("idsolicitud");}).get();
        
           $.ajax({
                type:"POST",
                url: "../php/apruebasolicitudMasiva.php",
                datatype:"text",
                success:mandafiltros,
                error: function (xhr,ajaxOptions, thrownError){alert("Error accesando al servidor");},
                data: {
                    solicitudes:JSON.stringify(solicitudes),
                    iddepartamento:iddepartamento
                    }, 
                async:true,
                timeout: 60000
        });
       
    });
}

function quitarFuncionBotones(){
    $("#btnCancelarSolicitudes").unbind('click');
    
    $("#btnAutorizarSolicitudes").unbind('click');
}

function validaBotones(){
    if($("[name='chkSolicitud'].chkaprobar:checked").length == $("[name='chkSolicitud']:checked").length && $("[name='chkSolicitud'].chkaprobar:checked").length > 0){
        $("#btnAutorizarSolicitudes").prop('disabled', false);
    }
    else $("#btnAutorizarSolicitudes").prop('disabled', true);
    
    if($("[name='chkSolicitud'].chkCancelar:checked").length == $("[name='chkSolicitud']:checked").length && $("[name='chkSolicitud'].chkCancelar:checked").length > 0){
        $("#btnCancelarSolicitudes").prop('disabled', false);
    }
    else $("#btnCancelarSolicitudes").prop('disabled', true);
asignaFuncionBotones();

}



function checkTodos(datos){
    var splitIds=datos.split(';');
    var count=0;
    //document.getElementById('selectusuarios').selectedIndex=0;
    //$('.select2').trigger('change');
    filtradodepartamentos=[];
    if(document.getElementById("dep_-44").checked==true){
    while(count<splitIds.length-1){
       document.getElementById("dep_"+splitIds[count]).checked=true;
       filtradodepartamentos.push(parseInt(splitIds[count]));
         count++;
         //alert(filtradodepartamentos.toString()+"asd");
    }
    
}
else{
    filtradodepartamentos=[];
    filtradodepartamentos[0]=-1;
     while(count<splitIds.length-1){
       document.getElementById("dep_"+splitIds[count]).checked=false;
         count++;
         
    }
}

mandafiltros();
//muestrafiltrodepartamento();
}

function mostrarComentario(idsolicitud){
    console.log("Entro mostrarComentario");
    if(!$("#tabla_solicitudes").find('#comentario'+idsolicitud).length > 0 ){
       //$('#tabla_solicitudes').find('.comentario').remove();
       //$('#tabla_solicitudes').find('.sinBorde').removeClass("sinBorde");
       $('#tr'+idsolicitud).after($('#comentario'+idsolicitud).clone()); 
       $('#tabla_solicitudes').find('.comentario').show();
       $('#tr'+idsolicitud).addClass("sinBorde");
    }
    else{
        $('#tabla_solicitudes').find('#comentario'+idsolicitud).remove();
        $('#tr'+idsolicitud).removeClass("sinBorde");
    }
    
}




