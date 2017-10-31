Ext.define('Infosys_web.controller.Facturacompra', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['facturacompra.Items',
             'Clientes',
             'Factura',
             'Productosf',
             'Tipo_documento',
             'Sucursales_clientes',
             'Tipo_documento.Selector4'],

    models: ['facturacompra.Item',
             'Tipo_documento',
             'Sucursales_clientes'],

    views: ['facturacompra.Facturacompra',
            'facturacompra.BuscarClientes',
            'facturacompra.BuscarSucursales',
            'facturacompra.BuscarProductos',
            'ventas.Principalfactura'],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
       ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'facturacompra',
        selector: 'facturacompra'
    },{
        ref: 'facturacompraclientes',
        selector: 'facturacompraclientes'
    },{
        ref: 'buscarsucursalesclientesfacturacompra',
        selector: 'buscarsucursalesclientesfacturacompra'
    },{
        ref: 'facturasprincipal',
        selector: 'facturasprincipal'
    },{
        ref: 'buscarproductosfacturacompra',
        selector: 'buscarproductosfacturacompra'
    }




    ],
    //init es lo primero que se ejecuta en el controller
    //especia de constructor
    init: function() {
    	//el <<control>> es el puente entre la vista y funciones internas
    	//del controller
        this.control({

            'facturacompra #rutId': {
                specialkey: this.special
            },

            'facturacompra #numfactId': {
                specialkey: this.special2
            },

            'facturasprincipal button[action=mfacturacompra]': {
                click: this.mfacturacompra
            },
           
            'facturacompra button[action=buscarsucursalesclientesfacturacompra]': {
                click: this.buscarsucursalesclientesfacturacompra
            },
            'facturacompra button[action=buscarfactura]': {
                click: this.buscarfactura
            },
            'facturacompra button[action=buscarsucursalfacturacompra]': {
                click: this.buscarsucursalfacturacompra
            },
            'facturacompra button[action=buscarvendedor]': {
                click: this.buscarvendedor
            },
            'facturacompra button[action=buscarproductos]': {
                click: this.buscarproductos
            },
            'facturacompra #nombreId': {
                click: this.special
            },

            'facturacompra #netoId': {
                specialkey: this.calculaiva
            },            

            'facturacompra button[action=validarut]': {
                click: this.validarut
            },
            'facturacompra button[action=grabarfacturacompra]': {
                click: this.grabarfacturacompra
            },
            'facturacompraclientes button[action=buscar]': {
                click: this.buscar
            },
            'facturacompraclientes button[action=seleccionarcliente]': {
                click: this.seleccionarcliente
            },
            'buscarsucursalesclientesfacturacompra button[action=seleccionarsucursalganado]': {
                click: this.seleccionarsucursalganado
            },
            'facturacompra #tipocondpagoId': {
                select: this.selecttipocondpago                
            },
            'facturacompra #fechafacturaId': {
                select: this.selecttipocondpago
            },
            'facturacompra button[action=agregarItem]': {
                click: this.agregarItem
            }, 
            'facturacompra button[action=eliminaritem]': {
                click: this.eliminaritem
            },
            'facturacompra #tipoDocumentoId': {
                select: this.selectItemdocuemento
            },
            'buscarproductosfacturacompra button[action=seleccionarproductos]': {
                click: this.seleccionarproductos
            },
            'buscarproductosfacturacompra button[action=buscar]': {
                click: this.buscarp
            },
            
        });
    },

    seleccionarproductos: function(){

        var view = this.getBuscarproductosfacturacompra();
        var viewIngresa = this.getFacturacompra();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#productoId').setValue(row.data.id);
            viewIngresa.down('#nombreproductoId').setValue(row.data.nombre);
            viewIngresa.down('#codigoId').setValue(row.data.codigo);
            viewIngresa.down('#precioId').setValue(row.data.p_venta);
            viewIngresa.down('#cantidadOriginalId').setValue(row.data.stock);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    buscarp: function(){
        var view = this.getBuscarproductosfacturacomprao();
        var st = this.getProductosfStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre}
        st.load();
    },

    buscarproductos: function(){

        var st = this.getProductosfStore();
        Ext.create('Infosys_web.view.facturacompra.BuscarProductos').show();
        st.load();
    },

    validaboleta: function(){

        var view =this.getFacturacompra();
        var rut = view.down('#rutId').getValue();
        var cero = "";
        var cero1 = "";
        
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
                        view.down('#finaltotalId').setValue(Ext.util.Format.number(cero, '0,000'));
                        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(cero1, '0'));
                        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(cero1, '0'));
                        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(cero1, '0'));
                        view.down('#finalafectoId').setValue(Ext.util.Format.number(cero1, '0'));                      
                    }
                    
                }
            }

        });       
       
    },

    selectItemdocuemento: function() {
        
        var view =this.getFacturacompra();
        var tipo_documento = view.down('#tipoDocumentoId');
        var stCombo = tipo_documento.getStore();
        var record = stCombo.findRecord('id', tipo_documento.getValue()).data;
        var cero = "";
        var cero1 = "";
        
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
                    Ext.Msg.alert('Correlativo Existe');
                    return;
                }

            }            
        });
        var grid  = view.down('#itemsgridId');
        view.down('#finaltotalId').setValue(Ext.util.Format.number(cero, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(cero1, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(cero1, '0'));
        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(cero1, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(cero1, '0'));        

        
        var bolDisabled = tipo_documento.getValue() == 2 ? true : false; // campos se habilitan sólo en factura

        if(bolDisabled == true){  // limpiar campos
           view.down('#rutId').setValue('19');
           this.validaboleta();
           
        }
        grid.getStore().removeAll();  
        
    },

           
    calculaiva: function(){

        var view = this.getFacturacompra();
        var tipo_documento = view.down('#tipoDocumentoId').getValue();
        if (tipo_documento == 19 ){
            var iva = 0;
            var neto = view.down('#netoId').getValue();
            view.down('#totalId').setValue(neto);
            view.down('#ivaId').setValue(iva);
        }else if (tipo_documento == 2 ){
            
            var iva = 0;
            var neto = view.down('#netoId').getValue();
            view.down('#totalId').setValue(neto);
            view.down('#ivaId').setValue(iva);

        }else{
        var neto = view.down('#netoId').getValue();
        var iva = (parseInt((neto * 19) / 100));
        var total = (neto + iva);
        view.down('#ivaId').setValue(iva);
        view.down('#totalId').setValue(total);
        };
    },

    recalcularFinal: function(){

        var view = this.getFacturacompra();
        var stItem = this.getFacturacompraItemsStore();
        var pretotal = 0;
        var total = 0;
        
        stItem.each(function(r){
            pretotal = (parseInt(pretotal) + parseInt(r.data.neto))
          
        });
        
        iva11 = ((pretotal * 11)/100);
        iva8 = ((pretotal * 8)/100);
        afecto = pretotal;
        iva = (iva11 + iva8);
        total = (pretotal + iva);
        
        //iva = (total - afecto);
        view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(pretotal, '0'));
        view.down('#finaltotaliva11Id').setValue(Ext.util.Format.number(iva11, '0'));
        view.down('#finaltotaliva08Id').setValue(Ext.util.Format.number(iva8, '0'));
        view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));
          
    },

    changedctofinal: function(){
        this.recalcularFinal();
    },


    agregarItem: function() {

        var view = this.getFacturacompra();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getFacturacompraItemsStore();
        var producto = view.down('#productoId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var bolEnable = true;
        var st = this.getProductosfStore();
        
        var neto = ((cantidad * precio));        
        var iva11 = (parseInt(neto * 11)/100);
        var iva8 = (parseInt(neto * 8)/100);
        var exists = 0;
        var iva = (iva11 + iva8 );
        var total = ((neto + iva ));

             
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
            Ext.Msg.alert('Alerta', 'Debe Ingresar Datos a la Factura.');
            return false;
           
        }

                      
        stItem.add(new Infosys_web.model.facturacompra.Item({
            id_producto: producto,
            nombre: nombre,
            precio: precio,
            cantidad: cantidad,
            neto: neto,
            total: total,
            iva: iva,
        }));
        this.recalcularFinal();

        cero="";
        cero1=0;
        cero2=1;
        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#nombreproductoId').setValue(cero);
        view.down('#cantidadId').setValue(cero2);
        view.down('#precioId').setValue(cero);
        view.down('#cantidadOriginalId').setValue(cero);
        view.down("#buscarproc").focus();
    },

    eliminaritem: function() {
        var view = this.getFacturacompra();
        var total = view.down('#finaltotalpostId').getValue();
        var neto = view.down('#finaltotalnetoId').getValue();
        var iva11 = view.down('#finaltotaliva11Id').getValue();
        var iva8 = view.down('#finaltotaliva08Id').getValue();
        var grid  = view.down('#itemsgridId');
        var st = this.getProductosfStore();
       
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var total = (parseInt(total) - parseInt(row.data.total));
            var neto = (parseInt(neto) - parseInt(row.data.neto));
            //var iva = (parseInt(iva) - parseInt(row.data.iva));
            var afecto = neto;
            var iva11a = (parseInt(row.data.neto * 1.11));
            var iva8a = (parseInt(row.data.neto * 1.08));
            var iva11 = (parseInt(iva11) - parseInt(iva11a));
            var iva8 = (parseInt(iva8) - parseInt(iva8a));

            view.down('#finaltotalId').setValue(Ext.util.Format.number(total, '0,000'));
            view.down('#finaltotalpostId').setValue(Ext.util.Format.number(total, '0'));
            view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(neto, '0'));
            view.down('#finaltotaliva11Id').setValue(Ext.util.Format.number(iva11, '0'));
            view.down('#finaltotaliva08Id').setValue(Ext.util.Format.number(iva8, '0'));
            view.down('#finalafectoId').setValue(Ext.util.Format.number(afecto, '0'));

            grid.getStore().remove(row);

                                  
      
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

    },

    
    special: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut()
        }
    },

    special2: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validafactura()
        }
    },
  
    
    selecttipocondpago: function() {
        
        var view =this.getFacturacompra();
        var condicion = view.down('#tipocondpagoId').getValue();
        var fechafactura = view.down('#fechafacturaId').getValue();

        if (!condicion){

            Ext.Msg.alert('Alerta', 'Selecciona Forma de Pago.');
            return;
            
        }else{
        
        var condicion = view.down('#tipocondpagoId');
        var stCombo = condicion.getStore();
        var record = stCombo.findRecord('id', condicion.getValue()).data;
        dias = record.dias;
        
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

        };
       
            
    },

    seleccionarsucursalganado: function(){

        var view = this.getBuscarsucursalesclientesfacturacompra();
        var viewIngresa = this.getfacturacompra();
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

        var view = this.getFacturacompraclientes()
        var st = this.getClientesStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : "Nombre"}
        st.load();
    },

    buscarsucursalfacturacompra: function(){

       var busca = this.getFacturacompra()
       var nombre = busca.down('#id_cliente').getValue();
       
       if (nombre){
         var edit = Ext.create('Infosys_web.view.facturacompra.BuscarSucursales').show();
          var st = this.getSucursales_clientesStore();
          st.proxy.extraParams = {nombre : nombre};
          st.load();
       }else {
          Ext.Msg.alert('Alerta', 'Debe seleccionar Cliente.');
            return;
       }
      
    },

    seleccionarcliente: function(){

        var view = this.getFacturacompraclientes();
        var viewIngresa = this.getFacturacompra();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var estado = (row.data.estado);
            if (estado == 3) {
                Ext.Msg.alert('Cliente Bloqueado');
                view.close();
                return;                  
            }else if (estado == 4){
                 Ext.Msg.alert('Cliente protestos Vigentes');
                 view.close();
            return;
            }else {
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
        };

              
    },
           
    grabarfacturacompra : function() {

        var viewIngresa = this.getFacturacompra();
        var tipo_documento = viewIngresa.down('#tipoDocumentoId').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idtipo= viewIngresa.down('#tipoDocumentoId').getValue();
        var idsucursal= viewIngresa.down('#id_sucursalID').getValue();
        var idbodega= viewIngresa.down('#bodegaId').getValue();
        var idcondventa= viewIngresa.down('#tipocondpagoId').getValue();
        var ordencompra= viewIngresa.down('#ordencompraId').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var numdocumento = viewIngresa.down('#numfacturaId').getValue();
        var fechafactura = viewIngresa.down('#fechafacturaId').getValue();
        var fechavenc = viewIngresa.down('#fechavencId').getValue();
        var stItem = this.getFacturacompraItemsStore();
        var stFactura = this.getFacturaStore();        
        
        if(numdocumento==0){
            Ext.Msg.alert('Ingrese Datos a La Factura');
            return;   
            }

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'facturas/savecompra',
            params: {
                idcliente: idcliente,
                numfactura: numdocumento,
                idsucursal: idsucursal,
                idbodega: idbodega,
                idcondventa: idcondventa,
                idtipo: idtipo,
                ordencompra: ordencompra,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                fechafactura : fechafactura,
                fechavenc: fechavenc,
                tipodocumento : tipo_documento,
                netofactura: viewIngresa.down('#finaltotalnetoId').getValue(),
                ivafactura11: viewIngresa.down('#finaltotaliva11Id').getValue(),
                ivafactura8: viewIngresa.down('#finaltotaliva08Id').getValue(),
                afectofactura: viewIngresa.down('#finalafectoId').getValue(),
                totalfacturas: viewIngresa.down('#finaltotalpostId').getValue()
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                var idfactura= resp.idfactura;
                 viewIngresa.close();
                 stFactura.load();
                 window.open(preurl + 'facturas/exportTXTFC/?idfactura='+idfactura);
            }
           
        });
        
        var view = this.getFacturacompra();
        var st = this.getFacturaStore();
        st.proxy.extraParams = {documento: idtipo,
                                idbodega: idbodega }
        st.load();       
        
    },

    
    validarut: function(){

        var view =this.getFacturacompra();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;       
        if(numero==0){
            var edit = Ext.create('Infosys_web.view.facturacompra.BuscarClientes');            
                  
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
                        view.down("#direccionId").setValue(cliente.direccion)
                        view.down("#rutId").setValue(rut)                  
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

                //view.close();

            }

        });       
        }
    },

    mfacturacompra: function(){

         var viewIngresa = this.getFacturasprincipal();
         var idbodega = viewIngresa.down('#bodegaId').getValue();
         if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;    
         }else{
            var nombre = 21;            
            Ext.Ajax.request({
                url: preurl + 'correlativos/generafactcomp?valida='+nombre,
                params: {
                    id: 1
                },
                success: function(response){
                    var resp = Ext.JSON.decode(response.responseText);

                    if (resp.success == true) {
                        var cliente = resp.cliente;
                        var correlanue = cliente.correlativo;
                        var descripcion = cliente.nombre;
                        var id = cliente.id;
                        correlanue = (parseInt(correlanue)+1);
                        var correlanue = correlanue;
                        var view = Ext.create('Infosys_web.view.facturacompra.Facturacompra').show();
                        view.down('#numfacturaId').setValue(correlanue);
                        view.down('#bodegaId').setValue(idbodega);
                        view.down('#tipoDocumentoId').setValue(id);
                                           
                    }else{
                        Ext.Msg.alert('Correlativo YA Existe');
                        return;
                    }
                }            
            });             
         };              
    },

    buscarvendedor: function(){

        Ext.create('Infosys_web.view.vendedores.BuscarVendedor').show();
    },

      
});










