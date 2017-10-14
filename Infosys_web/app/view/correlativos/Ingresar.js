Ext.define('Infosys_web.view.correlativos.Ingresar', {
    extend: 'Ext.window.Window',
    alias : 'widget.correlativosingresar',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],

    title : 'Editar/Crear Correlativos',
    layout: 'fit',
    autoShow: true,
    width: 250,
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
                    combineErrors: true,
                    labelWidth: 80,
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
                        name : 'nombre',
                        fieldLabel: 'Tipo Documento'
                    },    
                    {
                        xtype: 'textfield',
                        name : 'correlativo',
                        fieldLabel: 'Correl. Inicio'
                    },    
                    {
                        xtype: 'textfield',
                        name : 'hasta',
                        fieldLabel: 'Correl. Final'
                    },{
                        xtype: 'datefield',
                        fieldCls: 'required',
                        maxHeight: 25,
                        //labelWidth: 50,
                        //width: 170,
                        fieldLabel: '<b>FECHA</b>',
                        itemId: 'fechacorrelId',
                        name: 'fecha_venc',
                        value: new Date()
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
                action: 'grabarcorrelativos'
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
