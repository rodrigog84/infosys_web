Ext.define('Infosys_web.view.facturaganado.Cobrosadic', {
    extend: 'Ext.window.Window',
    alias : 'widget.cobrosadicfacturasganado',

    requires: ['Ext.form.Panel',
               'Ext.form.field.Text'],

    title : 'Cobros Adicionales',
    layout: 'fit',
    autoShow: true,
    width: 380,
    height: 250,
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
                    //anchor: '100%',
                    //labelAlign: 'left',
                    //allowBlank: false,
                    combineErrors: true,
                    //labelWidth: 150,
                    msgTarget: 'side'
                },
                items: [{
                            xtype: 'fieldset',
                            title: 'Cobros',
                            items: [
                                     {
                                            xtype: 'textfield',
                                            name : 'id',
                                            itemId: 'FactId',
                                            fieldLabel: 'id',
                                            hidden:true
                                        },{
                                                xtype: 'numberfield',
                                                fieldCls: 'required',
                                                fieldLabel: 'Comisi&oacute;n Ganado (%)',
                                                name:'comisionganado',
                                                itemId:'comisionganado',
                                                width: 300,
                                                height: 25,     
                                                labelWidth: 200,                                           
                                                align: 'right',
                                                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")},
                                                //anchor: '20%',
                                                readOnly : false,
                                        },{
                                                xtype: 'numberfield',
                                                fieldCls: 'required',
                                                fieldLabel: 'Otros Cargos (%)',
                                                name:'otroscargos',
                                                itemId:'otroscargos',
                                                width: 300,
                                                height: 25,    
                                                labelWidth: 200,                                             
                                                align: 'right',
                                                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")},
                                                //anchor: '20%',
                                                readOnly : false,
                                        },{
                                                xtype: 'numberfield',
                                                fieldCls: 'required',
                                                fieldLabel: 'Costo Mayor Plazo (%)',
                                                name:'costomayorplazo',
                                                itemId:'costomayorplazo',
                                                width: 300,
                                                height: 25,    
                                                labelWidth: 200,                                             
                                                align: 'right',
                                                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")},
                                                //anchor: '20%',
                                                readOnly : false,

                                        }
                                    ]
                        }]
            }
        ];
        
      this.dockedItems = [{
        xtype: 'toolbar',
        dock: 'bottom',
        id:'butons',
        ui: 'footer',
        items: ['->', {
            xtype: 'button',
            //iconCls: 'icono',
            scale: 'large',
            action: 'ingresacobadic',
            text: 'ACEPTAR'
        }]
    }];

        this.callParent(arguments);
    }
});
