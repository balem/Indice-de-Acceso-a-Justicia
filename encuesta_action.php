<?php

require("encuesta.php");
$usr=new users();
$login=$usr->isOnline();

if($login && isset($_GET['del_usr_id']) ){
	$delAction=$usr->delUser($_GET['del_usr_id']);
	if($delAction){
		header("Location: ./obs-usuarios.php");
	}else{
		header("Location: ./");
	}
}

if($login && isset($_GET['del_var_id']) ){
	$variable=new variables();
	$action=$variable->delVar($_GET['del_var_id']);
	if($action){
		header("Location: ./obs-variable.php");
	}else{
		header("Location: ./");
	}
}

if($login && isset($_GET['del_enc_id']) ){
	$encuesta=new encuesta();
	$action=$encuesta->delEnc($_GET['del_enc_id']);
	if($action){
		header("Location: ./obs-encuestas.php");
	}else{
		header("Location: ./");
	}
}

if(isset($_POST['inser_enc_var'])){
	$insert_action=new encuesta();
	print $insert_action->create_enc_var($_POST['id_enc'],$_POST['id_var']);
}

if(isset($_POST['del_enc_var'])){
	$delete_action=new encuesta();
	print $delete_action->delete_enc_var($_POST['id_enc'],$_POST['id_var']);
}

if(isset($_POST['update_enc_var'])){
	$update_lable=new encuesta();
	print $update_lable->update_enc_var_label($_POST['id_enc'],$_POST['id_var'],$_POST['val_var']);
}

if(isset($_POST['habilitacion_voto'])){
	$encuesta=new encuesta();
	print $encuesta->habilitar_votacion($_POST['habilitacion_voto']);
	
}
?>