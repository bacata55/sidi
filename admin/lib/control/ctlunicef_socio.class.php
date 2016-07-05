<?
/**
 * Maneja todas las acciones de administraci�n de los socios
 *
 * @author Ruben A. Rojas C.
 */

class ControladorPagina {

    /**
     * VO de Socio
     * @var object 
     */
    var $vo;

    /**
     * Variable para el manejo de la clase SocioDAO
     * @var object 
     */
    var $dao;

    /**
     * Constructor
     * Crea la conexi�n a la base de datos y ejecuta la accion
     * @access public
     * @param string $accion Variable que indica la accion a realizar
     */	
    function ControladorPagina($accion) {

        $this->dao = new UnicefSocioDAO();
        $this->vo = new UnicefSocio();

        if ($accion == 'insertar') {
            $this->parseForm();
            $this->dao->Insertar($this->vo);
        }
        else if ($accion == 'actualizar') {
            $this->parseForm();
            $this->dao->Actualizar($this->vo);
        }
        else if ($accion == 'borrar') {
            $this->dao->Borrar($_GET["id"]);
        }
    }

    /**
     * Realiza el Parse de las variables de la forma y las asigna al VO de Socio (variable de clase) 
     * @access public	
     */	
    function parseForm() {
        
        $this->vo->id = (isset($_POST["id"]) && strlen($_POST["id"]) > 0) ? $_POST["id"] : 0;
        $this->vo->nombre =  (isset($_POST["nombre"])) ? $_POST["nombre"] : '';

    }
}
?>
