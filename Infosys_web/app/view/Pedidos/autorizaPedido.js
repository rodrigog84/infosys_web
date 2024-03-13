Ext.define('Infosys_web.view.pedidos.autorizaPedido' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.autorizapedido',
    
    title : 'Autoriza Pedido',
    autoHeight: false,

    autoShow: true,
    width: 800,
    height: 200,
    initComponent: function() {
        me = this;
        console.log(me.data)
        num_pedido = me.data.num_pedido;
        fec_pedido = me.data.fecha_pedido;
        razon_social = me.data.nombre_cliente;
        idpedido = me.data.id;

        this.items = [
            {
                xtype: 'form',
                padding: '5 5 0 5',
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
                        xtype: 'displayfield',
                        itemId : 'num_pedido',
                        fieldLabel : 'N&uacute;mero',
                        labelStyle: ' font-weight:bold',
                        //fieldStyle: 'font-weight:bold',
                        value : num_pedido,
                        labelWidth: 200,
                    
                    },  

                     
                    {
                        xtype: 'displayfield',
                        itemId : 'fec_pedido',
                        fieldLabel : 'Fecha',
                        labelStyle: ' font-weight:bold',
                        value : fec_pedido,
                        labelWidth: 200,
                    
                    },   
                    {
                        xtype: 'displayfield',
                        itemId : 'razon_social',
                        fieldLabel : 'Raz&oacute;n Social ',
                        labelStyle: ' font-weight:bold',
                        value : razon_social,
                        labelWidth: 200,
                    
                    },{
                        xtype: 'toolbar',
                        dock: 'bottom',
                        items: [
                        {
                            iconCls: 'icon-save',
                            text: 'Autorizar Pedido',
                            handler: function(grid, rowIndex, colIndex) {
                               // var rec = grid.getStore().getAt(rowIndex);
                                //salert("Edit " + rec.get('firstname'));
                                var vista = this.up('autorizapedido');
                                vista.fireEvent('autorizaPedido',idpedido)
                            },                            
                            /*handler: function() {
                                var form = this.up('form').getForm();
                                if(form.isValid()){
                                    form.submit({
                                        url: preurl + 'pedidos/autoriza_pedido/' + idpedido,
                                        waitMsg: 'Enviando...',
                                        success: function(fp, o) {

                                            Ext.Msg.alert('Atención', o.result.message);
    
                                            //console.log(this)
                                            //console.log(me)
                                            //this.close;
                                            var vista = me.up('pedidosautorizaprincipal');
                                            console.log(vista)
                                            me.close;

                                        }
                                    });
                                }
                            }  */                          
                        }                      ]
                    }
                ],

            }
        ];
        
        this.callParent(arguments);
    }
});
