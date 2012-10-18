// JavaScript Document

$(function(){
	$("#save-button").button();
	$("#enc_num").focus();
	$("#enc-form").submit(function(e){
		e.preventDefault
		/*nro=$("#enc_num").val();
		if(nro==""){
			alert("¿Cual es el numero de encuesta?");
			$("#enc_num").focus();
			return false;
		}
		if(isNaN(nro)){
			alert("El numero de encuesta debe ser numerico");
			$("#enc_num").focus();
			return false;
		}*/
		return true;
	});
});