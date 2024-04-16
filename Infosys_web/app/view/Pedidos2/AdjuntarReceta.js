Ext.define('Infosys_web.view.Pedidos2.AdjuntarReceta' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.adjuntarreceta',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Cargar Recetas Pedido',
    layout: 'fit',
    autoShow: true,
    width: 1200,
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
            fields: ['id','codigo','nombre', 'requiererecetasino','recetacargada','permitecargar','subereceta','omarchivoreceta'],
            proxy: {
              type: 'ajax',
                actionMethods:  {
                    read: 'POST'
                 },              
                url : preurl +'pedidos2/getDetallePedido?idpedido='+idpedido,
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
        header: "Cod Producto",
        width: 150,
        dataIndex: 'codigo'
        
    },{
        header: "Nom Producto",
        width: 250,
        dataIndex: 'nombre'
        
    },{
        header: "Requiere Receta",
        width: 160,
        dataIndex: 'requiererecetasino'
        
    },{
        header: "Receta Cargada",
        width: 160,
        dataIndex: 'recetacargada'
        
    },{
        header: "Carga",
        width: 350,
        renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                    console.log(record.data)
                    var disabled_file = record.data.permitecargar == 1 ? '' : 'disabled';
                    return Ext.String.format(
                        '<input type="file" id="fileInput_{0}" name="fileInput_{0}" style="width: 95%" ' + disabled_file + ' onchange="handleFileSelect(this, {0})">',
                        record.data.id
                    );
                },
        /*items: [{
            isDisabled: function(view, rowIndex, colIndex, item, record) {
                // Returns true if 'editable' is false (, null, or undefined)
                //console.log(record.get('idcomprobante'));
                if(record.get('permitecargar') == 1){
                    return false;
                }else{
                    return true;
                }
            }


        }]   */             
        
    },{
            header: "Descargar",
            xtype:'actioncolumn',
            width:110,
            items: [{
                icon: 'images/search_page.png',  // Use a URL in the icon config
                tooltip: 'Ver Comprobante',
                handler: function(grid, rowIndex, colIndex) {
                    var record = grid.getStore().getAt(rowIndex);
                    var iddetallepedido = record.data.id
                   window.open(preurl + 'pedidos2/verreceta?iddetallepedido='+iddetallepedido);
                },
                isDisabled: function(view, rowIndex, colIndex, item, record) {
                    // Returns true if 'editable' is false (, null, or undefined)
                    console.log(record.data)
                    console.log('sube receta' + record.data.subereceta);
                    if(record.data.subereceta == 1){
                        return false;
                    }else{
                        return true;
                    }
                }


            }]
    }




    ],
        };

        // Función para manejar la selección de archivos
        window.handleFileSelect = function(input, rowIndex) {
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
                        url: preurl +'pedidos2/saveReceta', // La URL de tu script PHP para guardar archivos
                        method: 'POST',
                        async: false,
                        /*headers: {
                                        'Content-Type': 'multipart/form-data'
                                    },*/
                       params: {
                            file: dataURL,
                            name: file.name,
                            iddetallepedido: rowIndex
                        },
                        success: function(response) {
                            Ext.Msg.alert('Éxito', 'El archivo se ha guardado correctamente.');
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
