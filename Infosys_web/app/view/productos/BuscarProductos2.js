Ext.define('Infosys_web.view.productos.BuscarProductos2' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.buscarproductos25',
    
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
                flex: 1,
                dataIndex: 'id',
                hidden: true
            },{
                header: "Codigo de Barra",
                flex: 1,
                dataIndex: 'codigo'
            },{
                header: "Nombres",
                flex: 1,
                dataIndex: 'nombre'
            },{
                header: "Ubicacion Fisica",
                flex: 1,
                dataIndex: 'nom_ubi_prod',
                hidden: true
            },{
                header: "Precio Venta",
                flex: 1,
                dataIndex: 'p_venta',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number((valor),"0,00.00")},
            },{
                header: "Precio Neto",
                flex: 1,
                dataIndex: 'p_neto',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number((valor),"0,00.00")},
            },{
                header: "Precio Promedio",
                flex: 1,
                dataIndex: 'p_promedio',
                align: 'right',
                renderer: function(valor){return Ext.util.Format.number((valor),"0,00.00")},
                hidden: true
            },{
                header: "Stock",
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
                action: 'buscarproguias',
                text : 'Buscar'
            }
            ]      
        },{
            xtype: 'button',
            margin: 15,
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
