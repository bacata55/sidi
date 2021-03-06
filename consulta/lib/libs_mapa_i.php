<?
//COMMON
if (isset($_GET["psql"])){
	include_once("admin/lib/common/postgresdb.class.php");
}
else{
	include_once("admin/lib/common/mysqldb.class.php");	
}

include_once("admin/lib/common/archivo.class.php");
include_once("admin/lib/common/cadena.class.php");
include_once('admin/lib/common/class.ezpdf.php');

//MODEL
include_once("admin/lib/model/evento.class.php");
include_once("admin/lib/model/evento_c.class.php");
include_once("admin/lib/model/municipio.class.php");
include_once("admin/lib/model/depto.class.php");
include_once("admin/lib/model/region.class.php");
include_once("admin/lib/model/poblado.class.php");
include_once("admin/lib/model/resguardo.class.php");
include_once("admin/lib/model/parque_nat.class.php");
include_once("admin/lib/model/div_afro.class.php");
include_once("admin/lib/model/tipo_evento.class.php");
include_once("admin/lib/model/cat_tipo_evento.class.php");
include_once("admin/lib/model/actor.class.php");
include_once("admin/lib/model/cons_hum.class.php");
include_once("admin/lib/model/riesgo_hum.class.php");
include_once("admin/lib/model/proyecto.class.php");
include_once("admin/lib/model/moneda.class.php");
include_once("admin/lib/model/estado_proyecto.class.php");
include_once("admin/lib/model/contacto.class.php");
include_once("admin/lib/model/tipo_vinculorgpro.class.php");
include_once("admin/lib/model/org.class.php");
include_once("admin/lib/model/sector.class.php");
include_once("admin/lib/model/poblacion.class.php");
include_once("admin/lib/model/tema.class.php");
include_once("admin/lib/model/tipo_org.class.php");
include_once("admin/lib/model/enfoque.class.php");
include_once("admin/lib/model/dato_sectorial.class.php");
include_once("admin/lib/model/cat_d_s.class.php");
include_once("admin/lib/model/u_d_s.class.php");
include_once("admin/lib/model/clase_desplazamiento.class.php");
include_once("admin/lib/model/tipo_desplazamiento.class.php");
include_once("admin/lib/model/fuente.class.php");
include_once("admin/lib/model/periodo.class.php");
include_once("admin/lib/model/desplazamiento.class.php");
include_once("admin/lib/model/mina.class.php");
include_once("admin/lib/model/condicion_mina.class.php");
include_once("admin/lib/model/estado_mina.class.php");
include_once("admin/lib/model/sexo.class.php");
include_once("admin/lib/model/edad.class.php");
include_once("admin/lib/model/info_ficha.class.php");
include_once("admin/lib/model/cat_evento_c.class.php");
include_once("admin/lib/model/subfuente_evento_c.class.php");


//DAO
include_once("admin/lib/dao/evento.class.php");
include_once("admin/lib/dao/evento_c.class.php");
include_once("admin/lib/dao/municipio.class.php");
include_once("admin/lib/dao/depto.class.php");
include_once("admin/lib/dao/region.class.php");
include_once("admin/lib/dao/poblado.class.php");
include_once("admin/lib/dao/resguardo.class.php");
include_once("admin/lib/dao/parque_nat.class.php");
include_once("admin/lib/dao/div_afro.class.php");
include_once("admin/lib/dao/tipo_evento.class.php");
include_once("admin/lib/dao/cat_tipo_evento.class.php");
include_once("admin/lib/dao/actor.class.php");
include_once("admin/lib/dao/cons_hum.class.php");
include_once("admin/lib/dao/riesgo_hum.class.php");
include_once("admin/lib/dao/proyecto.class.php");
include_once("admin/lib/dao/moneda.class.php");
include_once("admin/lib/dao/estado_proyecto.class.php");
include_once("admin/lib/dao/contacto.class.php");
include_once("admin/lib/dao/tipo_vinculorgpro.class.php");
include_once("admin/lib/dao/org.class.php");
include_once("admin/lib/dao/sector.class.php");
include_once("admin/lib/dao/poblacion.class.php");
include_once("admin/lib/dao/tema.class.php");
include_once("admin/lib/dao/tipo_org.class.php");
include_once("admin/lib/dao/enfoque.class.php");
include_once("admin/lib/dao/dato_sectorial.class.php");
include_once("admin/lib/dao/cat_d_s.class.php");
include_once("admin/lib/dao/u_d_s.class.php");
include_once("admin/lib/dao/clase_desplazamiento.class.php");
include_once("admin/lib/dao/tipo_desplazamiento.class.php");
include_once("admin/lib/dao/fuente.class.php");
include_once("admin/lib/dao/periodo.class.php");
include_once("admin/lib/dao/desplazamiento.class.php");
include_once("admin/lib/dao/mina.class.php");
include_once("admin/lib/dao/condicion_mina.class.php");
include_once("admin/lib/dao/estado_mina.class.php");
include_once("admin/lib/dao/sexo.class.php");
include_once("admin/lib/dao/edad.class.php");
include_once("admin/lib/dao/sissh.class.php");
include_once("admin/lib/dao/info_ficha.class.php");
include_once("admin/lib/dao/cat_evento_c.class.php");
include_once("admin/lib/dao/subfuente_evento_c.class.php");

?>