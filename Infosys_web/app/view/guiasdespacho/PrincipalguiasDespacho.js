Ext.define('Infosys_web.view.guiasdespacho.PrincipalguiasDespacho' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.guiasprincipaldespacho',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'EMISION GUIAS DESPACHO DIRECTAS',
    store: 'Guiasdespacho',
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
        header: "Numero Guia",
        flex: 1,
        dataIndex: 'num_factura',
        align: 'right'
               
    },{
        header: "Estado Guia",
        flex: 1,
        dataIndex: 'estado_guia',
        align: 'right'
               
    },{
        header: "Tipo Guia",
        flex: 1,
        dataIndex: 'tipoguia',
        align: 'right'
               
    },{
        header: "Fecha",
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
         width: 390,
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
        header: "Total Guia",
        flex: 1,
        dataIndex: 'totalfactura',
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
     
        
    }],
    
    initComponent: function() {
        var me = this
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'iguiasdespacho',
                text : 'Genera Guia'
            },{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'mguiasglosa',
                text : 'Guia Glosa'
            },{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'mguiastraslado',
                text : 'Guia Traslado'
            },{
                xtype: 'button',
                iconCls : 'icon-pdf',
                text: 'Edita / Imprimir PDF',
                action:'generarguiaspdf2'
            },{
                xtype: 'button',
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:'exportarexcelguias2'
            },{
                xtype: 'button',
                width: 140,
                iconCls : 'icon-exel',
                text: 'Exportar Envases',
                action:'exportarexcelenvases'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'anular',
                text : 'Anular Guias'
            },'->',{
                xtype: 'combo',
                itemId: 'tipoSeleccionId',
                fieldLabel: '',
                forceSelection : true,
                editable : false,
                valueField : 'id',
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'clientes.Selector2'
            },{
                width: 250,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: ''
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscarguiasdirectas',
                text : 'Buscar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrarguia',
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
                displayField : 'nombre',
                emptyText : "Seleccione",
                //value: "1",
                store : 'Bodegas'
            }],
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Guiasdespacho',
            displayInfo: true
        }];
        
        this.callParent(arguments);
        this.on('render', this.loadStore, this);
    },
    loadStore: function() {
        this.getStore().load();
    }      
});
