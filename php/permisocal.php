<?php
error_reporting(E_ALL ^ E_NOTICE); 
session_start();
if($_SESSION["idusuario"]=="") header('Location:../index.php');
/**
* 
*/
class Permiso
{

	public $idsolicitud;
	public $fechainicial;
	public $fechafinal;
	public $mes;
	public $dia;
	public $tipopermiso;
	public $periodo;
	public $comentario;

	
}

 ?>
