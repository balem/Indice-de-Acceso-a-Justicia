<?php
session_start();



class encuesta {
	function listarEncuestas(){
		$conection = new conector;
		$link = $conection->conectar();
		$isAdmin= ($_SESSION['usr_level']=="administrador")?true:false;
		$sqlXtra = (!$isAdmin)?' where encuestas.enc_tipo_usuario="'.$_SESSION['usr_level'].'" ':'';
		$sql="select * from encuestas inner join usuarios on usuarios.usr_id=encuestas.enc_usuario $sqlXtra order by enc_id desc;";
		$consulta = mysql_query($sql,$link);
		$totalResultado = mysql_num_rows($consulta);
		$respuesta="<table id='user-list' cellspacing='0'><tr><th>T&iacute;tulo de encuesta</th><th style='width:10%'>creado por</th><th style='width:10%'>fecha de creacion</th><th style='width:2%;text-align:center;'>Acciones</th></tr>";
		while($row = mysql_fetch_array($consulta)){
			$respuesta.="<tr><td><a href='#' linkId='$row[enc_id]' class='votar_link'>$row[enc_titulo]</a></td><td>$row[usr_name]</td><td>$row[enc_fecha]</td><td align='center'>".( ($isAdmin)?"<a href='obs-encuestas-detalle.php?id=$row[enc_id]'>[configurar]</a><a href='obs-encuestas-resultado.php?id=$row[enc_id]'>[resultados]</a>":"")." <a href='#' linkId='$row[enc_id]' class='votar_link'>[cargar]</a></td></tr>";
		}
		$respuesta.="</ul>";
		return $respuesta;
	}
	function formulario($id){
		$respuesta = "";
		$conection = new conector;
		$link = $conection->conectar();
		$consulta = ($id!="new")?(mysql_query("select * from encuestas where enc_id=$id limit 1;",$link)):"";
		$datos = ($consulta!='')?(mysql_fetch_assoc($consulta)):'';
		$respuesta .= '
			<form action="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'" method="post" id="enc-form">'.($consulta==''?'<input type="hidden" name="hidden-action" value="add-enc" />':'<input type="hidden" name="hidden-action" value="update-enc" />').'
				<p><label>Titulo:</label><input type="text" name="enc-title" id="enc-title" value="'.($consulta!=''?$datos['enc_titulo']:'').'"></p>
				<p><label>usuarios habilitados:</label><select name="usr-level" id="usr-level"><option value="usuario" '.(($consulta!=''&&$datos['enc_tipo_usuario']=="usuario")?' selected="selected"':'').'>usuario</option><option value="organizacion" '.(($consulta!=''&&$datos['enc_tipo_usuario']=="organizacion")?' selected="selected"':'').'>organizacion</option><option value="administrador" '.(($consulta!=''&&$datos['enc_tipo_usuario']=="administrador")?' selected="selected"':'').'>administrador</option></select></p>
				'.(($consulta!='')?$this->enc_variables($id):"").'
				<p align="center">'.($id!="new"?'<input type="button" value="eliminar encuesta" id="del-button" del-enc-id="'.$datos['enc_id'].'" style="margin-right:30px;color:red!important;border-color:red;">':'').'<input type="submit" name="submit" value="guardar" id="save-button"></p>
			</form>';
		return $respuesta;
	}
	function addEnc($name,$levelAcces,$creator){
		$conection = new conector;
		$link = $conection->conectar();
		$fechaHoy=date("Y-m-d");
		$sql = "insert into encuestas (enc_id,enc_titulo,enc_usuario,enc_fecha,enc_tipo_usuario) values('','$name',$creator,'$fechaHoy','$levelAcces');";
		$consulta = mysql_query($sql,$link);
		if($consulta){return true;}
		return false;
	}
	function delEnc($id){
		$conection = new conector;
		$link = $conection->conectar();
		$sql="DELETE FROM encuestas WHERE enc_id = $id";
		$consulta = mysql_query($sql,$link);
		if($consulta){
			$consulta2=mysql_query("DELETE FROM encuestas_carga WHERE carga_enc_id = $id",$link);
			$consulta3=mysql_query("DELETE FROM  encuestas_resultado WHERE enc_res_enc_id = $id",$link);
			return true;
		}
		return false;
	}
	function updateEnc($id,$name,$levelAcces,$creator){
		$conection = new conector;
		$link = $conection->conectar();
		$fechaHoy=date("Y-m-d");
		$sql = "UPDATE encuestas SET enc_titulo = '$name', enc_usuario = $creator, enc_fecha = '$fechaHoy', enc_tipo_usuario = '$levelAcces' WHERE enc_id= $id;";
		$consulta = mysql_query($sql,$link);
		if($consulta){return true;}
		return false;
	}
	function enc_variables($enc_id){
		$respuesta ='<h2 class="h2_title" style="margin-top:50px">Variables de la encuesta</h2>';
		$conection = new conector;
		$link = $conection->conectar();
		$consulta = mysql_query("SELECT * FROM variable ORDER BY variable.var_id DESC;",$link);
		$respuesta.="<table id='user-list' cellspacing='0'><tr><th style='width:5%'>&nbsp;</th><th style='width:55%'>Nombre</th><th>Alias</th><th style='width:5%'>Puntaje</th></tr>";
		$suma=0;
		while($row = mysql_fetch_array($consulta)) {
			$consulta2=mysql_query("select * from encuestas_carga where carga_enc_id=$enc_id and carga_var_id=$row[var_id] limit 1",$link);
			$resultado=mysql_fetch_assoc($consulta2);
			$activado=(empty($resultado['carga_id']))?false:true;
			$suma = ($activado?($suma+$row['var_puntaje']):$suma);
			$respuesta .="<tr><td align='center'><input type='checkbox' rowid='$row[var_id]' rowenc='$enc_id' ".($activado?'checked="checked"':'')."></td><td>$row[var_nombre]</td><td><input type='text' value='".($activado?$resultado['carga_alias']:'')."' class='alias' ".($activado?"":"disabled='disabled'")." rowid='$row[var_id]' rowenc='$enc_id' /></td><td><input type='text' id='input-puntaje$row[var_id]' disabled='disabled' value='$row[var_puntaje]' class='disableInput'/></td></tr>";
		}
		$respuesta.="<tr><td colspan='3'><h2 class='h2_title' style='text-align:right;margin:0px!important;'>Puntaje total de la encuesta:</h2></td><td><input id='total-puntaje' type='text' disabled='disabled' value='$suma' class='disableInput'/></td></tr></table>";
		return $respuesta;
	}
	function create_enc_var($enc_id,$var_id){
		$conection = new conector;
		$link = $conection->conectar();
		return mysql_query("insert into encuestas_carga(carga_id,carga_enc_id,carga_var_id,carga_alias)VALUES('','$enc_id','$var_id','');",$link);
	}
	function delete_enc_var($enc_id,$var_id){
		$conection = new conector;
		$link = $conection->conectar();
		$consulta = mysql_query("DELETE FROM encuestas_carga WHERE carga_enc_id = $enc_id and carga_var_id=$var_id;",$link);
		if($consulta){
			mysql_query("DELETE FROM encuestas_resultado WHERE enc_res_enc_id = $enc_id and enc_res_enc_val=$var_id;",$link);
			return true;
		}
		return false;
	}
	function update_enc_var_label($enc_id,$var_id,$new_label){
		$conection = new conector;
		$link = $conection->conectar();
		return mysql_query("update encuestas_carga set carga_alias='$new_label' where carga_enc_id = $enc_id and carga_var_id=$var_id;",$link);
	}
	function carga_enc_form($enc_id){
		$nro_enc=1;
		$conection = new conector;
		$link = $conection->conectar();
		$prev_sql="select max(enc_res_nro_enc) as max from encuestas_resultado;";
		$prev_consulta=mysql_query($prev_sql,$link);
		$resultado_consulta=mysql_fetch_assoc($prev_consulta);
		if($resultado_consulta['max']!=NULL){
			$nro_enc=$nro_enc+$resultado_consulta['max'];
		}
		
		$sql="select * from encuestas inner join encuestas_carga on encuestas_carga.carga_enc_id=encuestas.enc_id where encuestas.enc_id=$enc_id order by carga_var_id desc;";
		
		$consulta=mysql_query($sql,$link);
		$num_variables=mysql_num_rows($consulta);
		$respuesta="<form action='$_SERVER[PHP_SELF]?id=$_GET[id]' method='post' id='enc-form'><input type='hidden' name='enc_num' value='$nro_enc' /><input type='hidden' name='enc_id' value='$_GET[id]' /><table id='user-list' cellspacing='0'><tr><th style='width:60%'>
En cada una de las preguntas siguientes haga clic en la opción con la información que corresponda a su país. Si lo desea podrá dejar un comentario en cada pregunta.

</th><th>&nbsp;</th></tr>";
		if($num_variables>0){
			while($row=mysql_fetch_array($consulta)){
				$respuesta.='<tr><td>'.$row['carga_alias'].'</td><td><label class="input_radio"><input type="radio" name="'.$row['carga_var_id'].'" value="1">si</label><label class="input_radio"><input type="radio" name="'.$row['carga_var_id'].'" value="0">no</label><label class="input_radio"><input type="radio" name="'.$row['carga_var_id'].'" value="99">NS/NR</label> <input type="text" name="'.$row['carga_var_id'].'_coment" class="text_input_carga"></td></tr>';
			}
			$respuesta.='</table><p align="center"><input type="submit" name="submit" value="guardar" id="save-button"></p></form>';
		}else{
			$respuesta="<h1>No existe ninguna variable para esta encuesta</h1><p align='center'><a href='obs-encuestas.php'>volver al inicio</a></p>";
		}
		return $respuesta;
	}
	function listResults($enc_id){
		$conection = new conector;
		$link = $conection->conectar();
		$sql="select encuestas_resultado.enc_res_enc_val, encuestas_carga.carga_alias from encuestas_resultado inner join encuestas_carga on encuestas_carga.carga_var_id=encuestas_resultado.enc_res_enc_val where encuestas_carga.carga_enc_id=$enc_id group by encuestas_resultado.enc_res_enc_val order by encuestas_resultado.enc_res_enc_val desc;";
		$consulta=mysql_query($sql,$link);
		$num_variables=mysql_num_rows($consulta);
		$respuesta = '<table id="user-list" cellspacing="0">';
		if($num_variables>0){
			$anchoCol=100/($num_variables+1);
			$respuesta.="<tr><th style='width:$anchoCol%;'>Nro enc.</th>";
			while($row=mysql_fetch_array($consulta)){
				$respuesta.="<th style='width:$anchoCol%;'>$row[carga_alias]</th>";
			}
			$respuesta.='</tr>';
			$sql2="select * from encuestas_resultado where enc_res_enc_id=$enc_id order by enc_res_nro_enc asc";
			$consulta2=mysql_query($sql2,$link);
			$nroEnc="";
			while($row=mysql_fetch_array($consulta2)){
				if($nroEnc!=$row['enc_res_nro_enc']){
					$respuesta.=($nroEnc=="")?'':'</tr>';
					$nroEnc=$row['enc_res_nro_enc'];
					$respuesta.="<tr><td>$row[enc_res_nro_enc]</td>";
				}
				$myValor=($row['enc_res_valor']==1)?'si':'no';
				$myValor=($row['enc_res_valor']==99)?'ns/nr':$myValor;
				$myComents=($row['enc_res_comentario']!="")?"comentario: $row[enc_res_comentario]":"";
				$respuesta.="<td>$myValor<span class='results_coments_row'>$myComents</span></td>";
			}
			$respuesta.='</tr></table>';
		}else{
			$respuesta="<h1>No existe ninguna variable para esta encuesta</h1><p align='center'><a href='obs-encuestas.php'>volver al inicio</a></p>";
		}
		return $respuesta;
	}
	function carga_enc($datos){
		$conection = new conector;
		$link = $conection->conectar();
		$nro_enc=$datos['enc_num'];
		$id_enc=$datos['enc_id'];
		foreach($datos as $key=>$value){
			if(is_numeric($key)){
				$comentarios=$datos[$key."_coment"];
				$sql = "insert into encuestas_resultado(enc_res_id,enc_res_nro_enc,enc_res_enc_id,enc_res_enc_val,enc_res_valor,enc_res_comentario,enc_res_usr_votante) values(NULL, $nro_enc, $id_enc, $key, '$value','$comentarios',$_SESSION[usr_id]);";
				mysql_query($sql,$link);
			}
		}
		return true;
	}
	function titulo_enc($enc_id){
		$conection = new conector;
		$link = $conection->conectar();
		$sql="select enc_titulo from encuestas where enc_id=$enc_id limit 1;";
		$consulta=mysql_query($sql,$link);
		$valores=mysql_fetch_assoc($consulta);
		return $valores['enc_titulo'];
	}
	function habilitar_votacion($enc_id){
		$conection = new conector;
		$link = $conection->conectar();
		$sql="select * from encuestas_resultado where enc_res_enc_id=$enc_id and enc_res_usr_votante=$_SESSION[usr_id] group by enc_res_enc_id";
		$consulta=mysql_query($sql,$link);
		$resultado_consulta=mysql_fetch_assoc($consulta);
		if($resultado_consulta['enc_res_enc_id']==NULL){
			return true;
		}
		return false;
	}
}

//array(6) { ["enc_num"]=> string(1) "2" [31]=> string(2) "on" [26]=> string(2) "on" [24]=> string(2) "on" [22]=> string(2) "on" ["submit"]=> string(7) "guardar" } 



/*************************************

***********> VARIABLES <**************

*************************************/




class variables{
	function listVars(){
		$respuesta ="";
		$conection = new conector;
		$link = $conection->conectar();
		$consulta = mysql_query("SELECT * FROM variable inner join contexto on contexto.cont_id=variable.var_contexto left join factor on factor.fac_id=variable.var_factor left join tipo on tipo.tipo_id=variable.var_tipo ORDER BY variable.var_id DESC;",$link);
		$respuesta.="<table id='user-list' cellspacing='0'><tr><th>Nombre</th><th>Normativa</th><th>Contexto</th><th>Factor</th><th>Tipo</th><th>Puntaje</th></tr>";
		while($row = mysql_fetch_array($consulta)) {
			$respuesta .="<tr><td><a href='obs-variable-detalle.php?id=".$row['var_id']."'>$row[var_nombre]</a></td><td>$row[var_normativa]</td><td>$row[cont_descripcion]</td><td>$row[fac_nombre]</td><td>$row[tipo_nombre]</td><td>$row[var_puntaje]</td></tr>";
		}
		$respuesta.="</table>";
		return $respuesta;
	}
	function formulario($id){
		$respuesta = "";
		$conection = new conector;
		$link = $conection->conectar();
		//variables
		$consulta = ($id!="new")?(mysql_query("SELECT * FROM variable where var_id=$id limit 1;",$link)):"";
		$datos = ($consulta!='')?(mysql_fetch_assoc($consulta)):'';
		//contexto
		$consulta2=mysql_query("select * from contexto",$link);
		$selectContexto="<select name='var-contexto' id='var-contexto'>";
		while($rowCont = mysql_fetch_array($consulta2)){
			$selectContexto.="<option value='$rowCont[cont_id]' ".(($consulta!=''&&$datos['var_contexto']==$rowCont['cont_id'])?'selected="selected"':"").">$rowCont[cont_descripcion]</option>";
		}
		$selectContexto.="</select>";
		//factor
		$consulta3=mysql_query("select * from factor",$link);
		$selectFactor="<select name='var-factor' id='var-factor'>";
		while($rowFact= mysql_fetch_array($consulta3)){
			$selectFactor.="<option value='$rowFact[fac_id]' ".(($consulta!=''&&$datos['var_factor']==$rowFact['fac_id'])?'selected="selected"':"").">$rowFact[fac_nombre]</option>";
		}
		$selectFactor.="</select>";
		//tipo
		$consulta4=mysql_query("select * from tipo",$link);
		$selectTipo="<select name='var-tipo' id='var-tipo'>";
		while($rowTipo = mysql_fetch_array($consulta4)){
			$selectTipo.="<option value='$rowTipo[tipo_id]' ".(($consulta!=''&&$datos['var_tipo']==$rowTipo['tipo_id'])?'selected="selected"':"").">$rowTipo[tipo_nombre]</option>";
		}
		$selectTipo.="</select>";
		
		//respuesta
		$respuesta .= '
			<form action="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'" method="post" id="var-form">'.($consulta==''?'<input type="hidden" name="hidden-action" value="add-var" />':'<input type="hidden" name="hidden-action" value="update-var" />').'
				<p><label>Nombre de variable:</label><textarea name="var-name" id="var-name">'.($consulta!=''?$datos['var_nombre']:'').'</textarea></p>
				<p><label>Normativa de variable:</label><input type="text" name="var-normativa" id="var-normativa" value="'.($consulta!=''?$datos['var_normativa']:'').'"></p>
				<p><label>Puntaje de variable:</label><input type="text" name="var-puntaje" id="var-puntaje" value="'.($consulta!=''?$datos['var_puntaje']:'').'"></p>
				<p><label>Contexto:</label>'.$selectContexto.'</p>
				<p><label>Factor:</label>'.$selectFactor.'</p>
				<p><label>Tipo:</label>'.$selectTipo.'</p>
				<p align="center">'.($id!="new"?'<input type="button" value="eliminar variable" id="del-button" delvar="'.$datos['var_id'].'" style="margin-right:30px;color:red!important;border-color:red;">':'').'<input type="submit" name="submit" value="guardar" id="save-button"></p>
			</form>';
		return $respuesta;
	}
	function updateVar($id,$name,$contexto,$factor,$tipo,$puntaje,$normativa){
		$conection = new conector;
		$link = $conection->conectar();
		$sql = "UPDATE variable SET var_nombre='".addslashes($name)."',var_contexto=$contexto,var_factor=$factor,var_tipo=$tipo,var_puntaje=$puntaje,var_normativa= '".addslashes($normativa)."' WHERE var_id = $id;";
		$consulta = mysql_query($sql,$link);
		if($consulta){return true;}
		return false;
	}
	function addVar($name,$contexto,$factor,$tipo,$puntaje,$normativa){
		$conection = new conector;
		$link = $conection->conectar();
		$sql = "insert into variable (var_id,var_nombre,var_contexto,var_factor,var_tipo,var_puntaje,var_normativa) values('','".addslashes($name)."',$contexto,$factor,$tipo,$puntaje,'".addslashes($normativa)."');";
		$consulta = mysql_query($sql,$link);
		if($consulta){return true;}
		return false;
	}
	function delVar($id){
		$conection = new conector;
		$link = $conection->conectar();
		$consulta = mysql_query("DELETE FROM variable WHERE var_id = $id",$link);
		if($consulta){return true;}
		return false;
	}
}







/*************************************

***********> USUARIOS <***************

*************************************/

class users {
	function login( $user="", $pass=""){
		if( $user != "" && $pass != "" ){
			$conection = new conector;
			$link = $conection->conectar();
			$consulta = mysql_query("SELECT * FROM `usuarios` WHERE `usr_name` = '$user' AND `usr_passwrd` = '".$pass."' LIMIT 0 , 1",$link);
			$usr_data= mysql_fetch_assoc($consulta);
			if(!empty($usr_data['usr_id'])){
				$_SESSION['usr_id'] = $usr_data['usr_id'];
				$_SESSION['usr_name'] = $usr_data['usr_name'];
				$_SESSION['usr_level'] = $usr_data['usr_level_acces'];
				return true;
			}return false;
		}return false;
	}
	function isOnline(){
		return (isset($_SESSION['usr_id'])?true:false);
	}
	function listUsers(){
		$respuesta ="";
		$conection = new conector;
		$link = $conection->conectar();
		$consulta = mysql_query("SELECT * FROM `usuarios`;",$link);
		$respuesta.="<table id='user-list' cellspacing='0'><tr><th>Nombre</th><th>Correo</th><th>Perfil</th></tr>";
		while($row = mysql_fetch_array($consulta)) {
			$respuesta .="<tr><td><a href='obs-usuario-detalle.php?id=$row[usr_id]'>$row[usr_name]</a></td><td>$row[usr_mail]</td><td>$row[usr_level_acces]</td></tr>";
		}
		return $respuesta;
	}
	function formulario($id){
		$respuesta = "";
		$conection = new conector;
		$link = $conection->conectar();
		$consulta = ($id!="new")?(mysql_query("SELECT * FROM `usuarios` where usr_id=$id limit 1;",$link)):"";
		$datos = ($consulta!='')?(mysql_fetch_assoc($consulta)):'';
		$respuesta .= '
			<form action="'.$_SERVER['PHP_SELF'].'?id='.$_GET['id'].'" method="post" id="usr-form">'.($consulta==''?'<input type="hidden" name="hidden-action" value="add-user" />':'<input type="hidden" name="hidden-action" value="update-user" />').'
				<p><label>Nombre de usuario:</label><input type="text" name="usr-name" id="usr-name" value="'.($consulta!=''?$datos['usr_name']:'').'"></p>
				<p><label>Correo electronico:</label><input type="text" name="usr-mail" id="usr-mail" value="'.($consulta!=''?$datos['usr_mail']:'').'"></p>
				<p><label>Contrase&ntilde;a:</label><input type="password" name="usr-pass" id="usr-pass" value=""></p>
				<p><label>Perfil:</label><select name="usr-level" id="usr-level"><option value="usuario" '.(($consulta!=''&&$datos['usr_level_acces']=="usuario")?' selected="selected"':'').'>usuario</option><option value="organizacion" '.(($consulta!=''&&$datos['usr_level_acces']=="organizacion")?' selected="selected"':'').'>organizacion</option><option value="administrador" '.(($consulta!=''&&$datos['usr_level_acces']=="administrador")?' selected="selected"':'').'>administrador</option></select></p>
				<p align="center">'.($id!="new"?'<input type="button" value="eliminar usuario" id="del-button" delUsr="'.$datos[usr_id].'" style="margin-right:30px;color:red!important;border-color:red;">':'').'<input type="submit" name="submit" value="guardar" id="save-button"></p>
			</form>';
		return $respuesta;
	}
	function updateUser($id,$name,$mail,$pass,$level){
		$conection = new conector;
		$link = $conection->conectar();
		$sql = "UPDATE usuarios SET usr_name = '$name', usr_passwrd = '$pass', usr_mail = '$mail', usr_level_acces = '$level' WHERE usr_id = $id;";
		$consulta = mysql_query($sql,$link);
		if($consulta){return true;}
		return false;
	}
	function delUser($id){
		$conection = new conector;
		$link = $conection->conectar();
		$consulta = mysql_query("DELETE FROM usuarios WHERE usr_id = $id",$link);
		if($consulta){return true;}
		return false;
	}
	function addUser($name,$mail,$pass,$level){
		$conection = new conector;
		$link = $conection->conectar();
		$fechaHoy=date("Y-m-d");
		$sql = "insert into usuarios (usr_id,usr_name,usr_passwrd,usr_mail,usr_fecha,usr_level_acces) values('','$name','$pass','$mail','$fechaHoy','$level');";
		$consulta = mysql_query($sql,$link);
		if($consulta){return true;}
		return false;
	}
}







/*************************************

***********> CONECTION <**************

*************************************/






class conector{
	function conectar( $host="localhost", $usr="USUSARIO", $pswd="CONTRASEÑA", $dbname = "indicetrans" ){
		if (!($link=mysql_connect($host,$usr,$pswd))){echo "Error conectando a la base de datos.";exit();}
		if (!mysql_select_db($dbname,$link)){echo "Error seleccionando la base de datos.";exit();}
		return $link;
	}
}

?>
