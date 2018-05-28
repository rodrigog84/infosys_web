Ext.define('Infosys_web.view.Email_autorizados.bitacora_avisos' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.bitacoraavisos',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Bitacora Avisos',
    layout: 'fit',
    autoShow: true,
    width: 880,
    height: 480,
    modal: true,
    iconCls: 'icon-sheet',
    y: 10,
    initComponent: function() {
        var me = this
        this.items = {
            xtype: 'grid',
            iconCls: 'icon-grid',
            title : 'Bitacora Avisos',
            store: 'Bitacora_aviso',
            autoHeight: true,
            viewConfig: {
                forceFit: true

            },
           columns: [{
                header: "ID",
                flex: 1,
                itemId: 'Id',
                dataIndex: 'id',
                hidden : true
            },{
                header: "Observacion",
                width: 590,
                dataIndex: 'observacion'                
            },{
                header: "Usuario",
                flex: 1,
                dataIndex: 'nom_usuario'
            },{
                header: "Fecha",
                flex: 1,
                type: 'date',
                renderer:Ext.util.Format.dateRenderer('d/m/Y'),  
                dataIndex: 'fecha_aviso'
            }],
            };

        this.dockedItems = [{
           xtype: 'toolbar',
            dock: 'top',
            
            items: [
           {
                width: 250,
                xtype: 'textfield',
                text: 'Nombre',
                itemId: 'nom_email'
            },{
                xtype: 'button',
                iconCls : 'icon-edit',
                text: 'Editar',
                action:''
            },{
                xtype: 'button',
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:''
            }
            ]      
        }
        /*,
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Existencias2',
            displayInfo: true
        }*/];
        this.callParent(arguments);
    }
});
