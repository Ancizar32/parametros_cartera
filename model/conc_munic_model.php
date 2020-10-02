<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class conc_munic_model extends Odbc
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
    $codmunicipio = isset($param['codmunicipio']) ? $param['codmunicipio'] : ''; 
    $filter = [];
    $filter[] = empty($param['codconcepto']) ? '' : " and (UPPER(a.codconcepto||'') LIKE UPPER('%".$param['codconcepto']."%') OR UPPER(b.desconcepto) LIKE UPPER('%".$param['codconcepto']."%')) ";

    $filter[] = empty($param['base_liquidacion']) ? '' : " and a.base_liquidacion = '".$param['base_liquidacion']."' ";
    $filter[] = empty($param['porc_aplica']) ? '' : " and a.porc_aplica = '".$param['porc_aplica']."' ";
    $filter[] = empty($param['aplica_ica_tercero']) ? '' : " and a.aplica_ica_tercero = '".$param['aplica_ica_tercero']."' ";
    $filter[] = empty($param['codconcepto_unique']) ? '' : " and a.codconcepto = '".$param['codconcepto_unique']."' ";
    
   
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

    $sql = "SELECT
    ".$limit."  
    a.codmunicipio,
    trim(c.depato)||' / '||trim(c.nombre) as codmunicipio_text,
    a.codconcepto,
    b.desconcepto as codconcepto_text,
    a.base_liquidacion,
    a.porc_aplica,
    a.aplica_ica_tercero
    FROM conceptos_munic as a, conceptos_mvto as b, municipios as c
    WHERE 
    (a.estado = 'A' OR a.estado IS NULL) AND
    a.codmunicipio = '{codmunicipio}' AND
    a.codconcepto = b.codconcepto AND
    a.codmunicipio = trim(c.coddpe)||trim(c.codigo)
    ".$filters."
    ORDER BY b.desconcepto ASC";

    $query = $this->p($sql, [
        'codmunicipio'=>$codmunicipio
    ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}


public function load_Municipios(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT trim(coddpe)||trim(codigo) as id, trim(depato)||' / '||trim(nombre) as text from municipios WHERE estado = 'A' AND (UPPER(depato) LIKE UPPER('%".$term."%') OR UPPER(nombre) LIKE UPPER('%".$term."%')) ORDER BY depato ASC";
    // echo $sql;
    $this->consultar($sql,__FUNCTION__);
}

public function load_ConceptoMvto(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT first 10 a.codconcepto as id, a.codconcepto || ' : ' || a.desconcepto as text FROM conceptos_mvto as a WHERE UPPER(a.codconcepto||'') LIKE UPPER('%".$term."%') OR UPPER(a.desconcepto) LIKE UPPER('%".$term."%') order by a.desconcepto ASC";
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
    $codmunicipio = isset($param['codmunicipio']) ? $param['codmunicipio'] : null;
    $codconcepto = isset($param['codconcepto']) ? $param['codconcepto'] : null;
    $usrmodi = isset($param['usrmodi']) ? $param['usrmodi'] : null;
    $fecmodi = isset($param['fecmodi']) ? $param['fecmodi'] : null;
    $horamod = isset($param['horamod']) ? $param['horamod'] : null;
    $sql="UPDATE conceptos_munic SET 
    estado = 'I', 
    usrmodi = '$usrmodi', 
    fecmodi = '$fecmodi',
    horamod = '$horamod'
    WHERE codconcepto = '".$codconcepto."' AND codmunicipio = '".$codmunicipio."' AND (estado = 'A' OR estado IS NULL) ";
    // echo $sql;
    return $this->consultar($sql, __FUNCTION__); 

}

}
