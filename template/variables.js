// JavaScript Document

$(function(){
	
	$("#save-button").button();
	$("#del-button").button();
	$("#usr-form #usr-name").focus();
	
	$("#var-form").submit(function(e){
		e.preventDefault
		nombre=$("#var-form #var-name").val();
		puntaje=$("#var-form #var-puntaje").val();
		if(nombre=="" || puntaje==""){
			alert("Debe completar todos los campos del formulario");
			$("#var-form #var-name").focus();
			return false;
		}
		if(isNaN(puntaje)){
			alert("El valor del campo puntaje debe ser numerico");
			$("#var-form #var-puntaje").focus();
			return false;
		}
		return true;
	});
	
	$("#del-button").click(function(){
		if(confirm("Esta seguro de eliminar a esta variable?")){
			myId=$(this).attr("delvar");
			window.location.href="encuesta_action.php?del_var_id="+myId;
		}
	});
	
});