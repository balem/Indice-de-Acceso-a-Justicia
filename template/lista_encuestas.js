// JavaScript Document

$(function(){

	$(".votar_link").click(function(e){
		e.preventDefault;
		id_enc=$(this).attr("linkId");
		$.post("encuesta_action.php",{habilitacion_voto:id_enc},function(data){
			if(!data){
				alert("Usted ya ha votado en esta encuesta, gracias por su colaboracion");
			}else{
				window.location=("obs-encuestas-carga.php?id="+id_enc);
			}
		});
		//obs-encuestas-carga.php?id
	})

});