Ext.define('Infosys_web.view.ventas.Principalfactura' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.facturasprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Facturacion',
    store: 'Factura',
    height: 500,
    viewConfig: {
        forceFit: true

    },
    columns: [{
        header: "Id Factura",
        flex: 1,
        dataIndex: 'id',
        align: 'right',
        hidden: true
               
    },{
        header: "Forma",
        flex: 1,
        dataIndex: 'forma',
        align: 'right',
        hidden: true
               
    },{
        header: "Id Factura",
        flex: 1,
        dataIndex: 'id_factura',
        align: 'right',
        hidden: true
               
    },{
        header: "Num Docto",
        flex: 1,
        dataIndex: 'num_factura',
        align: 'right'
               
    },{
        header: "Tipo Documento",
        dataIndex: 'tipo_doc',
        width:300,
        align: 'left'
               
    },{        
        header: "Fecha Emision ",
        flex: 1,
        dataIndex: 'fecha_factura',
        type: 'date',
        renderer: Ext.util.Format.dateRenderer('d/m/Y'),
        align: 'center'
        
    },{
        header: "Fecha Venc.",
        flex: 1,
        dataIndex: 'fecha_venc',
        type: 'date',
        renderer: Ext.util.Format.dateRenderer('d/m/Y'),
        align: 'center'
        
    },{
        header: "Rut",
        flex: 1,
        dataIndex: 'rut_cliente',
        align: 'right'

    },{
        header: "Razon Social",
         width: 300,
        dataIndex: 'nombre_cliente'
    },{
        header: "Vendedor",
        flex: 1,
        dataIndex: 'nom_vendedor',
        hidden: true
    },{
        header: "Neto",
        flex: 1,
        dataIndex: 'sub_total',
        hidden: true,
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}     
    },{
        header: "Descuento",
        flex: 1,
        dataIndex: 'descuento',
        hidden: true,
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
     
    },{
        header: "Afecto",
        flex: 1,
        dataIndex: 'neto',
         hidden: true,
         align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
     
    },{
        header: "I.V.A",
        flex: 1,
        dataIndex: 'iva',
         hidden: true,
         align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
     
    },{
        header: "Total",
        flex: 1,
        dataIndex: 'totalfactura',
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
    },{
            header: "Estado DTE",
            xtype:'actioncolumn',
            width:90,
            align: 'center',
            items: [{
                icon: 'images/search_page.png',  // Use a URL in the icon config
                tooltip: 'Ver Estado DTE',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //salert("Edit " + rec.get('firstname'));
                    var vista = this.up('facturasprincipal');
                    vista.fireEvent('verEstadoDte',rec,1)
                },
                isDisabled: function(view, rowIndex, colIndex, item, record) {
                    // Returns true if 'editable' is false (, null, or undefined)
                    if(record.get('tipo_documento') == 101 || record.get('tipo_documento') == 103){
                        return false;
                    }else{
                        return true;
                    }
                }                
            }]
    },{
            header: "Ver DTE",
            xtype:'actioncolumn',
            width:70,
            align: 'center',
            items: [{
                icon: 'images/download-icon.png',  // Use a URL in the icon config
                tooltip: 'Descargar DTE',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //salert("Edit " + rec.get('firstname'));
                    var vista = this.up('facturasprincipal');
                    vista.fireEvent('verEstadoDte',rec,2)
                },
                isDisabled: function(view, rowIndex, colIndex, item, record) {
                    // Returns true if 'editable' is false (, null, or undefined)
                    if(record.get('tipo_documento') == 101 || record.get('tipo_documento') == 103){
                        return false;
                    }else{
                        return true;
                    }
                }                
            }]
    },{
            header: "Env&iacute;o SII",
            xtype:'actioncolumn',
            width:70,
            align: 'center',
            items: [{
                iconCls: 'icon-upload',  // Use a URL in the icon config
                tooltip: 'Ver Estado Env&iacute;o',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //salert("Edit " + rec.get('firstname'));
                    var vista = this.up('facturasprincipal');
                    vista.fireEvent('verEstadoDte',rec,3)
                },
                isDisabled: function(view, rowIndex, colIndex, item, record) {
                    // Returns true if 'editable' is false (, null, or undefined)
                    if(record.get('tipo_documento') == 101 || record.get('tipo_documento') == 103){
                        return false;
                    }else{
                        return true;
                    }
                }                
            }]     
        
    }],
    
    initComponent: function() {
        var me = this
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'mfactura',
                text : 'Genera Venta'
            },{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'mfacturaglosa',
                text : 'Venta Glosa'
            },{
                xtype: 'button',
                iconCls : 'icon-pdf',
                text: 'Edita / Imprimir PDF',
                action:'generarfacturapdf'
            },{
                xtype: 'button',
                iconCls : 'icon-pdf',
                text: 'Copia Cedible',
                action:'generarfacturacediblepdf'
            },{                
                xtype: 'button',
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:'exportarexcelfacturas'
            },{
                xtype: 'button',
                iconCls : 'icon-pdf',
                text: 'PDF Libro Ventas',
                action:'generarlibropdf'
            },'->',{
                xtype: 'combo',
                itemId: 'tipoSeleccionId',
                fieldLabel: '',
                forceSelection : true,
                editable : false,
                valueField : 'id',
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'clientes.Selector'
            },{
                width: 200,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: ''
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscarfact',
                text : 'Buscar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrarfactura',
                text : 'Cerrar'
            }]      
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Factura',
            displayInfo: true
        }];
        
        this.callParent(arguments);
        this.on('render', this.loadStore, this);
    },
    loadStore: function() {
        this.getStore().load();
    }      
});
