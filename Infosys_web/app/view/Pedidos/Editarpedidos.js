Ext.define('Infosys_web.view.Pedidos.Editarpedidos', {
    extend: 'Ext.window.Window',
    alias : 'widget.editarpedidos',

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
    title: 'Editar Pedido',

    initComponent: function() {
        var me = this;
        var stItms = Ext.getStore('Pedidos.Items');
        stItms.removeAll();
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
                    height: 200,
                    layout: {
                        type: 'vbox',
                        align: 'stretch'
                    },
                    items: [{
                        xtype: 'fieldcontainer',
                        height: 37,
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
                                labelWidth: 150,
                                name: 'id',
                                itemId: 'idId',
                                fieldLabel: '<b>id</b>',
                                hidden: true
                            },{
                                xtype: 'textfield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                width: 250,
                                labelWidth: 150,
                                name: 'num_pedido',
                                itemId: 'ticketId',
                                fieldLabel: '<b>NUMERO PEDIDO</b>',
                                readOnly: true
                            },{
                                xtype: 'displayfield',
                                width: 15
                               
                            },{
                                xtype: 'combo',
                                align: 'center',
                                width: 450,
                                maxHeight: 25,
                                matchFieldWidth: false,
                                listConfig: {
                                    width: 350
                                },
                                itemId: 'tipoDocumentoId',
                                fieldLabel: '<b>DOCUMENTO</b>',
                                fieldCls: 'required',
                                store: 'Tipo_documento.Selector',
                                valueField: 'id',
                                displayField: 'nombre'
                            },{
                                xtype: 'displayfield',
                                width: 145
                               
                            },{
                                xtype: 'datefield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                labelWidth: 50,
                                width: 170,
                                fieldLabel: '<b>FECHA</b>',
                                itemId: 'fechapedidoId',
                                name: 'fecha_pedido',
                                value: new Date()
                            },{
                                xtype: 'displayfield',
                                width: 10
                               
                            },{
                                xtype      : 'timefield',
                                fieldCls: 'required',
                                increment  : 30,
                                format     : 'H:i',
                                value: Ext.Date.format(new Date(),'H:i'),
                                maxHeight: 25,
                                width: 200,
                                itemId: 'horapedidoId',
                                name : 'hora_pedido',
                                fieldLabel: '<b>HORA PEDIDO</b>'
                            }
                            ]
                        },{
                            xtype: 'fieldcontainer',
                            height: 35,
                            width: 462,
                            fieldLabel: '',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [{
                                    xtype: 'textfield',
                                    itemId: 'id_cliente',
                                    name : 'id',
                                    hidden: true
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    maxHeight: 25,
                                    width: 220,
                                    fieldLabel: '<b>RUT</b>',
                                    itemId: 'rutId',
                                    name : 'rut'                                         
                                },{
                                    xtype: 'displayfield',
                                    width: 10
                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    fieldLabel: '<b>RAZON SOCIAL</b>',
                                    maxHeight: 25,
                                    labelWidth: 125,
                                    width: 735,
                                    itemId: 'nombre_id',
                                    name : 'nombre',
                                    readOnly: true                                    
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    fieldLabel: '<b>TELEFONO</b>',
                                    maxHeight: 25,
                                    labelWidth: 80,
                                    width: 200,
                                    itemId: 'TelefonoId',
                                    name : 'telefono'                             
                                }
                            ]
                        },{
                            xtype: 'fieldcontainer',
                            height: 30,
                            width: 462,
                            fieldLabel: '',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [
                             {
                                xtype: 'textfield',
                                fieldLabel: '<b>ID</b>',
                                fieldCls: 'required',
                                maxHeight: 25,
                                width: 580,
                                itemId: 'id_sucursalID',
                                name : 'id_sucursal',
                                hidden: true
                            },{
                                xtype: 'textfield',
                                itemId: 'obsId',
                                name : 'idobserva',
                                fieldLabel: 'Id Observacion',
                                hidden: true
                            },{
                                xtype: 'textfield',
                                fieldLabel: '<b>DIRECCION</b>',
                                fieldCls: 'required',
                                maxHeight: 25,
                                width: 580,
                                itemId: 'direccionId',
                                name : 'direccion',                                         
                                readOnly: true,
                                hidden: true
                            },{xtype: 'splitter'},{
                                xtype: 'button',
                                text: 'Sucursal',
                                itemId: 'sucursalId',
                                maxHeight: 25,
                                width: 70,
                                action: 'buscarsucursalfactura',
                                hidden: true
                            },{
                                xtype: 'displayfield',
                                width: 10                                           
                            },{
                                xtype: 'combo',
                                itemId: 'tipoVendedorId',
                                width: 350,
                                fieldCls: 'required',
                                maxHeight: 25,
                                fieldLabel: '<b>VENDEDOR</b>',
                                forceSelection : true,
                                name : 'id_vendedor',
                                valueField : 'id',
                                displayField : 'nombre',
                                emptyText : "Seleccione",
                                store : 'Vendedores'
                            },{
                                xtype: 'displayfield',
                                width: 10                                           
                            },{
                                xtype: 'textfield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                width: 100,
                                name: 'id_lista',
                                value: "NO",
                                itemId: 'preciosId',
                                fieldLabel: '<b>Lista Precios</b>',
                                hidden: true
                            },{
                                xtype: 'combo',
                                itemId: 'bodegaId',
                                labelWidth: 60,
                                width: 205,
                                fieldCls: 'required',
                                maxHeight: 25,
                                fieldLabel: '<b>BODEGA</b>',
                                forceSelection : true,
                                name : 'id_bodega',
                                valueField : 'id',
                                displayField : 'nombre',
                                emptyText : "Seleccione",
                                store : 'Bodegas',
                                readOnly: true
                            },{
                                xtype: 'displayfield',
                                width: 10                                           
                            },{
                                xtype: 'datefield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                labelWidth: 150,
                                width: 270,
                                fieldLabel: '<b>FECHA ELABORACION</b>',
                                itemId: 'fechaelaboraId',
                                name: 'fecha_elaboracion',
                                value: new Date()
                            },{
                                xtype: 'displayfield',
                                width: 10                                           
                            },{
                                xtype: 'combo',                                
                                labelWidth: 40,
                                width: 160,
                                maxHeight: 25,
                                itemId: 'horaelaId',
                                fieldLabel: 'Hora',
                                forceSelection : true,
                                valueField : 'id',
                                displayField : 'nombre',
                                emptyText : "Seleccione",
                                store : 'Pedidos.Selector2'
                            }
                            ]
                        },{
                            xtype: 'fieldcontainer',
                            height: 30,
                            width: 462,
                            fieldLabel: '',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [
                            {
                                xtype: 'combo',
                                itemId: 'tipoPedidoId',
                                width: 250,
                                fieldCls: 'required',
                                maxHeight: 25,
                                fieldLabel: '<b>TIPO PEDIDO</b>',
                                forceSelection : true,
                                name : 'tipopedido',
                                valueField : 'id',
                                displayField : 'nombre',
                                emptyText : "Seleccione",
                                store : 'Pedidos.Selector'
                            },{
                                xtype: 'displayfield',
                                flex: 1,
                                maxWidth: 35,
                                labelWidth: 50
                            },{
                                xtype: 'datefield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                labelWidth: 130,
                                width: 240,
                                fieldLabel: '<b>FECHA DESPACHO</b>',
                                itemId: 'fechadespachoId',
                                name: 'fecha_factura',
                                value: new Date()
                            },{
                                xtype: 'displayfield',
                                flex: 1,
                                maxWidth: 25,
                                labelWidth: 20
                            },{
                                xtype      : 'timefield',
                                fieldCls: 'required',
                                increment  : 30,
                                format     : 'H:i',
                                value: Ext.Date.format(new Date(),'H:i'),
                                maxHeight: 25,
                                labelWidth: 120,
                                width: 200,
                                itemId: 'horadespachoId',
                                name : 'hora_despacho',
                                fieldLabel: '<b>HORA DESPACHO</b>'
                            },{
                                xtype: 'displayfield',
                                width: 15
                            },{
                                xtype: 'combo',
                                itemId: 'tipocondpagoId',
                                width: 330,
                                fieldCls: 'required',
                                maxHeight: 25,
                                fieldLabel: '<b>COND.PAGO</b>',
                                forceSelection : true,
                                name : 'id_condpago',
                                valueField : 'id',
                                displayField : 'nombre',
                                emptyText : "Seleccione",
                                store : 'Cond_pago'
                            }
                            ]
                    },{
                    xtype: 'fieldset',
                    title: 'Items Documento',
                    fieldDefaults: {
                        labelWidth: 70,
                        align: 'center'                        
                    },
                    items: [
                    {
                        xtype: 'container',
                        layout: {
                            type: 'vbox'
                        },
                        defaults: {
                            flex: 1
                        },
                        items: [

                        {
                            xtype: 'fieldcontainer',
                            layout: 'hbox',
                            align: 'center',     
                            items: [{
                                xtype: 'textfield',
                                width: 140,
                                labelWidth: 40,
                                fieldLabel: 'Codigo',
                                itemId: 'codigoId',
                                style: 'font-weight: bold;'
                            }, {xtype: 'splitter'},{
                                xtype: 'textfield',
                                align: 'center',
                                labelWidth: 55,
                                itemId: 'nombreproductoId',
                                fieldLabel: 'Producto',
                                name: 'nomproducto',                                
                                readOnly: true
                            },{
                                xtype: 'textfield',
                                align: 'center',
                                labelWidth: 60,
                                itemId: 'productoId',
                                fieldLabel: 'Producto',
                                name: 'Productos',                                
                                hidden: true
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'button',
                                text: 'Buscar Producto',
                                maxHeight: 25,
                                width: 120,
                                allowBlank: true,
                                action: 'buscarproductos2',
                                itemId: 'buscarproc'
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 180,
                                labelWidth: 40,
                                fieldLabel: 'Precio',
                                itemId: 'precioId',
                                style: 'font-weight: bold;'
                            },{xtype: 'splitter'},
                            {
                                xtype: 'textfield',
                                width: 120,
                                labelWidth: 40,
                                minValue: 0,
                                fieldLabel: 'Stock',
                                readOnly: true,
                                itemId: 'cantidadOriginalId',
                                style: 'font-weight: bold;'

                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 160,
                                labelWidth: 60,
                                minValue: 0,
                                value: 1,
                                fieldLabel: 'Cantidad',
                                itemId: 'cantidadId'
                            },{
                                xtype: 'numberfield',
                                width: 120,
                                labelWidth: 60,
                                minValue: 0,
                                value: 0,
                                fieldLabel: 'Descuento Pro',
                                itemId: 'totdescuentoId',
                                hidden: true
                            },
                            {xtype: 'splitter'},
                            {
                            xtype: 'combo',
                            width: 190,
                            queryMode: 'local',
                            itemId: 'DescuentoproId',
                            fieldLabel: 'Descto %',
                            store: 'Tabladescuento',
                            emptyText : "Seleccione",
                            valueField: 'id',
                            displayField: 'nombre'
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'button',
                                text: 'Agregar',
                                iconCls: 'icon-plus',
                                width: 80,
                                allowBlank: true,
                                action: 'agregarItem2'
                            }]
                        }

                        ]
                    }]

                     }

                            ]
                        },{
                            xtype: 'grid',
                            itemId: 'itemsgridId',
                            title: 'Detalle',
                            labelWidth: 50,
                            store: 'Pedidos.Editar',
                            tbar: [{
                                iconCls: 'icon-delete',
                                text: 'Eliminar',
                                action: 'eliminaritem2'
                            },
                            {
                                iconCls: 'icon-delete',
                                text: 'Editar',
                                action: 'editaritem2'
                            }
                            ],
                            height: 210,
                            columns: [
                                { text: 'Id producto',  dataIndex: 'id_producto', width: 250, hidden : true },
                                { text: 'Id descuento',  dataIndex: 'id_descuento', width: 250, hidden : true },
                                { text: 'codigo',  dataIndex: 'codigo', width: 250, hidden : true },
                                { text: 'Producto',  dataIndex: 'nom_producto', width: 250 },
                                { text: 'Bodega',  dataIndex: 'id_bodega', width: 250, hidden:true},
                                { text: 'Precio Unitario',  dataIndex: 'precio', align: 'right',flex:1, decimalPrecision:3},
                                { text: 'Cantidad',  dataIndex: 'cantidad', align: 'right',width: 150, decimalPrecision:3},
                                { text: 'Descuento',  dataIndex: 'descuento', align: 'right',width: 100, renderer: function(valor){return Ext.util.Format.number((valor),"0,000")} },
                                { text: 'Neto',  dataIndex: 'neto', align: 'right',flex:1, decimalPrecision:3},
                                { text: 'Iva',  dataIndex: 'iva', align: 'right',flex:1,renderer: function(valor){return Ext.util.Format.number((valor),"0,000")} },
                                { text: 'Total',  dataIndex: 'total', align: 'right',flex:1, renderer: function(valor){return Ext.util.Format.number((valor),"0,000")} }
                                ]
                            },{
                        xtype: 'fieldset',
                        title: 'Total Documento',
                        fieldDefaults: {
                        labelWidth: 120
                        },
                        items: [
                            {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        items: [{
                            xtype: 'numberfield',
                            fieldCls: 'required',
                            width: 200,
                            name : 'neto',
                            itemId: 'finaltotalnetoId',
                            readOnly: true,
                            fieldLabel: '<b>VALOR NETO</b>',
                            labelAlign: 'top'
                        },
                        {xtype: 'splitter'},
                        {
                            xtype: 'combo',
                            width: 280,
                            queryMode: 'local',
                            itemId: 'tipoDescuentoId',
                            fieldLabel: '<b>DESCUENTO</b>',
                            store: 'Tabladescuento',
                            emptyText : "Seleccione",
                            valueField: 'id',
                            disabled : true,   
                            labelAlign: 'top',
                            displayField: 'nombre'
                        },{
                            xtype: 'numberfield',
                            fieldCls: 'required',
                            width: 200,
                            name : 'descuento',
                            itemId: 'finaldescuentoId',
                            disabled : true,  
                            readOnly: true,
                            fieldLabel: '<b>descuento</b>',
                            hidden: true
                        },
                        {xtype: 'splitter'},
                        {
                            xtype: 'numberfield',
                            fieldCls: 'required',
                            width: 150,
                            name : 'descuentovalor',
                            itemId: 'descuentovalorId',
                            readOnly: true,
                            fieldLabel: '<b>DESCUENTO $</b>',
                            labelAlign: 'top'
                        },{xtype: 'splitter'},{
                            xtype: 'numberfield',
                            fieldCls: 'required',
                            width: 150,
                            name : 'afecto',
                            itemId: 'finalafectoId',
                            readOnly: true,
                            fieldLabel: '<b>AFECTO</b>',
                            labelAlign: 'top'
                        },{xtype: 'splitter'},
                        {
                            xtype: 'numberfield',
                            width: 150,
                            fieldCls: 'required',
                            name : 'iva',
                            itemId: 'finaltotalivaId',
                            readOnly: true,
                            fieldLabel: '<b>IVA</b>',
                            labelAlign: 'top'
                            //renderer: function(valor){return Ext.util.Format.number(parseInt(iva),"0.000")} 
                        },{xtype: 'splitter'},{
                            xtype: 'textfield',
                            fieldCls: 'required',
                            width: 230,
                            name : 'total',
                            itemId: 'finaltotalId',
                            readOnly: true,
                            fieldLabel: '<b>TOTAL DOCUMENTO</b>',
                            labelAlign: 'top'
                        },{
                            xtype: 'numberfield',
                            itemId: 'finaltotalpostId',
                            hidden: true
                        }]
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
                            text: 'OBSERVACIONES'
                        },{
                            xtype: 'button',
                            iconCls: 'icon-save',
                            scale: 'large',
                            action: 'grabarpedidos2',
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
