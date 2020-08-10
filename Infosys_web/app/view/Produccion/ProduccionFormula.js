Ext.define('Infosys_web.view.Produccion.ProduccionFormula', {
    extend: 'Ext.window.Window',
    alias : 'widget.produccioningresarformula',

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
    title: 'PRODUCCION',

    initComponent: function() {
        var me = this;
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
                    height: 185,
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
                        items: [,{
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
                            width: 262,
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
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    maxHeight: 25,
                                    labelWidth: 130,
                                    width: 220,
                                    fieldLabel: '<b>NUMERO PEDIDO</b>',
                                    itemId: 'npedidoId',
                                    name : 'num_pedido'                                         
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'button',
                                    text: 'Buscar',
                                    maxHeight: 25,
                                    width: 80,                                                                        
                                    action: 'buscarpedidopro2',
                                    itemId: 'buscarBtnp'
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
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 130,
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
                                    labelWidth: 115,
                                    width: 605,
                                    itemId: 'nombre_id',
                                    name : 'nombre',
                                    readOnly: true                                    
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
                                    itemId: 'formulaId',
                                    name : 'id_formula',
                                    hidden: true
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 130,
                                    maxHeight: 25,
                                    width: 520,
                                    fieldLabel: '<b>NOMBRE FORMULA</b>',
                                    itemId: 'nombreformulaId',
                                    name : 'nombre',
                                    hidden: true                                         
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 60,
                                    maxHeight: 25,
                                    width: 180,
                                    fieldLabel: '<b>CODIGO</b>',
                                    itemId: 'codigoId',
                                    name : 'codigo',
                                    readOnly: true                                         
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 80,
                                    maxHeight: 25,
                                    width: 340,
                                    fieldLabel: '<b>PRODUCTO</b>',
                                    itemId: 'nombreproductoId',
                                    name : 'nom_producto'                                         
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
                                    width: 200,
                                    fieldLabel: '<b>CANTIDAD PRO</b>',
                                    itemId: 'cantidadPROId',
                                    name : 'cantidad_pro',
                                    readOnly: true                                           
                                },{
                                    xtype: 'textfield',
                                    itemId: 'productoId',
                                    name : 'id_producto',
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
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 155,
                                    maxHeight: 25,
                                    width: 450,
                                    fieldLabel: '<b>ENCARGADO</b>',
                                    itemId: 'encargadoId',
                                    name : 'nombre_encargado'                                         
                                }
                            ]
                        },
                        ]
                        },{
                            xtype: 'grid',
                            itemId: 'itemsgridId',
                            title: 'Detalle',
                            labelWidth: 50,
                            store: 'PedidosFormula',
                            height: 340,
                            columns: [
                                { text: 'Id producto',  dataIndex: 'id_producto', width: 250, hidden : true },
                                { text: 'codigo',  dataIndex: 'codigo', width: 150, hidden : true },
                                { text: 'Producto',  dataIndex: 'nombre_producto', width: 450 },
                                { text: 'Bodega',  dataIndex: 'id_bodega', width: 250, hidden:true},
                                { text: 'Valor Compra',  dataIndex: 'valor_compra', align: 'right',width: 150, decimalPrecision:3},
                                { text: 'Cantidad',  dataIndex: 'cantidad', align: 'right',width: 150, decimalPrecision:3},
                                { text: 'Valor Produccion',  dataIndex: 'valor_produccion', align: 'right',width: 150, renderer: function(valor){return Ext.util.Format.number((valor),"0,000")} },
                                { text: 'Porcentaje',  dataIndex: 'porcentaje', align: 'right',width: 150 },
                                
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
                            action: 'grabarproduccion5',
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
