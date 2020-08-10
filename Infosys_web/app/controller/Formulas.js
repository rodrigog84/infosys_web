Ext.define('Infosys_web.controller.Formulas', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['ProductosForm',
             'Formulas',
             'formula.Items',
             'formula.Editar',
             'Clientes'],

    models: ['Formulas',
             'formulas.Item'],

    views: ['formula.Principal',
            'formula.BuscarProductos',
            'formula.BuscarClientes',
            'formula.EditarFormula',
            'formula.BuscarProductos2',
            'formula.BuscarClientes2',
            'formula.reformula'],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{
       ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{
        ref: 'formulaprincipal',
        selector: 'formulaprincipal'
    },{
        ref: 'formulaingresar',
        selector: 'formulaingresar'
    },{
        ref: 'buscarclientesformula',
        selector: 'buscarclientesformula'
    },{
        ref: 'buscarproductosformula',
        selector: 'buscarproductosformula'
    },{
        ref: 'formulaeditar',
        selector: 'formulaeditar'
    },{
        ref: 'buscarclientesformula2',
        selector: 'buscarclientesformula2'
    },{
        ref: 'buscarproductosformula2',
        selector: 'buscarproductosformula2'
    },{
        ref: 'reformulacion',
        selector: 'reformulacion'
    }
    ],
    //init es lo primero que se ejecuta en el controller
    //especia de constructor
    init: function() {
    	//el <<control>> es el puente entre la vista y funciones internas
    	//del controller
        this.control({
           
            'topmenus menuitem[action=mformulas]': {
                click: this.mformulas
            },
            'formulaprincipal button[action=agregarformulas]': {
                click: this.agregarformulas
            },
            'formulaprincipal button[action=buscarformulas]': {
                click: this.buscarformulas
            },
            'formulaprincipal button[action=cerrarformulas]': {
                click: this.cerrarformulas
            },
            'formulaprincipal button[action=cerrarformulas]': {
                click: this.cerrarformulas
            },
            'buscarclientesformula button[action=seleccionarcliente]': {
                click: this.seleccionarcliente
            },
            'buscarclientesformula button[action=buscarclientes]': {
                click: this.buscar
            },
            'buscarclientesformula2 button[action=seleccionarcliente2]': {
                click: this.seleccionarcliente2
            },
            'buscarclientesformula2 button[action=buscarclientes2]': {
                click: this.buscar2
            },
            'formulaingresar button[action=validarut]': {
                click: this.validarut
            },
            'formulaeditar button[action=validarut2]': {
                click: this.validarut2
            },
            'formulaingresar button[action=buscarproductos]': {
                click: this.buscarproductos
            },
            'formulaeditar button[action=buscarproductos2]': {
                click: this.buscarproductos2
            },
            'buscarproductosformula button[action=seleccionarproductos]': {
                click: this.seleccionarproductos
            },
            'buscarproductosformula2 button[action=seleccionarproductos2]': {
                click: this.seleccionarproductos2
            },
            'buscarproductosformula button[action=buscar]': {
                click: this.buscarp
            },
            'buscarproductosformula2 button[action=buscar2]': {
                click: this.buscarp2
            },
            'formulaingresar button[action=agregarItem]': {
                click: this.agregarItem
            },
            'formulaingresar button[action=grabarformula]': {
                click: this.grabarformula
            },
            'formulaeditar button[action=grabarformula2]': {
                click: this.grabarformula2
            },
            'formulaprincipal button[action=exportarformula]': {
                click: this.exportarformula
            },
            'formulaprincipal button[action=editaformula]': {
                click: this.editarformula
            },
            'formulaingresar button[action=editaritem]': {
                click: this.editaritem
            },
            'formulaingresar button[action=eliminaritem]': {
                click: this.eliminaritem
            },
            'formulaeditar button[action=editaritem2]': {
                click: this.editaritem2
            },
            'formulaeditar button[action=eliminaritem2]': {
                click: this.eliminaritem2
            },
            'formulaeditar button[action=agregarItem2]': {
                click: this.agregarItem2
            },
            'reformulacion button[action=reformula]': {
                click: this.reformula
            },
            'reformulacion button[action=limpia]': {
                click: this.limpia
            },
            
        });
    },

    reformula: function(){

        var view = this.getFormulaeditar();
        var viewedit = this.getReformulacion();
        viewedit.close();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var idformula = view.down('#idId').getValue();
        var stItem = this.getFormulaItemsStore();
        var producto = view.down('#productoId').getValue();
        var codigo = view.down('#codigoId').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var cantidador = view.down('#cantidadformId').getValue();
        var precio = (view.down('#precioId').getValue());
        var porcentaje = view.down('#valorporId').getValue();         
        var stItem = this.getFormulaEditarStore();
        var pretotal = 0;
        var total = 0;

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        console.log(porcentaje)

        Ext.Ajax.request({
            url: preurl + 'formula/reformula',
            params: {
                idformula: idformula,
                items: Ext.JSON.encode(dataItems),
                cantidad: cantidador,
                porcentaje: porcentaje,
                precio: precio,
                idproducto: producto,
                idbodega: idbodega,

                },
             success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 var idformula= resp.idformula;
                 stItem.load();
                
            }
           
        });        
        stItem.load();                    
        
        cero="";
        cero1=0;
        cero2=0;
        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#nombreproductoId').setValue(cero);
        view.down('#cantidadId').setValue(cero2);
        view.down('#precioId').setValue(cero);
        view.down('#cantidadOriginalId').setValue(cero);
        view.down('#valorporId').setValue(cero);
        view.down("#buscarproc").focus();
        
    },

    limpia: function(){

        var view = this.getFormulaeditar();
        cero="";
        cero1=0;
        cero2=0;
        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#nombreproductoId').setValue(cero);
        view.down('#cantidadId').setValue(cero2);
        view.down('#precioId').setValue(cero);
        view.down('#cantidadOriginalId').setValue(cero);
        view.down('#valorporId').setValue(cero);
        view.down("#buscarproc").focus();
        var viewedit = this.getreFormulacion();
        viewedit.close();
        
    },

    editaritem2: function() {
        var view = this.getFormulaeditar();
        var cantidadformdet = view.down('#cantidadformdetId').getValue();

        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_producto = row.data.id_producto;
            var valorcompra = row.data.valor_compra;
            var cantidad = row.data.cantidad;
            var porcentaje = row.data.porcentaje;
            var cantidaddet = (cantidadformdet - cantidad );
            var nombre = row.data.nombre_producto;
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
                        view.down('#precioId').setValue(valorcompra);
                        view.down('#productoId').setValue(row.data.id_producto);
                        view.down('#nombreproductoId').setValue(nombre);                        
                        view.down('#codigoId').setValue(cliente.codigo);
                        view.down('#cantidadOriginalId').setValue(cliente.stock);
                        view.down('#cantidadId').setValue(cantidad); 
                        view.down('#cantidadformdetId').setValue(cantidaddet);
                        view.down('#valorporId').setValue(porcentaje);           
                    }
                    
                }
            }

        });
        grid.getStore().remove(row);
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    eliminaritem2: function() {
        var view = this.getFormulaeditar();
        var cantidadformdet = view.down('#cantidadformdetId').getValue();
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var cantidad = row.data.cantidad;
            var cantidaddet = (cantidadformdet - cantidad );
            //view.down('#cantidadformdetId').setValue(cantidaddet);
            console.log(cantidaddet);
            grid.getStore().remove(row);
            //this.recalcular();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

        this.recalcular();


            
    },

    recalcular: function(){

        var view = this.getFormulaeditar();
        var stItem = this.getFormulaEditarStore();
        var pretotal = 0;
        var total = 0;
        
        stItem.each(function(r){
            pretotal = (pretotal + parseInt(r.data.cantidad))
            
        });
        total = pretotal;

        console.log(total);
             
        view.down('#cantidadformdetId').setValue(total);
            
        
    },

    eliminaritem: function() {
        var view = this.getFormulaingresar();
        var cantidadformdet = view.down('#cantidadformdetId').getValue();
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var cantidad = row.data.cantidad;
            var cantidaddet = (cantidadformdet - cantidad );
            //view.down('#cantidadformdetId').setValue(cantidaddet);
            grid.getStore().remove(row);
            //this.recalcular();
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }

        this.recalcular2();
            
    },

    recalcular2: function(){

        var view = this.getFormulaingresar();
        var stItem = this.getFormulaEditarStore();
        var pretotal = 0;
        var total = 0;
        
        stItem.each(function(r){
            pretotal = (pretotal + parseInt(r.data.cantidad))
            
        });
        total = pretotal;

        console.log(total);
             
        view.down('#cantidadformdetId').setValue(total);
            
        
    },

    editarformula: function(){

        var stItms = Ext.getStore('formula.Items');
        stItms.removeAll();       
        var view = this.getFormulaprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var view = this.getFormulaeditar();
            var stItem = this.getFormulaEditarStore();
            var idformula = row.data.id;
            var id_bodega = row.data.id_bodega;            
            stItem.proxy.extraParams = {idformula : idformula};
            stItem.load();
            
            Ext.Ajax.request({
            url: preurl +'formula/edita/?idformula=' + row.data.id,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {                    
                    var view = Ext.create('Infosys_web.view.formula.EditarFormula').show();                   
                    var cliente = resp.cliente;                   
                    view.down("#ticketId").setValue(cliente.num_formula);
                    //view.down("#obsId").setValue(cliente.num_pedido);
                    view.down("#idId").setValue(idformula);
                    view.down("#fechadocumId").setValue(cliente.fecha_formula);
                    view.down("#rutId").setValue(cliente.rut_cliente);                                       
                    view.down("#id_cliente").setValue(cliente.id_cliente);
                    view.down("#nombre_id").setValue(cliente.nombre_cliente);
                    view.down("#tipoVendedorId").setValue(cliente.id_vendedor);
                    view.down("#bodegaId").setValue(id_bodega);
                    view.down("#nombreformulaId").setValue(cliente.nombre_formula);
                    view.down("#cantidadformId").setValue(cliente.cantidad);
                    view.down("#cantidadformdetId").setValue(cliente.cantidad);                  
                    
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

     exportarformula: function(){
        var view = this.getFormulaprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            window.open(preurl +'formula/exportPDF/?idformula=' + row.data.id)
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    grabarformula: function(){

        var viewIngresa = this.getFormulaingresar();
        var numeroformula = viewIngresa.down('#ticketId').getValue();
        var tipodocumento=22;
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var nomcliente = viewIngresa.down('#nombre_id').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var fechaformula = viewIngresa.down('#fechadocumId').getValue();
        var cantidadform = viewIngresa.down('#cantidadformId').getValue();
        var cantidadformdet = viewIngresa.down('#cantidadformdetId').getValue();
        var nomformula = viewIngresa.down('#nombreformulaId').getValue();
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        //var idobserva = viewIngresa.down('#obsId').getValue();
        var stItem = this.getFormulaItemsStore();
        var stformulas = this.getFormulasStore();

        if (cantidadform != cantidadformdet ){
            
             Ext.Msg.alert('Cantidad Solicitada no es Igua a Formula');
            return; 

        }
           
        if(!nomformula){
            Ext.Msg.alert('Ingrese Nombre Formula');
            return;   
        };
        if(!vendedor){
            Ext.Msg.alert('Ingrese Datos del Vendedor');
            return;   
        };
        if(!cantidadform){
            Ext.Msg.alert('Ingrese Cantidad Formula');
            return;   
        };
                
        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'formula/save',
            params: {
                idcliente: idcliente,
                tipo_documento: tipodocumento, 
                nomcliente: nomcliente,
                nomformula: nomformula,
                cantidadform: cantidadform,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                //idobserva: idobserva,
                idbodega: idbodega,
                numeroformula : numeroformula,
                fechaformula: Ext.Date.format(fechaformula,'Y-m-d'),
                },
             success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 var idformula= resp.idformula;
                 viewIngresa.close();
                 stformulas.load();
                 window.open(preurl + 'formula/exportPDF/?idformula='+idformula);
               
            }
           
        });
       
    },

    grabarformula2: function(){

        var viewIngresa = this.getFormulaeditar();
        var numeroformula = viewIngresa.down('#ticketId').getValue();
        var tipodocumento=22;
        var idcliente = viewIngresa.down('#id_cliente').getValue();
        var nomcliente = viewIngresa.down('#nombre_id').getValue();
        var vendedor = viewIngresa.down('#tipoVendedorId').getValue();
        var fechaformula = viewIngresa.down('#fechadocumId').getValue();
        var cantidadform = viewIngresa.down('#cantidadformId').getValue();
        var cantidadformdet = viewIngresa.down('#cantidadformdetId').getValue();
        var nomformula = viewIngresa.down('#nombreformulaId').getValue();
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        var idformula = viewIngresa.down('#idId').getValue();
        var stItem = this.getFormulaEditarStore();
        var stformulas = this.getFormulasStore();

        if (cantidadform != cantidadformdet ){
            
             Ext.Msg.alert('Cantidad Solicitada no es Igua a Formula');
            return; 

        }
           
        if(!nomformula){
            Ext.Msg.alert('Ingrese Nombre Formula');
            return;   
        };
        if(!vendedor){
            Ext.Msg.alert('Ingrese Datos del Vendedor');
            return;   
        };
        if(!cantidadform){
            Ext.Msg.alert('Ingrese Cantidad Formula');
            return;   
        };
                
        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'formula/save2',
            params: {
                idcliente: idcliente,
                idformula: idformula,
                tipo_documento: tipodocumento, 
                nomcliente: nomcliente,
                nomformula: nomformula,
                cantidadform: cantidadform,
                items: Ext.JSON.encode(dataItems),
                vendedor : vendedor,
                //idobserva: idobserva,
                idbodega: idbodega,
                numeroformula : numeroformula,
                fechaformula: Ext.Date.format(fechaformula,'Y-m-d'),
                },
             success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 var idformula= resp.idformula;
                 viewIngresa.close();
                 stformulas.load();
                 window.open(preurl + 'formula/exportPDF/?idformula='+idformula);
               
            }
           
        });
       
    },


    agregarItem: function() {

        var view = this.getFormulaingresar();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getFormulaItemsStore();
        var producto = view.down('#productoId').getValue();
        var codigo = view.down('#codigoId').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var cantidador = view.down('#cantidadformId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var porcentaje = view.down('#valorporId').getValue();
        var cantidaddet = view.down('#cantidadformdetId').getValue();
        var nombreformula = view.down('#nombreformulaId').getValue();

              
        if(!porcentaje){            
            Ext.Msg.alert('Alerta', 'Ingrese Porcentaje');
            return false;
        } 
        
        if(!nombreformula){            
            Ext.Msg.alert('Alerta', 'Ingrese Nombre formula');
            return false;
        }
        
         if(!cantidador){            
            Ext.Msg.alert('Alerta', 'Ingrese Cantidad Formula');
            return false;
        }  
        
        
        var exists = 0;
        
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

        var cantidad = ((cantidador * porcentaje) /100)
        var cantidaddet = (cantidaddet + cantidad);

        if (cantidaddet > cantidador){

             Ext.Msg.alert('Alerta', 'Cantidad Supera Lo Solicitado');
            return false;
            
        }else{
             view.down('#cantidadformdetId').setValue(cantidaddet);
        };

        stItem.each(function(r){
            if(r.data.id == producto){
                Ext.Msg.alert('Alerta', 'El registro ya existe.');
                exists = 1;
                cero="";
                view.down('#codigoId').setValue(cero);
                view.down('#productoId').setValue(cero);
                view.down('#nombreproductoId').setValue(cero);
                view.down('#cantidadId').setValue(cero);
                view.down('#valorporId').setValue(cero);
                view.down('#precioId').setValue(cero);

                return; 
            }
        });
        if(exists == 1)
            return;
                
        stItem.add(new Infosys_web.model.formulas.Item({
            id_producto: producto,
            id_bodega: idbodega,
            codigo: codigo,
            nombre_producto: nombre,
            valor_compra: precio,
            cantidad: cantidad,
            valor_produccion: precio,
            porcentaje: porcentaje
        }));
        
        cero="";
        cero1=0;
        cero2=0;
        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#nombreproductoId').setValue(cero);
        view.down('#cantidadId').setValue(cero2);
        view.down('#precioId').setValue(cero);
        view.down('#cantidadOriginalId').setValue(cero);
        view.down('#valorporId').setValue(cero);
        view.down("#buscarproc").focus();
    },

    agregarItem2: function() {

        var view = this.getFormulaeditar();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getFormulaEditarStore();
        var producto = view.down('#productoId').getValue();
        var codigo = view.down('#codigoId').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var cantidador = view.down('#cantidadformId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var porcentaje = view.down('#valorporId').getValue(); 
        var cantidaddet = view.down('#cantidadformdetId').getValue();        
        var exists = 0;
        
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

        var cantidad = (parseInt(cantidador * porcentaje) /100)
        var cantidaddet = (Math.round(cantidaddet + cantidad));

        if (cantidaddet > cantidador){
             var edit = Ext.create('Infosys_web.view.formula.reformula').show(); 
             return;    
        }else{
             view.down('#cantidadformdetId').setValue(cantidaddet);
        };

        stItem.each(function(r){
            if(r.data.id == producto){
                Ext.Msg.alert('Alerta', 'El registro ya existe.');
                exists = 1;
                cero="";
                view.down('#codigoId').setValue(cero);
                view.down('#productoId').setValue(cero);
                view.down('#nombreproductoId').setValue(cero);
                view.down('#cantidadId').setValue(cero);
                view.down('#valorporId').setValue(cero);
                view.down('#precioId').setValue(cero);

                return; 
            }
        });
        if(exists == 1)
            return;
                
        stItem.add(new Infosys_web.model.formulas.Item({
            id_producto: producto,
            id_bodega: idbodega,
            codigo: codigo,
            nombre_producto: nombre,
            valor_compra: precio,
            cantidad: cantidad,
            valor_producion: precio,
            porcentaje: porcentaje
        }));
        
        cero="";
        cero1=0;
        cero2=0;
        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#nombreproductoId').setValue(cero);
        view.down('#cantidadId').setValue(cero2);
        view.down('#precioId').setValue(cero);
        view.down('#cantidadOriginalId').setValue(cero);
        view.down('#valorporId').setValue(cero);
        view.down("#buscarproc").focus();
    },

    seleccionarproductos2: function(){

        var view = this.getBuscarproductosformula2();
        var viewIngresa = this.getFormulaeditar();
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

    buscarp2: function(){
        var view = this.getBuscarproductosformula2();
        var st = this.getProductosFormStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre}
        st.load();
    },

    buscarproductos2: function(){
          
        var viewIngresa = this.getFormulaeditar();
        var codigo = viewIngresa.down('#codigoId').getValue()
        if (!codigo){
            var st = this.getProductosFormStore();
            Ext.create('Infosys_web.view.formula.BuscarProductos2').show();
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

        var view = this.getBuscarproductosformula();
        var viewIngresa = this.getFormulaingresar();
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
        var view = this.getBuscarproductosformula();
        var st = this.getProductosFormStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre}
        st.load();
    },

    buscarproductos: function(){
          
        var viewIngresa = this.getFormulaingresar();
        var codigo = viewIngresa.down('#codigoId').getValue()
        if (!codigo){
            var st = this.getProductosFormStore();
            Ext.create('Infosys_web.view.formula.BuscarProductos').show();
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

    validarut2: function(){

        var view =this.getFormulaeditar();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

        if(numero==0){
            var edit = Ext.create('Infosys_web.view.formula.BuscarClientes2');            
                  
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
                        //view.down("#btnproductoId").focus()  
                                             
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

    buscar2: function(){

        var view = this.getBuscarclientesformula2();
        var st = this.getClientesStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
        st.load();
    },

    seleccionarcliente2: function(){

        var view = this.getBuscarclientesformula2();
        var viewIngresa = this.getFormulaeditar();
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
            viewIngresa.down('#rutId').setValue(row.data.rut);
            view.close();            
            
            };          
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    validarut: function(){

        var view =this.getFormulaingresar();
        var rut = view.down('#rutId').getValue();
        var numero = rut.length;

        if(numero==0){
            var edit = Ext.create('Infosys_web.view.formula.BuscarClientes');            
                  
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
                        //view.down("#btnproductoId").focus()  
                                             
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

    buscar: function(){

        var view = this.getBuscarclientesformula();
        var st = this.getClientesStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
        st.load();
    },

    seleccionarcliente: function(){

        var view = this.getBuscarclientesformula();
        var viewIngresa = this.getFormulaingresar();
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
            viewIngresa.down('#rutId').setValue(row.data.rut);
            view.close();            
            
            };          
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
       
    },

    agregarformulas: function(){

         var viewIngresa = this.getFormulaprincipal();
         var idbodega = 1;

         if(!idbodega){
            Ext.Msg.alert('Alerta', 'Debe Elegir Bodega');
            return;    
         }else{
         var nombre = "22";    
         Ext.Ajax.request({

            url: preurl + 'correlativos/genera?valida='+nombre,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {
                    var view = Ext.create('Infosys_web.view.formula.Formula').show();                   
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
    
    cerrarformulas: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
    },
 
    mformulas: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'formulaprincipal'});
    },

   
    buscarformulas: function(){
        console.log("LLegamos")
        var view = this.getFormulaprincipal()
        var st = this.getFormulasStore()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre}
        st.load();
    },

    
  
});










