 var count=0; 

function recibeDias(dias,idusuario)
{  
  
    if (window.XMLHttpRequest) {
                  // code for IE7+, Firefox, Chrome, Opera, Safari
                  xmlhttp=new XMLHttpRequest();
                  } 
                  else {  // code for IE6, IE5
                  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                  }

        xmlhttp.open("GET","cambiarDias.php?idu="+idusuario+"&dias="+dias,true);

  xmlhttp.send();     


}
function diaLab(){
    var count=0;
    var stringDias="";
           var checkbox = document.getElementsByName("diasLab"); 
           while(checkbox[count]) {
                if (checkbox[count].checked){
                    if(stringDias===""){
                        stringDias=checkbox[count].value;
                                      }
                    else{
                  stringDias=stringDias+","+checkbox[count].value;
                        }
            $('#dia'+checkbox[count].value).addClass("verde");
          }
          else  $('#dia'+checkbox[count].value).removeClass("verde");
                count++;
            }
            document.getElementById('txtDias').value=stringDias;
}

function agregarDia(dia){
        $('#diasLab'+dia).click();
}



function fondoHide(idud,dias,nombre,fechaIngreso){
   if(nombre!='' && nombre!=null)document.getElementById("lblNombre").innerHTML = nombre;
   if($('.fondo').is(':hidden')){
       $("#idDias").val(idud);
  $.ajax({
				type:'GET',
				url:'../php/mandarDiasLaborables.php?idu='+idud,
				datatype:'text',
                                beforeSend: function () {
                                $('.fondo').show();
                                },
				success:llenarDiasLab, 
				error: function (xhr,ajaxOptions, thrownError){alert('Error accesando al servidor ');},
				async:true,
				timeout: 60000

			});
   
}
else{
    $("#idDias").val('');
    console.log(fechaIngreso);
    $.ajax({
				type:'GET',
				url:'../php/mandarDiasLaborables.php?idu='+idud+'&dias='+dias+'&fechaingreso='+fechaIngreso+'&accion=1',
				datatype:'text',
                                beforeSend: function () {
                                $('.fondo').show();
                                },
				success:guardarDias, 
				error: function (xhr,ajaxOptions, thrownError){alert('Error accesando al servidor ');},
				async:true,
				timeout: 60000

			});
    
    
    
    


}

diaLab();
}

function guardarDias(data){
$("body").css("overflow-y", "auto");
count=0;
$('.fondo').hide();
$('.modal2').hide();
$("#txtDias").val('');
var checkbox = document.getElementsByName("diasLab"); 
        while(checkbox[count]) {
        checkbox[count].checked=false;        
        count++;
           }
}

function llenarDiasLab(data){
   $("body").css("overflow-y", "hidden");
   count=0;
   diasLab=data;
   if(diasLab!='' && diasLab!=null){
        var fechaIngreso=diasLab.split('|');
        var splitDias=fechaIngreso[0].split(',');
      while(splitDias[count]){
        if(!document.getElementById('diasLab'+splitDias[count]).checked){
          document.getElementById('diasLab'+splitDias[count]).checked=true;
        }
        else{
          document.getElementById('diasLab'+splitDias[count]).checked=false;
        }
        count++;
      }
    }
    else{
    count=1; 
    while(count<=7){
        document.getElementById('diasLab'+count).checked=false;
        count++;
}

}
var date = new Date(fechaIngreso[1]);
picker.set('select', date);
$("#txtFechaIngreso2").val(picker.get('highlight', 'dd-mm-yyyy'));
$("#txtFechaIngreso").val(picker.get('highlight', 'dd-mm-yyyy'));

diaLab();
$('.fondo').show();
$('.modal2').show();
}



function abrirHistorico(id){
          $('#idHistorico').val(id);
          $('#formHistorico').submit();
        }