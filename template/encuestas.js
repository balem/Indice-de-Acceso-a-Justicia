// JavaScript Document

$(function(){
	
	$("#save-button").button();
	$("#del-button").button();
	$("#user-list .alias").blur(function(){
		enc_id=$(this).attr('rowenc');
		var_id=$(this).attr('rowid');
		var_val=$(this).val();
		$.post("encuesta_action.php",{update_enc_var:true,id_enc:enc_id,id_var:var_id,val_var:var_val},function(data){
			if(!data){
				alert("Ha ocurrido un error al insertar la variable en la encuesta.\nIntente actualizar la pagina y volver a intentarlo");
			}
		});
	})
	$("#user-list input[type='checkbox']").click(function(){
		enc_id=$(this).attr('rowenc');
		var_id=$(this).attr('rowid');
		estado=$(this).attr('checked');
		nuevoValor=$("#input-puntaje"+var_id).val();
		if(estado=="checked"){
			$.post("encuesta_action.php",{inser_enc_var:true,id_enc:enc_id,id_var:var_id},function(data){
				if(data){
					$("#user-list .alias[rowid='"+var_id+"']").attr('disabled',false);
					$("#user-list .alias[rowid='"+var_id+"']").focus();
					$("#total-puntaje").val(parseInt($("#total-puntaje").val())+parseInt(nuevoValor));
				}else{
					alert("Ha ocurrido un error al insertar la variable en la encuesta.\nIntente actualizar la pagina y volver a intentarlo");
				}
			});
		}else{
			$.post("encuesta_action.php",{del_enc_var:true,id_enc:enc_id,id_var:var_id},function(data){
				if(data){
					$("#user-list .alias[rowid='"+var_id+"']").attr('disabled',true);
					$("#user-list .alias[rowid='"+var_id+"']").val("");
					$("#total-puntaje").val(parseInt($("#total-puntaje").val())-parseInt(nuevoValor));
				}else{
					alert("Ha ocurrido un error al eliminar la variable de la encuesta.\nIntente actualizar la pagina y volver a intentarlo");
				}
			});
		}
	})
	
	$("#enc-form #enc-title").focus();
	
	$("#enc-form").submit(function(e){
		e.preventDefault
		nombre=$("#enc-form #enc-title").val();
		usuarios=$("#enc-form #usr-level").val();
		if(nombre=="" || usuarios==""){
			alert("Debe completar todos los campos del formulario");
			$("#enc-form #enc-title").focus();
			return false;
		}
		return true;
	});
	
	$("#del-button").click(function(){
		if(confirm("Esta seguro de eliminar a esta encuesta?")){
			window.location.href="encuesta_action.php?del_enc_id="+$(this).attr("del-enc-id");
		}
	});
	
	
});