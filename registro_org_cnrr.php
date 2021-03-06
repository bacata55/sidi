<?
//LIBRERIAS
//COMMON
include_once("admin/lib/common/mysqldb.class.php");
include_once("admin/lib/common/archivo.class.php");

//CONTROL
include_once("admin/lib/control/ctlorg.class.php");

//MODEL
include_once("admin/lib/model/org.class.php");
include_once("admin/lib/model/municipio.class.php");
include_once("admin/lib/model/depto.class.php");
include_once("admin/lib/model/tipo_org.class.php");
include_once("admin/lib/model/sector.class.php");
include_once("admin/lib/model/enfoque.class.php");
include_once("admin/lib/model/poblacion.class.php");

//DAO
include_once("admin/lib/dao/org.class.php");
include_once("admin/lib/dao/municipio.class.php");
include_once("admin/lib/dao/depto.class.php");
include_once("admin/lib/dao/tipo_org.class.php");
include_once("admin/lib/dao/sector.class.php");
include_once("admin/lib/dao/enfoque.class.php");
include_once("admin/lib/dao/poblacion.class.php");

//INICIALIZACION DE VARIABLES
$org_dao = New OrganizacionDAO();
$org_vo = New OrganizacionRegistro();
$municipio_dao = New MunicipioDAO();
$depto_dao = New DeptoDAO();
$tipo_org_dao = New TipoOrganizacionDAO();
$sector_dao = New SectorDAO();
$enfoque_dao = New EnfoqueDAO();
$poblacion_dao = New PoblacionDAO();
$conn = MysqlDb::getInstance();

$sectores = $sector_dao->GetAllArray('ID_COMP NOT IN (14,21)');
$poblaciones = $poblacion_dao->GetAllArray('','','');
$enfoques = $enfoque_dao->GetAllArray('');

//ACCION DE LA FORMA
if (isset($_POST["submit"])){
	$ct = New ControladorPagina($_POST["accion"]);
}

$id_depto = (isset($_GET['id_depto']))	? $_GET['id_depto'] : -1;

//VER DETALLE DE ORG
$ver = 0;
if (isset($_GET["id_org_r"])){
	$id_org_r = $_GET["id_org_r"];
	
	$org_vo = $org_dao->getOrgRegistro($id_org_r);
	$mun_sede = $municipio_dao->Get($org_vo->id_mun_sede);
	$id_depto = $mun_sede->id_depto;
	
	$ver = 1;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Informaci&oacute;n Organizaciones CNRR</title>
<link href="style/consulta.css" rel="stylesheet" type="text/css" />
<script src="admin/js/general.js"></script>
<script>
function validar(){
	
	if (validar_forma('nombre,Nombre,sigla,Sigla,id_tipo,Tipo,id_mun_sede,Sede,pu_email,Email,tel1,Tel�fono 1',document.getElementById('pu_email').value)){
		
		//SECTOR
		error = 1;
		arr = document.forms[0].id_sectores;
		for (i=0;i<arr.length;i++){
			if (arr[i].checked){
				error = 0;
			}
		}
		
		if (error == 1){
			alert("Seleccione alg�n Sector");
			return false;
		}
		
		//ENFOQUE
		error = 1;
		arr = document.forms[0].id_enfoques;
		for (i=0;i<arr.length;i++){
			if (arr[i].checked){
				error = 0;
			}
		}
		
		if (error == 1){
			alert("Seleccione alg�n Enfoque");
			return false;
		}

		//POBLACION
		error = 1;
		arr = document.forms[0].id_poblaciones;
		for (i=0;i<arr.length;i++){
			if (arr[i].checked){
				error = 0;
			}
		}
		
		if (error == 1){
			alert("Seleccione alg�na Poblaci�n Beneficiaria");
			return false;
		}

		//MPIO
		error = 1;
		if (document.forms[0].id_muns){
			ob = document.forms[0].id_muns;
			selected = new Array();
			for (var i = 0; i < ob.options.length; i++){
			  if (ob.options[ i ].selected)
				  error = 0;
			}
		}

		if (error == 1){
			alert("Seleccione la Cobertura Geogr�fica por Municipio");
			return false;
		}
		
		return validar_forma('ingresa_nombre,Nombre de la persona quien ingresa la Informaci�n,ingresa_email,Email de la persona quien ingresa la Informaci�n,ingresa_tel,Tel�fono de la persona quien ingresa la Informaci�n','');
		
	}
	else{
		return false;
	}
}

function listarMunicipios(combo_depto){

	selected = new Array();
	ob = document.getElementById(combo_depto);
	for (var i = 0; i < ob.options.length; i++){
		if (ob.options[ i ].selected)
		selected.push(ob.options[ i ].value);
	}
	var id_deptos = selected.join(",");

	if (selected.length == 0){
		alert("Debe seleccionar alg�n departamento");
	}
	else{
		getDataV1('comboBoxMunicipio','admin/ajax_data.php?object=comboBoxMunicipio&multiple=17&id_deptos='+id_deptos,'comboBoxMunicipio')
	}
}
</script>
<script src="admin/js/ajax.js"></script>
</head>

<body>
<div id="cont">
    <form action="<? echo $_SERVER['PHP_SELF']; ?>" method="POST">
<!--	<table align='center' cellspacing="0" cellpadding="0" border="1" width="916" class="table_login">-->
	<table align="center" border="0" cellpadding="7" cellspacing="3" width="916">
		<tr><td align="center"><img src="images/cnrr/titulo.jpg"><img src="images/cnrr/relleno_up.jpg"><img src="images/cnrr/tituloup3.jpg"></td></tr>
    </table>
    <br>
    <table align="center" border="0" cellpadding="7" cellspacing="3" width="916" class="table_login">
		<?
		if (!isset($_POST["submit"])){ ?>
	    	<tr>
	    		<td><img src="images/spacer.gif" width="100" height="1"></td>
	    		<td colspan="3"><img src="images/spacer.gif" width="720" height="1"></td>
	    	</tr>
	    	<tr>
	    		<td colspan="5">
			    	<H1>Gracias por compartir la informaci�n sobre su organizaci�n.</H1><br>
		
					Para iniciar el registro de su organizaci�n primero seccione el departamento donde se encuentra la <strong>sede de su organizaci�n</strong>.
					<br><br>
					Si su organizaci�n tiene m�s de una sede agradecemos haga un registro por cada una.
					
					Despu�s podr� indicar la cobertura geogr�fica de su organizaci�n o de esa sede espec�fica en diferente
	    		</td>
	    	</tr>
			<tr>
				<td align="left" colspan="5"><b>Seleccione el Departamento donde se encuentra su organizaci�n</b>
	     		&nbsp;
				 	<select id="id_depto" name="id_depto" class="select" onchange="location.href='<?=$_SERVER['PHP_SELF']?>?id_depto='+this.value">
	    		  		<option value=''>Seleccione alguno...</option>
						<?
						//DEPTO
						$depto_dao->ListarCombo('combo',$id_depto,'');
		    			?>
	    			</select>
  				</td>
  			</tr>
  			<?
	    	if (isset($_GET['id_depto']) || $ver == 1){ ?>
	  			<tr>
				<td align="left" colspan="5"><b>Seleccione el Municipio donde se encuentra su organizaci�n</b>&nbsp;				
					<select id="id_mun_sede" name="id_mun_sede" class="select">
					<option value=''>Seleccione alguno...</option>
					<?
					//MUNS
					$municipio_dao->ListarCombo('combo',$org_vo->id_mun_sede,"ID_DEPTO IN ('".$id_depto."')");
					?>
					</select>
				</td>
	        </tr>  			
				<tr>
					<td align="right"><b>Nombre</b></td>
					<td align="left" colspan="3"><input type="text" name="nombre" id="nombre" class="textfield" value='<?=$org_vo->nom;?>' size="67" /></td>
				</tr>
				<tr>
				  <td align="right"><b>Sigla</b></td>
				  <td align="left">
				  		<input type="text" name="sigla" id="sigla" class="textfield" value='<?=$org_vo->sig;?>' size="23" />
				  	</td>
				  <td align="right"><b>NIT</b></td>
				  <td align="left"><input type="text" name="nit" id="nit" class="textfield" size="19" value='<?=$org_vo->nit;?>' /></td>
		
				</tr>
				<tr>
					<td align="right"><b>Tipo</b></td>
					<td align="left">
					<select id="id_tipo" name="id_tipo" class="select">
					<option value=''>Seleccione alguno...</option>
					<?
					//TIPO
					$tipo_org_dao->ListarCombo('combo',$org_vo->id_tipo,$condicion);
					?>
					</select>
					</td>
		        </tr>
				<tr>
				  <td align="right"><b>Direcci�n</b></td>
				  <td align="left" colspan="3"><input type="text" name="dir" id="dir" class="textfield"value='<?=$org_vo->dir;?>' size="67" /></td>
				</tr>
				<tr>
				  <td align="right"><b>A�o de fundaci&oacute;n en Colombia</b></td>
				  <td  align="left"><input type="text" name="naci" id="naci" class="textfield" value='<?=$org_vo->naci;?>' size="23" /></td>
				</tr>
				<tr>
				  <td align="right"><b>Nombre del Representante</b></td>
				  <td align="left"><input type="text" name="n_rep" id="n_rep" class="textfield" value='<?=$org_vo->n_rep;?>' size="30" /></td>
				  <td align="right"><b>Cargo del Representante</b></td>
				  <td  align="left"><input type="text" name="t_rep" id="t_rep" class="textfield" value='<?=$org_vo->t_rep;?>' size="20" /></td>
			  </tr>
				<tr>
				  <td align="right"><b>Email</b></td>
				  <td align="left"><input type="text" name="pu_email" id="pu_email" class="textfield" value='<?=$org_vo->pu_email;?>' size="30"/></td>
				</tr>
				<tr>
				  <td align="right"><b>P�gina Web</b></td>
				  <td align="left" colspan="3"><input type="text" name="web" id="web" class="textfield" value='<?=$org_vo->web;?>' size="60" /></td>
				</tr>
				<tr>
				  	<td align="right"><b>Tel�fono 1</b></td>
				  	<td align="left" colspan="3"><input type="text" name="tel1" id="tel1" class="textfield" value='<?=$org_vo->tel1;?>' size="15" />
				  		&nbsp;&nbsp;<b>Tel�fono 2</b>&nbsp;&nbsp;
						&nbsp;&nbsp;<input type="text" name="tel2" id="tel2" class="textfield" value='<?=$org_vo->tel2;?>' size="15" />
						&nbsp;&nbsp;<b>Fax</b>
						&nbsp;&nbsp;<input type="text" name="fax" id="fax" class="textfield" value='<?=$org_vo->fax;?>' size="10" />
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr><td height="1"><img src="images/spacer.gif" height="1"></td><td height="1" class="td_dotted_top" colspan="3"><img src="images/spacer.gif" height="1"></tr>
				<tr>
					<td><b>Sector</b><br><br><font class="nota">(Seleccione el o los temas en los que trabaja la Organizaci&oacute;n)</font></td>
					<td colspan="3">
						<table border="0" width="100%">
							<tr>
								<?
								$s=0;
							  	foreach ($sectores as $vo){
							  		if(fmod($s,10) == 0)	echo "<td valign='top' align='left'>";
							  		
							  		$chk = (in_array($vo->id,$org_vo->id_sectores)) ? 'checked' : '';
							  		
							  		echo "<input type='checkbox' id='id_sectores' name='id_sectores[]' value='$vo->id' $chk>&nbsp;$vo->nombre_es<br>";
							  		
							  		$s++;
							  	}
							  	?>
							  </tr>
						</table>
					</td>
				</tr>
				<tr><td height="1"><img src="images/spacer.gif" height="1"></td><td height="1" class="td_dotted_top" colspan="3"><img src="images/spacer.gif" height="1"></tr>
				<tr>
					<td><b>Enfoque de la Organizaci�n</b><br><br><font class="nota">(Seleccione el o los enfoques de las acciones de la Organizaci&oacute;n)</font></td>
					<td colspan="3">
						<table width="100%" border="0">
							<tr>
								<?
								$s=0;
							  	foreach ($enfoques as $vo){
							  		
							  		if(fmod($s,7) == 0)	echo "<td valign='top' width='50%' align='left'>";
							  		
							  		$chk = (in_array($vo->id,$org_vo->id_enfoques)) ? 'checked' : '';
							  		
							  		echo "<input type='checkbox' id='id_enfoques' name='id_enfoques[]' value='$vo->id' $chk>&nbsp;$vo->nombre_es<br>";
							  		
							  		$s++;
							  		
							  	}
							  	?>
							  </tr>
						</table>
					</td>
				</tr>						
				<tr><td height="1"><img src="images/spacer.gif" height="1"></td><td height="1" class="td_dotted_top" colspan="3"><img src="images/spacer.gif" height="1"></tr>
				<tr>
					<td><b>Poblaci&oacute;n Beneficiaria</b><br><br><font class="nota">(Seleccione el o los tipos de poblaci�n en los que su Organizaci�n focaliza sus actividades)</font></td>
					<td colspan="3">
						<table width="100%">
							<tr>
								<?
								$s=0;
							  	foreach ($poblaciones as $vo){
							  		if(fmod($s,10) == 0)	echo "<td valign='top' align='left'>";
							  		
							  		$chk = (in_array($vo->id,$org_vo->id_poblaciones)) ? 'checked' : '';
							  		
							  		echo "<input type='checkbox' id='id_poblaciones' name='id_poblaciones[]' value='$vo->id' $chk>&nbsp;$vo->nombre_es<br>";
							  		
							  		$s++;
							  		
							  	}
							  	?>
							  </tr>
						</table>
					</td>
				</tr>
				<tr><td height="1"><img src="images/spacer.gif" height="1"></td><td height="1" class="td_dotted_top" colspan="3"><img src="images/spacer.gif" height="1"></tr>
				<tr>
					<td><b>Cobertura Geogr&aacute;fica por Municipio</b><br><br><font class="nota">(Selecci�n el o los municipios en los que la Organizaci�n tiene presencia)</font></td>
					<td colspan="3" align="left">
						<table width="100%" border="0" align="left">
							<tr>
								<td width="160">
									<table>
										<tr>
											<td><b>Departamento</b><br>
												<select id="id_depto_cobertura" name="id_depto_cobertura[]"  multiple size="17" class="select">
													<?
													//DEPTO
													$depto_dao->ListarCombo('combo',$id_depto_cobertura,'');
													?>
												</select><br><br>
												<a href="#" onclick="listarMunicipios('id_depto_cobertura');return false;">Listar Muncipios</a>
											</td>
											<td valign="top">
												<p align='justify'><font class="nota">Seleccione el (los) Departamento(s) y luego haga click en Listar Municipios.<br><br>
												Para seleccionar varios Departamentos utilice la tecla Ctrl y el click izquierdo del mouse.</font></p>
											
											</td>
										</tr>
									</table>
								</td>
								<td width="200" valign="top">
									<table>
										<tr>
											<td id="comboBoxMunicipio"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>					
					</td>
				</tr>
				<tr><td height="1"><img src="images/spacer.gif" height="1"></td><td height="1" class="td_dotted_top" colspan="3"><img src="images/spacer.gif" height="1"></tr>
				<tr>	
				  <td><b>Participa en alg�n espacio de coordinaci�n?</b></td>
				  <td colspan="3" align='left'>
				    <? if ($org_vo->esp_coor == ""){ ?>
				  		<input type="radio" name="esp">Si&nbsp;<input type="radio" name="esp" checked>No
				  	<? }
				  	   else{ ?>
				  	   	<input type="radio" name="esp" checked>Si&nbsp;<input type="radio" name="esp">No
				  	   	<?
				  	   }
				  	   ?>
				  	&nbsp;&nbsp;
				  	<b>Si, �Cual?</b><br><textarea id="esp_coor" name="esp_coor" cols="50" rows="5" class="area"><?=$org_vo->esp_coor;?></textarea></td>
				</tr>				
				<tr>	
				  <td><b>Comentarios Generales</b><br><br><font class="nota">(Adicione informaci�n general de la Organizaci�n, tal como: misi�n, visi�n, o un p�rrafo corto que complemente el trabajo que la Organizaci�n desarrolla)</font></td>
				  <td colspan="3" align='left'>
				  	<textarea id="descripcion" name="descripcion" cols="50" rows="9" class="area"><?=$org_vo->des;?></textarea>
				  </td>
				</tr>	
				<tr>
					<td><b>Donantes</b>(Opcional)<br><br><font class="nota">(Gobiernos y/u  organizaciones de los que reciben recursos)</font></td>
					<td colspan="3" align='left'>
						<table>
							<tr>
								<?
								for ($i=1;$i<11;$i++){
									if(fmod($i-1,4) == 0)	echo "<td valign='top'>";
									echo "$i. <input type='text' name='donantes[]' class='textfield' size='20' value='".$org_vo->donantes[$i-1]."'><br><br>";
								}
								?>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><b>�Conoce  organizaciones que trabajen/relacionadas con poblaci�n vulnerable?</b></td>
					<td colspan="3" align='left'>
						<table cellpadding="3">
							<?
							for ($i=0;$i<5;$i++){
								echo "<tr>
										<td>Nombre&nbsp;<input type='text' name='pob_vul_nombre[]' class='textfield' value='".$org_vo->org_pob_vul_nombre[$i]."' size='15'>
										<td>Email&nbsp;<input type='text' name='pob_vul_email[]' class='textfield' value='".$org_vo->org_pob_vul_email[$i]."' size='20'>
										<td>Tel&nbsp;<input type='text' name='pob_vul_tel[]' class='textfield' value='".$org_vo->org_pob_vul_tel[$i]."' size='20'>
										
									</tr>";
							}
							?>
						</table>
					</td>
				</tr>
				<tr><td height="1"><img src="images/spacer.gif" height="1"></td><td height="1" class="td_dotted_top" colspan="3"><img src="images/spacer.gif" height="1"></tr>
				<tr>
					<td><b>Persona quien ingresa la Informaci�n</b></td>
					<td colspan="3" align='left'>Nombre&nbsp;<input type="text" id="ingresa_nombre" name="ingresa_nombre" class="textfield" value='<?=$org_vo->ingresa_nombre?>' size="15">&nbsp;
					Email&nbsp;<input type="text" id="ingresa_email" name="ingresa_email" class="textfield" value='<?=$org_vo->ingresa_email?>' size="20">&nbsp;
					Tel&nbsp;<input type="text" id="ingresa_tel" name="ingresa_tel" class="textfield" value='<?=$org_vo->ingresa_tel?>' size="20"></td>
				</tr>
				<tr><td height="1"><img src="images/spacer.gif" height="1"></td><td height="1" class="td_dotted_top" colspan="3"><img src="images/spacer.gif" height="1"></tr>
				<tr><td>&nbsp;</td></tr>
				<?
				if ($ver == 0){ ?>
				<tr>
					 <td colspan="4" align='center'>
				   <input type="hidden" name="accion" value="registrar" />
				   <input type="hidden" name="activo" value="0" />
				   <input type="hidden" name="cnrr" value="1" />
				   <input type="submit" name="submit" value="Aceptar" class="boton" onclick="return validar();" />
				</td>
				</tr>
	    		<?
				}
			}
		}
		else {?>
		<tr>
			 <td align="center" class="titulo_login">Registro realizado con �xito!</td>
		</tr>
		<?
		}
		?>
    </table>
</div>
</form>
</body>
</html>