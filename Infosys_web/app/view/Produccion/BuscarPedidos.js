Ext.define('Infosys_web.view.Produccion.BuscarPedidos' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.buscarpedidosproduccion',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Pedidos Para Produccion',
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
        title : 'Pedidos Generados',
        store: 'PedidosProduccion',
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
        header: "Numero",
        flex: 1,
        dataIndex: 'num_pedido'
               
    },{
        header: "Fecha",
        flex: 1,
        dataIndex: 'fecha_doc',
        type: 'date',
        renderer:Ext.util.Format.dateRenderer('d/m/Y') 
    },{
        header: "Fecha Pedido",
        flex: 1,
        dataIndex: 'fecha_pedido',
        type: 'date',
        renderer:Ext.util.Format.dateRenderer('d/m/Y')
    },{
        header: "Rut",
        flex: 1,
        align: 'right',
        dataIndex: 'rut_cliente'
    },{
        header: "Id_Cliente",
        flex: 1,
        align: 'right',
        dataIndex: 'id_cliente',
        hidden: true
    },{
        header: "Razon Social",
         width: 300,
        dataIndex: 'nombre_cliente'
    },{
        header: "Vendedor",
        flex: 1,
        dataIndex: 'nom_vendedor'
    },{
        header: "Producto",
        flex: 1,
        dataIndex: 'nom_producto'
    },{
        header: "Id producto",
        flex: 1,
        dataIndex: 'id_producto',
        hidden: true
    },{
        header: "Bodega",
        flex: 1,
        dataIndex: 'nom_bodega',
        hidden: true
    },{
        header: "Id Bodega",
        flex: 1,
        dataIndex: 'id_bodega',
        hidden: true
    },{
        header: "Id Formula",
        flex: 1,
        dataIndex: 'id_formula',
        hidden: true
    },{
        header: "Nombre Formula",
        flex: 1,
        dataIndex: 'nom_formula',
        hidden: true
    },{
        header: "Cantidad",
        flex: 1,
        dataIndex: 'cantidad',
        hidden: true
    },{
        header: "Estado",
        flex: 1,
        dataIndex: 'estado',
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
            action: 'seleccionarpedidoproduccion',
            dock: 'bottom',
            text : 'Seleccionar'
        },
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'PedidosProduccion',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
