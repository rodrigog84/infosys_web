
Ext.define('Infosys_web.view.clientes.Autoriza', {
    extend: 'Ext.window.Window',
    alias: 'widget.autorizacionclientes',

    requires: [
        'Ext.toolbar.Toolbar',
        'Ext.toolbar.Fill',
        'Ext.button.Button',
        'Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.form.FieldContainer',
        'Ext.form.field.Checkbox'
    ],

    draggable: false,
    height: 200,
    width: 350,
    resizable: false,
    //closable: false,
    layout: 'fit',
    iconCls: 'icon-lock16',
    title: 'AUTORIZACION CLIENTES',
   
    initComponent: function() {
        
        var me = this;

        Ext.applyIf(me, {
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    items: [
                        {
                            xtype: 'tbfill'
                        },
                        {
                            xtype: 'button',
                            action: 'autorizaclientes',
                            iconCls: 'icon-lock',
                            scale: 'medium',
                            text: 'Ingresar'
                        }
                    ]
                }
            ],
            items: [
                {
                    xtype: 'form',
                    border: false,
                    bodyPadding: 10,
                    frameHeader: false,
                    title: '',
                    items: [
                        
                        {
                            xtype: 'textfield',
                            anchor: '100%',
                            //value: '123456',
                            fieldLabel: 'Clave Autoriza',
                            name: 'password',
                            itemId: 'enterId',
                            inputType: 'password'
                        },{
                            xtype: 'textareafield',
                            anchor: '100%',
                            //value: '123456',
                            fieldLabel: 'Observacion',
                            name: 'observacion',
                            itemId: 'observaId'
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});