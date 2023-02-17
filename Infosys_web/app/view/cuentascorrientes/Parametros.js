Ext.define('Infosys_web.view.cuentascorrientes.Parametros' ,{
    extend: 'Ext.form.Panel',
    alias : 'widget.parametroscc',
    
    requires: ['Ext.form.Panel','Ext.toolbar.Paging'],
    title : 'Par&aacute;metros Cancelaciones',
    autoHeight: true,

    autoShow: true,
    width: 700,
    height : 800,
    initComponent: function() {
        me = this;


     var tipoServer = Ext.create('Ext.data.Store', {
        fields: ['value', 'nombre'],
        data : [
            {"value":"smtp", "nombre":"smtp"},
            {"value":"imap", "nombre":"imap"}
        ]
    });


        response_email = Ext.Ajax.request({
        async: false,
        url: preurl + 'facturas/get_email/'});
        var obj_email = Ext.decode(response_email.responseText);
        var email = obj_email.data ? obj_email.data : false;


        response_tasa = Ext.Ajax.request({
        async: false,
        url: preurl + 'cuentacorriente/busca_parametro_cc/tasa_interes'});
        var obj_tasa = Ext.decode(response_tasa.responseText);
        var tasa_interes = obj_tasa.data ? obj_tasa.data : 0;


        response_dias = Ext.Ajax.request({
        async: false,
        url: preurl + 'cuentacorriente/busca_parametro_cc/dias_cobro'});
        var obj_dias = Ext.decode(response_dias.responseText);
        var dias_cobro = obj_dias.data ? obj_dias.data : 0;


        this.items = [
            {
                xtype: 'form',
                padding: '5 5 0 5',
                border: false,
                frame: true,
                style: 'background-color: #fff;',
                items: [
                       
                        {
                            xtype: 'numberfield',
                            name: 'tasa_interes',
                            itemId : 'tasa_interes',
                            fieldLabel : 'Tasa Inter&eacute;s',
                            labelStyle: ' font-weight:bold',
                            labelWidth: 150,
                            allowBlank : false,
                            value : tasa_interes
                   
                        },{
                            xtype: 'numberfield',
                            name: 'dias_cobro',
                            itemId : 'dias_cobro',
                            fieldLabel : 'D&iacute;as Cobro',
                            labelStyle: ' font-weight:bold',
                            allowDecimals : false,
                            labelWidth: 150,
                            allowBlank : false,
                            value : dias_cobro
                   
                        },{
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
                                        url: preurl + 'cuentacorriente/actualiza_parametros_cc',
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
