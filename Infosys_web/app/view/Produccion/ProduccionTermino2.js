Ext.define('Infosys_web.view.Produccion.ProduccionTermino2', {
    extend: 'Ext.window.Window',
    alias : 'widget.producciontermino2',

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
    height: 520,
    width: 1450,
    layout: 'fit',
    title: 'PRODUCCION TERMINO ',
    //closable: false,

    initComponent: function() {
        var me = this;
        //var stItms = Ext.getStore('ProduccionTermino');
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
                    height: 150,
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
                                width: 10                                   
                            },{
                                xtype: 'textfield',
                                itemId: 'pedidoId',
                                name : 'id',
                                hidden: true
                            },{
                                xtype: 'displayfield',
                                width: 80
                               
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
                                    itemId: 'formulaId',
                                    name : 'id_formula',
                                    hidden: true
                                },{
                                    xtype: 'numberfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 80,
                                    maxHeight: 25,
                                    width: 200,
                                    fieldLabel: '<b>CANTIDAD</b>',
                                    itemId: 'cantidadId',
                                    name : 'cantidad',
                                    readOnly: true                                           
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'numberfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 120,
                                    maxHeight: 25,
                                    width: 220,
                                    fieldLabel: '<b>CANTIDAD PROD</b>',
                                    itemId: 'cantidadproduccalId',
                                    value:0,
                                    name : 'cantidad'                                       
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'numberfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 120,
                                    maxHeight: 25,
                                    width: 220,
                                    fieldLabel: '<b>CANTIDAD REAL</b>',
                                    itemId: 'cantidadproducId',
                                    name : 'cantidad'                                       
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
                                    xtype: 'numberfield',
                                    itemId: 'diasvencId',
                                    name : 'diasvenc',
                                    hidden: true
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'datefield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    labelWidth: 100,
                                    width: 210,
                                    fieldLabel: '<b>FECHA VENC.</b>',
                                    itemId: 'fechavencId',
                                    name: 'fecha_vencimiento'
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
                            items: [{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 130,
                                    maxHeight: 25,
                                    width: 420,
                                    fieldLabel: '<b>NOMBRE FORMULA</b>',
                                    itemId: 'nombreformulaId',
                                    name : 'nombre'                                       
                                },
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
                            items: [{
                                    xtype: 'timefield',
                                    fieldCls: 'required',
                                    format: 'H:i',
                                    msgTarget: 'side',
                                    labelWidth: 130,
                                    maxHeight: 25,
                                    width: 250,
                                    fieldLabel: '<b>HORA INICIO</b>',
                                    itemId: 'horainicioId',
                                    name : 'hora_inicio'                                 
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'timefield',
                                    fieldCls: 'required',
                                    format: 'H:i',
                                    msgTarget: 'side',
                                    labelWidth: 130,
                                    maxHeight: 25,
                                    width: 250,
                                    fieldLabel: '<b>HORA TERMINO</b>',
                                    itemId: 'horaterminoId',
                                    name : 'hora_inicio'                                         
                                },{
                                    xtype: 'datefield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    labelWidth: 50,
                                    width: 170,
                                    fieldLabel: '<b>FECHA inicio</b>',
                                    itemId: 'fechainicioId',
                                    name: 'fecha_docum',
                                    value: new Date(),
                                    hidden: true
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 105,
                                    maxHeight: 25,
                                    width: 450,
                                    fieldLabel: '<b>ENCARGADO</b>',
                                    itemId: 'encargadoId',
                                    name : 'nombre_encargado'                                         
                                },{
                                    xtype: 'displayfield',
                                    width: 40                                   
                                },{
                                    xtype: 'combo',
                                    itemId: 'bodegaId',
                                    labelWidth: 60,
                                    width: 255,
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    fieldLabel: '<b>BODEGA</b>',
                                    forceSelection : true,
                                    name : 'id_bodega',
                                    valueField : 'id',
                                    displayField : 'nombre',
                                    emptyText : "Seleccione",
                                    //value: "1",
                                    store : 'Bodegas'
                                }
                            ]
                        },
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
                                action: 'buscarproductosconsumopro',
                                itemId: 'buscarproc'
                            },{xtype: 'splitter'},{
                                xtype: 'textfield',
                                align: 'center',
                                labelWidth: 60,
                                width: 310,
                                itemId: 'nombreproductoforId',
                                fieldLabel: 'Producto',
                                name: 'nom_producto_for',                                
                                readOnly: true
                            },
                            ,{xtype: 'splitter'},{
                                xtype: 'textfield',
                                align: 'center',
                                labelWidth: 60,
                                width: 310,
                                itemId: 'productoforId',
                                fieldLabel: 'Producto',
                                name: 'id_producto_for',                                
                                hidden: true
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'textfield',
                                width: 110,
                                labelWidth: 40,
                                fieldLabel: 'Lote',
                                itemId: 'loteId',
                                style: 'font-weight: bold;',
                                readOnly: true,
                                hidden: true
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 110,
                                labelWidth: 40,
                                fieldLabel: 'Precio',
                                itemId: 'precioId',
                                style: 'font-weight: bold;',
                                minValue: 0,
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 100,
                                labelWidth: 40,
                                minValue: 0,
                                fieldLabel: 'Stock',
                                itemId: 'cantidadoriId',
                                readOnly: true,
                                //hidden: true
                            },
                            {
                                xtype: 'numberfield',
                                width: 100,
                                labelWidth: 40,
                                minValue: 0,
                                fieldLabel: 'Stock',
                                itemId: 'stockTotalId',
                                readOnly: true,
                                hidden: true
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
                                width: 200,
                                labelWidth: 115,
                                minValue: 0,
                                fieldLabel: 'Cantidad Pro KG.',
                                itemId: 'cantidadoproId'
                            },{xtype: 'splitter'},{
                                xtype: 'numberfield',
                                width: 200,
                                labelWidth: 115,
                                minValue: 0,
                                fieldLabel: 'Cantidad For KG.',
                                itemId: 'cantidadforId',
                                readOnly: true,
                            },{
                                xtype: 'datefield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                labelWidth: 50,
                                width: 150,
                                fieldLabel: '<b>VENC.</b>',
                                itemId: 'fechavencimientoId',
                                name: 'fecha_vencimiento',
                                value: new Date(),
                                hidden: false,
                            },{xtype: 'splitter'},{
                                xtype: 'button',
                                text: 'Agregar',
                                iconCls: 'icon-plus',
                                width: 80,
                                allowBlank: true,
                                action: 'agregarItem'
                            }]
                        
                        },{
                            xtype: 'grid',
                            itemId: 'itemsgridId',
                            title: 'Detalle Consumo Produccion',
                            labelWidth: 50,
                            store: 'ProduccionTermino',
                            tbar: [
                            {
                                iconCls: 'icon-delete',
                                text: 'Editar',
                                action: 'editaritem'
                            }
                            ],
                            height: 225,
                            columns: [
                                { text: 'Id',  type: 'auto', dataIndex: 'id', width: 250, hidden : true },                                
                                { text: 'Id existencia',  dataIndex: 'id_existencia', width: 250, hidden : true },
                                { text: 'Id producto',  dataIndex: 'id_producto', width: 250, hidden : true },
                                { text: 'Id bodega',  dataIndex: 'id_bodega', width: 250, hidden : true },
                                { text: 'codigo',  dataIndex: 'codigo', width: 150, hidden : true },
                                { text: 'Lote',  dataIndex: 'lote', width: 100},
                                { text: 'Fecha Venc.',  dataIndex: 'fecha_vencimiento', width: 120, renderer:Ext.util.Format.dateRenderer('d/m/Y') },
                                { text: 'Producto',  dataIndex: 'nom_producto', width: 450 },
                                { text: 'Precio',  dataIndex: 'valor_compra', align: 'right',width: 150, decimalPrecision:3},
                                { text: 'Cantidad',  dataIndex: 'cantidad', align: 'right',width: 150, renderer: function(valor){return Ext.util.Format.number((valor),"0.00")} },
                                { text: 'Cantidad Real',  dataIndex: 'cantidad_real', align: 'right',width: 150, renderer: function(valor){return Ext.util.Format.number((valor),"0.00")} },
                                
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
                    items: [{
                            iconCls: 'icon-reset',
                            text: 'Cancelar',
                            action: 'cancelar',
                        },'->',
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
                            action: 'grabarproduccion2',
                            itemId: 'grabarproduccion2',
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
