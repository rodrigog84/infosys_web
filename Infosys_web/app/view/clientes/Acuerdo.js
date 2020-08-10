Ext.define('Infosys_web.view.clientes.Acuerdo', {
    extend: 'Ext.window.Window',
    alias : 'widget.acuerdodepagoclientes',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],
    //y: 50,
    title : 'Acuerdo de Pago a Clientes',
    layout: 'fit',
    autoShow: true,
    width: 560,
    height: 375,
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
                        xtype: 'datefield',
                        fieldCls: 'required',
                        maxHeight: 25,
                        labelWidth: 50,
                        width: 170,
                        fieldLabel: '<b>FECHA</b>',
                        itemId: 'fechaId',
                        name: 'fecha_subida',
                        value: new Date()
                    },{
                        xtype: 'combo',
                        itemId: 'tipoSeleccionId',
                        fieldLabel: 'Ciudad Donde se Firma',
                        forceSelection : true,
                        editable : false,
                        width: 440,
                        labelWidth: 140,
                        valueField : 'id',
                        displayField : 'nombre',
                        emptyText : "Seleccione",
                        store : 'clientes.Selector7'
                    },{
                        xtype: 'combo',
                        itemId: 'tipoSeleccion2Id',
                        fieldLabel: 'Tipo de Acuerdo',
                        forceSelection : true,
                        width: 440,
                        labelWidth: 170,
                        editable : false,
                        valueField : 'id',
                        displayField : 'nombre',
                        emptyText : "Seleccione",
                        store : 'clientes.Selector1'
                    },{
                    msgTarget: 'side',
                    fieldLabel: 'Id',
                    xtype: 'textfield',
                    width: 140,
                    labelWidth:70,
                    name : 'id',
                    itemId: 'idId',
                    hidden: true
                    },{
                    msgTarget: 'side',
                    fieldLabel: 'Razon Social',
                    xtype: 'textfield',
                    labelWidth:100,
                    width: 520,
                    name : 'nombre',
                    itemId: 'nombreId'
                    },{
                    msgTarget: 'side',
                    fieldLabel: 'Giro',
                    xtype: 'textfield',
                    width: 350,
                    name : 'giro',
                    labelWidth:70,
                    itemId: 'giroId',
                    hidden: true
                    },{
                    msgTarget: 'side',
                    fieldLabel: 'Rut',
                    xtype: 'textfield',
                    width: 180,
                    labelWidth:70,
                    name : 'rut',
                    itemId: 'rutId'
                    },{
                    msgTarget: 'side',
                    fieldLabel: 'Representante Legal',
                    labelWidth:140,
                    xtype: 'textfield',
                    width: 520,
                    name : 'representante',
                    itemId: 'prepresentaId'
                     },{
                    msgTarget: 'side',
                    fieldLabel: 'Rut 2',
                    xtype: 'textfield',
                    labelWidth:70,
                    width: 180,
                    name : 'rut2',
                    itemId: 'rut2Id'
                    },{
                    msgTarget: 'side',
                    fieldLabel: 'Representante Legal2',
                    labelWidth:140,
                    xtype: 'textfield',
                    width: 520,
                    name : 'representante2',
                    itemId: 'representante2Id'
                    },{
                    msgTarget: 'side',
                    fieldLabel: 'Correo',
                    xtype: 'textfield',
                    width: 350,
                    name : 'correo',
                    itemId: 'correoId'
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
                action: 'generaacuerdopdf',
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
