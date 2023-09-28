Ext.define('Infosys_web.view.facturaganado.Facturaganado', {
    extend: 'Ext.window.Window',
    alias : 'widget.facturaganado',

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
    width: 1320,
    layout: 'fit',
    title: 'Factura Ganado',

    initComponent: function() {
        var me = this;
        var stItms = Ext.getStore('facturaganado.Items');
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
                                            store: 'Tipo_documento.Selector4',
                                            valueField: 'id',
                                            displayField: 'nombre'
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
                                            xtype: 'displayfield',
                                            width: 40                                          
                                        },{
                                            xtype: 'numberfield',
                                            name: 'idfactura',
                                            itemId: 'idfactura',
                                            hidden: true
                                          
                                        },{
                                            xtype: 'numberfield',
                                            name: 'id_bodega',
                                            itemId: 'bodegaId',
                                            hidden: true
                                          
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
                                            width: 45
                                           
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
                                        }, {xtype: 'splitter'},
                                        {
                                            xtype: 'button',
                                            text: 'Buscar',
                                            maxHeight: 25,
                                            width: 80,
                                            allowBlank: true,
                                            //disabled : true,                                            
                                            action: 'validarut',
                                            itemId: 'buscarBtn'
                                        },{xtype: 'splitter'},{
                                            xtype: 'textfield',
                                            fieldCls: 'required',
                                            fieldLabel: '<b>RAZON SOCIAL</b>',
                                            maxHeight: 25,
                                            labelWidth: 80,
                                            width: 845,
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
                                            action: 'buscarsucursalfacturaganado'
                                            //,disabled : true  
                                        },{xtype: 'splitter'},{
                                            xtype: 'textfield',
                                            fieldCls: 'required',
                                            fieldLabel: '<b>GIRO</b>',
                                            labelWidth: 60,
                                            maxHeight: 25,
                                            width: 405,
                                            itemId: 'giroId',
                                            readOnly: true,
                                            //disabled : true,                                           
                                            name : 'giro'
                                          
                                        },{xtype: 'splitter'},{
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
                                            width: 300,
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
                                            width: 300,
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
                                            width: 200,
                                            labelWidth: 85,
                                            maxHeight: 25,
                                            fieldLabel: '<b>GUIAS</b>',
                                            name: 'guias_despacho',
                                            itemId: 'guiasdespachoId',
                                            style: 'font-weight: bold;'
                                        },{xtype: 'splitter'},{
                                            xtype: 'textfield',
                                            width: 200,
                                            labelWidth: 85,
                                            maxHeight: 25,
                                            fieldLabel: '<b>GUIAS</b>',
                                            name: 'id_despacho',
                                            itemId: 'idguiasdespachoId',
                                            style: 'font-weight: bold;',
                                            hidden: true
                                        },{xtype: 'splitter'},,
                                        {
                                            xtype: 'button',
                                            text: 'Buscar Guias',
                                            iconCls: 'icon-plus',
                                            width: 105,
                                            itemId: 'guiasdespacho2Id',                                            
                                            action: 'buscarguias'
                                        }
                                    ]
                                    },{
                    xtype: 'fieldset',
                    title: '<b>ITEMS DOCUMENTOS</b>',
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
                                width: 120,
                                labelWidth: 40,
                                fieldLabel: 'Codigo',
                                itemId: 'codigoId',
                                style: 'font-weight: bold;'
                            }, {xtype: 'splitter'},{
                                xtype: 'textfield',
                                width: 260,
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
                                text: 'Buscar',
                                maxHeight: 25,
                                width: 60,
                                allowBlank: true,
                                action: 'buscarproductos',
                                itemId: 'buscarproc'
                            },{xtype: 'splitter'},
                            {
                                xtype: 'textfield',
                                width: 110,
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
                                xtype: 'numberfield',
                                width: 120,
                                labelWidth: 40,
                                fieldLabel: 'Kilos',
                                itemId: 'kilosId',
                                style: 'font-weight: bold;'
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 145,
                                labelWidth: 40,
                                fieldLabel: 'Precio',
                                itemId: 'precioId',
                                style: 'font-weight: bold;'
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 155,
                                labelWidth: 30,
                                fieldLabel: 'Neto',
                                itemId: 'netoId',
                                style: 'font-weight: bold;'
                            },
                            {xtype: 'splitter'},{
                                xtype: 'numberfield',
                                width: 155,
                                labelWidth: 30,
                                fieldLabel: 'Iva',
                                itemId: 'ivaId',
                                style: 'font-weight: bold;',
                                hidden: true
                            },,
                            {xtype: 'splitter'},{
                                xtype: 'numberfield',
                                width: 155,
                                labelWidth: 30,
                                fieldLabel: 'Total',
                                itemId: 'totalId',
                                style: 'font-weight: bold;',
                                hidden: true
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
                            store: 'facturaganado.Items',
                            tbar: [{
                                iconCls: 'icon-delete',
                                text: 'Editar',
                                action: 'EditarItem'
                            },'->',{
                                iconCls: 'icon-delete',
                                text: 'Eliminar',
                                action: 'eliminaritem'
                            },
                            ],
                            height: 210,
                            columns: [
                                { text: 'Id',  dataIndex: 'id', width: 250, hidden : true },
                                { text: 'Id producto',  dataIndex: 'id_producto', width: 250, hidden : true },
                                { text: 'codigo',  dataIndex: 'codigo', width: 250, hidden : true },
                                { text: 'Producto',  dataIndex: 'nombre', width: 250 },
                                { text: 'Cantidad',  dataIndex: 'cantidad', align: 'right',width: 150},
                                { text: 'Kilos',  dataIndex: 'kilos', align: 'right',flex:1, renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")} },
                                { text: 'Precio',  dataIndex: 'precio', align: 'right',width: 150},
                                { text: 'Neto',  dataIndex: 'neto', align: 'right',flex:1,renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")} },
                                { text: 'Iva',  dataIndex: 'iva', align: 'right',flex:1,renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")},hidden : true },
                                { text: 'Total',  dataIndex: 'total', align: 'right',flex:1, renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")},hidden : true }
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
                            width: 150,
                            name : 'neto',
                            itemId: 'finaltotalnetoId',
                            readOnly: true,
                            fieldLabel: '<b>VALOR NETO</b>',
                            labelAlign: 'top'
                        },
                        {xtype: 'splitter'},
                        {
                            xtype: 'numberfield',
                            fieldCls: 'required',
                            width: 150,
                            name : 'ncomisionganado',
                            itemId: 'finalcomisionganadoId',
                            readOnly: true,
                            fieldLabel: '<b>COMISION GANADO</b>',
                            labelAlign: 'top'
                        },
                        {xtype: 'splitter'},
                        {
                            xtype: 'numberfield',
                            fieldCls: 'required',
                            width: 150,
                            name : 'notroscargos',
                            itemId: 'finalotroscargosId',
                            readOnly: true,
                            fieldLabel: '<b>OTROS CARGOS</b>',
                            labelAlign: 'top'
                        },
                        {xtype: 'splitter'},
                        {
                            xtype: 'numberfield',
                            fieldCls: 'required',
                            width: 150,
                            name : 'ncostomayorplazo',
                            itemId: 'finalcostomayorplazoId',
                            readOnly: true,
                            fieldLabel: '<b>COSTO MAYOR PLAZO</b>',
                            labelAlign: 'top'
                        },                        
                        {xtype: 'splitter'},
                        {
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
                            width: 150,
                            name : 'total',
                            itemId: 'finaltotalId',
                            readOnly: true,
                            fieldLabel: '<b>TOTAL DOCUMENTO</b>',
                            labelAlign: 'top'
                        },{
                            xtype: 'numberfield',
                            itemId: 'finaltotalpostId',
                            hidden: true
                        },{
                            xtype: 'numberfield',
                            itemId: 'mcomisionganado',
                            value : 0,
                            renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")},
                            hidden: true
                        },{
                            xtype: 'numberfield',
                            itemId: 'mcostomayorplazo',
                            value : 0,
                            renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")},
                            hidden: true
                        },{
                            xtype: 'numberfield',
                            itemId: 'motroscargos',
                            value : 0,
                            renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")},
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
                            action: 'cancelar',
                        },'->',
                        {
                            xtype: 'button',
                            //iconCls: 'icono',
                            scale: 'large',
                            action: 'cobrosadic',
                            itemId: 'cobrosadic',
                            text: 'COBROS ADICIONALES'
                        },
                        {
                            xtype: 'button',
                            //iconCls: 'icono',
                            scale: 'large',
                            itemId: 'observaciones',
                            action: 'observaciones',
                            text: 'OBSERVACIONES'
                        },{
                            xtype: 'button',
                            iconCls: 'icon-save',
                            scale: 'large',
                            itemId: 'grabarfactura',
                            action: 'grabarfacturaganado',
                            text: 'Grabar / Emitir'
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
        //me.down('#productoId').getStore().load();
    }

});
