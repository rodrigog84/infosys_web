Ext.define('Infosys_web.view.consumos.Consumodiario', {
    extend: 'Ext.window.Window',
    alias : 'widget.consumodiario',

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
    height: 440,
    width: 1300,
    layout: 'fit',
    title: 'CONSUMO DIARIO PRODUCCION',

    initComponent: function() {
        var me = this;  
        var stItms = Ext.getStore('ProduccionTermino');
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
                    height: 75,
                    layout: {
                        type: 'vbox',
                        align: 'stretch'
                    },
                    items: [{
                        xtype: 'fieldcontainer',
                        height: 40,
                        labelWidth: 120,
                        width: 462,
                        fieldLabel: '',
                        layout: {
                            type: 'hbox',
                            align: 'stretch'
                        },
                        items: [{
                                xtype: 'textfield',
                                itemId: 'idId',
                                name : 'id',
                                hidden: true
                            },{
                                xtype: 'numberfield',
                                itemId: 'corrId',
                                name : 'corr_id',
                                value: 0,
                                hidden: true
                            },{
                                xtype: 'textfield',
                                width: 140,
                                maxHeight: 25,
                                labelWidth: 40,
                                fieldLabel: 'id_movimiento',
                                itemId: 'tipomovId',
                                style: 'font-weight: bold;',
                                hidden: true                                
                            },{
                                xtype: 'textfield',
                                width: 140,
                                labelWidth: 40,
                                maxHeight: 25,
                                fieldLabel: '<b>TIPO</b>',
                                itemId: 'nomtipomovId',
                                style: 'font-weight: bold;',
                                readOnly: true                                
                            },{
                                xtype: 'displayfield',
                                width: 130                                   
                            },{
                                xtype: 'displayfield',
                                fieldLabel: '<b>AGRICOLA Y COMERCIAL LIRCAY SPA.</b>',
                                labelWidth: 520,
                                maxHeight: 25,
                                width: 520                               
                            },{
                                xtype: 'displayfield',
                                width: 210                                   
                            },{
                                xtype: 'datefield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                labelWidth: 50,
                                width: 170,
                                fieldLabel: '<b>FECHA</b>',
                                itemId: 'fechadocumId',
                                name: 'fecha_docum'
                            }
                            ]
                        },{
                            xtype: 'fieldcontainer',
                            layout: 'hbox',
                            align: 'center',     
                            items: [{
                                xtype: 'textfield',
                                width: 140,
                                labelWidth: 40,
                                fieldLabel: 'id_producto',
                                itemId: 'productoId',
                                style: 'font-weight: bold;',
                                hidden: true
                            },{
                                xtype: 'textfield',
                                width: 140,
                                labelWidth: 40,
                                fieldLabel: 'id_existencia',
                                itemId: 'idpId',
                                style: 'font-weight: bold;',
                                hidden: true
                            },{
                                xtype: 'textfield',
                                width: 140,
                                labelWidth: 40,
                                fieldLabel: 'Codigo',
                                itemId: 'codigoId',
                                style: 'font-weight: bold;'
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'button',
                                text: 'Buscar',
                                maxHeight: 25,
                                width: 60,
                                allowBlank: true,
                                action: 'buscarproductosconsumo',
                                itemId: 'buscarproc'
                            },{xtype: 'splitter'},{
                                xtype: 'textfield',
                                align: 'center',
                                labelWidth: 60,
                                width: 380,
                                itemId: 'nombreproductoforId',
                                fieldLabel: 'Producto',
                                name: 'nom_producto_for',                                
                                readOnly: true
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 120,
                                labelWidth: 40,
                                fieldLabel: 'Lote',
                                itemId: 'loteId',
                                style: 'font-weight: bold;',
                                readOnly: true
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 120,
                                labelWidth: 40,
                                fieldLabel: 'Precio',
                                itemId: 'precioId',
                                style: 'font-weight: bold;',
                                readOnly: true
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 100,
                                labelWidth: 40,
                                minValue: 0,
                                fieldLabel: 'Stock',
                                itemId: 'cantidadoriId',
                                readOnly: true
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 100,
                                labelWidth: 40,
                                minValue: 0,
                                fieldLabel: 'stock_critico',
                                itemId: 'stockcriticoId',
                                readOnly: true,
                                hidden: true
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 220,
                                labelWidth: 115,
                                minValue: 0,
                                fieldLabel: 'Cantidad Pro KG.',
                                itemId: 'cantidadoproId'
                            },{
                                xtype: 'datefield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                 labelWidth: 50,
                                width: 150,
                                fieldLabel: '<b>VENC.</b>',
                                itemId: 'fechavencimientoId',
                                name: 'fecha_vencimiento',
                                //value: new Date(),
                                hidden: true,
                            },{
                                xtype: 'button',
                                text: 'Agregar',
                                iconCls: 'icon-plus',
                                width: 80,
                                allowBlank: true,
                                action: 'agregarItem'
                            }]
                        }

                        ]
                        },{
                            xtype: 'grid',
                            itemId: 'itemsgridId',
                            title: 'Detalle Consumo diario',
                            labelWidth: 50,
                            store: 'ProduccionTermino',
                            tbar: [
                            {
                                iconCls: 'icon-delete',
                                text: 'Editar',
                                action: 'editaritem'
                            }
                            ],
                            height: 260,
                            columns: [
                                { text: 'Id',  type: 'auto', dataIndex: 'id', width: 250, hidden : true },                                
                                { text: 'Id existencia',  dataIndex: 'id_existencia', width: 250, hidden : true },
                                { text: 'Id producto',  dataIndex: 'id_producto', width: 250, hidden : true },
                                { text: 'Id bodega',  dataIndex: 'id_bodega', width: 250, hidden : true },
                                { text: 'codigo',  dataIndex: 'codigo', width: 150, hidden : true },
                                { text: 'Lote',  dataIndex: 'lote', width: 100},
                                { text: 'Fecha Venc.',  dataIndex: 'fecha_vencimiento', width: 120, renderer:Ext.util.Format.dateRenderer('d/m/Y') },
                                { text: 'Producto',  dataIndex: 'nom_producto', width: 450 },
                                { text: 'Precio',  dataIndex: 'precio', align: 'right',width: 150, decimalPrecision:3},
                                { text: 'Cantidad',  dataIndex: 'cantidad', align: 'right',width: 150, renderer: function(valor){return Ext.util.Format.number((valor),"0.00")} },
                                ]
                            },{
                        xtype: 'fieldset',
                        title: '',
                        fieldDefaults: {
                        labelWidth: 120
                        },
                        items: [
                            {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        items: []
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
                            action: 'grabarconsumo',
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
