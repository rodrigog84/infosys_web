Ext.define('Infosys_web.view.ordencompra.Ingresar', {
    extend: 'Ext.window.Window',
    alias : 'widget.ordencompraingresar',

    requires: ['Ext.form.Panel','Ext.form.field.Text',
               'Ext.grid.plugin.CellEditing'],

    title : 'Crear Orden de Compra',
    layout: 'fit',
    autoShow: true,
    height: 640,
    width: 1220,
    modal: true,
    iconCls: 'icon-sheet',
    //y: 10,
    initComponent: function() {
        //limpiamos store productos
        var st = Ext.getStore('Productos');
        st.proxy.extraParams = {};
        st.load();
        //limpiamos store items
        var stItms = Ext.getStore('ordencompra.Items');
        stItms.load();

        this.items = [{
                xtype: 'form',
                padding: '5 5 0 5',
                border: false,
                style: 'background-color: #fff;',
                
                fieldDefaults: {
                    anchor: '100%',
                    labelWidth: 120,
                    labelAlign: 'left',
                    allowBlank: true,
                    combineErrors: false,
                    msgTarget: 'side'
                },

                items: [{
                    xtype: 'fieldset',
                    title: 'Datos Proveedor',
                    items: [{
                        xtype: 'container',
                        layout: {
                            type: 'vbox'
                        },
                        defaults: {
                            flex: 1
                        },
                        items: [
        					{
        					    xtype: 'textfield',
        					    name : 'id',
        					    hidden: true
        					},{
                                xtype: 'textfield',
                                name : 'idproveedor',
                                itemId: 'idproveedor',
                                hidden: true
                            },    
                            {
                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                items: [{
                                    xtype: 'button',
                                    iconCls: 'icon-search',
                                    text: 'Buscar Proveedor',
                                    width: 250,
                                    allowBlank: true,
                                    action: 'wbuscarproveedor'
                                },{
                                    xtype: 'displayfield',
                                    width: 390                                          
                                },{
                                    xtype: 'datefield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    width: 230,
                                    fieldLabel: '<b>FECHA</b>',
                                    itemId: 'fechaordenId',
                                    name: 'fecha',
                                    value: new Date()
                                }
                                ]
                            },
                            {
                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                items: [{
                                    msgTarget: 'side',
                                    fieldLabel: 'Nombre Empresa',
                                    xtype: 'textfield',
                                    width: 895,
                                    name : 'nombres',
                                    itemId: 'nombreId',
                                    readOnly : true
                                   
                                },{
                                    msgTarget: 'side',
                                    fieldLabel: 'Rut',
                                    xtype: 'textfield',
                                    width: 895,
                                    name : 'rut',
                                    itemId: 'rutId',
                                    readOnly : true,
                                    hidden: true
                                   
                                }
                                ]
                            },
                            {
                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                items: [{
                                    xtype: 'textfield',
                                    width: 895,
                                    name : 'direccion',
                                    itemId: 'direccionId',
                                    fieldLabel: 'Direccion Empresa',
                                    readOnly : true
                                }]
                            },
                            {
                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                items: [{
                                xtype: 'textfield',
                                name : 'id_giro',
                                itemId: 'giroId',
                                hidden: true
                                },
                                {
                                    xtype: 'textfield',
                                    width: 450,
                                    name : 'nom_giro',
                                    itemId: 'nom_giroId',
                                    fieldLabel: 'Giro Empresa',
                                    readOnly : true
                                },
                                    {xtype: 'splitter'},
                                {
                                    xtype: 'textfield',
                                    width: 240,
                                    name : 'fono',
                                    itemId: 'fonoId',
                                    fieldLabel: 'Telefono Empresa',
                                    readOnly : true
                                }
                                
                               
                                ]
                            },
                            {
                                xtype: 'fieldcontainer',
                                layout: 'hbox',
                                items: [{
                                    xtype: 'textfield',
                                   width: 360,
                                   fieldLabel: 'Nombre Contacto',
                                    itemId: 'nombre_contactoId',
                                    name : 'nombre_contacto'
                                }, {xtype: 'splitter'},{
                                    xtype: 'button',
                                    text: 'Buscar',
                                    maxHeight: 25,
                                    width: 60,
                                    allowBlank: true,
                                    action: 'buscarcontactos'
                                },{xtype: 'splitter'},{
                                    xtype: 'textfield',
                                    width: 190,
                                    name : 'fono_contacto',
                                    itemId: 'fono_contactoId',
                                    fieldLabel: 'Telefono Contacto'
                                },{xtype: 'splitter'},{
                                    xtype: 'textfield',
                                    width: 245,
                                    name : 'e_mail_contacto',
                                    itemId: 'mail_contactoId',
                                    fieldLabel: 'Mail Contacto'
                                }


                                ]
                            }
                            ]
                    }]
                },
                {
                    xtype: 'fieldset',
                    title: 'Items Ordencompra',
                    fieldDefaults: {
                        labelWidth: 70
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
                                text: 'Buscar',
                                maxHeight: 25,
                                width: 140,
                                allowBlank: true,
                                action: 'buscarproductosl',
                               
                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 130,
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

                            },{
                                xtype: 'textfield',
                                width: 120,
                                labelWidth: 40,
                                minValue: 0,
                                fieldLabel: 'U.lote',
                                hidden: true,
                                itemId: 'uloteId',
                                style: 'font-weight: bold;'

                            },{
                                xtype: 'textfield',
                                width: 120,
                                labelWidth: 40,
                                minValue: 0,
                                fieldLabel: 'U.lote',
                                hidden: true,
                                itemId: 'ultimoloteId',
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
                                width: 140,
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
                                labelWidth: 60,
                                width: 120,
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
                                action: 'agregarItem'
                            }]
                        }

                        ]
                    }]

                },
                {
                    xtype: 'grid',
                    itemId: 'itemsgridId',
                    title: 'Items',
                    store: 'ordencompra.Items',
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
                    height: 250,
                    columns: [
                            {text: 'Id producto',  dataIndex: 'id_producto', width: 250, hidden : true },
                            { text: 'Id descuento',  dataIndex: 'id_descuento', width: 250, hidden : true },
                            { text: 'ulote',  dataIndex: 'u_lote', width: 250, hidden : true },
                            { text: 'codigo',  dataIndex: 'codigo', width: 250, hidden : true },
                            { text: 'Producto',  dataIndex: 'nombre', width: 250 },
                            { text: 'Precio Unitario',  dataIndex: 'precio', align: 'right',flex:1, renderer: function(valor){return Ext.util.Format.number((valor),"0.00")} },
                            { text: 'Cantidad',  dataIndex: 'cantidad', align: 'right',width: 100},
                            { text: 'Descuento',  dataIndex: 'dcto', align: 'right',flex:1, renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")} },
                            { text: 'Neto',  dataIndex: 'neto', align: 'right',flex:1,renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")} },
                            { text: 'Iva',  dataIndex: 'iva', align: 'right',flex:1,renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")} },
                            { text: 'Total',  dataIndex: 'total', align: 'right',flex:1, renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,000")} }
                    ]
                },
                {
                    xtype: 'fieldset',
                    title: 'RESUMEN ORDEN DE COMPRA',
                    fieldDefaults: {
                        labelWidth: 110
                    },
                    items: [
                    {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        items: [ {
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
                            displayField: 'nombre'
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
                        },{
                            xtype: 'textfield',
                            itemId: 'obsId',
                            name : 'id_observa',
                            fieldLabel: 'Observacion',
                            hidden: true
                    }]
                        }
                    ]

                }
                ]
        }];
        
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'bottom',
            id:'buttons',
            ui: 'footer',
        items: ['->',{
                xtype: 'button',
                //iconCls: 'icono',
                scale: 'large',
                action: 'observaciones',
                text: 'OBSERVACIONES'
            },{
                iconCls: 'icon-save',
                text: 'Grabar',
                action: 'grabar',
                itemId: 'grabaorden',
            },'-',{
                iconCls: 'icon-reset',
                text: 'Cancelar',
                scope: this,
                handler: this.close
            }]
        }];
        this.callParent(arguments);
    }
});
