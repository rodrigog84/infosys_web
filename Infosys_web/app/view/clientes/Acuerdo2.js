Ext.define('Infosys_web.view.clientes.Acuerdo2', {
    extend: 'Ext.window.Window',
    alias : 'widget.acuerdodepagoconcretado',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],
    //y: 50,
    title : 'Acuerdo de Pago a Clientes',
    layout: 'fit',
    autoShow: true,
    width: 500,
    height: 120,
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
                    labelAlign: 'left',
                    //allowBlank: false,
                    combineErrors: false,
                    msgTarget: 'side'
                },
                items: [{
                        xtype: 'textfield',
                        name : 'id',
                        itemId: 'id_cliente',
                        fieldLabel: 'id',
                        hidden:true
                    },{
                        xtype: 'combo',
                        itemId: 'tipoSeleccionAId',
                        fieldLabel: 'SITUACION ACUERDO',
                        forceSelection : true,
                        editable : false,
                        width: 440,
                        labelWidth: 180,
                        valueField : 'id',
                        displayField : 'nombre',
                        emptyText : "Seleccione",
                        store : 'clientes.Selector4'
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
                action: 'generarpdf',
                text : 'Generar'
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
