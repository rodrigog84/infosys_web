Ext.define('Infosys_web.view.clientefinal.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.clientefinalprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Clientes Finales Pedidos',
    store: 'Clientefinal',
    autoHeight: true,
    viewConfig: {
        forceFit: true

    },
    columns: [{
        header: "Rut",
        flex: 1,
        dataIndex: 'rut'
    },{
        header: "Nombre",
        flex: 1,
        dataIndex: 'nombre'
    },{
        header: "Direcci&oacute;n",
        flex: 1,
        dataIndex: 'direccion'
    }],
    
    initComponent: function() {
        var me = this

        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [
            {
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'agregarclientefinal',
                text : 'Agregar'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-edit',
                action: 'editarclientefinal',
                text : 'Editar'
            },'->'
            ,'-',{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrartipoenvase',
                text : 'Cerrar'
            }]      
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Clientefinal',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
