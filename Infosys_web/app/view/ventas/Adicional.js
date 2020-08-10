Ext.define('Infosys_web.view.ventas.Adicional', {
    extend: 'Ext.window.Window',
    alias : 'widget.nobreadicional',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],
    //y: 50,
    title : 'Nombre Adicional en Guias de Despacho',
    layout: 'fit',
    autoShow: true,
    closable: false,
    width: 800,
    height: 120,
    modal: true,
    iconCls: 'icon-sheet',
    viewConfig: {
         forzarMayusculas: true,

    },
    
    initComponent: function() {
     
        this.items = [
            {
                xtype: 'form',
                padding: '5 5 0 5',
                border: false,
                style: 'background-color: #fff;',
                
                fieldDefaults: {
                    anchor: '100%',
                    labelAlign: 'left',
                    allowBlank: false,
                    combineErrors: false,
                    labelWidth: 170,
                    msgTarget: 'side'
                },

                items: [
                      {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        fieldLabel: '<b>NOMBRE A DESPACHO</b>',
                        items: [
                            {
                                xtype: 'textfield',
                                align: 'center',
                                labelWidth: 55,
                                width: 520,
                                itemId: 'nombreadicionalId',
                                fieldLabel: '',
                                name: 'nom_adicional'
                            },{
                                xtype: 'textfield',
                                width: 140,
                                labelWidth: 40,
                                fieldLabel: 'Codigo',
                                itemId: 'codigoId',
                                style: 'font-weight: bold;',
                                hidden : true
                            }, {xtype: 'splitter'},{
                                xtype: 'textfield',
                                align: 'center',
                                labelWidth: 55,
                                itemId: 'nombreproductoId',
                                fieldLabel: 'Producto',
                                name: 'nomproducto',                                
                                hidden: true
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
                                fieldLabel: 'Id',
                                hidden: true
                            },
                            {
                                xtype: 'numberfield',
                                width: 180,
                                labelWidth: 40,
                                fieldLabel: 'Precio',
                                itemId: 'precioId',
                                style: 'font-weight: bold;',
                                hidden: true
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
                                style: 'font-weight: bold;',
                                hidden: true

                            },
                            {xtype: 'splitter'},
                            {
                                xtype: 'numberfield',
                                width: 160,
                                labelWidth: 60,
                                minValue: 0,
                                value: 1,
                                fieldLabel: 'Cantidad',
                                itemId: 'cantidadId',
                                hidden: true
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
                            }
                        ]
                    }
                ]

             
            }
        ];
        
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'bottom',
            id:'buttons',
            ui: 'footer',
            items: ['->', {
                iconCls: 'icon-search',
                action: 'grabaradicional',
                text : 'Grabar'
            },'-',{
                iconCls: 'icon-reset',
                text: 'Cancelar',
                action: 'cancelaadicional',
            }]
        }];

        this.callParent(arguments);
    }
});
