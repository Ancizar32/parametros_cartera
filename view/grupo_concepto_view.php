<?php
/**
 * Este controlador tiene como objetivo gestionar la vista principal 
 * y los metodos de proposito general de la aplicacion
 *
 * @author Ancizar
 */
class grupo_concepto_view extends VistaBase
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
    <h5 class="card-header"><span class="text-center">Grupo de conceptos</span></h5>
        <div class="card-body">
            <div class="form-row" id="main_buttons">
                <div class="form-group col-sm-6">
                    <form class="form-inline">
                        <div class="btn-group">
                          <button type="button" class="btn btn-primary dropdown-toggle mr-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Opciones
                          </button>
                          <div class="dropdown-menu">
                            <a class="dropdown-item" href="#" id="create_button">Agregar concepto al grupo</a>
                            <!-- <a class="dropdown-item" href="#" id="edit_button">Editar concepto</a> -->
                            <a class="dropdown-item" href="#" id="disable_button">Eliminar concepto del grupo</a>
                            <a class="dropdown-item" href="#" id="select_all">Seleccionar Todo</a>
                            <a class="dropdown-item" href="#" id="deselect_all">Deseleccionar Todo</a>
                          </div>
                        </div>
                        <!-- <button id="advance_search_button" type="button" class="btn btn-primary mr-4 my-2">Busqueda avanzada</button> -->
                    </form>
                </div>            

        </div>
        <div class="form-row">
           <div class="form-group col-sm-6">
            <div class="form-row align-items-end">
              <div class="col-md-9 w-100" id="codconcepto_content">
              <label for="codconcepto" class="col-form-label">Conceptos de movimiento</label>
              <select class="form-control" id="codconcepto" ></select>
              </div>                    
              <div class="col-md-3 w-100">
               <button class="btn btn-outline-secondary" id="details_button" style="height: 34px;" type="button">Ver detalle</button>
            </div>
            </div> 

            <div id="config_panel" class="my-2">

              <div class="form-group row mb-2">
              <label for="tp_tercero" class="col-sm-4 col-form-label">Tercero</label>
              <div id="tp_tercero_content"  class="col-sm-8">
                <select class="form-control" id="tp_tercero" >
                  <option value="">-- Seleccionar --</option>
                  <option value="N">Natural</option>
                  <option value="J">Jur&iacute;dico</option>
                  <option value="T">Todos</option>
                </select>
              </div>
              </div>
              <div class="form-group row mb-2">
              <label for="tp_regimen" class="col-sm-4 col-form-label">Regimen</label>
              <div id="tp_regimen_content"  class="col-sm-8">
                <select class="form-control" id="tp_regimen" >
                  <option value="">-- Seleccionar --</option>
                  <option value="C">Com&uacute;n</option>
                  <option value="S">Simplificado</option>
                  <option value="T">Todos</option>
                </select>
              </div>
              </div>
              <div class="form-group row mb-2">
              <label for="g_contribuyente" class="col-sm-4 col-form-label">Gran contribuyente</label>
              <div id="g_contribuyente_content"  class="col-sm-8">
                <select class="form-control" id="g_contribuyente" >
                  <option value="">-- Seleccionar --</option>
                  <option value="S">Si</option>
                  <option value="N">No</option>
                  <option value="T">Todos</option>
                </select>
              </div>
              </div>
              <div class="form-group row mb-2">
              <label for="auto_retenedor" class="col-sm-4 col-form-label">Autoretenedores</label>
              <div id="auto_retenedor_content"  class="col-sm-8">
                <select class="form-control" id="auto_retenedor" >
                  <option value="">-- Seleccionar --</option>
                  <option value="S">Si</option>
                  <option value="N">No</option>
                  <option value="T">Todos</option>
                </select>
              </div>
              </div>
              <div class="form-group row mb-2">
              <label for="ext_reteica" class="col-sm-4 col-form-label">Exentos de reteica</label>
              <div id="ext_reteica_content"  class="col-sm-8">
                <select class="form-control" id="ext_reteica" >
                  <option value="">-- Seleccionar --</option>
                  <option value="S">Si</option>
                  <option value="N">No</option>
                  <option value="T">Todos</option>
                </select>
              </div>
              </div>
              <div class="form-group row mb-2">
              <label for="auto_reteica" class="col-sm-4 col-form-label">Auto-Reteica</label>
              <div id="auto_reteica_content"  class="col-sm-8">
                <select class="form-control" id="auto_reteica" >
                  <option value="">-- Seleccionar --</option>
                  <option value="S">Si</option>
                  <option value="N">No</option>
                  <option value="T">Todos</option>
                </select>
              </div>
            </div> 
            </div> 

          </div>
          <div class="form-group col-sm-6">
          <table id="table_grupo_concept" class="table table-bordered dt-responsive" style="width:100%"></table>
        </div>
      </div>

    </div>
</div>


        <div class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div id="advanceSearch_modal_grupo_concept">
            <div class="form-row">
                <div class="form-group col-sm-6">
                  <label for="codconcepto_base_advance" class="col-form-label">Concepto base</label>
                    <input type="text" class="form-control" id="codconcepto_base_advance">  
                </div>
                <div class="form-group col-sm-6">  
                </div>
                <div class="form-group col-sm-4">
                  <label for="tp_tercero_advance" class="col-form-label">Tercero</label>
                  <div id="tp_tercero_advance_content">
                    <select class="form-control" id="tp_tercero_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="N">Natural</option>
                      <option value="J">Jur&iacute;dico</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div> 
                <div class="form-group col-sm-4">
                  <label for="tp_regimen_advance" class="col-form-label">Regimen</label>
                  <div id="tp_regimen_advance_content">
                    <select class="form-control" id="tp_regimen_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="C">Com&uacute;n</option>
                      <option value="S">Simplificado</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div> 
                <div class="form-group col-sm-4">
                  <label for="g_contribuyente_advance" class="col-form-label">Gran contribuyente</label>
                  <div id="g_contribuyente_advance_content">
                    <select class="form-control" id="g_contribuyente_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="auto_retenedor_advance" class="col-form-label">Autoretenedores</label>
                  <div id="auto_retenedor_advance_content">
                    <select class="form-control" id="auto_retenedor_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div> 
                <div class="form-group col-sm-4">
                  <label for="ext_reteica_advance" class="col-form-label">Exentos de reteica</label>
                  <div id="ext_reteica_advance_content">
                    <select class="form-control" id="ext_reteica_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="auto_reteica_advance" class="col-form-label">Auto-Reteica</label>
                  <div id="auto_reteica_advance_content">
                    <select class="form-control" id="auto_reteica_advance" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div>  
             </div>
          </div>

          <div id="newRecord_modal_grupo_concept">
             <div class="form-row">
                <div class="form-group col-sm-12">
                  <label for="codconcepto_base" class="col-form-label">Concepto base</label>
                  <div id="codconcepto_base_content">
                    <select class="form-control" id="codconcepto_base"></select>
                  </div>   
                </div>

                <div id="config_panel_add" class="col-sm-12 my-2">

              <div class="form-group row mb-2">
              <label for="tp_tercero" class="col-sm-4 col-form-label">Tercero</label>
              <div id="tp_tercero_add_content"  class="col-sm-8">
                <select class="form-control" id="tp_tercero_add" >
                  <option value="">-- Seleccionar --</option>
                  <option value="N">Natural</option>
                  <option value="J">Jur&iacute;dico</option>
                  <option value="T">Todos</option>
                </select>
              </div>
              </div>
              <div class="form-group row mb-2">
              <label for="tp_regimen" class="col-sm-4 col-form-label">Regimen</label>
              <div id="tp_regimen_add_content"  class="col-sm-8">
                <select class="form-control" id="tp_regimen_add" >
                  <option value="">-- Seleccionar --</option>
                  <option value="C">Com&uacute;n</option>
                  <option value="S">Simplificado</option>
                  <option value="T">Todos</option>
                </select>
              </div>
              </div>
              <div class="form-group row mb-2">
              <label for="g_contribuyente" class="col-sm-4 col-form-label">Gran contribuyente</label>
              <div id="g_contribuyente_add_content"  class="col-sm-8">
                <select class="form-control" id="g_contribuyente_add" >
                  <option value="">-- Seleccionar --</option>
                  <option value="S">Si</option>
                  <option value="N">No</option>
                  <option value="T">Todos</option>
                </select>
              </div>
              </div>
              <div class="form-group row mb-2">
              <label for="auto_retenedor" class="col-sm-4 col-form-label">Autoretenedores</label>
              <div id="auto_retenedor_add_content"  class="col-sm-8">
                <select class="form-control" id="auto_retenedor_add" >
                  <option value="">-- Seleccionar --</option>
                  <option value="S">Si</option>
                  <option value="N">No</option>
                  <option value="T">Todos</option>
                </select>
              </div>
              </div>
              <div class="form-group row mb-2">
              <label for="ext_reteica" class="col-sm-4 col-form-label">Exentos de reteica</label>
              <div id="ext_reteica_add_content"  class="col-sm-8">
                <select class="form-control" id="ext_reteica_add" >
                  <option value="">-- Seleccionar --</option>
                  <option value="S">Si</option>
                  <option value="N">No</option>
                  <option value="T">Todos</option>
                </select>
              </div>
              </div>
              <div class="form-group row mb-2">
              <label for="auto_reteica" class="col-sm-4 col-form-label">Auto-Reteica</label>
              <div id="auto_reteica_add_content"  class="col-sm-8">
                <select class="form-control" id="auto_reteica_add" >
                  <option value="">-- Seleccionar --</option>
                  <option value="S">Si</option>
                  <option value="N">No</option>
                  <option value="T">Todos</option>
                </select>
              </div>
            </div> 
            </div> 
                 
             </div>
          </div>

          <div class="form-row" id="editRecord_modal_grupo_concept">
            <div class="form-row">
                <div class="form-group col-sm-6">
                  <label for="codconcepto_base_edit" class="col-form-label">Concepto base</label>
                  <div id="codconcepto_base_edit_content">
                    <select class="form-control" id="codconcepto_base_edit"></select>
                  </div>   
                </div>
                <div class="form-group col-sm-6">  
                </div>
                <div class="form-group col-sm-4">
                  <label for="tp_tercero_edit" class="col-form-label">Tercero</label>
                  <div id="tp_tercero_edit_content">
                    <select class="form-control" id="tp_tercero_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="N">Natural</option>
                      <option value="J">Jur&iacute;dico</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div> 
                <div class="form-group col-sm-4">
                  <label for="tp_regimen_edit" class="col-form-label">Regimen</label>
                  <div id="tp_regimen_edit_content">
                    <select class="form-control" id="tp_regimen_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="C">Com&uacute;n</option>
                      <option value="S">Simplificado</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div> 
                <div class="form-group col-sm-4">
                  <label for="g_contribuyente_edit" class="col-form-label">Gran contribuyente</label>
                  <div id="g_contribuyente_edit_content">
                    <select class="form-control" id="g_contribuyente_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="auto_retenedor_edit" class="col-form-label">Autoretenedores</label>
                  <div id="auto_retenedor_edit_content">
                    <select class="form-control" id="auto_retenedor_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div> 
                <div class="form-group col-sm-4">
                  <label for="ext_reteica_edit" class="col-form-label">Exentos de reteica</label>
                  <div id="ext_reteica_edit_content">
                    <select class="form-control" id="ext_reteica_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <label for="auto_reteica_edit" class="col-form-label">Auto-Reteica</label>
                  <div id="auto_reteica_edit_content">
                    <select class="form-control" id="auto_reteica_edit" >
                      <option value="">-- Seleccionar --</option>
                      <option value="S">Si</option>
                      <option value="N">No</option>
                      <option value="T">Todos</option>
                    </select>
                  </div>
                </div>  
             </div>
          </div>

        </div>

<?
}


}
?>