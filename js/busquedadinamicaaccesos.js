//Toma valor que se ingresa en campo nombreusaurio y lo manda a la pagina de busqueda
//*******************************************************************************************************************************************************************
//*******************************************************************************************************************************************************************           
function showResult(str) {
  $("#tr").show();
  if (str.length==0) {
    document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="0px";
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("livesearch").innerHTML=this.responseText;
      document.getElementById("livesearch").style.border="4px solid #A5ACB2";
    }
  }
  xmlhttp.open("GET","busquedaaccesos.php?q="+str,true);
  xmlhttp.send();
}

//Asignacion de valor clickeado en lista alimentada por showResult() al campo de nombreusaurio
//Esta funcion recibe un dato: idusuario | nombreusuario
// 640 | Pedro Salcido Morales y pone el nombre en el campo de nombreusuario
//*******************************************************************************************************************************************************************
//*******************************************************************************************************************************************************************           
function hello(a)
{
    var temp=a.split("|");
    document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="0px";
    document.getElementById("busqueda").value=a;
    document.getElementById("datausaurio").value=temp[0];
    document.getElementById("nombreusuarioagrega").value="'"+temp[1]+"'";
    $('#forminfo').attr('action','accesos.php');
    $('#mandarinfo').click();
    //showdata(a);
}



//Alta baja de permisos con idusuario e idpermiso
//*******************************************************************************************************************************************************************
//*******************************************************************************************************************************************************************           
function recibepermiso(idpermiso)
{
  var idusuarioapermisos=document.getElementById('datausaurio').value;
   
//    alert(idusuarioapermisos+ idpermiso);  
            if (window.XMLHttpRequest) {
                  // code for IE7+, Firefox, Chrome, Opera, Safari
                  xmlhttp=new XMLHttpRequest();
                  } 
                  else {  // code for IE6, IE5
                  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                  }

   
  if(document.getElementById(idpermiso).checked )
    {
        xmlhttp.open("GET","agregapermiso.php?idu="+idusuarioapermisos+"&idp="+idpermiso,true);
        
    }
  else
    {
      xmlhttp.open("GET","eliminapermiso.php?idu="+idusuarioapermisos+"&idp="+idpermiso,true);
      $('#cbxtodospermisos').prop('checked',false);
    }
  xmlhttp.send();     


}

//Alta baja de departamentos que el usaurio puede aprobar con base en iddepartamento e idsuario
//*******************************************************************************************************************************************************************
//*******************************************************************************************************************************************************************           
function recibedepartamento(iddptoorigen)
{
  if(iddptoorigen!=-44){
      var temp=iddptoorigen.split("_");
      var iddepto=temp[1];
    }
  var idusuarioadepto=document.getElementById('datausaurio').value;
            if (window.XMLHttpRequest) {
                  // code for IE7+, Firefox, Chrome, Opera, Safari
                  xmlhttp=new XMLHttpRequest();
                  } 
                  else {  // code for IE6, IE5
                  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                  }
 if(iddptoorigen==-44){ 
      
   if(document.getElementById("todosdepaprob").checked ){
      xmlhttp.open("GET","agregadepartamento.php?idu="+idusuarioadepto+"&idds=-44",true);
   }
   else{
       xmlhttp.open("GET","eliminadepartamento.php?idu="+idusuarioadepto+"&idds=-44",true);
   }
      
  }

  else if(document.getElementById(iddptoorigen).checked )
    {

        xmlhttp.open("GET","agregadepartamento.php?idu="+idusuarioadepto+"&idd="+iddepto,true);
        
    }
  else
    {
      xmlhttp.open("GET","eliminadepartamento.php?idu="+idusuarioadepto+"&idd="+iddepto,true);
      $('#todosdepaprob').prop('checked',false);
    }

  xmlhttp.send();     


}
 



//Alta baja de departamentos, de los cuales el usuariuo puede solicitar permisos
//*******************************************************************************************************************************************************************
//*******************************************************************************************************************************************************************           

 function recibedepartamentosol(iddptoorigen)
{
  if(iddptoorigen!=-44){
   var temp=iddptoorigen.split("_");
   var iddepto=temp[1];
  }
  
  var idusuarioadepto=document.getElementById('datausaurio').value;
  if (window.XMLHttpRequest) {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  } 
  else {  // code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  if(iddptoorigen==-44){ 
      
   if(document.getElementById("todosdepsol").checked ){
      xmlhttp.open("GET","agregadepartamentosol.php?idu="+idusuarioadepto+"&idds=-44",true);
   }
   else{
       xmlhttp.open("GET","eliminadepartamentosol.php?idu="+idusuarioadepto+"&idds=-44",true);
   }
      
  }
  
  else if(document.getElementById(iddptoorigen).checked )
    {
        xmlhttp.open("GET","agregadepartamentosol.php?idu="+idusuarioadepto+"&idds="+iddepto,true);
        
    }
  else
    {
      xmlhttp.open("GET","eliminadepartamentosol.php?idu="+idusuarioadepto+"&idds="+iddepto,true);
      $('#todosdepsol').prop('checked',false);
    }

  
  xmlhttp.send();     


}
 
 

 function habilitatodoslospermisos(permisos) {
  var arraypermisos=permisos.split("|");
     if(document.getElementById('cbxtodospermisos').checked)
        {
              for (var i = 1; i < arraypermisos.length; i++) 
                {
                  if( document.getElementById(''+arraypermisos[i]).checked !=true )
                  $('#'+arraypermisos[i]).click();  
                }
        }
  else
      {
            for (var i = 1; i <= arraypermisos.length; i++) 
              {
                if( document.getElementById(''+arraypermisos[i]).checked  )
                $('#'+arraypermisos[i]).click();  
              } 
      }  
    
 }




 function setAllRequestableDepartments(departamentos)
 {
    //var arrayrequestdeptos=departamentos.split(",");
     if(document.getElementById('todosdepsol').checked)
        {
              /*for (var i = 0; i < arrayrequestdeptos.length; i++) 
                {
                  if( document.getElementById(''+arrayrequestdeptos[i]).checked !=true  )
                  $('#'+arrayrequestdeptos[i]).click();                   

                }*/
         $('[id^=depsol_]').prop("checked",true); 
         recibedepartamentosol(-44);
         
        }
        else
      {
            /*for (var i = 0; i <= arrayrequestdeptos.length; i++) 
              {
                if( document.getElementById(''+arrayrequestdeptos[i]).checked  )
                $('#'+arrayrequestdeptos[i]).click();  
              }*/ 
         $('[id^=depsol_]').prop("checked",false); 
         recibedepartamentosol(-44);
      }  
        

 }




  function setAllApprovableDeptos(departamentosaprob)
 {
    /*var arrayaprobdpts=departamentosaprob.split(",");
     if(document.getElementById('todosdepaprob').checked)
        {
              for (var i = 0; i < arrayaprobdpts.length; i++) 
                {

                  if( document.getElementById(''+arrayaprobdpts[i]).checked !=true  )
                  $('#'+arrayaprobdpts[i]).click();                   

                }
        }
        else
      {
            for (var i = 0; i < arrayaprobdpts.length; i++) 
              {
                if( document.getElementById(''+arrayaprobdpts[i]).checked  )
                $('#'+arrayaprobdpts[i]).click();  
              } 
      }*/  
      if(document.getElementById('todosdepaprob').checked){
          $('[id^=dep_]').prop("checked",true); 
         recibedepartamento(-44)
      }
      else{
          $('[id^=dep_]').prop("checked",false); 
         recibedepartamento(-44)
      }

 }
