<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class grupo_concepto_model extends Odbc
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
    $codconcepto = isset($param['codconcepto']) ? $param['codconcepto'] : null; 
    $filter = [];
    $filter[] = empty($param['codconcepto_base']) ? '' : " and (a.codconcepto_base||'' LIKE '%".$param['codconcepto_base']."%' OR b.desconcepto LIKE '%".$param['codconcepto_base']."%') ";
    $filter[] = empty($param['tp_tercero']) ? '' : " and a.tp_tercero = '".$param['tp_tercero']."' ";
    $filter[] = empty($param['tp_regimen']) ? '' : " and a.tp_regimen = '".$param['tp_regimen']."' ";
    $filter[] = empty($param['g_contribuyente']) ? '' : " and a.g_contribuyente = '".$param['g_contribuyente']."' ";
    $filter[] = empty($param['auto_retenedor']) ? '' : " and a.auto_retenedor = '".$param['auto_retenedor']."' ";
    $filter[] = empty($param['ext_reteica']) ? '' : " and a.ext_reteica = '".$param['ext_reteica']."' ";
    $filter[] = empty($param['auto_reteica']) ? '' : " and a.auto_reteica = '".$param['auto_reteica']."' ";
    $filter[] = empty($param['codconcepto_base_unique']) ? '' : " and a.codconcepto_base = '".$param['codconcepto_base_unique']."' ";
   
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
            a.codconcepto,
            c.desconcepto as codconcepto_text,
            a.codconcepto_base,
            b.desconcepto as codconcepto_base_text,
            a.tp_tercero,
            a.tp_regimen,
            a.g_contribuyente,
            a.auto_retenedor,
            a.ext_reteica,
            a.auto_reteica,
            a.estado,
            a.usrcrea,
            a.feccrea,
            a.horacre,
            a.usrmodi,
            a.fecmodi,
            a.horamod
            from grupo_concepto as a, conceptos_mvto as b, conceptos_mvto as c 
            WHERE a.codconcepto = '{codconcepto}' AND a.estado = 'A' AND
            a.codconcepto_base = b.codconcepto AND
            a.codconcepto = c.codconcepto 
            ".$filters."
            order by a.codconcepto DESC";

    $query = $this->p($sql, [
        'codconcepto'=>$codconcepto
    ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}


public function load_ConceptoMvto(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT first 10 a.codconcepto as id, a.codconcepto || ' : ' || a.desconcepto as text FROM conceptos_mvto as a WHERE (a.porc_aplica <> 0 AND a.porc_aplica IS NOT NULL) AND (UPPER(a.codconcepto || '') LIKE UPPER('%".$term."%') OR UPPER(a.desconcepto) LIKE UPPER('%".$term."%')) order by a.desconcepto ASC";
    // echo $sql;
    $this->consultar($sql,__FUNCTION__);
}

public function load_ConceptoMvtoGroup(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT first 10 a.codconcepto as id, a.codconcepto || ' : ' || a.desconcepto as text FROM conceptos_mvto as a WHERE (a.porc_aplica IS NULL OR a.porc_aplica = 0) AND (UPPER(a.codconcepto || '') LIKE UPPER('%".$term."%') OR UPPER(a.desconcepto) LIKE UPPER('%".$term."%')) order by a.desconcepto ASC";
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
    $codconcepto = isset($param['codconcepto']) ? $param['codconcepto'] : null;
    $codconcepto_base = isset($param['codconcepto_base']) ? $param['codconcepto_base'] : null;
    $usrmodi = isset($param['usrmodi']) ? $param['usrmodi'] : null;
    $fecmodi = isset($param['fecmodi']) ? $param['fecmodi'] : null;
    $horamod = isset($param['horamod']) ? $param['horamod'] : null;
    $sql="UPDATE grupo_concepto SET 
    estado = 'I', 
    usrmodi = '$usrmodi', 
    fecmodi = '$fecmodi',
    horamod = '$horamod'
    WHERE codconcepto = '".$codconcepto."' AND codconcepto_base = '".$codconcepto_base."' AND estado = 'A' ";
    // echo $sql;
    return $this->consultar($sql, __FUNCTION__); 

}

}
