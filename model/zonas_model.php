<?php

class zonas_model extends Odbc
{

    function __construct()
    { }

    /**
     * Esta función permite validar la existencia de una zona por el codigo y por la sucursal
     * @param [String] $sucursal codigo de la sucursal
     * @param [Integer] $codigopt codigo de la zona
     */
    function get_existe_zona($codigopt, $sucursal)
    {
        $sql = "SELECT count(codigopt) as cont
        FROM puntoventa WHERE codigopt=$codigopt and  codsucur='$sucursal'";
        //echo "<pre>$sql</pre>";
        $this->consultar($sql, __FUNCTION__);
    }

    /**
     * Esta función permite validar la existencia de una zona por el codigo, la sucursal y la ubicación
     * @param [String] $sucursal codigo de la sucursal
     * @param [Integer] $codigopt codigo de la zona
     */
    function get_existe_zona_ubic($codigopt, $sucursal, $coddpto, $codmuni, $codvereda)
    {
        $sql = "SELECT count(codigopt) as cont
        FROM puntoventa WHERE codigopt=$codigopt and  codsucur='$sucursal' AND coddepto=$coddpto AND municipi=$codmuni";
        if($codvereda !="N/A"){
            $sql2 = " AND cod_vecor=$codvereda";
            $sql= $sql.$sql2;
        }
        //echo "<pre>$sql</pre>";
        $this->consultar($sql, __FUNCTION__);
    }

    /**
     * Esta función permite validar si existe un cobrador para alguna zona
     * @param [String] $cobrador la cedula del cobrador
     */
    function get_existe_cobrador($cobrador){
        $sql = "SELECT count(codigopt) as cont
        FROM puntoventa WHERE cobrador='$cobrador'";
        //echo "<pre>$sql</pre>";
        $this->consultar($sql, __FUNCTION__);
    }

    function filtrar_punto_venta($sucursal, $codigopt, $cobrador)
    {
        $sql = "SELECT s.nombre as sucur,codigopt, descptov,pro_personal_nombre(cobrador) as cobrador, m.depato as depto,listamad_cont, listaele_cont, listamad, listaele,
        pv.coddepto,pv.codsucur  FROM puntoventa pv, sucursales s, municipios m, personal p WHERE pv.codsucur=s.codigo and m.codigo=pv.municipi and m.coddpe=pv.coddepto and p.cedula=pv.cobrador
        and p.estado IN ( 'A', 'V' )";
        $where ="";
        if ($sucursal != '') {
            $where = " and  s.codigo='$sucursal'";
        }
        if($codigopt !=''){
            $where = $where." and codigopt=$codigopt";
        }
        if($cobrador !=''){
            $where = $where." and pv.cobrador='$cobrador'";
        }
        $orderby = " group by 1,2,3,4,5,6,7 ,8,9,10,11 order by 1,2";
        $consulta = $sql . $where . $orderby;
        //echo "<pre>$consulta</pre>";
        $this->consultar($consulta, __FUNCTION__);
    }

    /**
     * Esta función permite obtener los indicadores de madera y electronica para los municipios seleccionados en pantalla
     */
    function get_madera_electronica($depto,$muni){
        $sql="SELECT madera, electronica, madera_cont, electr_cont  FROM municipios where coddpe=$depto and codigo=$muni";
        //echo "<pre>$consulta</pre>";
        $this->consultar($sql, __FUNCTION__);

    }

    /**
     * Esta función permite crear un registro en la tabla puntoventa
     *
     * @param $array un arreglo con los indices y los valores de la tabla
     */
    function insert_zonas($array)
    {
        $insert = $this->crearSentenciaInsert("puntoventa", $array);
        //echo 'insert'.$insert;
        $this->consultar($insert, __FUNCTION__);
    }

    /**
     * Esta función permite actualizar el resultado del proceso  en recaudos en el banco
     */
    function update_zonas($array, $codzona, $sucursal)
    {
        $condiciones = array("codigopt" => $codzona, "codsucur" => $sucursal);
        $update =  $this->crearSentenciaUpdate("puntoventa", $array, $condiciones);
        //echo 'update '.$update;
        $this->consultar($update);
    }

    /**
     * Esta función permite eliminar las zonas correspodientes a unas condiciones
     */
    function delete_zonas($condiciones){
        $delete = $this->crearSentenciaDelete("puntoventa", $condiciones);
        //echo 'delete '.$delete;
        $this->consultar($delete);
    }
    
    /**
     * Esta función permite obtener la fecha de cierre para la sucursal de la zona
     */
    function obtener_fecha_cierre($sucursal){
        $sql = "SELECT to_char(MAX(s3anactas.feccie)) as feccie FROM s3anactas WHERE s3anactas.sucurs = $sucursal";
        $this->consultar($sql);
    }

    /**
     * Esta función permite validar la zona en s3anactas 
     */
    function validar_eliminar_zona($codzona,$sucursal,$feccie,$coddpto, $codmuni, $codvereda){
        $sql = "SELECT COUNT(*) as cont FROM s3anactas WHERE s3anactas.sucurs = $sucursal
         AND s3anactas.zona = $codzona AND s3anactas.feccie = $feccie AND s3anactas.coddpto =$coddpto AND s3anactas.codmuni=$codmuni AND s3anactas.saldo > 0 ";
        if($codvereda !=""){
            $sql2 = " AND s3anactas.codvereda=$codvereda";
            $sql= $sql.$sql2;
        }
        $this->consultar($sql);
    }

    /**
     * Esta función permite validar la zona en s3anactas 
     */
    function validar_eliminar_zonas($codzona,$sucursal,$feccie){
        $sql = "SELECT COUNT(*) as cont FROM s3anactas WHERE s3anactas.sucurs = $sucursal
         AND s3anactas.zona = $codzona AND s3anactas.feccie = $feccie AND s3anactas.saldo > 0 ";
        $this->consultar($sql);
    }
    /**
     * Esta función permite obtener los codigos de las zonas registradas en la base de datos
     */
    function getCodigoZonas($sucursal){
        $req = "SELECT distinct codigopt FROM puntoventa ";
        if(isset($sucursal)){
            $where = " WHERE codsucur=$sucursal ";
            $req = $req.$where;
        }
        $orderby = " ORDER BY 1";
        $req = $req.$orderby;
        //echo "<pre>$req</pre>";
        $this->consultar($req, __FUNCTION__);
    }

    /**
     * Esta función permite obtener la información correspondiente a una zona 
     */
    function getZona($codigo, $sucursal){
        $req = "SELECT   descptov, cobrador FROM puntoventa WHERE codigopt=$codigo AND codsucur=$sucursal  GROUP BY codigopt, descptov, codsucur, cobrador";
        //echo "<pre>$req</pre>";
        $this->consultar($req, __FUNCTION__);
    }

    /**
     * Esta función permite obtener la información correspondiente los departamentos municipios y veredas de una zona 
     */
    function getZonas($codigo, $sucursal){
        $req = "SELECT pv.codigopt, pv.coddepto, m.depato as depto, pv.municipi, m.nombre, pv.cod_vecor, case when pv.cod_vecor is not null then (select nom_vecor from veredas v where v.cod_vecor=pv.cod_vecor and v.cod_dpto= pv.coddepto and pv.municipi=cod_muni ) else '' end as vereda
        FROM puntoventa pv, sucursales s, municipios m WHERE pv.codigopt=$codigo and pv.codsucur=$sucursal and m.codigo=pv.municipi and m.coddpe=pv.coddepto group by 1,2,3,4,5,6";
        //echo "<pre>$req</pre>";
        $this->consultar($req, __FUNCTION__);
    }
    /**
     * Esta función permite obtener las sucursales que se encuentran activas y cuyo nombre
     * es diferente de null y diferente de vacio 
     */
    function getSucursales($sucursal){
        $sql = "SELECT codigo, nombre FROM  sucursales WHERE nombre <> '' AND estado='A' 
                AND nombre IS NOT NULL  AND codregion IS NOT NULL ";
        $where ="";
        if(isset($sucursal)){
            $where = " AND codigo='$sucursal'";
        }
        $orderby = "ORDER BY codigo";
        $consulta = $sql . $where . $orderby;
        //echo "<pre>$consulta</pre>";
        $this->consultar($consulta, __FUNCTION__);
    }

    /**
     * Esta función permite obtener los cobradores
     *
     * @param [String] $cargos cargos a los cuales puede acceder la persona que ejecuta la acción
     * @param [Integer] $sucursal sucursal a la cual pertenece la persona que ejecuta la acción
     * @return void
     */
    function getCobradores($cargos, $sucursal){
        $req = "SELECT cedula, pro_personal_nombre(cedula) as nombre FROM  personal WHERE codcargo IN ($cargos) and estado IN ( 'A', 'V' )  ";
        if(isset($sucursal)){
            $where = " AND codsucur=$sucursal ";
            $req = $req.$where;
        }
        $orderby = "  order by nombre ";
        $req = $req.$orderby;
        //echo "<pre>$req</pre>";
        $this->consultar($req, __FUNCTION__);
    }

    /**
     * Esta función permite obtener los departamentos
     */
    function getDepartamentos(){
        $sql="SELECT distinct coddpe, depato FROM municipios ";
        //echo "<pre>$req</pre>";
        $this->consultar($sql, __FUNCTION__);
    }

    /**
     * Esta función permite obtener los municipios
     */
    function getMunicipios($coddpt){
        $sql="SELECT codigo, nombre FROM municipios WHERE coddpe='$coddpt'";
        //echo "<pre>$req</pre>";
        $this->consultar($sql, __FUNCTION__);
    }

    /**
     * Esta función permite obtner las veredas o los corregimientos de un departamento y municipio
     */
    function getVereda($coddpt, $codmun){
        $sql="SELECT cod_vecor, nom_vecor FROM veredas WHERE cod_dpto='$coddpt' and cod_muni='$codmun'";
         //echo "<pre>$req</pre>";
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

    /**
     * Esta función permite crear una sentenciua en sql para realizar un update
     * @author Andres Felipe Abril Romero<andres.abril@ibg.com.co>
     * @param [type] $tabla
     * @param $array un arreglo que contiene las llaves y los valores 
     * @return la sentencia en sql
     */
    function crearSentenciaDelete($tabla,$condiciones){
        $delete = " DELETE FROM $tabla ";
        $where = array();
        foreach ($condiciones as $key => $value) {
            array_push($where , " $key=$value ");
        }
        $delete2 = "WHERE ".implode(' AND ', $where);
        $delete =$delete.$delete2;
        return $delete;
    }

}
