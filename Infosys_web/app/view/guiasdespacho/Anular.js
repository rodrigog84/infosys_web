Ext.define('Infosys_web.view.guiasdespacho.Anular', {
    extend: 'Ext.window.Window',
    alias : 'widget.anulaguias',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],
    //y: 50,
    title : 'ANULA GUIAS DESPACHO',
    layout: 'fit',
    autoShow: true,
    width: 320,
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
                    allowBlank: false,
                    combineErrors: false,
                    labelWidth: 150,
                    msgTarget: 'side'
                },

                items: [
                      {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        fieldLabel: '<b>ESTA SEGURO</b>',
                        items: [
                        {
                            xtype: 'textfield',
                            itemId: 'facturaId',
                            name : 'id_factura',
                            hidden: true
                        },{
                            xtype: 'button',
                            iconCls : '',
                            text: ' SI ',
                            action:'anularguia'
                        },{
                            xtype: 'displayfield',
                            width: 30
                           
                        },{
                            xtype: 'button',
                            iconCls : '',
                            text: 'NO',
                            action:'salirguias'
                        }
                        ]
                    }
                ]

             
            }
        ];
        
       

        this.callParent(arguments);
    }
});
