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

$contenido='<div id="nav-menu" style="list-style:none;"><ul><li class="home"><a href="index.php"><strong>Inicio</strong></a></li><li>Usuarios</li></ul></div><div id="main-menu" class="ui-corner-all"><div class="button-new-top-right"><a href="obs-usuario-detalle.php?id=new">[ crear nuevo ]</a></div><h2 class="h2_title">Usuarios</h2>'.$usr->listUsers(true).'</div>';

require("template/template.php");
$theme = new template();
$theme->pHeader("Usuarios | Observatorio Latinoamericano","","");
$theme->pBody($contenido,$login);

?>