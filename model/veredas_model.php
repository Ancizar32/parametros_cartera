<?php

class veredas_model extends Odbc
{

    function __construct()
    { }

    /**
     * Esta funcion permitye validar que no exista el mismo codigo de la vereda para un 
     * departamento y un municipio
     */
    function validarcrear_veredas($coddpt, $codmun, $codver){
        $sql = "SELECT count(cod_vecor) as cont from veredas where cod_dpto='$coddpt' and cod_muni = '$codmun' and  cod_vecor= '$codver'";
        //echo "<pre>$sql</pre>";
        $this->consultar($sql, __FUNCTION__);
    }

    /**
     * Esta función permite crear un registro en la tabla puntoventa
     *
     * @param $array un arreglo con los indices y los valores de la tabla
     */
    function insert_veredas($array)
    {
        $insert = $this->crearSentenciaInsert("veredas", $array);
        //echo 'insert'.$insert;
        $this->consultar($insert, __FUNCTION__);
    }

    /**
     * Esta función permite actualizar un registro de la tabla municipio
     */
    function update_veredas($coddpt, $codmun, $codver, $array){
        $condiciones = array("cod_dpto" => "'$coddpt'", "cod_muni" => "'$codmun'", "cod_vecor" => "'$codver'");
        $update =  $this->crearSentenciaUpdate("veredas", $array, $condiciones);
        //echo 'update '.$update;
        $this->consultar($update);
    }

    /**
     * Esta función permite obtener los departamentos
     */
    function getDepartamentos(array $param){
        $term = isset($param['term']) ? $param['term'] : ""; 
        $sql="SELECT distinct coddpe as id, depato as text FROM municipios Where depato like '%".$term."%'  ";
        // echo "<pre>$sql</pre>";
        $this->consultar($sql, __FUNCTION__);
    }

    /**
     * Esta función permite obtener los departamentos
     */
    function get_municipios(array $param){
        $term = isset($param['term']) ? $param['term'] : ""; 
        $depto =  isset($param['depto']) ? $param['depto'] : ""; 
        $sql="SELECT distinct codigo as id, nombre as text FROM municipios Where coddpe='$depto' AND nombre like '%".$term."%'  ";
        //echo "<pre>$sql</pre>";
        $this->consultar($sql, __FUNCTION__);
    }

    /**
     * Esta función permite obtener los departamentos
     */
    function get_nombredepto($coddep){
        $sql = "SELECT distinct depato FROM municipios Where coddpe='$coddep' ";
        $this->consultar($sql, __FUNCTION__);
    }

    /**
     * Esta función permite obtener los municipios
     */
    function filtrar_veredas($coddpt, $codMun){
        $sql="SELECT cod_dpto,depato, cod_muni,nombre, cod_vecor, nom_vecor, v.impuesto, v.estado FROM veredas v, municipios  WHERE cod_dpto = coddpe and cod_muni = codigo and cod_vecor is not null ";
        if($coddpt != ""){
            $sql.= " AND cod_dpto='$coddpt'";
        }
        if($codMun != ""){
            $sql.= " AND cod_muni='$codMun'";
        }
        //echo "<pre>$sql</pre>";
        $this->consultar($sql, __FUNCTION__);
    }


    /**
     * Esta función permite crear una sentencia en sql para realizar un insert
     * @author Andres Felipe Abril Romero<andres.abril@ibg.com.co>
     * @param [String] $tabla nombre de la tabla 
     * @param $array un arreglo que contiene las llaves y los valores
     * @return la sentencia en sql
     */
    function crearSentenciaInsert($tabla,$array){
        $insert = " INSERT INTO $tabla ";
        $claves = "(" . implode(', ', array_keys($array)) . ")";
        $valores =" values (" .implode(', ', $array). ")";
        $insert = $insert . $claves . $valores;
        return $insert;
    }

    /**
     * Esta función permite crear una sentenciua en sql para realizar un update
     * @author Andres Felipe Abril Romero<andres.abril@ibg.com.co>
     * @param [type] $tabla
     * @param $array un arreglo que contiene las llaves y los valores 
     * @return la sentencia en sql
     */
    function crearSentenciaUpdate($tabla, $array, $condiciones){
        $update = " UPDATE $tabla SET ";
        $valores = array();
        $where = array();
        foreach ($array as $key => $value) {
            array_push($valores, " $key=$value ");
        }

        foreach ($condiciones as $key => $value) {
            array_push($where , " $key=$value ");
        }
        $update2 = implode(', ', $valores);
        $update3 = "WHERE ".implode(' AND ', $where);
        $update =$update.$update2.$update3;
        return $update;
    }

}
