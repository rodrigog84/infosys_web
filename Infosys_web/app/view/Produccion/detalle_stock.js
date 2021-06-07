Ext.define('Infosys_web.view.Produccion.detalle_stock' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.detallestock4',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Detalle Existencias Produccion',
    layout: 'fit',
    autoShow: true,
    width: 880,
    height: 480,
    modal: true,
    iconCls: 'icon-sheet',
    y: 10,
    initComponent: function() {
        var me = this
        this.items = {
            xtype: 'grid',
            iconCls: 'icon-grid',
            title : 'Detalle Stock Por Producto',
            store: 'Existencias4',
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
                header: "ID PRODUCTO",
                flex: 1,
                itemId: 'IdProducto',
                dataIndex: 'id_producto',
                hidden : true
            },{
                header: "Codigo",
                width: 190,
                dataIndex: 'codigo',
                hidden: true               
            },{
                header: "Producto",
                width: 190,
                dataIndex: 'nom_producto'                
            },{
                header: "Numero",
                flex: 1,
                dataIndex: 'num_movimiento',
                hidden: true
            },{
                header: "Lote",
                flex: 1,
                dataIndex: 'lote'
            },{
                header: "Fecha Venc",
                width: 140,
                type: 'date',
                renderer:Ext.util.Format.dateRenderer('d/m/Y'),  
                dataIndex: 'fecha_vencimiento'
            },{
                header: "Entrada",
                flex: 1,
                dataIndex: 'cantidad_entrada',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")}

            },{
                header: "Saldo",
                flex: 1,
                dataIndex: 'saldo',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")}

            },{
                header: "Stock Critico",
                flex: 1,
                dataIndex: 'stock_critico',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")},
                hidden: true

            },{
                header: "Precio Promedio",
                flex: 1,
                dataIndex: 'p_promedio',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")}

            },{
                header: "Precio Neto Venta",
                flex: 1,
                dataIndex: 'valor_producto_neto',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")},
                hidden: true

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
            },'->',{
                xtype: 'numberfield',
                itemId: 'stockId',
                name : 'stock',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")},
                width: 160,
                fieldLabel: '<b>Stock</b>',
                labelAlign: 'right',
                align: 'top',
                readOnly: true
            },{
                xtype: 'numberfield',
                itemId: 'stockcriticoId',
                name : 'stock_critico',
                width: 220,
                fieldLabel: '<b>Stock Critico</b>',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")},
                labelAlign: 'right',
                align: 'top',
                hidden: true
            },{
                xtype: 'numberfield',
                itemId: 'pventaId',
                name : 'precio_venta',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00.00")},
                width: 180,
                fieldLabel: '<b>Precio Venta</b>',
                labelAlign: 'right',
                align: 'top',
                readOnly: true
            },
            ]      
        },,{
            xtype: 'button',
            margin: 5,
            action: 'seleccionarproductosstock4',
            dock: 'bottom',
            text : 'Seleccionar'
        },
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Existencias4',
            displayInfo: true
        }];
        this.callParent(arguments);
    }
});
