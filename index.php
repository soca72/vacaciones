<!DOCTYPE html>
<html lang="en">
<head>
  <title>Vacaciones</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="js/jquery-3.1.0.js"></script>
  <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/login.css">
<script src="js/bootstrap.min.js"></script>
  

<script type="text/javascript">
	function noespacios() {
		var er = new RegExp(/\s/);
		var web = document.getElementById('user').value;
		if(er.test(web)){
			web=web.replace(" ","");
			document.getElementById('user').value=web;
			return false;
		}
                else return true;
	}
</script>
</head>
<body>
<?php
session_start();
if(isset($_SESSION["idusuario"])!="") header('Location:php/main.php');

?>
<div class="container-fluid">
<div class="row">
    <div class="col-md-12 encabezado"> 
	<b><font size="3" face="Verdana, Arial, Helvetica, sans-serif">Sistema de vacaciones GRUPO RIO</font></b>
		
   </div>
  </div>
<br>
<center><font color="red"><?php if(isset($_SESSION["DBConnect"])!=""){ echo $_SESSION["DBConnect"];$_SESSION["DBConnect"]="";}?></font></center>
<br>
<br>

    <div class="row vertical-offset-100">
    	<div class="col-md-4 col-md-offset-4">
    		<div class="panel panel-default">
                    <div class="form-group">
                        <center>
			    		    <img src="images/logo_rio.jpg" width="400">
			</center>
                                </div>
			  	<div class="panel-heading" align="center">
                                    
			    	<h3 class="panel-title">Bienvenido</h3>
			 	</div>
			  	<div class="panel-body">
			    	<form accept-charset="UTF-8" role="form" action="php/comprobarusuario.php" method="POST">
                   				 <fieldset>
			    	  	
                                        <div class="form-group">
			    		    <input class="form-control" placeholder="Usuario" name="nombreusuario" type="text" id="user" onkeyup="noespacios()" onkeydown="noespacios()" requiered>
			    		</div>
			    		<div class="form-group">
			    			<input class="form-control" placeholder="Contraseña" name="contrasena" type="password" id="password" requiered>
                                                
			    		</div>	
                                        <div class="form-group" style="text-align:center;">
                                                <font color="red"><?php if(isset($_SESSION["badUser"])!=""){ echo $_SESSION["badUser"];$_SESSION["badUser"]="";}?></font>
			    		</div>	    		
   	
			    	    </div>
			    		<input class="btn btn-md  btn-primary btn-block" type="submit" value="Ingresar" id="login">
			    	</fieldset>
			      	</form>
                                  <?php if(isset($_POST["q"])!="" && isset($_POST["q"])!=null){
                                      $q=$_POST['q'];
                                      echo " 
                                    <form accept-charset='UTF-8' id='iniciarSesion' action='php/comprobarusuario.php' method='POST'>
                                        <input class='form-control'  name='idUsuario' value='$q' type='hidden' readonly id='idUsuario'>
                                    </form>
			    </div>
			</div>
                        <script>
                             if(document.getElementById('idUsuario').value!=''){
                                 document.getElementById('iniciarSesion').submit();
                             }
                                  </script>";
                                  
                                  }?>
		</div>
	</div>
</div>
 		 <script>
                             
                                  $(document).ready(function(){

                                     $('#login').click(function(){
					if($('#user').val()===''){
						alert('Ingresa el usuario');
					return false;
								}
						if($('#password').val()===''){
						alert('Ingresa la contraseña');
						  return false;
						     }
						});
					  });
                                          
                                          
   window.onbeforeunload = function() {
window.location.href="../index.php";
}
			</script>
                        
</body>
</html>
