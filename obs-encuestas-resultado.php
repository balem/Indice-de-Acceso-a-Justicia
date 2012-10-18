<?php

require("encuesta.php");
$usr=new users();
$encuestas = new encuesta();
$login=$usr->isOnline();

if(!$login && isset($_POST['usuario']) && isset($_POST['pass']) ){
	$usr->login($_POST['usuario'],$_POST['pass']);
	$login=$usr->isOnline();
}
if($login && $_SESSION['usr_level']!="administrador"){
	header("Location: ./obs-encuestas.php");
}
if(!isset($_GET['id'])){header("Location: obs-encuestas.php");};
$titulo = "Resultados de encuesta";

$styles='<style>#main-menu{width:95%!important;min-width:800px!important}.disableInput{width:50px!important;text-align:center;}#usr-detalles label{display:inline-block;width:150px;}#usr-detalles{text-align:left;}#usr-detalles p{padding:20px 20px 0px;padding-left:50px;}#usr-detalles textarea{padding:5px; width:300px;}#usr-detalles input[type="text"]{padding:5px; width:300px;}#usr-detalles select{padding:5px;}#usr-detalles input[disabled="disabled"]{border:none;}</style>';
$script='<script type="text/javascript" src="template/encuestas.js"></script>';

$contenido='<div id="nav-menu" style="list-style:none;"><ul><li class="home"><a href="index.php"><strong>Inicio</strong></a></li><li><a href="obs-encuestas.php">Encuestas</a> ></li><li>'.$titulo.'</li></ul></div><div id="main-menu" class="ui-corner-all"><div class="button-new-top-right"><a href="obs-encuestas.php" style="color:red !important;">[ atras ]</a></div><h2 class="h2_title">Resultados: '.$encuestas->titulo_enc($_GET['id']).'</h2><div id="usr-detalles">'.$encuestas->listResults($_GET['id']).'</div></div>';

require("template/template.php");
$theme = new template();
$theme->pHeader($titulo." | Observatorio Latinoamericano",$styles,$script);
$theme->pBody($contenido,$login);

?>