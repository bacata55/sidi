<?
if (isset($_GET["m_g"]) && !isset($_GET["m_e"])){
	switch ($_GET["m_g"]){
	  case "consulta": ?>
		<div align="justify" style="padding:15px">
			La opci�n Reportes del Sistema de Informaci�n, le permite sacar listados por cualquiera de los m�dulos del Sistema, aplicando diferentes filtros (localizaci�n geogr�fica, rango de fechas, tema, demograf�a).
			<br><br>
			Despu�s de preparar su propio reporte, ud. podr� exportarlo a formato PDF o EXCEL.
		</div>
		<?
		break;
	}
}

switch ($_SESSION["m_e"]){
	case "org": ?>
		  <div style='width:85%;margin:10px'>
	      La Base de Datos de organizaciones contiene informaci�n sobre las organizaciones que trabajan
		  en el campo humanitario en el pa�s y/o con colombianos refugiados en el exterior.
		  Entre la informaci�n recopilada se encuentran los perfiles de las organizaciones,
		  los campos en los que trabajan, y los departamentos (o pa�ses) que cubren.
	    </div>
	<?
	break;

	case "tabla_grafico" : ?>
		<div style='width:85%;margin:10px'>
			<b>	Reporte B�sico</b>: Esta opci�n generar� el formato predeterminado de la minificha <br><br>
			<b>	Reporte Avanzado</b>: Esta opci�n permite generar tablas y gr�ficas de consutlas espec�ficas<br>
		</div>
	<?
	break;

	case "grafica_org":  ?>
		<br>
		<ul id="menulat">
		      <li>GRAFICAS DE ORGANIZACIONES</li>
	    </ul>
	    <ul id="menulat">
	      <li><a href="index.php?m_e=grafica_org&accion=graficar&class=OrganizacionDAO&method=graficaConteo">Gr�fica por Tipo, Poblaci�n, Enfoque o Sector para una Ubicaci�n</a></li>
	      <li><a href="index.php?m_e=grafica_org&accion=graficar&class=OrganizacionDAO&method=graficaConteoDeptoMpio">Gr�fica por Departamento o Municipio para un Tipo, Poblaci�n, Enfoque o Sector</a></li>
	    </ul>
	    <?
	break;

	case 'minificha': ?>
		<div style="padding:15px">
			<b>�Qu� es un perfil geogr�fico?</b>
			<br><br>
			Es una complicaci�n de cifras estad�sticas b�sicas provenientes de diferentes fuentes tanto del Gobierno como algunas ONGs.
			<br><br>
			Tambi�n encontrar� un mapa geogr�fico b�sico de la zona geogr�fica seleccionada.
			<br><br>
			Este producto es �til para conocer la informaci�n b�sica de un departamento o municipio.
			<br>
			Pr�ximamente podr� encontrarlo por regiones.
			<br><br>
			<img src="images/stop.gif">&nbsp;<a href='#' onclick="document.getElementById('instrucciones').style.display=''">Ver instrucciones</a>			
			<!-- <br><br>
			<a href="#" onclick="return generarMinificha();"><img src="images/consulta/boton_minificha.jpg" border="0" title="Esta opci�n le permite generar la Minificha de la Ubicaci�n seleccionada" onmouseover="this.src='images/consulta/boton_minificha.jpg'" onmouseout="this.src='images/consulta/boton_minificha.jpg'" /></a>
			 -->
		</div>
		<?
	break;
}


?>
