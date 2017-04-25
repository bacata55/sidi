<?
/**
 * DAO de TipoProyecto
 *
 * Contiene los métodos de la clase TipoProyecto
 * @author
 */

Class TipoProyectoDAO {

    /**
     * Conexión a la base de datos
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
     * Crea la conexión a la base de datos
     * @access public
     */
    function TipoProyectoDAO (){
        $this->conn = MysqlDb::getInstance();
        $this->tabla = "tipo_proy";
        $this->columna_id = "ID_TIPP";
        $this->columna_nombre = "NOM_TIPP";
        $this->columna_order = "NOM_TIPP";
    }

    /**
     * Consulta los datos de una TipoProyecto
     * @access public
     * @param int $id ID del TipoProyecto
     * @return VO
     */
    function Get($id){
        $sql = "SELECT * FROM ".$this->tabla." WHERE ".$this->columna_id." = ".$id;
        $rs = $this->conn->OpenRecordset($sql);
        $row_rs = $this->conn->FetchObject($rs);

        //Crea un VO
        $tipo_proyecto_vo = New TipoProyecto();

        //Carga el VO
        $tipo_proyecto_vo = $this->GetFromResult($tipo_proyecto_vo,$row_rs);

        //Retorna el VO
        return $tipo_proyecto_vo;
    }

    /**
     * Consulta los datos de los Tema que cumplen una condición
     * @access public
     * @param string $condicion Condición que deben cumplir los Tema y que se agrega en el SQL statement.
     * @param string $limit Limit en el SQL
     * @param string $order by Order by en el SQL
     * @return array Arreglo de VOs
     */
    function GetAllArray($condicion,$limit='',$order_by=''){

        $c = 0;
        $sql = "SELECT * FROM ".$this->tabla;
        if ($condicion != ""){
            $sql .= " WHERE ".$condicion;
        }

        //ORDER
        if ($order_by != ""){
            $sql .= " ORDER BY ".$order_by;
        }
        else{
            $sql .= " ORDER BY ".$this->columna_order;
        }

        //LIMIT
        if ($limit != ""){
            $sql .= " LIMIT ".$limit;
        }

        $array = Array();

        $rs = $this->conn->OpenRecordset($sql);
        while ($row_rs = $this->conn->FetchObject($rs)){
            //Crea un VO
            $vo = New TipoProyectoDAO();
            //Carga el VO
            $vo = $this->GetFromResult($vo,$row_rs);
            //Carga el arreglo
            $array[] = $vo;
        }
        //Retorna el Arreglo de VO
        return $array;
    }

    /**
     * Lista los TipoProyecto que cumplen la condición en el formato dado
     * @access public
     * @param string $formato Formato en el que se listarán los TipoProyecto, puede ser Tabla o ComboSelect
     * @param int $valor_combo ID del TipoProyecto que será selccionado cuando el formato es ComboSelect
     * @param string $condicion Condición que deben cumplir los TipoProyecto y que se agrega en el SQL statement.
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
     * Lista los TipoProyecto en una Tabla
     * @access public
     */
    function ListarTabla($condicion){

        include_once ("lib/common/layout.class.php");

        $layout = new Layout();

        $layout->adminGrid(array ('nombre' => array ('titulo' => 'Nombre')));

    }

    /**
     * Carga un VO de TipoProyecto con los datos de la consulta
     * @access public
     * @param object $vo VO de TipoProyecto que se va a recibir los datos
     * @param object $Resultset Resource de la consulta
     * @return object $vo VO de TipoProyecto con los datos
     */
    function GetFromResult ($vo,$Result){

        $vo->id = $Result->{$this->columna_id};
        $vo->nombre = $Result->{$this->columna_nombre};

        return $vo;
    }

    /**
     * Inserta un TipoProyecto en la B.D.
     * @access public
     * @param object $tipo_proyecto_vo VO de TipoProyecto que se va a insertar
     */
    function Insertar($tipo_proyecto_vo){
        //CONSULTA SI YA EXISTE
        $cat_a = $this->GetAllArray($this->columna_nombre." = '".$tipo_proyecto_vo->nombre."'");
        if (count($cat_a) == 0){
            $sql =  "INSERT INTO ".$this->tabla." (".$this->columna_nombre.") VALUES ('".$tipo_proyecto_vo->nombre."')";
            $this->conn->Execute($sql);

            echo "Registro insertado con &eacute;xito!";
        }
        else{
            echo "Error - Existe un registro con el mismo nombre";
        }

    }

    /**
     * Actualiza un TipoProyecto en la B.D.
     * @access public
     * @param object $tipo_proyecto_vo VO de TipoProyecto que se va a actualizar
     */
    function Actualizar($tipo_proyecto_vo){
        $sql =  "UPDATE ".$this->tabla." SET ";
        $sql .= $this->columna_nombre." = '".$tipo_proyecto_vo->nombre."'";

        $sql .= " WHERE ".$this->columna_id." = ".$tipo_proyecto_vo->id;

        $this->conn->Execute($sql);

    }

    /**
     * Borra un TipoProyecto en la B.D.
     * @access public
     * @param int $id ID del TipoProyecto que se va a borrar de la B.D
     */
    function Borrar($id){

        //BORRA
        $sql = "DELETE FROM ".$this->tabla." WHERE ".$this->columna_id." = ".$id;
        $this->conn->Execute($sql);

    }

    /**
     * Check de llaves foraneas, para permitir acciones como borrar
     * @access public
     * @param int $id ID del registro a consultar
     */
    function checkForeignKeys($id){

        $tabla_rel = 'proyecto';

        $sql = "SELECT sum($this->columna_id) FROM $tabla_rel WHERE ".$this->columna_id." = ".$id;
        $rs = $this->conn->OpenRecordset($sql);
        $row = $this->conn->FetchRow($rs);

        $r = ($row[0] > 0) ? true : false;

        return $r;

    }
}

?>
