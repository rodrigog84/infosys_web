Ext.define('Infosys_web.view.formula.BuscarProductos2' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.buscarproductosformula2',
    
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
            store: 'ProductosForm',
            autoHeight: true,
            viewConfig: {
                forceFit: true

            },
           columns: [{
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
                header: "Clasificacion",
                width: 100,
                align: 'right',
                dataIndex: 'clasificacion'
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
                action: 'buscar2',
                text : 'Buscar'
            }
            ]      
        },{
            xtype: 'button',
            margin: 5,
            action: 'seleccionarproductos2',
            dock: 'bottom',
            text : 'Seleccionar'
        },
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'ProductosForm',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
