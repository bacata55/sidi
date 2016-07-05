<?php
/**
 * DAO de SubComponente
 *
 * Contiene los m�todos de la clase SubComponente 
 * @author Ruben A. Rojas C.
 */

Class UnicefSubComponenteDAO {

	/**
	 * Conexi�n a la base de datos
	 * @var object 
	 */
	var $conn;

	/**
	 * Nombre de la Tabla en la Base de Datos
	 * @var string
	 */
	var $tabla;

	/**
	 * Nombre de la columna ID de la Tabla en la Base de Datos
	 * @var string
	 */
	var $columna_id;

	/**
	 * Nombre de la columna Nombre de la Tabla en la Base de Datos
	 * @var string
	 */
	var $columna_nombre;

	/**
	 * Nombre de la columna para ordenar el RecordSet
	 * @var string
	 */
	var $columna_order;

	/**
	 * Constructor
	 * Crea la conexi�n a la base de datos
	 * @access public
	 */	
	function UnicefSubComponenteDAO (){
		$this->conn = MysqlDb::getInstance();
		$this->tabla = "unicef_sub_componente";
		$this->columna_id = "id_sub_componente";
		$this->columna_nombre = "nombre";
		$this->columna_order = "nombre";
	}

	/**
	 * Consulta los datos de un SubComponente
	 * @access public
	 * @param int $id ID del SubComponente
	 * @return VO
	 */	
	function Get($id){
		$sql = "SELECT * FROM ".$this->tabla." WHERE ".$this->columna_id." = ".$id;
		$rs = $this->conn->OpenRecordset($sql);
		$row_rs = $this->conn->FetchObject($rs);

		//Crea un VO
		$subcat_vo = New UnicefSubComponente();

		//Carga el VO
		$subcat_vo = $this->GetFromResult($subcat_vo,$row_rs);

		//Retorna el VO
		return $subcat_vo;
	}

	/**
	* Consulta el valor de un field de la Org
	* @access public
	* @param int $id ID del Organizacion
	* @param string $field Field de la tabla org
	* @return VO
	*/
	function GetFieldValue($id,$field){
		$sql = "SELECT ".$field." FROM ".$this->tabla." WHERE ".$this->columna_id." = ".$id;
		$rs = $this->conn->OpenRecordset($sql);
		$row_rs = $this->conn->FetchRow($rs);

		//Retorna el VO
		return $row_rs[0];
	}

	/**
	 * Consulta Vos
	 * @access public
	 * @param string $condicion Condici�n que deben cumplir los Tema y que se agrega en el SQL statement.
	 * @param string $limit Limit en el SQL
	 * @param string $order by Order by en el SQL 
	 * @return array Arreglo de VOs
	 */	
	function GetAllArray($condicion,$limit='',$order_by=''){

		$sql = "SELECT * FROM ".$this->tabla;

		if ($condicion != "") $sql .= " WHERE ".$condicion;

		//ORDER
		$sql .= ($order_by != "") ?  " ORDER BY $order_by" : " ORDER BY ".$this->columna_order;

		//LIMIT
		if ($limit != "") $sql .= " LIMIT ".$limit;

		$array = Array();

		$rs = $this->conn->OpenRecordset($sql);
		while ($row_rs = $this->conn->FetchObject($rs)){
			//Crea un VO
			$vo = New UnicefSubComponente();
			//Carga el VO
			$vo = $this->GetFromResult($vo,$row_rs);
			//Carga el arreglo
			$array[] = $vo;
		}  
		//Retorna el Arreglo de VO
		return $array;
	}

	/**
	 * Consulta los datos de los Depto que cumplen una condici�n
	 * @access public
	 * @param string $condicion Condici�n que deben cumplir los Depto y que se agrega en el SQL statement.
	 * @return array Arreglo de ID
	 */
	function GetAllArrayID($condicion){

		$sql = "SELECT ".$this->columna_id." FROM ".$this->tabla."";

		if ($condicion != "") $sql .= " WHERE ".$condicion;

		$sql .= " ORDER BY ".$this->columna_order;

		$array = Array();

		$rs = $this->conn->OpenRecordset($sql);
		while ($row_rs = $this->conn->FetchRow($rs)){
			//Carga el arreglo
			$array[] = $row_rs[0];
		}

		//Retorna el Arreglo
		return $array;
	}

	/**
	 * Lista los SubComponente que cumplen la condici�n en el formato dado
	 * @access public
	 * @param string $formato Formato en el que se listar�n los SubComponente, puede ser Tabla o ComboSelect
	 * @param int $valor_combo ID del SubComponente que ser� selccionado cuando el formato es ComboSelect
	 * @param string $condicion Condici�n que deben cumplir los SubComponente y que se agrega en el SQL statement.
	 */			
	function ListarCombo($formato,$valor_combo,$condicion){
		$arr = $this->GetAllArray($condicion);
		$num_arr = count($arr);
		$v_c_a = is_array($valor_combo);

		for($a=0;$a<$num_arr;$a++){
			$vo = $arr[$a];

			if ($valor_combo == "" && $valor_combo != 0)
				echo "<option value=".$vo->id.">".$vo->nombre."</option>";
			else{
				echo "<option value=".$vo->id;

				if (!$v_c_a){
					if ($valor_combo == $vo->id)
						echo " selected ";
				}
				else{
					if (in_array($vo->id,$valor_combo))
						echo " selected ";
				}

				echo ">".$vo->nombre."</option>";
			}
		}
	}

	/**
	 * Carga un VO de SubComponente con los datos de la consulta
	 * @access public
	 * @param object $vo VO de SubComponente que se va a recibir los datos
	 * @param object $Resultset Resource de la consulta
	 * @return object $vo VO de SubComponente con los datos
	 */			
	function GetFromResult ($vo,$Result){

		$vo->id = $Result->{$this->columna_id};
		$vo->nombre = $Result->{$this->columna_nombre};
		$vo->id_componente = $Result->id_componente;

		return $vo;
	}

	/**
	 * Inserta un SubComponente en la B.D.
	 * @access public
	 * @param object $subcat_vo VO de SubComponente que se va a insertar
	 */		
	function Insertar($subcat_vo){
		//CONSULTA SI YA EXISTE
		$subcat_a = $this->GetAllArray($this->columna_nombre." = '".$subcat_vo->nombre."'");
		if (count($subcat_a) == 0){
			$sql =  "INSERT INTO ".$this->tabla." (".$this->columna_nombre.",VICT_SCATEVEN,ID_CATEVEN) VALUES ('".$subcat_vo->nombre."',$subcat_vo->info_vict,$subcat_vo->id_cat)";
			$this->conn->Execute($sql);
			echo "Registro insertado con &eacute;xito!";
		}
		else{
			echo "Error - Existe un registro con el mismo nombre";
		}

	}

	/**
	 * Actualiza un SubComponente en la B.D.
	 * @access public
	 * @param object $subcat_vo VO de SubComponente que se va a actualizar
	 */		
	function Actualizar($subcat_vo){
		$sql =  "UPDATE ".$this->tabla." SET ";
		$sql .= $this->columna_nombre." = '".$subcat_vo->nombre."',";
		$sql .= " VICT_SCATEVEN = ".$subcat_vo->info_vict.",";
		$sql .= " ID_CATEVEN = ".$subcat_vo->id_cat;
		$sql .= " WHERE ".$this->columna_id." = ".$subcat_vo->id;

		$this->conn->Execute($sql);

	}

	/**
	 * Borra un SubComponente en la B.D.
	 * @access public
	 * @param int $id ID del SubComponente que se va a borrar de la B.D
	 */	
	function Borrar($id){

		//BORRA
		$sql = "DELETE FROM ".$this->tabla." WHERE ".$this->columna_id." = ".$id;
		$this->conn->Execute($sql);

	}

	/**
	 * Retorna el numero de Registros
	 * @access public
	 * @return int
	 */
	function numRecords($condicion){
		$sql = "SELECT count(".$this->columna_id.") as num FROM ".$this->tabla;
		if ($condicion != ""){
			$sql .= " WHERE ".$condicion;
		}
		$rs = $this->conn->OpenRecordset($sql);
		$row_rs = $this->conn->FetchRow($rs);

		return $row_rs[0];
	}	
	
	/**
	 * Check de llaves foraneas, para permitir acciones como borrar
	 * @access public
	 * @param int $id ID del registro a consultar
	 */	
	function checkForeignKeys($id){

		$tabla_rel = 'unicef_resultado';
		$col_id = $this->columna_id;
		
		$sql = "SELECT sum($col_id) FROM $tabla_rel WHERE $col_id = $id";
		$rs = $this->conn->OpenRecordset($sql);
		$row = $this->conn->FetchRow($rs);

		$r = ($row[0] > 0) ? true : false;

		return $r;

	}
}

?>
