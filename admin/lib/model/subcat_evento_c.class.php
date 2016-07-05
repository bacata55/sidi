<?
/**
* Maneja todas las propiedades del Objeto SubCatEventoConflicto
* Valores de Objeto VO
* @author Ruben A. Rojas C.
*/

Class SubCatEventoConflicto {

  /**
  * Identificador
  * @var int
  */
	var $id;

  /**
  * ID de la Categoria a la que pertenece
  * @var int
  */
	var $id_cat;	
	
  /**
  * Nombre de la SubCategoria
  * @var string
  */	
	var $nombre;
	
	/**
	* Determina si un evento incluye o no la informaci�n de caracteristicas de las v�ctimas
	* @var string
	*/	
	var $info_vict;

}

?>