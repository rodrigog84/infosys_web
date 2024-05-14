Ext.define('Infosys_web.view.Produccion.Solicitaproduccion', {
    extend: 'Ext.window.Window',
    alias : 'widget.solicitaproduccionformula',

    requires: [
        'Ext.form.FieldContainer',
        'Ext.button.Button',
        'Ext.form.field.Display',
        'Ext.form.field.ComboBox',
        'Ext.grid.Panel',
        'Ext.grid.column.Number',
        'Ext.grid.column.Date',
        'Ext.grid.column.Boolean',
        'Ext.grid.View',
        'Ext.toolbar.Toolbar',
        'Ext.toolbar.Fill',
        'Ext.form.field.Number',
        'Ext.toolbar.Separator'
    ],

    autoShow: true,
    height: 640,
    width: 1300,
    layout: 'fit',
    title: 'SOLICITA PRODUCCION',

    initComponent: function() {
        var me = this;
         var fomulastore = Ext.create('Ext.data.Store', {
            fields: ['id', 'nombre_formula', 'cantidad', 'texto'],
            proxy: {
              type: 'ajax',
                actionMethods:  {
                    read: 'POST'
                 },              
                url : preurl +'produccion/getFormulasporproducir',
                reader: {
                    type: 'json',
                    root: 'data'
                }
            },
            autoLoad: true            
        });



         var pedidosformula = Ext.create('Ext.data.Store', {
            fields: ['id', 'codigo', 'producto', 'num_pedido', 'nombre_cliente' ,'texto','stock','cantidad_disponible'],
            proxy: {
              type: 'ajax',
                actionMethods:  {
                    read: 'POST'
                 },              
                url : preurl +'produccion/getPedidosFormula',
                reader: {
                    type: 'json',
                    root: 'data'
                }
            },
            autoLoad: true            
        });    



       /* var fomulastore = Ext.create('Ext.data.Store', {
                    fields: ['id', 'nombre', 'saldo', 'documento'],
                    proxy: {
                      type: 'ajax',
                        actionMethods:  {
                            read: 'POST'
                         },              
                        url : preurl +'cuentacorriente/getDocumentosByCtacte',
                        reader: {
                            type: 'json',
                            root: 'data'
                        }
                    },
                    autoLoad: true            
                });           
    */

        //var stItms = Ext.getStore('PedidosFormula');
        //stItms.removeAll();
        Ext.applyIf(me, {
            items: [
                {
                xtype: 'container',
                margin: 8,
                layout: {
                    type: 'vbox',
                    align: 'stretch'
                },
                items: [{
                    xtype: 'container',
                    height: 110, //ALTO DE ENCABEZADO
                    layout: {
                        type: 'vbox',
                        align: 'stretch'
                    },
                    items: [{
                        xtype: 'fieldcontainer',
                        height: 30,
                        labelWidth: 120,
                        width: 462,
                        fieldLabel: '',
                        layout: {
                            type: 'hbox',
                            align: 'stretch'
                        },
                        items: [{
                                xtype: 'textfield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                width: 250,
                                labelWidth: 130,
                                name: 'num_pedido',
                                itemId: 'ticketId',
                                fieldLabel: '<b>NUMERO</b>',
                                readOnly: true
                            },{
                                xtype: 'displayfield',
                                width: 330
                               
                            },{
                                xtype: 'displayfield',
                                fieldLabel: '<b>AGRICOLA Y COMERCIAL LIRCAY SPA.</b>',
                                labelWidth: 520,
                                width: 520
                               
                            },{
                                xtype: 'datefield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                labelWidth: 50,
                                width: 170,
                                fieldLabel: '<b>FECHA</b>',
                                itemId: 'fechadocumId',
                                name: 'fecha_docum',
                                value: new Date()
                            }
                            ]
                        },{
                            xtype: 'fieldcontainer',
                            height: 30,
                            width: 300,

                            fieldLabel: '',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [{
                                    xtype: 'textfield',
                                    itemId: 'validaId',
                                    name : 'id',
                                    hidden: true
                                },{
                                    xtype: 'textfield',
                                    itemId: 'pedidoId',
                                    name : 'id',
                                    hidden: true
                                },{
                                       xtype: 'combobox',
                                       labelWidth: 200,
                                        queryMode: 'local',
                                        width: 900,
                                        fieldLabel: '<b>FORMULA</b>',
                                        store : fomulastore,
                                        displayField : 'texto',
                                        valueField : 'id',                                    
                                        emptyText : 'Seleccionar',
                                        editable: false,
                                        itemId : 'formulaid' ,
                                        name : 'formulaname' ,
                                        forceSelection: true,  
                                        listConfig: {
                                            minWidth: 280
                                        },
                                        listeners: {
                                            select: function (combo, records) {
                                                
                                                console.log(combo)
                                                console.log(records)
                                                var selectedRecord = records[0];
                                                var formula = selectedRecord.get('id')


                                                pedidosformula.proxy.extraParams = {
                                                                                        idformula : formula
                                                                                    }
                                                pedidosformula.load();    

                                            }
                                        },                                          
                                    },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                }
                            ]
                        },{
                            xtype: 'fieldcontainer',
                            height: 30,
                            width: 300,
                            fieldLabel: '',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [{
                                    xtype: 'textfield',
                                    itemId: 'validaId',
                                    name : 'id',
                                    hidden: true
                                },{
                                    xtype: 'textfield',
                                    itemId: 'pedidoId',
                                    name : 'id',
                                    hidden: true
                                },{
                                       xtype: 'combobox',
                                        queryMode: 'local',
                                        labelWidth: 200,
                                        width: 600,
                                        fieldLabel: '<b>PRODUCTOS PEDIDO</b>',
                                        store : pedidosformula,
                                        displayField : 'texto',
                                        valueField : 'id',                                    
                                        emptyText : 'Seleccionar',
                                        editable: false,
                                        itemId : 'productoid' ,
                                        name : 'productoname' ,
                                        forceSelection: true,  
                                        listConfig: {
                                            minWidth: 900,
                                            width: 900
                                        },
                                        listeners: {
                                            select: function (combo, records) {
                                                me.down('#stockId').setValue(records[0].data.stock)
                                                me.down('#cantpendienteId').setValue(records[0].data.cantidad_disponible)
                                                me.down('#cantpendienteId').setMaxValue(records[0].data.cantidad_disponible)


                                            }
                                        },                                          
                                    },{
                                        xtype: 'splitter'
                                        },{
                                    xtype: 'numberfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 90,
                                    maxHeight: 25,
                                    width: 210,
                                    fieldLabel: '<b>STOCK</b>',
                                    itemId: 'stockId',
                                    name : 'stock',
                                    readOnly: true                                         
                                },{
                                        xtype: 'splitter'
                                        },{
                                    xtype: 'numberfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 90,
                                    maxHeight: 25,
                                    width: 210,
                                    fieldLabel: '<b>CANTIDAD</b>',
                                    itemId: 'cantpendienteId',
                                    name : 'cantpendiente'                                         
                                },{
                                        xtype: 'splitter'
                                        },{
                                                xtype: 'button',
                                                text: 'Agregar',
                                                iconCls: 'icon-plus',
                                                width: 80,
                                                allowBlank: true,
                                                action: 'agregarDocumento',
                                                listeners: {
                                                    click: function (button, event, eOpts) {
                                                            var iddetalle = me.down('#productoid').getValue()
                                                            var idformula = me.down('#formulaid').getValue()

                                                              Ext.Ajax.request({
                                                                 //url: preurl + 'cuentacorriente/getCuentaCorriente/' + record.get('cuenta') + '/' + editor.value ,
                                                                 url: preurl + 'produccion/getPedidosFormula',
                                                                      params: {
                                                                          iddetalle: iddetalle,
                                                                          idformula: idformula
                                                                      },
                                                                 async: false,
                                                                 success: function(response, opts) {             
                                                                    
                                                                    var obj = Ext.decode(response.responseText);
                                                                    
                                                                    var iddetalle = obj.data[0].id;
                                                                    var codigodetalle = obj.data[0].codigo;
                                                                    var productodetalle = obj.data[0].producto;
                                                                    var num_pedidodetalle = obj.data[0].num_pedido;
                                                                    var nombre_clientedetalle = obj.data[0].nombre_cliente;
                                                                    var cantidad_disponibledetalle = obj.data[0].cantidad_disponible;
                                                                    var cantidad_totaldetalle = obj.data[0].cantidad_total;
                                                                    var stockdetalle = obj.data[0].stock;

                                                                    var cantproduccion = me.down('#cantpendienteId').getValue()
                                                                    var grid = me.down('#detalleProductosId');
                                                                    stItem = grid.getStore();
                                                                    var store = grid.getStore();


                                                                    var repetido = false


                                                                    if(cantproduccion > cantidad_disponibledetalle){

                                                                         Ext.Msg.alert('Alerta', 'Solicitud supera el máximo permitido');
                                                                    }else{

                                                                        stItem.each(function(r){
                                                                            //if(r.data.id_ctacte == ctacteid && r.data.id_documento == documentid && r.data.numcheque == numcheque){
                                                                            if(r.data.id == iddetalle){
                                                                                repetido = true
                                                                            }

                                                                            //console.log(r)
                                                                        });



                                                                        if(repetido){

                                                                            Ext.Msg.alert('Alerta', 'Producto ya ingresado');   
                                                                        }else{
                                                                            store.insert(store.count(), {id:iddetalle, codigo: codigodetalle, producto:productodetalle, num_pedido : num_pedidodetalle,nombre_cliente: nombre_clientedetalle, stock: stockdetalle, cantidad_disponible: cantproduccion, cantidad_total : cantidad_totaldetalle});
                                                                        }

                                                                        var griddetalle = me.down('#itemsgridId');
                                                                        stItemDetalle = griddetalle.getStore();
                                                                        var storeDetalle = griddetalle.getStore();   
                                                                        storeDetalle.removeAll()    


                                                                        //var porct_solicitud = (cantproduccion/cantidad_totaldetalle).toFixed(2); // Esto te dará el resultado con máximo 2 decimales

                                                                        
                                                                        stItem.each(function(r){
                                                                                var cantproduccion = r.data.cantidad_disponible;
                                                                                var cantidad_totaldetalle = r.data.cantidad_total;
                                                                                var porct_solicitud = (cantproduccion/cantidad_totaldetalle).toFixed(2); // Esto te dará el resultado con máximo 2 decimales
                                                                                console.log(porct_solicitud)


                                                                                iddetallelinea = r.data.id;

                                                                              Ext.Ajax.request({
                                                                                 url: preurl + 'produccion/getProductosFormulabyiddetalle',
                                                                                      params: {
                                                                                          iddetallelinea: iddetallelinea,
                                                                                          porct_solicitud : porct_solicitud
                                                                                      },
                                                                                 async: false,
                                                                                 success: function(response, opts) {
                                                                                        var objdetalle = Ext.decode(response.responseText);
                                                                                        //console.log(objdetalle.data)

                                                                                                                                                                     
                                                                                        objdetalle.data.forEach(function(r) {
                                                                                            //console.log(r);
                                                                                            var existereg = false;
                                                                                            storeDetalle.each(function(reg){
                                                                                                if(r.id_producto == reg.data.id_producto){
                                                                                                        existereg = true;
                                                                                                        r.cantidad += reg.data.cantidad;
                                                                                                        reg.set('cantidad', parseInt(reg.data.cantidad) + parseInt(r.cantidad)); 
                                                                                                        reg.commit();                                                                                                     

                                                                                                }
                                                                                                
                                                                                            });      
                                                                             
                                                                                            if(!existereg){
                                                                                                storeDetalle.insert(storeDetalle.count(), {id_producto:r.id_producto, codigo: r.codigo, nombre_producto:r.nombre_producto, id_bodega : r.id_bodega,valor_compra: r.valor_compra,cantidad: parseInt(r.cantidad),valor_produccion: r.valor_produccion, porcentaje: r.porcentaje});    
                                                                                            }
                                                                                            
                                                                                        });
                                                                                        //objdetalle.each(function(r){

                                                                                            //console.log(r)
                                                                                        //});

                                                                                 }
                                                                                })  



                                                                            
                                                                        });




                                                                    }






                                                                 }
                                                              });  




                                                    }
                                                }
                                        }



                                    ,{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                }
                            ]
                        },

                        ]
                        },

{
                            xtype: 'fieldcontainer',
                            height: 30,
                            width: 462,
                            fieldLabel: '',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [{
                                    xtype: 'timefield',
                                    fieldCls: 'required',
                                    format: 'H:i',
                                    msgTarget: 'side',
                                    labelWidth: 200,
                                    maxHeight: 25,
                                    width: 300,
                                    fieldLabel: '<b>HORA INICIO</b>',
                                    itemId: 'horainicioId',
                                    name : 'hora_inicio'                                         
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 155,
                                    maxHeight: 25,
                                    width: 450,
                                    fieldLabel: '<b>ENCARGADO</b>',
                                    itemId: 'encargadoId',
                                    name : 'nombre_encargado'                                         
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 60,
                                    maxHeight: 25,
                                    width: 180,
                                    fieldLabel: '<b>LOTE</b>',
                                    itemId: 'numLoteId',
                                    name : 'num_lote'                                         
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 60,
                                    maxHeight: 25,
                                    width: 180,
                                    fieldLabel: '<b>CICLOS</b>',
                                    itemId: 'ciclosId',
                                    name : 'ciclos'                                         
                                }
                            ]
                        },                        

{
                        xtype: 'fieldcontainer',
                        height: 400,
                        labelWidth: 120,
                        width: 1200,
                        fieldLabel: '',
                        layout: {
                            type: 'hbox',
                            align: 'stretch'
                        },
                        items: [


                                {
                                    xtype: 'grid',
                                    tbar: [],
                                    selModel: {
                                        selType: 'cellmodel'
                                    },
                                    itemId: 'detalleProductosId',
                                    width : 700,
                                    height: 400,
                                    store: Ext.create('Ext.data.Store', {
                                                            autoDestroy: true,
                                                            fields: ['id', 'codigo', 'producto', 'num_pedido', 'nombre_cliente','cantidad_disponible','cantidad_total'],
                                                            }),                                          
                                    columns: [

                                        { text: 'Id Detalle Producto',  dataIndex: 'id', width: 100, headerCfg: { style: 'font-size: 9px;' }, hidden : true },
                                        { text: 'Cod. Prod',  dataIndex: 'codigo', headerCfg: { style: 'font-size: 8px;' }, width: 120, hidden : true},
                                        { text: 'Prod',  dataIndex: 'producto', headerCfg: { style: 'font-size: 8px;' }, width: 200, renderer: function(value) {
                                                        return '<div style="font-size: 9px;">' + value + '</div>';
                                                    }},
                                        { text: '#. Pedido',  dataIndex: 'num_pedido', headerCfg: { style: 'font-size: 9px;' }, width: 90, renderer: function(value) {
                                                        return '<div style="font-size: 9px;">' + value + '</div>';
                                                    }},
                                        { text: 'Cliente',  dataIndex: 'nombre_cliente', headerCfg: { style: 'font-size: 8px;' }, width: 200, renderer: function(value) {
                                                        return '<div style="font-size: 9px;">' + value + '</div>';
                                                    }},
                                        { text: 'Cantidad',  dataIndex: 'cantidad_disponible', headerCfg: { style: 'font-size: 8px;' }, width: 150, renderer: function(value) {
                                                        return '<div style="font-size: 9px;">' + value + '</div>';
                                                    }},
                                        /*{
                                            header: "Cantidad Prod.",
                                            width: 150,
                                            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {

                                                        var cantidad_disponible = record.data.cantidad_disponible;
                                                        return Ext.String.format(
                                                            '<input type="number" id="cantprod_{0}" name="cantprod_{0}" style="width: 60%"  value="' + cantidad_disponible + '">',
                                                            record.data.id
                                                        );
                                                    },
                                            },*/
                                    {
                                        xtype: 'actioncolumn',
                                        width: 60,
                                        text: 'Elim.',
                                        align: 'center',
                                        items: [{
                                            icon: gbl_site + 'Infosys_web/resources/images/delete.png',
                                            // Use a URL in the icon config
                                            tooltip: 'Eliminar',
                                            handler: function (grid, rowIndex, colIndex) {
                                                var rec = grid.getStore().getAt(rowIndex);
                                                grid.getStore().remove(rec);

                                                /***** ACTUALIZACION SEGUNDA GRILLA ****/

                                                var griddetalle = me.down('#itemsgridId');
                                                stItemDetalle = griddetalle.getStore();
                                                var storeDetalle = griddetalle.getStore();   
                                                storeDetalle.removeAll()    

                                                stItem = grid.getStore();
                                                var store = grid.getStore();       
                                                console.log(stItem)
                                                stItem.each(function(r){
                                                        var cantproduccion = r.data.cantidad_disponible;
                                                        var cantidad_totaldetalle = r.data.cantidad_total;
                                                        var porct_solicitud = (cantproduccion/cantidad_totaldetalle).toFixed(2); // Esto te dará el resultado con máximo 2 decimales

                                                        console.log('---------------------------')
                                                        console.log(cantproduccion)
                                                        console.log(cantidad_totaldetalle)
                                                        console.log(porct_solicitud)

                                                        iddetallelinea = r.data.id;

                                                        Ext.Ajax.request({
                                                            url: preurl + 'produccion/getProductosFormulabyiddetalle',
                                                                                      params: {
                                                                                          iddetallelinea: iddetallelinea,
                                                                                          porct_solicitud : porct_solicitud
                                                                                      },
                                                            async: false,
                                                            success: function(response, opts) {
                                                                    var objdetalle = Ext.decode(response.responseText);
                                                                    //console.log(objdetalle.data)

                                                                                                                                                 
                                                                    objdetalle.data.forEach(function(r) {
                                                                        //console.log(r);
                                                                        var existereg = false;
                                                                        storeDetalle.each(function(reg){
                                                                            if(r.id_producto == reg.data.id_producto){
                                                                                    existereg = true;
                                                                                    r.cantidad += reg.data.cantidad;
                                                                                    reg.set('cantidad', parseInt(reg.data.cantidad) + parseInt(r.cantidad)); 
                                                                                    reg.commit();                                                                                                     

                                                                            }
                                                                            
                                                                        });      
                                                         
                                                                        if(!existereg){
                                                                            storeDetalle.insert(storeDetalle.count(), {id_producto:r.id_producto, codigo: r.codigo, nombre_producto:r.nombre_producto, id_bodega : r.id_bodega,valor_compra: r.valor_compra,cantidad: parseInt(r.cantidad),valor_produccion: r.valor_produccion, porcentaje: r.porcentaje});    
                                                                        }
                                                                        
                                                                    });
                                                                    //objdetalle.each(function(r){

                                                                        //console.log(r)
                                                                    //});

                                                                
                                                            }

                                                        });
                                                });



                                                /*var store = grid.getStore();
                                                var total_cancelacion = 0;
                                                store.each(function(r){
                                                    total_cancelacion +=  parseInt(r.data.monto);
                                                });     
                                                total_format = Ext.util.Format.number(parseInt(total_cancelacion),"0,000")
                                                me.down('#ingresoDetalleCancelacionId').setTitle("Cancelacion Cuenta Corriente. Total Cancelacion: $ " + total_format);       
                                                */
                                            }
                                        }]
                                    }

                                    ]
                                },
                                {
                                    xtype: 'dis,ayfield',
                                    width: 10                                   
                                },
                                {
                                    xtype: 'grid',
                                    tbar: [],
                                    selModel: {
                                        selType: 'cellmodel'
                                    },
                                    itemId: 'itemsgridId',
                                    width : 500,
                                    height: 400,
                                   // store: 'PedidosFormula',      
                                    store: Ext.create('Ext.data.Store', {
                                                            autoDestroy: true,
                                                            fields: ['id_producto', 'codigo', 'nombre_producto', 'id_bodega', 'valor_compra','cantidad','valor_produccion','porcentaje'],
                                                            }),                                                                           
                                    columns: [
                                            { text: 'Id producto',  dataIndex: 'id_producto', width: 250, hidden : true },
                                            { text: 'codigo',  dataIndex: 'codigo', width: 150, hidden : true },
                                            { text: 'Producto',  dataIndex: 'nombre_producto', width: 320},
                                            { text: 'Bodega',  dataIndex: 'id_bodega', width: 250, hidden:true},
                                            { text: '($) Compra',  dataIndex: 'valor_compra', align: 'right',width: 90, decimalPrecision:3,hidden:true},
                                            { text: 'Cantidad',  dataIndex: 'cantidad', align: 'right',width: 90, decimalPrecision:3, renderer: function(value) {
                                                        return '<div style="font-size: 11px;">' + value + '</div>';
                                                    }},
                                            { text: '($) Produccion',  dataIndex: 'valor_produccion', align: 'right',width: 100, hidden:true },
                                            { text: 'Porc.',  dataIndex: 'porcentaje', align: 'right',width: 90 , renderer: function(value) {
                                                        return '<div style="font-size: 11px;">' + value + '</div>';
                                                    }},
                                            
                                            ]
                                }



                            ]
                        }                     

                    ]
                }
            ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    layout: {
                        type: 'hbox',
                        align: 'middle',
                        pack: 'center'
                    },
                    items: ['->',
                        {
                            xtype: 'button',
                            //iconCls: 'icono',
                            scale: 'large',
                            action: 'observaciones',
                            text: 'OBSERVACIONES',
                            hidden: true
                        },{
                            xtype: 'button',
                            iconCls: 'icon-save',
                            scale: 'large',
                            action: 'grabarsolicitaproduccion',
                            itemId: 'grabarproduccion5',
                            disabled : false,  
                            text: 'Grabar / Emitir'
                        },
                        {
                            xtype: 'tbseparator'
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
        
    }

});
