Ext.define('Infosys_web.view.consumos.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.consumosprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Movimientos Consumos Diarios de Productos',
    store: 'Consumo_movimientodiario',
    autoHeight: true,
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
                dataIndex: 'numero',
                hidden: true
            },{
                header: "Tipo",
                flex: 1,
                dataIndex: 'id_tipom',
                renderer: function(value){
                if (value == 1) {
                    return 'Entrada';
                 }
                 if (value == 2) {
                    return 'Salida';
                 }
                 if (value == 3) {
                    return 'Traspaso';
                 }
                }
            },{
                header: "Tipo Movimiento",
                flex: 1,
                dataIndex: 'id_tipomd',
                hidden: true
            },{
                header: "Nombre",
                flex: 1,
                dataIndex: 'nom_tipomd'
            },{
                header: "Rut",
                flex: 1,
                dataIndex: 'rut'
            },{
                header: "Bodega Entrada",
                flex: 1,
                dataIndex: 'nom_bodegaent'
            },{
                header: "Bodega Salida",
                flex: 1,
                dataIndex: 'nom_bodegasal'
            },{
                header: "Detalle",
                flex: 1,
                dataIndex: 'detalle'
            },{
                header: "Fecha",
                flex: 1,
                type: 'date',
                renderer:Ext.util.Format.dateRenderer('d/m/Y'),  
                dataIndex: 'fecha'


            }],
    
    initComponent: function() {
        var me = this
        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [
            {
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'consumodiario',
                text : 'Agregar'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-edit',
                action: 'desplegarmovimiento',
                text : 'Desplegar Movimiento'
            },{
                xtype: 'button',
                width: 120,
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:'exportarexcelcdiario'
            },'->',{
                width: 250,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: 'Nombre'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: '',
                text : 'Buscar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: '',
                text : 'Cerrar'
            }]      
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Consumo_movimientodiario',
            displayInfo: true
        }];
        
        this.callParent(arguments);
        this.getStore().load();
    }
});
