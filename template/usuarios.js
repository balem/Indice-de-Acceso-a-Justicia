// JavaScript Document

$(function(){
	
	$("#save-button").button();
	$("#del-button").button();
	$("#usr-form #usr-name").focus();
	
	$("#usr-form").submit(function(e){
		e.preventDefault
		nombre=$("#usr-form #usr-name").val();
		mail=$("#usr-form #usr-mail").val();
		pass=$("#usr-form #usr-pass").val();
		if(nombre=="" || mail=="" || pass==""){
			alert("Debe completar todos los campos del formulario");
			$("#usr-form #usr-name").focus();
			return false;
		}
		return true;
	});
	
	$("#del-button").click(function(){
		if(confirm("Esta seguro de eliminar a este usuario?")){
			myId=$(this).attr("delUsr");
			window.location.href="encuesta_action.php?del_usr_id="+myId;
		}
	});
	
});