Ext.define('Infosys_web.view.ventas.BuscarProductos' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.buscarproductosfacturas',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Busqueda Productos',
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
            store: 'Productosf',
            autoHeight: true,
            viewConfig: {
                forceFit: true

            },
           columns: [{
                header: "Id",
                width: 100,
                dataIndex: 'Id',
                hidden: true
            },{
                header: "Codigo",
                width: 100,
                dataIndex: 'codigo'
            },{
                header: "Nombres",
                width: 750,
                dataIndex: 'nombre'
            },{
                header: "Ubicacion Fisica",
                 width: 100,
                dataIndex: 'nom_bodega',
                hidden: true
            },{
                header: "Precio Venta",
                width: 100,
                dataIndex: 'p_venta',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number((valor),"0,00.00")},
            },{
                header: "Precio Neto",
                width: 100,
                dataIndex: 'p_neto',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number((valor),"0,00.00")},
                hidden: true
            },{
                header: "Stock",
                width: 100,
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
                itemId: 'bodegaId',
                fieldLabel: 'Bodega',
                hidden: true
            },{
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
            }
            ]      
        },{
            xtype: 'button',
            margin: 5,
            action: 'seleccionarproductos',
            dock: 'bottom',
            text : 'Seleccionar'
        },
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Productosf',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
