Ext.define('Infosys_web.view.Pedidos2.GenerarOcint' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.generarocint',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Generar OC',
    layout: 'fit',
    autoShow: true,
    width: 650,
    height: 300,
    modal: true,
    iconCls: 'icon-sheet',
    y: 10,
    initComponent: function() {
        var me = this
        var cliente = me.cliente;
        var rut = me.rut;
        var num_pedido = me.num_pedido;
        var idpedido = me.idpedido;


        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [
            {
                width: 250,
                xtype: 'displayfield',
                fieldLabel : 'Cliente',
                labelStyle: ' font-weight:bold',
                fieldStyle: 'font-weight:bold',
                value : cliente
            },'-',{
                width: 150,
                xtype: 'displayfield',
                labelStyle: ' font-weight:bold',
                fieldStyle: 'font-weight:bold',
                fieldLabel: 'Rut',
                value : rut
            },'-',{
                width: 150,
                xtype: 'displayfield',
                labelStyle: ' font-weight:bold',
                fieldStyle: 'font-weight:bold',
                fieldLabel: 'Num Pedido',
                value : num_pedido,
                align: 'right',
 
            },{
                xtype: 'textfield',
                itemId: 'idpedido',
                name : 'idpedido',
                value : idpedido,
                hidden: true
            }
            ]      
        }];
        this.items = {
            xtype: 'grid',
            store:Ext.create('Ext.data.Store', {
            fields: ['id','occargada','permitecargaroc','subeoc','nomarchivooc','ordencompra','ordencompraint','nomarchivoocint'],
            proxy: {
              type: 'ajax',
                actionMethods:  {
                    read: 'POST'
                 },              
                url : preurl +'pedidos2/getPedido?idpedido='+idpedido,
                reader: {
                    type: 'json',
                    root: 'data'
                }
            },
            autoLoad: true            
            }),
            autoHeight: true,
            viewConfig: {
                forceFit: true,
            },
        columns: [{
        header: "Generar Orden",
        width: 350,
        renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                    var disabled_file = record.data.ordencompraint == '' ? '' : 'disabled';
                    console.log(record.data)
                    return Ext.String.format(
                        '<input type="button"  id="btnoc_{0}" name="btnoc_{0}" style="width: 95%" ' + disabled_file + ' value="Generar OC" onclick="handlebtnoc(this, {0})"  >',
                        record.data.id
                    );
                },
    },{
        header: "Nro OC",
        flex: 1,
        dataIndex: 'ordencompraint',
    },{
            header: "Descargar",
            xtype:'actioncolumn',
            width:110,
            items: [{
                icon: 'images/search_page.png',  // Use a URL in the icon config
                tooltip: 'Ver OC',
                handler: function(grid, rowIndex, colIndex) {
                    var record = grid.getStore().getAt(rowIndex);
                    var idpedido = record.data.id
                    var oc = record.data.ordencompraint
                   window.open(preurl + 'pedidos/exportPDFOC?idpedidos=' + idpedido + '&oc=' + oc);
                },
                isDisabled: function(view, rowIndex, colIndex, item, record) {
                    // Returns true if 'editable' is false (, null, or undefined)
                    if(record.data.ordencompraint == ''){
                        return true;
                    }else{
                        return false;
                    }
                }


            }]
    }




    ],
        };


        

        // Función para manejar la selección de archivos
        window.handlebtnoc = function(input, input_nro, rowIndex) {
                 Ext.Ajax.request({

                    url: preurl + 'correlativos/genera?valida=109',
                    params: {
                        id: 1
                    },
                    success: function(response){
                        var resp = Ext.JSON.decode(response.responseText);

                        if (resp.success == true) {

                            var cliente = resp.cliente;
                            var correlanue = cliente.correlativo;

                            window.open(preurl +'pedidos/exportPDFOC/?idpedidos=' + input_nro + '&oc=' + correlanue)
                            Ext.Msg.alert('Éxito', 'OC generada correctamente.');
                            me.close();

                        }else{
                            Ext.Msg.alert('Correlativo YA Existe');
                            return;
                        }
                    }            
                });            

            

        };        
        
        this.callParent(arguments);
    }
});
