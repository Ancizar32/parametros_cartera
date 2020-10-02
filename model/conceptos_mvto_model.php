<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class conceptos_mvto_model extends Odbc
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
    $filter[] = empty($param['codconcepto']) ? '' : " and a.codconcepto = '".$param['codconcepto']."' ";
    $filter[] = empty($param['desconcepto']) ? '' : " and a.desconcepto = '".$param['desconcepto']."' ";
    $filter[] = empty($param['aplica_tabla_porc']) ? '' : " and a.aplica_tabla_porc = '".$param['aplica_tabla_porc']."' ";
    $filter[] = empty($param['vlr_base']) ? '' : " and a.vlr_base = '".$param['vlr_base']."' ";
    $filter[] = empty($param['aplica_cree']) ? '' : " and a.aplica_cree = '".$param['aplica_cree']."' ";
    $filter[] = empty($param['causa_gasto']) ? '' : " and a.causa_gasto = '".$param['causa_gasto']."' ";
    $filter[] = empty($param['codtipo_cuenta']) ? '' : " and a.codtipo_cuenta = '".$param['codtipo_cuenta']."' ";
    $filter[] = empty($param['cruza_rodamiento']) ? '' : " and a.cruza_rodamiento = '".$param['cruza_rodamiento']."' ";
    $filter[] = empty($param['cuenta_contable']) ? '' : " and (a.cuenta_contable LIKE '%".$param['cuenta_contable']."%' OR c.pc_nomcue LIKE '%".$param['cuenta_contable']."%') ";
    $filter[] = empty($param['impto_finan']) ? '' : " and a.impto_finan = '".$param['impto_finan']."' ";
    $filter[] = empty($param['modulo_uso']) ? '' : " and a.modulo_uso = '".$param['modulo_uso']."' ";
    $filter[] = empty($param['naturaleza']) ? '' : " and a.naturaleza = '".$param['naturaleza']."' ";
    $filter[] = empty($param['porc_aplica']) ? '' : " and a.porc_aplica = '".$param['porc_aplica']."' ";
    $filter[] = empty($param['porc_aplica2']) ? '' : " and a.porc_aplica2 = '".$param['porc_aplica2']."' ";
    $filter[] = empty($param['solicita_centro_co']) ? '' : " and a.solicita_centro_co = '".$param['solicita_centro_co']."' ";
    $filter[] = empty($param['solicita_codcuenta']) ? '' : " and a.solicita_codcuenta = '".$param['solicita_codcuenta']."' ";
    $filter[] = empty($param['solicita_det_fact']) ? '' : " and a.solicita_det_fact = '".$param['solicita_det_fact']."' ";
    $filter[] = empty($param['solicita_documento']) ? '' : " and a.solicita_documento = '".$param['solicita_documento']."' ";
    $filter[] = empty($param['solicita_tercero']) ? '' : " and a.solicita_tercero = '".$param['solicita_tercero']."' ";

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
            a.desconcepto, 
            a.aplica_tabla_porc, 
            a.vlr_base,
            a.aplica_cree, 
            a.causa_gasto, 
            a.codtipo_cuenta, 
            a.cruza_rodamiento, 
            a.cuenta_contable, 
            c.pc_nomcue as cuenta_contable_text,
            a.impto_finan, 
            a.modulo_uso, 
            a.naturaleza, 
            a.porc_aplica, 
            a.porc_aplica2, 
            a.solicita_centro_co, 
            a.solicita_codcuenta, 
            a.solicita_det_fact, 
            a.solicita_documento, 
            a.solicita_tercero
            from conceptos_mvto as a
            left join contab:placue as c ON a.cuenta_contable = c.pc_codcue        
            WHERE 1 = 1 ".$filters."
            order by a.codconcepto DESC";
     // echo $filters;
    $this->consultar($sql,__FUNCTION__);
}


public function loadNextNumber()
{
    $sql = "SELECT
        MIN(a.codconcepto) + 1 as last 
        FROM conceptos_mvto as a
        LEFT JOIN conceptos_mvto as b
        ON a.codconcepto = b.codconcepto - 1
        WHERE
        b.codconcepto IS NULL";
    // echo $sql;
    $this->consultar($sql,__FUNCTION__);
}

public function load_CuentaContable(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT first 10 pc_codcue as id, pc_codcue || ' : ' || pc_nomcue as text FROM contab:placue WHERE pc_codcue LIKE '%".$term."%' OR pc_nomcue LIKE '%".$term."%' order by pc_nomcue ASC";
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

public function validRemove(array $param){
    $codconcepto = isset($param['codconcepto']) ? $param['codconcepto'] : null;

    $sql = "SELECT COUNT(*) AS cnt 
        FROM movimiento_detalle
       WHERE movimiento_detalle.codconcepto = '{codconcepto}'";

    $query = $this->p($sql, [
        'codconcepto'=>$codconcepto,
    ]);
    // echo $query;
    $this->consultar($query,__FUNCTION__);
}


public function deleteRecord(array $param){
    $codconcepto = isset($param['codconcepto']) ? $param['codconcepto'] : null;
    $sql="DELETE FROM conceptos_mvto 
     WHERE codconcepto = '{codconcepto}' ";

     $query = $this->p($sql, [
        'codconcepto'=>$codconcepto,
    ]);
     // echo $query;
    $this->consultar($query,__FUNCTION__); 
}


 function editRecord(array $param){

    $codconcepto = isset($param['codconcepto']) ? $param['codconcepto'] : null;
    $desconcepto = isset($param['desconcepto']) ? $param['desconcepto'] : null;
    $aplica_tabla_porc = isset($param['aplica_tabla_porc']) ? $param['aplica_tabla_porc'] : null;
    $vlr_base = isset($param['vlr_base']) ? $param['vlr_base'] : null;
    $aplica_cree = isset($param['aplica_cree']) ? $param['aplica_cree'] : null;
    $causa_gasto = isset($param['causa_gasto']) ? $param['causa_gasto'] : null;
    $codtipo_cuenta = isset($param['codtipo_cuenta']) ? $param['codtipo_cuenta'] : null;
    $cruza_rodamiento = isset($param['cruza_rodamiento']) ? $param['cruza_rodamiento'] : null;
    $impto_finan = isset($param['impto_finan']) ? $param['impto_finan'] : null;
    $modulo_uso = isset($param['modulo_uso']) ? $param['modulo_uso'] : null;
    $naturaleza = isset($param['naturaleza']) ? $param['naturaleza'] : null;
    $porc_aplica = isset($param['porc_aplica']) ? $param['porc_aplica'] : null;
    $porc_aplica2 = isset($param['porc_aplica2']) ? $param['porc_aplica2'] : null;
    $solicita_centro_co = isset($param['solicita_centro_co']) ? $param['solicita_centro_co'] : null;
    $solicita_codcuenta = isset($param['solicita_codcuenta']) ? $param['solicita_codcuenta'] : null;
    $solicita_det_fact = isset($param['solicita_det_fact']) ? $param['solicita_det_fact'] : null;
    $solicita_documento = isset($param['solicita_documento']) ? $param['solicita_documento'] : null;
    $solicita_tercero = isset($param['solicita_tercero']) ? $param['solicita_tercero'] : null;
    
    $sql="UPDATE conceptos_mvto SET 
    desconcepto = '".trim($desconcepto)."', 
    aplica_tabla_porc = '".trim($aplica_tabla_porc)."', 
    vlr_base = '".trim($vlr_base)."', 
    aplica_cree = '".trim($aplica_cree)."', 
    causa_gasto = '".trim($causa_gasto)."', 
    codtipo_cuenta = '".trim($codtipo_cuenta)."', 
    cruza_rodamiento = '".trim($cruza_rodamiento)."', 
    impto_finan = '".trim($impto_finan)."', 
    modulo_uso = '".trim($modulo_uso)."', 
    naturaleza = '".trim($naturaleza)."', 
    porc_aplica = '".trim($porc_aplica)."', 
    porc_aplica2 = '".trim($porc_aplica2)."', 
    solicita_centro_co = '".trim($solicita_centro_co)."', 
    solicita_codcuenta = '".trim($solicita_codcuenta)."', 
    solicita_det_fact = '".trim($solicita_det_fact)."', 
    solicita_documento = '".trim($solicita_documento)."', 
    solicita_tercero = '".trim($solicita_tercero)."' 
    WHERE codconcepto = '".$codconcepto."' ";
    // echo $sql;
    $this->consultar($sql, __FUNCTION__);   
}


}
