Ext.define('Infosys_web.view.existencia.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.existenciaprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Tarjeta Existencia productos',
    store: 'Existencias',
    height: 500,
    viewConfig: {
        forceFit: true

    },
    columns: [{
        header: "Id Producto",
        width: 390,
        dataIndex: 'id_producto',
         hidden: true        
    },{
        header: "codigo",
        width: 100,
        dataIndex: 'codigo'        
    },{
        header: "Nombre Producto",
        width: 390,
        dataIndex: 'nom_producto'
        
    },{
        header: "Stock",
        flex: 1,
        dataIndex: 'stock',
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number((valor),"0,000.00")}

    },{
        header: "Bodega",
        flex: 1,
        dataIndex: 'nom_bodega'
    },{
        header: "Fecha Ultimo Movimiento",
        flex: 1,
        renderer:Ext.util.Format.dateRenderer('d/m/Y'),  
        dataIndex: 'fecha_ultimo_movimiento'
        
    }],
    
    initComponent: function() {
        var me = this

        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [{
                xtype: 'button',
                iconCls : 'icon-exel',
                text: 'Editar Detalle',
                action:'editarexistencia'
            },'-',{
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
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'Bodegas'
            },{
                xtype: 'button',
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:'exportarexcelexistencia'
            },'->',{
                    xtype: 'combo',
                    itemId: 'clasificacionId',
                    fieldLabel: 'Clasificacion',
                    forceSelection : true,
                    editable : false,
                    name : 'clasificacion',
                    value: "3",
                    valueField : 'id',
                    displayField : 'nombre',
                    emptyText : "Seleccione",
                    store : 'productos.Clasificacion'
            },{
                xtype: 'combo',
                itemId: 'tipoSeleccionId',
                fieldLabel: '',
                forceSelection : true,
                editable : false,
                valueField : 'id',
                value: "1",
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'existenciabusca'
            },{
                width: 200,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: ''
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscarexistencia',
                text : 'Buscar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrarexistencia',
                text : 'Cerrar'
            }]      
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Existencias',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
