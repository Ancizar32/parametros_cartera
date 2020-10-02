<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class conceptos_mvto_view extends VistaBase
{

/**
* Pintar la pantalla principal
*/
function dibujar()
{
?>
<link rel="stylesheet" type="text/css" href="resources/css/contabilidad.css"/>
<link rel="stylesheet" type="text/css" href="resources/lib/DataTables/datatables.min.css"/>
<link rel="stylesheet" type="text/css" href="resources/lib/bootstrap-4.5.2/dist/css/bootstrap.min.css">
<script type="text/javascript" src="resources/lib/bootstrap-4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="resources/lib/DataTables/datatables.min.js"></script>

<div class="container-fluid" id="bodyview">
    <div class="card">
    <h5 class="card-header"><span class="text-center">Conceptos de moviemiento</span></h5>
        <div class="card-body">
            <div class="form-row" id="main_buttons">
                <div class="form-group col-sm-6">
                    <form class="form-inline">
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle mr-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Opciones
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" id="create_button">Nuevo concepto de moviemiento</a>
                            <a class="dropdown-item" href="#" id="edit_button">Editar concepto de moviemiento</a>
                            <a class="dropdown-item" href="#" id="disable_button">Eliminar concepto de moviemiento</a>
                            <a class="dropdown-item" href="#" id="select_all">Seleccionar Todo</a>
                            <a class="dropdown-item" href="#" id="deselect_all">Deseleccionar Todo</a>
                          </div>
                        </div>
                        <button id="advance_search_button" type="button" class="btn btn-primary mr-4 my-2">Busqueda avanzada</button>
                    </form>
                </div> 
            </div>

            <table id="table" class="table table-bordered dt-responsive" style="width:100%"></table>

        </div>
    </div>
</div>


        <div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div id="advanceSearch_modal_concept_mov">
             <div class="form-row">
              <div class="form-group col-sm-4">
                <label for="codconcepto_advance" class="col-form-label">C&oacute;digo concepto</label>
                <input type="text" class="form-control" id="codconcepto_advance" >
              </div>
              <div class="form-group col-sm-8">
                <label for="desconcepto_advance" class="col-form-label">Descripción concepto</label>
                <input type="text" class="form-control" id="desconcepto_advance" >
              </div>
              <div class="form-group col-sm-4">
                <label for="aplica_tabla_porc_advance" class="col-form-label">Aplicar % de la tabla de bases(S/N) o Municipios (M)</label>
                <div id="aplica_tabla_porc_advance_content">
                  <select class="form-control" id="aplica_tabla_porc_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                    <option value="M">Municipios</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="vlr_base_advance" class="col-form-label">Valor base de liquidaci&oacute;n</label>
                <input type="number" class="form-control" id="vlr_base_advance" >
              </div>
              <div class="form-group col-sm-4">
                <label for="porc_aplica_advance" class="col-form-label">Porcentaje a aplicar</label>
                <input type="number" class="form-control" id="porc_aplica_advance" >
              </div>
              <div class="form-group col-sm-4">
                <label for="porc_aplica2_advance" class="col-form-label">Otro Porcentaje a aplicar</label>
                <input type="number" class="form-control" id="porc_aplica2_advance" >
              </div>
              <div class="form-group col-sm-4">
                <label for="solicita_tercero_advance" class="col-form-label">Solicitar identificación del tercero?</label>
                <div id="solicita_tercero_advance_content">
                  <select class="form-control" id="solicita_tercero_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="impto_finan_advance" class="col-form-label">Aplica para Impuesto Financiero?</label>
                <div id="impto_finan_advance_content">
                  <select class="form-control" id="impto_finan_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="solicita_documento_advance" class="col-form-label">Solicitar n&uacute;mero de documento</label>
                <div id="solicita_documento_advance_content">
                  <select class="form-control" id="solicita_documento_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="solicita_centro_co_advance" class="col-form-label">Solicitar centro de costos</label>
                <div id="solicita_centro_co_advance_content">
                  <select class="form-control" id="solicita_centro_co_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="solicita_det_fact_advance" class="col-form-label">Solicitar detalle de los productos de la factura(S/N)</label>
                <div id="solicita_det_fact_advance_content">
                  <select class="form-control" id="solicita_det_fact_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="solicita_codcuenta_advance" class="col-form-label">Solicitar código de la cuenta del banco</label>
                <div id="solicita_codcuenta_advance_content">
                  <select class="form-control" id="solicita_codcuenta_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="modulo_uso_advance" class="col-form-label">Aplicar en movimiento caja o en causación facturas</label>
                <div id="modulo_uso_advance_content">
                  <select class="form-control" id="modulo_uso_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="M">Movimiento</option>
                    <option value="F">Factura</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="cruza_rodamiento_advance" class="col-form-label">Maneja rodamientos de la compañía</label>
                <div id="cruza_rodamiento_advance_content">
                  <select class="form-control" id="cruza_rodamiento_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-6">
                <label for="cuenta_contable_advance" class="col-form-label">Cuenta contable</label>
                <input type="text" class="form-control" id="cuenta_contable_advance">
              </div>
              <div class="form-group col-sm-4">
                <label for="aplica_cree_advance" class="col-form-label">Concepto aplica impuesto CREE</label>
                <div id="aplica_cree_advance_content">
                  <select class="form-control" id="aplica_cree_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-2">
                <label for="codtipo_cuenta_advance" class="col-form-label">Tipo de cuenta del concepto</label>
                <div id="codtipo_cuenta_advance_content">
                  <select class="form-control" id="codtipo_cuenta_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select>
                </div>
              </div> 
              <div class="form-group col-sm-4">
                <label for="causa_gasto_advance" class="col-form-label">Causar gasto por la sucursal?</label>
                <div id="causa_gasto_advance_content">
                  <select class="form-control" id="causa_gasto_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="S">Si</option>
                    <option value="N">No</option>
                  </select>
                </div>
              </div>
              <div class="form-group col-sm-4">
                <label for="naturaleza_advance" class="col-form-label">Concepto de comprobante</label>
                <div id="naturaleza_advance_content">
                  <select class="form-control" id="naturaleza_advance" >
                    <option value="">-- Seleccionar --</option>
                    <option value="C">Crédito</option>
                    <option value="D">Débito</option>
                  </select>
                </div>
              </div>
           </div>
          </div>

          <div id="newRecord_modal_concept_mov">
             <div class="form-row">
                <div class="form-group col-sm-4">
                  <label for="codconcepto" class="col-form-label">C&oacute;digo concepto</label>
                  <input type="text" class="form-control" id="codconcepto" >
                </div>
                <div class="form-group col-sm-8">
                  <label for="desconcepto" class="col-form-label">Descripción concepto</label>
                  <input type="text" class="form-control" id="desconcepto" >
                </div>
                <div class="form-group col-sm-4">
                  <label for="aplica_tabla_porc" class="col-form-label">Aplicar % de la tabla de bases(S/N) o Municipios (M)</label>
                  <div id="aplica_tabla_porc_content">
                    <select class="form-control" id="aplica_tabla_porc" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                      <option value="M">Municipios</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="vlr_base" class="col-form-label">Valor base de liquidaci&oacute;n</label>
                  <input type="number" class="form-control" id="vlr_base" >
                </div>
                <div class="form-group col-sm-4">
                  <label for="porc_aplica" class="col-form-label">Porcentaje a aplicar</label>
                  <input type="number" class="form-control" id="porc_aplica" >
                </div>
                <div class="form-group col-sm-4">
                  <label for="porc_aplica2" class="col-form-label">Otro Porcentaje a aplicar</label>
                  <input type="number" class="form-control" id="porc_aplica2" >
                </div>
                <div class="form-group col-sm-4">
                  <label for="solicita_tercero" class="col-form-label">Solicitar identificación del tercero?</label>
                  <div id="solicita_tercero_content">
                    <select class="form-control" id="solicita_tercero" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="impto_finan" class="col-form-label">Aplica para Impuesto Financiero?</label>
                  <div id="impto_finan_content">
                    <select class="form-control" id="impto_finan" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="solicita_documento" class="col-form-label">Solicitar n&uacute;mero de documento</label>
                  <div id="solicita_documento_content">
                    <select class="form-control" id="solicita_documento" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="solicita_centro_co" class="col-form-label">Solicitar centro de costos</label>
                  <div id="solicita_centro_co_content">
                    <select class="form-control" id="solicita_centro_co" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="solicita_det_fact" class="col-form-label">Solicitar detalle de los productos de la factura(S/N)</label>
                  <div id="solicita_det_fact_content">
                    <select class="form-control" id="solicita_det_fact" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="solicita_codcuenta" class="col-form-label">Solicitar código de la cuenta del banco</label>
                  <div id="solicita_codcuenta_content">
                    <select class="form-control" id="solicita_codcuenta" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="modulo_uso" class="col-form-label">Aplicar en movimiento caja o en causación facturas</label>
                  <div id="modulo_uso_content">
                    <select class="form-control" id="modulo_uso" >
                      <option value="">-- Seleccionar --</option>
                      <option value="M">Movimiento</option>
                      <option value="F">Factura</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="cruza_rodamiento" class="col-form-label">Maneja rodamientos de la compañía</label>
                  <div id="cruza_rodamiento_content">
                    <select class="form-control" id="cruza_rodamiento" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-6">
                  <label for="cuenta_contable" class="col-form-label">Cuenta contable</label>
                  <div id="cuenta_contable_content">
                    <select class="form-control" id="cuenta_contable"></select>
                  </div>   
                </div>
                <div class="form-group col-sm-4">
                  <label for="aplica_cree" class="col-form-label">Concepto aplica impuesto CREE</label>
                  <div id="aplica_cree_content">
                    <select class="form-control" id="aplica_cree" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-2">
                  <label for="codtipo_cuenta" class="col-form-label">Tipo de cuenta del concepto</label>
                  <div id="codtipo_cuenta_content">
                    <select class="form-control" id="codtipo_cuenta" >
                      <option value="">-- Seleccionar --</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                    </select>
                  </div>
                </div> 
                <div class="form-group col-sm-4">
                  <label for="causa_gasto" class="col-form-label">Causar gasto por la sucursal?</label>
                  <div id="causa_gasto_content">
                    <select class="form-control" id="causa_gasto" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="naturaleza" class="col-form-label">Concepto de comprobante</label>
                  <div id="naturaleza_content">
                    <select class="form-control" id="naturaleza" >
                      <option value="">-- Seleccionar --</option>
                      <option value="C">Crédito</option>
                      <option value="D">Débito</option>
                    </select>
                  </div>
                </div>
             </div>
          </div>

          <div class="form-row" id="editRecord_modal_concept_mov">
            <div class="form-row">
                <div class="form-group col-sm-4">
                  <label for="codconcepto_edit" class="col-form-label">C&oacute;digo concepto</label>
                  <input type="text" class="form-control" id="codconcepto_edit" readOnly>
                </div>
                <div class="form-group col-sm-8">
                  <label for="desconcepto_edit" class="col-form-label">Descripción concepto</label>
                  <input type="text" class="form-control" id="desconcepto_edit" >
                </div>
                <div class="form-group col-sm-4">
                  <label for="aplica_tabla_porc_edit" class="col-form-label">Aplicar % de la tabla de bases(S/N) o Municipios (M)</label>
                  <div id="aplica_tabla_porc_edit_content">
                    <select class="form-control" id="aplica_tabla_porc_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                      <option value="M">Municipios</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="vlr_base_edit" class="col-form-label">Valor base de liquidaci&oacute;n</label>
                  <input type="number" class="form-control" id="vlr_base_edit" >
                </div>
                <div class="form-group col-sm-4">
                  <label for="porc_aplica_edit" class="col-form-label">Porcentaje a aplicar</label>
                  <input type="number" class="form-control" id="porc_aplica_edit" >
                </div>
                <div class="form-group col-sm-4">
                  <label for="porc_aplica2_edit" class="col-form-label">Otro Porcentaje a aplicar</label>
                  <input type="number" class="form-control" id="porc_aplica2_edit" >
                </div>
                <div class="form-group col-sm-4">
                  <label for="solicita_tercero_edit" class="col-form-label">Solicitar identificación del tercero?</label>
                  <div id="solicita_tercero_edit_content">
                    <select class="form-control" id="solicita_tercero_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="impto_finan_edit" class="col-form-label">Aplica para Impuesto Financiero?</label>
                  <div id="impto_finan_edit_content">
                    <select class="form-control" id="impto_finan_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="solicita_documento_edit" class="col-form-label">Solicitar n&uacute;mero de documento</label>
                  <div id="solicita_documento_edit_content">
                    <select class="form-control" id="solicita_documento_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="solicita_centro_co_edit" class="col-form-label">Solicitar centro de costos</label>
                  <div id="solicita_centro_co_edit_content">
                    <select class="form-control" id="solicita_centro_co_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="solicita_det_fact_edit" class="col-form-label">Solicitar detalle de los productos de la factura(S/N)</label>
                  <div id="solicita_det_fact_edit_content">
                    <select class="form-control" id="solicita_det_fact_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="solicita_codcuenta_edit" class="col-form-label">Solicitar código de la cuenta del banco</label>
                  <div id="solicita_codcuenta_edit_content">
                    <select class="form-control" id="solicita_codcuenta_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="modulo_uso_edit" class="col-form-label">Aplicar en movimiento caja o en causación facturas</label>
                  <div id="modulo_uso_edit_content">
                    <select class="form-control" id="modulo_uso_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="M">Movimiento</option>
                      <option value="F">Factura</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="cruza_rodamiento_edit" class="col-form-label">Maneja rodamientos de la compañía</label>
                  <div id="cruza_rodamiento_edit_content">
                    <select class="form-control" id="cruza_rodamiento_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-6">
                  <label for="cuenta_contable_edit" class="col-form-label">Cuenta contable</label>
                  <div id="cuenta_contable_edit_content">
                    <select class="form-control" id="cuenta_contable_edit"></select>
                  </div>  
                </div>
                <div class="form-group col-sm-4">
                  <label for="aplica_cree_edit" class="col-form-label">Concepto aplica impuesto CREE</label>
                  <div id="aplica_cree_edit_content">
                    <select class="form-control" id="aplica_cree_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-2">
                  <label for="codtipo_cuenta_edit" class="col-form-label">Tipo de cuenta del concepto</label>
                  <div id="codtipo_cuenta_edit_content">
                    <select class="form-control" id="codtipo_cuenta_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                    </select>
                  </div>
                </div> 
                <div class="form-group col-sm-4">
                  <label for="causa_gasto_edit" class="col-form-label">Causar gasto por la sucursal?</label>
                  <div id="causa_gasto_edit_content">
                    <select class="form-control" id="causa_gasto_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="naturaleza_edit" class="col-form-label">Concepto de comprobante</label>
                  <div id="naturaleza_edit_content">
                    <select class="form-control" id="naturaleza_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="C">Crédito</option>
                      <option value="D">Débito</option>
                    </select>
                  </div>
                </div>
             </div>
          </div>

        </div>

<?
}


static function editModal(array $param){
    $data = $param[0];
    $data['codconcepto'] = trim($data['codconcepto']);
    $data['desconcepto'] = trim($data['desconcepto']);
    $data['aplica_tabla_porc'] = trim($data['aplica_tabla_porc']);
    $data['vlr_base'] = trim($data['vlr_base']);
    $data['aplica_cree'] = trim($data['aplica_cree']);
    $data['causa_gasto'] = trim($data['causa_gasto']);
    $data['codtipo_cuenta'] = trim($data['codtipo_cuenta']);
    $data['cruza_rodamiento'] = trim($data['cruza_rodamiento']);
    $data['cuenta_contable'] = trim($data['cuenta_contable']);
    $data['cuenta_contable_text'] = trim($data['cuenta_contable_text']);
    $data['impto_finan'] = trim($data['impto_finan']);
    $data['modulo_uso'] = trim($data['modulo_uso']);
    $data['naturaleza'] = trim($data['naturaleza']);
    $data['porc_aplica'] = trim($data['porc_aplica']);
    $data['porc_aplica2'] = trim($data['porc_aplica2']);
    $data['solicita_centro_co'] = trim($data['solicita_centro_co']);
    $data['solicita_codcuenta'] = trim($data['solicita_codcuenta']);
    $data['solicita_det_fact'] = trim($data['solicita_det_fact']);
    $data['solicita_documento'] = trim($data['solicita_documento']);
    $data['solicita_tercero'] = trim($data['solicita_tercero']);
    $id= trim($data['codconcepto']);

    $html =[ <<<T
    
  T, $data];

    return $html;
}


}
?>