Ext.define('Infosys_web.controller.Produccion', {
    extend: 'Ext.app.Controller',

    //asociamos vistas, models y stores al controller

    models: ['Pedidos',
             'Formulas',
             'pedidos.valida',
             'valida',
             'Produccion',
             'Produccion.Item'
             ],

    stores: ['Pedidos',
             'Produccion',
             'PedidosProduccion',
             'PedidosFormula',
             'Valida',
             'ProduccionTermino',
             'Bodegas'
             ],
    
    views: ['Produccion.Principal',
            'Produccion.Produccion',
            'Produccion.BuscarPedidos',
            'Produccion.ValidaStock',
            'Produccion.ProduccionTermino'
            ],

    //referencias, es un alias interno para el controller
    //podemos dejar el alias de la vista en el ref y en el selector
    //tambien, asi evitamos enredarnos
    refs: [{    
       ref: 'produccionprincipal',
        selector: 'produccionprincipal'
    },{
        ref: 'topmenus',
        selector: 'topmenus'
    },{    
        ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{    
        ref: 'produccioningresar',
        selector: 'produccioningresar'
    },{    
        ref: 'buscarpedidosproduccion',
        selector: 'buscarpedidosproduccion'
    },{    
        ref: 'validastock',
        selector: 'validastock'
    },{    
        ref: 'producciontermino',
        selector: 'producciontermino'
    }
    ],
    
    init: function() {
    	
        this.control({
            'topmenus menuitem[action=mProduccion]': {
                click: this.mProduccion
            },
            'produccionprincipal button[action=cerrarproduccion]': {
                click: this.cerrarproduccion
            },
            'produccionprincipal button[action=terminoproduccion]': {
                click: this.terminoproduccion
            },
            'produccionprincipal button[action=generaproduccion]': {
                click: this.generaproduccion
            },
            'produccioningresar button[action=buscarpedidopro]': {
                click: this.buscarpedidopro
            },
            'buscarpedidosproduccion button[action=buscarclienteproduccion]': {
                click: this.buscarclienteproduccion
            },
            'buscarpedidosproduccion button[action=seleccionarpedidoproduccion]': {
                click: this.seleccionarpedidoproduccion
            },
            'produccioningresar button[action=grabarproduccion]': {
                click: this.grabarproduccion
            },
            'validastock button[action=Salir]': {
                click: this.Salir
            },  
            'producciontermino button[action=Salir]': {
                click: this.Salir
            },
            'producciontermino button[action=editaritem]': {
                click: this.editaritem
            },
            'producciontermino button[action=agregarItem]': {
                click: this.agregarItem
            },
            'producciontermino button[action=grabarproduccion2]': {
                click: this.grabarproduccion2
            },
            'produccionprincipal button[action=exportarpedidos]': {
                click: this.exportarpedidos
            },          
        });
    },

    exportarpedidos: function(){

        var view = this.getProduccionprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var estado = row.data.estado;
            if (estado == 2){
                window.open(preurl +'produccion/exportPDF2/?idproduccion=' + row.data.id)
                
            }else{
                window.open(preurl +'produccion/exportPDF/?idproduccion=' + row.data.id)
                
            }
            
        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
        
    },


    grabarproduccion2: function(){

        var view = this.getProducciontermino();
        var fechaproduccion = view.down('#fechadocumId').getValue();
        var cantidadproduccion = view.down('#cantidadproducId').getValue();
        var idcliente = view.down('#id_cliente').getValue();
        var idproducto = view.down('#productoId').getValue();
        var numproduccion = view.down('#ticketId').getValue();
        var idproduccion = view.down('#idId').getValue();
        var idpedido = view.down('#pedidoId').getValue();
        var horatermino = view.down('#horaterminoId').getValue();
        var bodega = view.down('#bodegaId').getValue();
        var stItem = this.getProduccionTerminoStore();
        var stProduccion = this.getProduccionStore();

        if(!horatermino){
             Ext.Msg.alert('Debe Ingresar Hora Termino');
            return; 
            
        }

        if(!bodega){
             Ext.Msg.alert('Debe Asignar Bodega');
            return; 
            
        }

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'produccion/save2',
            params: {
                fechaproduccion: Ext.Date.format(fechaproduccion,'Y-m-d'),
                cantidadproduccion: cantidadproduccion,
                idproducto: idproducto,
                idbodega: bodega,
                idpedido: idpedido,
                idcliente: idcliente,
                numproduccion: numproduccion,
                idproduccion: idproduccion,
                horatermino: Ext.Date.format(horatermino,'H:i'),
                items: Ext.JSON.encode(dataItems),
            },
             success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 var idproduccion= resp.idproduccion;
                 view.close();
                 stProduccion.load();
                 window.open(preurl + 'produccion/exportPDF2/?idproduccion='+idproduccion);
            }
           
        });
        
               
    },

    agregarItem: function() {

        var view = this.getProducciontermino();
        var tipo_documento = view.down('#tipoDocumentoId');
        var stItem = this.getProduccionTerminoStore();
        var producto = view.down('#productoforId').getValue();
        var codigo = view.down('#codigoId').getValue();
        var nombre = view.down('#nombreproductoforId').getValue();
        var cantidad = view.down('#cantidadId').getValue();        
        var cantidad_pro = view.down('#cantidadoproId').getValue();
        var cantidadori = view.down('#cantidadoriId').getValue();
        var precio = ((view.down('#precioId').getValue()));
        var porcentaje = view.down('#valorporId').getValue(); 
        var porcentaje_pro = view.down('#valorporproId').getValue(); 
        var exists = 0;

       
        if(!producto){            
            Ext.Msg.alert('Alerta', 'Debe Seleccionar un Producto');
            return false;
        }
        if(precio==0){
            Ext.Msg.alert('Alerta', 'Debe Ingresar Precio Producto');
            return false;
        } 
         
       
        var porcentaje_pro =  ((cantidad_pro / cantidad)*100);      
       

        stItem.each(function(r){
            if(r.data.id == producto){
                Ext.Msg.alert('Alerta', 'El registro ya existe.');
                exists = 1;
                cero="";
                view.down('#codigoId').setValue(cero);
                view.down('#productoId').setValue(cero);
                view.down('#nombreproductoforId').setValue(cero);
                view.down('#precioId').setValue(cero);
                view.down('#valorporproId').setValue(cero);
                view.down('#valorporId').setValue(cero);
                view.down('#cantidadoproId').setValue(cero);
                view.down('#cantidadoriId').setValue(cero);
        
                return; 
            }
        });
        if(exists == 1)
            return;
                
        stItem.add(new Infosys_web.model.Produccion.Item({
            id_producto: producto,
            codigo: codigo,
            nom_producto: nombre,
            valor_compra: precio,
            cantidad: cantidadori,
            cantidad_pro: cantidad_pro,
            valor_producion: precio,
            porcentaje_pro: porcentaje_pro,
            porcentaje: porcentaje
        }));
        
        cero="";
        cero1=0;
        cero2=0;
        view.down('#codigoId').setValue(cero);
        view.down('#productoId').setValue(cero);
        view.down('#nombreproductoforId').setValue(cero);
        view.down('#precioId').setValue(cero);
        view.down('#valorporproId').setValue(cero);
        view.down('#valorporId').setValue(cero);
        view.down('#cantidadoproId').setValue(cero);
        view.down('#cantidadoriId').setValue(cero);
        this.recalcular();
        
    },

    recalcular: function() {

        var view = this.getProducciontermino();
        var stItem = this.getProduccionTerminoStore();
        var pretotal = 0;
        var total = 0;
        
        stItem.each(function(r){
            pretotal = (pretotal + parseInt(r.data.cantidad_pro))
            console.log(pretotal)
          
        });
        total = pretotal;
             
        view.down('#cantidadproducId').setValue(total);

    },

    editaritem: function() {
        var view = this.getProducciontermino();
        var cantidadformdet = view.down('#cantidadId').getValue();
        var grid  = view.down('#itemsgridId');
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_producto = row.data.id_producto;
            var valorcompra = row.data.valor_compra;
            var cantidad = row.data.cantidad;
            var cantidad_pro = row.data.cantidad_pro;
            var porcentaje = row.data.porcentaje;
            var porcentaje_pro = row.data.porcentaje_pro;
            var cantidaddet = (cantidadformdet - cantidad );
            var nombre = row.data.nom_producto;
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
                        view.down('#productoforId').setValue(id_producto);
                        view.down('#nombreproductoforId').setValue(nombre);                        
                        view.down('#codigoId').setValue(cliente.codigo);
                        view.down('#valorporId').setValue(porcentaje);
                        view.down('#valorporproId').setValue(porcentaje_pro);
                        view.down('#cantidadoriId').setValue(cantidad);
                        view.down('#cantidadoproId').setValue(cantidad_pro);           
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

   
    terminoproduccion: function(){
              
        var stItms = Ext.getStore('ProduccionTermino');
        stItms.removeAll();
       
        var view = this.getProduccionprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var stItem = this.getProduccionTerminoStore();
            var idproduccion = row.data.id;
            var estado = row.data.estado;

            if(estado=="2"){
                Ext.Msg.alert('Produccion Realizada');
            return;               

            }else{
            stItem.proxy.extraParams = {idproduccion : idproduccion};
            stItem.load();
            
            Ext.Ajax.request({
            url: preurl +'produccion/termino/?idproduccion=' + row.data.id,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);
                if (resp.success == true) {                    
                    var cliente = resp.cliente; 
                    var view = Ext.create('Infosys_web.view.Produccion.ProduccionTermino').show();                   
                    view.down("#ticketId").setValue(cliente.num_produccion);
                    view.down("#idId").setValue(idproduccion);                    
                    view.down("#npedidoId").setValue(cliente.num_pedido);
                    view.down("#horainicioId").setValue(cliente.hora_inicio);
                    view.down("#pedidoId").setValue(cliente.id_pedido);
                    view.down("#numLoteId").setValue(cliente.lote);
                    view.down("#rutId").setValue(cliente.rut_cliente);                                       
                    view.down("#id_cliente").setValue(cliente.id_cliente);
                    view.down("#nombre_id").setValue(cliente.nom_cliente);
                    view.down("#nombreformulaId").setValue(cliente.nom_formula);
                    view.down("#cantidadId").setValue(cliente.cantidad);
                    view.down("#cantidadproducId").setValue(cliente.cantidad);
                    view.down("#formulaId").setValue(cliente.id_formula_pedido);
                    view.down("#productoId").setValue(cliente.id_producto);
                    view.down("#nombreproductoId").setValue(cliente.nom_producto);
                    view.down("#encargadoId").setValue(cliente.encargado);
                    
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

    Salir : function(){

        var view = this.getValidastock();
        var viewnew = this.getProduccioningresar();
        view.close();
        viewnew.close();

        
    },

    grabarproduccion: function(){

        var view = this.getProduccioningresar();
        var numproduccion = view.down('#ticketId').getValue();
        var fechaproduccion = view.down('#fechadocumId').getValue();
        var idpedido = view.down('#pedidoId').getValue();
        var numpedido = view.down('#npedidoId').getValue();
        var idcliente = view.down('#id_cliente').getValue();
        var idformula = view.down('#formulaId').getValue();
        var nombreformula = view.down('#nombreformulaId').getValue();
        var cantidadproduccion = view.down('#cantidadId').getValue();
        var lote = view.down('#numLoteId').getValue();
        var nombreproducto = view.down('#nombreproductoId').getValue();
        var idproducto = view.down('#productoId').getValue();
        var horainicio = view.down('#horainicioId').getValue();
        var encargado = view.down('#encargadoId').getValue();
        var stItem = this.getPedidosFormulaStore();
        var stProduccion = this.getProduccionStore();

        if(!encargado){
             Ext.Msg.alert('Debe Asignar Encargado');
            return; 
            
        }

        if(!lote){
             Ext.Msg.alert('Debe Asignar Lote');
            return; 
            
        }

        if(!numpedido){
             Ext.Msg.alert('Debe Asignar Pedido');
            return; 
            
        }

        var dataItems = new Array();
        stItem.each(function(r){
            dataItems.push(r.data)
        });

        Ext.Ajax.request({
            url: preurl + 'produccion/save',
            params: {
                numproduccion: numproduccion,
                fechaproduccion: Ext.Date.format(fechaproduccion,'Y-m-d'),
                idpedido: idpedido,
                numpedido: numpedido,            
                idcliente: idcliente,
                idformula: idformula,
                nombreformula: nombreformula,
                cantidadproduccion: cantidadproduccion,
                lote: lote,
                nombreproducto: nombreproducto,
                idproducto: idproducto,
                horainicio: Ext.Date.format(horainicio,'H:i'),
                encargado: encargado,                
                items: Ext.JSON.encode(dataItems),
            },
             success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 var idproduccion= resp.idproduccion;
                 view.close();
                 stProduccion.load();
                 window.open(preurl + 'produccion/exportPDF/?idproduccion='+idproduccion);
            }
           
        });
        
               
    },

    seleccionarpedidoproduccion: function(){
        
        var view = this.getBuscarpedidosproduccion();
        var viewIngresa = this.getProduccioningresar();
        var grid  = view.down('grid');
        var validar="SI";
        if (grid.getSelectionModel().hasSelection()) {
            var row = grid.getSelectionModel().getSelection()[0];
            var id_pedido = row.data.id;
            viewIngresa.down('#pedidoId').setValue(row.data.id);
            viewIngresa.down('#npedidoId').setValue(row.data.num_pedido);
            viewIngresa.down('#rutId').setValue(row.data.rut_cliente);
            viewIngresa.down('#id_cliente').setValue(row.data.id_cliente);
            viewIngresa.down('#nombre_id').setValue(row.data.nombre_cliente);
            viewIngresa.down('#nombreformulaId').setValue(row.data.nom_formula);
            viewIngresa.down('#formulaId').setValue(row.data.id_formula);
            viewIngresa.down('#cantidadId').setValue(row.data.cantidad);
            viewIngresa.down('#nombreproductoId').setValue(row.data.nom_producto);
            viewIngresa.down('#productoId').setValue(row.data.id_producto);
            view.close();
            var st = this.getPedidosFormulaStore()
            st.proxy.extraParams = {pedido : id_pedido}
            st.load();
            this.validaStock();       

        }else{
            Ext.Msg.alert('Alerta', 'Selecciona un registro.');
            return;
        }
    },

    validaStock: function(){

        var view = this.getProduccioningresar();
        var idpedido = view.down('#pedidoId').getValue();
        var sta = this.getValidaStore();
        sta.proxy.extraParams = {pedido : idpedido};
        sta.load();  
        Ext.Ajax.request({
        url: preurl + 'pedidos/validaformula?pedido='+idpedido,
        params: {
            id: 1
        },
        success: function(response){
            var resp = Ext.JSON.decode(response.responseText);
            if (resp.success == true) {
                Ext.create('Infosys_web.view.Produccion.ValidaStock').show();
                                             
            };
            }            
        });
       
    },

    buscarpedidopro: function(){

        var st = this.getPedidosProduccionStore();
        st.load();                
        Ext.create('Infosys_web.view.Produccion.BuscarPedidos').show();            
      
        
    },

    generaproduccion: function(){

         var viewIngresa = this.getProduccionprincipal();
         var nombre = "23";    
         Ext.Ajax.request({

            url: preurl + 'correlativos/genera?valida='+nombre,
            params: {
                id: 1
            },
            success: function(response){
                var resp = Ext.JSON.decode(response.responseText);

                if (resp.success == true) {
                    var view = Ext.create('Infosys_web.view.Produccion.Produccion').show();                   
                    var cliente = resp.cliente;
                    var correlanue = cliente.correlativo;
                    correlanue = (parseInt(correlanue)+1);
                    var correlanue = correlanue;
                    view.down("#ticketId").setValue(correlanue);
                }else{
                    Ext.Msg.alert('Correlativo YA Existe');
                    return;
                }
            }            
        });
        
         
       
    },
    
    mProduccion: function(){       
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
        viewport.add({xtype: 'produccionprincipal'});
    },

    
    cerrarproduccion: function(){
        var viewport = this.getPanelprincipal();
        viewport.removeAll();
     
    },
  
});










