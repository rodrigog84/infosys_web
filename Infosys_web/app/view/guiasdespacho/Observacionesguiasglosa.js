Ext.define('Infosys_web.view.guiasdespacho.Observacionesguiasglosa', {
    extend: 'Ext.window.Window',
    alias : 'widget.observacionesguiasglosa',

    requires: ['Ext.form.Panel',
               'Ext.form.field.Text'],

    title : 'Observaciones Documentos',
    layout: 'fit',
    autoShow: true,
    width: 380,
    height: 610,
    modal: true,
    iconCls: 'icon-sheet',

    initComponent: function() {
        this.items = [
            {
                xtype: 'form',
                padding: '5 5 0 5',
                autoScroll: true,
                border: false,
                style: 'background-color: #fff;',
                
                fieldDefaults: {
                    //anchor: '100%',
                    //labelAlign: 'left',
                    //allowBlank: false,
                    combineErrors: true,
                    //labelWidth: 150,
                    msgTarget: 'side'
                },
                items: [ {
                        xtype: 'textfield',
                        name : 'id',
                        itemId: 'FactId',
                        fieldLabel: 'id',
                        hidden:true
                    },{
                        xtype: 'textfield',
                        name : 'valida',
                        value: "NO",
                        itemId: 'validaId',
                        fieldLabel: 'valida',
                        hidden:true
                    },{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        items: [{
                            msgTarget: 'side',
                            fieldLabel: 'Rut ',
                            xtype: 'textfield',
                            enableKeyEvents: true,
                            labelWidth: 60,
                            width: 180,
                            name : 'rut',
                            itemId: 'rutmId',
                            hidden: true
                        },{
                            msgTarget: 'side',
                            fieldLabel: 'Rut ',
                            xtype: 'textfield',
                            enableKeyEvents: true,
                            labelWidth: 60,
                            width: 180,
                            name : 'rut',
                            itemId: 'rutId'
                        },{xtype: 'splitter'}
                        ,{
                            xtype: 'button',
                            text: 'Buscar',
                            itemId: 'validarutId',
                            maxHeight: 25,
                            width: 70,
                            action: 'validar'
                        }]
                     },{
                        xtype: 'textfield',
                        fieldCls: 'required',
                        labelWidth: 60,
                        width: 350,
                        height: 30,
                        fieldLabel: 'Nombre',
                        itemId: 'nombreId',
                        name: 'nombre'                            
                    },{
                        xtype: 'textfield',
                        fieldCls: 'required',
                        labelWidth: 60,
                        width: 350,
                        height: 30,
                        fieldLabel: 'Destino',
                        itemId: 'destinoId',
                        name: 'destino'                            
                    },{
                        xtype: 'textfield',
                        labelWidth: 100,
                        width: 350,
                        height: 30,
                        maxLength: 20,
                        fieldLabel: 'Comuna Destino',
                        itemId: 'comunadestinoId',
                        name: 'comuna_destino'
                    },{
                        xtype: 'textfield',
                        fieldCls: 'required',
                        labelWidth: 100,
                        width: 350,
                        height: 30,
                        fieldLabel: 'Empresa de Transporte',
                        itemId: 'empresaId',
                        name: 'empresa'                            
                    },{
                        xtype: 'textfield',
                        fieldCls: 'required',
                        labelWidth: 100,
                        width: 250,
                        height: 30,
                        fieldLabel: 'Rut Empresa Transp.',
                        emptyText: 'Ej: 76123456-7',
                        enableKeyEvents: true,
                        itemId: 'rutempresaId',
                        name: 'rut_empresa'                            
                    },{
                        xtype: 'textfield',
                        fieldCls: 'required',
                        width: 180,
                        height: 25,
                        fieldLabel: 'Patente Camion',
                        itemId: 'camionId',
                        name: 'pat_camion'                            
                    },{
                        xtype: 'textfield',
                        fieldCls: 'required',
                        width: 180,
                        height: 25,
                        fieldLabel: 'Patente Carro',
                        itemId: 'carroId',
                        name: 'pat_carro'                            
                    },{
                        xtype: 'textfield',
                        fieldCls: 'required',
                        width: 200,
                        height: 25,
                        fieldLabel: 'Fono',
                        itemId: 'fonoId',
                        name: 'fono'                            
                    },{
                        xtype: 'fieldcontainer',
                        layout: 'hbox',
                        height: 30,
                        items: [{
                            xtype: 'datefield',
                            fieldCls: 'required',
                            labelWidth: 100,
                            width: 235,
                            height: 25,
                            fieldLabel: 'Fecha Salida',
                            format: 'd/m/Y',
                            submitFormat: 'Y-m-d',
                            value: new Date(),
                            itemId: 'fechasalidaId',
                            name: 'fecha_salida'
                        },{
                            xtype: 'splitter'
                        },{
                            xtype: 'timefield',
                            fieldCls: 'required',
                            labelWidth: 35,
                            width: 105,
                            height: 25,
                            fieldLabel: 'Hora',
                            format: 'H:i',
                            increment: 15,
                            value: new Date(),
                            itemId: 'horasalidaId',
                            name: 'hora_salida'
                        }]
                    },{
                        xtype: 'datefield',
                        fieldCls: 'required',
                        labelWidth: 100,
                        width: 235,
                        height: 25,
                        fieldLabel: 'Llegada Estimada',
                        format: 'd/m/Y',
                        submitFormat: 'Y-m-d',
                        value: new Date(),
                        itemId: 'fechallegadaId',
                        name: 'fecha_llegada'
                    },{
                        xtype: 'textareafield',
                        fieldCls: 'required',
                        width: 350,
                        height: 80,
                        fieldLabel: 'Observaciones',
                        itemId: 'observaId',
                        name: 'observaciones'                            
                    },{
                        xtype: 'textareafield',
                        fieldCls: 'required',
                        width: 350,
                        height: 140,
                        fieldLabel: 'Num Factura',
                        itemId: 'idfactura',
                        name: 'id_factura',
                        hidden: true                            
                    }
                ]
            }
        ];
        
      this.dockedItems = [{
        xtype: 'toolbar',
        dock: 'bottom',
        id:'butons',
        ui: 'footer',
        items: ['->', {
            xtype: 'button',
            //iconCls: 'icono',
            scale: 'large',
            action: 'ingresaobsguias',
            text: 'INGRESAR'
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
