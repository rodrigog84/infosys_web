Ext.define('Infosys_web.view.Pedidos.Facturas', {
    extend: 'Ext.window.Window',
    alias : 'widget.facturasingresarpedidos',

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
    title: 'Facturacion Pedidos',

    initComponent: function() {
        var me = this;
        //var stItms = Ext.getStore('productos.Items');
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
                    items: [
                        {
                            xtype: 'container',
                            height: 150,
                            layout: {
                                type: 'vbox',
                                align: 'stretch'
                            },
                            items: [
                                {
                                    xtype: 'fieldcontainer',
                                    height: 37,
                                    labelWidth: 120,
                                    width: 462,
                                    fieldLabel: '',
                                    layout: {
                                        type: 'hbox',
                                        align: 'stretch'
                                    },
                                    items: [  {
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
                                            displayField: 'nombre',
                                            readOnly : true
                                        },{
                                            xtype: 'displayfield',
                                            width: 40                                          
                                        },{
                                            xtype: 'numberfield',
                                            name: 'idfactura',
                                            itemId: 'idfactura',
                                            hidden: true
                                          
                                        },{
                                            xtype: 'textfield',
                                            fieldCls: 'required',
                                            maxHeight: 25,
                                            width: 250,
                                            labelWidth: 150,
                                            name: 'num_factura',
                                            itemId: 'numfacturaId',
                                            fieldLabel: '<b>NUMERO DOCUMENTO</b>',
                                            readOnly: true


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
                                            itemId: 'fechafacturaId',
                                            name: 'fecha_factura',
                                            value: new Date(),
                                            readOnly: true
                                        },{
                                            xtype: 'displayfield',
                                            width: 50
                                           
                                        },{
                                            xtype: 'datefield',
                                            fieldCls: 'required',
                                            maxHeight: 25,
                                             labelWidth: 50,
                                            width: 150,
                                            fieldLabel: '<b>VENC.</b>',
                                            itemId: 'fechavencId',
                                            name: 'fecha_venc',
                                            value: new Date(),
                                            readOnly: true

                                        }
                                    ]
                                },
                                {
                                    xtype: 'fieldcontainer',
                                    height: 35,
                                    width: 462,
                                    fieldLabel: '',
                                    layout: {
                                        type: 'hbox',
                                        align: 'stretch'
                                    },
                                    items: [
                                        {
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
                                            name : 'rut',
                                            readOnly: true                                          
                                        },{
                                            xtype: 'displayfield',
                                            width: 20
                                           
                                        },
                                        {
                                            xtype: 'button',
                                            text: 'Buscar',
                                            maxHeight: 25,
                                            width: 80,                                        
                                            action: 'validarut',
                                            itemId: 'buscarBtn',
                                            hidden: true
                                        },{
                                            xtype: 'displayfield',
                                            width: 50
                                           
                                        },{
                                            xtype: 'textfield',
                                            fieldCls: 'required',
                                            fieldLabel: '<b>RAZON SOCIAL</b>',
                                            maxHeight: 25,
                                            labelWidth: 80,
                                            width: 885,
                                            itemId: 'nombre_id',
                                            name : 'nombre',                                           
                                            readOnly: true
                                            
                                        }
                                    ]
                                }, {
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
                                            xtype: 'textareafield',
                                            itemId: 'obsId',
                                            name : 'idobserva',
                                            fieldLabel: 'Id Observacion',
                                            hidden: true
                                        },{
                                            xtype: 'textareafield',
                                            itemId: 'observaId',
                                            name : 'observacion',
                                            fieldLabel: 'Observacion',
                                            hidden: true
                                        },{
                                            xtype: 'textfield',
                                            fieldLabel: '<b>DIRECCION</b>',
                                            fieldCls: 'required',
                                            maxHeight: 25,
                                            width: 580,
                                            itemId: 'direccionId',
                                            name : 'direccion',
                                            readOnly: true
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
                                            width: 50
                                           
                                        },{
                                            xtype: 'combo',
                                            itemId: 'giroId',
                                            fieldLabel: 'Giro',
                                            name: 'id_giro',
                                            store: 'Cod_activ',
                                            queryMode: 'local',
                                            maxHeight: 25,
                                            forceSelection: true,
                                            displayField: 'nombre',
                                            valueField: 'id',
                                            listConfig: {
                                                minWidth: 450
                                            },
                                            width: 480
                                            
                                        },{
                                            xtype: 'textfield',
                                            fieldCls: 'required',
                                            maxHeight: 25,
                                            width: 100,
                                            name: 'permite',
                                            value: "NO",
                                            itemId: 'permiteId',
                                            fieldLabel: '<b>permite</b>',
                                            hidden: true
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
                                            fieldCls: 'required',
                                            itemId: 'tipoCiudadId',
                                            name : 'nombre_ciudad',
                                            labelWidth: 60,
                                            maxHeight: 25,
                                            width: 190,
                                            readOnly: true,
                                            fieldLabel: '<b>CIUDAD</b>'
                                        },{
                                            xtype: 'displayfield',
                                            flex: 1,
                                            maxWidth: 25,
                                            labelWidth: 50
                                        },{
                                            xtype: 'textfield',
                                            fieldCls: 'required',
                                            itemId: 'tipoComunaId',
                                            name : 'nombre_comuna',
                                            labelWidth: 60,
                                            readOnly: true,
                                            maxHeight: 25,
                                            width: 190,
                                            fieldLabel: '<b>COMUNA</b>'
                                        },{
                                            xtype: 'displayfield',
                                            flex: 1,
                                            maxWidth: 25,
                                            labelWidth: 50
                                        },{
                                            xtype: 'combo',
                                            itemId: 'tipoVendedorId',
                                            width: 350,
                                            labelWidth: 75,
                                            fieldCls: 'required',
                                            maxHeight: 25,
                                            fieldLabel: '<b>VENDEDOR</b>',
                                            forceSelection : true,
                                            name : 'id_vendedor',
                                            valueField : 'id',
                                            displayField : 'nombre',
                                            emptyText : "Seleccione",
                                            store : 'Vendedores',
                                            readOnly: true
                                        },{
                                            xtype: 'displayfield',
                                            width: 10
                                        },{
                                            xtype: 'combo',
                                            itemId: 'tipocondpagoId',
                                            labelWidth: 80,
                                            width: 250,
                                            fieldCls: 'required',
                                            maxHeight: 25,
                                            fieldLabel: '<b>COND.PAGO</b>',
                                            forceSelection : true,
                                            name : 'id_condpago',
                                            valueField : 'id',
                                            displayField : 'nombre',
                                            emptyText : "Seleccione",
                                            store : 'Cond_pago',
                                            readOnly: true
                                        },{
                                            xtype: 'displayfield',
                                            width: 10
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
                                        }
                                    ]
                                    }
                            ]
                        },{
                            xtype: 'grid',
                            itemId: 'itemsgridId',
                            title: 'Detalle',
                            labelWidth: 50,
                            store: 'facturapedidos',
                            tbar: [
                            ],
                            height: 260,
                            columns: [
                                { text: 'Id producto',  dataIndex: 'id_producto', width: 250, hidden : true },
                                { text: 'codigo',  dataIndex: 'codigo', width: 250, hidden : true },
                                { text: 'Producto',  dataIndex: 'nombre', width: 250 },
                                { text: 'Bodega',  dataIndex: 'id_bodega', width: 250, hidden:true},
                                { text: 'Precio Unitario',  dataIndex: 'precio', align: 'right',flex:1, decimalPrecision:3},
                                { text: 'Cantidad',  dataIndex: 'cantidad', align: 'right',width: 150, decimalPrecision:3},
                                { text: 'Descuento',  dataIndex: 'dcto', align: 'right',width: 100, renderer: function(valor){return Ext.util.Format.number((valor),"0,000")} },
                                { text: 'Neto',  dataIndex: 'neto', align: 'right',flex:1,renderer: function(valor){return Ext.util.Format.number((valor),"0,000")} },
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
                            labelAlign: 'top',
                            displayField: 'nombre',
                            hidden: true
                        },{
                            xtype: 'numberfield',
                            fieldCls: 'required',
                            width: 200,
                            name : 'descuento',
                            itemId: 'finaldescuentoId',
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
                            labelAlign: 'top',
                            hidden: true
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
                            text: 'OBSERVACIONES',
                            hidden: true
                        },{
                            xtype: 'button',
                            iconCls: 'icon-save',
                            scale: 'large',
                            itemId: 'grabarfactura',
                            disabled : false,
                            action: 'grabarfactura',
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
