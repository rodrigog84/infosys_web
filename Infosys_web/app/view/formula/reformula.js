Ext.define('Infosys_web.view.formula.reformula', {
    extend: 'Ext.window.Window',
    alias : 'widget.reformulacion',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],
    //y: 50,
    title : 'REFORMULACION',
    layout: 'fit',
    autoShow: true,
    width: 620,
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
                    labelAlign: 'left',
                    allowBlank: false,
                    combineErrors: false,
                    labelWidth: 450,
                    msgTarget: 'side'
                },

                items: [
                      {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        //labelWidth: 600,
                        fieldLabel: '<b>SE MODIFICARAN LOS PROCENTAJES AUTOMATICAMENTE</b>',
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
                            action:'reformula'
                        },{
                            xtype: 'displayfield',
                            width: 30
                           
                        },{
                            xtype: 'button',
                            iconCls : '',
                            text: 'NO',
                            action:'limpia'
                        }
                        ]
                    }
                ]

             
            }
        ];
        
       

        this.callParent(arguments);
    }
});
