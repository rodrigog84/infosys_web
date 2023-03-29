Ext.define('Infosys_web.view.cuentascorrientes.modificaTasa' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.modificatasa',
    
    title : 'Modifica Tasa Inter&eacute;s',
    autoHeight: false,

    autoShow: true,
    width: 800,
    height: 160,
    initComponent: function() {
        me = this;
   /*     var idfactura = me.idfactura;
        estado_envio_dte = "";
        estado_dte = "";


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

*/

        this.items = [
            {
                xtype: 'form',
                padding: '1 1 1 1',
                border: false,
                frame: false,
                style: 'background-color: #fff;',
                waitMsgTarget: true, 
                //icon: 'images/download-icon.png',  // Use a URL in the icon config
                viewConfig:{
                    loadingCls: 'images/download-icon.png'
                },                
                items: [  
                    {
                            xtype: 'numberfield',
                            fieldLabel: 'Tasa Inter&eacute;s',
                            labelStyle: ' font-weight:bold',
                            labelWidth: 200,
                            name: 'tasainteres',
                            itemId: 'tasainteres',
                           // value : tasa_interes,
                    },
                    {
                            xtype: 'numberfield',
                            fieldLabel: 'D&iacute;as Cobro',
                            labelStyle: ' font-weight:bold',
                            labelWidth: 200,
                            allowDecimals : false,
                            name: 'diascobro',
                            itemId: 'diascobro',
                           // value : dias_cobro,
                    },
                    {
                            xtype: 'numberfield',
                            fieldLabel: 'Clave Autorizaci&oacute;n',
                            labelStyle: ' font-weight:bold',
                            labelWidth: 200,
                            allowDecimals : false,
                            name: 'claveautorizacion',
                            itemId: 'claveautorizacion',
                    },
                        {
                        xtype: 'toolbar',
                        dock: 'bottom',
                        items: [
                        {
                            xtype: 'button',
                            iconCls: 'icon-save',
                            text: 'Modificar Tasa Cancelaci&oacute;n Actual',
                            handler: function() {
                                var form = this.up('form').getForm();
                                if(form.isValid()){
                                    /*form.submit({
                                        url: preurl + 'cuentacorriente/valida_actualiza_tasa',
                                        waitMsg: 'Validando...',
                                        success: function(fp, o) {

                                            //Ext.Msg.alert('Atención', o.result.message); 
                                            //console.log(o.result.valida)
                                            //me.down('#permiteactualiza').setValue(o.result.valida)
                                            //if(o.result.valida == 1){
                                              //  me.down('#permiteactualiza').setValue(o.result.valida)
                                                //console.log('valido')
                                                //e.dit = Ext.create('Infosys_web.view.cuentascorrientes.CancelacionesIngresar')
                                                //console.log(edit)
                                                //var vista = me.up('form');
                                                
                                               // vista.fireEvent('actualizaciontasacorrecta')
                                            //}   
                                            //this.close;


                                        }
                                    });*/

                                    var vista = this.up('modificatasa');
                                    vista.fireEvent('actualizaciontasacorrecta')                                     
                                }

                                                               
                            }                          
                        }                        ]
                    }
                ],
            }
        ];
        
        this.callParent(arguments);
    }
});
