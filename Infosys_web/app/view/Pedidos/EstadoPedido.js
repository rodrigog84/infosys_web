Ext.define('Infosys_web.view.Pedidos.EstadoPedido', {
    extend: 'Ext.window.Window',
    alias : 'widget.estadopedidos',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],
    //y: 50,
    title : 'ESTADO DE PEDIDO',
    layout: 'fit',
    autoShow: true,
    width: 950,
    height: 300,
    modal: true,
    iconCls: 'icon-sheet',

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
                    //allowBlank: false,
                    combineErrors: false,
                    labelWidth: 70,
                    msgTarget: 'side'
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
                            items: [ {
                                    xtype: 'numberfield',
                                    name: 'id_pedido',
                                    itemId: 'pedidoId',
                                    hidden: true
                                  
                                },{
                                    xtype: 'numberfield',
                                    name: 'id_produccion',
                                    itemId: 'produccionId',
                                    hidden: true
                                  
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    width: 250,
                                    labelWidth: 150,
                                    name: 'num_pedido',
                                    itemId: 'numpedidoId',
                                    fieldLabel: '<b>NUMERO PEDIDO</b>',
                                    readOnly: true
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'datefield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    labelWidth: 50,
                                    width: 135,
                                    fieldLabel: '<b>FECHA</b>',
                                    itemId: 'fechaPEDIDOId',
                                    name: 'fecha_pedido',
                                    value: new Date(),
                                    readOnly: true
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    width: 250,
                                    labelWidth: 170,
                                    name: 'num_produccion',
                                    itemId: 'numproduccionId',
                                    fieldLabel: '<b>NUMERO PRODUCCION</b>',
                                    readOnly: true
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'datefield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    labelWidth: 165,
                                    width: 250,
                                    fieldLabel: '<b>FECHA PRODUCCION</b>',
                                    itemId: 'fechaproduccionId',
                                    name: 'fecha_produccion',
                                    value: new Date(),
                                    readOnly: true
                                }
                            ]
                        },{
                            xtype: 'fieldcontainer',
                            height: 37,
                            labelWidth: 120,
                            width: 462,
                            fieldLabel: '',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [ {
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    width: 450,
                                    labelWidth: 80,
                                    name: 'nom_formula',
                                    itemId: 'nomformulaId',
                                    fieldLabel: '<b>FORMULA</b>',
                                    readOnly: true,
                                    hidden: true
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    width: 450,
                                    labelWidth: 80,
                                    name: 'nom_producto',
                                    itemId: 'nomProductoId',
                                    fieldLabel: '<b>PRODUCTO</b>',
                                    readOnly: true
                                }
                            ]
                        },{
                            xtype: 'fieldcontainer',
                            height: 37,
                            labelWidth: 120,
                            width: 462,
                            fieldLabel: '',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [ {
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    width: 240,
                                    labelWidth: 140,
                                    name: 'cant_pedido',
                                    itemId: 'cantpedidoId',
                                    fieldLabel: '<b>CANTIDAD PEDIDO</b>',
                                    align: 'left',
                                    //renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")},
                                    readOnly: true
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    width: 260,
                                    labelWidth: 180,
                                    align: 'left',
                                    name: 'cant_producido',
                                    itemId: 'cantproducidoId',
                                    fieldLabel: '<b>CANTIDAD PRODUCIDO</b>',
                                    //renderer: function(valor){return Ext.util.Format.number((valor),"0,00")},
                                    readOnly: true
                                },{
                                    xtype: 'displayfield',
                                    width: 10                                   
                                },{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    width: 300,
                                    labelWidth: 200,
                                    align: 'left',
                                    name: 'cant_real',
                                    itemId: 'cantprodrealId',
                                    fieldLabel: '<b>CANTIDAD REAL PRODUCIDO</b>',
                                    renderer: function(valor){return Ext.util.Format.number((valor),"0.000,00")},
                                    readOnly: true
                                }
                            ]
                        },{
                            xtype: 'fieldcontainer',
                            height: 37,
                            labelWidth: 120,
                            width: 462,
                            fieldLabel: '',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [ {
                                    xtype: 'datefield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    labelWidth: 150,
                                    width: 260,
                                    fieldLabel: '<b>FECHA INICIO</b>',
                                    itemId: 'fechainicioId',
                                    name: 'fecha_inicio',
                                    value: new Date(),
                                    readOnly: true
                                },{
                                    xtype: 'displayfield',
                                    width: 100                                   
                                },{
                                    xtype: 'datefield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    labelWidth: 150,
                                    width: 260,
                                    fieldLabel: '<b>FECHA TERMINO</b>',
                                    itemId: 'fechaterminoId',
                                    name: 'fecha_termino',
                                    value: new Date(),
                                    readOnly: true
                                }
                            ]
                        },{
                            xtype: 'fieldcontainer',
                            height: 37,
                            labelWidth: 120,
                            width: 462,
                            fieldLabel: '',
                            layout: {
                                type: 'hbox',
                                align: 'stretch'
                            },
                            items: [ {
                                    xtype: 'timefield',
                                    fieldCls: 'required',
                                    format: 'H:i',
                                    msgTarget: 'side',
                                    labelWidth: 150,
                                    width: 260,
                                    maxHeight: 25,
                                    fieldLabel: '<b>HORA INICIO</b>',
                                    itemId: 'horainicioId',
                                    name : 'hora_inicio',   
                                    readOnly: true
                                },{
                                    xtype: 'displayfield',
                                    width: 100                                  
                                },{
                                     xtype: 'timefield',
                                    fieldCls: 'required',
                                    format: 'H:i',
                                    msgTarget: 'side',
                                    labelWidth: 150,
                                    width: 260,
                                    maxHeight: 25,
                                    fieldLabel: '<b>HORA TERMINO</b>',
                                    itemId: 'horaterminoId',
                                    name : 'hora_termino',   
                                    readOnly: true                                    
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
            items: ['->',{
                xtype: 'button',
                iconCls: 'icon-edit',
                action: 'verproduccion',
                text : 'VER PRODUCCION'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrarestado',
                text : 'Cerrar'
            }]
        }];

        this.callParent(arguments);
    }
});
