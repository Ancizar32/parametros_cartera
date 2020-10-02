<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class contabilidad_model extends Odbc
{
    
    public function __construct()
    {
    }



public function load_data(array $param)
{
    $filter = [];
    $filter[] = empty($param['codigo']) ? '' : " and a.codigo = '".$param['codigo']."' ";
    $filter[] = empty($param['codctble']) ? '' : " and (a.codctble like '%".$param['codctble']."%' OR e.td_nombre like '%".$param['codctble']."%') ";
    $filter[] = empty($param['ctactble']) ? '' : " and (a.ctactble like '%".$param['ctactble']."%' OR c.pc_nomcue like '%".$param['ctactble']."%') ";
    $filter[] = empty($param['ctactble2']) ? '' : " and (a.ctactble2 like '%".$param['ctactble2']."%' OR d.pc_nomcue like '%".$param['ctactble2']."%') ";
    $filter[] = empty($param['descrip']) ? '' : " and a.descrip like '%".$param['descrip']."%' ";
    $filter[] = empty($param['estado']) ? '' : " and a.estado = '".$param['estado']."' ";
    $filter[] = empty($param['tipo']) ? '' : " and (a.tipo LIKE '%".$param['tipo']."%' OR b.tc_nomcom LIKE '%".$param['tipo']."%' )";
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
    $sql = "select ".$limit." a.codctble, e.td_nombre as codctble_text, a.codigo, a.ctactble, c.pc_nomcue as ctactble_text, a.ctactble2, d.pc_nomcue as ctactble2_text, a.descrip, a.estado, a.tipo, b.tc_nomcom from s3tiptrans as a 
    left join contab:compro as b ON a.tipo = b.tc_codcom
    left join contab:placue as c ON a.ctactble = c.pc_codcue
    left join contab:placue as d ON a.ctactble2 = d.pc_codcue
    left join contab:tipdoc as e ON a.codctble = e.td_codigo
    where  codigo IS NOT NULL ".$filters." order by CAST(codigo as INTEGER) DESC";
     // echo $sql;
    $this->consultar($sql,__FUNCTION__);
}


public function load_TipoCuenta(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT first 10 tc_codcom as id, tc_codcom || ' : ' || tc_nomcom as text FROM contab:compro WHERE tc_codcom LIKE '%".$term."%' OR tc_nomcom LIKE '%".$term."%' order by CAST(tc_codcom as INTEGER) ASC";
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

public function load_TipoDocumento(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT first 10 td_codigo as id, td_codigo || ' : ' || td_nombre as text FROM contab:tipdoc WHERE td_codigo LIKE '%".$term."%' OR td_nombre LIKE '%".$term."%' order by td_nombre ASC";
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

public function disableRecord($id){
    $sql="UPDATE s3tiptrans SET estado = 'I' WHERE codigo = '".$id."' ";
    $this->consultar($sql, __FUNCTION__);   
}

public function enableRecord($id){
    $sql="UPDATE s3tiptrans SET estado = 'A' WHERE codigo = '".$id."' ";
    $this->consultar($sql, __FUNCTION__);   
}

public function editRecord(array $param){

    $codctble = isset($param['codctble']) ? $param['codctble'] : null;
    $ctactble = isset($param['ctactble']) ? $param['ctactble'] : null;
    $ctactble2 = isset($param['ctactble2']) ? $param['ctactble2'] : null;
    $descrip = isset($param['descrip']) ? $param['descrip'] : null;
    $tipo = isset($param['tipo']) ? $param['tipo'] : null;
    $estado = isset($param['estado']) ? $param['estado'] : null;
    $codigo = isset($param['codigo']) ? $param['codigo'] : null;
     
    $sql="UPDATE s3tiptrans SET 
    codctble = '".trim($codctble)."',
    ctactble = '".trim($ctactble)."',
    ctactble2 = '".trim($ctactble2)."',
    descrip = '".trim($descrip)."',
    estado = '".trim($estado)."',
    tipo = '".trim($tipo)."' 
    WHERE codigo = '".$codigo."' ";
    // echo $sql;
    $this->consultar($sql, __FUNCTION__);   
}


}
