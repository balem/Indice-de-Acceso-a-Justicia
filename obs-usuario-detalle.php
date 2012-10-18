<?php

require("encuesta.php");
$usr=new users();
$login=$usr->isOnline();

if(!$login && isset($_POST['usuario']) && isset($_POST['pass']) ){
	$usr->login($_POST['usuario'],$_POST['pass']);
	$login=$usr->isOnline();
}
if($login && $_SESSION['usr_level']!="administrador"){
	header("Location: ./obs-encuestas.php");
}
if(!isset($_GET['id'])){header("Location: obs-usuarios.php");};
$titulo = ($_GET['id']=="new")?"Nuevo usuario":"Detalle usuario";

if(isset($_POST['submit'])){
	$action_response = false;
	if($_POST['hidden-action']=="add-user"){
		$action_response = $usr->addUser($_POST['usr-name'],$_POST['usr-mail'],$_POST['usr-pass'],$_POST['usr-level']);
	}else if($_POST['hidden-action']=="update-user"){
		$action_response = $usr->updateUser($_GET['id'],$_POST['usr-name'],$_POST['usr-mail'],$_POST['usr-pass'],$_POST['usr-level']);
	}
	if($action_response){header("Location:obs-usuarios.php");}
}

$styles='<style>#usr-detalles{text-align:left;}#usr-detalles p{padding:20px;padding-left:50px;}#usr-detalles label{display:inline-block;width:150px;}#usr-detalles input[type=text],#usr-detalles input[type=password]{padding:5px; width:300px;}#usr-detalles select{padding:5px;}</style>';
$script='<script type="text/javascript" src="template/usuarios.js"></script>';

$contenido='<div id="nav-menu" style="list-style:none;"><ul><li class="home"><a href="index.php"><strong>Inicio</strong></a></li><li><a href="obs-usuarios.php">Usuarios</a> ></li><li>'.$titulo.'</li></ul></div><div id="main-menu" class="ui-corner-all"><div class="button-new-top-right"><a href="obs-usuarios.php" style="color:red !important;">[ cancelar ]</a></div><h2 class="h2_title">'.$titulo.'</h2><div id="usr-detalles">'.$usr->formulario($_GET['id']).'</div></div>';

require("template/template.php");
$theme = new template();
$theme->pHeader($titulo." | Observatorio Latinoamericano",$styles,$script);
$theme->pBody($contenido,$login);

?>