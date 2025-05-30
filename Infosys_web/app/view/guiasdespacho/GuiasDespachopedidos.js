Ext.define('Infosys_web.view.guiasdespacho.GuiasDespachopedidos', {
    extend: 'Ext.window.Window',
    alias : 'widget.guiasdespachopedidosingresar',

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
    //closable: false,
    height: 640,
    width: 1300,
    layout: 'fit',
    title: 'GUIAS DESPACHO DIRECTAS',

    initComponent: function() {
        var me = this;
        var stItms = Ext.getStore('productos.Items');
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
                    items: [
                        {
                            xtype: 'container',
                            height: 200,
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
                                            xtype: 'textfield',
                                            name: 'id_documento',
                                            itemId: 'tipodocumentoId',
                                            hidden: true
                                          
                                        },{
                                            xtype: 'textfield',
                                            name: 'id_transportista',
                                            itemId: 'idtransportista',
                                            hidden: true                                          
                                        },{
                                            xtype: 'textfield',
                                            name: 'tipo_documento',
                                            itemId: 'tipoDocumentoId',
                                            hidden: true
                                          
                                        },{
                                            xtype: 'textfield',
                                            name: 'id_factura',
                                            itemId: 'facturaId',
                                            hidden: true
                                          
                                        },{
                                            xtype: 'textfield',
                                            name: 'id_factura',
                                            itemId: 'idfactura',
                                            hidden: true
                                          
                                        },{
                                            xtype: 'textfield',
                                            name: 'id_bodega',
                                            itemId: 'bodegaId',
                                            hidden: true
                                          
                                        },{
                                            xtype: 'textfield',
                                            width: 450,
                                            fieldLabel: '<b>DOCUMENTO</b>',
                                            name: 'nom_documento',
                                            itemId: 'nomdocumentoId',
                                            value: 12,
                                            readOnly: true
                                          
                                        },{
                                            xtype: 'displayfield',
                                            width: 40                                          
                                        },{
                                            xtype: 'textfield',
                                            fieldCls: 'required',
                                            maxHeight: 25,
                                            width: 250,
                                            labelWidth: 150,
                                            allowBlank: false,
                                            name: 'num_factura',
                                            itemId: 'numfacturaId',
                                            fieldLabel: '<b>NUMERO DOCUMENTO</b>',
                                            readOnly: true
                                        },{
                                            xtype: 'textfield',
                                            fieldCls: 'required',
                                            maxHeight: 25,
                                            width: 250,
                                            labelWidth: 150,
                                            allowBlank: false,
                                            name: 'id_folio',
                                            itemId: 'idfolio',
                                            fieldLabel: '<b>ID FOLIO</b>',
                                            hidden: true

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
                                            value: new Date()
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
                                            name : 'rut'
                                            //disabled : true                                            
                                        },{
                                            xtype: 'displayfield',
                                            width: 20
                                           
                                        },
                                        {
                                            xtype: 'button',
                                            text: 'Buscar',
                                            maxHeight: 25,
                                            width: 80,
                                            allowBlank: true,
                                            //disabled : true,                                            
                                            action: 'validarutD',
                                            itemId: 'buscarBtn'
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
                                            //disabled : true,                                            
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
                                            //disabled : true,                                            
                                            readOnly: true
                                        },{xtype: 'splitter'},{
                                            xtype: 'button',
                                            text: 'Sucursal',
                                            itemId: 'sucursalId',
                                            maxHeight: 25,
                                            width: 70,
                                            allowBlank: true,
                                            action: 'buscarsucursalguiadespacho'
                                            //disabled : true  
                                        },{
                                            xtype: 'displayfield',
                                            width: 50
                                           
                                        },{
                                            xtype: 'textfield',
                                            fieldCls: 'required',
                                            fieldLabel: '<b>GIRO</b>',
                                            maxHeight: 25,
                                            width: 550,
                                            itemId: 'giroId',
                                            readOnly: true,
                                            //disabled : true,                                           
                                            name : 'giro'
                                          
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
                                            maxHeight: 25,
                                            width: 200,
                                            labelWidth: 60,
                                            readOnly: true,
                                            //disabled : true,                                            
                                            fieldLabel: '<b>CIUDAD</b>'
                                        },{
                                            xtype: 'displayfield',
                                            width: 15
                                            //labelWidth: 50
                                        },{
                                            xtype: 'textfield',
                                            fieldCls: 'required',
                                            itemId: 'tipoComunaId',
                                            name : 'nombre_comuna',
                                            readOnly: true,
                                            maxHeight: 25,
                                            width: 200,
                                            labelWidth: 60,
                                            //disabled : true,                                           
                                            fieldLabel: '<b>COMUNA</b>'
                                        },{
                                            xtype: 'displayfield',
                                            width: 15
                                            //labelWidth: 50
                                        },{
                                            xtype: 'combo',
                                            itemId: 'tipoVendedorId',
                                            width: 250,
                                            fieldCls: 'required',
                                            maxHeight: 25,
                                            labelWidth: 80,
                                            fieldLabel: '<b>VENDEDOR</b>',
                                            forceSelection : true,
                                            name : 'id_vendedor',
                                            valueField : 'id',
                                            displayField : 'nombre',
                                            emptyText : "Seleccione",
                                            store : 'Vendedores',
                                            //disabled : true, 
                                        },{
                                            xtype: 'displayfield',
                                            width: 10
                                        },{
                                            xtype: 'combo',
                                            itemId: 'tipocondpagoId',
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
                                            //disabled : true, 
                                        },{xtype: 'splitter'},{
                                            xtype: 'textfield',
                                            width: 150,
                                            labelWidth: 85,
                                            maxHeight: 25,
                                            fieldLabel: '<b>O. COMPRA</b>',
                                            name: 'orden_compra',
                                            itemId: 'ordencompraId',
                                            style: 'font-weight: bold;'
                                        },{xtype: 'splitter'},{
                                            xtype: 'textfield',
                                            width: 150,
                                            labelWidth: 85,
                                            maxHeight: 25,
                                            fieldLabel: '<b>PEDIDO</b>',
                                            name: 'num_pedido',
                                            itemId: 'pedidoId',
                                            style: 'font-weight: bold;'
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
                            },{
                                xtype: 'textfield',
                                itemId: 'idpId',
                                name : 'idp',
                                fieldLabel: 'Existencia',
                                hidden: true
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'button',
                                text: 'Buscar Producto',
                                maxHeight: 25,
                                width: 120,
                                allowBlank: true,
                                action: 'buscarproductos7',
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
                            },{
                                xtype: 'numberfield',
                                width: 180,
                                labelWidth: 40,
                                fieldLabel: 'PrecioP',
                                itemId: 'preciopromId',
                                style: 'font-weight: bold;',
                                hidden: true
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
                                value: 1,
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
                            disabled : true,
                            displayField: 'nombre',
                            hidden: true
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'button',
                                text: 'Agregar',
                                iconCls: 'icon-plus',
                                width: 80,
                                allowBlank: true,
                                action: 'agregarItem3'
                            },{
                                xtype: 'numberfield',
                                width: 120,
                                labelWidth: 60,
                                fieldLabel: 'Stock',
                                itemId: 'stock',
                                hidden: true
                            },{
                                xtype: 'numberfield',
                                width: 120,
                                fieldLabel: 'Stock Critico',
                                itemId: 'stock_critico',
                                hidden: true
                            },{
                                xtype: 'textfield',
                                width: 120,
                                labelWidth: 40,
                                minValue: 0,
                                fieldLabel: 'lote',
                                readOnly: true,
                                itemId: 'loteId',
                                hidden: true

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
                                hidden: true,
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
                            store: 'productos.Items',
                            tbar: [{
                                iconCls: 'icon-delete',
                                text: 'Eliminar',
                                action: 'eliminaritem3'
                            },
                            {
                                iconCls: 'icon-delete',
                                text: 'Editar',
                                action: 'editaritem3'
                            }
                            ],
                            height: 210,
                            columns: [
                                { text: 'Id existencia',  dataIndex: 'id_existencia', width: 100, hidden : true },
                                { text: 'Id producto',  dataIndex: 'id_producto', width: 100, hidden : true },
                                { text: 'Id descuento',  dataIndex: 'id_descuento', width: 100, hidden : true },
                                { text: 'codigo',  dataIndex: 'codigo', width: 100, hidden : true },
                                { text: 'Producto',  dataIndex: 'nombre', width: 250 },
                                { text: 'Lote',  dataIndex: 'lote', width: 100},
                                { text: 'Fecha Venc.',  dataIndex: 'fecha_vencimiento', width: 120, renderer:Ext.util.Format.dateRenderer('d/m/Y') },
                                { text: 'Precio Unitario',  dataIndex: 'precio', align: 'right',width: 100, renderer: function(valor){return Ext.util.Format.number((valor),"0.00")} },
                                { text: 'Precio Promedio',  dataIndex: 'p_promedio', align: 'right',flex:1,hidden: true },
                                { text: 'Cantidad',  dataIndex: 'cantidad', align: 'right',width: 100},
                                { text: 'Descuento',  dataIndex: 'dcto', align: 'right',width: 100, renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")} },
                                { text: 'Neto',  dataIndex: 'neto', align: 'right',width: 150,renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")} },
                                { text: 'Iva',  dataIndex: 'iva', align: 'right',width: 150,renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")} },
                                { text: 'Total',  dataIndex: 'total', align: 'right',width: 150, renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")} }
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
                    items: [{
                            iconCls: 'icon-reset',
                            text: 'Cancelar',
                            action: 'cancelar2',
                        },'->',
                        {
                            xtype: 'button',
                            //iconCls: 'icono',
                            scale: 'large',
                            action: 'observacionesguia',
                            text: 'OBSERVACIONES'
                        },{
                            xtype: 'button',
                            iconCls: 'icon-save',
                            scale: 'large',
                            action: 'grabarguiadirecta',
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
