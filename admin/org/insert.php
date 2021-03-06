<?
//INICIALIZACION DE VARIABLES
$org_vo = New Organizacion();
$org_dao = New OrganizacionDAO();
$municipio_vo = New Municipio();
$municipio_dao = New MunicipioDAO();
$depto_vo = New Depto();
$depto_dao = New DeptoDAO();
$region_vo = New Region();
$region_dao = New RegionDAO();
$poblado_vo = New Poblado();
$poblado_dao = New PobladoDAO();
$resguardo_vo = New Resguardo();
$resguardo_dao = New ResguardoDAO();
$parque_nat_vo = New ParqueNat();
$parque_nat_dao = New ParqueNatDAO();
$div_afro_vo = New DivAfro();
$div_afro_dao = New DivAfroDAO();
$tipo_org_dao = New TipoOrganizacionDAO();
$tipo_org_vo = New TipoOrganizacion();
$sector_dao = New SectorDAO();
$sector_vo = New Sector();
$enfoque_dao = New EnfoqueDAO();
$enfoque_vo = New Enfoque();
$poblacion_dao = New PoblacionDAO();
$poblacion_vo = New Poblacion();
$conn = MysqlDb::getInstance();

//$sectores = $sector_dao->GetAllArray('ID_COMP NOT IN (14,21)');
$sectores['hum'] = $tema_dao->GetAllArray('ID_CLASIFICACION = 2 AND ID_PAPA = 0');
$sectores['des'] = $tema_dao->GetAllArray('ID_CLASIFICACION = 4 AND ID_PAPA = 169');
$sectores['paz'] = $tema_dao->GetAllArray('ID_CLASIFICACION = 4 AND ID_PAPA = 168');

$poblaciones = $poblacion_dao->GetAllArray('','','');
$enfoques = $enfoque_dao->GetAllArray('');
$id_donantes = '';
$id = $org_dao->getMaxID() + 1;
$verifi = 0;

if (isset($_GET["accion"])){
	$accion = $_GET["accion"];
}
else if (isset($_POST["accion"])){
	$accion = $_POST["accion"];
}

$condicion = '';
$titulo_pag = ($accion == 'insertar') ? 'Nueva' : 'Actualizar';
//if ($_SESSION["cnrr"] == 1)	$condicion = 'CNRR = '.$_SESSION["cnrr"];

$id_depto = Array();
$num_deptos = 0;
$id_cat = 0;
$chk_conf = "";
$depto_fuera = 'fuera';

if (isset($_GET['id_depto'])){
	$id_depto_s = explode(",",$_GET['id_depto']);

	$num_deptos = count($id_depto_s);

	if($num_deptos == 1)
	$id_depto = $id_depto_s[0];
	else
	$id_depto = $id_depto_s;
}

if (isset($_GET["id_papa"]) && $_GET["id_papa"] != ''){
	$org_vo->id_papa = $_GET["id_papa"];

	$papa_vo = $org_dao->Get($org_vo->id_papa);

	$org_vo->id_tipo = $papa_vo->id_tipo;
	$org_vo->desc = $papa_vo->desc;
	$org_vo->id_sectores = $papa_vo->id_sectores;
	$org_vo->id_enfoques = $papa_vo->id_enfoques;
	$org_vo->id_poblaciones = $papa_vo->id_poblaciones;
	$org_vo->id_donantes = $papa_vo->id_donantes;
	$org_vo->bd = $papa_vo->bd;
	$org_vo->dona = $papa_vo->dona;
	$org_vo->info_confirmada = $papa_vo->info_confirmada;
	$org_vo->id_tipo = $papa_vo->id_tipo;
	$org_vo->consulta_social = $papa_vo->consulta_social;
	$org_vo->cnrr = $papa_vo->cnrr;
	$org_vo->esp_coor = $papa_vo->esp_coor;

}


//VIENE DE LA OPCION DE PUBLICAR ORG. REGISTRADA
$publicar = (isset($_POST["id_org_r"]) || isset($_GET["id_org_r"])) ? 1 : 0;
if ($publicar == 1){
	$id_org_r = (isset($_POST["id_org_r"])) ? $_POST["id_org_r"] : $_GET["id_org_r"];

	$org_vo = $org_dao->getOrgRegistro($id_org_r);

	//DEPTO SEDE
	$mun_sede = $municipio_dao->Get($org_vo->id_mun_sede);
	$id_depto = $mun_sede->id_depto;

	if (isset($_POST["submit"])){
		$a = $org_dao->GetAllArray("nom_org = '".$_POST["nombre"]."' AND SIG_ORG = '".$_POST["sigla"]."'",'','');

		if (count($a) > 0){

			$id_depto = $org_vo->id_deptos;
			$num_deptos = count($id_depto);

			$accion = "actualizar";
			$id = $org_dao->GetMaxID();

			$org_dao->setPublicadaOrgRegistro($id_org_r);
		}
	}

	$_SESSION["nombre_org"] = $org_vo->nom;
	$verifi = 1;
	$publicar = 1;

}

//VIENE DE LA VERIFICACION
if (isset($_GET["nombre"])){
	//$_SESSION["nombre_org"] = urlencode($_GET["nombre"]);
	$_SESSION["nombre_org"] = $_GET["nombre"];

	$org_vo->nom = urldecode($_GET["nombre"]);
	$verifi = 1;
}

if (isset($_GET["verifi"])){
	$verifi = 1;
	$org_vo->nom = stripslashes($_SESSION["nombre_org"]);
}

//Caso de Actualizacion
if ($accion == "actualizar" && !isset($_POST["submit"])){

	$id = $_GET["id"];
	$org_vo = $org_dao->Get($id);
    $id_depto = $depto_fuera;

    //DEPTO SEDE
    if (!empty($org_vo->id_mun_sede)) {
        $mun_sede = $municipio_dao->Get($org_vo->id_mun_sede);
        if (!isset($_GET["actualizar_cobertura"]) && !isset($_GET["id_depto"])){
            $id_depto = $mun_sede->id_depto;
        }
        //SI VIENE DE INSERTAR
        else if (isset($_GET["id_depto"]) && !isset($_GET["depto_ch"])){
            $id_depto_s = explode(",",$_GET['id_depto']);
            $org_vo->id_deptos = $id_depto_s;
            $id_depto = $id_depto_s;

        }
        else if (isset($_GET["id_depto"]) && isset($_GET["depto_ch"])){
            $id_depto_s = explode(",",$_GET['id_depto']);
            $org_vo->id_deptos = $id_depto_s;
            $id_depto = $id_depto_s[0];

        }
        else{
            $id_depto = $org_vo->id_deptos;
        }
    }

	$id_donantes = implode("|",$org_vo->id_donantes);
	//NACIMIENTO
	if ($org_vo->naci == 0)	$org_vo->naci = "";

	$num_deptos = count($id_depto);
}

//PASO 4. COBERTURA GEOGRAFICA
if (isset($_POST["submit"]) && $publicar == 0){

	if ($accion == "insertar"){
		$id = $org_dao->GetMaxID();
	}

	else if ($accion == "actualizar"){
		$id = $_POST["id"];
	}

	$org_vo = $org_dao->Get($id);
	$id_depto = $org_vo->id_deptos;
	$num_deptos = count($id_depto);
}

?>

<script src="js/ajax.js"></script>
<script>
function verLogo(imagen){
	var win = window.open('','','width=300,height=200,top=100,left=100,scrollbars=1');

	win.document.write('<img src="/sissh/'+ imagen +'">');

}
function unSelectNombreDepto(){

	ob = document.getElementById('id_muns');

	for (var i = 0; i < ob.options.length; i++){
		if (ob.options[ i ].text.indexOf("--------") > -1)
			ob.options[ i ].selected = false;
	}
}
function buscarOrgs(){

	texto = document.getElementById('s').value;

	/*keyNum = e.keyCode;

	if (keyNum == 8){  //Backspace
		texto = texto.slice(0, -1);  //Borra el ultimo caracter
	}
	else{
		keyChar = String.fromCharCode(keyNum);
		texto +=  keyChar;
	}*/

	if (texto.length > 1){
		document.getElementById('ocurrenciasOrg').style.display='';
		getDataV1('ocurrenciasOrg','ajax_data.php?object=ocurrenciasOrg&case='+document.getElementById('case').options[document.getElementById('case').selectedIndex].value+'&s='+texto,'ocurrenciasOrg')
	}

	//El valor de donde, se coloca en js/ajax.js
}
//AGREGA UNA OPCION A UN COMBO
function AddOption1(text,value,combo){

	combo.options[combo.options.length] = new Option(text, value);

}

//Paso siguiente a seleccionar el papa
function sigPapa(){
	var id_papa = document.getElementById('id_papa').value;

	var id_depto = '<?=$id_depto?>';
	<?
	if (isset($_GET["id"])){
		echo "var id = ".$_GET['id'].";";
		echo "location.href = 'index.php?accion=$accion&verifi=1&id_depto='+id_depto+'&id_papa='+id_papa+'&id='+id;";
	}
	else{
		echo "location.href = 'index.php?accion=$accion&verifi=1&id_depto='+id_depto+'&id_papa='+id_papa;";
	}
	?>
}

function initAutocomplete() {
    // Create the search box and link it to the UI element.
    var input = document.getElementById('id_mun_sede');
    var searchBox = new google.maps.places.SearchBox(input);
}

</script>

<style type="text/css">
</style>

<div class="container">
<form action="<?=$_SERVER['PHP_SELF']?>" method="POST" class="form">
	<h2><?=strtoupper($titulo_pag)?> ORGANIZACION</h2>
		<? if (!isset($_POST["submit"]) && !isset($_GET["actualizar_cobertura"])){ ?>
			<? if ($verifi == 0 && $accion == "insertar"){ ?>
				<div class="panel panel-default">
						<div class="panel-heading">PASO 1. Verificación</div>
						<div class="panel-body form-inline">
						<p>
							Digite el nombre o la sigla (seleccionar sigla en el combo) de la Organización que va a ingresar en el sistema y verifique que no existe
							mediante el listado que aparece debajo del campo a medida que va ingresando caracteres.
							Si necesita ingresar una Organización Sede, por favor, incluya el nombre de la sede en el título (ej. Ocha - Medellín).
						</p>
						<div class="form-group">
							<select id='case' class='form-control'>
								<option value='nombre'>Nombre</option>
								<option value='sigla'>Sigla</option>
							</select>
						</div>
						<div class="form-group">
							<label for="">
								<input type='radio' id="comience" name="donde" value='comience' checked>
								Que <b>comience</b> con
							</label>
							<label for="">
								<input type='radio' id="contenga" name="donde" value='contenga'>
								Que <b>contenga</b> a
							</label>
						</div>
						<input type="text" id='s' name='s' class='form-control' size="30">
						<input type="button" class="boton" value="Buscar" onclick="buscarOrgs();document.getElementById('sig_validar').style.display='';">
						<input type="button" id="sig_validar" value="Siguiente" class="boton" onclick="location.href='index.php?m_e=org&accion=insertar&nombre='+document.getElementById('s').value" style="display:none">
						<div id='ocurrenciasOrg' class='ocurrenciasOrg' style='display:none'></div>
						</div>
				</div>
					<? }
					else { ?>
						<div class="panel panel-default">
							<div class="panel-heading"><b>1. Ubicaci&oacute;n geogr&aacute;fica de la sede</b></div>
							<div class="panel-body">
								<div class="form-group">
									<label for="">Departamento</label>
									<?php $onchange = ($accion == "insertar") ? 'index.php?accion=insertar&verifi=1' : "index.php?accion=actualizar&paso_1=ok&id=$id&depto_ch=1" ?>
									<select id="id_depto" name="id_depto" class="form-control" onchange="enviar_deptos('<?php echo $onchange ?>');">
					    		  		<option value=''>Seleccione alguno...</option>
										<?
										//DEPTO
										$depto_dao->ListarCombo('combo',$id_depto,'');
						    			?>
	                                    <option disabled>---------------------</option>
	                                    <?php
	                                    $chk =  ($id_depto == $depto_fuera) ? 'selected' : '';
	                                    ?>
	                                    <option value='<?php echo $depto_fuera ?>' <?php echo $chk ?> >Fuera de Colombia</option>
					    			</select>
					  			</div>
							</div>
						</div>
				    	<?
						}
						?>
				<!-- LOCALIZACION : FIN -->
			<? if (!isset($_GET['id_papa']) && (isset($_GET['id_depto']) || $accion == "actualizar" || $publicar == 1)){ ?>
            <script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initAutocomplete" async defer></script>

			<!-- ORGANIZACION A LA QUE PERTENECE : INICIO -->
			<div class="panel panel-default">
				<div class="panel-heading">
					<b>2. Organizaci&oacute;n a la que pertenece</b>, si est&aacute; creando una oficina en el territorio,
					debe seleccionar en este campo la organiaci&oacute;n principal
				</div>
				<div class="panel-body">
					<div class="form-group  form-inline">
						<input type="hidden" id="id_papa" name="id_papa" value="<?=$org_vo->id_papa;?>" />
						<select id="nom_papa" name="nom_papa" multiple size="1" class="form-control" style="width:400px" />
						<?
						if ($org_vo->id_papa != 0){
							$papa = $org_dao->Get($org_vo->id_papa);
							echo "<option value=".$org_vo->id_papa.">".$papa->nom."</option>";
						}
						?>
						</select>
						<input type="button" name="buscar_org" value="Buscar Organizaci&oacute;n" class="boton" onclick="window.open('buscar_org.php?field_hidden=id_papa&field_text=nom_papa&multiple=0&combo_extra=','','top=100,left=100,width=800,height=700,scrollbars=1');" />
						<br /><br />
					</div>
					<div class="form-group">
						<? if (!isset($_GET['id_papa'])){ ?>
						<button type="button" class="boton" onclick="sigPapa()">Cargar informaci&oacute;n asociada a la oficina principal o <b>continuar si no aplica</b></button>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php }
			if (isset($_GET['id_papa']) || $accion == "actualizar" || $publicar == 1){ ?>
				<!-- DATOS GENERALES : INICIO -->
				<div class="panel panel-default">
					<div class="panel-heading">
						<b>3. Datos generales</b>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="form-group col-md-4">
								<label>Nombre [ <a href="index.php?accion=insertar">Cambiar</a>]</label>
								<input type="text" name="nombre" id="nombre" class="form-control" value='<?=$org_vo->nom;?>' size="60" />
							</div>
							<div class="form-group col-md-4">
								<label for="">Sigla</label>
								<input type="text" name="sigla" id="sigla" class="form-control" value="<?=$org_vo->sig;?>" size="15" />
							</div>
							<div class="form-group col-md-4">
								<label for="">NIT</label>
								<input type="text" name="nit" id="nit" class="form-control" value="<?=$org_vo->nit;?>" size="15" />
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-4">
								<label for="">Tipo</label>
								<select id="id_tipo" name="id_tipo" class="form-control">
									<option value=''>Seleccione alguno...</option>
									<?
									//TIPO
									$tipo_org_dao->ListarCombo('combo',$org_vo->id_tipo,$condicion);
									?>
								</select>
							</div>
							<div class="form-group col-md-4">
								<label for="">Municipio donde se encuentra la sede</label>
			                    <?php $name_dd = ($id_depto != $depto_fuera) ? 'id_mun_sede' : 'pais_ciudad' ?>
								<?
			                    //MUNS
			                    if ($id_depto != $depto_fuera) { ?>
			                        <select id="<?php echo $name_dd ?>" name="<?php echo $name_dd ?>" class="form-control">
			                        <option value=''>Seleccione alguno...</option>
			                        <?php
								    $municipio_dao->ListarCombo('combo',$org_vo->id_mun_sede,"ID_DEPTO IN ('".$id_depto."')");
			                        ?>
			                        </select>
			                        <?php
			                    }
			                    else {
			                        echo '<input type="text" id="id_mun_sede" name="pais_ciudad" class="form-control" value="'.$org_vo->pais_ciudad.'" placeholder="Busque la ciudad" />';
			                    }
								?>
							</div>
							<div class="form-group col-md-4">
								<label for="">Dirección</label>
								<input type="text" name="dir" id="dir" class="form-control" value="<?=$org_vo->dir;?>" />
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-4">
								<label for="">A&ntilde;o de iniciación de labores</label>
								<input type="text" name="naci" id="naci" class="form-control" value="<?=$org_vo->naci;?>" />
							</div>
							<div class="form-group col-md-4">
								<label for="">Email de contacto</label>
								<input type="text" name="pu_email" id="pu_email" class="form-control" value="<?=$org_vo->pu_email;?>" />
								<!-- Se oculta rediseño 2016 -->
								<input type="text" name="un_email" id="un_email" class="hidden" value="<?=$org_vo->un_email;?>" size="20" />
							</div>
							<div class="form-group col-md-4">
								<label for="">P&aacute;gina Web</label>
								<input type="text" name="web" id="web" class="form-control" value="<?=$org_vo->web;?>" />
							</div>
						</div>
						<div class="row">
							<div class="form-group col-md-4">
								<label for="">Tel&eacute;fono 1</label>
								<input type="text" name="tel1" id="tel1" class="form-control" value="<?=$org_vo->tel1;?>" />
							</div>
							<div class="form-group col-md-4">
								<label for="">Tel&eacute;fono 2</label>
								<input type="text" name="tel2" id="tel2" class="form-control" value="<?=$org_vo->tel2;?>" />
							</div>
							<div class="form-group col-md-4">
								<label for="">Fax</label>
								<input type="text" name="fax" id="fax" class="form-control" value="<?=$org_vo->fax;?>" />
							</div>
						</div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="">Nombre del representante</label>
                                <input type="text" name="n_rep" id="n_rep" class="form-control" size="30" value="<?=$org_vo->n_rep;?>" />
                            </div>
                            <div class="form-group col-md-3">
                                <label for="">Cargo del representante</label>
                                <input type="text" name="t_rep" id="t_rep" class="form-control" size="20" value="<?=$org_vo->t_rep;?>" />
                            </div>
                            <div class="form-group col-md-3">
                                <label for="">Tel&eacute;fono del representante</label>
                                <input type="text" name="tel_rep" id="tel_rep" class="form-control" size="30" value="<?=$org_vo->tel_rep;?>" />
                            </div>
                            <div class="form-group col-md-3">
                                <label for="">Email del representante</label>
                                <input type="text" name="email_rep" id="email_rep" class="form-control" size="20" value="<?=$org_vo->email_rep;?>" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="">Sectores en los que trabaja la organizaci&oacute;n</label>
                                <?
                                $s=0;
                                foreach ($sectores['hum'] as $vo){

                                    $chk = (in_array($vo->id,$org_vo->id_sectores['hum'])) ? 'checked' : '';

                                    echo "<div class='checkbox'>
                                        <label for="">
                                        <input type='checkbox' id='id_sectores' name='id_sectores[]' value='$vo->id' $chk>&nbsp;$vo->nombre_es
                                        </label>
                                        ";

                                    $s++;
                                }
                                ?>
                            </div>
                            <div class="form-group col-md-4">
                                <label>En el &acute;rea de desarrollo, en que resultados UNDAF trabaja la organizazi&oacute;n</label>
                                <?
                                $s=0;
                                foreach ($sectores['des'] as $vo){

                                    $chk = (in_array($vo->id,$org_vo->id_sectores['des'])) ? 'checked' : '';

                                    echo "<input type='checkbox' id='id_sectores' name='id_sectores[]' value='$vo->id' $chk>&nbsp;$vo->nombre_es<br>";

                                    $s++;
                                }
                                ?>
                            </div>
                        </div>

                        <?php
                        // Se eliminó sectores el 6-2016, no estaba siendo relevante esta info
                        /*        
                        $s=0;
                        foreach ($enfoques as $vo){

                            if(fmod($s,7) == 0)	echo "<td valign='top' width='50%' align='left'>";

                            $chk = (in_array($vo->id,$org_vo->id_enfoques)) ? 'checked' : '';

                            echo "<input type='checkbox' id='id_enfoques' name='id_enfoques[]' value='$vo->id' $chk>&nbsp;$vo->nombre_es<br>";

                            $s++;

                        }*/
                        ?>
						<tr>
							<td><b>Poblaci&oacute;n Beneficiaria (*)</b><br><br><font class="nota">(Seleccione el o los tipos de poblaci�n en los que su Organizaci�n focaliza sus actividades)</font></td>
							<td colspan="5">
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
						<tr>
							<td><b>Donantes <br>(Organizaciones de las que recibe recursos)</b></td>
							<td colspan="5">
								<input type="hidden" id="id_donantes" name="id_donantes" value="<?=$id_donantes?>" />
								<select id="donantes" name="donantes[]" multiple size="<?=count($org_vo->id_donantes)?>" class="form-control" style="width:500px" />
									<?
									foreach($org_vo->id_donantes as $id_dona){
										$dona = $org_dao->Get($id_dona);
										echo "<option value=".$id_dona.">".$dona->nom."</option>";
									}
									?>
								</select>
								<br><input type="button" name="buscar_org" value="Buscar Donante" class="boton" onclick="window.open('buscar_org.php?field_hidden=id_donantes&field_text=donantes&multiple=1&combo_extra=','','top=100,left=100,width=800,height=700,scrollbars=1');" />
								&nbsp;<input type="button" name="borrar_dona" value="Borrar Donante" class="boton" onclick="delete_option(document.getElementById('donantes'));CopiarOpcionesCombo(document.getElementById('donantes'),document.getElementById('id_donantes'));" />
								<br>
								<?
								if ($publicar == 1){ ?>
									<br><br>
									<b>Donantes Registrados</b><br><br>
									<?
									$i = 1;
									foreach ($org_vo->donantes as $dona){
										echo "&nbsp;&nbsp;$i. $dona<br>";
										$i++;
									}
								}
								?>
							</td>
						  </tr>
						<tr>
						  <td><b>Descripci�n</b></td>
						  <td colspan="5"><textarea name="des" id="des" class="area" style="width:600px;height:150px"><?=$org_vo->des;?></textarea></td>
						</tr>
						<tr>
						  <td><b>Forma de visualizar la Organizaci�n en el sistema</b></td>
						  <td colspan="5">
							<select name="view" class="form-control">
							  <option value="2" <? if ($org_vo->view == 2)  echo " selected "; ?>>Nombre</option>
							  <option value="1" <? if ($org_vo->view == 1)  echo " selected "; ?>>Sigla</option>
							</select>
						  </td>
						</tr>

						<tr>
						  <td><b>La Organizaci�n crea Base de Datos de Organizaciones</b></td>
						  <td colspan="5"><input type="radio" name="bd" value="0" <? if ($org_vo->bd == 0)  echo " checked "; ?>>No &nbsp;&nbsp; <input type="radio" name="bd" value="1" <? if ($org_vo->bd == 1)  echo " checked "; ?>>Si</td>
						</tr>
						<tr>
						  <td><b>La Organizaci�n es donante</b></td>
						  <td colspan="5"><input type="radio" name="dona" value="0" <? if ($org_vo->dona == 0)  echo " checked "; ?>>No &nbsp;&nbsp; <input type="radio" name="dona" value="1" <? if ($org_vo->dona == 1)  echo " checked "; ?> />Si</td>
						</tr>

						<tr>
							<td>
						  		<b>Icono</b>
						  		<?
						  		 if ($org_vo->logo != "" && $org_vo->logo != " ") {?>
						  			<br> [ <a href='#' onclick="verLogo('<?=$org_vo->logo?>');return false;">Ver actual</a> ]
						  		 <? } ?>
						  	</td>
							<td colspan="5">
							  <input type="text" id="logo" name="logo" size="30" value="<?=$org_vo->logo;?>" class="form-control" size="40" />&nbsp;<input type="button" name="to_images" value="Seleccionar Imagen" class="boton" onclick="window.open('select_image.php?field=logo&dir=logo','','width=700,height=600,left=150,top=60,scrollbars=1,status=1')">
							</td>
						</tr>
						<tr>
						  <td><b>La Informaci�n de la Organizaci�n est� confirmada?</b></td>
						  <td colspan="5"><input type="radio" name="info_conf" value="0" <? if ($org_vo->info_confirmada == 0)  echo " checked "; ?>>No &nbsp;&nbsp; <input type="radio" name="info_conf" value="1" <? if ($org_vo->info_confirmada == 1)  echo " checked "; ?> />Si</td>
						</tr>
						<tr>
						  <td><b>Consulta Social</b></td>
						  <td colspan="5"><input type="radio" name="consulta_social" value="0" <? if ($org_vo->consulta_social == 0)  echo " checked "; ?>>No &nbsp;&nbsp; <input type="radio" name="consulta_social" value="1" <? if ($org_vo->consulta_social == 1)  echo " checked "; ?> />Si</td>
						</tr>
						<?
						//CODIGO_USUARIO_CNRR
						if ($_SESSION["id_tipo_usuario_s"] != 21){ ?>
							<tr>
							  <td><b>CNRR</b></td>
							  <td colspan="5"><input type="radio" name="cnrr" value="0" <? if ($org_vo->cnrr == 0)  echo " checked "; ?>>No &nbsp;&nbsp; <input type="radio" name="cnrr" value="1" <? if ($org_vo->cnrr == 1)  echo " checked "; ?> />Si</td>
							</tr>
						<?
						}
						else{ ?>
							<input type="hidden" name="cnrr" value="1" />
						<?
						}
						?>
						<tr>
						  <td><b>Participa en alg�n espacio de coordinaci�n?</b></td>
						  <td>
						  	<input type="radio" name="esp" <? if ($org_vo->esp_coor != "")  echo " checked "; ?>>Si&nbsp;<input type="radio" name="esp" <? if ($org_vo->esp_coor == "")  echo " checked "; ?>>No
						  </td>
						  <td colspan="4"><b>Si, Cual?</b><br><textarea id="esp_coor" name="esp_coor" cols="50" rows="5" class="area"><?=$org_vo->esp_coor?></textarea></td>
						</tr>
					</table>
				</td>
				<!-- DATOS GENERALES : FIN -->
			</tr>
			<!-- DATOS DEL REPRESENTANTE : INICIO -->
			<!--<tr>
				<td>
					<table border="0" cellpadding="5" cellspacing="1" width="100%" align="center" class="tabla_consulta">
						<tr><td class="titulo_lista" colspan="2">3. DATOS DEL REPRESENTANTE</td></tr>
						<tr>
						  <td width="300"><b>Nombre del Representante</b></td>
						  <td><input type="text" name="n_rep" id="n_rep" class="form-control" size="40" value="<?=$org_vo->n_rep;?>" /></td>
						</tr>
						<tr>
						  <td><b>Cargo</b></td>
						  <td><input type="text" name="t_rep" id="t_rep" class="form-control" size="40" value="<?=$org_vo->t_rep;?>"  /></td>
						</tr>
						<tr>
							<td><b>Tel�fono del Representante</b></td>
							<td><input type="text" name="tel_rep" id="tel_rep" class="form-control" size="40" value="<?=$org_vo->tel_rep;?>"  /></td>
						</tr>
						<tr>
						  <td><b>Email del Representante</b></td>
						  <td><input type="text" name="email_rep" id="email_rep" class="form-control" size="40" value="<?=$org_vo->email_rep;?>"  /></td>
						</tr>

					</table>
				</td>
			</tr>-->
			<!-- DATOS DEL REPRESENTANTE : FIN -->
			<!-- ENFOQUES - SECTOR : INICIO -->
			<!--<tr>
				<td>
					<table border="0" cellpadding="5" cellspacing="1" width="100%" align="center" class="tabla_consulta">
						<tr><td class="titulo_lista" colspan="2">3. ENFOQUE - SECTOR - DONANTES</td></tr>


					</table>
				</td>
			</tr>-->
			<!-- ENFOQUES - SECTOR : FIN -->
			<!-- ULTIMO PASO: DEFINIR COBERTURA GEOGRAFICA, SI ES ACTUALIZACION DEBE MOSTRAR EL LINK -->
			<? if ($accion == "actualizar" && !isset($_GET["actualizar_cobertura"])){
				$index = (isset($_SESSION["undaf"]) && $_SESSION["undaf"] == 1) ? 'index_undaf.php' : 'index.php';
				?>
				<tr>
					<!-- COBERTURA GEOGRAFICA : INICIO -->
					<td>
						<table border="0" cellpadding="5" cellspacing="1" width="100%" align="center" class="tabla_consulta">
							<tr><td class="titulo_lista" colspan="2">4. COBERTURA GEOGRAFICA : <a href="<?=$index."?".$_SERVER['QUERY_STRING']?>&actualizar_cobertura=1">Modificar</a></td></tr>
						</table>
					</td>
					<!-- COBERTURA GEOGRAFICA : FIN -->
				</tr>
			<? } ?>
			<tr>
				<td align='center'>
					<input type="hidden" name="id" value="<? echo $id; ?>">
					<? if ($publicar == 1)	echo '<input type="hidden" name="id_org_r" value="'.$id_org_r.'">'; ?>
				  <input type="hidden" name="accion" value="<? echo $accion; ?>">
				  <input type="submit" name="submit" value="Aceptar" class="boton" onclick="return validar_forma('id_mun_sede,Sede,id_tipo,Tipo,nombre,Nombre','')">
				</td>
			</tr>
		<? } // IF ID_DEPTO o ACTUALIZAR?>
	<? } // IF !ISSET SUBMIT
// ULTIMO PASO: DEFINIR COBERTURA GEOGRAFICA
else if (isset($_POST["submit"]) || isset($_GET["actualizar_cobertura"])){
		if (!isset($_POST["opcion_f"])){ ?>
			<tr>
				<!-- COBERTURA GEOGRAFICA : INICIO -->
				<td>
					<table border="0" cellpadding="5" cellspacing="1" width="100%" align="center" class="tabla_consulta">
						<tr><td class="titulo_lista" colspan="4">COBERTURA GEOGRAFICA</td></tr>
						<tr>
							<td valign="top"><b>DEPARTAMENTO</b><br>
								<select id="id_depto" name="id_depto[]" multiple size="15" class="form-control">
								<?
								//DEPTOS
								$depto_dao->ListarCombo('combo',$org_vo->id_deptos,'');
								?>
								</select>&nbsp;<img src="images/select_all.png">&nbsp;<a href='#' onclick="selectAll(document.getElementById('id_depto'));return false;">Seleccionar todos</a><br><br>
								<input type="button" value="Listar Municipios" class="boton" onclick="enviar_deptos('index.php?accion=actualizar&actualizar_cobertura=1&id=<?=$id?>');">
							</td>
						<? if (isset($_GET["id_depto"]) || $accion == "actualizar" || $publicar == 1){
							//echo "$num_deptos---$publicar";
							?>
								<td valign="top"><b>MUNICIPIO</b><br>
									<select id="id_muns" name="id_muns[]" multiple size="15" class="form-control">
					        		  <?
					        		  //MUNICIPIO
					        		  for($d=0;$d<$num_deptos;$d++){
					        		  	$id_d = $id_depto[$d];
					        		  	$depto = $depto_dao->Get($id_d);
					        		  	$muns = $municipio_dao->GetAllArray('ID_DEPTO ='.$id_d);

					        		  	echo "<option value='' disabled>-------- ".$depto->nombre." --------</option>";
					        		  	foreach ($muns as $mun){
					        		  		echo "<option value='".$mun->id."'";
					        		  		if (in_array($mun->id,$org_vo->id_muns))  echo " selected ";
					        		  		echo ">".$mun->nombre."</option>";
					        		  	}

					        		  }
					        			?>
					        		</select>&nbsp;<img src="images/select_all.png">&nbsp;<a href='#' onclick="selectAll(document.getElementById('id_muns'));unSelectNombreDepto();return false;">Seleccionar todos</a>
								</td>
							</tr>
							<tr>
								<td colspan='2'>
									<br><b>Siguiente paso</b>:<br><br>
									<input type="radio" name="opcion_f" value="1">&nbsp; Definir la Cobertura Geogr�fica en Poblados o Regiones<br>
									<input type="radio" name="opcion_f" value="3" checked>&nbsp; Finalizar
								</td>
							</tr>
						<?}
		}
					else if (isset($_POST["opcion_f"]) && $_POST["opcion_f"] == 1){ ?>
						 <tr><td class="titulo_lista" colspan="2">4. COBERTURA GEOGRAFICA</td></tr>
						  <tr>
								<td>POBLADO</td>
								<td>
		  							<?
		  							//POBLADO
		  							$id_depto = $_POST['id_depto'];
		  							$id_depto_post = implode(",",$id_depto);

		  							$d = 0;
		  							foreach ($id_depto as $id_d){
		  								$id_depto[$d] = "'".$id_d."'";
		  								$d++;
		  							}
		  							$id_depto = implode(",",$id_depto);

		  							$sql = "SELECT ID_POB, NOM_POB FROM poblado INNER JOIN municipio ON poblado.ID_MUN = municipio.ID_MUN WHERE ID_DEPTO IN (".$id_depto.") ORDER BY NOM_POB";
		  							$rs = $conn->OpenRecordset($sql);
		  							if ($conn->RowCount($rs) > 0){
									  ?>
			  							<select name="id_poblados[]" multiple size="8" class="form-control">
									  	<?
									  	while ($row_rs = $conn->FetchRow($rs)){
									  		echo "<option value=".$row_rs[0];
									  		if (in_array($row_rs[0],$org_vo->id_poblados))  echo " selected ";
									  		echo ">".$row_rs[1]."</option>";
									  	}
					          			?>
					          			</select>
					          		<?}
					          		else{
					          			echo "No hay Poblados definidos en el (los) Departamento (s) seleciconado (s).";

									}?>
								</td>
							</tr>
							 <tr>
								<td>REGION</td>
								<td>
								  <?
								  //REGION
								  $sql = "SELECT DISTINCT region.ID_REG, NOM_REG FROM region INNER JOIN mun_reg ON region.ID_REG = mun_reg.ID_REG INNER JOIN municipio ON mun_reg.ID_MUN = municipio.ID_MUN WHERE ID_DEPTO IN (".$id_depto.")";
								  $rs = $conn->OpenRecordset($sql);
								  if ($conn->RowCount($rs) > 0){
									  ?>
			  							<select name="id_regiones[]" multiple size="8" class="form-control">
									  	<?
									  	while ($row_rs = $conn->FetchRow($rs)){
									  		echo "<option value=".$row_rs[0];
									  		if (in_array($row_rs[0],$org_vo->id_regiones))  echo " selected ";
									  		echo ">".$row_rs[1]."</option>";
									  	}
					          			?>
					          			</select>
					          		<?}
					          		else{
					          			echo "No hay Regiones definidas en el (los) Departamento (s) seleciconado (s).";

									}?>
								</td>
							</tr>
							<tr>
								<td colspan='2'>
									<br><b>Siguiente paso</b>:<br><br>
									<input type="radio" name="opcion_f" value="2">&nbsp; Definir la Cobertura Geogr�fica en Parque Natural, Resguardo o Divison Afro<br>
									<input type="radio" name="opcion_f" value="5" checked>&nbsp; Finalizar
									<input type="hidden" value="<?=$id_depto_post?>" name="id_depto" />
								</td>
							</tr>
						<?
					}
					else if (isset($_POST["opcion_f"]) && $_POST["opcion_f"] == 2){
							?>
							<tr><td class="titulo_lista" colspan="2">4. COBERTURA GEOGRAFICA</td></tr>
							<tr>
								<td width="200">PARQUE NATURAL</td>
								<td>
		  							<?
		  							//PARQUE NATURAL
		  							$id_depto = $_POST['id_depto'];
		  							$id_depto = explode(",",$id_depto);

		  							$d = 0;
		  							foreach ($id_depto as $id_d){
		  								$id_depto[$d] = "'".$id_d."'";
		  								$d++;
		  							}

		  							$id_depto = implode(",",$id_depto);

		  							$sql = "SELECT DISTINCT parque_natural.ID_PAR_NAT, NOM_PAR_NAT FROM parque_natural INNER JOIN par_nat_mun ON parque_natural.ID_PAR_NAT = par_nat_mun.ID_PAR_NAT INNER JOIN municipio ON par_nat_mun.ID_MUN = municipio.ID_MUN WHERE ID_DEPTO IN (".$id_depto.")";
		  							$rs = $conn->OpenRecordset($sql);
		  							if ($conn->RowCount($rs) > 0){
									  ?>
			  							<select name="id_parques[]" multiple size="8" class="form-control">
									  	<?
									  	while ($row_rs = $conn->FetchRow($rs)){
									  		echo "<option value=".$row_rs[0];
									  		if (in_array($row_rs[0],$org_vo->id_parques))  echo " selected ";
									  		echo ">".$row_rs[1]."</option>";
									  	}
					          			?>
					          			</select>
					          		<?}
					          		else{
					          			echo "No hay Parques Naturales definidos en el (los) Departamento (s) seleciconado (s).";

									}?>
								</td>
							</tr>
							<tr>
								<td>RESGUARDO</td>
				        		<td>
								<?
								//RESGUARDO
								$sql = "SELECT DISTINCT resguardo.ID_RESGUADRO, NOM_RESGUARDO FROM resguardo INNER JOIN res_mun ON resguardo.ID_RESGUADRO = res_mun.ID_RESGUADRO INNER JOIN municipio ON res_mun.ID_MUN = municipio.ID_MUN WHERE ID_DEPTO IN (".$id_depto.")";
								$rs = $conn->OpenRecordset($sql);
								if ($conn->RowCount($rs) > 0){
									  ?>
			  							<select name="id_resguardos[]" multiple size="8" class="form-control">
									  	<?
									  	while ($row_rs = $conn->FetchRow($rs)){
									  		echo "<option value=".$row_rs[0];
									  		if (in_array($row_rs[0],$org_vo->id_resguardos))  echo " selected ";
									  		echo ">".$row_rs[1]."</option>";
									  	}
					          			?>
					          			</select>
					          		<?}
					          		else{
					          			echo "No hay Resguardos definidos en el (los) Departamento (s) seleciconado (s).";
									  }?>
							</td>
							</tr>
							<tr>
								<td>DIVISION AFRO</td>
				        		<td>
				        			<?
				        			//DIVISION AFRO
				        			$sql = "SELECT DISTINCT division_afro.ID_DIV_AFRO, NOM_DIV_AFRO FROM division_afro INNER JOIN div_afro_mun ON division_afro.ID_DIV_AFRO = div_afro_mun.ID_DIV_AFRO INNER JOIN municipio ON div_afro_mun.ID_MUN = municipio.ID_MUN WHERE ID_DEPTO IN (".$id_depto.")";
				        			//echo $sql;
				        			$rs = $conn->OpenRecordset($sql);
				        			if ($conn->RowCount($rs) > 0){
									  ?>
			  							<select name="id_divisiones_afro[]" multiple size="8" class="form-control">
									  	<?
									  	while ($row_rs = $conn->FetchRow($rs)){
									  		echo "<option value=".$row_rs[0];
									  		if (in_array($row_rs[0],$org_vo->id_divisiones_afro))  echo " selected ";
									  		echo">".$row_rs[1]."</option>";
									  	}
					          			?>
					          			</select>
					          		<?}
					          		else{
					          			echo "No hay Resguardos definidos en el (los) Departamento (s) seleciconado (s).";
					          		}
									?>
								</td>
							</tr>
							<input type="hidden" value="4" name="opcion_f" />
						<?}
						if (isset($_GET["id_depto"]) || $accion == "actualizar" || $publicar == 1){
							?>
							<!-- COBERTURA GEOGRAFICA : FIN -->
							</tr>
							<tr>
					           <td align='center' colspan="2">
					        		<input type="hidden" name="id" value="<? echo $id; ?>">
					              <input type="hidden" name="accion" value="<? echo $accion; ?>">
					              <input type="hidden" name="actualizar_cobertura" value="1">
					              <input type="submit" name="submit" value="Aceptar" class="boton">
					           </td>
				     		</tr>
				     	<?} ?>
			<? } ?>
</table>
</form>
</div>
