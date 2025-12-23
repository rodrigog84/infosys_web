Ext.define('Infosys_web.view.guiasdespacho.BuscarFacturasanticipadas' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.buscarfacturaanticipada',
    
    requires: ['Ext.toolbar.Paging'],
    title : '<b>Facturas Anticipadas</b>',
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
            title : '<b>Facturas Anticipadas</b>',
            store: 'Facturaanticipada',
            autoHeight: true,
            viewConfig: {
                forceFit: true

            },
           columns: [{
                header: "Id",
                flex: 1,
                itemId: 'clienteId',
                dataIndex: 'id',
                hidden: true
            },{
                header: "Id",
                flex: 1,
                itemId: 'idId',
                dataIndex: 'id',
                hidden: true
            },{
                header: "Numero",
                 width: 190,
                dataIndex: 'num_factura'
                
            },{
                header: "Neto",
                flex: 1,
                dataIndex: 'neto',
                labelAlign: 'rigth',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
            
               
            },{
                header: "Iva",
                flex: 1,
                dataIndex: 'iva',
                labelAlign: 'rigth',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
            
            },{
                header: "Total",
                flex: 1,
                dataIndex: 'totalfactura',
                labelAlign: 'rigth',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
            },{
                header: "Fecha",
                flex: 1,
                dataIndex: 'fecha_factura',
                type: 'date',
                renderer:Ext.util.Format.dateRenderer('d/m/Y') 
            }],
        };
        this.dockedItems = [

         /*   {
            xtype: 'toolbar',
            dock: 'top',
            items: [{
                width: 450,
                xtype: 'textfield',
                itemId: 'clienteId',
                fieldLabel: 'Id',
                hidden: true
            },
            {
                width: 450,
                xtype: 'numberfield',
                itemId: 'nombreId',
                name: 'numero',
                fieldLabel: 'Numero'
            },
            '-',
            {
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscarfacturaanticipada',
                text : 'Buscar'
            },
            '-',
            {
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'seleccionartodas',
                text : 'Todas'
            }
            ]      
        },
        */
        {
            xtype: 'button',
            margin: 5,
            action: 'seleccionarfacturaanticipada',
            dock: 'bottom',
            text : 'Seleccionar'
        },
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Facturaanticipada',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    },
     loadStore: function() {
     this.getStore().load();
    }    
});
