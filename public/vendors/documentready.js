$(document).ready(function () {

    $(".numeric").bind("keypress", function (e) {
        if (isNaN(e.key) && e.key != ".")
            e.preventDefault();

    });

    contador_item_mandas = 0;

    function actualizaCocina() {
        setTimeout(function () {
            window.location.replace($("#path").val() + "/Mandas/cocina");
            actualizaCocina();
        }, 10000);
    }


    function actualizaBarra() {
        setTimeout(function () {
            window.location.replace($("#path").val() + "/Mandas/barra");
            actualizaBarra();
        }, 10000);
    }


    if ($("#contenedor_barra").length > 0) {
        actualizaBarra();
    }

    if ($("#contenedor_cocina").length > 0) {
        actualizaCocina();
    }

    if ($("#table_manda tr").length > 0) {
        contador_item_mandas = $("#table_manda tr").length;
        actualizaNumItems();
    }

    ban_color = true;
    if ($("#list_items").length > 0)
        getItemsByCategoria(1)

    if ($("#altaUsuario").length > 0) {
        $('#altaUsuario').on('hidden.bs.modal', function () {
            window.location.replace($("#path").val() + "/Usuarios");
        });

    }


    if ($("#tableVenta").length > 0) {
        actualizaVenta();
    }

    if ($("#productos_asociados").length > 0) {
        loadAsociados();
    }
    if ($("#productos_asociadoscompras").length > 0) {
        loadAsociadosCompras();
    }


    if ($("#myAreaChart").length > 0)
        loadGraficaVentas();



});

//FUNCIONES 

function refreshnumeric() {
    $(".numeric").bind("keypress", function (e) {
        if (isNaN(e.key) && e.key != ".")
            e.preventDefault();

    });
}

function loadGraficaVentas() {
    $.ajax({
        url: $("#path").val() + "/Ajax/loadgraficaventas",
        type: 'POST',
        data: null,
        beforeSend: function () {
            //$("#list_items").html("<i style='font-size:5em' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
        }
    }).done(function (msg) {
        $("#tempGrafica").html(msg);
    });


}

function agregaItemToManda(item) {
    contador_item_mandas++;
    nombre = $(item).attr("nombre");
    unidad = $(item).attr("unidad");
    cantidad = $(item).attr("cantidad");
    precio = $(item).attr("precio");
    item_id = $(item).attr("id");

    if (ban_color) {
        color = "#BBECFF";
    }
    else
        color = "#BBECFF "
    ban_color = !ban_color;

    row = '<tr id="row' + contador_item_mandas + '" class="item_manda activo" item_id="' + item_id + '" style="display:none;height:60px; background-color:' + color + ';border:solid 1px #FFE47F;font-size:1em ">' +
        '<td style="text-align:left;padding-left:10px;">' +
        '' + nombre +
        '</td>' +
        '<td>' +
        '' + cantidad +
        '</td>' +
        '<td>' +
        '$ <span class="precio">' + precio + '</span>' +
        '</td>' +
        '<td>' +
        '   <button onclick="removeItemManda(' + contador_item_mandas + ')"class="btn btn-warning"><i class="fa fa-remove"></i></button>' +
        '</td>' +
        '</tr>';
    $("#table_manda").append(row);
    $("#row" + contador_item_mandas).show("slow")
    $('#content_manda').animate({ scrollTop: $("#table_manda").height() }, '4000');
    actualizaNumItems();
}

function removeItemManda(numItem) {
    if (confirm("¿Deseas Eliminar este item?")) {
        $("#row" + numItem).hide("slow");
        //setTimeout(function(){$("#row"+numItem).remove();},100); 
        $("#row" + numItem).remove();
        actualizaNumItems();
    }



}

function actualizaNumItems() {
    //productos = $("#table_manda tr").length;
    productos = $(".activo").length;
    $("#items_count").html(productos);
    //contador_item_mandas = productos;
    total = 0;
    $(".precio").each(function () {
        total += parseFloat($(this).html());
    });

    if ($(".precio_guardado").length > 0) {
        $(".precio_guardado").each(function () {
            total += parseFloat($(this).html());
        });
    }

    $("#total").html("$ " + new Intl.NumberFormat('en-IN').format(total.toFixed(2)));
}

function getItemsByCategoria(categoria) {
    $.ajax({
        url: $("#path").val() + "/Ajax/getitemsbycategoria",
        type: 'POST',
        data: { categoria: categoria },
        beforeSend: function () {
            $("#list_items").html("<i style='font-size:5em' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
        }
    }).done(function (msg) {
        $("#list_items").html(msg);
    });
}

function setImage() {
    var fd = new FormData();
    var files = $('#fileavatar')[0].files;

    // Check file selected or not
    if (files.length > 0) {
        fd.append('file', files[0]);
        $.ajax({
            url: $("#path").val() + '/Ajax/showimagetemp',
            type: 'post',
            data: fd,
            contentType: false,
            processData: false,
            beforeSend: function () {
                $("#avatar").html("<i style='font-size:5em' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
            }
        }).done(function (msg) {
            if (msg != 0) {
                $("#avatar").html(msg);
            } else {
                alert('ocurrio un error al cargar la imagen');
                $("#avatar").html("");
            }
        });
    } else {
        alert("Por favor Seleccione un archivo");
    }
}

function iniciarManda() {

    msgErrors = "";
    mesa = $("#mesa option:selected").val();
    items = "";
    precios_ = "";

    if (mesa == 0)
        msgErrors += "Favor de Elegir Mesa a comandar \n";

    if ($(".item_manda").length == 0)
        msgErrors += "No existen Productos en el Pedido";

    if (msgErrors != "") {
        alert(msgErrors);
    } else {

        $(".item_manda").each(function () {
            items += $(this).attr("item_id") + "|";
        });

        $(".precio").each(function () {
            precios_ += $(this).html() + "|";
        });

        data = { items: items, mesa: mesa, precios: precios_ }

        //$("#contenedor_mandas").css("opacity",".5");                
        $.ajax({
            url: $("#path").val() + '/Ajax/almacenamanda',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#estatus_mandas").html("<i style='font-size:2em' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
            }
        }).done(function (msg) {

            if (msg != null) {
                $("#estatus_mandas").html("");
                alert("Comanda Almacenada Satisfactoriamente!");
                window.location.replace($("#path").val() + "/Mandas/panel");
            } else {
                $("#estatus_mandas").html("");
                alert('Ocurrió un error al Almacenar la Manda');
            }
        });
    }
}

function actualizaManda() {

    msgErrors = "";
    mesa = $("#mesa option:selected").val();
    items = "";
    precios_ = "";
    mandas_id = $("#mandas_id").val();

    if (mesa == 0)
        msgErrors += "Favor de Elegir Mesa a comandar \n";

    /*if($(".item_manda").length==0)    
        msgErrors += "No existen Productos en el Pedido";*/

    if (msgErrors != "") {
        alert(msgErrors);
    } else {

        $(".item_manda").each(function () {
            items += $(this).attr("item_id") + "|";
        });

        $(".precio").each(function () {
            precios_ += $(this).html() + "|";
        });

        data = { items: items, mesa: mesa, precios: precios_, mandas_id: mandas_id }

        //$("#contenedor_mandas").css("opacity",".5");                
        $.ajax({
            url: $("#path").val() + '/Ajax/actualizamanda',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#estatus_mandas").html("<i style='font-size:2em' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
            }
        }).done(function (msg) {

            if (msg != null) {
                $("#estatus_mandas").html("");
                alert("Comanda Actulizada Satisfactoriamente!");
                window.location.replace($("#path").val() + "/Mandas/panel");
            } else {
                $("#estatus_mandas").html("");
                alert('Ocurrió un error al Actualizar la Manda');
            }
        });
    }


}


function cancelaItemManda(mandaItemsId) {

    data = { mandaItemsId: mandaItemsId }
    if (confirm("¿Esta Seguro de querer eliminar dicho producto?")) {
        //$("#contenedor_mandas").css("opacity",".5");                
        $.ajax({
            url: $("#path").val() + '/Ajax/cancelaitemmanda',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#icono" + mandaItemsId).removeClass("fa-remove");
                $("#icono" + mandaItemsId).addClass("fa-spinner fa-spin fa-3x fa-fw");
            }
        }).done(function (msg) {

            if (msg) {
                $("#" + mandaItemsId).css("background-color", "#FFE0AD")
                $("#" + mandaItemsId).removeClass("activo")
                $("#button" + mandaItemsId).remove();
                $("#precio" + mandaItemsId).removeClass("precio_guardado");
                actualizaNumItems();
                alert("Cancelación Exitosa!")
            } else {
                $("#icono" + mandaItemsId).removeClass("fa-spinner fa-spin fa-3x fa-fw");
                $("#icono" + mandaItemsId).addClass("fa-remove");
                alert("No es posible realizar la Cancelación ya que el artículo fue entregado o ya está en proceso de preparación.")
            }

            /*if(msg != null){
                $("#estatus_mandas").html("");
                alert("Comanda Actulizada Satisfactoriamente!");  
                window.location.replace($("#path").val()+"/Mandas/panel");                           
            }else{                
                $("#estatus_mandas").html("");
                alert('Ocurrió un error al Actualizar la Manda');
            }*/
        });
    }
}

function cerrarManda() {
    mandas_id = $("#mandas_id").val();
    data = { mandas_id: mandas_id }

    $.ajax({
        url: $("#path").val() + '/Ajax/cerrarmanda',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#estatus_mandas").html("<i style='font-size:2em' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
        }
    }).done(function (msg) {
        $("#estatus_mandas").html("");
        if (msg) {
            alert("Comanda: " + mandas_id + " Cerrada Satisfactoriamente!")
            $("#status_manda").html(" cerrada");
            window.location.replace($("#path").val() + "/Mandas/panel");

        } else {
            alert("La Comanda: " + mandas_id + " No puede ser cerrada, ya que tiene productos sin despachar!")
        }
    });
}

function updateStatusEmpleado(elemento) {
    //Hacemos la Actualización del estatus correspondiente del empleado
    activo = $(elemento).prop("checked");
    empleados_id = $(elemento).attr("empleados_id");
    if (activo)
        status = 1;
    else
        status = 0;

    data = { empleados_id: empleados_id, status: status }
    $.ajax({
        url: $("#path").val() + '/Ajax/actualizaestatusempleado',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#empleadostatus" + empleados_id).html("<i style='font-size:1.5em;color:orange' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
        }
    }).done(function (msg) {
        if (msg == 1) {
            estatus = "Activo"
            $("#rowempleado" + empleados_id).css("background-color", "white");
            $(elemento).prop("checked", true);
        } else {
            estatus = "Baja";
            $("#rowempleado" + empleados_id).css("background-color", "#FFE0B7");
            $(elemento).prop("checked", false);
        }
        $("#empleadostatus" + empleados_id).html(estatus);

    });
}

function updateStatusCliente(elemento) {
    //Hacemos la Actualización del estatus correspondiente del empleado
    activo = $(elemento).prop("checked");
    clientes_id = $(elemento).attr("clientes_id");
    if (activo)
        status = 1;
    else
        status = 0;

    data = { clientes_id: clientes_id, status: status }
    $.ajax({
        url: $("#path").val() + '/Ajax/actualizaestatuscliente',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#clientestatus" + clientes_id).html("<i style='font-size:1.5em;color:orange' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
        }
    }).done(function (msg) {
        if (msg == 1) {
            estatus = "Activo"
            $("#rowcliente" + clientes_id).css("background-color", "white");
            $(elemento).prop("checked", true);
        } else {
            estatus = "Baja";
            $("#rowcliente" + clientes_id).css("background-color", "#FFE0B7");
            $(elemento).prop("checked", false);
        }
        $("#clientestatus" + clientes_id).html(estatus);

    });
}
function updateStatusProveedor(elemento) {
    //Hacemos la Actualización del estatus correspondiente del empleado
    activo = $(elemento).prop("checked");
    proveedores_id = $(elemento).attr("proveedores_id");
    if (activo)
        status = 1;
    else
        status = 0;

    data = { proveedores_id: proveedores_id, status: status }
    $.ajax({
        url: $("#path").val() + '/Ajax/actualizaestatusproveedor',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#proveedorstatus" + proveedores_id).html("<i style='font-size:1.5em;color:orange' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
        }
    }).done(function (msg) {
        if (msg == 1) {
            estatus = "Activo"
            $("#rowproveedor" + proveedores_id).css("background-color", "white");
            $(elemento).prop("checked", true);
        } else {
            estatus = "Baja";
            $("#rowproveedor" + proveedores_id).css("background-color", "#FFE0B7");
            $(elemento).prop("checked", false);
        }
        $("#proveedorstatus" + proveedores_id).html(estatus);

    });
}


function showchangePassword(cuenta, usuarios_id) {
    form = '<center>' +
        '<i style="font-size:30pt" class="fa fa-user"></i>' +
        '<input type="hidden" id="usuarios_id" value="' + usuarios_id + '"/>' +
        '<hr/>' +
        '<table>' +
        '<tr>' +
        '<td style="padding:5px">Contraseña Actual:*</td><td style="padding:5px"><input type="password" id="password_actual" class="form-control"/><div id="errorActual" style="color:red;display:none">*Rellene este campo</div></td>' +
        '</tr>' +
        '<tr>' +
        '<td style="padding:5px">Nueva Contraseña:*</td><td style="padding:5px"><input type="password" id="password_nueva" class="form-control"/><div id="errorNueva" style="color:red;display:none">*Rellene este campo</div></td>' +
        '</tr>' +
        '<tr>' +
        '<td style="padding:5px">Confirma Contraseña:*</td><td style="padding:5px"><input type="password" id="password_confirma" class="form-control"/><div id="errorConfirma" style="color:red;display:none">*El Password no coincide</div></td>' +
        '</tr>' +
        '</table>' +
        '</center>';

    titulo = "Cambiar Contraseña de la Cuenta: " + cuenta;
    //form="<center>"+
    //"<i style='color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'>"+
    //"</i>"+    
    //"</center>";
    $("#modal-bodychangePassword").html(form);
    $("#modal-titlechangePassword").html(titulo);
    $("#buttonChange").show("slow");
}

function changePassword() {
    usuarios_id = $("#usuarios_id").val();
    password_actual = $("#password_actual").val();
    password_nueva = $("#password_nueva").val();
    password_confirma = $("#password_confirma").val();
    validacion = true;

    if (password_actual.trim().length == 0) {
        $("#errorActual").show("slow");
        validacion = false;
    } else {
        $("#errorActual").hide("slow");
    }

    if (password_nueva.trim().length == 0) {
        $("#errorNueva").show("slow");
        validacion = false;
    } else {
        $("#errorNueva").hide("slow");
    }

    if (!(password_nueva == password_confirma)) {
        $("#errorConfirma").show("slow");
        validacion = false;
    } else {
        $("#errorConfirma").hide("slow");
    }

    if (validacion) {
        $("#buttonChange").hide("fast");
        data = { usuarios_id: usuarios_id, password_actual: password_actual, password_nueva: password_nueva, password_confirma: password_confirma }
        $.ajax({
            url: $("#path").val() + '/Ajax/actualizapassword',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#modal-bodychangePassword").html("<center><i style='font-size:1.5em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
            }
        }).done(function (msg) {

            $("#modal-bodychangePassword").html(msg);

        });

    }
}

function limpiaFormulariousuario() {
    $("#cuenta").val("");
    $("#password").val("");
    $("#confirma_password").val("");
    $("#cuentaError").hide("fast");
    $("#passwordError").hide("fast");
    $("#confirmaError").hide("fast");
}

function almacenaUsuario() {
    //Validamos
    empleados_id = $("#empleados_id option:selected").val();
    roles_id = $("#roles_id option:selected").val();
    cuenta = $("#cuenta").val();
    password = $("#password").val();
    confirma_password = $("#confirma_password").val();
    validacion = true;

    if (cuenta.trim().length == 0) {
        validacion = false;
        $("#cuentaError").show("slow");
    } else
        $("#cuentaError").hide("slow");

    if (password.trim().length == 0) {
        validacion = false;
        $("#passwordError").show("slow");
    } else
        $("#passwordError").hide("slow");

    if (!(password == confirma_password)) {
        validacion = false;
        $("#confirmaError").show("slow");
    } else
        $("#confirmaError").hide("slow");

    if (validacion) {
        $("#buttonAddUsuario").hide("slow");

        data = { empleados_id: empleados_id, roles_id: roles_id, cuenta: cuenta, password: password }
        $.ajax({
            url: $("#path").val() + '/Ajax/agregausuario',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#modal-bodyaltaUsuario").html("<center><i style='font-size:1.5em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
            }
        }).done(function (msg) {

            $("#modal-bodyaltaUsuario").html(msg);

        });
    }
}

function updateStatusUsuario(elemento) {
    //Hacemos la Actualización del estatus correspondiente del empleado
    activo = $(elemento).prop("checked");
    usuarios_id = $(elemento).attr("usuarios_id");
    if (activo)
        status = 1;
    else
        status = 0;

    //alert(usuarios_id+" Hola que tal");
    //return;

    data = { usuarios_id: usuarios_id, status: status }
    $.ajax({
        url: $("#path").val() + '/Ajax/actualizaestatususuario',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#usuarioStatus" + usuarios_id).html("<i style='font-size:1.5em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
        }
    }).done(function (msg) {
        if (msg == 1) {
            estatus = "Activo"
            $("#rowuser" + usuarios_id).css("background-color", "white");
            $(elemento).prop("checked", true);
        } else {
            estatus = "Baja";
            $("#rowuser" + usuarios_id).css("background-color", "#FFE0B7");
            $(elemento).prop("checked", false);
        }
        $("#usuarioStatus" + usuarios_id).html(estatus);

    });
}

function actualizaVenta() {
    let number_format = Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD"
    });
    gran_total = 0;
    subtotal = 0;
    iva = 0;
    descuentos = 0;
    $(".subtotales").each(function () {
        subtotal += parseFloat($(this).html())
    });
    $(".ivamontos").each(function () {
        iva += parseFloat($(this).html())
    });

    $(".descuentos").each(function () {
        descuentos += parseFloat($(this).val())
    });

    subtotal = subtotal + descuentos;
    gran_total = (subtotal - descuentos) + iva;

    if (isNaN(subtotal))
        subtotal = 0;

    $("#subtotal").html(number_format.format(subtotal));
    $("#descuento").html(number_format.format(descuentos));
    $("#iva").html(number_format.format(iva));
    $("#gran_total").html(number_format.format(gran_total));
}

function addProducto() {
    busquedaproducto = $("#input_producto").val();
    cantidad = $("#cant").val();
    if (!busquedaproducto == "" && cantidad != "" && cantidad > 0) {
        data = { busquedaproducto: busquedaproducto, cantidad: cantidad }
        $.ajax({
            url: $("#path").val() + '/Ajax/getrowproducto',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#loading_producto").show("fast");
            }
        }).done(function (msg) {
            if (msg == 'null') {
                $("#infoNolocalizado").modal("show");
                $("#input_producto").val("");
            } else {
                $("#tempo-venta").html(msg);
                $("#input_producto").val("");
                $("#cant").val("1");
                actualizaVenta();
                $('#productos_content').animate({ scrollTop: $("#tableVenta").height() }, '4000');
            }
            $("#loading_producto").hide("fast");
            $("#lista_productos_v").html("");
            $("#lista_productos_v").hide("fast");



        });
    }
}
function addProductoc() {
    busquedaproducto = $("#input_producto").val();
    cantidad = $("#cant").val();
    if (!busquedaproducto == "" && cantidad != "" && cantidad > 0) {
        data = { busquedaproducto: busquedaproducto, cantidad: cantidad }
        $.ajax({
            url: $("#path").val() + '/Ajax/getrowproductoc',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#loading_producto").show("fast");
            }
        }).done(function (msg) {
            if (msg == 'null') {
                $("#infoNolocalizado").modal("show");
                $("#input_producto").val("");
            } else {
                $("#tempo-venta").html(msg);
                $("#input_producto").val("");
                $("#cant").val("1");
                actualizaVenta();
                $('#productos_content').animate({ scrollTop: $("#tableVenta").height() }, '4000');
            }
            $("#loading_producto").hide("fast");
            $("#lista_productos_v").html("");
            $("#lista_productos_v").hide("fast");



        });
    }
}

function modalVenta() {
    if ($(".items").length > 0) {
        total = $("#gran_total").html();
        total = total.replace(",", "").replace("$", "");

        total = parseFloat(total);
        $("#inputTotal").val(total.toFixed(2));
        $("#inputPago").val(total.toFixed(2));
        $("#inputCambio").val("0.00");
        $("#inputPago").prop("disabled", false);
        $("#tipoPago").prop("disabled", false);
        $("#pie_venta").html(
            '<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>' +
            '<button type="submit" class="btn btn-primary" onclick="almacenaVenta();">Registrar Venta</button>'
        );
        $('#infoVenta').modal({
            backdrop: 'static',
            keyboard: false
        });
        showCuponArea(false);
        setTimeout(function () { $("#inputPago").focus() }, 500);
    }
}

function almacenaVenta() {
    turnos_id = $("#turnos_id").val();
    if($("#tipoPago").val()=="credito")
        estado = "credito";
    else
        estado = "cobrada";
    descuento = $("#descuento").html().replace(",", "").replace("$", "");
    iva = $("#iva").html().replace(",", "").replace("$", "");
    subtotal = $("#subtotal").html().replace(",", "").replace("$", "");
    total = $("#gran_total").html().replace(",", "").replace("$", "");
    forma_pago = $("#tipoPago option:selected").val();
    pago = $("#inputPago").val().replace(",", "").replace("$", "");
    cambio = $("#inputCambio").val().replace(",", "").replace("$", "");
    cupon = $("#checkCupon").prop("checked");
    ban_cupon = $("#ban_cupon").length;
    clientes_id = $("#clientes_id").val();





    items = "";
    precios = "";
    cantidades = "";
    ivas = "";
    subtotales = "";
    ivamontos = "";
    descuentos = "";
    totales = "";

    $(".items").each(function () {
        items += $(this).html() + "|";
    });
    $(".precios").each(function () {
        precios += $(this).html().replace(",", "").replace("$", "") + "|";
    });
    $(".cantidades").each(function () {
        cantidades += $(this).val() + "|";
    });
    $(".ivas").each(function () {
        ivas += parseFloat($(this).html()) / 100 + "|";
    });
    $(".subtotales").each(function () {
        subtotales += $(this).html() + "|";
    });
    $(".ivamontos").each(function () {
        ivamontos += $(this).html() + "|";
    });
    $(".descuentos").each(function () {
        descuentos += $(this).val() + "|";
    });
    $(".totales").each(function () {
        totales += $(this).html() + "|";
    });



    data = {
        turnos_id: turnos_id,
        estado: estado,
        descuento: descuento,
        iva: iva,
        subtotal: subtotal,
        total: total,
        forma_pago: forma_pago,
        pago: pago,
        cambio: cambio,
        items: items,
        precios: precios,
        cantidades: cantidades,
        ivas: ivas,
        subtotales: subtotales,
        ivamontos: ivamontos,
        descuentos: descuentos,
        totales: totales,
        clientes_id: clientes_id
    };

    if (cupon && ban_cupon > 0) {

        monto_cupon = $("#ban_cupon").attr("monto");
        if (monto_cupon > 0) {
            data["codigo_cupon"] = $("#inputCupon").val();
            data["monto_cupon"] = monto_cupon;
        }
    }
    /*alert(JSON.stringify(data))
    return;*/

    $.ajax({
        url: $("#path").val() + '/Ventas/almacenaventa',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#pie_venta").html("<i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
            $("#inputPago").prop("disabled", true);
            $("#tipoPago").prop("disabled", true);
        }
    }).done(function (msg) {
        if (msg != null) {

            $("#pie_venta").html(
                '<div class="alert alert-success"><i class="fa fa-check"></i> Venta Exitosa!</div>'
            );
            limpiarVenta(true);
            $("#precio_de_venta").prop("disabled", false);
            $("#input_producto").focus();
            setTimeout(function () {
                $("#infoVenta").modal("hide");
                window.open($("#path").val() + "/Ventas/imprimirventa?vnt=" + msg, "_blank");
            }, 1000);

        } else {
            $("#pie_venta").html(
                '<div class="alert alert-warning"><i class="fa fa-check"></i> Error al Almacenar Venta, Intente más tarde!</div>'
            );
            setTimeout(function () {
                $("#infoVenta").modal("hide");
            }, 3000);
        }
    });







}


function setCambio() {
    total = parseFloat($("#gran_total").html().replace(",", "").replace("$", ""));
    pago = parseFloat($("#inputPago").val());
    cambio = pago - total;

    $("#inputCambio").val(!isNaN(cambio) ? cambio.toFixed(2) : "0.00");
}

function limpiarVenta(desc = null) {
    if (desc == null) {
        if (confirm("¿Desea Limpiar la venta?")) {
            $("#body_productos").html("");
            $("#precio_de_venta").prop("disabled", false);
            actualizaVenta();
        }
    } else {
        $("#body_productos").html("");
        actualizaVenta();
    }

}

function removeProducto(productos_id) {
    $("#rowproducto" + productos_id).remove();
    if ($("#tableVenta").find("tr").length == 1)
        $("#precio_de_venta").prop("disabled", false);
    actualizaVenta();
}


function buscaProducto(bus = null) {
    $("#buttonAgregar").prop("disabled", true);
    busqueda = bus == null ? $("#inputNombreProducto").val().trim() : bus;
    if (!(busqueda == "") && busqueda.length > 2) {
        data = { busqueda: busqueda };
        $.ajax({
            url: $("#path").val() + '/Ajax/buscaproductos',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#lista_productos").html("<i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
            }
        }).done(function (msg) {
            $("#lista_productos").html(msg);
            $("#lista_productos").show("slow");
        });
    } else {
        $("#lista_productos").html("");
        $("#lista_productos").hide("slow");
    }
}

function agregarProductoAsociado(producto) {
    producto_ = producto.split("-");

    if (!($("#rowasociado" + producto_[0]).length > 0)) {
        row = '<tr id="rowasociado' + producto[0] + '">' +
            '<td class="items_id" style="text-align:center">' + producto_[0] +
            '</td>' +
            '<td>' + producto_[2] +
            '</td>' +
            '<td style="text-align:center">' + producto_[3] +
            '</td>' +
            '<td style="text-align:center"><button class="btn btn-warning" onclick="removeAsociado(' + producto[0] + ')"><i class="fa fa-times"></i></button>' +
            '</td>' +
            '</tr>';
        $("#productos_asociados").append(row);
    }
    $("#inputNombreProducto").val("");
    $("#buttonAgregar").prop("disabled", true);
    $("#inputNombreProducto").focus();
}

function removeAsociado(id) {
    $("#rowasociado" + id).remove();
}

function almacenaAsociados() {
    proveedores_id = $("#selectProveedor option:selected").val();
    items = "";
    $(".items_id").each(function () {
        items += $(this).html() + "|";
    });

    data = { proveedores_id: proveedores_id, items: items };
    $.ajax({
        url: $("#path").val() + '/Ajax/almacenaasociados',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#infoAsociar").modal("show");
            $("#modal-bodyAsociar").html("<i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
        }
    }).done(function (msg) {
        $("#modal-bodyAsociar").html(msg);
    });
}


function loadAsociados() {
    proveedores_id = $("#selectProveedor option:selected").val();
    if (proveedores_id != "") {
        data = { proveedores_id: proveedores_id };
        $.ajax({
            url: $("#path").val() + '/Ajax/loadasociados',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#productos_asociados").html("<i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
            }
        }).done(function (msg) {
            $("#productos_asociados").html(msg);
        });
    }


}
function loadAsociadosCompras() {
    proveedores_id = $("#selectProveedorCompra option:selected").val();
    if (proveedores_id != "") {
        data = { proveedores_id: proveedores_id };
        $.ajax({
            url: $("#path").val() + '/Ajax/loadasociadoscompras',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#productos_asociadoscompras").html("<i style='font-size:2em;color:blue' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
            }
        }).done(function (msg) {
            $("#productos_asociadoscompras").html(msg);
        });
    }


}


function setTotalrow(id) {
    precio = parseFloat($("#precio" + id).html().replace(",", "").replace("$", ""));
    cantidad = parseFloat($("#cantidad" + id).val().replace(",", "").replace("$", ""));
    descuento = parseFloat($("#descuento" + id).val().replace(",", "").replace("$", ""));
    iva = parseFloat($("#iva" + id).html().replace("%", "").replace("$", "") / 100);
    subtotal = ((precio * cantidad) - descuento);
    ivamonto = subtotal * iva;
    total = subtotal + ivamonto;
    $("#total" + id).html(total.toFixed(2));
    $("#subtotal" + id).html(subtotal.toFixed(2));
    $("#ivamonto" + id).html(ivamonto.toFixed(2));
    actualizaVenta();
}

function addRetiro() {
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });
    saldo = $("#saldo_final").html().replace(",", "").replace("$", "");
    saldo = saldo.replace("$", "");
    monto = parseFloat($("#inputMonto").val());


    if (saldo < monto) {
        $("#modal-bodyRetiro").html("<div class='alert alert-warning'><i class='fa fa-info'></i> EL monto de Retiro es Superior al Saldo Final en Caja!</div>")
        $("#infoRetiro").modal("show");
    }

    else {
        if (monto > 0 && !(isNaN(monto))) {
            saldo = saldo - monto;
            turnos_id = $("#turnos_id").html();
            motivo = $("#inputMotivo").val();
            data = { turnos_id: turnos_id, monto_retiro: monto, saldo_nuevo: saldo, motivo: motivo, tipo:'M' };
            $.ajax({
                url: $("#path").val() + '/Ajax/efectuaretiro',
                type: 'post',
                data: data,
                beforeSend: function () {
                    $("#button_retiro").html("<i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
                    $("#button_retiro").prop("disabled", true);
                }
            }).done(function (msg) {

                tiempo = Date.now();
                hoy = new Date(tiempo);
                if (!(msg == 'null')) {
                    $("#saldo_final").html(formatter.format(saldo));
                    row = '<tr id="retiro' + msg + '">' +
                        '<td>' + msg + '</td>' +
                        '<td>' + hoy.toLocaleDateString() + '</td>' +
                        '<td>$ ' + monto.toFixed(2) + '</td>' +
                        '<td>' + motivo + '</td>' +
                        '<td><a target="_blank" href="'+$("#path").val()+'/Caja/imprimeretiro?retiro='+msg+'"><button class="btn btn-success"><i class="fas fa-print"></i></button></a>'+
                        '<button class="btn btn-warning" id="buttonretiro' + msg + '"onclick="cancelaRetiro(' + msg + ',' + monto + ')"><i class="fa fa-times"></i></button></td>' +                        
                        '</tr>';
                    $("#rowsretiro").append(row)
                    $("#inputMonto").val("");
                    $("#inputMotivo").val("");

                    $("#button_retiro").html("Proceder");
                    $("#button_retiro").prop("disabled", false);
                }
                setTimeout(function(){window.open($("#path").val() + "/Caja/imprimeretiro?retiro="+msg);},500);                
            });            
        }

    }
}
function addEntrada() {
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });
    saldo = $("#saldo_final").html().replace(",", "").replace("$", "");
    saldo = parseFloat(saldo.replace("$", ""));
    monto = parseFloat($("#inputMonto").val());


    if (false){//saldo < monto) {
        $("#modal-bodyRetiro").html("<div class='alert alert-warning'><i class='fa fa-info'></i> EL monto de Retiro es Superior al Saldo Final en Caja!</div>")
        $("#infoRetiro").modal("show");
    }
    else {
        if (monto > 0 && !(isNaN(monto))) {
            saldo = saldo + monto;            
            turnos_id = $("#turnos_id").html();
            motivo = $("#inputMotivo").val();
            data = { turnos_id: turnos_id, monto_entrada: monto, saldo_nuevo: saldo, motivo: motivo };
            $.ajax({
                url: $("#path").val() + '/Ajax/efectuaentrada',
                type: 'post',
                data: data,
                beforeSend: function () {
                    $("#button_entrada").html("<i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
                    $("#button_entrada").prop("disabled", true);
                }
            }).done(function (msg) {

                tiempo = Date.now();
                hoy = new Date(tiempo);
                if (!(msg == 'null')) {
                    $("#saldo_final").html(formatter.format(saldo));
                    row = '<tr id="entrada' + msg + '">' +
                        '<td>' + msg + '</td>' +
                        '<td>' + hoy.toLocaleDateString() + '</td>' +
                        '<td>$ ' + monto.toFixed(2) + '</td>' +
                        '<td>' + motivo + '</td>' +
                        '<td><a target="_blank" href="'+$("#path").val()+'/Caja/imprimeentrada?entrada='+msg+'"><button class="btn btn-success"><i class="fas fa-print"></i></button></a>'+
                        '<button class="btn btn-warning" id="buttonentrada' + msg + '"onclick="cancelaEntrada(' + msg + ',' + monto + ')"><i class="fa fa-times"></i></button></td>' +
                        '</tr>';
                    $("#rowsentradas").append(row)
                    $("#inputMonto").val("");
                    $("#inputMotivo").val("");

                    $("#button_entrada").html("Ingresar a Efectivo");
                    $("#button_entrada").prop("disabled", false);
                }
                setTimeout(function(){window.open($("#path").val() + "/Caja/imprimeentrada?entrada="+msg);},500);                
            });
        }

    }
}
function cancelaEntrada(id, monto) {
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });
    saldo = $("#saldo_final").html().replace(",", "");
    saldo = saldo.replace("$", "");
    saldo = parseFloat(saldo) - parseFloat(monto);
    data = { entradas_id: id };
    $.ajax({
        url: $("#path").val() + '/Ajax/cancelaentrada',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#buttonentrada" + id).html("<i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
            $("#buttonentrada" + id).prop("disabled", true);
        }
    }).done(function (msg) {
        if (msg) {
            $("#saldo_final").html(formatter.format(saldo));
            $("#entrada" + id).hide("slow");
            setTimeout(function () { $("#entrada" + id).remove(); }, 2000)
        }

    });

}
function cancelaRetiro(id, monto) {
    var formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    });
    saldo = $("#saldo_final").html().replace(",", "");
    saldo = saldo.replace("$", "");
    saldo = parseFloat(saldo) + parseFloat(monto);
    data = { retiros_id: id };
    $.ajax({
        url: $("#path").val() + '/Ajax/cancelaretiro',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#buttonretiro" + id).html("<i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
            $("#buttonretiro" + id).prop("disabled", true);
        }
    }).done(function (msg) {
        if (msg) {
            $("#saldo_final").html(formatter.format(saldo));
            $("#retiro" + id).hide("slow");
            setTimeout(function () { $("#retiro" + id).remove(); }, 2000)
        }

    });

}

function getDetalleVenta() {
    ticket = $("#inputTicket").val();
    if (ticket.length > 0 && !isNaN(ticket)) {
        data = { ventas_id: ticket }

        $.ajax({
            url: $("#path").val() + '/Ajax/getdetallesventa',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#detalles").html("<center><i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
                $("#labelDetalleVenta").show("slow");
                $("#contenedorDetalleVenta").show("slow");
            }
        }).done(function (msg) {
            $("#detalles").html(msg);
            getmovimientosdev(ticket);
        });
    }
}

function getDetalleVentaFac() {
    ticket = $("#inputTicket").val();
    if (ticket.length > 0 && !isNaN(ticket)) {
        data = { ventas_id: ticket }

        $.ajax({
            url: $("#path").val() + '/Ajax/getdetallesventafac',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#detalles").html("<center><i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
                //$("#labelDetalleVenta").show("slow");
                //$("#contenedorDetalleVenta").show("slow");
            }
        }).done(function (msg) {
            $("#detalles").html(msg);
        });
    }
}

function almacenaCompra() {
    validate = true;
    items_id = "";
    cantidades = "";

    $(".items_id").each(function () {
        items_id += $(this).html() + "|";
    });

    $(".cantidades").each(function () {
        cantidades += $(this).val() + "|";
    });
    errors = "";


    identificador = $("#inputIdentificadorCompra").val().trim();

    if (identificador.length == 0) {
        errors += "<div class='alert alert-warning'>Indique el Campo Identificador</div>" + "<br/>";
        validate = false;
    }


    fecha_compra = $("#inputFechaCompra").val().trim();
    if (fecha_compra.length == 0) {
        errors += "<div class='alert alert-warning'>Indique la fecha de compra!</div>" + "<br/>";
        validate = false;
    }

    total = $("#inputTotalCompra").val().trim();

    if (total.length == 0) {
        errors += "<div class='alert alert-warning'>Indique el total de la Compra</div>" + "<br/>";
        validate = false;
    }

    proveedores_id = $("#selectProveedorCompra option:selected").val();
    tipo_documento = $("#selecttipodocumento option:selected").val();
    origen_pago = $("#selectorigenpago option:selected").val();

    data = { origen_pago: origen_pago, tipo_documento: tipo_documento, identificador: identificador, fecha_compra: fecha_compra, total_compra: total, proveedores_id: proveedores_id, items_id: items_id, cantidades: cantidades };

    $("#infoCompra").modal("show");
    if (!validate) {
        $("#modal-bodyCompra").html(errors);
    } else {
        $("#modal-bodyCompra").html("<div class='alert alert-success'>Se mandará para el almacenamiento de la Compra</div>" + JSON.stringify(data));

        $.ajax({
            url: $("#path").val() + '/Ajax/almacenacompra',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#modal-bodyCompra").html("<center><i style='font-size:2em;color:blue' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
            }
        }).done(function (msg) {
            $("#modal-bodyCompra").html(msg);
        });

    }
}

function getDetalleCompra(compras_id) {
    data = { compras_id: compras_id };
    $.ajax({
        url: $("#path").val() + '/Ajax/getdetallescompra',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#modal-bodyCompra").html("<center><i style='font-size:2em;color:blue' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
        }
    }).done(function (msg) {
        $("#modal-bodyCompra").html(msg);
    });

}

function inicializaEliminarCompra() {
    $("#modal-bodyDelCompra").html('<div class="alert alert-warning">Desea Eliminar el Registro de Compra <span id="compraId"></span>, considere que los inventarios serán nuevamente actualizados a las existencias antes del registro de esta Compra.</div>');
    $("#btnEliminarCompra").show();
}

function eliminaCompra() {
    compras_id = $("#compraId").html();

    data = { compras_id: compras_id };
    $.ajax({
        url: $("#path").val() + '/Ajax/eliminacompra',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#modal-bodyDelCompra").html("<center><i style='font-size:2em;color:blue' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
        }
    }).done(function (msg) {
        $("#btnEliminarCompra").hide();
        if (msg) {
            $("#modal-bodyDelCompra").html('<div class="alert alert-success"> La compra ' + compras_id + ' ha sido Eliminada Satisfactoriamente y fueron actualizados los Inventarios Correspondientes!</div>');
            $("#rowcompra" + compras_id).remove();
        } else {
            $("#modal-bodyDelCompra").html('<div class="alert alert-warning"> La compra ' + compras_id + ' No pudo ser Eliminada, favor de intentar más tarde!</div>');
        }

        //$("#delCompra").modal("hide");
    });
}

function getClientes() {
    rfc = $("#inputRFC").val();
    if (rfc.length >= 3) {
        data = { rfc: rfc };
        $.ajax({
            url: $("#path").val() + '/Ajax/getclientes',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#loading_clientes").html("<center><i style='font-size:2em;color:pink' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
            }
        }).done(function (msg) {
            $("#loading_clientes").html("");
            $("#list_clientes").html(msg);
        });
    }
    else {
        $("#list_clientes").html("");
        $("#list_clientes").hide("slow");
    }
}

function Facturar() {

    valido = true;
    errors = "";
    ventas_id = $("#inputTicket").val();
    if (ventas_id.trim().length == 0) {
        valido = false;
        errors += "<div class='alert alert-warning'>Indicar un Número de ticket válido!</div>";
    }
    rfc = $("#inputRFC").val();
    if (rfc.trim().length == 0 || rfc.trim().length < 12) {
        valido = false;
        errors += "<div class='alert alert-warning'>Indicar RFC válido!</div>";
    }
    nombre = $("#inputNombre").val();
    if (nombre.trim().length == 0) {
        valido = false;
        errors += "<div class='alert alert-warning'>Indicar un Nombre o una Razón social Válida!</div>";
    }
    usoCFDI = $("#selectUso option:selected").val();
    if (!valido) {
        $("#modal-bodyFacturacion").html(errors);
        $("#infoFacturacion").modal("show");
    }
    else {
        data = { ventas_id: ventas_id, rfc: rfc, nombre: nombre, usoCFDI: usoCFDI };
        $.ajax({
            url: $("#path").val() + '/Facturacion/facturar',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#infoFacturacion").modal("show");
                $("#modal-bodyFacturacion").html("<center><i style='font-size:2em;color:blue' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
            }
        }).done(function (msg) {
            $("#modal-bodyFacturacion").html(msg);
            $("#infoFacturacion").modal("show");
        });
    }

}

function clearFac() {
    $("#inputTicket").val("");
    $("#detalles").html("");
    $("#inputRFC").val("");
    $("#inputNombre").val("");
    $("#receptorContent").hide("slow");
    $("#usoContent").hide("slow");
}

function incluirFacturacion(check) {
    if (check) {
        $('#facturacion_content').show('slow');
        $("#inputRFC").prop("required", true);
        $("#input_nombre_comercial").prop("required", true);
        $("#inputCalle").prop("required", true);
        $("#inputNumInterno").prop("required", true);
        $("#inputNumExterno").prop("required", true);
        $("#inputColonia").prop("required", true);
        $("#inputMunicipio").prop("required", true);
        $("#inputCp").prop("required", true);
    } else {
        $('#facturacion_content').hide('slow');
        $("#inputRFC").prop("required", false);
        $("#input_nombre_comercial").prop("required", false);
        $("#inputCalle").prop("required", false);
        $("#inputNumInterno").prop("required", false);
        $("#inputNumExterno").prop("required", false);
        $("#inputColonia").prop("required", false);
        $("#inputMunicipio").prop("required", false);
        $("#inputCp").prop("required", false);
    }

}

function loadProductos(descripcion) {
    if (descripcion.length >= 3) {
        data = { descripcion: descripcion };
        $.ajax({
            url: $("#path").val() + '/Ajax/loadproductosv',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#lista_productos_v").html("<center><i style='font-size:2em;color:blue' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
            }
        }).done(function (msg) {
            $("#lista_productos_v").html(msg);
            $("#lista_productos_v").show("fast");

        });

    } else {
        $("#lista_productos_v").html("");
        $("#lista_productos_v").hide("fast");
    }
}
function loadProductosc(descripcion) {
    if (descripcion.length >= 3) {
        data = { descripcion: descripcion };
        $.ajax({
            url: $("#path").val() + '/Ajax/loadproductosvc',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#lista_productos_v").html("<center><i style='font-size:2em;color:blue' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
            }
        }).done(function (msg) {
            $("#lista_productos_v").html(msg);
            $("#lista_productos_v").show("fast");

        });

    } else {
        $("#lista_productos_v").html("");
        $("#lista_productos_v").hide("fast");
    }
}

function registraMovimiento(objeto, cantidad, movimiento, venta_items_id) {
    //alert($(objeto).attr("ventas_id"));



    cantidad_adquirida = $(objeto).attr("cantidad_adquirida");
    cantidad = parseFloat(cantidad);
    if ((cantidad > 0) && (cantidad <= cantidad_adquirida)) {
        ventas_id = $(objeto).attr("ventas_id");
        items_id = $(objeto).attr("items_id");
        precio = $(objeto).attr("precio");
        iva = $(objeto).attr("iva");
        descuento_total = $(objeto).attr("descuento");
        descuento_movimiento = (descuento_total / cantidad_adquirida) * cantidad;
        subtotal = (precio * cantidad) - ((descuento_total / cantidad_adquirida) * cantidad);
        iva_monto = (subtotal * iva);
        total = (subtotal + iva_monto);

        subtotal = subtotal.toFixed(2);
        iva_monto = iva_monto.toFixed(2);
        total = total.toFixed(2);

        data = {
            ventas_id: ventas_id,
            items_id: items_id,
            precio: precio,
            cantidad: cantidad,
            iva: iva,
            descuento: descuento_movimiento,
            subtotal: subtotal,
            iva_monto: iva_monto,
            total: total,
            tipo: movimiento
        };

        $.ajax({
            url: $("#path").val() + '/Ajax/registramovdev',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#button" + venta_items_id).html("<center><i style='font-size:1em;color:white' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
                $("#button" + venta_items_id).prop("disabled", true);
            }
        }).done(function (msg) {
            $("#button" + venta_items_id).html("Registrar");
            $("#button" + venta_items_id).prop("disabled", false);

            $("#modal-titleDetalleDev").html("Registro de Movimientos");
            $("#modal-bodyDetalleDev").html(msg);
            $("#modal-footerDetalleDev").html('<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>');




            $('#detalleDev').modal('show');
            getmovimientosdev(ventas_id);


        });
    }
}

function getmovimientosdev(ventas_id) {
    data = { ventas_id: ventas_id };
    $.ajax({
        url: $("#path").val() + '/Ajax/getmovimientosdev',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#movimientos_dev").html("<center><i style='font-size:1em;color:white' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
        }
    }).done(function (msg) {
        $("#movimientos_dev").html(msg);
    });
}

function showCuponArea(show) {
    if (show) {
        $("#resultCupon").show();
        $("#inputCupon").show();
        $("#inputCupon").focus();
        findCupon($("#inputCupon").val())
    } else {
        $("#resultCupon").hide();
        $("#inputCupon").hide();
        $("#resultCupon").html('');
        $("#inputCupon").val('');
        $("#checkCupon").prop("checked", false);
        $("#inputPago").val($("#inputTotal").val());
    }
}

function findCupon(cupon) {
    if (cupon.length >= 5) {
        data = { cupon: cupon };
        $.ajax({
            url: $("#path").val() + '/Ajax/getdetailcupon',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#resultCupon").html("<center><i style='font-size:1em;color:white' class='fa fa-spinner fa-spin fa-3x fa-fw'></i></center>");
                $("#inputPago").val($("#inputTotal").val());
            }
        }).done(function (msg) {
            //msg = $.parseJSON(msg)
            //for( var i=0;i<msg.length;i++)

            if (msg == '')
                $("#resultCupon").html("<span style='font-size:.5em;color:gray;'><i class='fas fa-exclamation-circle' style='color:red'></i> <span style='color:red'>Cupón no Válido</span> </span>");
            else {
                $("#resultCupon").html("<span style='font-size:.5em;color:gray;'><i class='fas fa-check' style='color:green'></i> Saldo: $ " + parseFloat(msg).toFixed(2) + "</span> <span style='display:none' id='ban_cupon' monto='" + parseFloat(msg).toFixed(2) + "'>1</span>");
                pago = parseFloat($("#inputPago").val());
                cupon = parseFloat(msg);
                pago = pago - cupon;
                $("#inputPago").val(pago.toFixed(2));
            }

        });
    }
    else {
        $("#resultCupon").html("<span style='font-size:.5em;color:gray;'>Ingrese Cupón Válido</span>");
        $("#inputPago").val($("#inputTotal").val());
    }

}

function confirmaEliminarMovimiento(movimiento_id, tipo) {
    $("#modal-titleDetalleDev").html("Cancelación de Movimientos");
    $("#modal-bodyDetalleDev").html("<div class='alert alert-info'>¿Confirma cancelar la devolución: " + movimiento_id + "?</div>")
    $("#modal-footerDetalleDev").html("<button class='btn btn-warning' data-dismiss='modal' onclick='eliminaMovimiento(" + movimiento_id + ",\"" + tipo + "\")'>Si</button><button class='btn btn-primary' data-dismiss='modal'>No</button>");
    $("#detalleDev").modal("show");
}

function eliminaMovimiento(movimiento_id, tipo) {
    data = { movimiento_id: movimiento_id, tipo: tipo };
    $.ajax({
        url: $("#path").val() + '/Ajax/deletemovimiento',
        type: 'post',
        data: data,
        beforeSend: function () {
            $("#btnmovimiento" + movimiento_id).html("<i style='font-size:1em;color:white' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
        }
    }).done(function (msg) {
        if (msg)
            $("#movimiento" + movimiento_id).hide("slow");
        else {
            $("#btnmovimiento" + movimiento_id).html("Error!");
        }

    });
}

function addGiro() {
    nuevo_giro = $("#newGiro").val();
    if (nuevo_giro.length > 0) {
        $.ajax({
            url: $("#path").val() + "/Ajax/addgiro",
            type: 'POST',
            data: { nuevo_giro: nuevo_giro },
            beforeSend: function () {
                $("#loading_giros").show("");
            }
        }).done(function (msg) {
            if (msg != 0) {
                $("#msgresultgiro").html("<div class='alert alert-success' style='font-size:.8em'>\"" + nuevo_giro + "\" almacenado satisfactoriamente!</div>")
                $("#loading_giros").hide("");
                msg = "<option value='0'>Selecciona.</option>" + msg;
                $("#giro_comercio").html(msg);
                setTimeout(function () {
                    $("#modalGiro").modal("hide");
                }, 1000);
            } else {
                $("#msgresultgiro").html("<div class='alert alert-danger' style='font-size:.8em'>\"" + nuevo_giro + "\" no se almacenó, intente más tarde!</div>")
                $("#loading_giros").hide("");
            }
            $("#newGiro").val('');
        });

    }
}

function almacenaCotizacion() {

    cliente = $("#cliente option:selected").val();
    if (cliente == 0) {
        $("#infoBody").html('<div class="alert alert-warning">Seleccione una Opción de cliente</div>');
        $("#info").modal("show");
    } else if ($(".items").length <= 0) {
        $("#infoBody").html('<div class="alert alert-warning">Debe Agregar Artículos a esta Cotización</div>');
        $("#info").modal("show");
    } else {
        turnos_id = $("#turnos_id").val();
        descuento = $("#descuento").html().replace(",", "").replace("$", "");
        iva = $("#iva").html().replace(",", "").replace("$", "");
        subtotal = $("#subtotal").html().replace(",", "").replace("$", "");
        total = $("#gran_total").html().replace(",", "").replace("$", "");

        items = "";
        precios = "";
        cantidades = "";
        ivas = "";
        subtotales = "";
        ivamontos = "";
        descuentos = "";
        totales = "";

        $(".items").each(function () {
            items += $(this).html() + "|";
        });
        $(".precios").each(function () {
            precios += $(this).html().replace(",", "").replace("$", "") + "|";
        });
        $(".cantidades").each(function () {
            cantidades += $(this).val() + "|";
        });
        $(".ivas").each(function () {
            ivas += parseFloat($(this).html()) / 100 + "|";
        });
        $(".subtotales").each(function () {
            subtotales += $(this).html() + "|";
        });
        $(".ivamontos").each(function () {
            ivamontos += $(this).html() + "|";
        });
        $(".descuentos").each(function () {
            descuentos += $(this).val() + "|";
        });
        $(".totales").each(function () {
            totales += $(this).html() + "|";
        });

        //Dato de Comentarios
        comentarios = $("#comentarios").val();

        //Dato tipo de precio
        tipo_precio = $("#precio_de_venta option:selected").val();

        //Dato de dias de vigencia
        vigencia = $("#vigencia").val();



        data = {
            turnos_id: turnos_id,
            descuento: descuento,
            iva: iva,
            subtotal: subtotal,
            total: total,
            items: items,
            precios: precios,
            cantidades: cantidades,
            ivas: ivas,
            subtotales: subtotales,
            ivamontos: ivamontos,
            descuentos: descuentos,
            totales: totales,
            clientes_id: cliente,
            comentarios: comentarios,
            tipo_precio: tipo_precio,
            vigencia: vigencia

        };

        path = $("#path").val();
        $.ajax({
            url: $("#path").val() + '/Caja/almacenacotizacion',
            type: 'post',
            data: data,
            beforeSend: function () {
                $("#btnGeneraCotizacion").html("<i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
                $("#btnGeneraCotizacion").prop("disabled", true);
            }
        }).done(function (msg) {
            if (msg != null) {
                $("#btnGeneraCotizacion").html(
                    'Generar Cotizacion'
                );
                $("#btnGeneraCotizacion").prop("disabled", false);
                $("#body_productos").html("");
                $("#cliente").val("0");
                $("#infoBody").html('<div class="alert alert-success">La Cotización se ha generado satisfactoriamente! <hr/><div align="center"><a target="_blank" href="' + path + '/Caja/imprimecotizacion?ctz=' + msg + '"><button class="btn btn-success" onclick="$(\'#info\').modal(\'hide\')">Descarga Cotización</button></a></div></div>');
                $("#info").modal("show");
                limpiaCotizacion();


            } else {
                $("#btnGeneraCotizacion").html(
                    'Generar Cotizacion'
                );
                $("#btnGeneraCotizacion").prop("disabled", false);
                $("#infoBody").html('<div class="alert alert-warning">No se generó la cotización, intentar más tarde.!</div>');
                $("#info").modal("show");
            }
        });
    }
}

function limpiaCotizacion() {
    $("#body_productos").html("");
    $("#precio_de_venta").prop("disabled", false);
    $("#comentarios").val('');
    $("#vigencia").val('1');
    actualizaVenta();
}

function showCotizaciones() {

    $.ajax({
        url: $("#path").val() + '/Caja/listacotizaciones',
        type: 'post',
        data: {},
        beforeSend: function () {
            $("#listCotizaciones").modal("show");
            $("#listBody").html("<i style='font-size:2em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
        }
    }).done(function (msg) {
        $("#listBody").html(msg);
    });
}

function modalCotizacion() {
    $("#modalCotizacion").modal("show");
    setTimeout(function () { $("#inputCotizacion").get(0).focus(); }, 500);
}
var artic = null;
var cotiz = null;
function getDataCotizacion() {
    cotizacion = $("#inputCotizacion").val();
    if (cotizacion.length >= 3) {
        $.ajax({
            url: $("#path").val() + '/Ajax/getcotizacion',
            type: 'post',
            data: { cotizacion: cotizacion },
            beforeSend: function () {
                $("#resultCotizacion").html("<i style='font-size:1em;color:green' class='fa fa-spinner fa-spin fa-3x fa-fw'></i>");
            }
        }).done(function (msg) {
            var resul = $.parseJSON(msg);
            if (resul.result == 1) {
                $("#resultCotizacion").html("<span class='text-success'>" + resul.mensaje + "</span>");
                $("#btnCotizacion").show("");
                cotiz = resul.cotizacion;
                artic = resul.articulos;
                $("#btnCotizacion").attr("onclick", "$('#modalCotizacion').modal('hide');vaciaCaVenta(); ");
            }
            else {
                $("#resultCotizacion").html("<span class='text-info'>" + resul.mensaje + "</span>");
                $("#btnCotizacion").hide("");
            }

        });
    }
    else {
        $("#resultCotizacion").html('');
    }
}

function vaciaCaVenta() {
    $("#precio_de_venta").val(cotiz["tipo_precio"]);
    artic.forEach(function (element) {
        $("#cant").val(element["cantidad"]);
        $("#input_producto").val(element["clave"]);
        addProducto();
    });
}

function procesaExtensiones() {
    var cadena = ".jpeg,image/jpeg| .png,image/png|.gif,image/gif| .tiff,image/tiff| .tif,image/tif|.pdf,application/pdf|.zip,application/x-zip-compressed|.docx,application/msword|.xsl,application/vnd.ms-excel|.xslsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|.rar,application/x-rar-compressed|.pptx,application/vnd.openxmlformats-officedocument.presentationml.presentation|.csv,text/csv|";
    var array = cadena.split("|");
    var extensiones = [];
    var tipos = [];
    array.pop();
    array.forEach(function (val, index) {
        res = val.split(",");
        extensiones.push(res[0]);
        tipos.push(res[1]);
    });
    alert(tipos);

}

function modalCliente() {
    $("#modalCliente").modal("show");
}

function setCliente() {
    clientes_id = $("#cliente").val();
    nombreCliente = $("#cliente option:selected").text();
    $("#clientes_id").val(clientes_id);
    $("#clienteNombre").html('');
    $("#clienteNombre").html(nombreCliente);
    $("#modalCliente").modal("hide");
}

function setPago() {
    if ($("#tipoPago").val() == "credito") {
        if ($("#cliente").val() != 0) {
            $("#inputPago").attr('readonly', true);
            $("#inputPago").val('0');
            setCambio();
        }else{
            $("#tipoPago").val("efectivo");
            setPago();
            alert("Seleccione primero un cliente al cual vender")

        }

    } else {
        total = parseFloat($("#gran_total").html().replace(",", "").replace("$", ""));
        $("#inputPago").val(total);
        $("#inputPago").attr('readonly', false);
        setCambio();
    }
}
//procesaExtensiones();
