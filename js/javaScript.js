function Buscar(){
    var cedula = $('#cedula').val();
    var sucursal = $('#suc').val();
    var fecha = $('#Fecha').val();
    if(!fecha){
    alert("Por Favor Escoger Una Fecha");
    }else{ if(sucursal=="compania" && !cedula){
        alert("Por Favor Escoger Una Sucursal o Digitar una Cedula");
    }else{
         
            $.ajax({

            type: "POST",
            url: "ajax/cargarTabla.php",
            data: "&suc="+sucursal+'&cedula='+cedula+'&fecha='+fecha,
             beforeSend: function () {
                        $("#datos").html("<center><img src=\"images/cargando.gif\"/></center>");
                },
            success: function(msg)
            {
              $("#datos").html(msg);
            }

        })
    }
}
}

function desasentar(cedula,fechamov,claseli ,idMotivo){
              
           var memorando = $('#'+idMotivo).val();
             if(!memorando){
             alert("Por favor ingrese el motivo por el cual se desasienta el funcionario");
             }
           else{
           $("#actualizaDiv").show();
		    $("#desasentar").remove();
           $.ajax({
            type: "POST",
            url: "ajax/desasentar.php",
            data: "cedula="+cedula+"&fecmovi="+fechamov+"&claseli="+claseli+"&motivo="+memorando,
            success: function(msg)
            {
                  $("#actualizaDiv").hide();
                alert("el mensaje: "+msg);
                if ( msg == "exito")
                {
                      alert("Usuario desasentado correctamente");
                }
                else
                {
                   alert("El usuario no pudo ser desasentado");

                }

            }
        })
   }
}

function Cargar(){
    var cedula = $('#cedula').val();
            $.ajax({

            type: "POST",
            url: "ajax/cargar.php",
            data: '&cedula='+cedula,
             success: function(msg)
            {
                //$("#suc2").html(msg);
                //$("#suc").val("xxx");
                //document.getElementById("suc").value = 2;
                document.getElementById("suc").value = msg;
                //alert(document.getElementById("suc").value);
                
            }

        })

    }

 function Cargar2(){
   var cedula = $('#cedula').val();
                   $.ajax({

            type: "POST",
            url: "ajax/cargarFecha.php",
            data: '&cedula='+cedula,
             success: function(msg)
            {
//                alert(msg);
                 //$("#fechamov").html(msg);
                //$("#suc").val("xxx");
                //document.getElementById("suc").value = 2;
                document.getElementById("Fecha").value = msg;
                //alert(document.getElementById("suc").value);
            }

        })
 }