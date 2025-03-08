function showResult(str) {
  var temp=str.split("|");
  var datoabuscar=temp[1];
  var idusuario=temp[0];
  document.getElementById('selectPermiso').selectedIndex=0;
  permiso('AAA');
  if(document.getElementById('busqueda').value==""){
     document.getElementById('datausuario').value="";
     document.getElementById('datausuario_diasLab').value="";
  }
  document.getElementById('fechainicio').value="";
  document.getElementById('fechafinal').value="";
  document.getElementById('diferenciaDias').value="";
  $("#tr").show();
  if (datoabuscar.length==0) {
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
  xmlhttp.open("GET","livesearch.php?q="+datoabuscar+"&idu="+idusuario,true);
  xmlhttp.send();
}


//Esta funcion recibe un dato: idusuario | nombreusuario
// 640 | Pedro Salcido Morales
function hello(a)
{
    var temp=a.split("|");
    //alert(temp[0]);
    /*document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="0px";*/
    document.getElementById("busqueda").value=a;
    document.getElementById("idusuariocambia").value=temp[0];
    showdata(a);
      $("#tr").hide();


}

function showdata(str) {
  if (str.length==0) {
    document.getElementById("datausuario").value="";
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
        var datos=this.responseText;
        var datoSplit=datos.split("|");
      document.getElementById("datausuario").value=datoSplit[0];
      document.getElementById("diasderechocambia").value=datoSplit[0];
      document.getElementById("datausuario_diasLab").value=datoSplit[1];
      sessionStorage.setItem('diasLaborables', datoSplit[1]);
      document.getElementById("fontDiasD").innerHTML = datoSplit[0];
      document.getElementById("fontFechaIngreso").innerHTML = datoSplit[3];
      document.getElementById("fontAntiguedad").innerHTML = datoSplit[4]+" años";
      document.getElementById("idBusqueda").value=datoSplit[5];
      document.getElementById("fechaCumple").value=datoSplit[6];
      document.getElementById('cambiarSolicitudes').submit();
       document.getElementById("fontAntiguedad2").value = datoSplit[4];
//      document.getElementById("fontAntiguedad2").innerHTML = datoSplit[4];
    }
  }
  xmlhttp.open("GET","live.php?q="+str,true);
  xmlhttp.send();
}
