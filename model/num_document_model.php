<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class num_document_model extends Odbc
{
    
    public function __construct()
    {
    }

public function p($format, array $args, $pattern="/\{(\w+)\}/") {
    return preg_replace_callback($pattern, function ($matches) use ($args) {
        return @$args[$matches[1]] ?: $matches[0];
    }, $format);
}

public function load_data(array $param)
{
    $filter = [];
    $filter[] = empty($param['consecutivo']) ? '' : " and a.consecutivo = '".$param['consecutivo']."' ";
    $filter[] = empty($param['codsucursal']) ? '' : " and a.codsucursal like '%".$param['codsucursal']."%' OR e.nombre LIKE '%".$param['codsucursal']."%' ";
    $filter[] = empty($param['codtp_comprobante']) ? '' : " and a.codtp_comprobante like '%".$param['codtp_comprobante']."%' OR b.destp_comprobante LIKE '%".$param['codtp_comprobante']."%' ";
    $filter[] = empty($param['codcaja']) ? '' : " and a.codcaja like '%".$param['codcaja']."%' OR d.nombres LIKE '%".$param['codcaja']."%' ";
    $filter[] = empty($param['codusuario']) ? '' : " and a.codusuario like '%".$param['codusuario']."%' OR f.nombres LIKE '%".$param['codusuario']."%' ";
    $filter[] = empty($param['numero_inicial']) ? '' : " and a.numero_inicial like '%".$param['numero_inicial']."%' ";
    $filter[] = empty($param['numero_final']) ? '' : " and a.numero_final like '%".$param['numero_final']."%' ";
    $filter[] = empty($param['numero_actual']) ? '' : " and a.numero_actual like '%".$param['numero_actual']."%' ";
    $filter[] = empty($param['estado']) ? '' : " and a.estado like '%".$param['estado']."%' ";

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
            unique a.consecutivo,
            a.codsucursal,
            e.nombre as codsucursal_text,
            a.codtp_comprobante,
            b.destp_comprobante as codtp_comprobante_text,
            a.codcaja,
            d.nombres as codcaja_text,
            a.codusuario,
            f.nombres as codusuario_text,
            a.numero_inicial,
            a.numero_final,
            a.numero_actual,
            a.estado,
            a.descto,
            a.usrcrea,
            a.feccrea,
            a.horacre,
            a.usrmodi,
            a.fecmodi,
            a.horamod,
            a.concepto
            from numera_documentos as a
            LEFT JOIN tipos_comprobantes as b ON a.codtp_comprobante = b.codtp_comprobante
            LEFT JOIN usuarios as c ON a.codcaja = c.codusu
            LEFT JOIN (
              select unique cedula, nombres, priapel, segapel, codsucur
              from personal where estado = 'A'
            ) as d ON c.cedula = d.cedula AND a.codsucursal = d.codsucur
            LEFT JOIN sucursales as e ON a.codsucursal = e.codigo
            LEFT JOIN (
              select unique cedula, nombres, priapel, segapel, codsucur
              from personal where estado = 'A'
            ) as f ON a.codusuario = f.cedula AND a.codsucursal = f.codsucur
            WHERE a.consecutivo IS NOT NULL
            ".$filters."
            order by a.consecutivo DESC";
     // echo $sql;
    $this->consultar($sql,__FUNCTION__);
}

public function validNumStatus(array $param){
    $codtp_comprobante = isset($param['codtp_comprobante']) ? $param['codtp_comprobante'] : '';
    $codsucursal = isset($param['codsucursal']) ? $param['codsucursal'] : '';
    $codcaja = isset($param['codcaja']) ? $param['codcaja'] : '';
    $codusuario = isset($param['codusuario']) ? $param['codusuario'] : '';

    $sql = "SELECT COUNT(*) AS cnt
           FROM numera_documentos nd
          WHERE nd.codtp_comprobante = '$comp'
            AND nd.codsucursal = '$suc'
            AND nd.codcaja = '$codcaja'
            AND nd.codusuario = '$codusuario'
            AND nd.codusuario IS NULL
            AND nd.estado = 'A'";
    $this->consultar($sql,__FUNCTION__);
}

public function vld_asignumeracion(array $param){
    $codsucursal = isset($param['codsucursal']) ? $param['codsucursal'] : null;
    $codtp_comprobante = isset($param['codtp_comprobante']) ? $param['codtp_comprobante'] : null;
    $numero_inicial = isset($param['numero_inicial']) ? $param['numero_inicial'] : null;
    $numero_final = isset($param['numero_final']) ? $param['numero_final'] : null;

    $sql = "SELECT COUNT(*) AS v_cnt
     FROM numera_documentos nd
    WHERE nd.codsucursal = {codsucursal}
      AND nd.codtp_comprobante = {codtp_comprobante}
      AND ({numero_inicial} BETWEEN nd.numero_inicial AND nd.numero_final
       OR {numero_final} BETWEEN nd.numero_inicial AND nd.numero_final )";

    $query = $this->p($sql, [
        'codsucursal'=>$codsucursal,
        'codtp_comprobante'=>$codtp_comprobante,
        'numero_inicial'=>$numero_inicial,
        'numero_final'=>$numero_final,
    ]);
    $this->consultar($query,__FUNCTION__);
}

public function vld_consec(array $param){
    $codsucursal = isset($param['codsucursal']) ? $param['codsucursal'] : null;
    $codtp_comprobante = isset($param['codtp_comprobante']) ? $param['codtp_comprobante'] : null;
    $numero_inicial = isset($param['numero_inicial']) ? $param['numero_inicial'] : null;

    $sql = "SELECT COUNT(*) AS v_cnt
              FROM numera_documentos nd
             WHERE nd.codsucursal = {codsucursal}
               AND nd.codtp_comprobante = {codtp_comprobante}
               AND nd.numero_final = {numero_inicial} - 1";

    $query = $this->p($sql, [
        'codsucursal'=>$codsucursal,
        'codtp_comprobante'=>$codtp_comprobante,
        'numero_inicial'=>$numero_inicial,
    ]);
    $this->consultar($query,__FUNCTION__);
}

public function load_branch_office(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT first 10 codigo as id, codigo || ' : ' || nombre as text FROM sucursales WHERE nombre LIKE '%".$term."%' AND estado = 'A' AND codregion IS NOT NULL order by nombre ASC ";
    // echo $sql;
    $this->consultar($sql,__FUNCTION__);
}

public function load_tipos_comprobantes(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT first 10 codtp_comprobante as id, codtp_comprobante || ' : ' || destp_comprobante as text FROM tipos_comprobantes WHERE codtp_comprobante || '' LIKE '%".$term."%' OR  destp_comprobante LIKE '%".$term."%' order by destp_comprobante ASC ";
    // echo $sql;
    $this->consultar($sql,__FUNCTION__);
}

public function load_cod_caja(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $suc = isset($param['suc']) ? $param['suc'] : '';
    $sql = "SELECT first 10 a.codusu as id, a.codusu || ' : ' || trim(d.nombres) || ' ' || trim(d.priapel) || ' ' || trim(d.segapel) as text from usuarios as a
        LEFT JOIN (
        SELECT unique cedula, nombres, priapel, segapel, codsucur
        from personal where estado = 'A'
        ) as d ON a.cedula = d.cedula
        where d.codsucur = '".$suc."' AND (a.codusu LIKE '%".$term."%' OR d.nombres LIKE '%".$term."%' OR d.priapel LIKE '%".$term."%' OR d.segapel LIKE '%".$term."%')
        order by d.nombres ASC ";
    $this->consultar($sql,__FUNCTION__);
}

public function load_cod_usuario(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $suc = isset($param['suc']) ? $param['suc'] : '';
    $sql = "SELECT first 10 unique a.cedula as id, a.cedula || ' : ' || trim(d.nombres) || ' ' || trim(d.priapel) || ' ' || trim(d.segapel) as text, d.nombres from usuarios as a
        LEFT JOIN (
        SELECT unique cedula, nombres, priapel, segapel, codsucur
        from personal where estado = 'A'
        ) as d ON a.cedula = d.cedula
        where d.codsucur = '".$suc."' AND (a.cedula LIKE '%".$term."%' OR d.nombres LIKE '%".$term."%' OR d.priapel LIKE '%".$term."%' OR d.segapel LIKE '%".$term."%')
        order by d.nombres ASC ";
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

public function disableRecord(array $param){
    $usrmodi = isset($param['usrmodi']) ? $param['usrmodi'] : nul; 
    $fecmodi = isset($param['fecmodi']) ? $param['fecmodi'] : nul; 
    $horamod = isset($param['horamod']) ? $param['horamod'] : nul; 
    $consecutivo = isset($param['consecutivo']) ? $param['consecutivo'] : null;

    $sql="UPDATE numera_documentos SET 
    estado = 'I',
    usrmodi = '".trim($usrmodi)."',
    fecmodi = '".trim($fecmodi)."',
    horamod = '".trim($horamod)."'
     WHERE consecutivo = '".$consecutivo."' ";
    $this->consultar($sql, __FUNCTION__);   
}

public function enableRecord(array $param){
    $usrmodi = isset($param['usrmodi']) ? $param['usrmodi'] : nul; 
    $fecmodi = isset($param['fecmodi']) ? $param['fecmodi'] : nul; 
    $horamod = isset($param['horamod']) ? $param['horamod'] : nul; 
    $consecutivo = isset($param['consecutivo']) ? $param['consecutivo'] : null;

    $sql="UPDATE numera_documentos SET 
    estado = 'A',
    usrmodi = '".trim($usrmodi)."',
    fecmodi = '".trim($fecmodi)."',
    horamod = '".trim($horamod)."'
    WHERE consecutivo = '".$consecutivo."' ";
    $this->consultar($sql, __FUNCTION__);   
}

public function chageStatus(array $param){
    $usrmodi = isset($param['usrmodi']) ? $param['usrmodi'] : nul; 
    $fecmodi = isset($param['fecmodi']) ? $param['fecmodi'] : nul; 
    $horamod = isset($param['horamod']) ? $param['horamod'] : nul; 
    $consecutivo = isset($param['consecutivo']) ? $param['consecutivo'] : null;
    $status = isset($param['status']) ? $param['status'] : null;

    $sql="UPDATE numera_documentos SET 
    estado = '".$status."',
    usrmodi = '".trim($usrmodi)."',
    fecmodi = '".trim($fecmodi)."',
    horamod = '".trim($horamod)."'
    WHERE consecutivo = '".$consecutivo."' ";
    $this->consultar($sql, __FUNCTION__);   
}

 function editRecord(array $param){

    $consecutivo = isset($param['consecutivo']) ? $param['consecutivo'] : nul; 
    $codsucursal = isset($param['codsucursal']) ? $param['codsucursal'] : nul; 
    $codtp_comprobante = isset($param['codtp_comprobante']) ? $param['codtp_comprobante'] : nul; 
    $codcaja = isset($param['codcaja']) ? $param['codcaja'] : nul; 
    $codusuario = isset($param['codusuario']) ? $param['codusuario'] : nul; 
    $numero_inicial = isset($param['numero_inicial']) ? $param['numero_inicial'] : nul; 
    $numero_final = isset($param['numero_final']) ? $param['numero_final'] : nul; 
    $numero_actual = isset($param['numero_actual']) ? $param['numero_actual'] : nul; 
    $usrmodi = isset($param['usrmodi']) ? $param['usrmodi'] : nul; 
    $fecmodi = isset($param['fecmodi']) ? $param['fecmodi'] : nul; 
    $horamod = isset($param['horamod']) ? $param['horamod'] : nul; 
    
    $sql="UPDATE numera_documentos SET 
    codsucursal = '".trim($codsucursal)."',
    codtp_comprobante = '".trim($codtp_comprobante)."',
    codcaja = '".trim($codcaja)."',
    codusuario = '".trim($codusuario)."',
    numero_inicial = '".trim($numero_inicial)."',
    numero_final = '".trim($numero_final)."',
    numero_actual = '".trim($numero_actual)."',
    usrmodi = '".trim($usrmodi)."',
    fecmodi = '".trim($fecmodi)."',
    horamod = '".trim($horamod)."'
    WHERE consecutivo = '".$consecutivo."' ";
    // echo $sql;
    $this->consultar($sql, __FUNCTION__);   
}


}
