Ext.define('Infosys_web.view.vendedores.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.vendedoresprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Vendedores',
    store: 'Vendedores',
    autoHeight: true,
    viewConfig: {
        forceFit: true

    },
    columns: [{
                header: "Nombre",
                flex: 1,
                dataIndex: 'nombre'
            },{
                header: "Rut",
                flex: 1,
                dataIndex: 'rut'
            },{
                header: "Codigo",
                flex: 1,
                dataIndex: 'cod_interno'
            },{
                header: "Direccion",
                flex: 1,
                dataIndex: 'direccion'
            },{
                header: "Fono",
                flex: 1,
                dataIndex: 'fono'
            },{
                header: "Comision",
                flex: 1,
                dataIndex: 'comision'
            },{
                header: "Estado",
                flex: 1,
                dataIndex: 'estado',
                renderer: function(value){
                if (value == 1) {
                    return '<img src="http://angus.agricultorestalca.cl/Infosys_web/Infosys_web/resources/images/add.png"/>';
                 }
                if (value == 2) {
                 //return '<img src="http://localhost:999/rutaimg.jpg" />'
                   return '<img src="http://angus.agricultorestalca.cl/Infosys_web/Infosys_web/resources/images/stop.png"/>';   
                }
        }
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
                action: 'agregarvendedores',
                text : 'Agregar'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-edit',
                action: 'editarvendedores',
                text : 'Editar'
            },{
                xtype: 'button',
                width: 120,
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:'exportarexcelvendedores'
            },'->',{
                width: 250,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: 'Nombre'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscarvendedores',
                text : 'Buscar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrarvendedores',
                text : 'Cerrar'
            }]      
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Vendedores',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
