<?php

/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class migra_caja_model extends Odbc
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
    $filter[] = empty($param['codsucursal']) ? '' : " and a.codsucursal = '".$param['codsucursal']."' ";    
   
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
    a.codsucursal,
    c.dessucur,
    a.fecha_cuadre
    from (select
    a.codsucursal,
    a.fecha_cuadre
    from cuadre_caja as a
    LEFT JOIN (
    select
    CAST(a.mc_cencos AS INTEGER) as mc_cencos,
    a.mc_fecdoc
    FROM contab:movcon as a
    WHERE a.mc_fecdoc > '09172019'
    GROUP BY a.mc_cencos, a.mc_fecdoc
    order BY a.mc_fecdoc DESC
    ) as b ON a.codsucursal = b.mc_cencos AND a.fecha_cuadre = b.mc_fecdoc
    WHERE a.fecha_cuadre > '09172019' AND b.mc_cencos IS NULL AND b.mc_fecdoc IS NULL AND a.codtp_comprobante NOT IN ('88', '98', '134', '135') ".$filters."
    GROUP BY a.codsucursal, a.fecha_cuadre
    ORDER BY CAST(a.codsucursal AS INTEGER) ASC, a.fecha_cuadre DESC) as a
    LEFT JOIN scparamet as c ON a.codsucursal = c.codsucur";

    $query = $this->p($sql, []);
    $this->consultar($query,__FUNCTION__);
    return $query;
}


public function load_branch_office(array $param)
{
    $term = isset($param['term']) ? $param['term'] : '';
    $sql = "SELECT first 10 codigo as id, codigo || ' : ' || nombre as text FROM sucursales WHERE nombre LIKE '%".$term."%' OR codigo||'' LIKE '%".$term."%' AND estado = 'A' AND codregion IS NOT NULL order by nombre ASC ";
    // echo $sql;
    $this->consultar($sql,__FUNCTION__);
}

public function loadControlNumber(){
     $sql = "
     SELECT
     fc.numero_control,
     fc.codigo_interfaz,
     fc.comprobante_cont,
     fc.documento_cont,
     fc.agrupar_info,
     fc.modulo_interfaz,
     fc.tipo_cuenta,
     fc.cuenta_contra,
     fc.genera_numeracion,
     fc.cuenta_anexa
     FROM cnf_interfaz fc
     ORDER BY fc.genera_numeracion";
     $query = $this->p($sql, []);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoComprb(array $param){
    $numero_control = isset($param['numero_control']) ? $param['numero_control'] : '';
     $sql = "
     SELECT cp.codtp_comprobante
        FROM cnf_comprobantes cp
       WHERE cp.control_detalle = {numero_control}";
     $query = $this->p($sql, [
        'numero_control'=>$numero_control
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoConcep(array $param){
    $numero_control = isset($param['numero_control']) ? $param['numero_control'] : '';
     $sql = "
     SELECT cn.codconcepto
        FROM cnf_conceptos cn
       WHERE cn.control_detalle = {numero_control}";
     $query = $this->p($sql, [
        'numero_control'=>$numero_control
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoFormaP(array $param){
    $numero_control = isset($param['numero_control']) ? $param['numero_control'] : '';
     $sql = "
     SELECT fp.codforma_pago
        FROM cnf_formaspago fp
       WHERE fp.control_detalle = {numero_control}";
     $query = $this->p($sql, [
        'numero_control'=>$numero_control
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}


public function loadInfoArea(array $param){
    $numero_suc = isset($param['numero_suc']) ? $param['numero_suc'] : '';
     $sql = "
     SELECT UNIQUE(cc.codarea) as codarea
        FROM control_conceptos cc
       WHERE cc.codsucursal = {numero_suc}";
     $query = $this->p($sql, [
        'numero_suc'=>$numero_suc
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoVldDesc(array $param){
    $sucursal = isset($param['sucursal']) ? $param['sucursal'] : '';
    $concepto = isset($param['concepto']) ? $param['concepto'] : '';
     $sql = "
     SELECT MAX(ide_cuenta) AS idecuenta
     FROM cart_especiales
    WHERE ide_sucursal = {sucursal}
      AND ide_concepto = {concepto}
      AND ide_estado = 'A'";
     $query = $this->p($sql, [
        'sucursal'=>$sucursal,
        'concepto'=>$concepto,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadCajaComprobante(array $param){
    $p_comprobante = isset($param['p_comprobante']) ? $param['p_comprobante'] : '';
     $sql = "
     SELECT tc.afecta_caja AS v_caja_comprobante
     FROM tipos_comprobantes tc
    WHERE tc.codtp_comprobante = '{p_comprobante}' ";
     $query = $this->p($sql, [
        'p_comprobante'=>$p_comprobante,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadCajaFormapago(array $param){
    $p_forma_pago = isset($param['p_forma_pago']) ? $param['p_forma_pago'] : '';
     $sql = "
     SELECT fp.afecta_caja AS v_caja_formapago
     FROM formas_pago fp
    WHERE fp.codforma_pago = '{p_forma_pago}' ";
     $query = $this->p($sql, [
        'p_forma_pago'=>$p_forma_pago,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoAgrupaCompr(array $param){
    $p_numero_control = isset($param['p_numero_control']) ? $param['p_numero_control'] : '';
    $p_codtp_comprobante = isset($param['p_codtp_comprobante']) ? $param['p_codtp_comprobante'] : '';
     $sql = "
     SELECT MAX(cc.agrupa_concepto) AS v_agrupa_concepto
     FROM cnf_comprobantes cc
    WHERE cc.control_detalle = '{p_numero_control}'
      AND cc.codtp_comprobante = '{p_codtp_comprobante}' ";
     $query = $this->p($sql, [
        'p_numero_control'=>$p_numero_control,
        'p_codtp_comprobante'=>$p_codtp_comprobante,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoTipoCuenta(array $param){
    $p_codtp_comprobante = isset($param['p_codtp_comprobante']) ? $param['p_codtp_comprobante'] : '';
     $sql = "
     SELECT tc.nattp_comprobante, tc.tipo_causacion, tc.codcompania
     FROM tipos_comprobantes tc
    WHERE tc.codtp_comprobante = '{p_codtp_comprobante}' ";
     $query = $this->p($sql, [
        'p_codtp_comprobante'=>$p_codtp_comprobante,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoContableConcepto(array $param){
    $p_codconcepto = isset($param['p_codconcepto']) ? $param['p_codconcepto'] : '';
     $sql = "
     SELECT cm.cuenta_contable, cm.causa_gasto, cm.desconcepto
     FROM conceptos_mvto cm
    WHERE cm.codconcepto = '{p_codconcepto}' ";
     $query = $this->p($sql, [
        'p_codconcepto'=>$p_codconcepto,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoNitComp(array $param){
    $p_codcompania = isset($param['p_codcompania']) ? $param['p_codcompania'] : '';
     $sql = "
     SELECT cm.numnit AS v_nitcompania
     FROM companias cm
    WHERE cm.codigo = '{p_codcompania}' ";
     $query = $this->p($sql, [
        'p_codcompania'=>$p_codcompania,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoSucursal(array $param){
    $p_sucursal = isset($param['p_sucursal']) ? $param['p_sucursal'] : '';
     $sql = "
     SELECT sc.codigo AS v_sucursal, sc.codcuenta AS v_codcuenta
     FROM sucursales sc
    WHERE sc.codigo = '{p_sucursal}' ";
     $query = $this->p($sql, [
        'p_sucursal'=>$p_sucursal,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoDesTerceroP(array $param){
    $p_numeroidentifi = isset($param['p_numeroidentifi']) ? $param['p_numeroidentifi'] : '';
     $sql = "
     SELECT TRIM(tr.primer_nombre) AS v_nom1, TRIM(tr.segundo_nombre) AS v_nom2,
             TRIM(tr.primer_apellido) AS v_ape1, TRIM(tr.segundo_apellido) AS v_ape2,
             TRIM(tr.nombre) AS v_razonso
        FROM s2tmpprov tr
       WHERE tr.nit = '{p_numeroidentifi}' ";
     $query = $this->p($sql, [
        'p_numeroidentifi'=>$p_numeroidentifi,
     ]);
    $this->consultar($query,__FUNCTION__);

    return $query;
}

public function loadInfoDesTerceroC(array $param){
    $p_numeroidentifi = isset($param['p_numeroidentifi']) ? $param['p_numeroidentifi'] : '';
     $sql = "
     SELECT TRIM(cl.cacc_nombre) AS v_nombres
        FROM cacc cl
       WHERE cl.cacc_nitcli = '{p_numeroidentifi}' ";
     $query = $this->p($sql, [
        'p_numeroidentifi'=>$p_numeroidentifi,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoDesTerceroI(array $param){
    $p_numeroidentifi = isset($param['p_numeroidentifi']) ? $param['p_numeroidentifi'] : '';
     $sql = "
     SELECT TRIM(pr.prinombre) AS v_nom1, TRIM(pr.segnombre) AS v_nom2, TRIM(pr.priapel) AS v_ape1,
             TRIM(pr.segapel) AS v_ape2
        FROM personal pr
       WHERE pr.cedula = '{p_numeroidentifi}'
         AND pr.estado = 'A' ";
     $query = $this->p($sql, [
        'p_numeroidentifi'=>$p_numeroidentifi,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoContableCodCuenta(array $param){
    $p_identificac = isset($param['p_identificac']) ? $param['p_identificac'] : '';
     $sql = "
     SELECT te.cuenta_tercero AS v_codcuenta
        FROM s2tmpprov te
       WHERE te.nit = '{p_identificac}' ";
     $query = $this->p($sql, [
        'p_identificac'=>$p_identificac,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoContableCodCuentaBySuc(array $param){
    $p_codsucursal = isset($param['p_codsucursal']) ? $param['p_codsucursal'] : '';
     $sql = "
     SELECT sc.codcuenta AS v_codcuenta
        FROM sucursales sc
       WHERE sc.codigo = '{p_codsucursal}' ";
     $query = $this->p($sql, [
        'p_codsucursal'=>$p_codsucursal,
     ]);
    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoMvto(array $param){
    $p_codsucursal = isset($param['p_codsucursal']) ? $param['p_codsucursal'] : '';
    $p_fecha = isset($param['p_fecha']) ? $param['p_fecha'] : '';
    $cadena_areas = isset($param['cadena_areas']) ? $param['cadena_areas'] : '';
    $cadena_comprb = !empty($param['cadena_comprb']) ? " AND mv.codtp_comprobante IN (".$param['cadena_comprb'].") " : ' '; 
    $cadena_concep = !empty($param['cadena_concep']) ? " AND md.codconcepto IN (".$param['cadena_concep'].") " : ' ';
    $cadena_formap = !empty($param['cadena_formap']) ? " AND md.codforma_pago IN (".$param['cadena_formap'].") " : ' ';

    $sql = "
    SELECT 
    mv.consecutivo, 
    mv.codsucursal, 
    mv.codtp_comprobante, 
    mv.numero_comprobante, 
    mv.fecha_operacion,mv.tipo_identifica, 
    mv.numero_identifica, 
    mv.usrcrea, 
    mv.feccrea, 
    md.codconcepto, 
    mv.codsucursal, 
    md.numero_documento, 
    md.codforma_pago, 
    md.codbanco, 
    md.codcuenta, 
    md.base_liquida, 
    md.valor_concepto, 
    mv.observaciones, 
    md.documento_pago, 
    mv.fecha_registro, 
    tc.tipo_causacion, 
    tc.nattp_comprobante 
    FROM movimiento mv, movimiento_detalle md, tipos_comprobantes tc 
    WHERE mv.codsucursal = '{p_codsucursal}' AND mv.feccrea = '{p_fecha}' {cadena_comprb} {cadena_concep} {cadena_formap} AND mv.estado = 'A' AND mv.consecutivo = md.consec_mast AND mv.codtp_comprobante = tc.codtp_comprobante 
    ORDER BY mv.fecha_operacion, mv.codsucursal, tc.tipo_causacion DESC, tc.nattp_comprobante DESC, md.valor_concepto DESC ";

    if ($p_codsucursal == '1') {
        $sql = "
        SELECT 
        mv.consecutivo, 
        mv.codsucursal, 
        mv.codtp_comprobante, 
        mv.numero_comprobante, 
        mv.fecha_operacion, 
        mv.tipo_identifica, 
        mv.numero_identifica, 
        mv.usrcrea, 
        mv.feccrea, 
        md.codconcepto, 
        mv.codsucursal, 
        md.numero_documento, 
        md.codforma_pago, 
        md.codbanco, 
        md.codcuenta, 
        md.base_liquida, 
        md.valor_concepto, 
        mv.observaciones, 
        md.documento_pago, 
        mv.fecha_registro, 
        tc.tipo_causacion, 
        tc.nattp_comprobante 
        FROM movimiento mv, movimiento_detalle md, tipos_comprobantes tc 
        WHERE mv.feccrea = '{p_fecha}' AND 
        (mv.codsucursal = '{p_codsucursal}' OR mv.codsucursal IN ({cadena_areas})) {cadena_comprb} {cadena_concep} {cadena_formap} AND mv.estado = 'A' AND mv.consecutivo = md.consec_mast AND mv.codtp_comprobante = tc.codtp_comprobante 
        ORDER BY mv.fecha_operacion, mv.codsucursal, tc.tipo_causacion DESC, tc.nattp_comprobante DESC, md.valor_concepto DESC ";
        
    }

    $query = $this->p($sql, [
            'p_fecha'=>$p_fecha,
            'p_codsucursal'=>$p_codsucursal,
            'cadena_areas'=>$cadena_areas,
            'cadena_comprb'=>$cadena_comprb,
            'cadena_concep'=>$cadena_concep,
            'cadena_formap'=>$cadena_formap,
         ]);

    $this->consultar($query,__FUNCTION__);
    return $query;
}

public function loadInfoFact(array $param){
    $p_codsucursal = isset($param['p_codsucursal']) ? $param['p_codsucursal'] : '';
    $p_fecha = isset($param['p_fecha']) ? $param['p_fecha'] : '';
    $cadena_areas = isset($param['cadena_areas']) ? $param['cadena_areas'] : '';
    $cadena_comprb = !empty($param['cadena_comprb']) ? " AND fc.codtp_comprobante IN (".$param['cadena_comprb'].") " : ' '; 
    $cadena_concep = !empty($param['cadena_concep']) ? " AND fd.codconcepto IN (".$param['cadena_concep'].") " : ' ';

    $sql = "
    SELECT 
    fc.consecutivo, 
    fc.codsucur_pago, 
    fc.codtp_comprobante, 
    fc.numero_comprobante, 
    fc.fecha_factura, 
    fc.tipo_identifica, 
    fc.identificacion, 
    fc.usrcrea, 
    fc.feccrea, 
    fd.codconcepto, 
    fd.descripcion, 
    fc.codsucursal, 
    fc.numero_comprobante,
    1,
    1,
    1, 
    fd.vlr_base, 
    fd.valor_concepto
    FROM facturas fc, factura_detalle fd
    WHERE 
    fc.feccrea = '{p_fecha}'
    AND fc.codsucursal = '{p_codsucursal}' AND fc.estado = 'A' AND fc.consecutivo = fd.consecutivo ";

    if ($p_codsucursal == '1') {
        $sql = "
        SELECT fc.consecutivo, 
        fc.codsucur_pago, 
        fc.codtp_comprobante, 
        fc.numero_comprobante, 
        fc.fecha_factura, 
        fc.tipo_identifica, 
        fc.identificacion, 
        fc.usrcrea, 
        fc.feccrea, 
        fd.codconcepto, 
        fd.descripcion, 
        fc.codsucursal, 
        fc.numero_comprobante, 
        1,1,1, 
        fd.vlr_base, 
        fd.valor_concepto 
        FROM facturas fc, factura_detalle fd
        WHERE fc.feccrea = '{p_fecha}'
        AND ( fc.codsucursal = '{p_codsucursal}'
        OR fc.codsucursal IN ({cadena_areas})) AND fc.estado = 'A' AND fc.consecutivo = fd.consecutivo ";
        
    }

    $query = $this->p($sql, [
            'p_fecha'=>$p_fecha,
            'p_codsucursal'=>$p_codsucursal,
            'cadena_areas'=>$cadena_areas,
         ]);

    $this->consultar($query,__FUNCTION__);
    return $query;
}



}
