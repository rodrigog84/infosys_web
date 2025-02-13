Ext.define('Infosys_web.view.proveedores.Ingresar', {
    extend: 'Ext.window.Window',
    alias : 'widget.proveedoringresar',

    requires: ['Ext.form.Panel','Ext.form.field.Text'],

    title : 'Editar/Crear Proveedores',
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
                    //allowBlank: false,
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
                      items: [{
                        xtype: 'textfield',
                        name : 'rutgraba',
                        itemId: 'rutgrabaId',
                        fieldLabel: 'rutgraba',
                        hidden: true
                     },{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        items: [{
                        msgTarget: 'side',
                        fieldLabel: 'Rut Proveedor',
                        xtype: 'textfield',
                        enableKeyEvents: true,
                        width: 250,
                        name : 'rut',
                        itemId: 'rutId'
                    }]
                     },]
                       }]
                     },{
                        xtype: 'textfield',
                        name : 'id',
                        itemId: 'id_proveedor',
                        fieldLabel: 'id',
                        //allowBlank: true,
                        hidden: true
                    },{
                        xtype: 'textfield',
                        name : 'nombres',
                        itemId: 'nombre_id',
                        fieldLabel: 'Nombres'
                    },{
                        xtype: 'textfield',
                        itemId: 'direccionId',
                        name : 'direccion',
                        fieldLabel: 'Direccion'
                    },{
                        xtype: 'combo',
                        itemId: 'giroId',
                        fieldLabel: 'Giro',
                        name: 'id_giro',
                        store: 'Cod_activ',
                        queryMode: 'local',
                        forceSelection: true,
                        displayField: 'nombre',
                        valueField: 'id',
                        listConfig: {
                            minWidth: 450
                        },
                        width: 480
                        
                    },{
                       xtype: 'combo',
                        itemId: 'tipoCiudadId',
                        fieldLabel: 'Ciudad',
                        name: 'id_ciudad',
                        store: 'TipoCiudades',
                        queryMode: 'local',
                        forceSelection: true,
                        displayField: 'nombre',
                        valueField: 'id',
                        listConfig: {
                            minWidth: 450
                        },
                        anchor: '65%'
                    },{
                         xtype: 'combo',
                        itemId: 'tipoComunaId',
                        fieldLabel: 'Comuna',
                        name: 'id_comuna',
                        store: 'TipoComunas',
                        queryMode: 'local',
                        forceSelection: true,
                        displayField: 'nombre',
                        valueField: 'id',
                        listConfig: {
                            minWidth: 450
                        },
                        anchor: '65%'
                    },{
                        xtype: 'textfield',
                        name : 'fono',
                        itemId: 'fonoId',
                        fieldLabel: 'Telefono'
                    },{
                        xtype: 'textfield',
                        name : 'e_mail',
                        itemId: 'e_mailId',
                        fieldLabel: 'E-Mail'
                        //allowBlank: true
                    },{
                        xtype: 'datefield',
                        name : 'fecha_ult_actualiz',
                        itemId: 'fecha_ult_actualizId',
                        fieldLabel: 'Fecha Ultima Actualizacion',
                        value: new Date(),
                        format: 'Y-m-d'
                    },{
                        xtype: 'combo',
                        itemId: 'tipoEstadoId',
                        fieldLabel: 'Estado',
                        forceSelection : true,
                        anchor: '65%',
                        editable : false,
                        name : 'estado',
                        valueField : 'id',
                        displayField : 'nombre',
                        emptyText : "Estado",
                        store : 'vendedores.Activo',
                        //allowBlank: false
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
                action: 'grabarproveedor'
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
