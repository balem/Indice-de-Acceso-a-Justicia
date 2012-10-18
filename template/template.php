<?php

class template{
	
	function pHeader($pHeader="Observatorio",$pStyle="",$pScript=""){
		print '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<link href="template/style.css" type="text/css" rel="stylesheet" />
			<link type="text/css" href="template/jquery-ui/css/redmond/jquery-ui-1.8.21.custom.css" rel="stylesheet" />
			'.$pStyle.'
			<script type="text/javascript" src="template/jquery-ui/js/jquery-1.7.2.min.js"></script>
			<script type="text/javascript" src="template/jquery-ui/js/jquery-ui-1.8.21.custom.min.js"></script>
			'.$pScript.'
			<title>'.$pHeader.'</title>
			</head>';
	}
	
	function pBody($pContent="",$loginForm=false){
		print '
		<body>
			<div id="main">
				<div id="header">
					<div id="logo"><h1>matriz de indicadores </h1></div>
				</div>'.($loginForm?'<div id="user-menu"><strong>'.$_SESSION['usr_name'].'</strong> | <a href="index.php?logout=1">salir</a></div>':'').'
				<div id="content">'.($loginForm?$pContent:($this->loginForm())).'</div>
			</div>
		</body>';
	}
	
	function loginForm(){
		$valor = '
			<div id="loginForm" class="ui-corner-all">
				<h2 class="ui-widget-header ui-corner-all">Ingreso al Sistema</h2>
				<form style="width:65%; margin:5px auto;" action="'.$_SERVER['PHP_SELF'].'" method="post">
					<p>Usuario</p>
					<p><input name="usuario" class="myTxt ui-corner-all"></p>
					<p>Contraseña</p>
					<p><input name="pass" type="password" class="myTxt ui-corner-all"></p>
					<script>$(function(){$("input:submit", "#loginForm" ).button();});</script>
					<p align="center"><input value="Ingresar" type="submit"></p>
				</form>
			</div>
			<div style="text-align: center">
				*Completa con el usuario y la contraseña brindada a tu organización.<br />
				Si no recibiste la misma, o tienes inconvenientes para el ingreso, favor envía un e-mail a 
				<a href="mailto:info@observatoriojusticia.org">info@observatoriojusticia.org</a>
			</div>
		';
		return $valor;
	}
}
?>
