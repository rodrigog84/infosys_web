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
        header: "Codigo",
        flex: 1,
        dataIndex: 'codigo'
    },{
        header: "Nombre Tipo Envase",
        flex: 1,
        dataIndex: 'nombre'
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
            store: 'Tipoenvases',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
