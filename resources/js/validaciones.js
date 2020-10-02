var nav4 = window.Event ? true : false;
var ubicaciones = [];
var eliminados = [];
var table;

$(document).ready(function () {

    /* Desactiva la tecla enter */
    window.addEventListener("keypress", function (event) {
        if (event.keyCode == 13) {
            event.preventDefault();
        }
    }, false);

    /* Configuracion personalizada del plugin datepicker*/
    $.datepicker.regional['es'] = {
        closeText: 'Cerrar',
        prevText: '<Ant',
        nextText: 'Sig>',
        currentText: 'Hoy',
        monthNamesShort: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
        dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Juv', 'Vie', 'Sab'],
        dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        weekHeader: 'Sm',
        dateFormat: 'dd/mm/yy',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
    };
    $.datepicker.setDefaults($.datepicker.regional['es']);

});



function agregar_cargador(idspin) {

    var target = document.createElement("div");
    target.setAttribute("id", idspin);
    target.setAttribute("style", 'position: fixed; top: 0; width: 100%; height: 100%; padding-top: 16%; z-index: 999998;');
    document.body.appendChild(target);

    var opts = {
        lines: 20, // El número de líneas para dibujar
        length: 0.2, // La longitud de cada línea
        width: 20, // El grosor de la línea
        radius: 110, // El radio del círculo interior
        corners: 1, // Redondeado de las esquinas de cada linea (0..1)
        rotate: 0, // Compensación de la rotación
        direction: 1, // Dirección -  1: las agujas del reloj, -1: sentido contrario
        color: '#3face3', // #rgb o #rrggbb
        speed: 2, // Frecuencia de rotaación
        trail: 20, // Porcentaje Afterglow
        shadow: false, // Ya sea para hacer una sombra
        hwaccel: true, // Si se debe utilizar la aceleración de hardware
        className: 'spinner', // La clase CSS para asignar a la ruleta
        zIndex: 2e9, // Propiedad z-index (por defecto en 2000000000)
        top: '50%', // Posición superior con respecto al padre en píxeles
        left: '50%', // Posición izquierda en relación al padre en píxeles

    };
    new Spinner(opts).spin(target);
}

function removerElementoDom(idElement) {
    if (document.getElementById(idElement)) {
        var hijo = document.getElementById(idElement);
        var padre = hijo.parentNode;
        padre.removeChild(hijo);
    }
}

/**
 * Esta función permite crear el modal para ingresar una nueva zona
 */
function crear() {
    $.ajax({
        type: "POST",
        url: "index.php",
        data: { metodo: 'crear', controlador: 'zonas' },
        success: function (html) {
            bootbox.dialog({
                title: "<span style='color:white;'> Crear Zona </span>",
                message: html,
                buttons: {
                    noclose: {
                        label: "Crear",
                        className: 'btn-primary',
                        callback: function () {
                           validarCrear();
                           return false;
                        }
                    }
                }
            });
            eventosdt("#form_crea");
            validarCampos();
        },
        error: function (data) {
            console.log(data);
        }
    });
}


function validar_ubicacion() {
    var coddpto = $("#departamento").val();
    var codmun = $("#municipio").val();
    if (coddpto == "" || codmun == "") {
        swal({
            title: "",
            text: "Debe seleccionar un departamento y un municipio",
            type: "warning",
            showCancelButton: false,
            confirmButtonText: "Aceptar",
        },
            function () {
                swal("Ok", "", "success");
            });
        return;
    }
}
/**
 * Esta función permite crear las reglas y los mensajes de los campos requeridos
 */
function validarCampos() {
    $("#form_crea").validate({
        focusInvalid: false,
        rules: {
            codzona: { required: true },
            sucursal: { required: true },
            cobrador: { required: true },
            desczona: { required: true },
        },
        messages: {
            codzona: { required: "Ingrese un código" },
            desczona: { required: "Ingrese una descripción" },
            sucursal: { required: "Ingrese una sucursal" },
            cobrador: { required: "Ingrese un cobrador" },
        },
    });
}

/**
 * Esta función permite crear las reglas y los mensajes de los campos requeridos al momento de realizar una modificación
 */
function validarCamposMod() {
    $("#form_modi").validate({
        focusInvalid: false,
        rules: {
            cobrador: { required: true },
            desczona: { required: true },
        },
        messages: {
            desczona: { required: "Ingrese una descripción" },
            cobrador: { required: "Ingrese un cobrador" },
        },
    });
}

/**
 * Esta función permite obtener los municipios asociados a un departamento 
 */
function refrescarZonas() {
    var zona = $("#zonab").val();
    $.ajax({
        type: "POST",
        url: "index.php",
        data: { metodo: 'refrescar_zonas', controlador: 'zonas' },
        success: function (html) {
            $('#zonab').html(html);
            $("#zonab").val(zona);
        },
        error: function (data) {
            console.log(data);
        }
    });
}



/**
 * Esta función permite obtener los municipios asociados a un departamento 
 */
function getMunicipios() {
    var coddpto = $("#departamento").val();
    $.ajax({
        type: "POST",
        url: "index.php",
        data: { metodo: 'get_municipios', controlador: 'zonas', coddpto: coddpto },
        success: function (html) {
            $('#municipio').html(html);
        },
        error: function (data) {
            console.log(data);
        }
    });
}

/**
 * Esta función permite obtener las veredas asociadas a un departamento y municipio
 */
function getVeredas() {
    var coddpto = $("#departamento").val();
    var codmun = $("#municipio").val();
    $.ajax({
        type: "POST",
        url: "index.php",
        data: { metodo: 'get_veredas', controlador: 'zonas', coddpto: coddpto, codmun: codmun },
        success: function (html) {
            $('#vereda').html(html);
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
 * Esta función permite validar el formulario para crear una zona
 */
function validarCrear() {
    var aux = $("#form_crea").valid();
    if (aux === true) {
        verificarZonas();
        //return true;
    } else {
        return false;
    }
    return false;
}

/**
 * Esta función permite validar el formulario para modificar una zona
 */
function validarModificar() {
    var aux = $("#form_modi").valid();
    if (aux === true) {
        modificarZona();
    } else {
        return false;
    }
    return false;
}

/**
 * Esta función permite verificar que no existan zonas con el codigo 
 */
function verificarZonas() {
    var codzona = $("#codzona").val();
    var sucursal = $("#sucursal").val();
    var nomsucur = $("#sucursal option:selected").text();
    return $.ajax({
        type: "POST",
        dataType: "json",
        url: "index.php",
        data: { metodo: 'validar_zona', controlador: 'zonas', codzona: codzona, sucursal: sucursal },
        success: function (data) {
            var obtener = data.objetojson;
            var contador;
            contador = obtener['contador'];
            console.log(contador);

            if (contador == 0) {
                crearZona();
            } else {
                swal({
                    title: "",
                    text: "La Zona con codigo " + codzona + " y sucursal " + nomsucur + " ya se encuentra registrada",
                    type: "warning",
                    showCancelButton: false,
                    confirmButtonText: "Aceptar",
                },
                    function () {
                        swal("Ok", "", "success");
                    });
                return;
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}


/**
 * Esta función permite crear el modal para modificar una nueva zona
 */
function modificar(array) {
    eliminados = [];
    $.ajax({
        type: "POST",
        url: "index.php",
        data: { metodo: 'modificar', controlador: 'zonas', detalle:JSON.stringify(array) },
        success: function (html) {
            bootbox.dialog({
                title: "<span style='color:white;'> Modificar Zona </span>",
                message: html,
                buttons: {
                    cancel: {
                        label: "Eliminar",
                        className: 'btn-danger',
                        callback: function () {
                            eliminarTodos();
                            return false;
                        }
                    },
                    noclose: {
                        label: "Modificar",
                        className: 'btn-primary',
                        callback: function () {
                             validarModificar();
                             return false;
                        }
                    }
                }
            });
            eventosdt("#form_modi");
            validarCamposMod();
        },
        error: function (data) {
            console.log(data);
        }
    });
}

/**
 * Esta función permite enviar los datos del formulario para crear una zona 
 */
function crearZona() {
    var codzona = $("#codzona").val();
    var desczona = $("#desczona").val();
    var sucursal = $("#sucursal").val();
    var cobrador = $("#cobrador").val();

    var municipios = new Array();
    $(".inputTable")
        .find("tr")
        .each(function () {
            var val1 = $(this).find("td:eq(0)").html();
            var val2 = $(this).find("td:eq(2)").html();
            var val3 = $(this).find("td:eq(4)").html();
            if (typeof val1 != "undefined") {
                var registro = {
                    coddep: val1,
                    codmun: val2,
                    codver: val3,
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
    agregar_cargador('spin');
    //var dataString = $("#form_modi").serialize();
    var dataString = {
        metodo: "ingresar",
        controlador: "zonas",
        codzona: codzona,
        desczona: desczona,
        sucursal: sucursal,
        cobrador: cobrador,
        municipios: JSON.stringify(municipios),
    };
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
                        realizarBusqueda();
                        refrescarZonas();
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
                    realizarBusqueda();
                });
            }
        },
        error: function (data) {
            console.log(data);
        }
    });

}

/**
 * Esta función permite modificar las zonas 
 */
function modificarZona() {
    console.log("entra al modificar");
    //Se valida que se hayan ingresado los campos obligatorios
    var codzona = $("#codzona").val();
    var desczona = $("#desczona").val();
    var sucursal = $("#sucursal").val();
    var cobrador = $("#cobrador").val();

    var municipios = new Array();
    $(".inputTable")
        .find("tr")
        .each(function () {
            var val1 = $(this).find("td:eq(0)").html();
            var val2 = $(this).find("td:eq(2)").html();
            var val3 = $(this).find("td:eq(4)").html();
            if (typeof val1 != "undefined") {
                var registro = {
                    coddep: val1,
                    codmun: val2,
                    codver: val3,
                };
                municipios.push(registro);
            }
        });

    agregar_cargador('spin');
    //var dataString = $("#form_modi").serialize();
    var dataString = {
        metodo: "actualizar",
        controlador: "zonas",
        codzona: codzona,
        desczona: desczona,
        sucursal: sucursal,
        cobrador: cobrador,
        municipios: JSON.stringify(municipios),
        eliminados: JSON.stringify(eliminados),
    };
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
                    realizarBusqueda();
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
                    realizarBusqueda();
                });
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}

/**
 * Esta función permite eliminar una ubicación de una zona 
 */
function eliminar(array) {

    bootbox.confirm("Esta seguro de ejecutar esta accion?", function (result) {
        console.log('This was logged in the callback: ' + result);
        if (result) {
            agregar_cargador('spin');
            var dataString = {
                metodo: "eliminar",
                controlador: "zonas",
                detalle: JSON.stringify(array),
            };
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
                                window.location.href = "index.php?controlador=index";
                            });
                    } else {
                        var mensaje = obtener['mensaje'];
                        swal({
                            title: "",
                            text: mensaje,
                            type: "error",
                            showCancelButton: false,
                            confirmButtonText: "Aceptar",
                        },
                            function () {
                                window.location.href = "index.php";
                            });
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    });
}

function eliminarTodos() {

    var codzona = $("#codzona").val();
    var sucursal = $("#sucursal").val();

    bootbox.confirm("Esta seguro de ejecutar esta accion?", function (result) {
        if (result) {
            agregar_cargador('spin');

            var dataString = {
                metodo: "eliminarTodas",
                controlador: "zonas",
                codzona: codzona,
                sucursal: sucursal,
            };
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
                            refrescarZonas();
                            realizarBusqueda();
                        });
                    } else {
                        var mensaje = obtener['mensaje'];
                        swal({
                            title: "",
                            text: mensaje,
                            type: "error",
                            showCancelButton: false,
                            confirmButtonText: "Aceptar",
                        },
                        function () {
                            //window.location.href = "index.php?controlador=vacaciones";
                            realizarBusqueda();
                        });
                    }
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }
    });
}

/**
 * Esta función pemrite realizar la busqueda de la información relacionada a las vacaciones de una persona
 */
function realizarBusqueda() {
    var sucursalb = $("#sucursalb").val();
    var cobradorb = $("#cobradorb").val();
    var zonab = $("#zonab").val();

    var dataString = {
        metodo: "refrescarTabla",
        controlador: "zonas",
        sucursalb: sucursalb,
        cobradorb: cobradorb,
        zonab: zonab,
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
    //$("#formVacaciones").submit();
    //$("#formVacaciones").ajax({url: 'index.php', type: 'post'})

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
