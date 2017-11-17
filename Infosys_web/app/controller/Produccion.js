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
             'ProduccionTermino'
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
        });
    },

    terminoproduccion: function(){
              
        var stItms = Ext.getStore('ProduccionTermino');
        stItms.removeAll();
       
        var view = this.getProduccionprincipal();
        if (view.getSelectionModel().hasSelection()) {
            var row = view.getSelectionModel().getSelection()[0];
            var view = this.getEditarpedidos();
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
                    var view = Ext.create('Infosys_web.view.Pedidos.Editarpedidos').show();                   
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
                horainicio: horainicio,
                encargado: encargado,                
                items: Ext.JSON.encode(dataItems),
            },
             success: function(response){
                 var resp = Ext.JSON.decode(response.responseText);
                 //var idproduccion= resp.idproduccion;
                 view.close();
                 stProduccion.load();
                 //window.open(preurl + 'produccion/exportPDF/?idproduccion='+idpedidos);
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










