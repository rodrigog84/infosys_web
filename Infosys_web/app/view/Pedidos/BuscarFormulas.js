Ext.define('Infosys_web.view.Pedidos.BuscarFormulas' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.buscarformulas',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Formulas Por Productos',
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
        title : 'Formulas',
        store: 'FormulasPedidos',
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
            header: "Numero",
            flex: 1,
            dataIndex: 'num_formula'
            },{
            header: "Id_Cliente",
            flex: 1,
            dataIndex: 'id_cliente',
            hidden: true
            },{
            header: "Nombre Cliente",
            flex: 1,
            dataIndex: 'nom_cliente'
            },{
            header: "Rut Cliente",
            flex: 1,
            dataIndex: 'rut_cliente'
            },{
            header: "Formula",
            flex: 1,
            dataIndex: 'nombre_formula'
            },{
            header: "Cantidad",
            flex: 1,
            dataIndex: 'cantidad'
            },{
            header: "Valor",
            flex: 1,
            dataIndex: 'valor'
            },{
            header: "Fecha",
            flex: 1,
            dataIndex: 'fecha_formula',
            type: 'date',
            renderer: Ext.util.Format.dateRenderer('d/m/Y'),
            align: 'center'
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
                action: 'buscar',
                text : 'Buscar'
            }
            ]      
        },{
            xtype: 'button',
            margin: 5,
            action: 'seleccionarformula',
            dock: 'bottom',
            text : 'Seleccionar'
        },
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'FormulasPedidos',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
