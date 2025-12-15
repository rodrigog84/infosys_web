Ext.define('Infosys_web.view.Pedidos2.BuscarClientefinal' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.buscarclientefinal',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Clientes Finales',
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
        title : 'Clientes Finales',
        store: 'Clientefinal',
        autoHeight: true,
        viewConfig: {
            forceFit: true
        },
        columns: [{
            header: "id",
            flex: 1,
            dataIndex: 'id',
            hidden: true
            },{
            header: "Rut",
            flex: 1,
            dataIndex: 'rut'
            },{
            header: "Nombre Cliente",
            flex: 1,
            dataIndex: 'nombre'
            },{
            header: "Direccion",
            flex: 1,
            dataIndex: 'direccion'
            },{
            header: "Rutnombre",
            flex: 1,
            dataIndex: 'rutnombre',
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
                action: 'buscarclientefinal2',
                text : 'Buscar'
            }
            ]      
        },{
            xtype: 'button',
            margin: 5,
            action: 'seleccionarclientefinal',
            dock: 'bottom',
            text : 'Seleccionar'
        },
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Clientefinal',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
