Ext.define('Infosys_web.controller.Formulas', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    stores: ['ProductosForm',
             'Formulas',
             'formula.Items'],

    models: ['Formulas',
             'formulas.Item'],

    views: ['formula.Principal',
            'formula.BuscarProductos',
            'formula.BuscarClientes'],

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
            'formulaingresar button[action=validarut]': {
                click: this.validarut
            },
            'formulaingresar button[action=buscarproductos]': {
                click: this.buscarproductos
            },
            'buscarproductosformula button[action=seleccionarproductos]': {
                click: this.seleccionarproductos
            },
            'buscarproductosformula button[action=buscar]': {
                click: this.buscarp
            },
            'formulaingresar button[action=agregarItem]': {
                click: this.agregarItem
            },
            'formulaingresar button[action=grabarformula]': {
                click: this.grabarformula
            },
            'formulaprincipal button[action=exportarformula]': {
                click: this.exportarformula
            },
            
        });
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
        var nomformula = viewIngresa.down('#nombreformulaId').getValue();
        var idbodega = viewIngresa.down('#bodegaId').getValue();
        //var idobserva = viewIngresa.down('#obsId').getValue();
        var stItem = this.getFormulaItemsStore();
        var stformulas = this.getFormulasStore();
           
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

    agregarItem: function() {

        var view = this.getFormulaingresar();
        var tipo_documento = view.down('#tipoDocumentoId');
        var rut = view.down('#rutId').getValue();
        var stItem = this.getFormulaItemsStore();
        var producto = view.down('#productoId').getValue();
        var codigo = view.down('#codigoId').getValue();
        var idbodega = view.down('#bodegaId').getValue();
        var nombre = view.down('#nombreproductoId').getValue();
        var cantidad = view.down('#cantidadId').getValue();
        var cantidadori = view.down('#cantidadOriginalId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var porcentaje = view.down('#valorporId').getValue();       
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

        var view = this.getFormulaprincipal()
        var st = this.getFormulaStore()
        var opcion = view.down('#tipoSeleccionId').getValue()
        var nombre = view.down('#nombreId').getValue()
        st.proxy.extraParams = {nombre : nombre,
                                opcion : opcion}
    },

    
  
});










