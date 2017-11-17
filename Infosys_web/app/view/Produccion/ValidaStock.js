Ext.define('Infosys_web.view.Produccion.ValidaStock' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.validastock',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Pedidos Para Produccion',
    layout: 'fit',
    autoShow: true,
    width: 1080,
    height: 480,
    closable: false,
    modal: true,
    iconCls: 'icon-sheet',
    y: 10,
    initComponent: function() {
        var me = this
        this.items = {
        xtype: 'grid',
        iconCls: 'icon-grid',
        title : 'Valida Stock',
        store: 'Valida',
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
        header: "Nombre",
        flex: 1,
        dataIndex: 'nombre'
               
    },{
        header: "Stock",
        flex: 1,
        dataIndex: 'stock'
    },{
        header: "Pedido",
        flex: 1,
        align: 'right',
        dataIndex: 'pedido'
    }],
        };
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [
            {
                xtype: 'displayfield',
                fieldLabel: '<b>PRODUCTOS SIN STOCK NO PERMITE GENERAR PRODUCCION</b>',
                labelWidth: 520,
                width: 520             
               
            },
            '-',
            {
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'Salir',
                text : 'Salir'
            }
            ]      
        }];
        
        this.callParent(arguments);
    }
});
