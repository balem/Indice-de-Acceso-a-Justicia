<?php

require("encuesta.php");
$usr=new users();
$variables = new variables();
$login=$usr->isOnline();

if(!$login && isset($_POST['usuario']) && isset($_POST['pass']) ){
	$usr->login($_POST['usuario'],$_POST['pass']);
	$login=$usr->isOnline();
}
if($login && $_SESSION['usr_level']!="administrador"){
	header("Location: ./obs-encuestas.php");
}
if(!isset($_GET['id'])){header("Location: obs-usuarios.php");};
$titulo = ($_GET['id']=="new")?"Nueva Variable":"Detalle Variable";

if(isset($_POST['submit'])){
	$action_response = false;
	if($_POST['hidden-action']=="add-var"){
		$action_response = $variables->addVar($_POST['var-name'],$_POST['var-contexto'],$_POST['var-factor'],$_POST['var-tipo'],$_POST['var-puntaje'],$_POST['var-normativa']);
	}else if($_POST['hidden-action']=="update-var"){
		$action_response = $variables->updateVar($_GET['id'],$_POST['var-name'],$_POST['var-contexto'],$_POST['var-factor'],$_POST['var-tipo'],$_POST['var-puntaje'],$_POST['var-normativa']);
	}
	if($action_response){header("Location:obs-variable.php");}
}

$styles='<style>#usr-detalles{text-align:left;}#usr-detalles p{padding:20px;padding-left:50px;}#usr-detalles label{display:inline-block;width:150px;}#usr-detalles textarea{padding:5px; width:300px;}#usr-detalles input[type="text"]{padding:5px; width:300px;}#usr-detalles select{padding:5px;}</style>';
$script='<script type="text/javascript" src="template/variables.js"></script>';

$contenido='<div id="nav-menu" style="list-style:none;"><ul><li class="home"><a href="index.php"><strong>Inicio</strong></a></li><li><a href="obs-variable.php">Variables</a> ></li><li>'.$titulo.'</li></ul></div><div id="main-menu" class="ui-corner-all"><div class="button-new-top-right"><a href="obs-variable.php" style="color:red !important;">[ cancelar ]</a></div><h2 class="h2_title">'.$titulo.'</h2><div id="usr-detalles">'.$variables->formulario($_GET['id']).'</div></div>';

require("template/template.php");
$theme = new template();
$theme->pHeader($titulo." | Observatorio Latinoamericano",$styles,$script);
$theme->pBody($contenido,$login);

?>