<?
session_start();

//LIBRERIAS
require_once $_SERVER['DOCUMENT_ROOT']."/sissh/admin/lib/dao/factory.class.php";
require_once $_SERVER['DOCUMENT_ROOT']."/sissh/admin/lib/common/mysqldb.class.php";

if (empty($_GET["mod"])) {
    die('Faltan parametros!');
}

ini_set('max_execution_time', 0);
ini_set('memory_limit','512M');

$mod = $_GET["mod"];
$render = isset($_GET["render"]) ? $_GET["render"] : 'csv';
$conn = MysqlDb::getInstance();
$render_json = true;

if (!isset($_GET['_c']) || $_GET['_c'] != 's1dicol-api' || !in_array($mod, array('3w'))) {
    die('¬¬');
} 

function utf8ing(&$item, $key) {
    $item = utf8_encode($item);
}

function nf($num) {
    return number_format($num,0,'.',',');
}

switch ($mod){

    case '3w':

        $mun_dao = FactoryDAO::factory('municipio');
        $depto_dao = FactoryDAO::factory('depto');
        $sector_dao = FactoryDAO::factory('sector');
        $org_dao = FactoryDAO::factory('org');
        $separador = '|';

        $muns_a = $mun_dao->GetAllArray('');
        $sectores_a = $sector_dao->GetAllArray('');
        $orgs_a = $org_dao->GetAllArray('','','');
        
        foreach($muns_a as $mun) {
            $muns[$mun->id] = utf8_encode($mun->nombre);
        }

        $orgs = array();
        $org_sectores = array();
        foreach($orgs_a as $org) {
            
            $id_org = $org->id;
            $orgs[$id_org] = utf8_encode($org->nom);
            
            $sql_s = 'SELECT GROUP_CONCAT(ID_COMP) 
                      FROM sector_org 
                      WHERE id_org = '.$id_org;
            
            $rs_s = $conn->OpenRecordset($sql_s);
            $row = $conn->FetchRow($rs_s);

            $org_sectores[$id_org] = (isset($row[0])) ? explode(',', $row[0]) : array() ;
        }

        $sectores_header = array('',''); // Para las 2 columnas divipola y nom_mun

        foreach($sectores_a as $sector) {
            $sector_nom = utf8_encode($sector->nombre_es);
            $sectores[$sector->id] = $sector_nom;
            $sectores_header[] = $sector_nom;
        }
        
        $csv = implode($separador, $sectores_header);

        $matrix = array();

        // Cobertura municipal
        $sql = 'SELECT ID_MUN, ID_ORG 
            FROM mpio_org
            WHERE ID_MUN <> "00000"';
        
        $id_orgs_ok = array();
        $rs = $conn->OpenRecordset($sql);
        while ($row = $conn->FetchRow($rs)) {

            $id_mun = $row[0];
            $id_org = $row[1];

            if (!empty($orgs[$id_org]) && !empty($org_sectores[$id_org])) {
                
                $id_orgs_ok[] = $id_org;
                
                $nom = $orgs[$id_org];

                foreach($org_sectores[$id_org] as $id_sector) {
                    $matrix[$id_mun][$id_sector][] = $nom;
                }
            } 
        }
        
        // Cobertura departamental
        $sql = 'SELECT ID_MUN, ID_ORG 
                FROM depto_org
                JOIN municipio USING (ID_DEPTO)
                WHERE ID_DEPTO <> "00000" AND ID_ORG NOT IN ('.implode(',',$id_orgs_ok).')';

        
        $rs = $conn->OpenRecordset($sql);
        while ($row = $conn->FetchRow($rs)) {

            $id_mun = '"'.$row[0].'"';
            $id_org = $row[1];
            
            if (!empty($orgs[$id_org]) && !empty($org_sectores[$id_org])) {
                $nom = $orgs[$id_org];

                foreach($org_sectores[$id_org] as $id_sector) {
                    if (empty($matrix[$id_mun][$id_sector]) || !in_array($nom, $matrix[$id_mun][$id_sector])) {
                        $id_orgs_ok[] = $id_org;
                        $matrix[$id_mun][$id_sector][] = $nom;
                    }
                }
            }
        }

        // Adiciona organizaciones encargadas o implementadoras de 4W
        $sql = 'SELECT DISTINCT ID_MUN, ID_ORG 
                FROM mun_proy
                JOIN vinculorgpro USING(ID_PROY)
                WHERE ID_MUN <> "00000" AND id_tipo_vinorgpro IN (1,3) AND ID_ORG NOT IN ('.implode(',',$id_orgs_ok).')';
        
        $rs = $conn->OpenRecordset($sql);
        while ($row = $conn->FetchRow($rs)) {

            $id_mun = '"'.$row[0].'"';
            $id_org = $row[1];
            
            if (!empty($orgs[$id_org]) && !empty($org_sectores[$id_org])) {
                $nom = $orgs[$id_org];

                foreach($org_sectores[$id_org] as $id_sector) {
                    if (empty($matrix[$id_mun][$id_sector]) || !in_array($nom, $matrix[$id_mun][$id_sector])) {
                        $matrix[$id_mun][$id_sector][] = $nom;
                    }
                }
            }
        }

        $json = array();
        foreach($matrix as $id_mun => $fila) {
            if (isset($muns[$id_mun])) {

                $fila2csv = array();
                $json_sectores_orgs = array();
                
                $mun_nom = $muns[$id_mun];
                

                $fila2csv[] = '"'.$id_mun.'"';
                $fila2csv[] = '"'.$mun_nom.'"';

                foreach($sectores as $id_sector => $sector) {
                    if (isset($fila[$id_sector])) {
                        $fila2csv[] = '"'.implode('~',$fila[$id_sector]).'"';
                        $json_sectores_orgs[$sector] = $fila[$id_sector];
                    }
                    else {
                        $fila2csv[] = "";
                        $json_sectores_orgs[$sector] = array();
                    }
                }
                
                $json_fila = array('PCODE' => $id_mun, 'Municipio' => $mun_nom, 'Sectores' => $json_sectores_orgs);

                $csv .= "\n".implode($separador, $fila2csv);
                
                $json[] = $json_fila;
            }

        }

        if ($render == 'csv') {
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=\"SIDIH-3W-HDX.csv\"");
            header("Pragma: no-cache");
            header("Expires: 0");
            
            echo $csv;
        }
        else { 
            header('Content-type: application/json');
            echo json_encode($json);
        }

    break;

}

?>
