Ext.define('Infosys_web.view.tipoenvases.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.tipoenvasesprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Tipo Envases',
    store: 'Tipoenvases',
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
                action: 'agregartipoenvase',
                text : 'Agregar'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-edit',
                action: 'editartipoenvases',
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
