<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class tip_comp_model extends Odbc
{
    
    public function __construct()
    {
    }



public function load_data(array $param)
{
    $filter = [];
    $filter[] = empty($param['codtp_comprobante']) ? '' : " and a.codtp_comprobante = '".$param['codtp_comprobante']."' ";
    $filter[] = empty($param['destp_comprobante']) ? '' : " and a.destp_comprobante like '%".$param['destp_comprobante']."%' ";
    $filter[] = empty($param['nattp_comprobante']) ? '' : " and a.nattp_comprobante = '".$param['nattp_comprobante']."' ";
    $filter[] = empty($param['codcompania']) ? '' : " and (a.codcompania like '%".$param['codcompania']."%' OR b.nombre like '%".$param['codcompania']."%') ";
    $filter[] = empty($param['tipo_causacion']) ? '' : " and a.tipo_causacion = '".$param['tipo_causacion']."' ";
    $filter[] = empty($param['modulo_uso']) ? '' : " and a.modulo_uso = '".$param['modulo_uso']."' ";
    $filter[] = empty($param['maneja_cons']) ? '' : " and a.maneja_cons = '".$param['maneja_cons']."' ";
    $filter[] = empty($param['imprime_comp']) ? '' : " and a.imprime_comp = '".$param['imprime_comp']."' ";
    $filter[] = empty($param['comp_contable']) ? '' : " and a.comp_contable = '".$param['comp_contable']."' ";
    $filter[] = empty($param['afecta_caja']) ? '' : " and a.afecta_caja = '".$param['afecta_caja']."' ";
    $filter[] = empty($param['forma_pago']) ? '' : " and (a.forma_pago = '".$param['forma_pago']."' OR c.desforma_pago like '%".$param['forma_pago']."%') ";
    foreach ($filter as $key => $value) {
            if ($value === '') {
               $limit = 'first 100';
            }
            else
            {
                $limit = '';
            }
        }        

    $filters = implode(' ', $filter);
    $sql = "select ".$limit."  
            a.codtp_comprobante,
            a.destp_comprobante,
            a.nattp_comprobante,
            a.codcompania,
            b.nombre as codcompania_text,
            a.tipo_causacion,
            a.modulo_uso,
            a.maneja_cons,
            a.imprime_comp,
            a.comp_contable,
            a.afecta_caja,
            a.forma_pago,
            c.desforma_pago as forma_pago_text
            from tipos_comprobantes as a
            LEFT JOIN companias as b ON a.codcompania = b.codigo  
            LEFT JOIN formas_pago as c ON a.forma_pago = c.codforma_pago || ''  
            where 
            a.codtp_comprobante IS NOT NULL 
            ".$filters."
            order by a.codtp_comprobante DESC";
     // echo $sql;
    $this->consultar($sql,__FUNCTION__);
}

public function load_company(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT first 10 codigo as id, codigo || ' : ' || nombre as text FROM companias WHERE codigo LIKE '%".$term."%' OR nombre LIKE '%".$term."%' order by nombre ASC";
    // echo $sql;
    $this->consultar($sql,__FUNCTION__);
}

public function load_formas_pago(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT first 10 codforma_pago as id, codforma_pago || ' : ' || desforma_pago as text FROM formas_pago WHERE desforma_pago LIKE '%".$term."%' order by desforma_pago ASC";
    // echo $sql;
    $this->consultar($sql,__FUNCTION__);
}

public function crearSentenciaInsert(array $param){
    $tabla = isset($param['tabla']) ? $param['tabla'] : '';
    $conten = isset($param['conten']) ? $param['conten'] : [];

    $insert = " INSERT INTO $tabla ";
    $claves = "(" . implode(', ', array_keys($conten)) . ")";
    $valores =" values (" .implode(',', array_map(function($item){return trim(sprintf("'%s'", $item));}, $conten)). ")";
    $insert = $insert . $claves . $valores;
    return $insert;
}

public function createRecord($query){
    $this->consultar($query, __FUNCTION__);
}

public function deleteRecord($id){
    $sql="DELETE FROM tipos_comprobantes WHERE codtp_comprobante = '".$id."' ";
    $this->consultar($sql, __FUNCTION__);   
}

public function editRecord(array $param){

    $destp_comprobante = isset($param['destp_comprobante']) ? $param['destp_comprobante'] : null;
    $nattp_comprobante = isset($param['nattp_comprobante']) ? $param['nattp_comprobante'] : null;
    $codcompania = isset($param['codcompania']) ? $param['codcompania'] : null;
    $tipo_causacion = isset($param['tipo_causacion']) ? $param['tipo_causacion'] : null;
    $modulo_uso = isset($param['modulo_uso']) ? $param['modulo_uso'] : null;
    $maneja_cons = isset($param['maneja_cons']) ? $param['maneja_cons'] : null;
    $imprime_comp = isset($param['imprime_comp']) ? $param['imprime_comp'] : null;
    $comp_contable = isset($param['comp_contable']) ? $param['comp_contable'] : null;
    $afecta_caja = isset($param['afecta_caja']) ? $param['afecta_caja'] : null;
    $cuenta_pagar = isset($param['cuenta_pagar']) ? $param['cuenta_pagar'] : null;
    $forma_pago = isset($param['forma_pago']) ? $param['forma_pago'] : null;
    $codtp_comprobante = isset($param['codtp_comprobante']) ? $param['codtp_comprobante'] : null;
     
    $sql="UPDATE tipos_comprobantes SET 
    destp_comprobante = '".trim($destp_comprobante)."',
    nattp_comprobante = '".trim($nattp_comprobante)."',
    codcompania = '".trim($codcompania)."',
    tipo_causacion = '".trim($tipo_causacion)."',
    modulo_uso = '".trim($modulo_uso)."',
    maneja_cons = '".trim($maneja_cons)."',
    imprime_comp = '".trim($imprime_comp)."',
    comp_contable = '".trim($comp_contable)."',
    afecta_caja = '".trim($afecta_caja)."',
    cuenta_pagar = '".trim($cuenta_pagar)."',
    forma_pago = '".trim($forma_pago)."' 
    WHERE codtp_comprobante = '".$codtp_comprobante."' ";
    // echo $sql;
    $this->consultar($sql, __FUNCTION__);   
}


}
