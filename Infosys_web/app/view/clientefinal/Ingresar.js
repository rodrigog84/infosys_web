Ext.define('Infosys_web.view.clientefinal.Ingresar', {
    extend: 'Ext.window.Window',
    alias : 'widget.clientefinalingresar',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],

    title : 'Editar/Crear Cliente Final',
    layout: 'fit',
    autoShow: true,
    width: 480,
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
                    combineErrors: true,
                    labelWidth: 150,
                    msgTarget: 'side'
                },

                items: [
                    {
                        xtype: 'textfield',
                        name : 'id',
                        fieldLabel: 'id',
                        hidden:true
                    },
                    {
                        xtype: 'textfield',
                        name : 'rut',
                        fieldLabel: 'Rut'
                    },    
                    {
                        xtype: 'textfield',
                        name : 'nombre',
                        fieldLabel: 'Nombre'
                    },    
                    {
                        xtype: 'textfield',
                        name : 'direccion',
                        fieldLabel: 'Direcci&oacute;n'
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
                iconCls: 'icon-save',
                text: 'Grabar',
                action: 'grabarclientefinal'
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
