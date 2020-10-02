/////////////////// At start ///////////////////
$(document).ready(function() {
    $("#accounting_notes").on("click", contenido_notas_contabilidad);    
    $("#comp_types").on("click", contenido_tip_comp); 
    $("#num_docs").on("click", start_num_comprobante); 
    $("#concept_mvto").on("click", start_conceptos_mvto);  
    $("#grupo_concepto").on("click", start_grupo_concepto);  
    $("#conc_munic_index").on("click", start_conc_munic);  
    $("#migra_caja_index").on("click", start_migra_caja);  
});

/*Variables*/
let sucursal = '';
let codtp_comprobante = '';
let codcaja = '';
let codusuario = '';
let numero_inicial = '';
let numero_final = '';
let numero_actual = '';
let codigo = '';
let descrip = '';
let tipo = '';
let codctble = '';
let ctactble = '';
let ctactble2 = '';
let estado = '';
let destp_comprobante = '';
let nattp_comprobante = '';
let codcompania = '';
let tipo_causacion = '';
let modulo_uso = '';
let maneja_cons = '';
let imprime_comp = '';
let comp_contable = '';
let afecta_caja = '';
let forma_pago = '';
let consecutivo = '';
let codsucursal = '';
let codconcepto = '';
let desconcepto = '';
let aplica_tabla_porc = '';
let vlr_base = '';
let aplica_cree = '';
let causa_gasto = '';
let codtipo_cuenta = '';
let cruza_rodamiento = '';
let cuenta_contable = '';
let impto_finan = '';
let naturaleza = '';
let porc_aplica = '';
let porc_aplica2 = '';
let solicita_centro_co = '';
let solicita_codcuenta = '';
let solicita_det_fact = '';
let solicita_documento = '';
let solicita_tercero = '';
let codconcepto_base = '';
let tp_tercero = '';
let tp_regimen = '';
let g_contribuyente = '';
let auto_retenedor = '';
let ext_reteica = '';
let auto_reteica = '';
let codmunicipio = '';
let base_liquidacion = '';
let aplica_ica_tercero = '';


///////////////////// P1 Conceptos de notas de contabilidad //////////////////////

function open_advance_search(){
    var element = $("#advanceSearch_modal_contabilidad")[0];
    modalStyle({
        html:element, 
        header:"Busqueda avanzada",
        myFunction : executeSearch,
        button_text:"Buscar",
        cancelButton: closeModal
    });
}

function executeSearch(){
    codigo = $("#codigo_advance").val();
    descrip = $("#descrip_advance").val();
    tipo = $("#tipo_advance").val();
    codctble = $("#codctble_advance").val();
    ctactble = $("#ctactble_advance").val();
    ctactble2 = $("#ctactble2_advance").val();
    estado = $("#estado_advance").val();
        
    setTimeout(function(){reloadTable();},500);    
}

function contenido_notas_contabilidad() {
    $("#index_zonas").empty();
    $.ajax({
      url: 'index.php',
      type: 'post',
      data: {
        controlador: 'contabilidad',
        metodo: 'index'
      },
      beforeSend: function () {
        preloader();
      },
      success: function (data) {      
       
        var p1 = new Promise(

        function(resolve, reject) {
         $('#index_zonas').html(data);
          window.setTimeout(
            function() {
              resolve(endPreloader());
            }, 500);
        }
        );

        p1.then(
            function(val) {
                startDataTable({
                  idTable : 'table_accounting',
                  controller : 'contabilidad',
                  method : 'reloadTable',
                  responsive: true,
                  scrollX: false,
                  autoWidth: false,
                  contextMenu: true,
                  contextMenu_items: {
                     "add": {
                          name: "Nueva nota", 
                          icon: "fas fa-plus-circle", 
                          callback: function(itemKey, opt, e) {
                              createModal();
                          }
                      },
                      "edit": {
                        name: "Editar nota", 
                        icon: "edit",
                        callback: function(itemKey, opt, e) {
                              loadEditModal();
                          }
                      },
                      "disable": {
                        name: "Inhabilitar nota", 
                        icon: "fas fa-trash-alt",
                        callback: function(itemKey, opt, e) {
                              disableRecord();
                          }
                      },
                      "enable": {
                        name: "Habilitar nota", 
                        icon: "fas fa-check-circle",
                        callback: function(itemKey, opt, e) {
                              enableRecord();
                          }
                      },
                      "selectAll": {
                        name: "Seleccionar todo", 
                        icon: "fas fa-list-alt",
                        callback: function(itemKey, opt, e) {
                              dataTableSelectAll();
                          }
                      },
                      "deSelectAll": {
                        name: "Deseleccionar todo", 
                        icon: "far fa-list-alt",
                        callback: function(itemKey, opt, e) {
                              dataTableDeselectAll();
                          }
                      },
                      "exportExcel": {
                        name: "Exportar a Excel", 
                        icon: "fas fa-file-excel",
                        callback: function(itemKey, opt, e) {
                              exportExcel();
                          }
                      },
                      "exportPDF": {
                        name: "Exportar a PDF", 
                        icon: "fas fa-file-pdf",
                        callback: function(itemKey, opt, e) {
                              exportPDF();
                          }
                      }
                  },
                  params :{
                        'codigo' : function(e){return codigo;},
                        'descrip' : function(e){return descrip;},
                        'tipo' : function(e){return tipo;},
                        'codctble' : function(e){return codctble;},
                        'ctactble' : function(e){return ctactble;},
                        'ctactble2' : function(e){return ctactble2;},
                        'estado' : function(e){return estado;},
                    },
                  columns : [
                      { "title" : "C&oacute;digo de nota", "data" : "row_id", className: "table_align_center"},
                      { "title" : "Descripci&oacute;n de nota", "data" : "col1", className: "table_align_center" },
                      { "title" : "Tipo de comprobante", "data" : "col2", className: "table_align_center" },
                      { "title" : "Tipo documento contable", "data" : "col3", className: "table_align_center" },
                      { "title" : "Cuenta contable debito", "data" : "col4", className: "table_align_center" },
                      { "title" : "Cuenta contable credito", "data" : "col5", className: "table_align_center" },
                      { "title" : "Estado Activa/Inactiva", "data" : "col6", className: "table_align_center" },
                  ]
                });
            })
        .catch(
            function(reason) {
              alert(reason);
        });

        p1.then(
            function(val) {
                $("#create_button").on("click", createModal);
                $("#disable_button").on("click", disableRecord);
                $("#enable_button").on("click", enableRecord);
                $("#edit_button").on("click", loadEditModal);
                $("#advance_search_button").on("click", open_advance_search); 
            })
        .catch(
            function(reason) {
              alert(reason);
        });

        p1.then(
            function(val) {
                endPreloader();
            })
        .catch(
        function(reason) {
          alert(reason);
        });
      },
      error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
        } 
    });

}

function create_record(){
    var parametros = {
            "codigo" : $('#codigo').val(),
            "descrip" : $('#descrip').val(),
            "tipo" : $('#tipo').val(),
            "codctble" : $('#codctble').val(),
            "ctactble" : $('#ctactble').val(),
            "ctactble2" : $('#ctactble2').val(),
            "estado" : $("#estado").prop("checked"),
        } 
    $.ajax({
        type: "POST",        
        dataType: "json",       
        url: "index.php",
        data: {
            metodo: 'crear', 
            controlador: 'contabilidad',
            param: parametros
        },   
        beforeSend: function(){
            startWait();
        },     
        success: function(data) { 
            endWait();           
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.success=='success') {
                        setTimeout(function(){reloadTable();},500);
                        setTimeout(function(){closeModal();},500);
                    }
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}

function createModal(){ 
  var element = $("#newRecord_modal_contabilidad")[0];
    modalStyle({
        html:element, 
        header:"Nuevo movimiento de cuenta",
        myFunction : create_record,
        button_text:"Crear",
        cancelButton: closeModal
    });
    setTimeout(function(){autocomplete_destino_rem();},200);
    setTimeout(function(){autocomplete_cuenta_contable();},200);
    setTimeout(function(){autocomplete_tip_doc();},200);
}


function loadEditModal(){ 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'returnEditModal', 
            controlador: 'contabilidad', 
            param: item_row
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();
            if (data.success=="error") {
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                });
            } else{
                var html = $.parseHTML(data.html[0]);
                var values = data.html[1];
                idEdit = values.codigo_id;
                var element = $("#editRecord_modal_contabilidad")[0];
                modalStyle({
                    html:element, 
                    header:"Edicion movimiento de cuenta",
                    myFunction : updateRecord,
                    button_text:"Editar",
                    cancelButton: closeModal,
                    dataValue:values,
                });
                setTimeout(function(){
                    autocomplete_destino_rem_edit();
                    autocomplete_cuenta_contable_edit();
                    autocomplete_tip_doc_edit();
                    $('#codigo_edit').val(values.codigo);
                    $('#descrip_edit').val(values.descrip);
                    $('#tipo_edit').select2("trigger", "select", {
                        data: { id: values.tipo, text: (values.tipo !=='') ? values.tipo+' : '+values.tipo_text : '' }
                    });
                    $('#codctble_edit').select2("trigger", "select", {
                        data: { id: values.codctble, text: (values.codctble !=='') ? values.codctble+' : '+values.codctble_text : '' }
                    });
                    $('#ctactble_edit').select2("trigger", "select", {
                        data: { id: values.ctactble, text: (values.ctactble !=='') ? values.ctactble+' : '+values.ctactble_text : '' }
                    });
                    $('#ctactble2_edit').select2("trigger", "select", {
                        data: { id: values.ctactble2, text: (values.ctactble2 !=='') ? values.ctactble2+' : '+values.ctactble2_text : '' }
                    });
                    $('#estado_edit').prop("checked", values.estado);
                },100);
            }            
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}

function autocomplete_destino_rem() {
  var element = $("#tipo");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#miSelect'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'contabilidad',
            'metodo' : 'fillOutAutocomplete' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(failure);
      }
    }
});
}

function autocomplete_tip_doc() {
  var element = $("#codctble");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#miSelect'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'contabilidad',
            'metodo' : 'fillOutAutocompleteTipDoc' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(failure);
      }
    }
});
}

function autocomplete_tip_doc_edit() {
  var element = $("#codctble_edit");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#codctble_edit_'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'contabilidad',
            'metodo' : 'fillOutAutocompleteTipDoc' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(failure);
      }
    }
});
}

function autocomplete_destino_rem_edit() {
  var element = $("#tipo_edit");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#tipo_edit_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'contabilidad',
            'metodo' : 'fillOutAutocomplete' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(failure);
      }
    }
});
}

function autocomplete_cuenta_contable() {
  var element = $("#ctactble");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#miSelect'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'contabilidad',
            'metodo' : 'fillOutAutocompleteCuenta' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(failure);
        }
        }
    });

    var element = $("#ctactble2");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#miSelect'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'contabilidad',
            'metodo' : 'fillOutAutocompleteCuenta' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(failure);
      }
    }
});
}

function autocomplete_cuenta_contable_edit() {
  var element = $("#ctactble_edit");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#miSelect_ctactble_edit'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'contabilidad',
            'metodo' : 'fillOutAutocompleteCuenta' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(failure);
        }
        }
    });

    var element = $("#ctactble2_edit");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#miSelect_ctactble2_edit'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'contabilidad',
            'metodo' : 'fillOutAutocompleteCuenta' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(failure);
      }
    }
});
}

function updateRecord(){ 
    var parametros = {
            "codigo_edit" : $('#codigo_edit').val(),
            "descrip_edit" : $('#descrip_edit').val(),
            "tipo_edit" : $('#tipo_edit').val(),
            "codctble_edit" : $('#codctble_edit').val(),
            "ctactble_edit" : $('#ctactble_edit').val(),
            "ctactble2_edit" : $('#ctactble2_edit').val(),
            "estado_edit" : $('#estado_edit').prop("checked"),
        } 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'editRecord', 
            controlador: 'contabilidad', 
            param: parametros
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();     
            if (data.success=='success') {
                closeModal();
                reloadTable();
            }      
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    })
}

function disableRecord(){
  var plural = "el registro seleccionado";
  var item_count = item_row.length;
  if (item_count < 1) {
    Swal.fire({
            title: 'Error!',
            text: 'Por favor seleccione un registro para inhabilitar',
            icon: "error",
          });
  }else {
    if (item_count > 1) {
      plural = "los registros seleccionados";
    }
    Swal.fire({
      title: 'Advertencia!',
      text: "Segur@ que desea inhabilitar "+plural+"?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Continuar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.value) {
            $.ajax({
            type: "POST",        
            url: "index.php",
            dataType: "json", 
            data: {
                metodo: 'disableRecord', 
                controlador: 'contabilidad', 
                param: item_row
            }, 
            beforeSend: function(){
                startWait();
            },     
            success: function(data) {
                endWait();
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                }).then((result) => {
                    if (result.value) {
                        if (data.success=='success') {
                            setTimeout(function(){reloadTable();},500);
                        }
                    }                
                });
            },
            error:function (jqXHR,textStatus) {
                endWait();
                Swal.fire({
                    title: 'Error!',
                    text: jqXHR.responseText,
                    icon: "error",
                  });
                }  
        })
      }
    })
  }
}

function enableRecord(){
  var plural = "el registro seleccionado";
  var item_count = item_row.length;
  if (item_count < 1) {
    Swal.fire({
            title: 'Error!',
            text: 'Por favor seleccione un registro para habilitar',
            icon: "error",
          });
  }else {
    if (item_count > 1) {
      plural = "los registros seleccionados";
    }
    Swal.fire({
      title: 'Advertencia!',
      text: "Segur@ que desea habilitar "+plural+"?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Continuar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.value) {
            $.ajax({
            type: "POST",        
            url: "index.php",
            dataType: "json", 
            data: {
                metodo: 'enableRecord', 
                controlador: 'contabilidad', 
                param: item_row
            }, 
            beforeSend: function(){
                startWait();
            },       
            success: function(data) {
                endWait();
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                }).then((result) => {
                    if (result.value) {
                        if (data.success=='success') {
                            setTimeout(function(){reloadTable();},500);
                        }
                    }                
                });
            },
            error:function (jqXHR,textStatus) {
                endWait();
                Swal.fire({
                    title: 'Error!',
                    text: jqXHR.responseText,
                    icon: "error",
                  });
            }  
        })
      }
    })
  }
}

///////////////// P2 Tipos de comprobantes ///////////////////////////

function contenido_tip_comp() {
    $("#index_zonas").empty();
    $.ajax({
      url: 'index.php',
      type: 'post',
      data: {
        controlador: 'tip_comp',
        metodo: 'index'
      },
      beforeSend: function () {
        preloader();
      },
      success: function (data) {      
        var p1 = new Promise(

        function(resolve, reject) {
         $('#index_zonas').html(data);
          window.setTimeout(
            function() {
              resolve(endPreloader());
            }, 500);
        }
        );

        p1.then(
            function(val) {
                startDataTable({
                  idTable : 'table_comp',
                  controller : 'tip_comp',
                  method : 'reloadTable',
                  responsive: true,
                  scrollX: false,
                  autoWidth: false,
                  contextMenu: true,
                  contextMenu_items: {
                     "add": {
                          name: "Nuevo tipo de comprobante", 
                          icon: "fas fa-plus-circle", 
                          callback: function(itemKey, opt, e) {
                              open_new_record_tip_comp();
                          }
                      },
                      "edit": {
                        name: "Editar tipo de comprobante", 
                        icon: "edit",
                        callback: function(itemKey, opt, e) {
                              loadEditModalTipComp();
                          }
                      },
                      "selectAll": {
                        name: "Seleccionar todo", 
                        icon: "fas fa-list-alt",
                        callback: function(itemKey, opt, e) {
                              dataTableSelectAll();
                          }
                      },
                      "deSelectAll": {
                        name: "Deseleccionar todo", 
                        icon: "far fa-list-alt",
                        callback: function(itemKey, opt, e) {
                              dataTableDeselectAll();
                          }
                      },
                      "exportExcel": {
                        name: "Exportar a Excel", 
                        icon: "fas fa-file-excel",
                        callback: function(itemKey, opt, e) {
                              exportExcel();
                          }
                      },
                      "exportPDF": {
                        name: "Exportar a PDF", 
                        icon: "fas fa-file-pdf",
                        callback: function(itemKey, opt, e) {
                              exportPDF();
                          }
                      }
                  },
                  params : {
                        'codtp_comprobante' : function(e){return codtp_comprobante;},
                        'destp_comprobante' : function(e){return destp_comprobante;},
                        'nattp_comprobante' : function(e){return nattp_comprobante;},
                        'codcompania' : function(e){return codcompania;},
                        'tipo_causacion' : function(e){return tipo_causacion;},
                        'modulo_uso' : function(e){return modulo_uso;},
                        'maneja_cons' : function(e){return maneja_cons;},
                        'imprime_comp' : function(e){return imprime_comp;},
                        'comp_contable' : function(e){return comp_contable;},
                        'afecta_caja' : function(e){return afecta_caja;},
                        'forma_pago' : function(e){return forma_pago;},
                    },
                  columns : [
                      { "title" : "C&oacute;digo comprobante", "data" : "row_id", className: "table_align_center"},
                      { "title" : "Descripci&oacute;n comprobante", "data" : "col1", className: "table_align_center" },
                      { "title" : "Naturaleza comprobante", "data" : "col2", className: "table_align_center" },
                      { "title" : "Compañia", "data" : "col3", className: "table_align_center" },
                      { "title" : "Tipo causación", "data" : "col4", className: "table_align_center" },
                      { "title" : "Modulo uso", "data" : "col5", className: "table_align_center" },
                      { "title" : "Control de consecutivo", "data" : "col6", className: "table_align_center" },
                      { "title" : "Imprimir detalles", "data" : "col7", className: "table_align_center" },
                      { "title" : "Tipo de Comprobante", "data" : "col8", className: "table_align_center" },
                      { "title" : "Afecta efectivo", "data" : "col9", className: "table_align_center" },
                      { "title" : "Forma de pago valida", "data" : "col10", className: "table_align_center" },
                  ]
                });
            })
        .catch(
            function(reason) {
              alert(reason);
        });

        p1.then(
            function(val) {
                $("#create_button").on("click", open_new_record_tip_comp);
                $("#disable_button").on("click", disableRecord);
                $("#enable_button").on("click", enableRecord);
                $("#edit_button").on("click", loadEditModalTipComp);
                $("#advance_search_button").on("click", open_advance_search_tip_comp); 
            })
        .catch(
            function(reason) {
              alert(reason);
        });

        p1.then(
            function(val) {
                endPreloader();
            })
        .catch(
        function(reason) {
          alert(reason);
        });
      },
      error:function (jqXHR,textStatus) {
            endPreloader();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
        } 
    });

}


function loadEditModalTipComp(){ 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'returnEditModal', 
            controlador: 'tip_comp', 
            param: item_row
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();
            if (data.success=="error") {
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                });
            } else{
                var html = $.parseHTML(data.html[0]);
                var values = data.html[1];
                idEdit = values.codtp_comprobante;
                var element = $("#editRecord_modal_tip_comp")[0];
                modalStyle({
                    html:element, 
                    header:"Edición tipo de comprobante",
                    myFunction : updateRecordTipComp,
                    button_text:"Editar",
                    cancelButton: closeModal,
                    dataValue:values,
                });
                setTimeout(function(){
                  startSelect2([
                    'nattp_comprobante_edit',
                    'tipo_causacion_edit',
                    'modulo_uso_edit',
                    'maneja_cons_edit',
                    'imprime_comp_edit',
                    'comp_contable_edit',
                    'afecta_caja_edit',
                  ]);
                }, 100);
                setTimeout(function(){
                    autocomplete_formas_pago_edit();
                    autocomplete_company_edit();
                    $("#codtp_comprobante_edit").val(values.codtp_comprobante);
                    $("#destp_comprobante_edit").val(values.destp_comprobante);
                    $("#nattp_comprobante_edit").val(values.nattp_comprobante);
                    $("#tipo_causacion_edit").val(values.tipo_causacion);
                    $("#modulo_uso_edit").val(values.modulo_uso);
                    $("#maneja_cons_edit").val(values.maneja_cons);
                    $("#imprime_comp_edit").val(values.imprime_comp);
                    $("#comp_contable_edit").val(values.comp_contable);
                    $("#afecta_caja_edit").val(values.afecta_caja);

                    $("#codcompania_edit").select2("trigger", "select", {
                        data: { id: values.codcompania, text: (values.codcompania !=='') 
                        ? values.codcompania+' : '+values.codcompania_text : '' }
                    });
                    $("#forma_pago_edit").select2("trigger", "select", {
                        data: { id: values.forma_pago, text: (values.forma_pago !=='') 
                        ? values.forma_pago+' : '+values.forma_pago_text : '' }
                    });
                    
                },100);
            }
            
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}


function updateRecordTipComp(){ 
    var parametros = {
            "destp_comprobante" : $("#destp_comprobante_edit").val(),
            "nattp_comprobante" : $("#nattp_comprobante_edit").val(),
            "codcompania" : $("#codcompania_edit").val(),
            "tipo_causacion" : $("#tipo_causacion_edit").val(),
            "modulo_uso" : $("#modulo_uso_edit").val(),
            "maneja_cons" : $("#maneja_cons_edit").val(),
            "imprime_comp" : $("#imprime_comp_edit").val(),
            "comp_contable" : $("#comp_contable_edit").val(),
            "afecta_caja" : $("#afecta_caja_edit").val(),
            "cuenta_pagar" : $("#cuenta_pagar_edit").val(),
            "forma_pago" : $("#forma_pago_edit").val(),
            "codtp_comprobante" : $("#codtp_comprobante_edit").val(),
        } 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'editRecord', 
            controlador: 'tip_comp', 
            param: parametros
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();     
            if (data.success=='success') {
                closeModal();
                reloadTable();
            }      
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}

function open_advance_search_tip_comp(){
    var element = $("#advanceSearch_modal_tip_comp")[0];
    modalStyle({
        html:element, 
        header:"Busqueda avanzada egresos",
        myFunction : executeSearchTipComp,
        button_text:"Buscar",
        cancelButton: closeModal
    });
    setTimeout(function(){
      startSelect2([
          'nattp_comprobante_advance',
          'tipo_causacion_advance',
          'modulo_uso_advance',
          'maneja_cons_advance',
          'imprime_comp_advance',
          'comp_contable_advance',
          'afecta_caja_advance',
        ]);
    },100);
}

function open_new_record_tip_comp(){
    var element = $("#newRecord_modal_tip_comp")[0];
    modalStyle({
        html:element, 
        header:"Nuevo tipo de comprobante",
        myFunction : create_tip_comp,
        button_text:"Crear",
        cancelButton: closeModal
    });
    setTimeout(function(){autocomplete_formas_pago();},200);
    setTimeout(function(){autocomplete_company();},200);
    setTimeout(function(){
      startSelect2([
        "nattp_comprobante",
        "tipo_causacion",
        "modulo_uso",
        "maneja_cons",
        "imprime_comp",
        "comp_contable",
        "afecta_caja",
      ]);
    },200);
}


function create_tip_comp(){
    var parametros = {
            'codtp_comprobante' : $("#codtp_comprobante").val(),
            'destp_comprobante' : $("#destp_comprobante").val(),
            'nattp_comprobante' : $("#nattp_comprobante").val(),
            'codcompania' : $("#codcompania").val(),
            'tipo_causacion' : $("#tipo_causacion").val(),
            'modulo_uso' : $("#modulo_uso").val(),
            'maneja_cons' : $("#maneja_cons").val(),
            'imprime_comp' : $("#imprime_comp").val(),
            'comp_contable' : $("#comp_contable").val(),
            'afecta_caja' : $("#afecta_caja").val(),
            'forma_pago' : $("#forma_pago").val(),
        } 
    $.ajax({
        type: "POST",        
        dataType: "json",       
        url: "index.php",
        data: {
            metodo: 'crear', 
            controlador: 'tip_comp',
            param: parametros
        },   
        beforeSend: function(){
            startWait();
        },     
        success: function(data) { 
            endWait();           
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.success=='success') {
                        setTimeout(function(){reloadTable();},500);
                        setTimeout(function(){closeModal();},500);
                    }
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}



function executeSearchTipComp(){
    codtp_comprobante = $("#codtp_comprobante_advance").val();
    destp_comprobante = $("#destp_comprobante_advance").val();
    nattp_comprobante = $("#nattp_comprobante_advance").val();
    codcompania = $("#codcompania_advance").val();
    tipo_causacion = $("#tipo_causacion_advance").val();
    modulo_uso = $("#modulo_uso_advance").val();
    maneja_cons = $("#maneja_cons_advance").val();
    imprime_comp = $("#imprime_comp_advance").val();
    comp_contable = $("#comp_contable_advance").val();
    afecta_caja = $("#afecta_caja_advance").val();
    forma_pago = $("#forma_pago_advance").val();        
    setTimeout(function(){reloadTable();},500);    
}

function autocomplete_formas_pago() {
  var element = $("#forma_pago");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#forma_pago_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'tip_comp',
            'metodo' : 'fillOutAutocompleteFormasPago' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(failure);
        }
        }
    });
}

function autocomplete_company() {
  var element = $("#codcompania");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#codcompania_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'tip_comp',
            'metodo' : 'fillOutAutocompleteCompany',
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(function(data, error){
            console.log(error);
          });
        }
        }
    });
}

function autocomplete_formas_pago_edit() {
  var element = $("#forma_pago_edit");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#forma_pago_edit_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'tip_comp',
            'metodo' : 'fillOutAutocompleteFormasPago' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(failure);
        }
        }
    });
}

function autocomplete_company_edit() {
  var element = $("#codcompania_edit");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#codcompania_edit_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'tip_comp',
            'metodo' : 'fillOutAutocompleteCompany' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(function(data, error){
            console.log(error);
          });
        }
        }
    });
}


/////////////////////////// P3 Numeracion comprobantes ///////////////////////////////

function start_num_comprobante() {
  $("#index_zonas").empty();
    $.when(
      $.ajax({
        url: 'index.php',
        type: 'post',
        data: {
          controlador: 'num_document',
          metodo: 'index'
        },
        beforeSend: function () {
          preloader();
        },
        success: function (data) {      
            $('#index_zonas').html(data);
        },
        error:function (jqXHR,textStatus) {
              Swal.fire({
                  title: 'Error!',
                  text: jqXHR.responseText,
                  icon: "error",
                });
          } 
      })
    ).done(function(){
      $.when(
      startDataTable({
        idTable : 'table_num_doc',
        controller : 'num_document',
        method : 'reloadTable',
        responsive: true,
        scrollX: false,
        autoWidth: false,
        contextMenu: true,
        contextMenu_items: {
           "add": {
                name: "Nuevo número de comprobante", 
                icon: "fas fa-plus-circle", 
                callback: function(itemKey, opt, e) {
                    open_new_record_num_comp();
                }
            },
            "edit": {
              name: "Editar número de comprobante", 
              icon: "edit",
              callback: function(itemKey, opt, e) {
                    loadEditModalNumComp();
                }
            },
            "disable": {
              name: "Inhabilitar numero de comprobante", 
              icon: "fas fa-trash-alt",
              callback: function(itemKey, opt, e) {
                    disableRecordNumComp();
                }
            },
            "enable": {
              name: "Habilitar numero de comprobante", 
              icon: "fas fa-check-circle",
              callback: function(itemKey, opt, e) {
                    enableRecordNumComp();
                }
            },
            "selectAll": {
              name: "Seleccionar todo", 
              icon: "fas fa-list-alt",
              callback: function(itemKey, opt, e) {
                    dataTableSelectAll();
                }
            },
            "deSelectAll": {
              name: "Deseleccionar todo", 
              icon: "far fa-list-alt",
              callback: function(itemKey, opt, e) {
                    dataTableDeselectAll();
                }
            },
            "exportExcel": {
              name: "Exportar a Excel", 
              icon: "fas fa-file-excel",
              callback: function(itemKey, opt, e) {
                    exportExcel();
                }
            },
            "exportPDF": {
              name: "Exportar a PDF", 
              icon: "fas fa-file-pdf",
              callback: function(itemKey, opt, e) {
                    exportPDF();
                }
            }
        },
        params : {
              'consecutivo' : function(e){return consecutivo;},
              'codsucursal' : function(e){return codsucursal;},
              'codtp_comprobante' : function(e){return codtp_comprobante;},
              'codcaja' : function(e){return codcaja;},
              'codusuario' : function(e){return codusuario;},
              'numero_inicial' : function(e){return numero_inicial;},
              'numero_final' : function(e){return numero_final;},
              'numero_actual' : function(e){return numero_actual;},
              'estado' : function(e){return estado;},
          },
        columns : [
            { "title" : "Consecutivo", "data" : "row_id", className: "table_align_center"},
            { "title" : "Sucursal", "data" : "sucursal", className: "table_align_center" },
            { "title" : "Tipo de comprobante", "data" : "tip_comp", className: "table_align_center" },
            { "title" : "Responsable caja", "data" : "resp_caja", className: "table_align_center" },
            { "title" : "Responsable papeleria manual", "data" : "resp_papel", className: "table_align_center" },
            { "title" : "N&uacute;mero inicial", "data" : "n_inicial", className: "table_align_center" },
            { "title" : "N&uacute;mero final", "data" : "n_final", className: "table_align_center" },
            { "title" : "N&uacute;mero actual", "data" : "n_actual", className: "table_align_center" },
            { "title" : "Estado", "data" : "estado", className: "table_align_center" },
        ]
      })
      
    ).done(function(){     
      $("#create_button").on("click", open_new_record_num_comp);
      $("#disable_button").on("click", disableRecordNumComp);
      $("#enable_button").on("click", enableRecordNumComp);
      $("#edit_button").on("click", loadEditModalNumComp);
      $("#advance_search_button").on("click", open_advance_search_num_comp); 
    }).done(function(){
      endPreloader();
    })
  });
}

function executeSearchNumComp(){
    consecutivo = $("#consecutivo_advance").val();
    codsucursal = $("#codsucursal_advance").val();
    codtp_comprobante = $("#codtp_comprobante_advance").val();
    codcaja = $("#codcaja_advance").val();
    codusuario = $("#codusuario_advance").val();
    numero_inicial = $("#numero_inicial_advance").val();
    numero_final = $("#numero_final_advance").val();
    numero_actual = $("#numero_actual_advance").val();        
    estado = $("#estado_advance").val();        
    setTimeout(function(){reloadTable();},500);    
}


function open_advance_search_num_comp(){
    var element = $("#advanceSearch_modal_num_doc")[0];
    modalStyle({
        html:element, 
        header:"Busqueda avanzada numeración comprobantes",
        myFunction : executeSearchNumComp,
        button_text:"Buscar",
        cancelButton: closeModal
    });
}


function open_new_record_num_comp(){ 
    $.ajax({
        type: "POST",        
        url: "index.php", 
        data: {
            metodo: 'createModal', 
            controlador: 'num_document',
        }, 
        beforeSend: function(){
            startWait();
        },       
        success: function(html) {
            endWait();
            var element = $("#newRecord_modal_num_doc")[0];
            modalStyle({
                html:element, 
                header:"Nuevo número de comprobante",
                myFunction : create_num_comp,
                button_text:"Crear",
                cancelButton: closeModal
            });
            setTimeout(function(){
              autocomplete_branch_office();
              autocomplete_tipos_comprobantes();
              autocomplete_cod_caja();
              autocomplete_cod_usuario();
            },100);
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}

function autocomplete_branch_office() {
  var element = $("#codsucursal");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#codsucursal_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'num_document',
            'metodo' : 'autocomplete_branch_office' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(function(jqXHR,textStatus){
             
          });
        }
        }
    });
    element.on('select2:open',(event) => {
      sucursal = '';
      $('#codcaja').select2("trigger", "select", {
            data: { id: '', text: '-- Sin selección --' }
        });
      $('#codusuario').select2("trigger", "select", {
            data: { id: '', text: '-- Sin selección --' }
        });
      
    });
    element.on('select2:select',(event) => {
      sucursal = $("#codsucursal").val();
    });
}

function autocomplete_cod_caja() {
  var element = $("#codcaja");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#codcaja_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'num_document',
            'metodo' : 'autocomplete_cod_caja',
            'suc' : sucursal
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(function(jqXHR,textStatus){
             
          });
        }
        }
    });
}

function autocomplete_cod_usuario() {
  var element = $("#codusuario");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#codusuario_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'num_document',
            'metodo' : 'autocomplete_cod_usuario',
            'suc' : sucursal
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(function(jqXHR,textStatus){
             
          });
        }
        }
    });
}

function autocomplete_tipos_comprobantes() {
  var element = $("#codtp_comprobante");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#codtp_comprobante_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'num_document',
            'metodo' : 'autocomplete_tipos_comprobantes' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(function(jqXHR,textStatus){
             
          });
        }
        }
    });
}

function create_num_comp(){
    var parametros = {
            'codsucursal' : $("#codsucursal").val(),
            'codtp_comprobante' : $("#codtp_comprobante").val(),
            'codcaja' : $("#codcaja").val(),
            'codusuario' : $("#codusuario").val(),
            'numero_inicial' : $("#numero_inicial").val(),
            'numero_final' : $("#numero_final").val(),
            'numero_actual' : $("#numero_actual").val(),
        } 
    $.ajax({
        type: "POST",        
        dataType: "json",       
        url: "index.php",
        data: {
            metodo: 'createRecord', 
            controlador: 'num_document',
            param: parametros
        },   
        beforeSend: function(){
            startWait();
        },     
        success: function(data) { 
            endWait();           
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.success=='success') {
                        setTimeout(function(){reloadTable();},500);
                        setTimeout(function(){closeModal();},500);
                    }
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}


function loadEditModalNumComp(){ 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'returnEditModal', 
            controlador: 'num_document', 
            param: item_row
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();
            if (data.success=="error") {
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                });
            } else{
                var html = $.parseHTML(data.html[0]);
                var values = data.html[1];
                idEdit = values.consecutivo;
                var element = $("#editRecord_modal_num_doc")[0];
                modalStyle({
                    html:element, 
                    header:"Edición número de comprobante",
                    myFunction : updateRecordNumComp,
                    button_text:"Editar",
                    dataValue:values,
                    cancelButton: closeModal
                });
                setTimeout(function(){
                    autocomplete_branch_office_edit();
                    autocomplete_cod_caja_edit();
                    autocomplete_cod_usuario_edit();
                    autocomplete_tipos_comprobantes_edit();
                    $("#consecutivo_edit").val(values.consecutivo);
                    $("#numero_inicial_edit").val(values.numero_inicial);
                    $("#numero_final_edit").val(values.numero_final);
                    $("#numero_actual_edit").val(values.numero_actual);

                    $("#codsucursal_edit").select2("trigger", "select", {
                        data: { id: values.codsucursal, text: (values.codsucursal !=='') 
                        ? values.codsucursal+' : '+values.codsucursal_text : '' }
                    });
                    $("#codtp_comprobante_edit").select2("trigger", "select", {
                        data: { id: values.codtp_comprobante, text: (values.codtp_comprobante !=='') 
                        ? values.codtp_comprobante+' : '+values.codtp_comprobante_text : '' }
                    });
                    $("#codcaja_edit").select2("trigger", "select", {
                        data: { id: values.codcaja, text: (values.codcaja !=='') 
                        ? values.codcaja+' : '+values.codcaja_text : '' }
                    });
                    $("#codusuario_edit").select2("trigger", "select", {
                        data: { id: values.codusuario, text: (values.codusuario !=='') 
                        ? values.codusuario+' : '+values.codusuario_text : '' }
                    });
                    
                },100);
            }
            
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}

function autocomplete_branch_office_edit() {
  var element = $("#codsucursal_edit");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#codsucursal_edit_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'num_document',
            'metodo' : 'autocomplete_branch_office' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(function(jqXHR,textStatus){
             
          });
        }
        }
    });
    element.on('select2:open',(event) => {
      sucursal = '';
      $('#codcaja_edit').select2("trigger", "select", {
            data: { id: '', text: '-- Sin selección --' }
        });
      $('#codusuario_edit').select2("trigger", "select", {
            data: { id: '', text: '-- Sin selección --' }
        });
      
    });
    element.on('select2:select',(event) => {
      sucursal = $("#codsucursal_edit").val();
    });
}

function autocomplete_cod_caja_edit() {
  var element = $("#codcaja_edit");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#codcaja_edit_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'num_document',
            'metodo' : 'autocomplete_cod_caja',
            'suc' : sucursal
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(function(jqXHR,textStatus){
             
          });
        }
        }
    });
}

function autocomplete_cod_usuario_edit() {
  var element = $("#codusuario_edit");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#codusuario_edit_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'num_document',
            'metodo' : 'autocomplete_cod_usuario',
            'suc' : sucursal
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(function(jqXHR,textStatus){
             
          });
        }
        }
    });
}

function autocomplete_tipos_comprobantes_edit() {
  var element = $("#codtp_comprobante_edit");
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#codtp_comprobante_edit_content'),
      minimumInputLength: 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
       language: {
        noResults: function (params) {
          return "No hay registros";
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : 'num_document',
            'metodo' : 'autocomplete_tipos_comprobantes' 
          };
          let f_params = {...params, ...additional_params};
      
          $.ajax({
            type: "POST",
            url: "index.php",
            data: f_params,
            dataType:"JSON"
          }).done(function(data){
              success(data);
          }).fail(function(jqXHR,textStatus){
             
          });
        }
        }
    });
}

function updateRecordNumComp(){ 
    var parametros = {
            'consecutivo' : $("#consecutivo_edit").val(),
            'codsucursal' : $("#codsucursal_edit").val(),
            'codtp_comprobante' : $("#codtp_comprobante_edit").val(),
            'codcaja' : $("#codcaja_edit").val(),
            'codusuario' : $("#codusuario_edit").val(),
            'numero_inicial' : $("#numero_inicial_edit").val(),
            'numero_final' : $("#numero_final_edit").val(),
            'numero_actual' : $("#numero_actual_edit").val(),
        } 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'editRecord', 
            controlador: 'num_document', 
            param: parametros
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();     
            if (data.success=='success') {
                closeModal();
                reloadTable();
            }      
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}


function disableRecordNumComp(){
  var plural = "el registro seleccionado";
  var item_count = item_row.length;
  if (item_count < 1) {
    Swal.fire({
            title: 'Error!',
            text: 'Por favor seleccione un registro para inhabilitar',
            icon: "error",
          });
  }else {
    if (item_count > 1) {
      plural = "los registros seleccionados";
    }
    Swal.fire({
      title: 'Advertencia!',
      text: "Segur@ que desea inhabilitar "+plural+"?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Continuar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.value) {
            $.ajax({
            type: "POST",        
            url: "index.php",
            dataType: "json", 
            data: {
                metodo: 'disableRecord', 
                controlador: 'num_document', 
                param: item_row
            }, 
            beforeSend: function(){
                startWait();
            },     
            success: function(data) {
                endWait();
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                }).then((result) => {
                    if (result.value) {
                        if (data.success=='success') {
                            setTimeout(function(){reloadTable();},500);
                        }
                    }                
                });
            },
            error:function (jqXHR,textStatus) {
                endWait();
                Swal.fire({
                    title: 'Error!',
                    text: jqXHR.responseText,
                    icon: "error",
                  });
                }  
        })
      }
    })
  }
}

function enableRecordNumComp(){
  var plural = "el registro seleccionado";
  var item_count = item_row.length;
  if (item_count < 1) {
    Swal.fire({
            title: 'Error!',
            text: 'Por favor seleccione un registro para habilitar',
            icon: "error",
          });
  }else {
    if (item_count > 1) {
      plural = "los registros seleccionados";
    }
    Swal.fire({
      title: 'Advertencia!',
      text: "Segur@ que desea habilitar "+plural+"?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Continuar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.value) {
            $.ajax({
            type: "POST",        
            url: "index.php",
            dataType: "json", 
            data: {
                metodo: 'enableRecord', 
                controlador: 'num_document', 
                param: item_row
            }, 
            beforeSend: function(){
                startWait();
            },       
            success: function(data) {
                endWait();
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                }).then((result) => {
                    if (result.value) {
                        if (data.success=='success') {
                            setTimeout(function(){reloadTable();},500);
                        }
                    }                
                });
            },
            error:function (jqXHR,textStatus) {
                endWait();
                Swal.fire({
                    title: 'Error!',
                    text: jqXHR.responseText,
                    icon: "error",
                  });
            }  
        })
      }
    })
  }
}




///////////////// P4 Conceptos movimiento ///////////////////////////


function start_conceptos_mvto() {
  $("#index_zonas").empty();
    $.when(
      $.ajax({
        url: 'index.php',
        type: 'post',
        data: {
          controlador: 'conceptos_mvto',
          metodo: 'index'
        },
        beforeSend: function () {
          preloader();
        },
        success: function (data) {      
            $('#index_zonas').html(data);
        },
        error:function (jqXHR,textStatus) {
              Swal.fire({
                  title: 'Error!',
                  text: jqXHR.responseText,
                  icon: "error",
                });
          } 
      })
    ).done(function(){
      $.when(
      startDataTable({
        idTable : 'table',
        controller : 'conceptos_mvto',
        method : 'reloadTable',
        responsive: false,
        scrollX: true,
        autoWidth: true,
        contextMenu: true,
        contextMenu_items: {
           "add": {
                name: "Nuevo concepto de moviemiento", 
                icon: "fas fa-plus-circle", 
                callback: function(itemKey, opt, e) {
                    open_new_recordConceptMvto();
                }
            },
            "edit": {
              name: "Editar concepto de moviemiento", 
              icon: "edit",
              callback: function(itemKey, opt, e) {
                    loadEditModalCompMvto();
                }
            },
            "disable": {
              name: "Eliminar concepto de moviemiento", 
              icon: "fas fa-trash-alt",
              callback: function(itemKey, opt, e) {
                    deleteRecordConceptMvto();
                }
            },
            "selectAll": {
              name: "Seleccionar todo", 
              icon: "fas fa-list-alt",
              callback: function(itemKey, opt, e) {
                    dataTableSelectAll();
                }
            },
            "deSelectAll": {
              name: "Deseleccionar todo", 
              icon: "far fa-list-alt",
              callback: function(itemKey, opt, e) {
                    dataTableDeselectAll();
                }
            },
            "exportExcel": {
              name: "Exportar a Excel", 
              icon: "fas fa-file-excel",
              callback: function(itemKey, opt, e) {
                    exportExcel();
                }
            },
            "exportPDF": {
              name: "Exportar a PDF", 
              icon: "fas fa-file-pdf",
              callback: function(itemKey, opt, e) {
                    exportPDF();
                }
            }
        },
        params : {
              'codconcepto' : function(e){return codconcepto;},
              'desconcepto' : function(e){return desconcepto;},
              'aplica_tabla_porc' : function(e){return aplica_tabla_porc;},
              'vlr_base' : function(e){return vlr_base;},
              'aplica_cree' : function(e){return aplica_cree;},
              'causa_gasto' : function(e){return causa_gasto;},
              'codtipo_cuenta' : function(e){return codtipo_cuenta;},
              'cruza_rodamiento' : function(e){return cruza_rodamiento;},
              'cuenta_contable' : function(e){return cuenta_contable;},
              'impto_finan' : function(e){return impto_finan;},
              'modulo_uso' : function(e){return modulo_uso;},
              'naturaleza' : function(e){return naturaleza;},
              'porc_aplica' : function(e){return porc_aplica;},
              'porc_aplica2' : function(e){return porc_aplica2;},
              'solicita_centro_co' : function(e){return solicita_centro_co;},
              'solicita_codcuenta' : function(e){return solicita_codcuenta;},
              'solicita_det_fact' : function(e){return solicita_det_fact;},
              'solicita_documento' : function(e){return solicita_documento;},
              'solicita_tercero' : function(e){return solicita_tercero;},
          },
        columns : [
            { "title" : "Código concepto", "data" : "row_id", className: "table_align_center"},
            { "title" : "Descripci&oacute;n concepto", "data" : "desconcepto", className: "table_align_center" },
            { "title" : "Aplica % tasa base", "data" : "aplica_tabla_porc", className: "table_align_center" },
            { "title" : "Valor base liquidación", "data" : "vlr_base", className: "table_align_center" },
            { "title" : "Aplica CREE", "data" : "aplica_cree", className: "table_align_center" },
            { "title" : "Causa gasto", "data" : "causa_gasto", className: "table_align_center" },
            { "title" : "Tipo de cuenta", "data" : "codtipo_cuenta", className: "table_align_center" },
            { "title" : "Cruza rodamiento", "data" : "cruza_rodamiento", className: "table_align_center" },
            { "title" : "Cuenta contable", "data" : "cuenta_contable", className: "table_align_center" },
            { "title" : "Impuesto financiero", "data" : "impto_finan", className: "table_align_center" },
            { "title" : "Modulo de uso", "data" : "modulo_uso", className: "table_align_center" },
            { "title" : "Naturaleza", "data" : "naturaleza", className: "table_align_center" },
            { "title" : "1° % por concepto", "data" : "porc_aplica", className: "table_align_center" },
            { "title" : "2° % por concepto", "data" : "porc_aplica2", className: "table_align_center" },
            { "title" : "Centro costos", "data" : "solicita_centro_co", className: "table_align_center" },
            { "title" : "Código cuenta", "data" : "solicita_codcuenta", className: "table_align_center" },
            { "title" : "Detalles de producto", "data" : "solicita_det_fact", className: "table_align_center" },
            { "title" : "Número documento", "data" : "solicita_documento", className: "table_align_center" },
            { "title" : "Identificación tercero", "data" : "solicita_tercero", className: "table_align_center" },
        ]
      })
      
    ).done(function(){     
      $("#create_button").on("click", open_new_recordConceptMvto);
      $("#disable_button").on("click", deleteRecordConceptMvto);
      $("#edit_button").on("click", loadEditModalCompMvto);
      $("#advance_search_button").on("click", open_advance_search_ConceptMvto); 
    }).done(function(){
      endPreloader();
    })
  });
}

function executeSearchConceptMvto(){
    codconcepto = $("#codconcepto_advance").val();
    desconcepto = $("#desconcepto_advance").val();
    aplica_tabla_porc = $("#aplica_tabla_porc_advance").val();
    vlr_base = $("#vlr_base_advance").val();
    aplica_cree = $("#aplica_cree_advance").val();
    causa_gasto = $("#causa_gasto_advance").val();
    codtipo_cuenta = $("#codtipo_cuenta_advance").val();
    cruza_rodamiento = $("#cruza_rodamiento_advance").val();        
    cuenta_contable = $("#cuenta_contable_advance").val();        
    impto_finan = $("#impto_finan_advance").val();        
    modulo_uso = $("#modulo_uso_advance").val();        
    naturaleza = $("#naturaleza_advance").val();        
    porc_aplica = $("#porc_aplica_advance").val();        
    porc_aplica2 = $("#porc_aplica2_advance").val();        
    solicita_centro_co = $("#solicita_centro_co_advance").val();        
    solicita_codcuenta = $("#solicita_codcuenta_advance").val();        
    solicita_det_fact = $("#solicita_det_fact_advance").val();        
    solicita_documento = $("#solicita_documento_advance").val();        
    solicita_tercero = $("#solicita_tercero_advance").val();        
    setTimeout(function(){reloadTable();},500);    
}


function open_advance_search_ConceptMvto(){
    var element = $("#advanceSearch_modal_concept_mov")[0];
    modalStyle({
        html:element, 
        header:"Busqueda avanzada concepto de movimiento",
        myFunction : executeSearchConceptMvto,
        button_text:"Buscar",
        cancelButton: closeModal
    });
    setTimeout(function(){
      startSelect2([
            'aplica_tabla_porc_advance',
            'solicita_tercero_advance',
            'impto_finan_advance',
            'solicita_documento_advance',
            'solicita_centro_co_advance',
            'solicita_det_fact_advance',
            'solicita_codcuenta_advance',
            'modulo_uso_advance',
            'cruza_rodamiento_advance',
            'aplica_cree_advance',
            'codtipo_cuenta_advance',
            'causa_gasto_advance',
            'naturaleza_advance',
          ]);
    },100);
}

 
function open_new_recordConceptMvto(){ 
  var element = $("#newRecord_modal_concept_mov")[0];
   $.ajax({
        type: "POST",        
        dataType: "json",       
        url: "index.php",
        data: {
            metodo: 'loadNextNumber', 
            controlador: 'conceptos_mvto'
        },   
        beforeSend: function(){
            startWait();
        },     
        success: function(data) { 
            endWait(); 
            $("#codconcepto").val(data[0].last);
            modalStyle({
                html:element, 
                header:"Nuevo concepto de movimiento",
                myFunction : create_conceptos_mvto,
                button_text:"Crear",
                cancelButton: closeModal
            });
            setTimeout(function(){
              autocomplete({
                id: 'cuenta_contable',
                controlador : 'conceptos_mvto',
                metodo : 'fillOutAutocompleteCuenta'
              });
              startSelect2([
                    'aplica_tabla_porc',
                    'solicita_tercero',
                    'impto_finan',
                    'solicita_documento',
                    'solicita_centro_co',
                    'solicita_det_fact',
                    'solicita_codcuenta',
                    'modulo_uso',
                    'cruza_rodamiento',
                    'aplica_cree',
                    'codtipo_cuenta',
                    'causa_gasto',
                    'naturaleza',
                  ]);
            },100);           
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });       
}


function create_conceptos_mvto(){
    var parametros = {
            'codconcepto' : $("#codconcepto").val(),
            'desconcepto' : $("#desconcepto").val(),
            'aplica_tabla_porc' : $("#aplica_tabla_porc").val(),
            'vlr_base' : $("#vlr_base").val(),
            'aplica_cree' : $("#aplica_cree").val(),
            'causa_gasto' : $("#causa_gasto").val(),
            'codtipo_cuenta' : $("#codtipo_cuenta").val(),
            'cruza_rodamiento' : $("#cruza_rodamiento").val(),
            'cuenta_contable' : $("#cuenta_contable").val(),
            'impto_finan' : $("#impto_finan").val(),
            'modulo_uso' : $("#modulo_uso").val(),
            'naturaleza' : $("#naturaleza").val(),
            'porc_aplica' : $("#porc_aplica").val(),
            'porc_aplica2' : $("#porc_aplica2").val(),
            'solicita_centro_co' : $("#solicita_centro_co").val(),
            'solicita_codcuenta' : $("#solicita_codcuenta").val(),
            'solicita_det_fact' : $("#solicita_det_fact").val(),
            'solicita_documento' : $("#solicita_documento").val(),
            'solicita_tercero' : $("#solicita_tercero").val(),
        } 
    $.ajax({
        type: "POST",        
        dataType: "json",       
        url: "index.php",
        data: {
            metodo: 'createRecord', 
            controlador: 'conceptos_mvto',
            param: parametros
        },   
        beforeSend: function(){
            startWait();
        },     
        success: function(data) { 
            endWait();           
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.success=='success') {
                        setTimeout(function(){reloadTable();},500);
                        setTimeout(function(){closeModal();},500);
                    }
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}


function loadEditModalCompMvto(){ 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'returnEditModal', 
            controlador: 'conceptos_mvto', 
            param: item_row
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();
            if (data.success=="error") {
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                });
            } else{
                var html = $.parseHTML(data.html[0]);
                var values = data.html[1];
                idEdit = values.codconcepto;
                var element = $("#editRecord_modal_concept_mov")[0];
                
                setTimeout(function(){
                    $("#codconcepto_edit").val(values.codconcepto);
                    $("#desconcepto_edit").val(values.desconcepto);
                    $("#aplica_tabla_porc_edit").val(values.aplica_tabla_porc);
                    $("#vlr_base_edit").val(values.vlr_base);
                    $("#aplica_cree_edit").val(values.aplica_cree);
                    $("#causa_gasto_edit").val(values.causa_gasto);
                    $("#codtipo_cuenta_edit").val(values.codtipo_cuenta);
                    $("#cruza_rodamiento_edit").val(values.cruza_rodamiento);
                    $("#impto_finan_edit").val(values.impto_finan);
                    $("#modulo_uso_edit").val(values.modulo_uso);
                    $("#naturaleza_edit").val(values.naturaleza);
                    $("#porc_aplica_edit").val(values.porc_aplica);
                    $("#porc_aplica2_edit").val(values.porc_aplica2);
                    $("#solicita_centro_co_edit").val(values.solicita_centro_co);
                    $("#solicita_codcuenta_edit").val(values.solicita_codcuenta);
                    $("#solicita_det_fact_edit").val(values.solicita_det_fact);
                    $("#solicita_documento_edit").val(values.solicita_documento);
                    $("#solicita_tercero_edit").val(values.solicita_tercero); 

                    setTimeout(function(){
                      autocomplete({
                        id: 'cuenta_contable_edit',
                        controlador : 'conceptos_mvto',
                        metodo : 'fillOutAutocompleteCuenta'
                      });
                      startSelect2([
                            'aplica_tabla_porc_edit',
                            'solicita_tercero_edit',
                            'impto_finan_edit',
                            'solicita_documento_edit',
                            'solicita_centro_co_edit',
                            'solicita_det_fact_edit',
                            'solicita_codcuenta_edit',
                            'modulo_uso_edit',
                            'cruza_rodamiento_edit',
                            'aplica_cree_edit',
                            'codtipo_cuenta_edit',
                            'causa_gasto_edit',
                            'naturaleza_edit',
                          ]);
                    },100);
                    setTimeout(function(){
                      $("#cuenta_contable_edit").select2("trigger", "select", {
                        data: { id: values.cuenta_contable, text: (values.cuenta_contable !=='') 
                        ? values.cuenta_contable+' : '+values.cuenta_contable_text : '' }
                    });
                    },100);                    
                },100);

                setTimeout(function() {
                  modalStyle({
                    html:element, 
                    header:"Edición concepto de movimiento",
                    myFunction : updateRecordConceptMvto,
                    button_text:"Editar",
                    dataValue:values,
                    cancelButton: closeModal
                  });
                }, 100);
            }
            
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}


function updateRecordConceptMvto(){ 
    var parametros = {
            'codconcepto' : $("#codconcepto_edit").val(),
            'desconcepto' : $("#desconcepto_edit").val(),
            'aplica_tabla_porc' : $("#aplica_tabla_porc_edit").val(),
            'vlr_base' : $("#vlr_base_edit").val(),
            'aplica_cree' : $("#aplica_cree_edit").val(),
            'causa_gasto' : $("#causa_gasto_edit").val(),
            'codtipo_cuenta' : $("#codtipo_cuenta_edit").val(),
            'cruza_rodamiento' : $("#cruza_rodamiento_edit").val(),
            'impto_finan' : $("#impto_finan_edit").val(),
            'modulo_uso' : $("#modulo_uso_edit").val(),
            'naturaleza' : $("#naturaleza_edit").val(),
            'porc_aplica' : $("#porc_aplica_edit").val(),
            'porc_aplica2' : $("#porc_aplica2_edit").val(),
            'solicita_centro_co' : $("#solicita_centro_co_edit").val(),
            'solicita_codcuenta' : $("#solicita_codcuenta_edit").val(),
            'solicita_det_fact' : $("#solicita_det_fact_edit").val(),
            'solicita_documento' : $("#solicita_documento_edit").val(),
            'solicita_tercero' : $("#solicita_tercero_edit").val(),
        } 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'editRecord', 
            controlador: 'conceptos_mvto', 
            param: parametros
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();     
            if (data.success=='success') {
                closeModal();
                reloadTable();
            }      
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}


function deleteRecordConceptMvto(){
  var plural = "el registro seleccionado";
  var item_count = item_row.length;
  if (item_count < 1) {
    Swal.fire({
            title: 'Error!',
            text: 'Por favor seleccione un registro para eliminar',
            icon: "error",
          });
  }else {
    if (item_count > 1) {
      plural = "los registros seleccionados";
    }
    Swal.fire({
      title: 'Advertencia!',
      text: "Segur@ que desea eliminar "+plural+"?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Continuar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.value) {
            $.ajax({
            type: "POST",        
            url: "index.php",
            dataType: "json", 
            data: {
                metodo: 'deleteRecord', 
                controlador: 'conceptos_mvto', 
                param: item_row
            }, 
            beforeSend: function(){
                startWait();
            },     
            success: function(data) {
                endWait();
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                }).then((result) => {
                    if (result.value) {
                        if (data.success=='success') {
                            setTimeout(function(){reloadTable();},500);
                        }
                    }                
                });
            },
            error:function (jqXHR,textStatus) {
                endWait();
                Swal.fire({
                    title: 'Error!',
                    text: jqXHR.responseText,
                    icon: "error",
                  });
                }  
        })
      }
    })
  }
}


///////////////// P5 Grupo concepto ///////////////////////////
let exist;

function start_grupo_concepto() {
  codconcepto = '';
  codconcepto_base = '';
  tp_tercero = '';
  tp_regimen = '';
  g_contribuyente = '';
  auto_retenedor = '';
  ext_reteica = '';
  auto_reteica = '';
  $("#index_zonas").empty();
  codconcepto='';
    $.when(
      $.ajax({
        url: 'index.php',
        type: 'post',
        data: {
          controlador: 'grupo_concepto',
          metodo: 'index'
        },
        beforeSend: function () {
          preloader();
        },
        success: function (data) {      
            $('#index_zonas').html(data);
        },
        error:function (jqXHR,textStatus) {
              Swal.fire({
                  title: 'Error!',
                  text: jqXHR.responseText,
                  icon: "error",
                });
          } 
      })
    ).done(function(){
      $.when(
      startDataTable({
        idTable : 'table_grupo_concept',
        controller : 'grupo_concepto',
        method : 'reloadTable',
        responsive: true,
        scrollX: false,
        autoWidth: false,
        contextMenu: true,
        contextMenu_items: {
           "add": {
                name: "Agregar concepto al grupo", 
                icon: "fas fa-plus-circle", 
                callback: function(itemKey, opt, e) {
                    open_new_recordGrupoConcept();
                }
            },
            "disable": {
              name: "Eliminar concepto del grupo", 
              icon: "fas fa-trash-alt",
              callback: function(itemKey, opt, e) {
                    deleteRecordGrupoConcept();
                }
            },
            "selectAll": {
              name: "Seleccionar todo", 
              icon: "fas fa-list-alt",
              callback: function(itemKey, opt, e) {
                    dataTableSelectAll();
                }
            },
            "deSelectAll": {
              name: "Deseleccionar todo", 
              icon: "far fa-list-alt",
              callback: function(itemKey, opt, e) {
                    dataTableDeselectAll();
                }
            },
            "exportExcel": {
              name: "Exportar a Excel", 
              icon: "fas fa-file-excel",
              callback: function(itemKey, opt, e) {
                    exportExcel();
                }
            },
            "exportPDF": {
              name: "Exportar a PDF", 
              icon: "fas fa-file-pdf",
              callback: function(itemKey, opt, e) {
                    exportPDF();
                }
            }
        },
        params : {
              'codconcepto' : function(e){return codconcepto;},
              'codconcepto_base' : function(e){return codconcepto_base;},
              'tp_tercero' : function(e){return tp_tercero;},
              'tp_regimen' : function(e){return tp_regimen;},
              'g_contribuyente' : function(e){return g_contribuyente;},
              'auto_retenedor' : function(e){return auto_retenedor;},
              'ext_reteica' : function(e){return ext_reteica;},
              'auto_reteica' : function(e){return auto_reteica;},
          },
        columns : [
            { "title" : "Concepto Agrupador", "data" : "codconcepto", className: "table_align_center"},
            { "title" : "Código concepto", "data" : "row_id", className: "table_align_center"},
            { "title" : "Descripci&oacute;n concepto", "data" : "codconcepto_base_text", className: "table_align_center" },
            { "title" : "Tercero", "data" : "tp_tercero", className: "table_align_center" },
            { "title" : "Regimen", "data" : "tp_regimen", className: "table_align_center" },
            { "title" : "Gran contribuyente", "data" : "g_contribuyente", className: "table_align_center" },
            { "title" : "Autoretenedor", "data" : "auto_retenedor", className: "table_align_center" },
            { "title" : "Exento reteica", "data" : "ext_reteica", className: "table_align_center" },
            { "title" : "Auto reteica", "data" : "auto_reteica", className: "table_align_center" },
        ],
        columnDefs : [
          {
           targets: [0,3,4,5,6,7,8],
           visible: false,
          },
          { 
            "width": "30%", 
            "targets": 1 
          },
          { 
            "width": "70%", 
            "targets": 2 
          }
        ]
      })
      
    ).done(function(){     
      $("#create_button").on("click", open_new_recordGrupoConcept);
      $("#disable_button").on("click", deleteRecordGrupoConcept);
      $("#edit_button").on("click", loadDetailsHeaderGrupoConcept);
      $("#advance_search_button").on("click", open_advance_search_GrupoConcept); 
      $("#details_button").on("click", open_details); 
    }).done(function(){
      autocomplete({
        id: 'codconcepto',
        controlador: 'grupo_concepto',
        metodo : 'fillOutAutocompleteConceptoMvto', 
        events : [
          {
            event : 'select2:select',
            action : 'setTimeout(function(){codconcepto = \'\';},100);'+
            'setTimeout(function(){codconcepto = $("#codconcepto").val();'+
            'loadDetailsHeaderGrupoConcept();},100);'+
            'setTimeout(function(){reloadTable();},100);'
          }
        ]
      });
    }).done(function(){
      $("#config_panel").hide();
      setTimeout(function(){
        autocomplete({
          id: 'codconcepto_base',
          controlador : 'grupo_concepto',
          metodo : 'fillOutAutocompleteConceptoMvtoGroup'
        });
      startSelect2([
          {
            id : 'tp_tercero',
            events : [
              {
                event : 'change.select2',
                action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
              }
            ]
          },
          {
            id : 'tp_regimen',
            events : [
              {
                event : 'change.select2',
                action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
              }
            ]
          },
          {
            id : 'g_contribuyente',
            events : [
              {
                event : 'change.select2',
                action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
              }
            ]
          },
          {
            id : 'auto_retenedor',
            events : [
              {
                event : 'change.select2',
                 action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
              }
            ]
          },
          {
            id : 'ext_reteica',
            events : [
              {
                event : 'change.select2',
                action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
              }
            ]
          },
          {
            id : 'auto_reteica',
            events : [
              {
                event : 'change.select2',
                action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
              }
            ]
          }
          ]);
      startSelect2([
          'tp_tercero_add',
          'tp_regimen_add',
          'g_contribuyente_add',
          'auto_retenedor_add',
          'ext_reteica_add',
          'auto_reteica_add',          
        ]);
      },100);
    }).done(function(){
      endPreloader();
    })
  });
}

function open_details(){
  if (codconcepto == '') {
     Swal.fire({
          title: 'Error!',
          text: 'Por favor seleccione un concepto para verificar el detalle.',
          icon: 'warning',
      });
  } else{
    var parametros = {
            'codconcepto' : codconcepto,
        } 
    var dataResponse = '';
    var html = '';
    $.when(
    $.ajax({
        type: "POST",        
        dataType: "json",       
        url: "index.php",
        data: {
            metodo: 'reloadTable', 
            controlador: 'conceptos_mvto',
            param: parametros
        },   
        beforeSend: function(){
            startWait();
        },     
        success: function(data) { 
            endWait(); 
            dataResponse = data.data[0];          
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    })).then(
    function(){
      html += '<div class="form-row">';
      html += '<div class="col">';
      html += '<ul class="list-group">';
      html += '<li class="list-group-item"><b>Concepto: </b>'+dataResponse.row_id+' : '+dataResponse.desconcepto+'</li>';
      html += '<li class="list-group-item"><b>Aplica % tasa base: </b>'+dataResponse.aplica_tabla_porc+'</li>';
      html += '<li class="list-group-item"><b>Valor base liquidación: </b>'+dataResponse.vlr_base+'</li>';
      html += '<li class="list-group-item"><b>Aplica CREE: </b>'+dataResponse.aplica_cree+'</li>';
      html += '<li class="list-group-item"><b>Causa gasto: </b>'+dataResponse.causa_gasto+'</li>';
      html += '<li class="list-group-item"><b>Tipo de cuenta: </b>'+dataResponse.codtipo_cuenta+'</li>';
      html += '<li class="list-group-item"><b>Cruza rodamiento: </b>'+dataResponse.cruza_rodamiento+'</li>';
      html += '<li class="list-group-item"><b>Cuenta contable: </b>'+dataResponse.cuenta_contable+'</li>';
      html += '<li class="list-group-item"><b>Impuesto financiero: </b>'+dataResponse.impto_finan+'</li>';
      html += '</ul>';
      html += '</div>';
      html += '<div class="col">';
      html += '<ul class="list-group">';
      html += '<li class="list-group-item"><b>Módulo de uso: </b>'+dataResponse.modulo_uso+'</li>';
      html += '<li class="list-group-item"><b>Naturaleza: </b>'+dataResponse.naturaleza+'</li>';
      html += '<li class="list-group-item"><b>1° % por concepto: </b>'+dataResponse.porc_aplica+'</li>';
      html += '<li class="list-group-item"><b>2° % por concepto: </b>'+dataResponse.porc_aplica2+'</li>';
      html += '<li class="list-group-item"><b>Centro de costos: </b>'+dataResponse.porc_aplica2+'</li>';
      html += '<li class="list-group-item"><b>Código de cuenta: </b>'+dataResponse.codtipo_cuenta+'</li>';
      html += '<li class="list-group-item"><b>Detalles de producto: </b>'+dataResponse.solicita_det_fact+'</li>';
      html += '<li class="list-group-item"><b>Número documento: </b>'+dataResponse.solicita_documento+'</li>';
      html += '<li class="list-group-item"><b>Solicita tercero: </b>'+dataResponse.solicita_tercero+'</li>';
      html += '</ul>';
      html += '</div>';
      html += '</div>';

    }).then(
      function(){
        modalStyle({
          body:html, 
          header:"Detalles del concepto",
          cancelButton: closeModal,
          width:'750'
      });
    })
  }

    
}


function executeSearchGrupoConcept(){
    codconcepto = $("#codconcepto").val();
    codconcepto_base = $("#codconcepto_base_advance").val();
    tp_tercero = $("#tp_tercero_advance").val();
    tp_regimen = $("#tp_regimen_advance").val();
    g_contribuyente = $("#g_contribuyente_advance").val();
    auto_retenedor = $("#auto_retenedor_advance").val();
    ext_reteica = $("#ext_reteica_advance").val();
    auto_reteica = $("#auto_reteica_advance").val();             
    setTimeout(function(){reloadTable();},500);    
}


function open_advance_search_GrupoConcept(){
    var element = $("#advanceSearch_modal_grupo_concept")[0];
    modalStyle({
        html:element, 
        header:"Busqueda avanzada grupo de concepto",
        myFunction : executeSearchGrupoConcept,
        button_text:"Buscar",
        cancelButton: closeModal
    });
}

 
function open_new_recordGrupoConcept(){ 
 if (codconcepto == '') {
     Swal.fire({
          title: 'Error!',
          text: 'Por favor seleccione un concepto de moviemiento para agregar un concepto base.',
          icon: 'warning',
      });
  } else{
    var element = $("#newRecord_modal_grupo_concept")[0];
    modalStyle({
        html:element, 
        header:"Nuevo concepto al grupo",
        myFunction : create_grupo_concepto,
        button_text:"Agregar",
        cancelButton: closeModal,
        width: 450
    });   
    }    
}


function create_grupo_concepto(){
    var parametros = {
            'codconcepto' : $("#codconcepto").val(),
            'codconcepto_base' : $("#codconcepto_base").val(),
            'tp_tercero' : $("#tp_tercero_add").val(),
            'tp_regimen' : $("#tp_regimen_add").val(),
            'g_contribuyente' : $("#g_contribuyente_add").val(),
            'auto_retenedor' : $("#auto_retenedor_add").val(),
            'ext_reteica' : $("#ext_reteica_add").val(),
            'auto_reteica' : $("#auto_reteica_add").val(),
        } 
    $.ajax({
        type: "POST",        
        dataType: "json",       
        url: "index.php",
        data: {
            metodo: 'createRecord', 
            controlador: 'grupo_concepto',
            param: parametros
        },   
        beforeSend: function(){
            startWait();
        },     
        success: function(data) { 
            endWait();           
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.success=='success') {
                        setTimeout(function(){reloadTable();},200);
                        if(!exist){
                          setTimeout(function(){loadDetailsHeaderGrupoConcept();},300);  
                        }                        
                        setTimeout(function(){closeModal();},400);
                    }
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}

function loadDetailsHeaderGrupoConcept(){ 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'returnEditModal', 
            controlador: 'grupo_concepto', 
            param: '',
            codconcepto : codconcepto
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();
            if (data.details.length > 0) {
                var values = data.details[0];
                setTimeout(function(){
                    $("#tp_tercero").val(values.tp_tercero);
                    $("#tp_regimen").val(values.tp_regimen);
                    $("#g_contribuyente").val(values.g_contribuyente);
                    $("#auto_retenedor").val(values.auto_retenedor);
                    $("#ext_reteica").val(values.ext_reteica);
                    $("#auto_reteica").val(values.auto_reteica);  

                    $("#tp_tercero_add").val(values.tp_tercero);
                    $("#tp_regimen_add").val(values.tp_regimen);
                    $("#g_contribuyente_add").val(values.g_contribuyente);
                    $("#auto_retenedor_add").val(values.auto_retenedor);
                    $("#ext_reteica_add").val(values.ext_reteica);
                    $("#auto_reteica_add").val(values.auto_reteica);                    
                },100);
                  setTimeout(function(){$("#config_panel").show();},200);
                  setTimeout(function(){$("#config_panel_add").hide();},200);
                  exist = true;
                } else{
                  exist =false;
                  setTimeout(function(){$("#config_panel").hide();},100);
                  setTimeout(function(){$("#config_panel_add").show();},100);
                  setTimeout(function(){
                    $("#tp_tercero").val('');
                    $("#tp_regimen").val('');
                    $("#g_contribuyente").val('');
                    $("#auto_retenedor").val('');
                    $("#ext_reteica").val('');
                    $("#auto_reteica").val('');                    
                  },200);
                }
                setTimeout(function(){
                  startSelect2([
                      {
                        id : 'tp_tercero',
                        events : [
                          {
                            event : 'change.select2',
                            action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
                          }
                        ]
                      },
                      {
                        id : 'tp_regimen',
                        events : [
                          {
                            event : 'change.select2',
                            action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
                          }
                        ]
                      },
                      {
                        id : 'g_contribuyente',
                        events : [
                          {
                            event : 'change.select2',
                            action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
                          }
                        ]
                      },
                      {
                        id : 'auto_retenedor',
                        events : [
                          {
                            event : 'change.select2',
                            action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
                          }
                        ]
                      },
                      {
                        id : 'ext_reteica',
                        events : [
                          {
                            event : 'change.select2',
                            action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
                          }
                        ]
                      },
                      {
                        id : 'auto_reteica',
                        events : [
                          {
                            event : 'change.select2',
                            action : 'setTimeout(function(){updateRecordGrupoConcept();},100);'
                          }
                        ]
                      }
                      ]);
                },100);
            
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}


function updateRecordGrupoConcept(){ 
    var parametros = {
            'codconcepto' : $("#codconcepto").val(),
            'tp_tercero' : $("#tp_tercero").val(),
            'tp_regimen' : $("#tp_regimen").val(),
            'g_contribuyente' : $("#g_contribuyente").val(),
            'auto_retenedor' : $("#auto_retenedor").val(),
            'ext_reteica' : $("#ext_reteica").val(),
            'auto_reteica' : $("#auto_reteica").val(),
        } 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'editRecord', 
            controlador: 'grupo_concepto', 
            param: parametros
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();     
            if (data.success=='success') {
                closeModal();
                reloadTable();
            }      
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}


function deleteRecordGrupoConcept(){
  var plural = "el registro seleccionado";
  var item_count = item_row.length;
  if (item_count < 1) {
    Swal.fire({
            title: 'Error!',
            text: 'Por favor seleccione un registro para eliminar',
            icon: "error",
          });
  }else {
    if (item_count > 1) {
      plural = "los registros seleccionados";
    }
    Swal.fire({
      title: 'Advertencia!',
      text: "Segur@ que desea eliminar "+plural+"?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Continuar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.value) {
            $.ajax({
            type: "POST",        
            url: "index.php",
            dataType: "json", 
            data: {
                metodo: 'deleteRecord', 
                controlador: 'grupo_concepto', 
                param: item_row,
                codconcepto : codconcepto
            }, 
            beforeSend: function(){
                startWait();
            },     
            success: function(data) {
                endWait();
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                }).then((result) => {
                    if (result.value) {
                      if (data.success=='success') {
                        $.when(reloadTable()).then(function(){
                            loadDetailsHeaderGrupoConcept()
                          })
                        }
                      }                
                });
            },
            error:function (jqXHR,textStatus) {
                endWait();
                Swal.fire({
                    title: 'Error!',
                    text: jqXHR.responseText,
                    icon: "error",
                  });
                }  
        })
      }
    })
  }
}


///////////////// P6 Grupo concepto ///////////////////////////


function start_conc_munic() {
  $("#index_zonas").empty();
  codconcepto='';
  codmunicipio='';
  base_liquidacion='';
  porc_aplica='';
  aplica_ica_tercero='';
    $.when(
      $.ajax({
        url: 'index.php',
        type: 'post',
        data: {
          controlador: 'conc_munic',
          metodo: 'index'
        },
        beforeSend: function () {
          preloader();
        },
        success: function (data) {      
            $('#index_zonas').html(data);
        },
        error:function (jqXHR,textStatus) {
              Swal.fire({
                  title: 'Error!',
                  text: jqXHR.responseText,
                  icon: "error",
                });
          } 
      })
    ).done(function(){
      $.when(
      startDataTable({
        idTable : 'table_conc_munic',
        controller : 'conc_munic',
        method : 'reloadTable',
        responsive: true,
        scrollX: false,
        autoWidth: false,
        contextMenu: true,
        contextMenu_items: {
           "add": {
                name: "Agregar concepto al municipio", 
                icon: "fas fa-plus-circle", 
                callback: function(itemKey, opt, e) {
                    open_new_recordConcMunic();
                }
            },
            "edit": {
              name: "Editar concepto", 
              icon: "edit",
              callback: function(itemKey, opt, e) {
                    loadEditModalConcMunic();
                }
            },
            "disable": {
              name: "Eliminar concepto del municipio", 
              icon: "fas fa-trash-alt",
              callback: function(itemKey, opt, e) {
                    deleteRecordConcMunic();
                }
            },
            "selectAll": {
              name: "Seleccionar todo", 
              icon: "fas fa-list-alt",
              callback: function(itemKey, opt, e) {
                    dataTableSelectAll();
                }
            },
            "deSelectAll": {
              name: "Deseleccionar todo", 
              icon: "far fa-list-alt",
              callback: function(itemKey, opt, e) {
                    dataTableDeselectAll();
                }
            },
            "exportExcel": {
              name: "Exportar a Excel", 
              icon: "fas fa-file-excel",
              callback: function(itemKey, opt, e) {
                    exportExcel();
                }
            },
            "exportPDF": {
              name: "Exportar a PDF", 
              icon: "fas fa-file-pdf",
              callback: function(itemKey, opt, e) {
                    exportPDF();
                }
            }
        },
        params : {
              'codmunicipio' : function(e){return codmunicipio;},
              'codconcepto' : function(e){return codconcepto;},
              'base_liquidacion' : function(e){return base_liquidacion;},
              'porc_aplica' : function(e){return porc_aplica;},
              'aplica_ica_tercero' : function(e){return aplica_ica_tercero;},
          },
        columns : [
            { "title" : "Municipio", "data" : "codmunicipio", className: "table_align_center"},
            { "title" : "Código concepto", "data" : "row_id", className: "table_align_center"},
            { "title" : "Descripci&oacute;n concepto", "data" : "codconcepto_text", className: "table_align_center" },
            { "title" : "Base de liquidación del concepto", "data" : "base_liquidacion", className: "table_align_center" },
            { "title" : "Porcentaje a aplicar", "data" : "porc_aplica", className: "table_align_center" },
            { "title" : "Aplicar con base en IyC del tercero", "data" : "aplica_ica_tercero", className: "table_align_center" },
        ],
        columnDefs : [
          {
           targets: 0,
           visible: false,
          }
        ]
      })
      
    ).done(function(){     
      $("#create_button").on("click", open_new_recordConcMunic);
      $("#disable_button").on("click", deleteRecordConcMunic);
      $("#edit_button").on("click", loadEditModalConcMunic);
      $("#advance_search_button").on("click", open_advance_search_ConcMunic); 
    }).done(function(){
      autocomplete({
        id: 'codmunicipio',
        controlador: 'conc_munic',
        metodo : 'fillOutAutocompleteMunicipio', 
        events : [
          {
            event : 'select2:select',
            action : 'setTimeout(function(){codmunicipio = \'\';},100);'+
            'setTimeout(function(){codmunicipio = $("#codmunicipio").val();},100);'+
            'setTimeout(function(){reloadTable();},100);'
          }
        ]
      });
      setTimeout(function(){
        startSelect2([
            'aplica_ica_tercero_advance',
          ]);
      },100);
      setTimeout(function(){
        autocomplete({
          id: 'codconcepto',
          controlador : 'conc_munic',
          metodo : 'fillOutAutocompleteConceptoMvto'
        });
        startSelect2([
            'aplica_ica_tercero',
          ]);
      },100);
      setTimeout(function(){
          autocomplete({
            id: 'codconcepto_edit',
            controlador : 'conc_munic',
            metodo : 'fillOutAutocompleteConceptoMvto',
            disabled : true
          });
          startSelect2([
                'aplica_ica_tercero_edit',
              ]);
        },100);
    }).done(function(){
      endPreloader();
    })
  });
}


function executeSearchConcMunic(){
    codmunicipio = $("#codmunicipio").val();
    codconcepto = $("#codconcepto_advance").val();
    base_liquidacion = $("#base_liquidacion_advance").val();
    porc_aplica = $("#porc_aplica_advance").val();
    aplica_ica_tercero = $("#aplica_ica_tercero_advance").val();          
    setTimeout(function(){reloadTable();},500);    
}


function open_advance_search_ConcMunic(){
    var element = $("#advanceSearch_modal_conc_munic")[0];
    modalStyle({
        html:element, 
        header:"Busqueda avanzada grupo de concepto",
        myFunction : executeSearchConcMunic,
        button_text:"Buscar",
        cancelButton: closeModal,
        width:450
    });
    
}

 
function open_new_recordConcMunic(){ 

    var element = $("#newRecord_modal_conc_munic")[0];
    modalStyle({
        html:element, 
        header:"Nuevo concepto al municipio",
        myFunction : create_conc_munic,
        button_text:"Agregar",
        cancelButton: closeModal,
        width : 450
    });
    
       
}


function create_conc_munic(){
    var parametros = {
            'codmunicipio' : $("#codmunicipio").val(),
            'codconcepto' : $("#codconcepto").val(),
            'base_liquidacion' : $("#base_liquidacion").val(),
            'porc_aplica' : $("#porc_aplica").val(),
            'aplica_ica_tercero' : $("#aplica_ica_tercero").val(),
        } 
    $.ajax({
        type: "POST",        
        dataType: "json",       
        url: "index.php",
        data: {
            metodo: 'createRecord', 
            controlador: 'conc_munic',
            param: parametros
        },   
        beforeSend: function(){
            startWait();
        },     
        success: function(data) { 
            endWait();           
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.success=='success') {
                        setTimeout(function(){reloadTable();},500);
                        setTimeout(function(){closeModal();},500);
                    }
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}

function loadEditModalConcMunic(){ 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'returnEditModal', 
            controlador: 'conc_munic', 
            param: item_row,
            codmunicipio : codmunicipio
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();
            if (data.success=="error") {
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                });
            } else{
                var values = data.details[0];
                var element = $("#editRecord_modal_conc_munic")[0];
                
                setTimeout(function(){
                    $("#codconcepto_edit").val(values.codconcepto);
                    $("#base_liquidacion_edit").val(values.base_liquidacion);
                    $("#porc_aplica_edit").val(values.porc_aplica);
                    $("#aplica_ica_tercero_edit").val(values.aplica_ica_tercero);
                    setTimeout(function(){
                      $("#codconcepto_edit").select2("trigger", "select", {
                        data: { id: values.codconcepto, text: (values.codconcepto !=='') 
                        ? values.codconcepto+' : '+values.codconcepto_text : '' }
                    });
                      startSelect2([
                          'aplica_ica_tercero_edit',
                        ]);
                    },100);                    
                },100);

                setTimeout(function() {
                  modalStyle({
                    html:element, 
                    header:"Edición concepto del municipio",
                    myFunction : updateRecordConcMunic,
                    button_text:"Editar",
                    dataValue:values,
                    cancelButton: closeModal,
                    width : 450
                  });
                }, 100);
            }
            
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}


function updateRecordConcMunic(){ 
    var parametros = {
            'codmunicipio' : $("#codmunicipio").val(),
            'codconcepto' : $("#codconcepto_edit").val(),
            'base_liquidacion' : $("#base_liquidacion_edit").val(),
            'porc_aplica' : $("#porc_aplica_edit").val(),
            'aplica_ica_tercero' : $("#aplica_ica_tercero_edit").val(),
        } 
    $.ajax({
        type: "POST",        
        url: "index.php",
        dataType: "json",
        data: {
            metodo: 'editRecord', 
            controlador: 'conc_munic', 
            param: parametros
        },  
        beforeSend: function(){
            startWait();
        },      
        success: function(data) {
            endWait();     
            if (data.success=='success') {
                closeModal();
                reloadTable();
            }      
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}


function deleteRecordConcMunic(){
  var plural = "el registro seleccionado";
  var item_count = item_row.length;
  if (item_count < 1) {
    Swal.fire({
            title: 'Error!',
            text: 'Por favor seleccione un registro para eliminar',
            icon: "error",
          });
  }else {
    if (item_count > 1) {
      plural = "los registros seleccionados";
    }
    Swal.fire({
      title: 'Advertencia!',
      text: "Segur@ que desea eliminar "+plural+"?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Continuar',
      cancelButtonText: 'Cancelar',
    }).then((result) => {
      if (result.value) {
            $.ajax({
            type: "POST",        
            url: "index.php",
            dataType: "json", 
            data: {
                metodo: 'deleteRecord', 
                controlador: 'conc_munic', 
                param: item_row,
                codmunicipio : codmunicipio
            }, 
            beforeSend: function(){
                startWait();
            },     
            success: function(data) {
                endWait();
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: data.icon,
                }).then((result) => {
                    if (result.value) {
                        if (data.success=='success') {
                            setTimeout(function(){reloadTable();},500);
                        }
                    }                
                });
            },
            error:function (jqXHR,textStatus) {
                endWait();
                Swal.fire({
                    title: 'Error!',
                    text: jqXHR.responseText,
                    icon: "error",
                  });
                }  
        })
      }
    })
  }
}


///////////////// P7 Migración de cajas ///////////////////////////


function start_migra_caja() {
  codsucursal = '';
  $("#index_zonas").empty();
  codconcepto='';
  codmunicipio='';
  base_liquidacion='';
  porc_aplica='';
  aplica_ica_tercero='';
    $.when(
      $.ajax({
        url: 'index.php',
        type: 'post',
        data: {
          controlador: 'migra_caja',
          metodo: 'index'
        },
        beforeSend: function () {
          preloader();
        },
        success: function (data) {      
            $('#index_zonas').html(data);
        },
        error:function (jqXHR,textStatus) {
              Swal.fire({
                  title: 'Error!',
                  text: jqXHR.responseText,
                  icon: "error",
                });
          } 
      })
    ).done(function(){
      $.when(
      startDataTable({
        idTable : 'table_migra_caja',
        controller : 'migra_caja',
        method : 'reloadTable',
        responsive: true,
        scrollX: false,
        autoWidth: false,
        contextMenu: true,
        contextMenu_items: {
           "migrate": {
                name: "Migrar", 
                icon: "fas fa-share-square", 
                callback: function(itemKey, opt, e) {
                    migrateInfo();
                }
            },
            "selectAll": {
              name: "Seleccionar todo", 
              icon: "fas fa-list-alt",
              callback: function(itemKey, opt, e) {
                    dataTableSelectAll();
                }
            },
            "deSelectAll": {
              name: "Deseleccionar todo", 
              icon: "far fa-list-alt",
              callback: function(itemKey, opt, e) {
                    dataTableDeselectAll();
                }
            }
        },
        params : {
              'codsucursal' : function(e){return codsucursal;},
          },
        columns : [
            { "title" : "row_id", "data" : "row_id", className: "table_align_center"},
            { "title" : "Código Sucursal", "data" : "codsucursal", className: "table_align_center"},
            { "title" : "Descripci&oacute;n Sucursal", "data" : "dessucur", className: "table_align_center" },
            { "title" : "Fecha movimiento", "data" : "fecha_cuadre", className: "table_align_center" },
        ],
        columnDefs : [
          {
           targets: 0,
           visible: false,
          },
          { 
            "width": "30%", 
            "targets": 1 
          },
          { 
            "width": "40%", 
            "targets": 2 
          },
          { 
            "width": "30%", 
            "targets": 2 
          }
        ]
      })
      
    ).done(function(){
      autocomplete({
        id: 'codsucursal',
        controlador: 'migra_caja',
        metodo : 'autocomplete_branch_office', 
        events : [
          {
            event : 'select2:select',
            action : 'setTimeout(function(){codsucursal = \'\';},100);'+
            'setTimeout(function(){codsucursal = $("#codsucursal").val();},100);'+
            'setTimeout(function(){reloadTable();},100);'
          }
        ]
      });
    }).done(function(){
      endPreloader();
    })
  });
}


function migrateInfo(){ 
    $.ajax({
        type: "POST",        
        dataType: "json",       
        url: "index.php",
        data: {
            metodo: 'cargaInfo', 
            controlador: 'migra_caja',
            param: item_row,
        },   
        beforeSend: function(){
            startWait();
        },     
        success: function(data) { 
            endWait();           
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
            }).then((result) => {
                if (result.value) {
                    if (data.success) {
                        // setTimeout(function(){reloadTable();},500);
                        // setTimeout(function(){closeModal();},500);
                    }
                    if (data.setFocus) {
                        setTimeout(function(){$("#"+data.setFocus).focus();},500);
                    }
                }
                
            });
        },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: jqXHR.responseText,
                icon: "error",
              });
            }  
    });
}

