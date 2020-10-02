$('[data-toggle="tooltip"]').tooltip();
// var actions = $("table tr td:last-child").html();

function eventosdt(form) {
  $(".add-new").click(function () {
	  validar_ubicacion();
    
    //se obtiene el codigo y el nombre del select de departamentos
    var dep = document.getElementById("departamento");
    var valuedep = dep.options[dep.selectedIndex].value;
    var textdep = dep.options[dep.selectedIndex].text;
    //se obtiene el codigo y el nombre del select de municipios
    var mun = document.getElementById("municipio");
    if(typeof mun.options[mun.selectedIndex] == "undefined"){
      return;
    }
    var valuemun = mun.options[mun.selectedIndex].value;
    var textmun = mun.options[mun.selectedIndex].text;

    //se obtiene el codigo y el nombre del select de veredas
    var ver = document.getElementById("vereda");
    
	  var valuever = ver.options[ver.selectedIndex].value;
	  if(typeof ver.options[ver.selectedIndex] == "undefined" || !valuever){
	  	valuever = "N/A";
		  var textver = "N/A";
	  }else{
		  var textver = ver.options[ver.selectedIndex].text;
	  }

    //$(this).attr("disabled", "disabled");
    var index = $(".inputTable tbody tr:last-child").index();
    var row =
      "<tr>" +
      "<td>" + valuedep + "</td>" +
      "<td>" + textdep + "</td>" +
      "<td>" + valuemun + "</td>" +
      "<td>" + textmun + "</td>" +
      "<td>" + valuever + "</td>" +
      "<td>" + textver + "</td>" +
      "<td style='text-align:center;'>" +
      '<a class="delete" title="Borrar" data-toggle="tooltip"><i class="far fa-trash-alt"></i></a>' +
      "</td>" +
      "</tr>";
    $(".inputTable").append(row);
    $(".inputTable tbody tr")
      .eq(index + 1)
      .find(".add, .edit")
      .toggle();
    $('[data-toggle="tooltip"]').tooltip();

    //se deben recargar los combos
    $("#departamento").val($("#target option:first").val());
    $("#municipio").html("");
    $("#vereda").html("");

  });

  // Add row on add button click
  $(document).on("click", ".add", function () {
    var empty = false;
    var input = $(this).parents("tr").find('input[type="text"]');
    input.each(function () {
      if (!$(this).val()) {
        $(this).addClass("error");
        empty = true;
      } else {
        $(this).removeClass("error");
      }
    });
    $(this).parents("tr").find(".error").first().focus();
    if (!empty) {
      input.each(function () {
        $(this).parent("td").html($(this).val());
      });
      $(this).parents("tr").find(".add, .edit").toggle();
      $(".add-new").removeAttr("disabled");
    }
  });

  // Delete row on delete button click
	$(document).on("click", ".delete", function () {
		$(this).parents("tr").remove();
		$(".add-new").removeAttr("disabled");
		if (seleccionado != null) {
			eliminados.push(seleccionado);
			//console.log(eliminados);
			seleccionado = null;
		}
	});
}


function eventosdtmunicipio(form){
  $('[data-toggle="tooltip"]').tooltip();
  
  
	//var actions = $("table tr td:last-child").html();

	$(".add-new").click(function () {
		$(this).attr("disabled", "disabled");
		var index = $(".inputTable tbody tr:last-child").index();
		var row = '<tr>' +
			'<td><input type="text" class="form-control" name="codigo[]" id="codigo" onkeypress="return acceptNum(event,this)" title="Solo Numeros" style="text-align:center; cursor:pointer;"  ></td>' +
      '<td><input type="text" class="form-control" name="nombre[]" id="nombre" style="text-align:center; cursor:pointer;" ></td>' +
      '<td><input type="text" class="form-control" name="listamadcont[]" id="listamadcont" onkeypress="return acceptNum(event,this)" title="Solo Numeros" style="text-align:center; cursor:pointer;"  ></td>' +
      '<td><input type="text" class="form-control" name="listaelecont[]" id="listaelecont" onkeypress="return acceptNum(event,this)" title="Solo Numeros" style="text-align:center; cursor:pointer;"  ></td>' +
      '<td><input type="text" class="form-control" name="listamadcont[]" id="listamad" onkeypress="return acceptNum(event,this)" title="Solo Numeros" style="text-align:center; cursor:pointer;"  ></td>' +
      '<td><input type="text" class="form-control" name="listaelecont[]" id="listaele" onkeypress="return acceptNum(event,this)" title="Solo Numeros" style="text-align:center; cursor:pointer;"  ></td>' +
      '<td><input type="text" class="form-control" name="flete[]" id="flete" onkeypress="return acceptNum(event,this)" title="Solo Numeros" style="text-align:center; cursor:pointer;"  ></td>' +
      '<td><input type="text" class="form-control" name="estado[]" id="estado" pattern="A|I" style="text-align:center; cursor:pointer;" ></td>' +
			'<td><input type="text" class="form-control" name="impuesto[]" id="impuesto" pattern="S|N" style="text-align:center; cursor:pointer;" ></td>' +
			'<td  style="text-align:center;">' +
			'<a class="add" title="Agregar" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-plus-circle"></i></a>' +
			'<a class="edit" title="Editar" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-pencil-alt"></i></a>' +
			'<a class="delete" title="Borrar" data-toggle="tooltip" data-placement="bottom"><i class="far fa-trash-alt"></i></a>' +
			'</td>' +
			'</tr>';
		$(".inputTable").append(row);
    $(".inputTable tbody tr").eq(index + 1).find(".add").show();
    $(".inputTable tbody tr").eq(index + 1).find(".edit").hide();
		$('[data-toggle="tooltip"]').tooltip();
  });
  
  $(document).on("click", ".add", function () {
		var empty = false;
		var input = $(this).parents("tr").find('input[type="text"]');
		input.each(function () {
			if (!$(this).val()) {
				$(this).addClass("error");
				empty = true;
			}
			else {
				$(this).removeClass("error");
			}

		});
		$(this).parents("tr").find(".error").first().focus();
		if (!empty) {
			input.each(function () {
        
        $(this).parent("td").html($(this).val()).addClass("text-center");
      });
			//$(this).parents("tr").find(".add, .edit").toggle();
			$(this).parents("tr").find(".add").hide();
			$(this).parents("tr").find(".edit").show();
			//console.log('boton con jaimito: ', $(this).parents("tr").find(".edit"));
			$(".add-new").removeAttr("disabled");

			if (seleccionado != null) {
				var registro = {
          codmuni: seleccionado,
          nombmuni: input.eq(1).val(),
					listamad_cont: input.eq(2).val(),
					listaele_cont: input.eq(3).val(),
					listamad: input.eq(4).val(),
          listaele: input.eq(5).val(),
          flete: input.eq(6).val(),
          estado: input.eq(7).val(),
          impuesto: input.eq(8).val(),
				};
				modificados.push(registro);
				console.log(modificados);
				seleccionado = null;
			}
		}

	});

  // Edit row on edit button click
	$(document).on("click", ".edit", function () {
		var existe = false;
		//console.log($(this).parents("tr").find("td:not(:last-child)"));
		$(this).parents("tr").find("td:not(:last-child)").each(function () {
			//console.log($(this).text());
			if ($(this).text()) {
				existe = true;
				$(this).html('<input type="text" class="form-control" value="' + $(this).text() + '">');
			}
		});
		if (existe) {
			$(this).parents("tr").find(".add, .edit").toggle();
			$(".add-new").attr("disabled", "disabled");
		}

	});

	// Delete row on delete button click
	$(document).on("click", ".delete", function () {
		$(this).parents("tr").remove();
		$(".add-new").removeAttr("disabled");
	});
}

/**
 * Esta función permite seleccionar un registro de la tabla
 * @param {String} consec 
 */
function seleccionar(consec) {
	seleccionado = consec;
	//console.log(seleccionado);
}

