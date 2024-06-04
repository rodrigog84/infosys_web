Ext.define('Infosys_web.view.Pedidos2.VerGuias' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.verguias',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Ver Guias',
    layout: 'fit',
    autoShow: true,
    width: 850,
    height: 450,
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
                xtype: 'displayfield',
                fieldLabel : 'Cliente',
                labelStyle: ' font-weight:bold',
                fieldStyle: 'font-weight:bold',
                value : cliente
            },'-',{
                width: 200,
                xtype: 'displayfield',
                labelStyle: ' font-weight:bold',
                fieldStyle: 'font-weight:bold',
                fieldLabel: 'Rut',
                value : rut
            },'-',{
                width: 250,
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
            fields: ['idpedido','idguia','numguia'],
            proxy: {
              type: 'ajax',
                actionMethods:  {
                    read: 'POST'
                 },              
                url : preurl +'pedidos2/getGuiasPedido?idpedido='+idpedido,
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
        header: "Num Guia",
        width: 400,
        dataIndex: 'numguia'
        
    },{
            header: "Descargar",
            xtype:'actioncolumn',
            width:400,
            items: [{
                icon: 'images/search_page.png',  // Use a URL in the icon config
                tooltip: 'Ver OC',
                handler: function(grid, rowIndex, colIndex) {
                    var record = grid.getStore().getAt(rowIndex);
                    var idguia = record.data.idguia
                   window.open(preurl + 'facturas/exportPDF?idfactura='+idguia);
                }

            }]
    }




    ],
        };

        // Función para manejar la selección de archivos
        window.handleFileSelect = function(input, input_nro, rowIndex) {


            if (input.files && input.files[0]) {

                var file = input.files[0];
                var reader = new FileReader();
                var grid = me.down('grid')
                reader.onload = function(e) {
                    var dataURL = e.target.result;

                    // Crea un objeto FormData para enviar el archivo al servidor
                    let formData = new FormData();
                    formData.append('file', file);



                   // Realiza una solicitud AJAX al servidor
                    Ext.Ajax.request({
                        url: preurl +'pedidos2/saveOcPedido', // La URL de tu script PHP para guardar archivos
                        method: 'POST',
                        async: false,
                        /*headers: {
                                        'Content-Type': 'multipart/form-data'
                                    },*/
                       params: {
                            file: dataURL,
                            name: file.name,
                            ordencompra: input_nro,
                            idpedido: rowIndex
                        },
                        success: function(response) {
                            Ext.Msg.alert('Éxito', 'La Orden de compra se ha guardado correctamente.');
                            me.close();

                        },
                        failure: function(response) {
                            Ext.Msg.alert('Error', 'Ha ocurrido un error al intentar guardar el archivo.');
                        }
                    });                

                };
                reader.readAsDataURL(file);

            }

        };        
        
        this.callParent(arguments);
    }
});
