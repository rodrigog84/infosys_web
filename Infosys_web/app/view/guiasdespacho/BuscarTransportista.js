Ext.define('Infosys_web.view.guiasdespacho.BuscarTransportista' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.buscatransguia',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Busqueda TRANSPORTISTAS',
    layout: 'fit',
    autoShow: true,
    width: 680,
    height: 380,
    modal: true,
    iconCls: 'icon-sheet',
    y: 10,
    initComponent: function() {
        var me = this
        this.items = {
        xtype: 'grid',
        iconCls: 'icon-grid',

        title : 'TRANSPORTISTAS',
        store: 'transportista',
        autoHeight: true,
        viewConfig: {
        forceFit: true

            },
    columns: [{
        header: "ID",
        width: 390,
        itemId: 'Id',
        dataIndex: 'id',
        hidden : true
        
    },{
        header: "RUT",
        width: 130,
        dataIndex: 'rut'
        
    },{
        header: "TRANSPORTISTA",
        flex: 1,
        itemId: 'transportistaId',
        dataIndex: 'nombre'
    },{
        header: "CAMION",
        flex: 1,
        itemId: 'camionId',
        dataIndex: 'camion'
    },{
        header: "CARRO",
        width: 390,
        itemId: 'carroId',
        dataIndex: 'carro',
        hidden: true
    }],
        };
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [
            {
                width: 340,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: 'Nombre'
            },{
                width: 190,
                xtype: 'textfield',
                itemId: 'patenteId',
                fieldLabel: 'Patente'
            },
            '-',
            {
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscartran',
                text : 'Buscar'
            }
            ]      
        },{
            xtype: 'button',
            margin: 5,
            action: 'seleccionartrans',
            dock: 'bottom',
            text : 'Seleccionar'
        },
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'transportista',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
