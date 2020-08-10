Ext.define('Infosys_web.view.Transportes.Ingresar', {
    extend: 'Ext.window.Window',
    alias : 'widget.transportistasingresar',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],

    title : 'Editar/Crear Transportistas',
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
                    xtype: 'fieldset', 
                    height:60,
                    title: 'Ingreso de Datos',
                    items: [
                    {
                      xtype: 'container',
                      layout: {
                          type: 'vbox'
                      },
                      defaults: {
                          flex: 1
                      },
                      items: [
                      {
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        items: [{
                        msgTarget: 'side',
                        fieldLabel: 'Rut Transportistas',
                        xtype: 'textfield',
                        //flex: 1
                        width: 250,
                        name : 'rut',
                        itemId: 'rutId'
                                                 
                        },
                        {xtype: 'splitter'},
                        {
                          xtype: 'button',
                          iconCls: 'icon-add',
                          width: 75,
                          allowBlank: true,
                          action: 'validarut',
                          text : 'Validar'
                        }]
                      },]
                       }]
                     },{
                        xtype: 'textfield',
                        name : 'id',
                        itemId: 'id',
                        fieldLabel: 'id',
                        hidden:true
                    },{
                        xtype: 'textfield',
                        name : 'nombre',
                        itemId: 'nombreId',
                        fieldLabel: 'Nombres'
                    },{
                        xtype: 'textfield',
                        name : 'ciudad',
                        itemId: 'ciudadId',
                        fieldLabel: 'Ciudad'
                    },{
                        xtype: 'textfield',
                        name : 'camion',
                        fieldLabel: 'Patente Camion',
                        itemId: 'camionId'
                    
                    },{
                        xtype: 'textfield',
                        name : 'carro',
                        fieldLabel: 'Patente Carro',
                        itemId: 'carroId'
                       
                    },{
                        xtype: 'textfield',
                        name : 'fono',
                        fieldLabel: 'Telefono',
                        itemId: 'fonoId',
                      
                    },{
                        xtype: 'datefield',
                        name : 'fecha_ult_actualiz',
                        itemId: 'fechaId',
                        fieldLabel: 'Fecha Ultima Actualizacion',
                        format: 'Y-m-d'
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
                action: 'grabartransportistas'
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
