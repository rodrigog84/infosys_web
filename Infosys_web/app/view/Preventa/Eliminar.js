Ext.define('Infosys_web.view.Preventa.Eliminar', {
    extend: 'Ext.window.Window',
    alias : 'widget.eliminarpreventa',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],
    //y: 50,
    title : 'ELIMINAR ORDEN DE TRABAJO',
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
                            xtype: 'numberfield',
                            itemId: 'idclienteID',
                            name : 'id_cliente',
                            hidden: true
                        },{
                            xtype: 'button',
                            iconCls : '',
                            text: ' SI ',
                            action:'eliminar'
                        },{
                            xtype: 'displayfield',
                            width: 30
                           
                        },{
                            xtype: 'button',
                            iconCls : '',
                            text: 'NO',
                            action:'salirpreventa'
                        }
                        ]
                    }
                ]

             
            }
        ];
        
       

        this.callParent(arguments);
    }
});
