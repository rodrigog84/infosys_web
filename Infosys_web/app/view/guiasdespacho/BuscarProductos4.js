Ext.define('Infosys_web.view.guiasdespacho.BuscarProductos4' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.buscarproductosguiasdirecta',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Busqueda Productos Guias Directas',
    layout: 'fit',
    autoShow: true,
    width: 1080,
    height: 480,
    modal: true,
    iconCls: 'icon-sheet',
    y: 10,
    initComponent: function() {
        var me = this
        this.items = {
            xtype: 'grid',
            iconCls: 'icon-grid',

            title : 'Productos',
            store: 'Despachafactura',
            autoHeight: true,
            viewConfig: {
                forceFit: true

            },
           columns: [{
                header: "Id",
                flex: 1,
                dataIndex: 'id_producto'
            },{
                header: "Codigo de Barra",
                flex: 1,
                dataIndex: 'codigo'
            },{
                header: "Nombres",
                flex: 1,
                dataIndex: 'nombre'
            },{
                header: "Precio Venta",
                flex: 1,
                dataIndex: 'p_venta',
                align: 'right'
            },{
                header: "Cantidad",
                flex: 1,
                align: 'right',
                dataIndex: 'stock'
            },{
                header: "Stock Critico",
                flex: 1,
                align: 'right',
                dataIndex: 'stock_critico',
                hidden: true
            },{
                header: "Dias Venc.",
                flex: 1,
                align: 'right',
                dataIndex: 'diasvencimiento',
                hidden: true
            }],
        };
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [
            {
                width: 450,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: 'Nombre'
            },
            '-',
            {
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscar',
                text : 'Buscar'
            },'->',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: '',
                text : 'Todas',
                hidden: true
            }
            ]      
        },{
            xtype: 'button',
            margin: 15,
            action: 'seleccionarproductos7',
            dock: 'bottom',
            text : 'Seleccionar'
        },
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Despachafactura',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
