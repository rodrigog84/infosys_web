Ext.define('Infosys_web.controller.Pedidos', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    models: ['Pedidos',
             'pedidos.Item'],


    stores: ['Pedidos.Editar',
            'Pedidos.Items',
            'Pedidos.Selector3',
            'Productosf',
            'Productosf',
            'Pedidos',
            'Sucursales_clientes',
            'Correlativos',
            'Clientes',
            'clientes.Selector2',
            'facturapedidos',
            'Bodegas',
            'Factura'
             ],

    
    views: ['Pedidos.Pedidos',
            'Pedidos.Principal',
            'Pedidos.BuscarClientes',
            'Pedidos.Editarpedidos',
            'Pedidos.BuscarProductos',
            'ventas.BuscarSucursales',
            'Pedidos.Observaciones',
            'Pedidos.Observaciones2',
            'Pedidos.Exportar',
            'Pedidos.Exportar2',
            'Pedidos.Facturas'
            ],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{    
       ref: 'pedidosprincipal',
        selector: 'pedidosprincipal'
    },{    
        ref: 'pedidosingresar',
        selector: 'pedidosingresar'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{    
        ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{    
        ref: 'buscarclientespedidos',
        selector: 'buscarclientespedidos'
    },{
        ref: 'buscarproductospedidos',
        selector: 'buscarproductospedidos'
    },{
        ref: 'editarpedidos',
        selector: 'editarpedidos'
    },{
        ref: 'buscarproductospedidos2',
        selector: 'buscarproductospedidos2'
    },{
        ref: 'buscarsucursalesclientes',
        selector: 'buscarsucursalesclientes'
    },{
        ref: 'observacionespedidos',
        selector: 'observacionespedidos'
    },{
        ref: 'observacionespedidos2',
        selector: 'observacionespedidos2'
    },{
        ref: 'formularioexportarpedidos',
        selector: 'formularioexportarpedidos'
    },{
        ref: 'facturasingresarpedidos',
        selector: 'facturasingresarpedidos'
    },{
        ref: 'formulariopdf',
        selector: 'formulariopdf'
    }

  
    ],
    
    init: function() {
    	
        this.control({
            'topmenus menuitem[action=mPedidos]': {
                click: this.mPedidos
            },

            'topmenus menuitem[action=mInforme]': {
                click: this.exportarlibrorecaudacion
            },            
            'pedidosingresar button[action=grabarpedidos]': {
                click: this.grabarpedidos
            },
            'editarpedidos button[action=grabarpedidos2]': {
                click: this.grabarpedidos2
            },
            'pedidosprincipal button[action=agregarpedido]': {
                click: this.agregarpedido
            },
            'pedidosprincipal button[action=cerrarpedidos]': {
                click: this.cerrarpedidos
            },
            'buscarclientespedidos button[action=seleccionarcliente]': {
                click: this.seleccionarcliente
            },
            'pedidosingresar button[action=buscarproductos]': {
                click: this.buscarproductos
            },
            'editarpedidos button[action=buscarproductos2]': {
                click: this.buscarproductos2
            },
            'buscarproductospedidos button[action=seleccionarproductos]': {
                click: this.seleccionarproductos
            },
            'buscarproductospedidos button[action=buscar]': {
                click: this.buscarp
            },
            'buscarproductospedidos2 button[action=seleccionarproductos2]': {
                click: this.seleccionarproductos2
            },
            'buscarproductospedidos2 button[action=buscar2]': {
                click: this.buscarp2
            },
            'pedidosingresar button[action=validarut]': {
                click: this.validarut
            },
            'pedidosingresar button[action=buscarsucursalfactura]': {
                click: this.buscarsucursalfactura
            },           
            'buscarclientespedidos button[action=buscarclientes]': {
                click: this.buscar
            },
            'pedidosingresar #productoId': {
                select: this.selectItem
            },
            'pedidoseditar #productoId': {
                select: this.selectItem2
            },
            'pedidosingresar button[action=agregarItem]': {
                click: this.agregarItem
            },
            'editarpedidos button[action=agregarItem2]': {
                click: this.agregarItem2
            },
            'pedidosingresar #finaldescuentoId': {
                change: this.changedctofinal
            },
            'pedidosingresar #tipoDocumento2Id': {
                select: this.selectItemdocuemento
            },
            'pedidosprincipal button[action=exportarpedidos]': {
                click: this.exportarpedidos
            },
            'pedidosprincipal button[action=editarpedidos]': {
                click: this.editarpedidos
            },
            'pedidosprincipal button[action=buscarpedido]': {
                click: this.buscarpedidos2
            },
            'pedidosingresar button[action=eliminaritem]': {
                click: this.eliminaritem
            },
            'editarpedidos button[action=eliminaritem2]': {
                click: this.eliminaritem2
            },
            'pedidosingresar button[action=editaritem]': {
                click: this.editaritem
            },
            'editarpedidos button[action=editaritem2]': {
                click: this.editaritem2
            },
            'buscarsucursalesclientes button[action=seleccionarsucursalclientepedidos]': {
                click: this.seleccionarsucursalclientepedidos
            },
            'pedidosingresar button[action=observaciones]': {
                click: this.agregarobserva
            },
            'observacionespedidos button[action=ingresaobs]': {
                click: this.ingresaobs
            },
            'editarpedidos button[action=observaciones]': {
                click: this.agregarobserva2
            },
            'observacionespedidos2 button[action=ingresaobs2]': {
                click: this.ingresaobs2
            },
            'pedidosingresar #DescuentoproId': {
                change: this.changedctofinal3
            },
            'editarpedidos #DescuentoproId': {
                change: this.changedctofinal4
            },
            'formularioexportarpedidos button[action=exportarExcelFormulario]': {
                click: this.exportarExcelFormulario
            },
            'pedidosprincipal button[action=exportarexcelpedidos]': {
                click: this.exportarexcelpedidos
            },
            'pedidosprincipal button[action=generafactura]': {
                click: this.generafactura
            },
            'facturasingresarpedidos button[action=grabarfactura]': {
                click: this.grabarfactura
            },
            'pedidosprincipal button[action=exportarexcelpedidoscaja]': {
                click: this.exportarlibrorecaudacion
            },
            'pedidosprincipal #Seleccion2Id': {
                change: this.changedctofinal8                
            },
            'pedidosprincipal button[action=exportarpdf]': {
                click: this.exportarpdf
            },
            'formulariopdf button[action=exportarExcelFormulariopdf]': {
                click: this.exportarExcelFormulariopdf
            },
        });
    },

    exportarpdf: function(){

        Ext.create('Infosys_web.view.Pedidos.Exportar2').show();        

    },    

    exportarExcelFormulariopdf: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var view =this.getFormulariopdf();
        var fecha = view.down('#fechaId').getSubmitValue();
                
        window.open(preurl + 'pedidos/exportarPdfinformeproduccion?fecha='+fecha);
        view.close();     
 
    },

    exportarlibrorecaudacion : function(){

        Ext.create('Infosys_web.view.pedidos_caja.Exportar');
    },

    grabarfactura: function() {

        var viewIngresa = this.getFacturasingresarpedidos();
        var bolEnable = true;
        viewIngresa.down('#grabarfactura').setDisabled(bolEnable);
        var tipo_documento = viewIngresa.down('#tipoDocumentoId');
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idtipo= viewIngresa.down('#tipoDocumentoId').getValue();
        var idsucursal= viewIngresa.down('#id_sucursalID').getValue();
        var idcondventa= viewIngresa.down('#tipocondpagoId').getValue();
        var idfactura = viewIngresa.down('#idfactura').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var observa = viewIngresa.down('#observaId').getValue();
        var idobserva = viewIngresa.down('#obsId').getValue();
        var numfactura = viewIngresa.down('#numfacturaId').getValue();
        var formadepago = viewIngresa.down('#tipocondpagoId').getValue();
        var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
        var fechavenc = viewIngresa.down('#fechavencId').getValue();
        var stItem = this.getFacturapedidosStore();
        var stFactura = this.getFacturaStore();

        if(vendedor==0  && tipo_documento.getValue() == 1){                 
            //viewIngresa.down('#grabarId').setDisabled(bolEnable);
            Ext.Msg.alert('Ingrese Datos del Vendedor');
            return;   
        }

        if(numfactura==0){
            //viewIngresa.down('#grabarId').setDisabled(bolEnable);
            
            Ext.Msg.alert('Ingrese Datos a La Factura');
            return;   
            }

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'facturas/save',
            params: {
                idcliente: idcliente,
                idfactura: idfactura,
                idsucursal: idsucursal,
                idcondventa: idcondventa,
                idtipo:idtipo,
                items: Ext.JSON.encode(dataItems),
                observacion: observa,
                idobserva: idobserva,
                vendedor : vendedor,
                numfactura : numfactura,
                fechafactura : fechafactura,
                fechavenc: fechavenc,
                formadepago: formadepago,
                tipodocumento : tipo_documento.getValue(),
                netofactura: viewIngresa.down('#finaltotalnetoId').getValue(),
                ivafactura: viewIngresa.down('#finaltotalivaId').getValue(),
                afectofactura: viewIngresa.down('#finalafectoId').getValue(),
                descuentofactura : viewIngresa.down('#descuentovalorId').getValue(),
                totalfacturas: viewIngresa.down('#finaltotalpostId').getValue()
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idfactura= resp.idfactura;
                 viewIngresa.close();
                 stFactura.load();
                 window.open(preurl + 'facturas/exportPDF/?idfactura='+idfactura);

            }
           
        });
    },



    generafactura : function() {

        var view = this.getPedidosprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var idpago = (row.data.id_pago);
            var factura = (row.data.tip_documento);
            var nombre = (row.data.tip_documento);
            var id_giro = (row.data.giro);
            var id_bodega = (row.data.id_bodega);
            var fechafactura = (row.data.fecha_doc);
            var st = this.getFacturapedidosStore()
            var pedido = (row.data.id);
            var idcliente = (row.data.id_cliente);
            var total = parseInt(row.data.total);
            var neto = (Math.round(parseInt(row.data.total) / 1.19))
            var iva = (total - neto);
            
            st.proxy.extraParams = {pedido : pedido}
            st.load();        
            var newview =Ext.create('Infosys_web.view.Pedidos.Facturas').show();

            Ext.Ajax.request({
            url: preurl +'pedidos/edita/?idpedidos=' + row.data.id,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cliente = (resp.cliente); 
                if (resp.success == true) {                    
                    var total = (cliente.total);
                    var neto = (cliente.neto);
                    var iva = (cliente.total - cliente.neto);
                    newview.down("#finalafectoId").setValue(neto);
                    newview.down("#finaltotalnetoId").setValue(neto);
                    newview.down("#finaltotalivaId").setValue(iva);
                    newview.down("#finaltotalId").setValue(Ext.util.Format.number(total, '0,000'));
                    newview.down("#finaltotalpostId").setValue(total);
            
                }

            }            
            });
                      
            Ext.Ajax.request({
                url: preurl + 'cond_pago/calculodias',
                params: {
                    idpago: idpago
                },
                success: function(response){
                   var resp = Ext.JSON.decode(response.responseText);
                   var dias = resp.dias;
                   Ext.Ajax.request({
                        url: preurl + 'facturas/calculofechas',
                        params: {
                            dias: dias,
                            fechafactura : fechafactura
                        },
                        success: function(response){
                           var resp = Ext.JSON.decode(response.responseText);
                           var fecha_final = resp.fecha_final;
                           newview.down("#fechavencId").setValue(fecha_final);                                  
                        }
                  });                                
                }
            });

            if(nombre == 101 || nombre == 103 || nombre == 105){ // FACTURA ELECTRONICA o FACTURA EXENTA

            // se valida que exista certificado
            response_certificado = Ext.Ajax.request({
            async: false,
            url: preurl + 'facturas/existe_certificado/'});

                var obj_certificado = Ext.decode(response_certificado.responseText);

                if(obj_certificado.existe == true){

                response_folio = Ext.Ajax.request({
                async: false,
                url: preurl + 'facturas/folio_documento_electronico/'+nombre});
                 
                var obj_folio = Ext.decode(response_folio.responseText);
                //console.log(obj_folio); 
                nuevo_folio = obj_folio.folio;
                if(nuevo_folio != 0){
                    newview.down('#numfacturaId').setValue(nuevo_folio);  
                    habilita = true;
                }else{
                    Ext.Msg.alert('Atención','No existen folios disponibles');
                    newview.down('#numfacturaId').setValue('');
                }

                }else{
                    Ext.Msg.alert('Atención','No se ha cargado certificado');
                    newview.down('#numfacturaId').setValue('');  
                }
            };
            
            Ext.Ajax.request({
            url: preurl + 'clientes/getallc?idcliente='+idcliente,
            params: {
                id: 1,
                idcliente: idcliente
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cliente = (resp.cliente);                
                if (resp.success == true) {
                    if(resp.cliente){
                        newview.down("#tipoDocumentoId").setValue(factura);
                        newview.down("#rutId").setValue(cliente.rut);
                        newview.down("#nombre_id").setValue(cliente.nombres);
                        newview.down("#direccionId").setValue(cliente.direccion);
                        newview.down("#giroId").setValue(cliente.giro);
                        newview.down("#tipoComunaId").setValue(cliente.nombre_comuna);
                        newview.down("#tipoCiudadId").setValue(cliente.nombre_ciudad);
                        newview.down("#tipoVendedorId").setValue(cliente.id_vendedor);
                        newview.down("#tipocondpagoId").setValue(cliente.id_pago);
                        newview.down("#giroId").setValue(cliente.id_giro);
                        newview.down("#id_cliente").setValue(idcliente);
                        newview.down("#bodegaId").setValue(id_bodega);
                    }
                }
            }
            });         
           
            //newview.down("#finaltotalnetoId").setValue(Ext.util.Format.number(neto, '0,000'));
             //newview.down("#finaltotalUnformat").setValue(total_unformat);
    
        }else{
            Ext.Msg.alert('Atención','Debe Selecionar Pedido');
            return;
            
        }
   
    },

    exportarexcelpedidos: function(){

        var viewnew =this.getPedidosprincipal();       
        Ext.create('Infosys_web.view.Pedidos.Exportar').show();
      
    
    },

    exportarExcelFormulario: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getPedidosprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportarpedidos()
        var viewnew =this.getPedidosprincipal()

        var fecha = view.down('#fechaId').getSubmitValue();
        
        var fecha2 = view.down('#fecha2Id').getSubmitValue();
                
        if (fecha > fecha2) {
        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };     

        window.open(preurl + 'adminServicesExcel/exportarExcelPedidos?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2);
        view.close();    

     
 
    },

    recalculardescuentopro: function(){
        var view = this.getPedidosingresar();
        var precio = view.down('#precioId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var total = ((precio * cantidad));
        var desc = view.down('#DescuentoproId').getValue();
        if (desc){
        var descuento = view.down('#DescuentoproId');
        var stCombo = descuento.getStore();
        var record = stCombo.findRecord('id', descuento.getValue()).data;
        var dcto = (record.porcentaje);
        totaldescuento = (Math.round(total * dcto)  / 100);
        view.down('#totdescuentoId').setValue(totaldescuento);
        };         
    },

    recalculardescuentopro2: function(){
        var view = this.getEditarpedidos();
        var precio = view.down('#precioId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var total = ((precio * cantidad));
        var desc = view.down('#DescuentoproId').getValue();
        if (desc){
        var descuento = view.down('#DescuentoproId');
        var stCombo = descuento.getStore();
        var record = stCombo.findRecord('id', descuento.getValue()).data;
        var dcto = (record.porcentaje);
        totaldescuento = (Math.round(total * dcto)  / 100);
        view.down('#totdescuentoId').setValue(totaldescuento);
        };         
    },

    changedctofinal3: function(){
        this.recalculardescuentopro();
    },

     changedctofinal4: function(){
        this.recalculardescuentopro2();
    },

    changedctofinal8: function(){
        this.buscarpedidos2();
    },

    

    buscarDoc: function(){
        
        var view = this.getPedidosprincipal();
        var st = this.getPedidosStore();
        var opcion = view.down('#tipoSeleccionId').getValue();
        var tipo = view.down('#tipoPedidoId').getValue();
        var nombre = view.down('#nombreId').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion,
                                tipo: tipo
                               }
        st.load();
        var cero="";
        view.down('#tipoSeleccionId').setValue(cero);
        //view.down('#tipoPedidoId').setValue(cero);
        view.down('#nombreId').setValue(cero);

    },

    ingresaobs2: function(){

        var view = this.getObservacionespedidos2();
        var viewIngresar = this.getEditarpedidos();                
        var observa = view.down('#observaId').getValue();
        var numero = viewIngresar.down('#ticketId').getValue();
        var id = viewIngresar.down('#obsId').getValue();       
        
        if (!observa){
             Ext.Msg.alert('Alerta', 'Ingrese Observaciones');
                 return;
        };

        Ext.Ajax.request({
            url: preurl + 'pedidos/saveobserva',
            params: {
                observa : observa,
                numero : numero,
                id: id
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idobserva = resp.idobserva;         
                view.close();
                viewIngresar.down("#obsId").setValue(idobserva);
            }           
        });
    },

    ingresaobs: function(){

        var view = this.getObservacionespedidos();
        var viewIngresar = this.getPedidosingresar();                
        var observa = view.down('#observaId').getValue();
        var numero = viewIngresar.down('#ticketId').getValue();
        var id = viewIngresar.down('#obsId').getValue();       
        
        if (!observa){
             Ext.Msg.alert('Alerta', 'Ingrese Observaciones');
                 return;
        };

        Ext.Ajax.request({
            url: preurl + 'pedidos/saveobserva',
            params: {
                observa : observa,
                numero : numero,
                id: id
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idobserva = resp.idobserva;         
                view.close();
                viewIngresar.down("#obsId").setValue(idobserva);
            }           
        });
    },

    agregarobserva: function(){

        var viewIngresa = this.getPedidosingresar();
        var observa = viewIngresa.down('#obsId').getValue();
        var numpedidos = viewIngresa.down('#ticketId').getValue();
        if (!observa){
            var view = Ext.create('Infosys_web.view.Pedidos.Observaciones').show();
            view.down("#numpedidoId").setValue(numpedidos);
        }else{
            Ext.Ajax.request({
            url: preurl + 'pedidos/getObserva',
            params: {
                idobserva: observa
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true){                
                var observar = (resp.observar);
                var view = Ext.create('Infosys_web.view.Pedidos.Observaciones').show();
                view.down('#observaId').setValue(observar.observaciones);
                };
            }           
            });
        }

    },

    agregarobserva2: function(){

         var viewIngresa = this.getEditarpedidos();
         var observa = viewIngresa.down('#obsId').getValue();
         var numpedidos = viewIngresa.down('#ticketId').getValue();
         if (!observa){
            var view = Ext.create('Infosys_web.view.Pedidos.Observaciones2').show();
            view.down("#numpedidoId").setValue(numpedidos);
         }else{
            Ext.Ajax.request({
            url: preurl + 'pedidos/getObserva',
            params: {
                idobserva: observa
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true){                
                var observar = (resp.observar);
                var view = Ext.create('Infosys_web.view.Pedidos.Observaciones2').show();
                view.down('#observaId').setValue(observar.observaciones);
                view.down('#numpedidoId').setValue(observar.num_pedidos);
                }else{
                  var view = Ext.create('Infosys_web.view.Pedidos.Observaciones2').show();
                  view.down("#numpedidoId").setValue(numpedidos);

                };
            }           
            });
        }
        
    },

    agregarItem: function() {

        var view = this.getPedidosingresar();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getPedidosItemsStore();
        var producto = view.down('#productoId').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var precioun = ((view.down('#precioId').getValue())/ 1.19);
        var descuento = view.down('#totdescuentoId').getValue(); 
        var iddescuento = view.down('#DescuentoproId').getValue();
        var bolEnable = true;
     
        if (descuento > 0){            
            view.down('#tipoDescuentoId').setDisabled(bolEnable);
            view.down('#descuentovalorId').setDisabled(bolEnable);
        };
        
        /*var tot = (Math.round(cantidad * precio) - descuento);
        var neto = (Math.round(tot / 1.19));
        var exists = 0;
        var iva = (tot - neto);
        var total = (neto + iva );*/

        var neto = ((cantidad * precio) - descuento);
        var total  = (Math.round(neto * 1.19));


        //var tot = ((cantidad * precio) - descuento);
        //var neto = (Math.round(tot / 1.19));
        var exists = 0;
        var iva = (total - neto);
        
        if(!producto){            
            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Producto');
            return false;
        }
        if(precio==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
        }
        if(cantidad>cantidadori){
            Ext.Msg.alert('Alerta', 'Cantidad Ingresada de Productos Supera El Stock');
            return false;
        }
        if(cantidad==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Cantidad.');
            return false;
        }        
        if(rut.length==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Datos al Pedido.');
            return false;          
        }

        stItem.each(function(r){
            if(r.data.id == producto){
                Ext.Msg.alert('Alerta', 'El registro ya existe.');
                exists = 1;
                cero="";
                view.down('#codigoId').setValue(cero);
                view.down('#productoId').setValue(cero);
                view.down('#nombreproductoId').setValue(cero);
                view.down('#cantidadId').setValue(cero);
                view.down('#descuentoId').setValue(cero);
                view.down('#precioId').setValue(cero);

                return; 
            }
        });
        if(exists == 1)
            return;
                
        stItem.add(new Infosys_web.model.pedidos.Item({
            id: producto,
            id_producto: producto,
            id_descuento: iddescuento,
            id_bodega: idbodega,
            nom_producto: nombre,
            precio: precio,
            cantidad: cantidad,
            neto: neto,
            total: total,
            iva: iva,
            dcto: descuento
        }));
        this.recalcularFinal();

        cero="";
        cero1=0;
        cero2=0;
        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#nombreproductoId').setValue(cero);
        view.down('#cantidadId').setValue(cero2);
        view.down('#precioId').setValue(cero);
        view.down('#cantidadOriginalId').setValue(cero);
        view.down('#totdescuentoId').setValue(cero1);
        view.down('#DescuentoproId').setValue(cero);
        view.down("#buscarproc").focus();
    },

     buscarsucursalfactura: function(){

       var busca = this.getPedidosingresar()
       var nombre = busca.down('#id_cliente').getValue();
       if (nombre){
         var edit = Ext.create('Infosys_web.view.ventas.BuscarSucursales').show();
          var st = this.getSucursales_clientesStore();
          st.proxy.extraParams = {nombre : nombre};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar ClienteS.');
            return;
       }
      
    },

    seleccionarsucursalclientepedidos: function(){

        var view = this.getBuscarsucursalesclientes();
        var viewIngresa = this.getPedidosingresar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#id_sucursalID').setValue(row.data.id);
            viewIngresa.down('#direccionId').setValue(row.data.direccion);
            viewIngresa.down('#tipoCiudadId').setValue(row.data.nombre_ciudad);
            viewIngresa.down('#tipoComunaId').setValue(row.data.nombre_comuna);
            viewIngresa.down('#preciosId').setValue(row.data.id_lista);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    validarut: function(){

        var view =this.getPedidosingresar();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

        if(numero==0){
            var edit = Ext.create('Infosys_web.view.clientes.BuscarClientes');            
                  
        }else{
       
        if(numero>9){            
            Ext.Msg.alert('Rut Erroneo Ingrese Sin Puntos');
            return;            
        }else{
            if(numero>13){
            Ext.Msg.alert('Rut Erroneo Ingrese Sin Puntos');
            return;   
            }
        }

        Ext.Ajax.request({
            url: preurl + 'clientes/validaRut?valida='+rut,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cero = "";
                if (resp.success == true) {
                    
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down("#id_cliente").setValue(cliente.id)
                        view.down("#nombre_id").setValue(cliente.nombres)
                        view.down("#tipoCiudadId").setValue(cliente.nombre_ciudad)
                        view.down("#tipoComunaId").setValue(cliente.nombre_comuna)
                        view.down("#tipoVendedorId").setValue(cliente.id_vendedor)
                        view.down("#giroId").setValue(cliente.giro)
                        view.down("#preciosId").setValue(cliente.id_lista)
                        view.down("#direccionId").setValue(cliente.direccion)    
                        view.down("#rutId").setValue(rut)
                        view.down("#btnproductoId").focus()  
                                             
                    }else{
                         Ext.Msg.alert('Rut No Exite');
                         view.down("#rutId").setValue(cero); 
                        return;   
                    }
                    
                }else{
                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');
                      view.down("#rutId").setValue(cero);
                      return;
                      
                }

              
            }

        });       
        }
    },

    special6: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut2()
        }
    },

    grabarpedidos2: function(){

        var viewIngresa = this.getEditarpedidos();
        var numeropedido = viewIngresa.down('#ticketId').getValue();
        var idpedido = viewIngresa.down('#idId').getValue();        
        var idtipo = viewIngresa.down('#tipoDocumentoId').getValue();
        var idpago = viewIngresa.down('#tipocondpagoId').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var nomcliente = viewIngresa.down('#nombre_id').getValue();
        var telefono = viewIngresa.down('#TelefonoId').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idsucursal = viewIngresa.down('#id_sucursalID').getValue();      
        var vendedor = viewIngresa.down('#tipoVendedorId');
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        var stCombo = vendedor.getStore();
        var record = stCombo.findRecord('id', vendedor.getValue()).data;
        var finalafectoId = viewIngresa.down('#finaltotalnetoId').getValue();
        var horael = viewIngresa.down('#horaelaId').getValue();
        if(!horael){
            Ext.Msg.alert('Ingrese Hora Elaboracion');
            return;   
        }else{
            var horaela = viewIngresa.down('#horaelaId');
            var stCombo2 = horaela.getStore();
            var record2 = stCombo2.findRecord('id', horaela.getValue()).data;
            var horaelab = record2.nombre;
            var idhoraelab = record2.id;
            
        };
        var vendedor = record.id;
        var fechapedidos = viewIngresa.down('#fechapedidoId').getValue();
        var fechaelaboracion = viewIngresa.down('#fechaelaboraId').getValue();
        var horapedido = viewIngresa.down('#horapedidoId').getValue();
        var fechadespacho = viewIngresa.down('#fechadespachoId').getValue();
        var horadespacho = viewIngresa.down('#horadespachoId').getValue();
        var condpago = viewIngresa.down('#tipocondpagoId').getValue();
        var idobserva = viewIngresa.down('#obsId').getValue();
        var idtipopedido = viewIngresa.down('#tipoPedidoId').getValue();

        if(!idtipopedido){
            Ext.Msg.alert('Ingrese Tipo Pedido');
            return;   
        }

        var stItem = this.getPedidosEditarStore();
        var stpedidos = this.getPedidosStore();

     
        if(vendedor==0  && tipo_documento.getValue() == 1){
            Ext.Msg.alert('Ingrese Datos del Vendedor');
            return;   
        }

        if(finalafectoId==0){
            Ext.Msg.alert('Ingrese Productos al Pedido');
            return;   
        }

        
        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'pedidos/save2',
            params: {
                idcliente: idcliente,
                nomcliente: nomcliente,
                telefono: telefono,
                idpedido: idpedido,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                idtipo : idtipo,
                idpago: idpago,
                idbodega: idbodega,
                idtipopedido: idtipopedido,
                idobserva: idobserva,
                numeropedido : numeropedido,
                fechadocum: Ext.Date.format(fechapedidos,'Y-m-d'),
                fechapedidos: Ext.Date.format(fechapedidos,'Y-m-d'),
                fechaelaboracion: Ext.Date.format(fechaelaboracion,'Y-m-d'),
                horaelab: horaelab,
                idhoraelab: idhoraelab,
                horapedido:  Ext.Date.format(horapedido,'H:i'),
                fechadespacho: Ext.Date.format(fechadespacho,'Y-m-d'),
                horadespacho:  Ext.Date.format(horadespacho,'H:i'),
                descuento : viewIngresa.down('#finaldescuentoId').getValue(),
                neto : viewIngresa.down('#finaltotalnetoId').getValue(),
                iva : viewIngresa.down('#finaltotalivaId').getValue(),
                afecto: viewIngresa.down('#finalafectoId').getValue(),
                total: viewIngresa.down('#finaltotalpostId').getValue()
            },
             success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 var idpedidos= resp.idpedidos;
                 viewIngresa.close();
                 stpedidos.load();
                 window.open(preurl + 'pedidos/exportPDF/?idpedidos='+idpedidos);
               
            }
           
        });
       
    },

    selectItem2: function() {

        var view = this.getEditarpedidos();
        var producto = view.down('#productoId');
        var stCombo = producto.getStore();
        var record = stCombo.findRecord('id', producto.getValue()).data;
        
        view.down('#precioId').setValue(record.p_venta);
        view.down('#codigoId').setValue(record.codigo);
        view.down('#cantidadOriginalId').setValue(record.stock);
          
    },

    agregarItem2: function() {

        var view = this.getEditarpedidos();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getPedidosEditarStore();
        var producto = view.down('#productoId').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var precioun = ((view.down('#precioId').getValue())/ 1.19);
        var descuento = view.down('#totdescuentoId').getValue(); 
        var iddescuento = view.down('#DescuentoproId').getValue();
        var bolEnable = true;
        var cero = " ";
        var cero1= 0;
        var cero2= 1;
        var bolEnable = true;
        
        /*if (descuento == 1){            
            var descuento = 0;
            var iddescuento = 0;
        };*/

        if (descuento > 0){            
            view.down('#tipoDescuentoId').setDisabled(bolEnable);
            view.down('#descuentovalorId').setDisabled(bolEnable);
        };
        
        /*var tot = (Math.round(cantidad * precio) - descuento);
        var neto = (Math.round(tot / 1.19));
        var exists = 0;
        var iva = (tot - neto);
        var total = (neto + iva );*/

        var neto = ((cantidad * precio) - descuento);
        var total  = (Math.round(neto * 1.19));

        var exists = 0;
        var iva = (total - neto);
        
        if(!producto){            
            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Producto');
            return false;
        }
        if(precio==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
        }
        if(cantidad>cantidadori){
            Ext.Msg.alert('Alerta', 'Cantidad Ingresada de Productos Supera El Stock');
            return false;
        }
        if(cantidad==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Cantidad.');
            return false;
        }        
        if(rut.length==0 ){  // se validan los datos sólo si es factura
            Ext.Msg.alert('Alerta', 'Debe Ingresar Datos al Pedido.');
            return false;          
        }

        stItem.each(function(r){
            if(r.data.id == producto){
                Ext.Msg.alert('Alerta', 'El registro ya existe.');
                exists = 1;
                cero="";
                view.down('#codigoId').setValue(cero);
                view.down('#productoId').setValue(cero);
                view.down('#nombreproductoId').setValue(cero);
                view.down('#cantidadId').setValue(cero);
                view.down('#descuentoId').setValue(cero);
                view.down('#precioId').setValue(cero);

                return; 
            }
        });
        if(exists == 1)
            return;
                
        stItem.add(new Infosys_web.model.pedidos.Item({
            id: producto,
            id_producto: producto,
            id_descuento: iddescuento,
            id_bodega: idbodega,
            nom_producto: nombre,
            precio: precio,
            cantidad: cantidad,
            neto: neto,
            total: total,
            iva: iva,
            descuento: descuento
        }));
        this.recalcularFinal2();

        cero="";
        cero1=0;
        cero2=0;
        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#nombreproductoId').setValue(cero);
        view.down('#cantidadId').setValue(cero2);
        view.down('#precioId').setValue(cero);
        view.down('#cantidadOriginalId').setValue(cero);
        view.down('#totdescuentoId').setValue(cero1);
        view.down('#DescuentoproId').setValue(cero);
        view.down("#buscarproc").focus();
    },

    editarpedidos: function(){

        var stItms = Ext.getStore('Pedidos.Items');
        stItms.removeAll();
       
        var view = this.getPedidosprincipal();       
                   
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var view = this.getEditarpedidos();
            var stItem = this.getPedidosEditarStore();
            var idpedidos = row.data.id;
            var id_bodega = row.data.id_bodega;
            stItem.proxy.extraParams = {idpedidos : idpedidos};
            stItem.load();
            
            Ext.Ajax.request({
            url: preurl +'pedidos/edita/?idpedidos=' + row.data.id,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {
                    
                    var view = Ext.create('Infosys_web.view.Pedidos.Editarpedidos').show();                   
                    var cliente = resp.cliente;                   
                    view.down("#ticketId").setValue(cliente.num_pedido);
                    view.down("#obsId").setValue(cliente.num_pedido);
                    view.down("#idId").setValue(cliente.id);
                    view.down("#tipoDocumentoId").setValue(cliente.tip_documento);
                    view.down("#tipoPedidoId").setValue(cliente.id_tipopedido);
                    view.down("#fechapedidoId").setValue(cliente.fecha_doc);
                    view.down("#horapedidoId").setValue(cliente.hora_pedido);                    
                    view.down("#fechadespachoId").setValue(cliente.fecha_doc);
                    view.down("#horadespachoId").setValue(cliente.hora_despacho);                                      
                    view.down("#rutId").setValue(cliente.rut_cliente);                                       
                    view.down("#id_cliente").setValue(cliente.id_cliente);
                    view.down("#tipocondpagoId").setValue(cliente.id_pago);                    
                    view.down("#nombre_id").setValue(cliente.nombre_cliente);
                    view.down("#TelefonoId").setValue(cliente.telefono);
                    view.down("#preciosId").setValue(cliente.id_lista);
                    view.down("#tipoVendedorId").setValue(cliente.id_vendedor);
                    view.down("#bodegaId").setValue(id_bodega);
                    if(cliente.id_sucursal!=0){
                        view.down("#direccionId").setValue(cliente.direccion_sucursal);
                        view.down("#id_sucursalID").setValue(cliente.id_sucursal);                                              
                    }else{
                        view.down("#direccionId").setValue(cliente.direccion);
                    };
                    var total = (cliente.total);
                    var neto = (cliente.neto);
                    var iva = (cliente.total - cliente.neto);
                    view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
                    view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
                    view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
                    view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
                    view.down('#finalafectoId').setValue(Ext.util.Format.number(neto, '0'));
                 
                }else{
                    Ext.Msg.alert('Correlativo no Existe');
                    return;
                }

            }
            
        });

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
        
       
       
    },

    eliminaritem2: function() {
        var view = this.getEditarpedidos();
        var total = view.down('#finaltotalpostId').getValue();
        var neto = view.down('#finaltotalnetoId').getValue();
        var iva = view.down('#finaltotalivaId').getValue();
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];         
            var total = (parseInt(total) - parseInt(row.data.total));
            var neto = (parseInt(neto) - parseInt(row.data.neto));
            var iva = (parseInt(iva) - parseInt(row.data.iva));
            var afecto = neto;
            view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
            view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
            view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
            view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
            view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));

            grid.getStore().remove(row);

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

       
       
    },


    eliminaritem: function() {
        var view = this.getPedidosingresar();
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            grid.getStore().remove(row);
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

        this.recalcularFinal();
       
    },

    editaritem: function() {
        var view = this.getPedidosingresar();
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_producto = row.data.id_producto;
            Ext.Ajax.request({
            url: preurl + 'productos/buscarp?nombre='+id_producto,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) { 
                    console.log("LLegamos")                   
                    if(resp.cliente){
                        console.log("LLegamos 2")  
                        var cliente = resp.cliente;
                        view.down('#precioId').setValue(cliente.p_venta);
                        view.down('#productoId').setValue(row.data.id_producto);
                        view.down('#codigoId').setValue(cliente.codigo);
                        view.down('#cantidadOriginalId').setValue(cliente.stock);
                        view.down('#cantidadId').setValue(row.data.cantidad);           
                    }
                    
                }
            }

        });
        grid.getStore().remove(row);
        this.recalcularFinal();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    editaritem2: function() {

        var view = this.getEditarpedidos();
        var total = view.down('#finaltotalpostId').getValue();
        var neto = view.down('#finaltotalnetoId').getValue();
        var afecto = view.down('#finalafectoId').getValue();
        var iva = view.down('#finaltotalivaId').getValue();
        var grid  = view.down('#itemsgridId');
        var cero = "";
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_producto = row.data.id_producto;
            var nom_producto = row.data.nom_producto;
            var totalnue = total - (row.data.total);
            var ivanue = iva - (row.data.iva);
            var afectonue = afecto - (row.data.neto);
            var netonue = neto - (row.data.neto);

            Ext.Ajax.request({
            url: preurl + 'productos/buscarp?nombre='+id_producto,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) { 
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down('#precioId').setValue(cliente.p_venta);
                        view.down('#productoId').setValue(row.data.id_producto);
                        view.down('#nombreproductoId').setValue(row.data.nom_producto);
                        view.down('#codigoId').setValue(cliente.codigo);
                        view.down('#cantidadOriginalId').setValue(cliente.stock);
                        view.down('#cantidadId').setValue(row.data.cantidad);
                        view.down('#totdescuentoId').setValue(row.data.dcto);
                        if ((row.data.id_descuento)==0){
                            view.down('#DescuentoproId').setValue(cero);
                        }else{
                            view.down('#DescuentoproId').setValue(row.data.id_descuento);
                        }
                        
                        view.down('#finaltotalId').setValue(Ext.util.Format.number(totalnue, '0,000'));
                        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalnue, '0'));
                        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netonue, '0'));
                        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivanue, '0'));
                        view.down('#finalafectoId').setValue(Ext.util.Format.number(afectonue, '0'));
                        view.down('#descuentovalorId').setValue(Ext.util.Format.number(cero));
       
                    }
                }
            }

        });
        grid.getStore().remove(row);
        //this.recalcularFinal();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    exportarpedidos: function(){
        var view = this.getPedidosprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            window.open(preurl +'pedidos/exportPDF/?idpedidos=' + row.data.id)
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    validaboleta: function(){

        var view =this.getPedidosingresar();
        var rut = view.down('#rutId').getValue();
        
        Ext.Ajax.request({
            url: preurl + 'clientes/validaRut?valida='+rut,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cero = "";
                if (resp.success == true) {
                    
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down("#id_cliente").setValue(cliente.id)
                        view.down("#nombre_id").setValue(cliente.nombres)
                        view.down("#rutId").setValue(rut)                       
                    }
                    
                }
            }

        });       
       
    },

    selectItemdocuemento: function() {
        
        var view =this.getPedidosingresar();
        var tipo_documento = view.down('#tipoDocumento2Id');
        var bolDisabled = tipo_documento.getValue() == 2 ? true : false; // campos se habilitan sólo en factura
        
        if(bolDisabled == true){  // limpiar campos
           view.down('#rutId').setValue('19');
           this.validaboleta();
           
        }

        view.down('#rutId').setDisabled(bolDisabled);
        view.down('#buscarBtn').setDisabled(bolDisabled);
        view.down('#nombre_id').setDisabled(bolDisabled);
        view.down("#rutId").focus();

    },

    changedctofinal: function(){
        this.recalcularFinal();
    },

    recalcular: function(){

        var view = this.getEditarpedidos();
        var stItem = this.getPedidosEditarStore();
        var grid2 = view.down('#itemsgridId');
        var pretotal = 0;
        var total = 0;
        var iva = 0;
        var neto = 0;
        var dcto = view.down('#finaldescuentoId').getValue();

        stItem.each(function(r){
            pretotal = ((pretotal) + (r.data.total))
            //iva = (parseInt(iva) + parseInt(r.data.iva))
            //neto = (parseInt(neto) + parseInt(r.data.neto))
        });

        neto = ((pretotal /1.19));
        iva = ((pretotal - neto));
        afecto = neto;
        neto = neto;
        pretotalfinal = pretotal;

        view.down('#finaltotalId').setValue(Ext.util.Format.number(pretotalfinal, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(pretotalfinal, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
        view.down('#finalpretotalId').setValue(Ext.util.Format.number(pretotalfinal, '0,000'));
    },

    
    recalcularFinal: function(){

        var view = this.getPedidosingresar();
        var stItem = this.getPedidosItemsStore();
        var grid2 = view.down('#itemsgridId');
        var pretotal = 0;
        var total = 0;
        var iva = 0;
        var neto = 0;
        var dcto = view.down('#finaldescuentoId').getValue();

        stItem.each(function(r){
            pretotal = pretotal + r.data.total
            iva = iva + r.data.iva
            neto = neto + r.data.neto
        });
        pretotalfinal = ((pretotal * dcto)  / 100);
        total = ((pretotal) - parseInt(pretotalfinal));
        afecto = neto;
        
        //iva = (total - afecto);
        view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
        //view.down('#finalpretotalId').setValue(Ext.util.Format.number(pretotal, '0,000'));
    },

    recalcularFinal2: function(){

        var view = this.getEditarpedidos();
        var stItem = this.getPedidosEditarStore();
        var grid2 = view.down('#itemsgridId');
        var pretotal = 0;
        var total = 0;
        var iva = 0;
        var neto = 0;
        var dcto = view.down('#finaldescuentoId').getValue();

        stItem.each(function(r){
            pretotal = (pretotal + (parseInt(r.data.total)));
            iva = (iva + (parseInt(r.data.iva)));
            neto = (neto + (parseInt(r.data.neto)));
        });

        /*neto = (pretotal / 1.19);
        iva = (pretotal - neto);*/
        afecto = neto;
        neto = neto;
        pretotalfinal = pretotal;
        
        view.down('#finaltotalId').setValue(Ext.util.Format.number(pretotalfinal, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(pretotalfinal, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
        view.down('#descuentovalorId').setValue(Ext.util.Format.number(pretotalfinal, '0'));
    },



    

    selectItem: function() {

        var view = this.getPedidosingresar();
        var producto = view.down('#productoId');
        var stCombo = producto.getStore();
        var record = stCombo.findRecord('id', producto.getValue()).data;
        
        view.down('#precioId').setValue(record.p_venta);
        view.down('#codigoId').setValue(record.codigo);
        view.down('#cantidadOriginalId').setValue(record.stock);
          
    },

    buscar: function(){

        var view = this.getBuscarclientespedidos();
        var st = this.getClientesStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
        st.load();
    },

    seleccionarproductos2: function(){

        var view = this.getBuscarproductospedidos2();
        var viewIngresa = this.getEditarpedidos();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id_producto);
            viewIngresa.down('#nombreproductoId').setValue(row.data.nombre);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);
            viewIngresa.down('#precioId').setValue(row.data.p_venta);
            viewIngresa.down('#cantidadOriginalId').setValue(row.data.stock);
            viewIngresa.down("#cantidadId").focus();
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    buscarproductos: function(){
          
        var viewIngresa = this.getPedidosingresar();
        var codigo = viewIngresa.down('#codigoId').getValue()
        if (!codigo){
            var st = this.getProductosfStore();
            Ext.create('Infosys_web.view.Pedidos.BuscarProductos').show();
            st.load();
        }else{

            Ext.Ajax.request({
            url: preurl + 'productos/buscacodigo?codigo='+codigo,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cero = "";
                if (resp.success == true){                    
                    if(resp.cliente){
                        var cliente = resp.cliente;                        
                        viewIngresa.down('#productoId').setValue(cliente.id);
                        viewIngresa.down('#nombreproductoId').setValue(cliente.nombre);
                        viewIngresa.down('#codigoId').setValue(cliente.codigo);
                        viewIngresa.down('#precioId').setValue(cliente.p_venta);
                        viewIngresa.down('#cantidadOriginalId').setValue(cliente.stock);
                        viewIngresa.down("#precioId").focus();
                                             
                    }
                }else{
                      var view = Ext.create('Infosys_web.view.productos.Ingresar').show();
                      view.down("#codigoId").setValue(codigo);
                      
                }

              
            }

        });           

        }
        
    },

    buscarproductos2: function(){

        var viewIngresa = this.getEditarpedidos();
        var codigo = viewIngresa.down('#codigoId').getValue()
        var lista = viewIngresa.down('#preciosId').getValue()
           
        if (!codigo){
            var st = this.getProductoslistaStore()
            st.proxy.extraParams = {idlista: lista}
            st.load();
            var view = Ext.create('Infosys_web.view.Pedidos.BuscarProductos2').show();
            view.down('#listaId').setValue(lista);
        }else{

            Ext.Ajax.request({
            url: preurl + 'productosfact/buscacodigo',
            params: {
                id: 1,
                codigo : codigo,
                idlista : lista
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cero = "";
                if (resp.success == true){                    
                    if(resp.cliente){
                        var cliente = resp.cliente;                        
                        viewIngresa.down('#productoId').setValue(cliente.id);
                        viewIngresa.down('#nombreproductoId').setValue(cliente.nombre);
                        viewIngresa.down('#codigoId').setValue(cliente.codigo);
                        viewIngresa.down('#precioId').setValue(cliente.valor_lista);
                        viewIngresa.down('#cantidadOriginalId').setValue(cliente.stock);
                        viewIngresa.down("#cantidadId").focus();                                             
                    }                    
                }else{
                       Ext.Msg.alert('Alerta', 'Producto no existe');
                        return;                   
                }              
            }

        });
        }
    },

    seleccionarproductos: function(){

        var view = this.getBuscarproductospedidos();
        var viewIngresa = this.getPedidosingresar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id);
            viewIngresa.down('#nombreproductoId').setValue(row.data.nombre);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);
            viewIngresa.down('#precioId').setValue(row.data.p_venta);
            viewIngresa.down('#cantidadOriginalId').setValue(row.data.stock);
            viewIngresa.down("#cantidadId").focus();
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    buscarp: function(){
        var view = this.getBuscarproductospedidos();
        var st = this.getProductosfStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre}
        st.load();
    },

    buscarp2: function(){
        var view = this.getBuscarproductospedidos2();
        var st = this.getProductosfStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre}
        st.load();
    },

    seleccionarcliente: function(){

        var view = this.getBuscarclientespedidos();
        var viewIngresa = this.getPedidosingresar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#id_cliente').setValue(row.data.id);
            viewIngresa.down('#nombre_id').setValue(row.data.nombres);
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            viewIngresa.down('#direccionId').setValue(row.data.direccion);
            viewIngresa.down('#rutId').setValue(row.data.rut);
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            viewIngresa.down('#tipocondpagoId').setValue(row.data.id_pago);
            viewIngresa.down('#TelefonoId').setValue(row.data.fono);
            view.close();
            var bolEnable = false;
            if(row.data.id_pago == 1){
                viewIngresa.down('#DescuentoproId').setDisabled(bolEnable);
                viewIngresa.down('#tipoDescuentoId').setDisabled(bolEnable);
                viewIngresa.down('#descuentovalorId').setDisabled(bolEnable);                
            };
            if(row.data.id_pago == 6){

                 viewIngresa.down('#DescuentoproId').setDisabled(bolEnable);
                 viewIngresa.down('#tipoDescuentoId').setDisabled(bolEnable);
                 viewIngresa.down('#descuentovalorId').setDisabled(bolEnable);
                
            };
            if (row.data.id_pago == 7){

                 view.down('#DescuentoproId').setDisabled(bolEnable);
                 view.down('#tipoDescuentoId').setDisabled(bolEnable);
                 view.down('#descuentovalorId').setDisabled(bolEnable);
                
            };          
            

           
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    validarut: function(){

        var view = this.getPedidosingresar();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

        if(numero==0){
            var edit = Ext.create('Infosys_web.view.Pedidos.BuscarClientes');            
                  
        }else{
       
        if(numero>9){            
            Ext.Msg.alert('Rut Erroneo Ingrese Sin Puntos');
            return;            
        }else{
            if(numero>13){
            Ext.Msg.alert('Rut Erroneo Ingrese Sin Puntos');
            return;   
            }
        }

        Ext.Ajax.request({
            url: preurl + 'clientes/validaRut?valida='+rut,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var cero = "";
                if (resp.success == true) {
                    
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down("#id_cliente").setValue(cliente.id)
                        view.down("#nombre_id").setValue(cliente.nombres)                        
                        view.down("#rutId").setValue(rut)
                        view.down('#tipoVendedorId').setValue(cliente.id_vendedor);
                        view.down('#direccionId').setValue(cliente.direccion);
                        view.down('#tipocondpagoId').setValue(cliente.id_pago);
                        view.down('#TelefonoId').setValue(cliente.fono);                      
                    }else{
                         Ext.Msg.alert('Rut No Exite');
                         view.down("#rutId").setValue(cero); 
                        return;   
                    }
                    
                }else{
                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');
                      view.down("#rutId").setValue(cero);
                      return;
                      
                }
            }

        });       
        }
    },

    mPedidos: function(){       
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'pedidosprincipal'});
    },

    
    buscarpedidos: function(){
        
        var view = this.getPedidosprincipal();
        var st = this.getPedidosStore();
        var nombre = view.down('#nombreId').getValue();
        var estado = view.down('#tipoSeleccion2Id').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                estado : estado}
        st.load();


    },

    buscarpedidos2: function(){
        
        var view = this.getPedidosprincipal();
        var st = this.getPedidosStore();
        var cero="";
        var nombre = view.down('#nombreId').getValue();
        if (nombre){
            view.down("#nombreId").setValue(cero);            
        };
        var estado = view.down('#Seleccion2Id').getValue();
        var opcion = view.down('#tipoSeleccionId').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                estado : estado,
                                opcion : opcion}
        st.load();


    },

    grabarpedidos: function(){

        var viewIngresa = this.getPedidosingresar();
        var numeropedido = viewIngresa.down('#ticketId').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var nomcliente = viewIngresa.down('#nombre_id').getValue();
        var telefono = viewIngresa.down('#TelefonoId').getValue();
        var idsucursal = viewIngresa.down('#id_sucursalID').getValue();      
        var vendedor = viewIngresa.down('#tipoVendedorId');
        var fechapedidos = viewIngresa.down('#fechapedidoId').getValue();
        var fechadocum = viewIngresa.down('#fechadocumId').getValue();
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        var idpago = viewIngresa.down('#tipocondpagoId').getValue();        
        var stCombo = vendedor.getStore();
        var record = stCombo.findRecord('id', vendedor.getValue()).data;
        var finalafectoId = viewIngresa.down('#finaltotalnetoId').getValue();
        var vendedor = record.id;
        var condpago = viewIngresa.down('#tipocondpagoId').getValue();
        var idobserva = viewIngresa.down('#obsId').getValue();
        var stItem = this.getPedidosItemsStore();
        var stpedidos = this.getPedidosStore();

        console.log(fechadocum)
        
     
        if(vendedor==0){
            Ext.Msg.alert('Ingrese Datos del Vendedor');
            return;   
        }

        if(finalafectoId==0){
            Ext.Msg.alert('Ingrese Productos al Pedido');
            return;   
        }

        
        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'pedidos/save',
            params: {
                idcliente: idcliente,
                nomcliente: nomcliente,
                telefono: telefono,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                idpago: idpago,
                idobserva: idobserva,
                idbodega: idbodega,
                numeropedido : numeropedido,
                fechadocum: Ext.Date.format(fechadocum,'Y-m-d'),
                fechapedido: Ext.Date.format(fechapedidos,'Y-m-d'),
                descuento : viewIngresa.down('#finaldescuentoId').getValue(),
                neto : viewIngresa.down('#finaltotalnetoId').getValue(),
                iva : viewIngresa.down('#finaltotalivaId').getValue(),
                afecto: viewIngresa.down('#finalafectoId').getValue(),
                total: viewIngresa.down('#finaltotalpostId').getValue()
            },
             success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 var idpedidos= resp.idpedidos;
                 viewIngresa.close();
                 stpedidos.load();
                 window.open(preurl + 'pedidos/exportPDF/?idpedidos='+idpedidos);
               
            }
           
        });
       
    },        
    
    agregarpedido: function(){

         var viewIngresa = this.getPedidosprincipal();
         var idbodega = viewIngresa.down('#bodegaId').getValue();
         
         if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;    
         }else{
         var nombre = "20";    
         Ext.Ajax.request({

            url: preurl + 'correlativos/genera?valida='+nombre,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {
                    var view = Ext.create('Infosys_web.view.Pedidos.Pedidos').show();                   
                    var cliente = resp.cliente;
                    var correlanue = cliente.correlativo;
                    correlanue = (parseInt(correlanue)+1);
                    var correlanue = correlanue;
                    view.down("#ticketId").setValue(correlanue);
                    view.down("#bodegaId").setValue(idbodega);
                }else{
                    Ext.Msg.alert('Correlativo YA Existe');
                    return;
                }
            }            
        });
        
        };        
       
    },

    cerrarpedidos: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
     
    },
  
});










