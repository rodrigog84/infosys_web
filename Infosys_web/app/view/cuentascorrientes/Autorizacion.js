Ext.define('Infosys_web.view.cuentascorrientes.Autorizacion' ,{
    extend: 'Ext.form.Panel',
    alias : 'widget.autorizacioncc',
    
    requires: ['Ext.form.Panel','Ext.toolbar.Paging'],
    title : 'Clave Autorizaci&oacute;n',
    autoHeight: true,

    autoShow: true,
    width: 700,
    height : 800,
    initComponent: function() {
        me = this;
        this.items = [
            {
                xtype: 'form',
                padding: '5 5 0 5',
                border: false,
                frame: true,
                style: 'background-color: #fff;',
                items: [
                       
                       {
                        xtype: 'displayfield',
                        itemId : 'clave_autorizacion',
                        fieldLabel : 'Clave:',
                        labelStyle: ' font-weight:bold',
                        fieldStyle: 'font-weight:bold',
                        value : '',
                        labelWidth: 200,
                    },
                     {
                        xtype: 'displayfield',
                        itemId : 'tiempo_restante',
                        fieldLabel : 'Tiempo Restante:',
                        labelStyle: '',
                        fieldStyle: '',
                        value : '',
                        labelWidth: 200,
                    },
                ]
            }
        ];
        
        this.callParent(arguments);
    }
});
