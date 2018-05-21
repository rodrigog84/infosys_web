Ext.define('Infosys_web.view.Produccion.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.produccionprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Produccion',
    store: 'Produccion',
    height: 300,
    viewConfig: {
        forceFit: true

    },
    columns: [{
        header: "Id",
        flex: 1,
        dataIndex: 'id',
        hidden: true
               
    },{
        header: "Numero",
        flex: 1,
        dataIndex: 'num_produccion'               
    },{
        header: "Fecha Pedido",
        flex: 1,
        dataIndex: 'fecha_pedido',
        type: 'date'
    },{
        header: "Fecha Producc.",
        flex: 1,
        dataIndex: 'fecha_produccion',
        type: 'date',
        //renderer: Ext.util.Format.dateRenderer('d/m/Y'),
        align: 'center'
    },{
        header: "Rut",
        flex: 1,
        align: 'right',
        dataIndex: 'rut_cliente',
        hidden: true
    },{
        header: "Id_Cliente",
        flex: 1,
        align: 'right',
        dataIndex: 'id_cliente',
        hidden: true
    },{
        header: "Razon Social",
         width: 390,
        dataIndex: 'nom_cliente'
    },{
        header: "Producto",
        flex: 1,
        dataIndex: 'nom_producto'
    },{
        header: "Id_producto",
        flex: 1,
        dataIndex: 'id_producto',
        hidden: true
    },{
        header: "Formula",
        flex: 1,
        dataIndex: 'nom_formula',
        hidden: true
    },{
        header: "Id Formula",
        flex: 1,
        dataIndex: 'id_formula_pedido',
        hidden: true
    },{
        header: "Cantidad",
        flex: 1,
        dataIndex: 'cantidad',
        hidden: true
    },{
        header: "Estado",
        flex: 1,
        dataIndex: 'estado',
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
                action: 'generaproduccion',
                text : 'Genera Produccion'
            },{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'terminoproduccion',
                text : 'Termino Produccion'
            },{
                xtype: 'button',
                iconCls : 'icon-pdf',
                text: 'Imprimir PDF',
                action:'exportarpedidos'
            },{
                xtype: 'button',
                width: 120,
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:'exportarexcelpedidos'
            },{
                xtype: 'button',
                width: 120,
                iconCls : 'icon-edit',
                text: 'Genera Venta',
                action:'generafactura',
                hidden: true
            },'->',{
                xtype: 'combo',
                width: 130,
                itemId: 'tipoSeleccionId',
                fieldLabel: '',
                forceSelection : true,
                editable : false,
                valueField : 'id',
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'clientes.Selector2'
            },{
                width: 240,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: ''
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscarpedido',
                text : 'Buscar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrarproduccion',
                text : 'Cerrar'
            }]      
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Produccion',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
