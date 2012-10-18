<?php

require("encuesta.php");
$usr=new users();
$encuesta = new encuesta();
$login=$usr->isOnline();

if(!$login && isset($_POST['usuario']) && isset($_POST['pass']) ){
	$usr->login($_POST['usuario'],$_POST['pass']);
	$login=$usr->isOnline();
}

$contenido='<div id="nav-menu" style="list-style:none;"><ul><li class="home"><a href="index.php"><strong>Inicio</strong></a></li><li>Encuestas</li></ul></div><div id="main-menu" class="ui-corner-all"><div class="button-new-top-right">'.(($_SESSION['usr_level']=="administrador")?'<a href="obs-encuestas-detalle.php?id=new">[ crear nuevo ]</a>':'').'</div><h2 class="h2_title">Encuestas</h2>'.$encuesta->listarEncuestas().'</div>';

require("template/template.php");
$theme = new template();
$theme->pHeader("Encuestas | Observatorio Latinoamericano","","<script type='text/javascript' src='template/lista_encuestas.js'></script>");
$theme->pBody($contenido,$login);

?>