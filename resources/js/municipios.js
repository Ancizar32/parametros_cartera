//#region  start
$(document).ready(function () {
    $("#municipios").on("click", inicializarMunicipios);
    $("#veredas").on("click", inicializarVeredas); 
});
//#endregion
var nav4 = window.Event ? true : false;
var table;
var seleccionado = null;
var modificados = [];

function inicializarMunicipios() {
    $("#index_zonas").empty()
    $.when(
        $.ajax({
            url: 'index.php',
            type: 'post',
            data: {
                controlador: 'municipios',
                metodo: 'index'
            },
            beforeSend: function () {
                preloader();
            },
            success: function (data) {
                $('#index_zonas').html(data);
            },
            error: function (jqXHR, textStatus) {
                Swal.fire({
                    title: 'Error!',
                    text: jqXHR.responseText,
                    icon: "error",
                });
            }
        })
    ).done(function () {
        inicializar_datatable("example1");
    }).done(function () {
        autocomplete_deptos();
    }).done(function () {
        $("#buscar").on("click", realizarBusqMunicipios);
        $("#gestionar").on("click", crearMunicipio);
    }).done(function () {
        endPreloader();
    });
}

/**
 * Esta función permite inicializar el select que busca con el autocomplete
 */
function autocomplete_deptos() {
    autocomplete({
        id: 'depto',
        controlador: 'municipios',
        metodo: 'fillOutAutocomplete'
    })
}

/**
 * Esta función permite inicializar una tabla 
 * @param {*} tabla 
 * @param {*} paging 
 * @param {*} order 
 */
function inicializar_datatable(tabla, paging = true, order = "desc") {

    table = $('#' + tabla).DataTable({
        responsive: true,
        searching: true,
        paging: paging,
        lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "Todos"]],
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar &nbsp;_MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Registros del _START_ al _END_ de _TOTAL_ registros totales",
            "sInfoEmpty": "Registros del 0 al 0 de 0 registros totales",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
        order: [
            [1, "asc"]
        ]
    });
}

/**
 * Esta función pemrite realizar la busqueda de la información relacionada a las vacaciones de una persona
 */
function realizarBusqMunicipios() {

    var depto = $("#depto").val();
    var dataString = {
        metodo: "refrescarTablaMunicipios",
        controlador: "municipios",
        depto: depto,
    };
    agregar_cargador('spin');
    $.ajax({
        type: "POST",
        url: "index.php",
        data: dataString,
        success: function (data) {
            removerElementoDom('spin');
            //console.log('date : ', data);
            table.destroy();
            $("#example1").html(data);
            inicializar_datatable("example1");

        },
        error: function (data) {
            console.log(data);
        }
    });
}

/**
 * Esta función permite tabular entre elementos
 * @param {*} e
 * @param {*} obj
 */
function tabular(e, obj) {
    tecla = document.all ? e.keyCode : e.which;
    if (tecla != 13) return;
    frm = obj.form;
    for (i = 0; i < frm.elements.length; i++)
        if (frm.elements[i] == obj) {
            if (i == frm.elements.length - 1) i = -1;
            break;
        }

    if (frm.elements[i + 1].disabled == true) tabular(e, frm.elements[i + 1]);
    else frm.elements[i + 1].focus();
    return false;
}

/**
 * Esta función permite validad el que el valor ingresado en un campo sea numerico
 * @param {*} evt
 * @param {*} obj
 */
function acceptNum(evt, obj) {
    console.log("entra");
    tabular(evt, obj);
    // NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, '.' = 46
    var key = nav4 ? evt.which : evt.keyCode;
    return key <= 13 || (key >= 48 && key <= 57);
}


/**
 * Esta función permite crear las reglas y los mensajes de los campos requeridos
 */
function validarCamposMunicipio() {
    $("#form_crea_municipio").validate({
        focusInvalid: false,
        rules: {
            coddep: { required: true },
            descdep: { required: true },
        },
        messages: {
            coddep: { required: "Ingrese un código" },
            descdep: { required: "Ingrese una descripción" },
        },
    });
}

/**
 * Esta función permite crear el modal para ingresar una nueva zona
 */
function crearMunicipio() {
    modificados = [];
    $.ajax({
        type: "POST",
        url: "index.php",
        data: { metodo: 'crear_municipio', controlador: 'municipios' },
        success: function (html) {
            bootbox.dialog({
                title: "<span style='color:white;'>Gestionar Departamento</span>",
                message: html,
                buttons: {
                    cancel: {
                        label: "Cancelar",
                        className: 'btn-danger',
                        callback: function () {
                            console.log('Custom cancel clicked');
                        }
                    },
                    noclose: {
                        label: "Guardar",
                        className: 'btn-primary',
                        callback: function () {
                            insertar();
                            return false;
                        }
                    }
                }
            });
            eventosdtmunicipio("#form_crea_municipio");
            validarCamposMunicipio();
        },
        error: function (data) {
            console.log(data);
        }
    });
}

/**
 * Esta función permite inicializar los municipios
 */
function inicializarMun(event, obj) {
    var codigoDepto = obj.value;
    if (codigoDepto == "") {
        console.log(codigoDepto)
        return;
    }
    codigoDepto = padLeft(obj.value);
    var dataString = {
        metodo: "obtInfoDepartamento",
        controlador: "municipios",
        depto: codigoDepto,
    };
    //se consulta si existe el departamento con el codigo y llena la tabla de municipios
    agregar_cargador('spin');
    $.ajax({
        type: "POST",
        dataType: "JSON",
        url: "index.php",
        data: dataString,
        success: function (data) {
            removerElementoDom('spin');
            var obtener = data.objetojson;
            var resultado = obtener['bandera'];
            if (resultado == 0) {
                var lista = obtener['lista'];
                var nombre = obtener['nombre'];
                var coddepto = obtener['coddpt'];
                //console.log('date : ', data);
                $(".inputTable").html(lista);
                $("#descdep").val(nombre);
                $("#coddepto").val(coddepto);
            } else {
                var lista = obtener['lista'];
                var coddepto = obtener['coddepto'];
                $(".inputTable").html(lista);
                $("#descdep").val("");
                $("#coddepto").val(coddepto);
            }
        },
        error: function (data) {
            console.log(data);
        }
    });

}

/**
 * Esta función permite agregar ceros a la izq al mes o al dia en caso de que sean menores de 10
 * @param {*} n 
 */
function padLeft(n) {
    return ("00" + n).slice(-2);
}


function insertar() {

    var aux = $("#form_crea_municipio").valid();
    if (aux === false) {
        return;
    }

    var municipios = new Array();
    $(".inputTable")
        .find("tr")
        .each(function () {
            var val1 = $(this).find("td:eq(0)").html();
            var val2 = $(this).find("td:eq(1)").html();
            var val3 = $(this).find("td:eq(2)").html();
            var val4 = $(this).find("td:eq(3)").html();
            var val5 = $(this).find("td:eq(4)").html();
            var val6 = $(this).find("td:eq(5)").html();
            var val7 = $(this).find("td:eq(6)").html();
            var val8 = $(this).find("td:eq(7)").html();
            var val9 = $(this).find("td:eq(8)").html();
            if (typeof val1 != "undefined") {
                var registro = {
                    codmuni: val1,
                    nombmuni: val2,
                    listamad_cont: val3,
                    listaele_cont: val4,
                    listamad: val5,
                    listaele: val6,
                    flete: val7,
                    estado: val8,
                    impuesto: val9,
                };
                municipios.push(registro);
            }
        });

    if (!municipios.length > 0) {
        swal({
            title: "",
            text: "Debe adicionar un municipio en la tabla.",
            type: "warning",
            showCancelButton: false,
            confirmButtonText: "Aceptar",
        },
            function () {
                swal("Ok", "", "success");
            });
        return;
    }

    var coddep = $("#coddep").val();
    var descdep = $("#descdep").val();
    var coddepto = $("#coddepto").val();
    var dataString = {
        metodo: "procesar_depto",
        controlador: "municipios",
        coddepto: coddepto,
        coddep: coddep,
        descdep: descdep,
        municipios: JSON.stringify(municipios),
        modificados: JSON.stringify(modificados),
    };

    agregar_cargador('spin');

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "index.php",
        data: dataString,
        success: function (data) {
            removerElementoDom('spin');
            var obtener = data.objetojson;
            var bandera;
            bandera = obtener['bandera'];
            console.log(data);

            if (bandera == "0") {
                swal({
                    title: "",
                    text: "Proceso exitoso.",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "Aceptar",
                },
                    function () {
                        //window.location.href = "index.php?controlador=vacaciones";
                        bootbox.hideAll();
                        realizarBusqMunicipios();
                        autocomplete_deptos();
                    });
            } else {
                swal({
                    title: "",
                    text: "Surgio un error, si el error persiste llamar al área de las TICs.",
                    type: "error",
                    showCancelButton: false,
                    confirmButtonText: "Aceptar",
                },
                    function () {
                        //window.location.href = "index.php?controlador=vacaciones";
                        realizarBusqMunicipios();
                    });
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

//#region veredas

function inicializarVeredas() {
    $("#index_zonas").empty()
    $.when(
        $.ajax({
            url: 'index.php',
            type: 'post',
            data: {
                controlador: 'veredas',
                metodo: 'index'
            },
            beforeSend: function () {
                preloader();
            },
            success: function (data) {
                $('#index_zonas').html(data);
            },
            error: function (jqXHR, textStatus) {
                Swal.fire({
                    title: 'Error!',
                    text: jqXHR.responseText,
                    icon: "error",
                });
            }
        })
    ).done(function () {
        inicializar_datatable("veredasTable");
    }).done(function () {
        autocomplete_verdeptos();
    }).done(function () {
        $("#buscar").on("click", realizarBusqVeredas);
        //$("#gestionar").on("click", crear_veredas);
    }).done(function () {
        endPreloader();
    });
}

/**
 * Esta función pemrite realizar la busqueda de la información relacionada a las vacaciones de una persona
 */
function realizarBusqVeredas() {

    var depto = $("#depto").val();
    var municipio = $("#municipio").val();
    var dataString = {
        metodo: "refrescarTablaVeredas",
        controlador: "veredas",
        depto: depto,
        municipio: municipio
    };
    agregar_cargador('spin');
    $.ajax({
        type: "POST",
        url: "index.php",
        data: dataString,
        success: function (data) {
            removerElementoDom('spin');
            //console.log('date : ', data);
            table.destroy();
            $("#veredasTable").html(data);
            inicializar_datatable("veredasTable");

        },
        error: function (data) {
            console.log(data);
        }
    });
}

/**
 * Esta función permite inicializar el select que busca con el autocomplete
 */
function autocomplete_verdeptos() {
    autocomplete({
        id: 'depto',
        controlador: 'veredas',
        metodo: 'fillOutAutocomplete',
    })
    
}


/**
 * Esta función permite inicializar el select que busca con el autocomplete
 */
function autocomplete_vermuncipios() {
    $('#municipio').val("");
    autocomplete({
        id: 'municipio',
        controlador: 'veredas',
        metodo: 'autocomplete_municipios',
        parametros: $('#depto').val()
    })

}


/**
 * Esta función permite inicializar el select que busca con el autocomplete
 */
function autocomplete_verdep() {
    autocomplete({
        id: 'depto_crea',
        controlador: 'veredas',
        metodo: 'fillOutAutocomplete',
    })
    
}


/**
 * Esta función permite inicializar el select que busca con el autocomplete
 */
function autocomplete_vermun() {
    $('#mun_crea').val("");
    autocomplete({
        id: 'mun_crea',
        controlador: 'veredas',
        metodo: 'autocomplete_municipios',
        parametros: $('#depto_crea').val()
    })

}
/**
 * Esta función permite crear el modal para ingresar una nueva zona
 */
function crearVereda() {
    modificados = [];
    $.ajax({
        type: "POST",
        url: "index.php",
        data: { metodo: 'crear_vereda', controlador: 'veredas' },
        success: function (html) {
            bootbox.dialog({
                title: "<span style='color:white;'>Crear Vereda</span>",
                message: html,
                buttons: {
                    cancel: {
                        label: "Cancelar",
                        className: 'btn-danger',
                        callback: function () {
                            console.log('Custom cancel clicked');
                        }
                    },
                    noclose: {
                        label: "Guardar",
                        className: 'btn-primary',
                        callback: function () {
                            insertarVereda();
                            return false;
                        }
                    }
                }
            });
            
            setTimeout(function(){
                autocomplete_verdep();
                validarCamposVereda('form_crea_veredas');
            },500);
            
        },
        error: function (data) {
            console.log(data);
        }
    });
}

/**
 * Esta función permite crear el modal para ingresar una nueva zona
 */
function modificarVereda(array) {
    modificados = [];
    $.ajax({
        type: "POST",
        url: "index.php",
        data: { metodo: 'mod_vereda', controlador: 'veredas', detalle:JSON.stringify(array)},
        success: function (html) {
            bootbox.dialog({
                title: "<span style='color:white;'>Modificar Vereda</span>",
                message: html,
                buttons: {
                    cancel: {
                        label: "Cancelar",
                        className: 'btn-danger',
                        callback: function () {
                            console.log('Custom cancel clicked');
                        }
                    },
                    noclose: {
                        label: "Guardar",
                        className: 'btn-primary',
                        callback: function () {
                            updateVereda();
                            return false;
                        }
                    }
                }
            });
            
            setTimeout(function(){
                validarCamposVereda('form_mod_veredas');
            },500);
            
        },
        error: function (data) {
            console.log(data);
        }
    });
}


/**
 * Esta función permite crear las reglas y los mensajes de los campos requeridos
 */
function validarCamposVereda(form) {
    $("#"+form).validate({
        focusInvalid: false,
        rules: {
            cod_vecor: { required: true },
            nom_vecor: { required: true },
            estado: { required: true },
            depto_crea: { required: true },
            mun_crea: { required: true },
        },
        messages: {
            cod_vecor: { required: "Ingrese un código" },
            nom_vecor: { required: "Ingrese una descripción" },
            estado: { required: "Ingrese por favor un estado" },
            depto_crea: { required: "Ingrese un departamento" },
            mun_crea: { required: "Ingrese un municipio" },
        },
    });
}

function insertarVereda(){

    var aux = $("#form_crea_veredas").valid();
    if (aux === false) {
        return;
    }
    var cod_vecor = $("#cod_vecor").val();
    var nom_vecor = $("#nom_vecor").val();
    var estado =  $("#estado").val();
    var muni = $("#mun_crea").val();
    var depto =  $("#depto_crea").val();
    var dataString = {
        metodo: "insert_veredas",
        controlador: "veredas",
        cod_vecor: cod_vecor,
        nom_vecor: nom_vecor,
        estado: estado,
        muni: muni,
        depto: depto,
    };

    
    agregar_cargador('spin');

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "index.php",
        data: dataString,
        success: function (data) {
            removerElementoDom('spin');
            var obtener = data.objetojson;
            var bandera;
            bandera = obtener['bandera'];
            console.log(data);

            if (bandera == "0") {
                swal({
                    title: "",
                    text: "Proceso exitoso.",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "Aceptar",
                },
                    function () {
                        //window.location.href = "index.php?controlador=vacaciones";
                        bootbox.hideAll();
                        realizarBusqVeredas();
                    });
            } else {
                var mensaje = obtener['mensaje'];
                if(mensaje == ""){
                    mesaje = "Surgio un error, si el error persiste llamar al área de las TICs.";
                }
                swal({
                    title: "",
                    text: mensaje,
                    type: "error",
                    showCancelButton: false,
                    confirmButtonText: "Aceptar",
                },
                    function () {
                        //window.location.href = "index.php?controlador=vacaciones";
                        //realizarBusqVeredas();
                    });
            }
        },
        error: function (data) {
            console.log(data);
        }
    })
}


function updateVereda(){

    var aux = $("#form_mod_veredas").valid();
    if (aux === false) {
        return;
    }
    var cod_vecor = $("#cod_vecor").val();
    var nom_vecor = $("#nom_vecor").val();
    var estado =  $("#estado").val();
    var cod_muni = $("#cod_muni").val();
    var cod_dpto =  $("#cod_dpto").val();
    var dataString = {
        metodo: "update_veredas",
        controlador: "veredas",
        cod_vecor: cod_vecor,
        nom_vecor: nom_vecor,
        estado: estado,
        cod_muni: cod_muni,
        cod_dpto: cod_dpto,
    };

    
    agregar_cargador('spin');

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "index.php",
        data: dataString,
        success: function (data) {
            removerElementoDom('spin');
            var obtener = data.objetojson;
            var bandera;
            bandera = obtener['bandera'];
            console.log(data);

            if (bandera == "0") {
                swal({
                    title: "",
                    text: "Proceso exitoso.",
                    type: "success",
                    showCancelButton: false,
                    confirmButtonText: "Aceptar",
                },
                    function () {
                        //window.location.href = "index.php?controlador=vacaciones";
                        bootbox.hideAll();
                        realizarBusqVeredas();
                    });
            } else {
                var mensaje = obtener['mensaje'];
                if(mensaje == ""){
                    mesaje = "Surgio un error, si el error persiste llamar al área de las TICs.";
                }
                swal({
                    title: "",
                    text: mensaje,
                    type: "error",
                    showCancelButton: false,
                    confirmButtonText: "Aceptar",
                },
                    function () {
                        //window.location.href = "index.php?controlador=vacaciones";
                        //realizarBusqVeredas();
                    });
            }
        },
        error: function (data) {
            console.log(data);
        }
    })
}

//#endregion