Ext.define('Infosys_web.view.facturaelectronica.Emails' ,{
    extend: 'Ext.form.Panel',
    alias : 'widget.emails',
    
    requires: ['Ext.form.Panel','Ext.toolbar.Paging'],
    title : 'Registro Emails',
    autoHeight: true,

    autoShow: true,
    width: 700,
    height : 800,
    initComponent: function() {
        me = this;


     var tipoServer = Ext.create('Ext.data.Store', {
        fields: ['value', 'nombre'],
        data : [
            {"value":"SMTP", "nombre":"SMTP"},
            {"value":"IMAP", "nombre":"IMAP"}
        ]
    });


        response_rut = Ext.Ajax.request({
        async: false,
        url: preurl + 'facturas/existe_empresa/'});
        var obj_rut = Ext.decode(response_rut.responseText);
        var existe_empresa = obj_rut.existe ? true : false;

        if(existe_empresa){

            response_empresa = Ext.Ajax.request({
            async: false,
            url: preurl + 'facturas/get_empresa_json/'});
            var obj_empresa = Ext.decode(response_empresa.responseText);
            console.log(obj_empresa);

            var rut = obj_empresa.rut+"-"+obj_empresa.dv;
            var razon_social = obj_empresa.razon_social;
            var giro = obj_empresa.giro;
            var cod_actividad = obj_empresa.cod_actividad;
            var direccion = obj_empresa.dir_origen;
            var comuna = obj_empresa.comuna_origen;
            var fec_resolucion = obj_empresa.fec_resolucion;
            var nro_resolucion = obj_empresa.nro_resolucion;
            var logo = gbl_site + 'core/facturacion_electronica/images/' + obj_empresa.logo;

        }else{
            var rut = "";
            var razon_social = "";
            var giro = "";
            var cod_actividad = 0;
            var direccion = "";
            var comuna = "";
            var fec_resolucion = "";
            var nro_resolucion = 0;
            var logo = gbl_site + 'core/facturacion_electronica/images/sinimagen.jpg';

        }


        this.items = [
            {
                xtype: 'form',
                padding: '5 5 0 5',
                border: false,
                frame: true,
                style: 'background-color: #fff;',
                items: [
                        {
                        xtype: 'fieldcontainer',
                        fieldLabel: '',
                        labelWidth: 1500,
                        layout: {
                            type: 'hbox',
                            align: 'stretch'
                        },
                        items: [ 
                                {
                                    xtype: 'displayfield',
                                    fieldLabel : 'Email Contacto SII',
                                    labelStyle: ' font-weight:bold',
                                    value : "",
                                    width: 340,
                                    labelWidth: 200,
                                },{
                                    xtype: 'displayfield',
                                    itemId : 'estado_certificado',
                                    fieldLabel : 'Email Intercambio',
                                    labelStyle: 'font-weight:bold',
                                    value : "",
                                    labelWidth: 200,
                                }
                        ]
                    },{
                        xtype: 'fieldcontainer',
                        fieldLabel: '',
                        layout: {
                            type: 'hbox',
                            align: 'stretch'
                        },
                        items: [ 
                            {
                            xtype: 'textfield',
                            name: 'email_contacto',
                            itemId : 'email_contacto',
                            fieldLabel : 'Email',
                            labelStyle: ' font-weight:bold',
                            value : rut,
                            labelWidth: 150,
                            inputType: 'email',
                            allowBlank : false
                   
                            },                        
                            {
                            xtype: 'textfield',
                            labelWidth: 150,
                            name: 'email_intercambio',
                            itemId : 'email_intercambio',
                            fieldLabel: '&nbsp;&nbsp;Email',
                            labelStyle: ' font-weight:bold',
                            value : giro,
                            allowBlank : false
                   
                            }
                        ]
                    },{
                        xtype: 'fieldcontainer',
                        fieldLabel: '',
                        layout: {
                            type: 'hbox',
                            align: 'stretch'
                        },
                        items: [ 
                            {
                            xtype: 'textfield',
                            name: 'pass_contacto',
                            itemId : 'pass_contacto',
                            fieldLabel : 'Contrase&ntilde;a',
                            labelStyle: ' font-weight:bold',
                            value : rut,
                            labelWidth: 150,
                            inputType: 'password',
                            allowBlank : false
                   
                            },                        
                            {
                            xtype: 'textfield',
                            labelWidth: 150,
                            name: 'pass_intercambio',
                            itemId : 'pass_intercambio',
                            fieldLabel: '&nbsp;&nbsp;Contrase&ntilde;a',
                            labelStyle: ' font-weight:bold',
                            inputType: 'password',
                            value : giro,
                            allowBlank : false
                   
                            }
                        ]
                    },{
                        xtype: 'fieldcontainer',
                        fieldLabel: '',
                        layout: {
                            type: 'hbox',
                            align: 'stretch'
                        },
                        items: [ 
                            {
                            xtype: 'combobox',
                            store : tipoServer,
                            fieldLabel: 'Tipo Server',
                            labelStyle: ' font-weight:bold',
                            labelWidth: 150,
                            emptyText : 'Seleccionar',
                            editable: false,
                            itemId : 'tipoServer_contacto' ,
                            name : 'tipoServer_contacto' ,
                            forceSelection: true, 
                            allowBlank : false,
                            displayField : 'nombre',
                            valueField : 'value'                            

                            },                        
                            {
                            xtype: 'combobox',
                            store : tipoServer,
                            fieldLabel: 'Tipo Server',
                            labelStyle: ' font-weight:bold',
                            labelWidth: 150,
                            emptyText : 'Seleccionar',
                            editable: false,
                            itemId : 'tipoServer_intercambio' ,
                            name : 'tipoServer_intercambio' ,
                            forceSelection: true, 
                            allowBlank : false,
                            displayField : 'nombre',
                            valueField : 'value'                            

                            }
                        ]
                    },{
                        xtype: 'fieldcontainer',
                        fieldLabel: '',
                        layout: {
                            type: 'hbox',
                            align: 'stretch'
                        },
                        items: [ 
                            {
                            xtype: 'numberfield',
                            name: 'port_contacto',
                            itemId : 'port_contacto',
                            fieldLabel : 'Puerto',
                            labelStyle: ' font-weight:bold',
                            value : rut,
                            labelWidth: 150,
                            inputType: 'email',
                            allowBlank : false
                   
                            },{
                                xtype: 'numberfield',
                                fieldCls: 'required',
                                labelWidth: 150,
                                name: 'port_intercambio',
                                itemId: 'port_intercambio',
                                fieldLabel: '&nbsp;&nbsp;Puerto',
                                labelStyle: ' font-weight:bold',
                                inputType: 'email',
                                value : razon_social,
                                allowBlank : false
                           }
                        ]
                    },{
                        xtype: 'fieldcontainer',
                        fieldLabel: '',
                        layout: {
                            type: 'hbox',
                            align: 'stretch'
                        },
                        items: [ 
                            {
                            xtype: 'textfield',
                            name: 'host_contacto',
                            itemId : 'host_contacto',
                            fieldLabel : 'Host',
                            labelStyle: ' font-weight:bold',
                            value : rut,
                            labelWidth: 150,
                            allowBlank : false
                   
                            },                        
                            {
                            xtype: 'textfield',
                            labelWidth: 150,
                            name: 'host_intercambio',
                            itemId : 'host_intercambio',
                            fieldLabel: '&nbsp;&nbsp;Host',
                            labelStyle: ' font-weight:bold',
                            value : giro,
                            allowBlank : false
                   
                            }
                        ]
                    },
                    {
                        xtype: 'toolbar',
                        dock: 'bottom',
                        items: [{
                            iconCls: 'icon-save',
                            text: 'Guardar',
                            //disabled :  permite_carga ? false : true,
                            handler: function() {
                                var form = this.up('form').getForm();
                                if(form.isValid()){
                                    form.submit({
                                        url: preurl + 'facturas/put_empresa',
                                        waitMsg: 'Guardando...',
                                        success: function(fp, o) {

                                            Ext.Msg.alert('Atención', o.result.message);

                                        }
                                    });
                                }
                            }                            
                        }]
                    }
                ]
            }
        ];
        
        this.callParent(arguments);
    }
});
