Ext.define('Infosys_web.controller.Facturacion', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['productos.Items',
             'facturas.Items',
             'Factura',
             'Clientes',
             'Productosf',
             'Tipo_documento',
             'Sucursales_clientes',
             'Tipo_documento.Selector',
             'facturas.Selector',
             'facturas.Selector2'],

    models: ['Facturas.Item',
             'Factura',
             'Tipo_documento',
             'Sucursales_clientes'],

    views: ['ventas.Ventas', 'ventas.Ejemplo','ventas.Facturas',
             'clientes.BuscarClientes','productos.BuscarProductos',
             'ventas.Principalfactura',
             'ventas.BuscarSucursales',
             'ventas.ResumenVentas',
             'ventas.EstadisticasVentas',
             'ventas.InformeStock',
             'ventas.VerDetalleProductoStock',  
             'facturaelectronica.RegistroEmpresa',                        
             'ventas.Exportar',
             'ventas.Observaciones',
             'ventas.Facturaseditar'],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
       ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'facturasingresar',
        selector: 'facturasingresar'
    },{
        ref: 'ventasejemplo',
        selector: 'ventasejemplo'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{
        ref: 'facturasprincipal',
        selector: 'facturasprincipal'
    },{
        ref: 'buscarclientes',
        selector: 'buscarclientes'
    },{
        ref: 'buscarproductos',
        selector: 'buscarproductos'
    },{
        ref: 'buscarsucursalesclientes',
        selector: 'buscarsucursalesclientes'
    },{
        ref: 'formularioexportar',
        selector: 'formularioexportar'
    },{
        ref: 'formularioexportarpdf',
        selector: 'formularioexportarpdf'
    },{
        ref: 'observacionesfacturasdirectas',
        selector: 'observacionesfacturasdirectas'
    },{
        ref: 'facturaseditar',
        selector: 'facturaseditar'
    }
    
    ],
    //init es lo primero que se ejecuta en el controller
    //especia de constructor
    init: function() {
    	//el <<control>> es el puente entre la vista y funciones internas
    	//del controller
        this.control({

            'facturasingresar #rutId': {
                specialkey: this.special
            },

            'facturasprincipal button[action=mfactura]': {
                click: this.mfactura
            },
           
            'topmenus menuitem[action=mejemplo]': {
                click: this.mejemplo
            },

            'topmenus menuitem[action=resumenventas]': {
                click: this.resumenventas
            },

            'topmenus menuitem[action=estadisticasventas]': {
                click: this.estadisticasventas
            },

            'topmenus menuitem[action=informestock]': {
                click: this.informestock
            },

            'topmenus menuitem[action=mregempresa]': {
                click: this.mregempresa
            }, 


            'resumenventas button[action=cerrarfactura]': {
                click: this.cerrarfactura
            },

            'informestock button[action=cerrarfactura]': {
                click: this.cerrarfactura
            },


            'estadisticasventas button[action=cerrarfactura]': {
                click: this.cerrarfactura
            },


            'informestock': {
                verDetalleProductoStock: this.verDetalleProductoStock
            },               


            'facturasingresar button[action=buscarclientes]': {
                click: this.buscarclientes
            },
            'facturasingresar button[action=buscarsucursalfactura]': {
                click: this.buscarsucursalfactura
            },
            'facturasingresar button[action=buscarvendedor]': {
                click: this.buscarvendedor
            },
            'facturasingresar button[action=buscarproductos]': {
                click: this.buscarproductos
            },
            'facturasingresar button[action=validarut]': {
                click: this.validarut
            },
            'facturasingresar button[action=grabarfactura]': {
                click: this.grabarfactura
            },
            'facturasprincipal button[action=cerrarfactura]': {
                click: this.cerrarfactura
            },
            'facturasprincipal button[action=generarfacturapdf]': {
                click: this.generarfacturapdf
            },
            'buscarclientes button[action=buscar]': {
                click: this.buscar
            },
            'buscarclientes button[action=seleccionarcliente]': {
                click: this.seleccionarcliente
            },
            'buscarproductos button[action=seleccionarproductos]': {
                click: this.seleccionarproductos
            },
            
            'buscarproductos button[action=buscar]': {
                click: this.buscarp
            },
            'facturasingresar #tipoDocumentoId': {
                select: this.selectItemdocuemento
            },
            'buscarsucursalesclientes button[action=seleccionarsucursalcliente]': {
                click: this.seleccionarsucursalcliente
            },
            'facturasingresar #tipocondpagoId': {
                select: this.selecttipocondpago
            },
            'facturasingresar #fechafacturaId': {
                select: this.selecttipocondpago
            },            
            'facturasprincipal button[action=exportarexcelfacturas]': {
                click: this.exportarexcelfacturas
            },
            'facturasprincipal button[action=generarlibropdf]': {
                click: this.generarlibropdf
            },            
            'formularioexportar button[action=exportarExcelFormulario]': {
                click: this.exportarExcelFormulario
            },
            'formularioexportarpdf button[action=exportarPdfFormulario]': {
                click: this.exportarPdfFormulario
            },            
            'facturasprincipal button[action=buscarfact]': {
                click: this.buscarfact
            },
            'facturasingresar button[action=observaciones]': {
                click: this.observaciones
            },
            'observacionesfacturasdirectas button[action=ingresaobs]': {
                click: this.ingresaobs
            },
             'observacionesfacturasdirectas #rutId': {
                specialkey: this.special6
            },
            'facturasingresar #DescuentoproId': {
                change: this.changedctofinal3
            },
            'facturasingresar #tipoDescuentoId': {
                change: this.changedctofinal
            },
            'facturasprincipal #tipoDocumentoId': {
                select: this.buscarDoc
            },
             'facturasingresar #netoId': {
                specialkey: this.calculaiva
            },
            'facturasprincipal button[action=editafactura]': {
                click: this.editafactura
            },
            'facturaseditar button[action=grabarfacturaeditar]': {
                click: this.grabarfacturaeditar
            },
            'facturasingresar #codigoId': {
                specialkey: this.buscarproductos
            }

        });
    },

    editaritem: function() {

        var view = this.getFacturasingresar();
        var grid  = view.down('#itemsgridId');
        var cero = "";
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
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down('#precioId').setValue(cliente.p_venta);
                        view.down('#productoId').setValue(row.data.id_producto);
                        view.down('#nombreproductoId').setValue(row.data.nombre);
                        view.down('#codigoId').setValue(cliente.codigo);
                        view.down('#cantidadOriginalId').setValue(cliente.stock);
                        view.down('#cantidadId').setValue(row.data.cantidad);
                        view.down('#totdescuentoId').setValue(row.data.dcto);
                        if ((row.data.id_descuento)==0){
                            view.down('#DescuentoproId').setValue(cero);
                        }else{
                            view.down('#DescuentoproId').setValue(row.data.id_descuento);
                        }       
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

    grabarfacturaeditar: function() {

        var viewIngresa = this.getFacturaseditar();
        var tipo_documento = viewIngresa.down('#tipoDocumentoId');
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idtipo= viewIngresa.down('#tipoDocumentoId').getValue();
        var idsucursal= viewIngresa.down('#id_sucursalID').getValue();
        var idcondventa= viewIngresa.down('#tipocondpagoId').getValue();
        var idfactura = viewIngresa.down('#idfactura').getValue();        
        var observa = viewIngresa.down('#observaId').getValue();
        var idobserva = viewIngresa.down('#obsId').getValue();
        var numfactura = viewIngresa.down('#numfacturaId').getValue();
        var sucursal = viewIngresa.down('#id_sucursalID').getValue();
        var formadepago = viewIngresa.down('#tipocondpagoId').getValue();
        var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
        var fechavenc = viewIngresa.down('#fechavencId').getValue();
        var neto = viewIngresa.down('#netoId').getValue();
        var iva = viewIngresa.down('#ivaId').getValue();
        var total = viewIngresa.down('#totalId').getValue();
        var totalant = viewIngresa.down('#totalantId').getValue();
        var stFactura = this.getFacturaStore();

        
        if(numfactura==0){
            Ext.Msg.alert('Ingrese Datos a La Factura');
            return;   
            }

        Ext.Ajax.request({
            url: preurl + 'facturas/update',
            params: {
                idfactura: idfactura,
                numfactura: numfactura,
                idcliente: idcliente,
                netofactura: neto,
                ivafactura: iva,
                afectofactura: neto,
                totalfacturas: total,
                totalant: totalant
            },
             success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idfactura= resp.idfactura;
                 viewIngresa.close();
                 stFactura.load();                          }
           
        });

        var view = this.getFacturasprincipal();
        var st = this.getFacturaStore();
        st.proxy.extraParams = {documento: idtipo}
        st.load(); 
      
        
    },

    editafactura : function(){

        var view = this.getFacturasprincipal();
        if (view.getSelectionModel().hasSelection()) {

            var row = view.getSelectionModel().getSelection()[0];
            var idcliente = row.data.id_cliente;
            var neto = row.data.sub_total;
            var iva = row.data.iva;
            var total = row.data.totalfactura;
            var idfactura = row.data.id;
            var numfactura = row.data.num_factura;
            var fechavenc = row.data.fecha_venc;
            var fechafact = row.data.fecha_factura;

            var tipo = "1";            
                        
            Ext.Ajax.request({
            url: preurl + 'clientes/getAllc?idcliente='+idcliente,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {
                    
                    if(resp.cliente){
                        var view = Ext.create('Infosys_web.view.ventas.Facturaseditar').show();
                        var cliente = resp.cliente;
                        view.down("#id_cliente").setValue(cliente.id)
                        view.down("#nombre_id").setValue(cliente.nombres)
                        view.down("#tipoCiudadId").setValue(cliente.nombre_ciudad)
                        view.down("#tipoComunaId").setValue(cliente.nombre_comuna)
                        view.down("#tipoVendedorId").setValue(cliente.id_vendedor)
                        view.down("#giroId").setValue(cliente.giro)
                        view.down("#direccionId").setValue(cliente.direccion)
                        view.down("#tipocondpagoId").setValue(cliente.id_pago)
                        view.down("#netoId").setValue(neto)
                        view.down("#ivaId").setValue(iva)
                        view.down("#totalId").setValue(total)
                        view.down("#totalantId").setValue(total)                        
                        view.down("#rutId").setValue(cliente.rut) 
                        view.down("#idfactura").setValue(idfactura)
                        view.down("#numfacturaId").setValue(numfactura)
                        view.down("#tipoDocumentoId").setValue(tipo)
                        view.down("#fechavencId").setValue(fechavenc)
                        view.down("#fechafacturaId").setValue(fechafact)
                        view.down("#netoId").focus();                                             
                    }
                    
                }
            }

        });   
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

    },

    calculaiva: function(){

        var view = this.getFacturasingresar();
        var tipo_documento = view.down('#tipoDocumentoId').getValue();
        if (tipo_documento == 18 || tipo_documento == 103  ){
            var iva = 0;
            var neto = view.down('#netoId').getValue();
            view.down('#totalId').setValue(neto);
            view.down('#ivaId').setValue(iva);
        }else{
        var neto = view.down('#netoId').getValue();
        var iva = ((neto * 19) / 100);
        var total = (neto + iva);
        view.down('#ivaId').setValue(iva);
        view.down('#totalId').setValue(total);
        };
    },



    buscarDoc: function(){
        
        var view = this.getFacturasprincipal();
        var st = this.getFacturaStore();
        var opcion = view.down('#tipoSeleccionId').getValue();
        var documento = view.down('#tipoDocumentoId').getValue();
        var nombre = view.down('#nombreId').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion,
                                documento: documento}
        st.load();
    },

    changedctofinal: function(){
        this.recalculardescuento();
    },

    recalculardescuento: function(){

        var view = this.getFacturasingresar();
        var pretotal = view.down('#finalafectoId').getValue();
        var total = view.down('#finaltotalpostId').getValue();
        var iva = view.down('#finaltotalivaId').getValue();
        var neto = view.down('#finaltotalnetoId').getValue();
        var descuento = view.down('#tipoDescuentoId');
        var stCombo = descuento.getStore();
        var record = stCombo.findRecord('id', descuento.getValue()).data;
        var dcto = (record.porcentaje);
       
        pretotalfinal = ((total * dcto)  / 100);
        total = ((total) - (pretotalfinal));
        afecto = ((total / 1.19));
        iva = (total - afecto);

        view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(iva, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
        view.down('#descuentovalorId').setValue(Ext.util.Format.number(pretotalfinal, '0'));
    },

     changedctofinal3: function(){
        this.recalculardescuentopro();
    },

    recalculardescuentopro: function(){

        var view = this.getFacturasingresar();
        var precio = view.down('#precioId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var total = ((precio * cantidad));
        var desc = view.down('#DescuentoproId').getValue();
        if (desc){
        var descuento = view.down('#DescuentoproId');
        var stCombo = descuento.getStore();
        var record = stCombo.findRecord('id', descuento.getValue()).data;
        var dcto = (record.porcentaje);
        totaldescuento = (((total * dcto)  / 100));
        view.down('#totdescuentoId').setValue(totaldescuento);
        };         
    },

    condicionpago: function(){

        var viewIngresa = this.getFacturasingresar();
        
    },


    mregempresa: function(){

        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'registroempresa'});
        
    },

    
    special6: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut2()
        }
    },

    validarut2: function(){

        var view = this.getObservacionesfacturasdirectas();
        var rut = view.down('#rutId').getValue();
        var okey = "SI";
        var cero = " ";
        
        if (!rut){
             Ext.Msg.alert('Alerta', 'Debe Ingresar Rut');
                 return;
        };

        Ext.Ajax.request({
            url: preurl + 'facturasvizualiza/validaRut?valida='+rut,
            params: {
                id: 1
            },
            
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {
                    var rutm = resp.rut;
                    if (resp.existe == true){
                        var observa = resp.observa;
                        if (observa){
                         view.down("#nombreId").setValue(observa.nombre);
                         view.down("#rutId").setValue(observa.rut);
                         view.down("#rutmId").setValue(rut);
                         view.down("#camionId").setValue(observa.pat_camion);
                         view.down("#carroId").setValue(observa.pat_carro);
                         view.down("#fonoId").setValue(observa.fono);
                         view.down("#validaId").setValue(okey);
                         view.down("#observaId").focus();
                    }             
                    };
                    if (resp.existe == false){
                        view.down("#nombreId").focus();
                         view.down("#rutId").setValue(rutm);
                         view.down("#rutmId").setValue(rut);
                         view.down("#validaId").setValue(okey);
                    }  
                    
                }else{

                      Ext.Msg.alert('Informacion', 'Rut Incorrecto');                      
                      return false;
                     
                      
                }
               
            }

        });
    },

    ingresaobs: function(){

        var view = this.getObservacionesfacturasdirectas();
        var viewIngresar = this.getFacturasingresar();                
        var rut = view.down('#rutmId').getValue();
        var nombre = view.down('#nombreId').getValue();
        var camion = view.down('#camionId').getValue();
        var fono = view.down('#fonoId').getValue();
        var carro = view.down('#carroId').getValue();
        var observa = view.down('#observaId').getValue();
        var valida = view.down('#validaId').getValue();
        var numero = view.down('#FactId').getValue();      
        
        var permite = "SI"

        if (valida == "NO"){
             Ext.Msg.alert('Alerta', 'Debe Validar Rut');
                 return;
        };        
        
        if (!rut){
             Ext.Msg.alert('Alerta', 'Debe Ingresar Rut');
                 return;
        };
        if (!nombre){
             Ext.Msg.alert('Alerta', 'Debe Ingresar Nombre');
                 return;
        };       
       
        Ext.Ajax.request({
            url: preurl + 'facturasvizualiza/saveobserva',
            params: {
                rut: rut,
                nombre: nombre,
                camion: camion,
                carro : carro,
                fono : fono,
                observa : observa,
                numero: numero
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idobserva = resp.idobserva;         
                view.close();
                viewIngresar.down("#observaId").setValue(observa);
                viewIngresar.down("#permiteId").setValue(permite);
                viewIngresar.down("#obsId").setValue(idobserva);               

            }
           
        });
    },

    observaciones: function(){

        var viewIngresa = this.getFacturasingresar();
        var numfactura = viewIngresa.down('#numfacturaId').getValue();
        var view = Ext.create('Infosys_web.view.ventas.Observaciones').show();
        view.down("#rutId").focus();
        view.down("#idfactura").setValue(numfactura);

    },

    exportarexcelfacturas: function(){
              
           Ext.create('Infosys_web.view.ventas.Exportar').show();
    },

    generarlibropdf: function(){
              
           Ext.create('Infosys_web.view.ventas.Exportarpdf').show();
    },    

    exportarExcelFormulario: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getFacturasprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportar()
        var viewnew =this.getFacturasprincipal()
        var fecha = view.down('#fechaId').getSubmitValue();
        var opcion = viewnew.down('#tipoSeleccionId').getValue()
        var nombre = viewnew.down('#nombreId').getSubmitValue();
        var fecha2 = view.down('#fecha2Id').getSubmitValue();
        var opcion = view.down('#tipoId').getSubmitValue();
        var tipo = viewnew.down('#tipoDocumentoId').getValue();
        
        if (!tipo) {        
               Ext.Msg.alert('Alerta', 'Debe seleccionar Tipo de Documento');
            return;          

        };


        if (fecha > fecha2) {        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };

        if (opcion == "LIBRO VENTAS"){

             
             if (tipo == 1){

             window.open(preurl + 'adminServicesExcel/exportarExcellibroFacturas?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2);
             view.close();

             };

             if (tipo == 2){

             window.open(preurl + 'adminServicesExcel/exportarExcellibroBoletas?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2);
             view.close();

             }; 
             
             if (tipo == 3){

             window.open(preurl + 'adminServicesExcel/exportarExcelGuias?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2);
             view.close();

             };
             
             if (tipo == 19){

             window.open(preurl + 'adminServicesExcel/exportarExcellibroFacturas?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2);
             view.close();

             };         
            

        }else{

             if (tipo == 1){

             window.open(preurl + 'adminServicesExcel/exportarExcelFacturas?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&opcion='+opcion+'&nombre='+nombre);
             view.close();

             };

             if (tipo == 2){

             window.open(preurl + 'adminServicesExcel/exportarExcelBoletas?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&opcion='+opcion+'&nombre='+nombre);
             view.close();

             };

             if (tipo == 3){

             window.open(preurl + 'adminServicesExcel/exportarExcelGuias?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&opcion='+opcion+'&nombre='+nombre);
             view.close();

             };

             if (tipo == 19){

             window.open(preurl + 'adminServicesExcel/exportarExcelFacturas?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&opcion='+opcion+'&nombre='+nombre);
             view.close();

             };          

        }

       
 
    },

    exportarPdfFormulario: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getFacturasprincipal()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportarpdf()
        var viewnew =this.getFacturasprincipal()
        var fecha = view.down('#fechaId').getSubmitValue();
        var opcion = viewnew.down('#tipoSeleccionId').getValue()
        var nombre = viewnew.down('#nombreId').getSubmitValue();
        var fecha2 = view.down('#fecha2Id').getSubmitValue();
        var opcion = view.down('#tipoId').getSubmitValue();

        
        if (fecha > fecha2) {
        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };

        if (opcion == "LIBRO VENTAS"){

              window.open(preurl + 'facturas/exportarPdflibroFacturas?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2);
            view.close();
            
            

        }else{

             window.open(preurl + 'facturas/exportarPdfFacturas?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2+'&opcion='+opcion+'&nombre='+nombre);
        view.close();         

        }
       
 
    },

    special: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut()
        }
    },

    selecttipocondpago: function() {
        
        var view =this.getFacturasingresar();
        var condicion = view.down('#tipocondpagoId');
        var fechafactura = view.down('#fechafacturaId').getValue();
                
        var stCombo = condicion.getStore();
        var record = stCombo.findRecord('id', condicion.getValue()).data;
        dias = record.dias;

        if (dias > 0){
        
        Ext.Ajax.request({
            url: preurl + 'facturas/calculofechas',
            params: {
                dias: dias,
                fechafactura : fechafactura
            },
            success: function(response){
               var resp = Ext.JSON.decode(response.responseText);
               var fecha_final= resp.fecha_final;
               view.down("#fechavencId").setValue(fecha_final);
                           
            }
           
        });

        }else{

            var fecha_final = fechafactura;
            view.down("#fechavencId").setValue(fecha_final);


        };

        var idpago = view.down('#tipocondpagoId').getValue();
        var bolEnable = false;
        var bolDisabel = true;
         
        if (idpago == 1){
            view.down('#DescuentoproId').setDisabled(bolEnable);
            view.down('#tipoDescuentoId').setDisabled(bolEnable);
            view.down('#descuentovalorId').setDisabled(bolEnable);
                
        };
        if (idpago == 6){

             view.down('#DescuentoproId').setDisabled(bolEnable);
             view.down('#tipoDescuentoId').setDisabled(bolEnable);
             view.down('#descuentovalorId').setDisabled(bolEnable);
            
        };
        if (idpago == 7){

             view.down('#DescuentoproId').setDisabled(bolEnable);
             view.down('#tipoDescuentoId').setDisabled(bolEnable);
             view.down('#descuentovalorId').setDisabled(bolEnable);
            
        };
        if (idpago == 2){

             view.down('#DescuentoproId').setDisabled(bolDisabel);
             view.down('#tipoDescuentoId').setDisabled(bolDisabel);
             view.down('#descuentovalorId').setDisabled(bolDisabel);
            
        };
        if (idpago == 3){

             view.down('#DescuentoproId').setDisabled(bolDisabel);
             view.down('#tipoDescuentoId').setDisabled(bolDisabel);
             view.down('#descuentovalorId').setDisabled(bolDisabel);
            
        };
        if (idpago == 4){

             view.down('#DescuentoproId').setDisabled(bolDisabel);
             view.down('#tipoDescuentoId').setDisabled(bolDisabel);
             view.down('#descuentovalorId').setDisabled(bolDisabel);
            
        };
        if (idpago == 5){

             view.down('#DescuentoproId').setDisabled(bolDisabel);
             view.down('#tipoDescuentoId').setDisabled(bolDisabel);
             view.down('#descuentovalorId').setDisabled(bolDisabel);
            
        };        

       
            
    },

    seleccionarsucursalcliente: function(){

        var view = this.getBuscarsucursalesclientes();
        var viewIngresa = this.getFacturasingresar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#id_sucursalID').setValue(row.data.id);
            viewIngresa.down('#direccionId').setValue(row.data.direccion);
            viewIngresa.down('#tipoCiudadId').setValue(row.data.nombre_ciudad);
            viewIngresa.down('#tipoComunaId').setValue(row.data.nombre_comuna);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    buscar: function(){

        var view = this.getBuscarclientes()
        var st = this.getClientesStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
    },

    buscarsucursalfactura: function(){

       var busca = this.getFacturasingresar()
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

    seleccionarcliente: function(){

        var view = this.getBuscarclientes();
        var viewIngresa = this.getFacturasingresar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#id_cliente').setValue(row.data.id);
            viewIngresa.down('#nombre_id').setValue(row.data.nombres);
            viewIngresa.down('#tipoCiudadId').setValue(row.data.nombre_ciudad);
            viewIngresa.down('#tipoComunaId').setValue(row.data.nombre_comuna);
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            viewIngresa.down('#giroId').setValue(row.data.giro);
            viewIngresa.down('#direccionId').setValue(row.data.direccion);
            viewIngresa.down('#rutId').setValue(row.data.rut);
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            viewIngresa.down('#tipocondpagoId').setValue(row.data.id_pago);
            view.close();
            var condicion = viewIngresa.down('#tipocondpagoId');
            var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
            var stCombo = condicion.getStore();
            var record = stCombo.findRecord('id', condicion.getValue()).data;
            dias = record.dias;
            var bolEnable = false;
            if (row.data.id_pago == 1){

                viewIngresa.down('#DescuentoproId').setDisabled(bolEnable);
                viewIngresa.down('#tipoDescuentoId').setDisabled(bolEnable);
                viewIngresa.down('#descuentovalorId').setDisabled(bolEnable);
                
            };
            if (row.data.id_pago == 6){

                 viewIngresa.down('#DescuentoproId').setDisabled(bolEnable);
                 viewIngresa.down('#tipoDescuentoId').setDisabled(bolEnable);
                 viewIngresa.down('#descuentovalorId').setDisabled(bolEnable);
                
            };
            if (row.data.id_pago == 7){

                 view.down('#DescuentoproId').setDisabled(bolEnable);
                 view.down('#tipoDescuentoId').setDisabled(bolEnable);
                 view.down('#descuentovalorId').setDisabled(bolEnable);
                
            };          
            

            if (dias > 0){
        
            Ext.Ajax.request({
                url: preurl + 'facturas/calculofechas',
                params: {
                    dias: dias,
                    fechafactura : fechafactura
                },
                success: function(response){
                   var resp = Ext.JSON.decode(response.responseText);
                   var fecha_final= resp.fecha_final;
                   viewIngresa.down("#fechavencId").setValue(fecha_final);
                               
            }
           
        });
        };
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },    

    generarfacturapdf: function(){
        var view = this.getFacturasprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            if (row.data.forma==0){
            window.open(preurl +'facturas/exportTXT/?idfactura=' + row.data.id)
            };
            if (row.data.forma==1){
            window.open(preurl +'facturas/exportPDF/?idfactura=' + row.data.id)
            };
            if (row.data.forma==2){
            window.open(preurl +'facturaganado/exportfacturaganadoPDF/?idfactura=' + row.data.id)
            };
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    
    grabarfactura: function() {

        var viewIngresa = this.getFacturasingresar();
        var tipo_documento = viewIngresa.down('#tipoDocumentoId');
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idtipo= viewIngresa.down('#tipoDocumentoId').getValue();
        var idsucursal= viewIngresa.down('#id_sucursalID').getValue();
        var idcondventa= viewIngresa.down('#tipocondpagoId').getValue();
        var ordencompra= viewIngresa.down('#ordencompraId').getValue();
        var idfactura = viewIngresa.down('#idfactura').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var observa = viewIngresa.down('#observaId').getValue();
        var idobserva = viewIngresa.down('#obsId').getValue();
        var numfactura = viewIngresa.down('#numfacturaId').getValue();
        var sucursal = viewIngresa.down('#id_sucursalID').getValue();
        var formadepago = viewIngresa.down('#tipocondpagoId').getValue();
        var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
        var fechavenc = viewIngresa.down('#fechavencId').getValue();
        var stItem = this.getProductosItemsStore();
        var stFactura = this.getFacturaStore();

        if(vendedor==0  && tipo_documento.getValue() == 1){
            Ext.Msg.alert('Ingrese Datos del Vendedor');
            return;   
        }


        if(numfactura==0){
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
                ordencompra: ordencompra,
                vendedor : vendedor,
                sucursal : sucursal,
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
                 window.open(preurl + 'facturas/exportTXT/?idfactura='+idfactura);
                 //window.open(preurl + 'facturas/exportPDF/?idfactura='+idfactura);              

            }
           
        });

        var view = this.getFacturasprincipal();
        var st = this.getFacturaStore();
        st.proxy.extraParams = {documento: idtipo}
        st.load(); 
      
        
    },

    cerrarfactura: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
    },

    selectItemdocuemento: function() {
        
        var view =this.getFacturasingresar();
        var tipo_documento = view.down('#tipoDocumentoId');
        var stCombo = tipo_documento.getStore();
        var record = stCombo.findRecord('id', tipo_documento.getValue()).data;
        
        var nombre = (record.id);    
         Ext.Ajax.request({

            url: preurl + 'correlativos/generafact?valida='+nombre,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {
                    var cliente = resp.cliente;
                    var correlanue = cliente.correlativo;
                    correlanue = (parseInt(correlanue)+1);
                    var correlanue = correlanue;
                    view.down('#numfacturaId').setValue(correlanue);
                    
                }else{
                    Ext.Msg.alert('Correlativo YA Existe');
                    return;
                }

            }            
        });
        var grid  = view.down('#itemsgridId');        

        
        var bolDisabled = tipo_documento.getValue() == 2 ? true : false; // campos se habilitan sólo en factura

        if(bolDisabled == true){  // limpiar campos
           view.down('#rutId').setValue('19');
           this.validaboleta();
           
        }

        //var bolDisable = true;

        //view.down('#rutId').setDisabled(bolDisabled);
        //view.down('#buscarBtn').setDisabled(bolDisabled);
        //view.down('#nombre_id').setDisabled(bolDisabled);
        //view.down('#direccionId').setDisabled(bolDisabled);
        //view.down('#giroId').setDisabled(bolDisabled);
        //view.down('#tipoCiudadId').setDisabled(bolDisabled);
        //view.down('#tipoComunaId').setDisabled(bolDisabled);
        //view.down('#sucursalId').setDisabled(bolDisabled);
        //view.down('#tipoVendedorId').setDisabled(bolDisabled);
        //view.down('#tipocondpagoId').setDisabled(bolDisabled);
        grid.getStore().removeAll();  
        var controller = this.getController('Productos');
        controller.recalcularFinal();

    },

    validaboleta: function(){

        var view =this.getFacturasingresar();
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
                        view.down("#tipoCiudadId").setValue(cliente.nombre_ciudad)
                        view.down("#tipoComunaId").setValue(cliente.nombre_comuna)
                        view.down("#tipoVendedorId").setValue(cliente.id_vendedor)
                        view.down("#giroId").setValue(cliente.giro)
                        view.down("#direccionId").setValue(cliente.direccion)
                        view.down("#tipocondpagoId").setValue(cliente.id_pago)
                        view.down("#rutId").setValue(rut)                       
                    }
                    
                }
            }

        });       
       
    },

    validarut: function(){

        var view =this.getFacturasingresar();
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
                if (resp.success == true){                    
                    if(resp.cliente){
                        var cliente = resp.cliente;
                        view.down("#id_cliente").setValue(cliente.id)
                        view.down("#nombre_id").setValue(cliente.nombres)
                        view.down("#tipoCiudadId").setValue(cliente.nombre_ciudad)
                        view.down("#tipoComunaId").setValue(cliente.nombre_comuna)
                        view.down("#tipoVendedorId").setValue(cliente.id_vendedor)
                        view.down("#giroId").setValue(cliente.giro)
                        view.down("#direccionId").setValue(cliente.direccion)    
                        view.down("#rutId").setValue(rut)
                        view.down("#tipocondpagoId").setValue(cliente.id_pago)                        
                        view.down("#buscarproc").focus()  
                                             
                    }else{
                         var viewedit = Ext.create('Infosys_web.view.clientes.Ingresar').show();                        
                         viewedit.down("#rutId").setValue(rut);  
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
    
    mfactura: function(){

         Ext.create('Infosys_web.view.ventas.Facturas').show();

     
    },

    mejemplo: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'facturasprincipal'});
    },

    buscarvendedor: function(){

        Ext.create('Infosys_web.view.vendedores.BuscarVendedor').show();
    },

    buscarproductos: function(){
        
        var viewIngresa = this.getFacturasingresar();
        var codigo = viewIngresa.down('#codigoId').getValue()
        if (!codigo){
            var st = this.getProductosfStore();
            Ext.create('Infosys_web.view.productos.BuscarProductos').show();
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

    seleccionarproductos: function(){

        var view = this.getBuscarproductos();
        var viewIngresa = this.getFacturasingresar();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id);
            viewIngresa.down('#nombreproductoId').setValue(row.data.nombre);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);
            viewIngresa.down('#precioId').setValue(row.data.p_venta);
            viewIngresa.down('#preciopromId').setValue(row.data.p_promedio);
            viewIngresa.down('#cantidadOriginalId').setValue(row.data.stock);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

     buscarp: function(){
        var view = this.getBuscarproductos();
        var st = this.getProductosfStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre}
        st.load();
    },

     buscarfact: function(){
        
        var view = this.getFacturasprincipal();
        var st = this.getFacturaStore()
        var tipo = view.down('#tipoDocumentoId').getValue();
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion,
                                documento: tipo}
        st.load();
    },


resumenventas: function(){
//
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'resumenventas'});
        
    },  
    
    informestock: function(){
//
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'informestock'});
        
    },  



    estadisticasventas: function(){
//
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'estadisticasventas'});
        
    },  

    verDetalleProductoStock: function(r){
          Ext.create('Infosys_web.view.ventas.VerDetalleProductoStock', {id_producto: r.data.id});            

    },        
          
  
});










