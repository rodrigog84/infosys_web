Ext.define('Infosys_web.view.existencia.detalle_existencias' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.detalleexistencias',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Detalle Existencias ',
    layout: 'fit',
    autoShow: true,
    width: 1200,
    height: 480,
    modal: true,
    iconCls: 'icon-sheet',
    y: 10,
    initComponent: function() {
        var me = this
        this.items = {
            xtype: 'grid',
            iconCls: 'icon-grid',
            title : 'Detalle Existencia',
            store: 'Existencias3',
            autoHeight: true,
            viewConfig: {
                forceFit: true

            },
           columns: [{
            header: "ID",
            flex: 1,
            itemId: 'Id',
            dataIndex: 'id',
            hidden : true
            },{
                header: "Producto",
                width: 190,
                dataIndex: 'nom_producto'
                
            },{
                header: "Tipo",
                flex: 1,
                dataIndex: 'nom_tipo_movimiento'
            },{
                header: "Numero",
                flex: 1,
                dataIndex: 'num_movimiento'
            },{
                header: "Bodega",
                flex: 1,
                dataIndex: 'nom_bodega'
            },{
                header: "Lote",
                flex: 1,
                dataIndex: 'lote'
            },{
                header: "Entrada",
                flex: 1,
                dataIndex: 'cantidad_entrada',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number((valor),"0,000.00")}

            },{
                header: "Salida",
                flex: 1,
                dataIndex: 'cantidad_salida',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number((valor),"0,000.00")}

            },{
                header: "Saldo",
                flex: 1,
                dataIndex: 'saldo',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number((valor),"0,000.00")}

            },{
                header: "Valor",
                flex: 1,
                dataIndex: 'valor_producto',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number((valor),"0,000.00")}

            },{
                header: "Fecha",
                flex: 1,
                type: 'date',
                renderer:Ext.util.Format.dateRenderer('d/m/Y'),  
                dataIndex: 'fecha_movimiento'
            },{
                header: "O/C",
                flex: 1,
                type: 'num_o_compra',
                hidden: true
            },{
                header: "Ver",
                xtype:'actioncolumn',
                align: 'center',
                width:100,
                items: [{
                icon: 'images/search_page.png',  // Use a URL in the icon config
                tooltip: 'Ver Pedido',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    console.log(rec.raw.nom_tipo_movimiento);
                    console.log(rec.raw.num_movimiento);
                    var tipo = (rec.raw.nom_tipo_movimiento);
                    var entrada = (rec.raw.cantidad_entrada);
                    var salida = (rec.raw.cantidad_salida);
                    var n_orden = (rec.raw.num_o_compra);
                    if(tipo=="FORMULARIO PRODUCCION"){
                        window.open(preurl +'produccion/exportPDF5/?idproduccion=' + rec.raw.num_movimiento)
                    };
                    if(tipo=="GUIA DESPACHO ELECTRONICA"){
                        if(entrada>0){
                        window.open(preurl +'ordencompra/exportPDF5/?idproduccion=' + rec.raw.num_o_compra)
                        };
                        /*if(salida>0){
                        window.open(preurl +'ordencompra/exportPDF5/?idproduccion=' + rec.raw.num_movimiento)
                        };*/
                    };
                    //window.open(preurl +'pedidos/exportPDF/?idpedidos=' + rec.raw.id)
                  
                }

                }
                ],
                }],
            };

        this.dockedItems = [{
           xtype: 'toolbar',
            dock: 'top',
            
            items: [
           {
                width: 250,
                xtype: 'textfield',
                itemId: 'productoId',
                hidden: true
            },{
                xtype: 'button',
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:'exportarexcelexistenciadetalle'
            },'->',{
                xtype: 'numberfield',
                itemId: 'stockId',
                name : 'stock',                
                width: 220,
                fieldLabel: '<b>Stock</b>',
                labelAlign: 'right',
                align: 'top',
                readOnly: true,
                renderer: function(valor){return Ext.util.Format.number((valor),"0,000.00")}

            },
            ]      
        }
        /*,
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Existencias2',
            displayInfo: true
        }*/];
        this.callParent(arguments);
    }
});
