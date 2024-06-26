Ext.define('Infosys_web.view.Pedidos2.Principaltransporte' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.pedidosprincipaltransporte',
    
    requires: ['Ext.toolbar.Paging'],     
    iconCls: 'icon-grid',

    title : 'Registro de Transporte',
    //store: 'Pedidos',
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
        header: "Numero Registro Transporte",
        width:450,
        dataIndex: 'num_registro'
               
    }/*,{
        header: "Fecha",
        flex: 1,
        dataIndex: 'fecha_doc',
        type: 'date',
        renderer:Ext.util.Format.dateRenderer('d/m/Y') 
    }*/,{
        header: "Fecha Registro Transporte",
        width:350,
        dataIndex: 'fecha_genera',
        type: 'date',
        renderer:Ext.util.Format.dateRenderer('d/m/Y H:i:s')
    },{
        header: "Cantidad Guias",
        flex: 1,
        //width:350,
        align: 'right',
        dataIndex: 'cantidad'
    },{
            header: "Ver Guias",
            xtype:'actioncolumn',
            width:150,
            align: 'center',
            items: [{
                iconCls: 'icon-search',  // Use a URL in the icon config
                tooltip: 'Ver Guias',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //salert("Edit " + rec.get('firstname'));
                    var vista = this.up('pedidosprincipaltransporte');
                    vista.fireEvent('tguiasdespachover',rec)
                },
                /*isDisabled: function(view, rowIndex, colIndex, item, record) {
                    return true;
                } */                             
            }]     
        
    },{
            header: "Ver Registro de Transporte",
            xtype:'actioncolumn',
            width:150,
            align: 'center',
            items: [{
                iconCls: 'icon-edit',  // Use a URL in the icon config
                tooltip: 'Ver Guias',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    window.open(preurl +'pedidos/exportPDFRT/?idregistro=' + rec.raw.id)

                },
                /*isDisabled: function(view, rowIndex, colIndex, item, record) {
                    return true;
                } */                             
            }]     
        
    }],
    
    initComponent: function() {
        var me = this


         me.store = Ext.create('Ext.data.Store', {
            fields: ['id', 'num_registro', 'fecha_genera', 'cantidad'],
            proxy: {
              type: 'ajax',
                actionMethods:  {
                    read: 'POST'
                 },              
                url : preurl +'pedidos2/getRegistrosTransporte',
                reader: {
                    type: 'json',
                    root: 'data'
                }
            },
            autoLoad: true            
        });

        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'agregarregistrotransporte',
                text : 'Agregar Registro de Transporte'
            },{
                xtype: 'button',
                iconCls : 'icon-pdf',
                text: 'Imprimir PDF',
                action:'exportarregistro'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'eliminar2',
                text : 'Elimina'
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
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Pedidos',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
