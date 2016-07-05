<script src='admin/js/consulta_org.js'></script><script src='admin/js/ajax.js'></script>
<script>
function graficar(id_dato,checked){

	ubicacion = 0;  // Para toda Colombia
	depto = 2;
	id_datos_off = "";

	if (checked == true)	checked = 1;
	else if (checked == false)	checked = 0;

	combo_por = document.getElementById('graficar_por');
	graficar_por = combo_por.options[combo_por.selectedIndex].value;

	id_depto = document.getElementById('id_depto').value;
	id_muns = document.getElementById('id_muns').value;

	cnrr = 2;
	if(document.getElementById('cnrr_si').checked == true)	cnrr = 1;
	if(document.getElementById('cnrr_no').checked == true)	cnrr = 0;

	consulta_social = 2;
	if(document.getElementById('consulta_social_si').checked == true)	consulta_social = 1;
	if(document.getElementById('consulta_social_no').checked == true)	consulta_social = 0;

	if (id_depto != ''){
		ubicacion = id_depto;
		depto = 1;
	}
	if (id_muns != ''){
		ubicacion = id_muns;
		depto = 0;
	}

	sede = 1;
	if (document.getElementById('sede')){
		if (document.getElementById('sede').checked == false)	sede = 0;
	}

	cobertura = 1;
	if (document.getElementById('cobertura')){
		if (document.getElementById('cobertura').checked == false)	cobertura = 0;
	}

	if (sede == 0 && cobertura == 0){
		alert('Se debe graficar Sede o Cobertura!');
		return false;
	}

	if (id_depto == '' && id_muns == ''){
		alert('Debe seleccionar la Ubicaci�n Geogr�fica');
		return false;
	}
	else{
		getDataV1('graficaConteoOrg','admin/ajax_data.php?object=graficaConteoOrg&graficar_por='+graficar_por+'&depto='+depto+'&ubicacion='+ubicacion+'&id_dato='+id_dato+'&checked='+checked+'&sede='+sede+'&cobertura='+cobertura+'&cnrr='+cnrr+'&consulta_social='+consulta_social,'graficaConteoOrg');
	}
}
function graficarDeptoMpio(id_ubicacion,checked){

	depto = 0;
	ubicacion = 0;
	id_datos_off = "";

	if (checked == true)	checked = 1;
	else if (checked == false)	checked = 0;

	combo_por = document.getElementById('graficar_por');
	graficar_por = combo_por.options[combo_por.selectedIndex].value;

	if (graficar_por == ''){
		alert('Seleccione el filtro');
		return false;
	}

	combo_filtro = document.getElementById('filtro_graficar_por');
	filtro_graficar_por = combo_filtro.options[combo_filtro.selectedIndex].value;

	id_depto = document.getElementById('id_depto').value;
	id_muns = document.getElementById('id_muns').value;

	cnrr = 2;
	if(document.getElementById('cnrr_si').checked == true)	cnrr = 1;
	if(document.getElementById('cnrr_no').checked == true)	cnrr = 0;

	consulta_social = 2;
	if(document.getElementById('consulta_social_si').checked == true)	consulta_social = 1;
	if(document.getElementById('consulta_social_no').checked == true)	consulta_social = 0;

	if (id_depto != ''){
		ubicacion = id_depto;
		depto = 1;
	}

	sede = 1;
	if (document.getElementById('sede')){
		if (document.getElementById('sede').checked == false)	sede = 0;
	}

	cobertura = 1;
	if (document.getElementById('cobertura')){
		if (document.getElementById('cobertura').checked == false)	cobertura = 0;
	}

	if (sede == 0 && cobertura == 0){
		alert('Se debe graficar Sede o Cobertura!');
		return false;
	}

	if (id_muns != ''){
		alert('La gr�fica es a nivel Nacional o Departamental');
		return false;
	}
	if (filtro_graficar_por == ''){
		alert('Seleccione '+graficar_por);
		return false;
	}
	else{
		getDataV1('graficaConteoOrgDeptopMpio','admin/ajax_data.php?object=graficaConteoOrgDeptoMpio&graficar_por='+graficar_por+'&filtro_graficar_por='+filtro_graficar_por+'&depto='+depto+'&ubicacion='+ubicacion+'&id_ubicacion='+id_ubicacion+'&checked='+checked+'&sede='+sede+'&cobertura='+cobertura+'&cnrr='+cnrr+'&consulta_social='+consulta_social,'graficaConteoOrg');
	}
}
</script>
<form>
<?
//LIBRERIAS
include_once("consulta/lib/libs_org.php");

//INICIALIZACION DE VARIABLES
$org_dao = New OrganizacionDAO();
$tipo_org = New TipoOrganizacionDAO();
$sector = New SectorDAO();
$enfoque = New EnfoqueDAO();
$poblacion = New PoblacionDAO();
$_SESSION["id_datos_off"] = array();

switch ($method){
	case 'graficaConteo':
		?>
		<div id="mapa" class="div_mapa">
			<table id="table_mapa" cellspacing='0' cellpadding='5' class='div_mapa'>
				<tr class='bcg_0000000_FFFFFF'>
					<td><font class='texto_FFCC00_12'>Filtro: Ubicaci�n Geogr�fica</font></td>
					<td align='right'><a href='#' onclick="showDiv('mapa','ocultar'); return false;">X</a></td></tr>
				</tr>
				<tr>
					<td>
						<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="700" height="570" id="gral" align="middle">
						<param name="allowScriptAccess" value="sameDomain" />
						<param name="movie" value="consulta/swf/mapa_i.swf" />
						<param name="quality" value="high" />
						<param name="bgcolor" value="#ffffff" />
						<embed src="consulta/swf/mapa_i.swf" width="700" height="570" align="middle" quality="high" bgcolor="#ffffff" name="gral" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
						</object>
					</td>

				</tr>
			</table>
		</div>
		<table cellspacing="1" cellpadding="5" class="tabla_consulta" width="940" align="center">
			<tr><td align='center' class='titulo_lista'>GRAFICA DE ORGANIZACIONES POR TIPO, POBLACION, ENFOQUE Y SECTOR</td></tr>
			<?
			if ($_SESSION['cnrr'] == 0){ ?>
				<tr><td><img src='images/back.gif' border=0>&nbsp;<a href='javascript:history.back(-1)'>Regresar</a></td>
			<? } ?>
			<tr>
				<td>
					<b>Graficar por</b>&nbsp;
					<select id='graficar_por' name="graficar_por" class="select">
						<option value="tipo">Tipo</option>
						<option value="sector">Sector</option>
						<option value="enfoque">Enfoque</option>
						<option value="poblacion">Poblaci�n</option>
					</select>&nbsp;
					<img src="images/flecha.gif">&nbsp;<a href="javascript:showDiv('mapa','mostrar');"><b>Filtrar por Ubicaci�n Geogr�fica</b></a>
					<span id="texto_ubicacion">Cargando Flash....</span>
				</td>
			</tr>
			<tr>
				<td>
					<?
					if ($_SESSION['cnrr'] == 1)	echo "<span style='display:none'>";
					else echo "<span>";
					?>
					<img src="images/flecha.gif">&nbsp;&nbsp;<b>CNRR</b>&nbsp;<input type="radio" id="cnrr_no" name="cnrr" value=0 >No&nbsp;<input type="radio" id="cnrr_si" name="cnrr" value=1 >Si&nbsp;&nbsp;</span>
					<img src="images/flecha.gif">&nbsp;&nbsp;<b>Consulta Social</b>&nbsp;<input type="radio" id="consulta_social_no" name="consulta_social" value=0>No&nbsp;<input type="radio" id="consulta_social_si" name="consulta_social" value=1>Si
					&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='Graficar' class="boton" onclick="graficar(0,'')">
			</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td id='graficaConteoOrg'></td></tr>
		</table>
		<input type="hidden"id="id_depto" name="id_depto" value=''>
		<input type="hidden" id="id_muns" name="id_muns" value=''>
		<?php
	break;
	case 'graficaConteoDeptoMpio':
		?>
		<div id="mapa" class="div_mapa">
			<table id="table_mapa" cellspacing='0' cellpadding='5' class='div_mapa'>
				<tr class='bcg_0000000_FFFFFF'>
					<td><font class='texto_FFCC00_12'>Filtro: Ubicaci�n Geogr�fica</font></td>
					<td align='right'><a href='#' onclick="showDiv('mapa','ocultar'); return false;">X</a></td></tr>
				</tr>
				<tr>
					<td>
						<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="700" height="570" id="gral" align="middle">
						<param name="allowScriptAccess" value="sameDomain" />
						<param name="movie" value="consulta/swf/mapa_i.swf" />
						<param name="quality" value="high" />
						<param name="bgcolor" value="#ffffff" />
						<embed src="consulta/swf/mapa_i.swf" width="700" height="570" align="middle" quality="high" bgcolor="#ffffff" name="gral" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
						</object>
					</td>

				</tr>
			</table>
		</div>
		<table cellspacing="1" cellpadding="5" class="tabla_consulta" width="940" align="center">
			<tr><td align='center' class='titulo_lista'>GRAFICA DE ORGANIZACIONES POR DEPARTAMENTO O MUNICPIO</td></tr>
			<?
			if ($_SESSION['cnrr'] == 0){ ?>
				<tr><td><img src='images/back.gif' border=0>&nbsp;<a href='javascript:history.back(-1)'>Regresar</a></td>
			<? } ?>
			<tr>
				<td>
					<img src="images/flecha.gif">&nbsp;<a href="javascript:showDiv('mapa','mostrar');"><b>Ubicaci�n Geogr�fica</b></a>
					<span id="texto_ubicacion">Cargando Flash....</span>&nbsp;&nbsp;
					<img src="images/flecha.gif">&nbsp;<b>Filtrar por</b>&nbsp;
					<select id='graficar_por' name="graficar_por" class="select" onchange="getDataV1('teps','admin/ajax_data.php?object=teps&graficar_por='+document.getElementById('graficar_por').options[document.getElementById('graficar_por').selectedIndex].value,'teps')">
						<option value="">[ Seleccione ]</option>
						<option value="tipo">Tipo</option>
						<option value="sector">Sector</option>
						<option value="enfoque">Enfoque</option>
						<option value="poblacion">Poblaci�n</option>
					</select>&nbsp;<span id="teps"></span>

				</td>
			</tr>
			<tr>
				<td>
					<?
					if ($_SESSION['cnrr'] == 1)	echo "<span style='display:none'>";
					else echo "<span>";
					?>
					<img src="images/flecha.gif">&nbsp;&nbsp;<b>CNRR</b>&nbsp;<input type="radio" id="cnrr_no" name="cnrr" value=0 >No&nbsp;<input type="radio" id="cnrr_si" name="cnrr" value=1 >Si&nbsp;&nbsp;</span>
					<img src="images/flecha.gif">&nbsp;&nbsp;<b>Consulta Social</b>&nbsp;<input type="radio" id="consulta_social_no" name="consulta_social" value=0>No&nbsp;<input type="radio" id="consulta_social_si" name="consulta_social" value=1>Si
					&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='Graficar' class="boton" onclick="graficarDeptoMpio(0,'')">
			</td></tr>
			<tr><td>&nbsp;</td></tr>
			<tr><td id='graficaConteoOrg'></td></tr>
		</table>
		<input type="hidden"id="id_depto" name="id_depto" value=''>
		<input type="hidden" id="id_muns" name="id_muns" value=''>
		<?php
	break;
}
	?>
</form>

