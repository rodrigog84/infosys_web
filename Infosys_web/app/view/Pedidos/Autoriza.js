Ext.define('Infosys_web.view.Pedidos.Autoriza' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.pedidosautorizaprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Pedidos por Autorizar',
    store: 'Pedidos',
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
        dataIndex: 'num_pedido'
               
    },{
        header: "Fecha",
        flex: 1,
        dataIndex: 'fecha_doc',
        type: 'date',
        renderer:Ext.util.Format.dateRenderer('d/m/Y') 
    },{
        header: "Fecha Pedido",
        flex: 1,
        dataIndex: 'fecha_pedido',
        type: 'date',
        renderer:Ext.util.Format.dateRenderer('d/m/Y')
    },{
        header: "Rut",
        flex: 1,
        align: 'right',
        dataIndex: 'rut_cliente'
    },{
        header: "Id_Cliente",
        flex: 1,
        align: 'right',
        dataIndex: 'id_cliente',
        hidden: true
    },{
        header: "Razon Social",
         width: 390,
        dataIndex: 'nombre_cliente'
    },{
        header: "Vendedor",
        flex: 1,
        dataIndex: 'nom_vendedor'
    },{
        header: "Cond Pago",
        flex: 1,
        dataIndex: 'id_pago',
        hidden: true
    },{
        header: "Neto",
        flex: 1,
        dataIndex: 'neto',
        hidden: true,
        aling: 'rigth',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
    },{
        header: "Descuento",
        flex: 1,
        dataIndex: 'descuento',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")},
        hidden: true
    },{
        header: "Total Venta",
        flex: 1,
        dataIndex: 'total',
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
        
    },{
        header: "Ver",
        xtype:'actioncolumn',
        align: 'center',
        width:100,
        items: [{
            icon: 'images/search_page.png',  // Use a URL in the icon config
            tooltip: 'Ver Pedido',
            handler: function(grid, rowIndex, colIndex) {
                var rec = grid.getStore().getAt(rowIndex);
                window.open(preurl +'pedidos/exportPDF/?idpedidos=' + rec.raw.id)
              
            }
            
            }
        ],
    },{
            header: "Autorizar",
            xtype:'actioncolumn',
            width:85,
            align: 'center',
            items: [{
                icon: 'images/check.png',  // Use a URL in the icon config
                tooltip: 'Autorizar Pedido',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //salert("Edit " + rec.get('firstname'));
                    var vista = this.up('pedidosautorizaprincipal');
                    vista.fireEvent('autorizaPedido',rec,1)
                },
            }]
    },{
        header: "Bodega",
        flex: 1,
        dataIndex: 'nom_bodega',
        hidden: true
    },{
        header: "Id Bodega",
        flex: 1,
        dataIndex: 'id_bodega',
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
                iconCls: 'icon-add',
                action: 'estadopedidos',
                text : 'Estado Pedido'
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
                action: 'cerrarpedidos',
                text : 'Cerrar'
            }]      
        },{
            xtype: 'toolbar',
            dock: 'top',
            items: ['-',{
                xtype: 'combo',
                itemId: 'bodegaId',
                labelWidth: 60,
                width: 255,
                fieldCls: 'required',
                maxHeight: 25,
                fieldLabel: '<b>BODEGA</b>',
                forceSelection : true,
                name : 'id_bodega',
                valueField : 'id',
                //value: "1",
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'Bodegas'
            },'-',{
                xtype: 'button',
                width: 160,
                iconCls : 'icon-pdf',
                text: 'Informe Produccion',
                action:'exportarpdf',
                hidden: true
            },'->',{
                xtype: 'combo',
                itemId: 'vendedorId',
                labelWidth: 80,
                width: 360,
                fieldCls: 'required',
                maxHeight: 25,
                fieldLabel: 'VENDEDOR',
                forceSelection : true,
                name : 'id_seleccion',
                valueField : 'id',
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'Vendedores'
            },'-',{
                xtype: 'combo',
                itemId: 'Seleccion2Id',
                labelWidth: 60,
                width: 205,
                fieldCls: 'required',
                maxHeight: 25,
                fieldLabel: '',
                forceSelection : true,
                name : 'id_seleccion',
                valueField : 'id',
                value: "1",
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'Pedidos.Selector3'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscarpedidos',
                text : 'Buscar',
                hidden: true
            }],
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Pedidos',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
