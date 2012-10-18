<?php

require("encuesta.php");
$usr=new users();
$login=$usr->isOnline();

if(!$login && isset($_POST['usuario']) && isset($_POST['pass']) ){
	$usr->login($_POST['usuario'],$_POST['pass']);
	$login=$usr->isOnline();
}
if($login && isset($_GET['logout'])){
	session_destroy();
	header("Location: ./");
}
if($login && $_SESSION['usr_level']!="administrador"){
	header("Location: ./obs-encuestas.php");
}

$scripts='<script>$(function(){$("#main-menu a").button();});</script>';
$contenido='
	<div id="main-menu" class="ui-corner-all">
		<h2 class="h2_title">Inicio</h2>
		<ul id="index_list">
			<li><a href="obs-encuestas.php"><img src="template/img/list.png" border="0"><br>ENCUESTAS</a></li>
			<li><a href="obs-variable.php"><img src="template/img/file.png" border="0"><br>VARIABLES</a></li>
			<li><a href="obs-usuarios.php"><img src="template/img/group.png" border="0"><br>USUARIOS</a></li>
		</ul>
	</div>';


require("template/template.php");
$theme = new template();
$theme->pHeader("Inicio | Observatorio Latinoamericano","",$scripts);
$theme->pBody($contenido,$login);

?>