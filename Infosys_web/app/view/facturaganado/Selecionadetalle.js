Ext.define('Infosys_web.view.facturaganado.Selecionadetalle' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.selecionadetalle',
    
    requires: ['Ext.toolbar.Paging'],
    title : '<b>Selecciona Detalle Guias</b>',
    layout: 'fit',
    autoShow: true,
    width: 1200,
    height: 380,
    modal: true,
    iconCls: 'icon-sheet',
    y: 10,
    initComponent: function() {
        var me = this
        this.items = {
            xtype: 'grid',
            iconCls: 'icon-grid',
            title : '<b>Detalle Guia despacho</b>',
            store: 'Guiasdespachopendientes4',
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
                header: "Id Producto",
                flex: 1,
                itemId: 'productoId',
                dataIndex: 'id_producto',
                hidden: true
            },{
                header: "Id Factura",
                flex: 1,
                itemId: 'idFact',
                dataIndex: 'id_factura',
                hidden: true
            },{
                header: "codigo",
                 width: 190,
                dataIndex: 'codigo'                
            },{
                header: "Nombre producto",
                 width: 190,
                dataIndex: 'nombre_producto'                
            },{
                header: "Cantidad",
                flex: 1,
                dataIndex: 'cantidad',
                labelAlign: 'rigth',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
            },{
                header: "Kilos",
                flex: 1,
                dataIndex: 'kilos',
                labelAlign: 'rigth',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
            
            },{
                header: "Precio",
                flex: 1,
                dataIndex: 'precios',
                labelAlign: 'rigth',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
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
                dataIndex: 'total',
                labelAlign: 'rigth',
                renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
            }],
        };
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [{
                width: 450,
                xtype: 'textfield',
                itemId: 'clienteId',
                fieldLabel: 'Id',
                hidden: true
            },
            '-',
            {
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'seleccionartodas',
                text : 'SeleccionarTodas'
            }
            ]      
        }];
        
        this.callParent(arguments);
    },
     loadStore: function() {
     this.getStore().load();
    }    
});
