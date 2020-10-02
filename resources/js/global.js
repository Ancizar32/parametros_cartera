const item_row = [];
var oTable;
var idEdit;


$(document).ready(function() {
  setTimeout(function(){
   endPreloader();
  }, 2000);
  
});


function getInputFields(idObject){
var params = idObject;

var obj_params = params.reduce((ob, param, pindex)=>{

   if($("#"+param).is("div.jsgrid")){
        var table_items = $("#"+param+" input.sel_chkbx:checked")
         .parents("tr")
         .toArray()
         .map(
        (element)=> $(element)
            .children("td")
            .toArray()
            .filter(cell=> !$(cell).hasClass("jsgrid-control-field"))
         )
         .map(row=> row.reduce((o, cell, index) => {
            var name = $("#"+param).jsGrid("fisdaeldOption", index + 1, "name");
            o[name] = $(cell).text();
            return o;
         },{})
        );

        ob[param] = table_items;

        return ob;

   } else if($("#"+param).is("input") || $("#"+param).is("select") || $("#"+param).is("textarea")){
        ob[param] = $("#"+param).val();
        return ob;
   } else if($("#"+param).length < 1) {
        
   } else {
        ob[param] = $("#"+param).text();
        return ob;
   }

},{});

return obj_params;

}


function autocomplete(options) {
  var element = $("#"+options.id);
      element.select2({
      theme: "bootstrap",
      dropdownParent: $('#'+options.id+'_content'),
      minimumInputLength: (options.minimum) ? options.minimum : 0,
      dropdownAutoWidth : true,
      width: 'auto',
      delay: 250,
      disabled: options.disabled ? options.disabled : false,
       language: {
        noResults: function (params) {
          return "No hay registros";
        },
        searching: function() {
          return "Buscando..";
        },
        inputTooShort: function () {
          if (options.inputTooShort) {
            return options.tooshort;  
          }
          
        }
      },
      ajax:{ 
      transport: function (params, success, failure) {
          if(!success)
                return;
          let additional_params = {
            'controlador' : options.controlador,
            'metodo' : options.metodo,
            'params' : options.parametros 
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

    if (options.events) {
      var eventArray = options.events;
      var rend = '';

      (function() {
      for (var i = 0;  i < eventArray.length; i++) {
        rend += 'element.on("'+eventArray[i].event+'", (event)=>{'+eventArray[i].action+';});';
      }
      return eval(rend);
      })();
    }
    
}


function startSelect2(ids){
  ids.forEach(function(element){
      if (isObject(element)) {
        var element_id = $("#"+element.id);
        if (element.id) {
          $("#"+element.id).select2({
          theme: "bootstrap",
          dropdownParent: $('#'+element.id+'_content'),
          minimumInputLength: 0,
          dropdownAutoWidth : true,
          width: 'auto',
          delay: 250,
           language: {
            noResults: function (params) {
                return "No hay registros";
              }
            }
          });  
        }
         
        if (Array.isArray(element.events)) {
          var eventArray = element.events;
          var rend = '';

          (function() {
          for (var i = 0;  i < eventArray.length; i++) {
            rend += 'element_id.on("'+eventArray[i].event+'", (event)=>{'+eventArray[i].action+';});';
          }
          return eval(rend);
          })();
        }
      }else{
       $("#"+element).select2({
        theme: "bootstrap",
        dropdownParent: $('#'+element+'_content'),
        minimumInputLength: 0,
        dropdownAutoWidth : true,
        width: 'auto',
        delay: 250,
         language: {
          noResults: function (params) {
              return "No hay registros";
            }
          }
        }); 
      }            
    })  
}

function isObject(obj) {
    return (!!obj) && (obj.constructor === Object);
};

function reloadTable(){
  closeModal();
  oTable.ajax.reload();
  var rows = oTable.rows().count();
  for (var i = rows - 1; i >= 0; i--) {
      item_row.splice(i, 1);             
  }
}

function countRowsTable(){
  return oTable.rows().count();
}

function cleanConst(){
  var rows = item_row.length;
  for (var i = rows - 1; i >= 0; i--) {
      item_row.splice(i, 1);             
  }
}

function buildColumnDef(columns){
  columns.forEach(function(item){
    console.log(item.data);
  });
}

// table tipos de egresos
function startDataTable(options){
   cleanConst();
  oTable = $('#'+options.idTable).DataTable({
      searching: true,
       "lengthMenu": [[4, 10, 25, -1], [4, 10, 25, "Todo"]],
       responsive: (options.responsive) ? options.responsive : false,
       scrollX: (options.scrollX) ? options.scrollX : false,
       info:     true,
       colReorder: false,
        "paging":  true,
        "pageLength": 10,
        "autoWidth": (options.autoWidth) ? options.autoWidth : false,
        // processing: true,
        language: {
        search: "_INPUT_",
        searchPlaceholder: "Buscar...",
        "decimal": "",
        "emptyTable": "No hay datos para mostrar",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Registros",
        "infoEmpty": "Mostrando 0 to 0 de 0 Registros",
        "infoFiltered": "(Filtrado de _MAX_ total Registros)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Registros",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "zeroRecords": "Sin resultados encontrados",        
        "oPaginate": {
        "sFirst":    "|<<",
        "sLast":     ">>|",
        "sNext":     ">>",
        "sPrevious": "<<"
      }
    },
    "dom": '<"float-right"l><"float-left"f>rt<"float-right"p><"float-left"i> <"d-none"B>',
     buttons: [
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL'
            },
            'copy', 'csv', 'excel', 'print'
        ],
    "ordering": true,
    "order": [[ 0, "desc" ]],
    'ajax':{
        'url' : 'index.php',
        'type' : 'POST', 
        'data' : {
            'controlador' : options.controller,
            'metodo' : options.method,
            param : options.params
        },
        beforeSend: function(){
            // startWait();
        }, 
        dataSrc: function ( json ) {
                endWait();
                return json.data;
            },
        error:function (jqXHR,textStatus) {
            endWait();
            Swal.fire({
                title: 'Error!',
                text: "Error al cargar la tabla, la consulta es demasiado grande o hay problemas de conexion",
                icon: "error",
              });
            } 
    },
    "columns" : options.columns,
    "columnDefs": options.columnDefs,
    "rowCallback": options.rowCallback
});


 $('#'+options.idTable+' tbody').on( 'click', 'tr', function () {
    var rows = oTable.rows().count();
    if (rows>0) {
    var data = oTable.rows(this).data()[0]['row_id'];
    var data_row=oTable.row(this);
       if ( item_row.includes(data) ) {
            data_row.deselect();
            var i = 0;
              while (i < item_row.length) {
                if (item_row[i] === data) {
                  item_row.splice(i, 1);
                } else {
                  ++i;
                }
              }
               // console.log(JSON.stringify(item_row)); 
        }
        else 
        {   
            data_row.select();
            item_row.push(data);
             // console.log(JSON.stringify(item_row)); 
        }
      }
    })

if(options.contextMenu){
// Example to set items on context menu
// contextMenu : {
// "edit": {
//       name: "Edit", 
//       icon: "edit", 
//       callback: function(itemKey, opt, e) {
//           myfunction();
//       }
//   },
// }
    $(function() {
        $.contextMenu({
            selector: '#'+options.idTable+' tbody tr',
            items: options.contextMenu_items,
            events: {
             show : function(options){         
             },
             hide : function(options){                
             },
             activated : function(options){
              var rows = oTable.rows().count();
              if (rows>0) {
              var data = oTable.rows(options.$trigger[0]).data()[0]['row_id'];
              var data_row=oTable.row(options.$trigger[0]);
                if (! item_row.includes(data) ) {
                      data_row.select();
                      item_row.push(data);
                       // console.log(JSON.stringify(item_row)); 
                  }
                }        
             }
           }
        });
    });
}

$("#select_all").button().on( "click", dataTableSelectAll)

$("#deselect_all").button().on( "click", dataTableDeselectAll)

}

function exportExcel(){
  $('.buttons-excel').click();
}

function exportPDF(){
  $('.buttons-pdf').click();
}

function dataTableSelectAll(){
  startWait(); 
  setTimeout(function(){
  var p = new Promise(resolve => resolve("done!"));   
  p.then((resolve)=>{
      var rows = oTable.rows().count();
      for (var i = rows - 1; i >= 0; i--) {
          var data=oTable.rows(i).data()[0]['row_id'];
            if (! item_row.includes(data) ) {
                item_row.push(data);
            }             
          oTable.rows().select();
      }
    })
  p.then((resolve)=>{
  // console.log(JSON.stringify(item_row)); 
  endWait();
  })
  },100);
}

function dataTableDeselectAll(){
  startWait(); 
  setTimeout(function(){
  var p = new Promise(resolve => resolve("done!"));   
  p.then((resolve)=>{
        var rows = oTable.rows().count();
        for (var i = rows - 1; i >= 0; i--) {
            var data=oTable.rows(i).data()[0]['row_id'];
            item_row.splice(i, 1);             
            oTable.rows().deselect();
        }
         })
  p.then((resolve)=>{
  // console.log(JSON.stringify(item_row)); 
  endWait();
  })
  },100);
}

function notification(title, message, type){
  $.notify({
    title: '<strong>'+title+'</strong></br>',
    message: message
    }, {
    animate: {
        enter: 'animated bounceIn',
        exit: 'animated bounceOut',
    },
    offset: {
        x: 0,
        y: 100
    },
    type: type,
    z_index: 99999,
  });
};

function startWait(){
  $('body').loadingModal({text: 'Espere por favor...'}).loadingModal('animation', 'fadingCircle').loadingModal('show');
}
function endWait(){
  $('body').loadingModal('hide');
}

function destroyModal(){
  $('.insert_modal').dialog("close"); 
  $('.insert_modal').empty();
}

function closeModal(){
  $('.insert_modal').dialog("close"); 
}

function preloader(){
  $('.context-menu-list').trigger('contextmenu:hide');
  $('body').removeClass('loaded');
}

function endPreloader(){
  $('body').addClass('loaded');
}


function modalStyle(options)
{
  // Params
  // Header: Put title on header
  // Body: Text on body
  // Button_text: Set button text
  // myFunction: Execute function
  // html: Inner html code

  $(".ui-dialog-content").dialog("close");
  if (options.body) {
    textBody = options.body;
  }else{
    textBody = "";
  }
  var newElement = $("<div class='insert_modal' style='overflow: visible;' id=\"data_serialized\">"+textBody+"</div>");
  if (options.html) {
    newElement.empty();
    newElement.append(options.html);
  }
  var buttonsBuild;
  var widthBuild;
  if (options.myFunction) {
    buttonsBuild = {
          "Ok": {
                click: function () {
                    options.myFunction();   
                  
             },
             text: options.button_text,
             class: 'btn btn-primary'
         },
         "Cerrar": {
                click: function () {
                  if(options.cancelButton){
                    options.cancelButton();
                  }else{
                    destroyModal(); 
                  }
                 
             },
             text: 'Cerrar',
             class: 'btn btn-secondary'
         }
     };
  } else{
    buttonsBuild = {
         "Cerrar": {
                click: function () {
                  if(options.cancelButton){
                    options.cancelButton();
                  }else{
                    destroyModal(); 
                  }
                 
             },
             text: 'Cerrar',
             class: 'btn btn-secondary'
         }
     };
  }
  if (options.width) {
    widthBuild = options.width;
  } else{
    widthBuild = $(window).width() > 1100 ? 1100 : 'auto';
  }
  newElement.dialog({
      resizable:false,
      title: options.header,
      opacity: .2,
      width: widthBuild,
      minHeight: 350,
      // maxHeight: 750,
      height: 'auto',
      modal: true,
      fluid: true, //new option
      position: { my: "top+50", at: "top+50", of: window },
      buttons: buttonsBuild
  }); 

$(function() {
  $('input[type=number]').each(function() {
      var readonly = $(this).attr("readonly");
      if(readonly === undefined) { // this is readonly
          $(this).clearer();
      }
  });
  $('input[type=date]').each(function() {
      var readonly = $(this).attr("readonly");
      if(readonly === undefined) { // this is readonly
          $(this).clearer();
      }
  });
  $('input[type=text]').each(function() {
      var readonly = $(this).attr("readonly");
      if(readonly === undefined) { // this is readonly
          $(this).clearer();
      }
  });
  $('input').attr('autocomplete','off');
});


}

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}