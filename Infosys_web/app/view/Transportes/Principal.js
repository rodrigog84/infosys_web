Ext.define('Infosys_web.view.Transportes.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.transportesprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Transportistas',
    store: 'transportista',
    height: 500,
    viewConfig: {
        forceFit: true

    },
    columns: [{
            header: "Rut",
            //flex: 1,
            dataIndex: 'rut'
        },{
            header: "Nombre Transportista",
            flex: 1,
            dataIndex: 'nombre'
        },{
            header: "Ciudad",
            flex: 1,
            dataIndex: 'ciudad',
        },{
            header: "Patente Camion",
            flex: 1,
            dataIndex: 'camion'
        },{
            header: "Patente Carro",
            flex: 1,
            dataIndex: 'carro'
        },{
            header: "Fecha Ult.Actualizacion",
            flex: 1,
            dataIndex: 'fecha',
             renderer:Ext.util.Format.dateRenderer('d/m/Y'),
            hidden: true
        },{
            header: "Fono",
            flex: 1,
            dataIndex: 'fono',
            hidden: true
        }],
    
    initComponent: function() {
        
        var me = this
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'agregartransportista',
                text : 'Agregar'
            },{
                xtype: 'button',
                iconCls: 'icon-edit',
                action: 'editartransportistas',
                text : 'Editar'
            },{
                xtype: 'button',
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:'exportarexceltransportista'
            },'->',{
                width: 250,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: 'Nombre'
            },{
                width: 190,
                xtype: 'textfield',
                itemId: 'patenteId',
                fieldLabel: 'Patente'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscartransportista',
                text : 'Buscar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrartransportistas',
                text : 'Cerrar'
            }]      
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'transportista',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
