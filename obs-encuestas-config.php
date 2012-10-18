<?php

require("encuesta.php");
$usr=new users();
$encuestas= new encuesta();
$login=$usr->isOnline();

if(!$login && isset($_POST['usuario']) && isset($_POST['pass']) ){
	$usr->login($_POST['usuario'],$_POST['pass']);
	$login=$usr->isOnline();
}
if(!isset($_GET['id'])){header("Location: obs-encuestas.php");};
$titulo = ($_GET['id']=="new")?"Nueva Encuesta":"Detalle encuesta";

$contenido='<div id="nav-menu" style="list-style:none;"><ul><li class="home"><a href="index.php"><strong>Inicio</strong></a></li><li><a href="obs-encuestas.php">Encuestas</a> ></li><li>'.$titulo.'</li></ul></div><div id="main-menu" class="ui-corner-all"><div class="button-new-top-right"><a href="obs-encuestas.php" style="color:red !important;">[ cancelar ]</a></div><h2 class="h2_title">'.$titulo.'</h2>'.$encuestas->formulario().'</div>';

require("template/template.php");
$theme = new template();
$theme->pHeader("Detalle encuesta | Observatorio Latinoamericano","","");
$theme->pBody($contenido,$login);

?>