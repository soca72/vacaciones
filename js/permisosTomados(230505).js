
var filtrosestado = []; //Se agregan los id de los estados, solo existen aqui y en el webservice, todavia no en la bd
var filtradodepartamentos =[]; //Guarda id de departamentos, existen aqui y en bd
var usuario="" ; //Variable para hacer mas legible el codigo
var idusuario;
var fechainicio="";
var fechafinal="";
var permisosusuario= [];
var iddepartamento;
var depPermitidos=0;
var url="";


//Objeto que contiene los miembros estados
var objetoestado={};
objetoestado.m1='AUTORIZADAS';
objetoestado.m2='APROBADAS POR JEFE INMEDIATO';
objetoestado.m3='APROBADAS POR RECURSOS HUMANOS';
objetoestado.m4='CANCELADAS';
objetoestado.m5='PENDIENTES DE APROBACION';
var objetodepartamento={};


function agregapermisodeusaurio(permiso)
{
	permisosusuario.push(permiso);
}

function mandafiltros(idu,idf)
{
	url="../php/filtrasolicitudes.php?idu="+idu+"&idFiltro="+idf+"&estados=5,2,3,1&orden=id";
	
				$.ajax({
                                    type:"GET",
                                    url:url,
                                    datatype:"text",
                                    success:llenatabla,
                                    error: function (xhr,ajaxOptions, thrownError){
                                        alert("Error accesando al servidor ");
                                    },
                                    async:true,
                                    timeout: 60000
                                });
}



function llenatabla(data, textStatus, 	xhr) {
	console.log(JSON.parse(data));
								var lineas= JSON.parse(data);
								console.log(lineas);
								var tr="";
                                                            var clase ="";

							    for (var i=0; i<lineas.length; i++) {
                                                                            tr += "<div class='container-fluid'>";
                                                                            
							    				//          DETERMINAMOS DE QUE COLOR SE DEBE DE IMPRIMIR EL RENGLON
							    				//****************************************************************************************		
							    				//****************************************************************************************
							    			    if ( typeof lineas[i].JEFEAPROBO != 'undefined' && typeof lineas[i].RHAPROBO== 'undefined' ) {
											
                                                                                        clase = "info";
                                                                                        //console.log(clase);
                                                                                    }
                                                                                    /*
                                                                                    if ( typeof lineas[i].RHAPROBO!= 'undefined' && typeof lineas[i].JEFEAPROBO == 'undefined') {
                                                                                        tr += "<div class='container-fluid'>";
                                                                                        clase = "info";
                                                                                    }*/
                                                                                    if ( (typeof lineas[i].JEFEAPROBO!= 'undefined' && typeof lineas[i].RHAPROBO!= 'undefined') || lineas[i].TIPOACCION=='au') {
                                                                                       
                                                                                        clase = "success";
                                                                                        //console.log(clase);
                                                                                    }
                                                                                    if (lineas[i].TIPOACCION=="den") {
                                                                                    
                                                                                        clase = "danger";
                                                                                        //console.log(clase);
                                                                                    }
                                                                                    if ( typeof lineas[i].JEFEAPROBO=='undefined' && typeof lineas[i].RHAPROBO=='undefined' &&  lineas[i].TIPOACCION!="den") {
                                                                                    
                                                                                        clase = "warning";
                                                                                        //console.log(clase);
                                                                                    }
											   
                                                                                           //console.log("*******Termina linea*******");


											 //INFORMACION GENERAL DE LA SOLICITUD
							    			//******************************************************************
							    			//******************************************************************
							    		    //tr = tr + clase +
								            tr = tr +"<div class = 'row'>"+
								            "<div class='col " + clase + " text-center' >" + lineas[i].IDSOLICITUD + "</div>" +
								            "<div class='col " + clase + " text-center' ><div class='esconder'>" + lineas[i].ORDENFECHAALTA + "</div>" + lineas[i].FECHAALTA + "</div>" +
								            "<div class='col " + clase + " '>" + lineas[i].NOMBRE + "</div>" +
								            "<div class='col " + clase + " text-center' ><div class='esconder'>" + lineas[i].ORDENFECHAINICIO + "</div>" + lineas[i].FECHAINICIO + "</div>" +
								            "<div class='col " + clase + " text-center' ><div class='esconder'>" + lineas[i].ORDENFECHAFIN + "</div>" + lineas[i].FECHAFIN + "</div>" +
								            "<div class='col " + clase + " text-center' >" + lineas[i].TOTALDIAS + "</div>" +
								            "<div class='col " + clase + " text-center' >" + lineas[i].HORAS + "</div>" +
								            "<div class='col " + clase + " text-center' >" + lineas[i].TIPODEPERMISO + "</div>";
                                                                            



								            //PARA IMPRIMIR UN - ENVEZ DE UNDEFINED CUANDO LOS CAMPOS DE IDAUTORIZA* NO ESTEN LLLENOS
								            //******************************************************************
								            //******************************************************************
								            	if(lineas[i].JEFEAPROBO || "")
								            		{
								            			tr = tr + "<div class='col " + clase + " text-center'>" + lineas[i].JEFEAPROBO + "</div>" ;
								            			
								            		}
                                                                                else
                                                                                        {
                                                                                                tr = tr + "<div class='col " + clase + " text-center'>" + "-" + "</div>" ;
                                                                                        }

										if(lineas[i].FECHAAUTORIZOJEFEAREA || "")
								            		{
								            			tr = tr + "<div class='col " + clase + " text-center'><div class='esconder'>" + lineas[i].ORDENFECHAAUTORIZOJEFEAREA + "</div>" + lineas[i].FECHAAUTORIZOJEFEAREA + "</div>" ;
								            		}
                                                                                else
                                                                                        {

                                                                                                tr = tr + "<div class='col " + clase + " text-center'>" + "-" + "</div>" ;
                                                                                        }

								        	if(lineas[i].RHAPROBO || "")
								            		{
								            			tr = tr + "<div class='col " + clase + " text-center'>" + lineas[i].RHAPROBO + "</div>" ;
								            		}
                                                                                else
                                                                                        {

                                                                                                tr = tr + "<div class='col " + clase + " text-center'>" + " -" + "</div>" ;
                                                                                        }

                                                                                if(lineas[i].FECHAAUTORIZORECURSOSH || "")
								            		{
								            			tr = tr + "<div class='col " + clase + " text-center'><div class='esconder'>" + lineas[i].ORDENFECHAAUTORIZORECURSOSH + "</div>" + lineas[i].FECHAAUTORIZORECURSOSH + "</div>" ;
								            		}
                                                                                else
                                                                                        {

                                                                                                tr = tr + "<div class='col " + clase + " text-center'>" + " -" + "</div>" ;
                                                                                        }
								        			
                                                                                tr += "<div class='col " + clase + " text-center'>" + (lineas[i].OPCIONPAGO=="CONSUELDO"?"Con sueldo":(lineas[i].OPCIONPAGO=="SINSUELDO"?"Sin sueldo":(lineas[i].OPCIONPAGO=="TIEMPO"?"Tiempo":"-"))) +"</div>"         
                                                                                if(lineas[i].COMENTARIO.length>1){
                                                                                tr += "<div class='col-12 " + clase + " comentario'><b>Comentario del solicitante:</b> " + lineas[i].COMENTARIO + "</div>" 
                                                                                }if(lineas[i].COMENTARIOJEFE.length>1){
                                                                                tr += "<div class='col-12 " + clase + " comentarioJefe'><b>Comentario de jefe área: </b> " + lineas[i].COMENTARIOJEFE + "</div>"
                                                                                }if(lineas[i].COMENTARIORH.length>1){
                                                                                tr += "<div class='col-12 " + clase + " comentarioRH'><b>Comentario de RH: </b> "+ lineas[i].COMENTARIORH+ "</div>"
                                                                                }
								        	tr = tr + "</div></div>";
                                                                                
                                                                                

								         	/*tr = tr + "\
				<form id='formaprobar"+lineas[i].IDSOLICITUD+"' class='form-inline' method='POST' hidden>\
                    <input name=accion value='a' hidden>\
                    <input name='idusuario' value="+idusuario+" hidden>\
                    <input type=hidden name='diaslaborados' value=$diaslaborados hidden>\
                    <input type='hidden' name='iddepartamento' value="+iddepartamento+" hidden> \
                    <input type=hidden name='idsolicitud' value="+lineas[i].IDSOLICITUD+" hidden>\
                    <input type=submit id='aprobar"+lineas[i].IDSOLICITUD+"' hidden>\
                  </form>";
*/
			
							   			 }; 
           
	    $("#divSolicitudes > .row").html(tr);
            $("#divSolicitudes > .row  > .container-fluid > .row").unbind("click");
            $("#divSolicitudes > .row  > .container-fluid > .row").on("click", function(){
                if(!$(this).find(".comentario").is(":visible"))$(this).find(".comentario").slideDown(50);
                else $(this).find(".comentario").slideUp(50);
                if(!$(this).find(".comentarioJefe").is(":visible"))$(this).find(".comentarioJefe").slideDown(100);
                else $(this).find(".comentarioJefe").slideUp(100);
                if(!$(this).find(".comentarioRH").is(":visible"))$(this).find(".comentarioRH").slideDown(150);
                else $(this).find(".comentarioRH").slideUp(150);
            });
            
            $("#divEncabezadosSolicitudes .encabezado").unbind("click");
            $("#divEncabezadosSolicitudes .encabezado").each(function(index,element){
                $(this).on("click", function(){
                    orderBy(index+1,"#divSolicitudes");
                });
            });
            
            
            
            
            
            
            
            
            
            
            
           
            //$("#divSolicitudes").trigger('updateAll');
            //$('#divSolicitudes').tablesorter();

}               

 var order = 1;
            function orderBy(child, idTabla){
               //$body.addClass("loading");
              console.log(child);
              if(order==child){
                        order=0;
                    }
                    else{
                        order=child;
                        }
                        console.log();
              $(idTabla+" > .row").children().detach().sort(function(a,b){
                 console.log(a);
                 if($.isNumeric($(a).children().find(":nth-child("+child+")").text())){
                    if(order==child){
                        return parseInt($(a).children().find(":nth-child("+child+")").text()) < parseInt($(b).children().find(":nth-child("+child+")").text())?-1:1;
                    }
                    else{
                        return parseInt($(a).children().find(":nth-child("+child+")").text()) > parseInt($(b).children().find(":nth-child("+child+")").text())?-1:1;
                    }
                 }
                 else{ 
                    if(order==child){
                        return $(a).children().find(":nth-child("+child+")").text().localeCompare($(b).children().find(":nth-child("+child+")").text());
                    }
                    else{
                        return $(b).children().find(":nth-child("+child+")").text().localeCompare($(a).children().find(":nth-child("+child+")").text());
                    }
                 }
              }).appendTo(idTabla + " > .row");
              //$body.removeClass("loading");
          }



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
