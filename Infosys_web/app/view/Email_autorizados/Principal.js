Ext.define('Infosys_web.view.Email_autorizados.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.emailautorizadosprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Email Autorizados',
    store: 'emailautorizados',
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
        header: "Nombre",
        flex: 1,
        dataIndex: 'nombre'
    },{
        header: "Email",
        flex: 1,
        dataIndex: 'email'
    },{
        header: "Existencia",
        flex: 1,
        dataIndex: 'id_existencia',
        renderer: function(value){
            if (value == 1) {
                return 'SI';
             }
            if (value == 2) {
             //return '<img src="http://localhost:999/rutaimg.jpg" />'
               return 'NO';   
            }
           
        }
    },{
        header: "Bloqueos",
        flex: 1,
        dataIndex: 'id_bloqueos',
        renderer: function(value){
            if (value == 1) {
                return 'SI';
             }
            if (value == 2) {
              return 'NO';   
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
                width: 120,
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:''
            },{
                width: 250,
                xtype: 'textfield',
                itemId: '',
                fieldLabel: '',
                hidden: true
            },'->',{
                xtype: 'button',
                width: 120,
                iconCls : 'icon-edit',
                text: 'BITACORA',
                action:'bitacoraaviso'
            },{
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
                action: 'cerraremail',
                text : 'Cerrar'
            }]      
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'emailautorizados',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
