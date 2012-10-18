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
$style='<style>#main-menu{width:95%!important;min-width:800px!important}</style>';
$contenido='<div id="nav-menu" style="list-style:none;"><ul><li class="home"><a href="index.php"><strong>Inicio</strong></a></li><li>Variables</li></ul></div><div id="main-menu" class="ui-corner-all"><div class="button-new-top-right"><a href="obs-variable-detalle.php?id=new">[ crear nuevo ]</a></div><h2 class="h2_title">Variables</h2>'.$variables->listVars().'</div>';

require("template/template.php");
$theme = new template();
$theme->pHeader("Variables | Observatorio Latinoamericano",$style,"");
$theme->pBody($contenido,$login);

?>