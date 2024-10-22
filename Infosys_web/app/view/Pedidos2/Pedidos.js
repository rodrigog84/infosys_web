Ext.define('Infosys_web.view.Pedidos2.Pedidos', {
    extend: 'Ext.window.Window',
    alias : 'widget.pedidosingresar2',

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
    height: 720,
    width: 1300,
    layout: 'fit',
    title: 'Pedido',

    initComponent: function() {
        var me = this;

         var tipoEnvase = Ext.create('Ext.data.Store', {
            fields: ['value', 'nombre'],
            data : [
                {"value":'SACO', "nombre":"SACO"},
                {"value": 'GRANEL', "nombre":"GRANEL"}
            ]
        }); 


         var tipoTransporte = Ext.create('Ext.data.Store', {
            fields:
             ['value', 'nombre'],
            data : [
                {"value":'CAMION SOLO', "nombre":"CAMION SOLO"},
                {"value": 'CON CARRO', "nombre":"CON CARRO"},
                {"value": 'RAMPLA', "nombre":"RAMPLA"},
                {"value": 'BATEA', "nombre":"BATEA"},

            ]
        }); 


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
                    height: 250,
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
                                labelWidth: 150,
                                name: 'num_pedido',
                                itemId: 'ticketId',
                                fieldLabel: '<b>NUMERO PEDIDO</b>',
                                readOnly: true
                            },{
                                xtype: 'displayfield',
                                width: 30
                               
                            },{
                                xtype: 'displayfield',
                                fieldLabel: '<b>AGRICOLA Y COMERCIAL LIRCAY SPA.</b>',
                                labelWidth: 500,
                                width: 500
                               
                            },{
                                xtype: 'datefield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                labelWidth: 50,
                                width: 170,
                                fieldLabel: '<b>FECHA</b>',
                                itemId: 'fechadocumId',
                                name: 'fecha_docum',
                                value: new Date(),
                                minValue: new Date()
                            },{
                                xtype: 'displayfield',
                                width: 15
                               
                            },{
                                xtype: 'datefield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                labelWidth: 120,
                                width: 290,
                                fieldLabel: '<b>FECHA PEDIDO</b>',
                                itemId: 'fechapedidoId',
                                name: 'fecha_pedido',
                                value: new Date(),
                                minValue: new Date()
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
                                    xtype: 'button',
                                    text: 'Buscar',
                                    maxHeight: 25,
                                    width: 80,                                                                        
                                    action: 'validarut22',
                                    itemId: 'buscarBtn'
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
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                xtype: 'combo',
                                itemId: 'tipoVendedorId',
                                width: 335,
                                fieldCls: 'required',
                                maxHeight: 25,
                                labelWidth: 75,
                                fieldLabel: '<b>VENDEDOR</b>',
                                forceSelection : true,
                                name : 'id_vendedor',
                                valueField : 'id',
                                displayField : 'nombre',
                                emptyText : "Seleccione",
                                store : 'Vendedores'
                            },{
                                xtype: 'textfield',
                                itemId: 'bodegaId',
                                name : 'id_bodega',
                                fieldLabel: 'Id Bodega',
                                hidden: true
                            }
                            ]
                        }
                        ,{
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
                                    msgTarget: 'side',
                                    labelWidth: 155,
                                    maxHeight: 25,
                                    width: 400,
                                    fieldLabel: '<b>NOMBRE FORMULA</b>',
                                    itemId: 'nombreformulaId',
                                    name : 'nombre',
                                    readOnly: true                                       
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'numberfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 80,
                                    maxHeight: 25,
                                    width: 180,
                                    fieldLabel: '<b>CANTIDAD</b>',
                                    itemId: 'cantidadformId',
                                    name : 'cantidad_form_or'                                         
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'button',
                                    text: 'Buscar Formula',
                                    maxHeight: 25,
                                    width: 120,                                                                        
                                    action: 'buscarformula',
                                    itemId: 'buscarBtnf'
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                xtype: 'datefield',
                                fieldCls: 'required',
                                maxHeight: 25,
                                labelWidth: 130,
                                width: 240,
                                fieldLabel: '<b>FECHA DESPACHO</b>',
                                itemId: 'fechadespachoId',
                                name: 'fecha_despacho',
                                minValue: Ext.Date.add(new Date(), Ext.Date.DAY, 5)
                            },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 130,
                                    maxHeight: 25,
                                    width: 240,
                                    fieldLabel: '<b>O.COMPRA</b>',
                                    itemId: 'ordencompraId',
                                    name : 'ordencompra'                                         
                                },{
                                    xtype: 'textfield',
                                    itemId: 'formulaId',
                                    name : 'id_formula',
                                    fieldLabel: 'Id Formula',
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
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 155,
                                    maxHeight: 25,
                                    width: 350,
                                    fieldLabel: '<b>UBICACION</b>',
                                    itemId: 'ubicacionId',
                                    name : 'ubicacion'                                         
                                },{
                                        xtype: 'combobox',
                                        fieldCls: 'required',
                                        labelWidth: 100,
                                        width: 250,
                                        height: 30,
                                        fieldLabel: '<b>TIPO ENVASE</b>',
                                        editable: false,
                                        store : tipoEnvase,
                                        emptyText : 'Seleccionar',
                                        displayField : 'nombre',
                                        valueField : 'value',                        
                                        itemId: 'tipoenvaseId',
                                        name: 'tipoenvase'                            
                                    },{
                                        xtype: 'combobox',
                                        fieldCls: 'required',
                                        labelWidth: 200,
                                        width: 350,
                                        height: 30,
                                        fieldLabel: '<b>TIPO TRANSPORTE</b>',
                                        editable: false,
                                        store : tipoTransporte,
                                        emptyText : 'Seleccionar',
                                        displayField : 'nombre',
                                        valueField : 'value',                        
                                        itemId: 'tipotransporteId',
                                        name: 'tipotransporte'                            
                                    },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    msgTarget: 'side',
                                    labelWidth: 150,
                                    maxHeight: 25,
                                    width: 240,
                                    fieldLabel: '<b>O.PEDIDO EXT</b>',
                                    itemId: 'opedidoextId',
                                    name : 'opedidoext'                                         
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
                                        xtype: 'displayfield',
                                        itemId : 'estado_cliente',
                                        fieldLabel : 'Estado Cliente:',
                                        labelStyle: ' font-weight:bold',
                                        fieldStyle: '',
                                        value : '',
                                        labelWidth: 200,
                                    }
                            ]
                        }
                        ,{
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
                                    itemId: 'obsId',
                                    name : 'id_observa',
                                    fieldLabel: 'Id Formula',
                                    hidden: true
                            },{
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
                                action: 'buscarproductos',
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
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'button',
                                text: 'Agregar',
                                iconCls: 'icon-plus',
                                width: 80,
                                allowBlank: true,
                                action: 'agregarItem'
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
                            store: 'Pedidos.Items',
                            tbar: [{
                                iconCls: 'icon-delete',
                                text: 'Eliminar',
                                action: 'eliminaritem'
                            },
                            {
                                iconCls: 'icon-delete',
                                text: 'Editar',
                                action: 'editaritem'
                            }
                            ],
                            height: 240,
                            columns: [
                                { text: 'Id',  dataIndex: 'id', width: 250, hidden : true },
                                { text: 'Id producto',  dataIndex: 'id_producto', width: 250, hidden : true },
                                { text: 'Id descuento',  dataIndex: 'id_descuento', width: 250, hidden : true },
                                { text: 'Id formula',  dataIndex: 'id_formula', width: 250, hidden : true },
                                { text: 'codigo',  dataIndex: 'codigo', width: 250, hidden : true },
                                { text: 'Formula',  dataIndex: 'nombreformula', width: 250 },
                                { text: 'Producto',  dataIndex: 'nom_producto', width: 250 },
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
                    items: [{
                            iconCls: 'icon-reset',
                            text: 'Cancelar',
                            action: 'cancelar2',
                        },'->',
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
                            action: 'grabarpedidos',
                            itemId: 'grabarpedidos',
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
