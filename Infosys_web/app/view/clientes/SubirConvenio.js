Ext.define('Infosys_web.view.clientes.SubirConvenio', {
    extend: 'Ext.window.Window',
    alias : 'widget.subirConvenio',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],
    //y: 50,
    title : 'Subir Convenios Pago',
    layout: 'fit',
    autoShow: true,
    width: 660,
    height: 180,
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
                    allowBlank: false,
                    combineErrors: false,
                    //labelWidth: 70,
                    msgTarget: 'side'
                },

                items: [
                      {
                        xtype: 'filefield',
                        id: 'form-file',
                        labelWidth: 60,
                        width: 250,
                        emptyText: 'Pdf',
                        fieldLabel: 'Archivo',
                        name: 'archivo',
                        itemId: 'archivoId',
                        buttonText: 'Examinar'
                    },{
                        xtype: 'datefield',
                        fieldCls: 'required',
                        maxHeight: 25,
                        labelWidth: 50,
                        width: 170,
                        fieldLabel: '<b>FECHA</b>',
                        itemId: 'fechasubidaId',
                        name: 'fecha_subida',
                        value: new Date()
                    },{
                        xtype: 'textfield',
                        name : 'id_cliente',
                        itemId: 'id_cliente',
                        fieldLabel: 'id',
                        hidden:true
                    },{
                        xtype: 'combo',
                        itemId: 'tipoSeleccionAId',
                        fieldLabel: 'TIPO DE ACUERDO',
                        forceSelection : true,
                        editable : false,
                        width: 440,
                        labelWidth: 180,
                        valueField : 'id',
                        name : 'id',
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
                iconCls: '',
                action: 'Subironvenio2',
                text : 'Subir'
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
