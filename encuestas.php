<?php

require("encuesta.php");
$usr=new users();
$login=$usr->isOnline();

if(!$login && isset($_POST['usuario']) && isset($_POST['pass']) ){
	$usr->login($_POST['usuario'],$_POST['pass']);
	$login=$usr->isOnline();
}

$scripts='<script>$(function(){$("#main-menu a").button();});</script>';
$contenido='
	<div id="main-menu" class="ui-corner-all">
		<h2 class="ui-widget-header ui-corner-all" style="text-align:left;">Inicio</h2>
		<ul>
			<li><a href="encuestas.php"><img src="template/img/list.png" border="0"><br>ENCUESTAS</a></li>
			<li><a href="variables.php"><img src="template/img/file.png" border="0"><br>VARIABLES</a></li>
			<li><a href="usuarios.php"><img src="template/img/group.png" border="0"><br>USUARIOS</a></li>
		</ul>
	</div>';


require("template/template.php");
$theme = new template();
$theme->pHeader("Inicio | Observatorio Latinoamericano","",$scripts);
$theme->pBody($contenido,$login);

?>