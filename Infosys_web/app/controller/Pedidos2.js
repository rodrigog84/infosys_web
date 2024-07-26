Ext.define('Infosys_web.controller.Pedidos2', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    models: ['Pedidos',
             'pedidos.Item'],


    stores: ['Pedidos.Editar',
            'Pedidos.Items',
            'Pedidos.Selector4',
            'Pedidos.Selector5',
            'Productosf',
            'Productosf',
            'Pedidos',
            'Sucursales_clientes',
            'Correlativos',
            'Clientes',
            'clientes.Selector2',
            'facturapedidos',
            'Bodegas',
            'Factura',
            'FormulasPedidos',
            'Vendedores'
             ],

    
    views: ['Pedidos2.Pedidos',
            'Pedidos2.Principal',
            'Pedidos2.Principaltransporte',
            'Pedidos2.Registrotransporte',            
            'Pedidos2.BuscarClientes',
            'Pedidos2.Editarpedidos',
            'Pedidos2.BuscarProductos',
            'Pedidos2.BuscarFormulas',
            'ventas.BuscarSucursales',
            'Pedidos2.Observaciones',
            'Pedidos2.Observaciones2',
            'Pedidos2.Exportar',
            'Pedidos2.Exportar2',
            'Pedidos2.EstadoPedido',
            'Pedidos2.Elimina',
            'Pedidos2.AdjuntarReceta',
            'Pedidos2.AdjuntarOc',
            'Pedidos2.VerGuias',
            'Pedidos2.VerGuiastransporte',
            'Pedidos2.GenerarOcint',
            ],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{    
       ref: 'pedidosprincipalformula',
        selector: 'pedidosprincipalformula'
    },{    
       ref: 'pedidosprincipaltransporte',
        selector: 'pedidosprincipaltransporte'
    },{    
        ref: 'pedidosingresar2',
        selector: 'pedidosingresar2'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{    
        ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{    
        ref: 'buscarclientespedidos2',
        selector: 'buscarclientespedidos2'
    },{
        ref: 'buscarproductospedidos2',
        selector: 'buscarproductospedidos2'
    },{
        ref: 'editarpedidos2',
        selector: 'editarpedidos2'
    },{
        ref: 'buscarproductospedidos22',
        selector: 'buscarproductospedidos22'
    },{
        ref: 'buscarsucursalesclientes',
        selector: 'buscarsucursalesclientes'
    },{
        ref: 'observacionespedidos2',
        selector: 'observacionespedidos2'
    },{
        ref: 'observacionespedidos22',
        selector: 'observacionespedidos22'
    },{
        ref: 'formularioexportarpedidos2',
        selector: 'formularioexportarpedidos2'
    },{
        ref: 'formulariopdf2',
        selector: 'formulariopdf2'
    },{
        ref: 'buscarformulas2',
        selector: 'buscarformulas2'
    },{
        ref: 'estadopedidos2',
        selector: 'estadopedidos2'
    },{
        ref: 'eliminaPedidos2',
        selector: 'eliminaPedidos2'
    },{
        ref: 'AdjuntarReceta',
        selector: 'AdjuntarReceta'
    },{
        ref: 'AdjuntarOc',
        selector: 'AdjuntarOc'
    },{
        ref: 'VerGuias',
        selector: 'VerGuias'
    },{
        ref: 'VerGuiastransporte',
        selector: 'VerGuiastransporte'
    },{
        ref: 'GenerarOcint',
        selector: 'GenerarOcint'
    }
  
    ],
    
    init: function() {
    	
        this.control({
            'topmenus menuitem[action=mPedidos2]': {
                click: this.mPedidos2
            },
            'topmenus menuitem[action=mRegistroTransporte]': {
                click: this.mRegistroTransporte
            },
            'topmenus menuitem[action=mInforme]': {
                click: this.exportarlibrorecaudacion
            },            
            'pedidosingresar2 button[action=grabarpedidos]': {
                click: this.grabarpedidos
            },
            'editarpedidos button[action=grabarpedidos2]': {
                click: this.grabarpedidos2
            },
            'pedidosprincipalformula button[action=agregarpedido]': {
                click: this.agregarpedido
            },
            'pedidosprincipaltransporte button[action=agregarregistrotransporte]': {
                click: this.agregarregistrotransporte
            },
            'pedidosprincipalformula': {
                adjuntarReceta: this.adjuntarReceta
            },     
            'pedidosprincipalformula': {
                adjuntarOc: this.adjuntarOc
            },     
            'pedidosprincipalformula': {
                iguiasdespachover: this.iguiasdespachover
            },  
            'pedidosprincipaltransporte': {
                tguiasdespachover: this.tguiasdespachover
            },                               
            'pedidosprincipalformula button[action=estadopedidos]': {
                click: this.estadopedidos
            },
            'estadopedidos button[action=cerrarestado]': {
                click: this.cerrarestado
            },
            'estadopedidos button[action=verproduccion]': {
                click: this.verproduccion
            },
            'pedidosprincipalformula button[action=cerrarpedidos]': {
                click: this.cerrarpedidos
            },
            'buscarclientespedidos2 button[action=seleccionarcliente]': {
                click: this.seleccionarcliente
            },
            'pedidosingresar2 button[action=buscarproductos]': {
                click: this.buscarproductos
            },
            'pedidosingresar2 button[action=buscarformula]': {
                click: this.buscarformula
            },
            'editarpedidos button[action=buscarproductos2]': {
                click: this.buscarproductos2
            },
            'buscarproductospedidos2 button[action=seleccionarproductos]': {
                click: this.seleccionarproductos
            },
            'buscarproductospedidos2 button[action=buscar]': {
                click: this.buscarp
            },
            'buscarproductospedidos2 button[action=seleccionarproductos2]': {
                click: this.seleccionarproductos2
            },
            'buscarproductospedidos2 button[action=buscar2]': {
            },
            'pedidosingresar2 button[action=validarut22]': {
                click: this.validarut
            },
            'pedidosingresar2 button[action=buscarsucursalfactura]': {
                click: this.buscarsucursalfactura
            },           
            'buscarclientespedidos2 button[action=buscarclientes]': {
                click: this.buscar
            },
            'pedidosingresar2 #productoId': {
                select: this.selectItem
            },
            'pedidoseditar #productoId': {
                select: this.selectItem2
            },
            'pedidosingresar2 button[action=agregarItem]': {
                click: this.agregarItem
            },
            'editarpedidos button[action=agregarItem2]': {
                click: this.agregarItem2
            },
            'pedidosingresar2 #tipoDocumento2Id': {
                select: this.selectItemdocuemento
            },
            'pedidosprincipalformula button[action=exportarpedidos]': {
                click: this.exportarpedidos
            },
            'pedidosprincipalformula button[action=editarpedidos]': {
                click: this.editarpedidos
            },
            'pedidosprincipalformula button[action=buscarpedido]': {
                click: this.buscarpedidos2
            },
            'pedidosingresar2 button[action=eliminaritem]': {
                click: this.eliminaritem
            },
            'editarpedidos button[action=eliminaritem2]': {
                click: this.eliminaritem2
            },
            'pedidosingresar2 button[action=editaritem]': {
                click: this.editaritem
            },
            'editarpedidos button[action=editaritem2]': {
                click: this.editaritem2
            },
            'buscarsucursalesclientes button[action=seleccionarsucursalclientepedidos]': {
                click: this.seleccionarsucursalclientepedidos
            },
            'pedidosingresar2 button[action=observaciones]': {
                click: this.agregarobserva
            },
            'observacionespedidos2 button[action=ingresaobs]': {
                click: this.ingresaobs
            },
            'editarpedidos button[action=observaciones2]': {
                click: this.agregarobserva2
            },
            'observacionespedidos22 button[action=ingresaobs2]': {
                click: this.ingresaobs2
            },
            'formularioexportarpedidos button[action=exportarExcelFormulario]': {
                click: this.exportarExcelFormulario
            },
            'formularioexportarpedidos2 button[action=exportarExcelFormulario]': {
                click: this.exportarExcelFormulario2
            },            
            'pedidosprincipalformula button[action=exportarexcelpedidos]': {
                click: this.exportarexcelpedidos
            },
            'pedidosprincipalformula button[action=generafactura]': {
                click: this.generafactura
            },
            'facturasingresarpedidos button[action=grabarfactura]': {
                click: this.grabarfactura
            },
            'pedidosprincipalformula button[action=exportarexcelpedidoscaja]': {
                click: this.exportarlibrorecaudacion
            },
            'pedidosprincipalformula #vendedorId': {
                change: this.changedctofinal9                
            },
            'pedidosprincipalformula #Seleccion2Id': {
                change: this.changedctofinal8                
            },
            'pedidosprincipalformula button[action=exportarpdf]': {
                click: this.exportarpdf
            },
            'formulariopdf button[action=exportarExcelFormulariopdf]': {
                click: this.exportarExcelFormulariopdf
            },
            'pedidosprincipalformula #bodegaId': {
                select: this.despliegadocumentos
            },
            'buscarformulas2 button[action=seleccionarformula]': {
                click: this.seleccionarformula
            },
            'buscarformulas2 button[action=buscarformulas]': {
                click: this.buscarformulas
            },
            'pedidosingresar2 #rutId': {
                specialkey: this.special
            },
            'eliminaPedidos2 button[action=eliminapedidos2]': {
                click: this.eliminapedidos2
            },
            'eliminaPedidos2 button[action=salirelimina2]': {
                click: this.salirelimina2
            },
            'pedidosprincipalformula button[action=eliminar2]': {
                click: this.eliminar2
            },
           
        });
    },

    eliminar2: function(){

        var view = this.getPedidosprincipalformula();
        var idbodega = view.down('#bodegaId').getValue();
        if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;            
        }else{
            if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var pedidoId=(row.data.id);
            var edit = Ext.create('Infosys_web.view.Pedidos2.Elimina');
            edit.down('#pedidoId').setValue(pedidoId);
                
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
            
        }
            

        
    },


    adjuntarReceta: function(r){
            console.log('adjuntar receta')
            console.log(r.data.id)


       Ext.Ajax.request({
           //url: preurl + 'cuentacorriente/getCuentaCorriente/' + record.get('cuenta') + '/' + editor.value ,
           url: preurl + 'pedidos2/getPedido?idpedido='+r.data.id,
           success: function(response, opts) {                         
                console.log(response)
              var obj = Ext.decode(response.responseText);
            console.log(obj)
              

                Ext.create('Infosys_web.view.Pedidos2.AdjuntarReceta', {  idpedido: r.data.id,
                                                                            cliente: obj.data.nombre_cliente,
                                                                            rut : obj.data.rut,
                                                                            num_pedido: obj.data.num_pedido});    

           },
           failure: function(response, opts) {
              console.log('server-side failure with status code ' + response.status);
           }
        });  


       
    },



    iguiasdespachover: function(r){

        var t = 1


       Ext.Ajax.request({
           //url: preurl + 'cuentacorriente/getCuentaCorriente/' + record.get('cuenta') + '/' + editor.value ,
           url: preurl + 'pedidos2/getPedido?idpedido='+r.data.id,
           success: function(response, opts) {                         
                console.log(response)
              var obj = Ext.decode(response.responseText);
            console.log(obj)
              

                Ext.create('Infosys_web.view.Pedidos2.VerGuias', {  idpedido: r.data.id,
                                                                            cliente: obj.data.nombre_cliente,
                                                                            rut : obj.data.rut,
                                                                            num_pedido: obj.data.num_pedido}); 

           },
           failure: function(response, opts) {
              console.log('server-side failure with status code ' + response.status);
           }
        });  


       
    },


    tguiasdespachover: function(r){

        var t = 1

        console.log('guias t')

        console.log(r.data)

        Ext.create('Infosys_web.view.Pedidos2.VerGuiastransporte', {  idregistro: r.data.id,
                                                                    cant_documentos: r.data.cantidad,
                                                                    num_registro: r.data.num_registro}); 


       
    },    


    adjuntarOc: function(r,t){
            console.log(t)


       Ext.Ajax.request({
           //url: preurl + 'cuentacorriente/getCuentaCorriente/' + record.get('cuenta') + '/' + editor.value ,
           url: preurl + 'pedidos2/getPedido?idpedido='+r.data.id,
           success: function(response, opts) {                         
                console.log(response)
              var obj = Ext.decode(response.responseText);
            console.log(obj)
              

                if(t == 1){
                    Ext.create('Infosys_web.view.Pedidos2.AdjuntarOc', {  idpedido: r.data.id,
                                                                                cliente: obj.data.nombre_cliente,
                                                                                rut : obj.data.rut,
                                                                                num_pedido: obj.data.num_pedido}); 

                }else if(t == 2){

                    Ext.create('Infosys_web.view.Pedidos2.GenerarOcint', {  idpedido: r.data.id,
                                                                                cliente: obj.data.nombre_cliente,
                                                                                rut : obj.data.rut,
                                                                                num_pedido: obj.data.num_pedido}); 



                }    

           },
           failure: function(response, opts) {
              console.log('server-side failure with status code ' + response.status);
           }
        });  


       
    },


    salirelimina2: function(){
        var view = this.getEliminaPedidos2();
        view.close();
    },

    eliminapedidos2: function(){

        var edit = this.getPedidosprincipalformula();
        var st = this.getPedidosStore();
        var idbodega = edit.down('#bodegaId').getValue();
        var nombre = edit.down('#nombreId').getValue();
        var estado = edit.down('#Seleccion2Id').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                estado : estado,
                                idbodega : idbodega}
        var view = this.getEliminaPedidos2(); 
        var idpedido = view.down('#pedidoId').getValue();                  
        Ext.Ajax.request({
        url: preurl +'pedidos/eliminapedido/?idpedidos=' + idpedido,
        params: {
            id: 1
        },
        success: function(response){
            var resp = Ext.JSON.decode(response.responseText);
            if (resp.success == true) {
                st.load();    
                view.close();                  
                Ext.Msg.alert('Pedido Eliminado Correctamente');
                return;
            }else{
                st.load();  
                view.close();
                Ext.Msg.alert('Pedido No disponoble para Eliminar');
                return;                
            }
        }            
        });        
        
    },


    buscarformulas: function(){

        var view = this.getBuscarformulas2();
        var st = this.getFormulasPedidosStore();
        var cero="";
        var nombre = view.down('#nombreId').getValue();
        if (nombre){
            view.down("#nombreId").setValue(cero);            
        };
        st.proxy.extraParams = {nombre : nombre}
        st.load();        
    },

    special: function(f,e){
        if (e.getKey() == e.ENTER) {
            this.validarut()
        }
    },

    verproduccion: function(){

        var view = this.getEstadopedidos(); 
        var idproduccion = view.down('#produccionId').getValue();  
        if(idproduccion){
        window.open(preurl + 'produccion/exportPDF2/?idproduccion='+idproduccion);
        }else{
            Ext.Msg.alert('Produccion No Terminada');
            return;
            
        }

        
    },


    estadopedidos: function(){

        var view = this.getPedidosprincipalformula(); 
        var id_bodega = view.down('#bodegaId').getValue();                  
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var view = this.getEditarpedidos();
            var stItem = this.getPedidosEditarStore();
            var idpedidos = row.data.id;
            var estado = row.data.estado;
            Ext.Ajax.request({
            url: preurl +'pedidos/estado/?idpedidos=' + row.data.id,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {                    
                    var view = Ext.create('Infosys_web.view.Pedidos2.EstadoPedido').show();                   
                    var cliente = resp.cliente;
                    view.down('#pedidoId').setValue(cliente.id);
                    view.down('#numpedidoId').setValue(cliente.num_pedido); 
                    view.down('#numproduccionId').setValue(cliente.num_produccion);
                    view.down('#produccionId').setValue(cliente.id_produccion); 
                    view.down('#fechaPEDIDOId').setValue(cliente.fecha_pedido);
                    view.down('#fechaproduccionId').setValue(cliente.fecha_inicio);
                    view.down('#nomformulaId').setValue(cliente.nom_formula);
                    view.down('#nomProductoId').setValue(cliente.nom_producto);
                    view.down('#cantpedidoId').setValue(cliente.cantidad);
                    view.down('#cantproducidoId').setValue(cliente.cantidad_prod);
                    view.down('#cantprodrealId').setValue(cliente.cant_real);
                    view.down('#fechainicioId').setValue(cliente.fecha_inicio);
                    view.down('#fechaterminoId').setValue(cliente.fecha_termino); 
                    view.down('#horainicioId').setValue(cliente.hora_inicio);
                    view.down('#horaterminoId').setValue(cliente.hora_termino);                 
                                   
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

    seleccionarformula: function(){

        var view = this.getBuscarformulas2();
        var viewIngresa = this.getPedidosingresar2();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            viewIngresa.down('#formulaId').setValue(row.data.id);
            viewIngresa.down('#nombreformulaId').setValue(row.data.nombre_formula);
            viewIngresa.down('#cantidadformId').setValue(row.data.cantidad);
            viewIngresa.down('#cantidadId').setValue(row.data.cantidad);
            view.close();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }        
    },



    despliegadocumentos: function(){

        var view = this.getPedidosprincipalformula();
        var idbodega = view.down('#bodegaId').getValue();
        var idtipo = 1;
        var st = this.getPedidosStore();
        st.proxy.extraParams = {documento: idtipo,
                                idbodega: idbodega,
                                tipopedido: 'I' }
        st.load();       
    },


    exportarpdf: function(){

        Ext.create('Infosys_web.view.Pedidos2.Exportar2').show();        

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

        var view = this.getPedidosprincipalformula();
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

        var viewnew =this.getPedidosprincipalformula();       
        Ext.create('Infosys_web.view.Pedidos2.Exportar').show();
      
    
    },

    exportarExcelFormulario: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getPedidosprincipalformula()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportarpedidos()
        var viewnew =this.getPedidosprincipalformula()

        var fecha = view.down('#fechaId').getSubmitValue();
        
        var fecha2 = view.down('#fecha2Id').getSubmitValue();
                
        if (fecha > fecha2) {
        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };     

        window.open(preurl + 'adminServicesExcel/exportarExcelPedidos?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2);
        view.close();    

     
 
    }, 
   


    exportarExcelFormulario2: function(){
        
        var jsonCol = new Array()
        var i = 0;
        var grid =this.getPedidosprincipalformula()
        Ext.each(grid.columns, function(col, index){
          if(!col.hidden){
              jsonCol[i] = col.dataIndex;
          }
          
          i++;
        })

        var view =this.getFormularioexportarpedidos2()
        var viewnew =this.getPedidosprincipalformula()

        var fecha = view.down('#fechaId').getSubmitValue();
        
        var fecha2 = view.down('#fecha2Id').getSubmitValue();

        var fechaformat = fecha.substring(6)+fecha.substring(3,5)+fecha.substring(0,2);
        var fecha2format = fecha2.substring(6)+fecha2.substring(3,5)+fecha2.substring(0,2);

        if (fechaformat > fecha2format) {
        
               Ext.Msg.alert('Alerta', 'Fechas Incorrectas');
            return;          

        };     

        window.open(preurl + 'adminServicesExcel/exportarExcelPedidos2?cols='+Ext.JSON.encode(jsonCol)+'&fecha='+fecha+'&fecha2='+fecha2);
        view.close();    

     
 
    }, 

    changedctofinal8: function(){
        this.buscarpedidos2();
    },
    
    changedctofinal9: function(){
        this.buscarpedidos3();
    },    

    buscarDoc: function(){
        
        var view = this.getPedidosprincipalformula();
        var st = this.getPedidosStore();
        var idbodega = view.down('#bodegaId').getValue();
        var opcion = view.down('#tipoSeleccionId').getValue();
        var tipo = view.down('#tipoPedidoId').getValue();
        var nombre = view.down('#nombreId').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion,
                                tipo: tipo,
                                idbodega: idbodega 
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

        var view = this.getObservacionespedidos2();
        var viewIngresar = this.getPedidosingresar2();                
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

        var viewIngresa = this.getPedidosingresar2();
        var observa = viewIngresa.down('#obsId').getValue();
        var numpedidos = viewIngresa.down('#ticketId').getValue();
        if (!observa){
            var view = Ext.create('Infosys_web.view.Pedidos2.Observaciones').show();
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
                var view = Ext.create('Infosys_web.view.Pedidos2.Observaciones').show();
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
            var view = Ext.create('Infosys_web.view.Pedidos2.Observaciones2').show();
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
                var view = Ext.create('Infosys_web.view.Pedidos2.Observaciones2').show();
                view.down('#observaId').setValue(observar.observaciones);
                view.down('#numpedidoId').setValue(observar.num_pedidos);
                }else{
                  var view = Ext.create('Infosys_web.view.Pedidos2.Observaciones2').show();
                  view.down("#numpedidoId").setValue(numpedidos);

                };
            }           
            });
        }
        
    },

    agregarItem: function() {

        var view = this.getPedidosingresar2();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getPedidosItemsStore();
        var producto = view.down('#productoId').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var nombreformula = view.down('#nombreformulaId').getValue();
        var formulaId = view.down('#formulaId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var precioun = ((view.down('#precioId').getValue())/ 1.19);
        var bolEnable = true;
     
        var neto = (cantidad * precio);
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
                view.down('#precioId').setValue(cero);

                return; 
            }
        });
        if(exists == 1)
            return;
        console.log(nombreformula)         
        stItem.add(new Infosys_web.model.pedidos.Item({
            id: producto,
            id_producto: producto,
            id_bodega: idbodega,
            id_formula: formulaId,
            nom_producto: nombre,
            nombreformula : nombreformula,
            precio: precio,
            cantidad: cantidad,
            neto: neto,
            total: total,
            iva: iva,
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
        view.down("#buscarproc").focus();
    },

     buscarsucursalfactura: function(){

       var busca = this.getPedidosingresar2()
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
        var viewIngresa = this.getPedidosingresar2();
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

        console.log("llegamos");

        var view =this.getPedidosingresar2();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

        if(numero==0){
            var edit = Ext.create('Infosys_web.view.Pedidos2.BuscarClientes');            
                  
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


                         if (cliente.estado == 3){
                                view.down('#estado_cliente').setFieldStyle('font-weight:bold;background-color:red;')
                                view.down('#estado_cliente').setValue('&nbsp;Cliente Bloqueado -  Se debe autorizar pedido&nbsp;')

                         }else if(cliente.estado == 4){
                                view.down('#estado_cliente').setFieldStyle('font-weight:bold;background-color:red;')
                                view.down('#estado_cliente').setValue('&nbsp;Cliente protestos Vigentes -  Se debe autorizar pedido&nbsp;')
                         }else{
                                view.down('#estado_cliente').setFieldStyle('font-weight:bold;background-color:green;')
                                view.down('#estado_cliente').setValue('&nbsp;Cliente Válido&nbsp;')
                                
                         }

                                                 
                        view.down("#id_cliente").setValue(cliente.id)
                        view.down("#nombre_id").setValue(cliente.nombres)
                        view.down("#tipoVendedorId").setValue(cliente.id_vendedor)
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
        var idFormula = viewIngresa.down('#formulaId').getValue();
        var nomformula = viewIngresa.down('#nombreformulaId').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var idobserva = viewIngresa.down('#obsId').getValue();
        var nomcliente = viewIngresa.down('#nombre_id').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId');
        var idbodega = 1;
        viewIngresa.down('#bodegaId').getValue();
        var cantidadfor = viewIngresa.down('#cantidadformId').getValue();        
        var stCombo = vendedor.getStore();
        var record = stCombo.findRecord('id', vendedor.getValue()).data;
        var finalafectoId = viewIngresa.down('#finaltotalnetoId').getValue();
        var vendedor = record.id;
        var fechadoc = viewIngresa.down('#fechadocumId').getValue();
        var fechapedido = viewIngresa.down('#fechapedidoId').getValue();
        var stItem = this.getPedidosEditarStore();
        var stpedidos = this.getPedidosStore();

        if(!idFormula){
             Ext.Msg.alert('Debe Asignar Formula');
            return;            
        }

     
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
                idpedido: idpedido, 
                nomcliente: nomcliente,
                idformula: idFormula,
                cantidadfor: cantidadfor,
                nomformula: nomformula,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                idbodega: idbodega,
                idobserva: idobserva,
                numeropedido : numeropedido,
                fechadocum: Ext.Date.format(fechadoc,'Y-m-d'),
                fechapedido: Ext.Date.format(fechapedido,'Y-m-d'),
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
                 window.open(preurl + 'pedidos/exportPDF2/?idpedidos='+idpedidos);
               
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
        var rut = view.down('#rutId').getValue();
        var stItem = this.getPedidosEditarStore();
        var producto = view.down('#productoId').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var precioun = ((view.down('#precioId').getValue())/ 1.19);
        var bolEnable = true;
        var cero = " ";
        var cero1= 0;
        var cero2= 1;
        var bolEnable = true;
        
        var neto = (cantidad * precio) ;
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
                view.down('#precioId').setValue(cero);

                return; 
            }
        });
        if(exists == 1)
            return;
                
        stItem.add(new Infosys_web.model.pedidos.Item({
            id_producto: producto,
            id_bodega: idbodega,
            nom_producto: nombre,
            precio: precio,
            cantidad: cantidad,
            neto: neto,
            total: total,
            iva: iva
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
        view.down("#buscarproc").focus();
    },

    editarpedidos: function(){

        //var view = Ext.create('Infosys_web.view.Pedidos.Editarpedidos').show();                   
                    
        var stItms = Ext.getStore('Pedidos.Items');
        stItms.removeAll();
       
        var view = this.getPedidosprincipalformula();       
                   
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var view = this.getEditarpedidos();
            var stItem = this.getPedidosEditarStore();
            var idpedidos = row.data.id;
            var id_bodega = row.data.id_bodega;
            var estado = row.data.estado;

            if(estado!="4"){
                Ext.Msg.alert('Alerta', 'Pedido en produccion');
            return;               

            }else{
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
                    var view = Ext.create('Infosys_web.view.Pedidos2.Editarpedidos').show();                   
                    var cliente = resp.cliente;                   
                    view.down("#ticketId").setValue(cliente.num_pedido);
                    view.down("#obsId").setValue(cliente.num_pedido);
                    view.down("#idId").setValue(cliente.id);
                    view.down("#fechadocumId").setValue(cliente.fecha_doc);
                    view.down("#fechapedidoId").setValue(cliente.fecha_pedido);
                    view.down("#rutId").setValue(cliente.rut_cliente);                                       
                    view.down("#id_cliente").setValue(cliente.id_cliente);
                    view.down("#nombre_id").setValue(cliente.nombre_cliente);
                    view.down("#tipoVendedorId").setValue(cliente.id_vendedor);
                    view.down("#nombreformulaId").setValue(cliente.nombre_formula);
                    view.down("#cantidadformId").setValue(cliente.cantidad_formula);
                    view.down("#formulaId").setValue(cliente.id_formula);
                    view.down("#bodegaId").setValue(id_bodega);
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
        };

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
        var view = this.getPedidosingresar2();
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
        var view = this.getPedidosingresar2();
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
                        view.down('#finaltotalId').setValue(Ext.util.Format.number(totalnue, '0,000'));
                        view.down('#finaltotalpostId').setValue(Ext.util.Format.number(totalnue, '0'));
                        view.down('#finaltotalnetoId').setValue(Ext.util.Format.number(netonue, '0'));
                        view.down('#finaltotalivaId').setValue(Ext.util.Format.number(ivanue, '0'));
                        view.down('#finalafectoId').setValue(Ext.util.Format.number(afectonue, '0'));
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
        var view = this.getPedidosprincipalformula();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            window.open(preurl +'pedidos/exportPDF/?idpedidos=' + row.data.id)
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    validaboleta: function(){

        var view =this.getPedidosingresar2();
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
        
        var view =this.getPedidosingresar2();
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

        var view = this.getPedidosingresar2();
        var stItem = this.getPedidosItemsStore();
        var grid2 = view.down('#itemsgridId');
        var pretotal = 0;
        var total = 0;
        var iva = 0;
        var neto = 0;
        stItem.each(function(r){
            pretotal = pretotal + r.data.total
            iva = iva + r.data.iva
            neto = neto + r.data.neto
        });
        total = pretotal;
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
        },



    

    selectItem: function() {

        var view = this.getPedidosingresar2();
        var producto = view.down('#productoId');
        var stCombo = producto.getStore();
        var record = stCombo.findRecord('id', producto.getValue()).data;
        
        view.down('#precioId').setValue(record.p_venta);
        view.down('#codigoId').setValue(record.codigo);
        view.down('#cantidadOriginalId').setValue(record.stock);
          
    },

    buscar: function(){

        var view = this.getBuscarclientespedidos2();
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

    buscarformula: function(){
          
        var view = this.getPedidosingresar2();
       
        var idcliente = view.down('#id_cliente').getValue()
        if(!idcliente){
             Ext.Msg.alert('Alerta', 'Selecciona un Cliente.');
            return;        
        }else{
            var st = this.getFormulasPedidosStore()
            st.proxy.extraParams = {idcliente : idcliente}
            st.load();               
            Ext.create('Infosys_web.view.Pedidos2.BuscarFormulas').show();            
        }          
        
    },

    buscarproductos: function(){
          
        var viewIngresa = this.getPedidosingresar2();
        var codigo = viewIngresa.down('#codigoId').getValue()
        if (!codigo){
            var st = this.getProductosfStore();
            Ext.create('Infosys_web.view.Pedidos2.BuscarProductos').show();
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
           
        if (!codigo){
            var view = Ext.create('Infosys_web.view.Pedidos2.BuscarProductos2').show();
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

        var view = this.getBuscarproductospedidos2();
        var viewIngresa = this.getPedidosingresar2();
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
        var view = this.getBuscarproductospedidos2();
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

        var view = this.getBuscarclientespedidos2();
        var viewIngresa = this.getPedidosingresar2();
        var grid  = view.down('grid');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var estado = (row.data.estado);
            /*if (estado == 3) {
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
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            viewIngresa.down('#rutId').setValue(row.data.rut);
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            view.close();
            var bolEnable = false;
            
            };*/

             if (estado == 3){
                    viewIngresa.down('#estado_cliente').setFieldStyle('font-weight:bold;background-color:red;')
                    viewIngresa.down('#estado_cliente').setValue('&nbsp;Cliente Bloqueado -  Se debe autorizar pedido&nbsp;')

             }else if(estado == 4){
                    viewIngresa.down('#estado_cliente').setFieldStyle('font-weight:bold;background-color:red;')
                    viewIngresa.down('#estado_cliente').setValue('&nbsp;Cliente protestos Vigentes -  Se debe autorizar pedido&nbsp;')
             }else{
                    viewIngresa.down('#estado_cliente').setFieldStyle('font-weight:bold;background-color:green;')
                    viewIngresa.down('#estado_cliente').setValue('&nbsp;Cliente Válido&nbsp;')
             }


            viewIngresa.down('#id_cliente').setValue(row.data.id);
            viewIngresa.down('#nombre_id').setValue(row.data.nombres);
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            viewIngresa.down('#rutId').setValue(row.data.rut);
            viewIngresa.down('#tipoVendedorId').setValue(row.data.id_vendedor);
            view.close();
            var bolEnable = false;


        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    validarut2: function(){

        var view = this.getPedidosingresar2();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

        if(numero==0){
            var edit = Ext.create('Infosys_web.view.Pedidos2.BuscarClientes');            
                  
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
                        if (cliente.estado=="3"){
                            view.down("#rutId").setValue(cero);
                            Ext.Msg.alert('Cliente Bloqueado');
                            return;
                        };
                        if (cliente.estado=="4"){
                            view.down("#rutId").setValue(cero);
                            Ext.Msg.alert('Cliente Protestos Vigentes');
                            return;                            
                        };
                        view.down("#id_cliente").setValue(cliente.id)
                        view.down("#nombre_id").setValue(cliente.nombres)                        
                        view.down("#rutId").setValue(rut)
                        view.down('#tipoVendedorId').setValue(cliente.id_vendedor);
                                             
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

    mPedidos2: function(){       
        var viewport = this.getPanelprincipal();
        /*var st = this.getPedidosStore();
        st.proxy.extraParams = {tipopedido : 'E'}
        st.load();
        */
        viewport.removeAll();
        viewport.add({xtype: 'pedidosprincipalformula'});
    },


    mRegistroTransporte: function(){       
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'pedidosprincipaltransporte'});
    },

    
    buscarpedidos: function(){
        
        var view = this.getPedidosprincipalformula();
        var idbodega = view.down('#bodegaId').getValue();
        if(!idbodega){
             Ext.Msg.alert('Debe Seleccionar Bodega Para Buscar');
            return;             
        }else{
        var st = this.getPedidosStore();
        var idbodega = view.down('#bodegaId').getValue();
        var nombre = view.down('#nombreId').getValue();
        var estado = view.down('#Seleccion2Id').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                estado : estado,
                                idbodega : idbodega}
        st.load();
        };


    },

    buscarpedidos2: function(){
        
        var view = this.getPedidosprincipalformula();
        var idbodega = view.down('#bodegaId').getValue();
        if(!idbodega){
             Ext.Msg.alert('Debe Seleccionar Bodega Para Buscar');
            return;             
        }else{
        var st = this.getPedidosStore();
        var cero="";
        var nombre = view.down('#nombreId').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        if (nombre){
            view.down("#nombreId").setValue(cero);            
        };
        var estado = view.down('#Seleccion2Id').getValue();
        var opcion = view.down('#tipoSeleccionId').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                estado : estado,
                                opcion : opcion,
                                idbodega : idbodega}
        var idvendedor = view.down('#vendedorId').setValue(cero);
        st.load();
        };
    },

    buscarpedidos3: function(){
        
        var view = this.getPedidosprincipalformula();
        var view = this.getPedidosprincipal();
        var idbodega = view.down('#bodegaId').getValue();
        if(!idbodega){
             Ext.Msg.alert('Debe Seleccionar Bodega Para Buscar');
            return;             
        }else{
        var st = this.getPedidosStore();
        var cero="";
        var nombre = view.down('#nombreId').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var idvendedor = view.down('#vendedorId').getValue();
        if (nombre){
            view.down("#nombreId").setValue(cero);            
        };
        var estado = view.down('#Seleccion2Id').getValue();
        var opcion = view.down('#tipoSeleccionId').getValue();
        st.proxy.extraParams = {nombre : nombre,
                                estado : estado,
                                opcion : opcion,
                                idbodega : idbodega,
                                idvendedor: idvendedor }
        st.load();
        };


    },

    grabarpedidos: function(){

        var viewIngresa = this.getPedidosingresar2();
        var numeropedido = viewIngresa.down('#ticketId').getValue();
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var nomcliente = viewIngresa.down('#nombre_id').getValue();
        var idFormula = viewIngresa.down('#formulaId').getValue();
        var nomformula = viewIngresa.down('#nombreformulaId').getValue();
        var id_observa = viewIngresa.down('#obsId').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId');
        var fechapedidos = viewIngresa.down('#fechapedidoId').getValue();
        var fechadocum = viewIngresa.down('#fechadocumId').getValue();
        var fechadespacho = viewIngresa.down('#fechadespachoId').getValue();
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        var cantidadfor = viewIngresa.down('#cantidadformId').getValue();
        var stCombo = vendedor.getStore();
        var record = stCombo.findRecord('id', vendedor.getValue()).data;
        var finalafectoId = viewIngresa.down('#finaltotalnetoId').getValue();
        var ordencompraId = viewIngresa.down('#ordencompraId').getValue();
        var ubicacionId = viewIngresa.down('#ubicacionId').getValue();

        var tipoenvaseId = viewIngresa.down('#tipoenvaseId').getValue();
        var tipotransporteId = viewIngresa.down('#tipotransporteId').getValue();

        var vendedor = record.id;
        var stItem = this.getPedidosItemsStore();
        var stpedidos = this.getPedidosStore();
        viewIngresa.down("#grabarpedidos").setDisabled(true);

        if(!idFormula){
            viewIngresa.down("#grabarpedidos").setDisabled(false);
            Ext.Msg.alert('Atención','Debe Asignar Formula');
            return;            
        }

        if(!tipoenvaseId){
            viewIngresa.down("#grabarpedidos").setDisabled(false);
            Ext.Msg.alert('Atención','Debe Seleccionar Tipo Envase');
            return;            
        }


        if(!tipotransporteId){
            viewIngresa.down("#grabarpedidos").setDisabled(false);
            Ext.Msg.alert('Atención','Debe Seleccionar Tipo Transporte');
            return;            
        }


        if(!fechadespacho){
            viewIngresa.down("#grabarpedidos").setDisabled(false);
            Ext.Msg.alert('Atención','Debe Asignar Fecha de Despacho a Solicitada');
            return;            
        }

        if(vendedor==0){
            viewIngresa.down("#grabarpedidos").setDisabled(false);
            Ext.Msg.alert('Atención','Ingrese Datos del Vendedor');
            return;   
        }

        if(finalafectoId==0){
            viewIngresa.down("#grabarpedidos").setDisabled(false);
            Ext.Msg.alert('Atención','Ingrese Productos al Pedido');
            return;   
        }


        if(ubicacionId==0){
            viewIngresa.down("#grabarpedidos").setDisabled(false);
            Ext.Msg.alert('Atención','Debe ingresar ubicación del cliente');
            return;   
        }
        
        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'pedidos2/save',
            params: {
                idcliente: idcliente,
                nomcliente: nomcliente,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                cantidadfor: cantidadfor,
                idformula: idFormula,
                nomformula: nomformula,
                id_observa: id_observa,
                idbodega: idbodega,
                numeropedido : numeropedido,
                ordencompra : ordencompraId,
                tipoenvase : tipoenvaseId,
                tipotransporte : tipotransporteId,
                ubicacion : ubicacionId,
                fechadocum: Ext.Date.format(fechadocum,'Y-m-d'),
                fechapedido: Ext.Date.format(fechapedidos,'Y-m-d'),
                fechadespacho: Ext.Date.format(fechadespacho,'Y-m-d'),
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
                 window.open(preurl + 'pedidos2/exportPDF2/?idpedidos='+idpedidos);
               
            }
           
        });
       
    },        
    
    agregarregistrotransporte: function(){

         var viewIngresa = this.getPedidosprincipaltransporte();

         var nombre = "110";    
         Ext.Ajax.request({

            url: preurl + 'correlativos/genera?valida='+nombre,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {
                    var view = Ext.create('Infosys_web.view.Pedidos2.Registrotransporte').show();                   
                    var cliente = resp.cliente;
                    var correlanue = cliente.correlativo;
                    correlanue = (parseInt(correlanue)+1);
                    var correlanue = correlanue;
                    view.down("#ticketId").setValue(correlanue);
                   // view.down("#bodegaId").setValue(idbodega);
                }else{
                    Ext.Msg.alert('Correlativo YA Existe');
                    return;
                }
            }            
        });
            
       
    },


    agregarpedido: function(){

         var viewIngresa = this.getPedidosprincipalformula();
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
                    var view = Ext.create('Infosys_web.view.Pedidos2.Pedidos').show();                   
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

    cerrarestado: function(){
        var view = this.getEstadopedidos();
        view.close();
     
    },
  
});










