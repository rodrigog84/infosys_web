Ext.define('Infosys_web.view.ordencompra.Principal_recepcion' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.ordencompraprincipalrecepcion',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Ordenes de Compra Recepcionadas ',
    store: 'Ordencomprarecepcion',
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
        header: "Id Proveedor",
        flex: 1,
        dataIndex: 'id_proveedor',
        hidden: true
    },{
        header: "Numero Orden",
        flex: 1,
        dataIndex: 'num_orden'
    },{
        header: "Empresa",
        flex: 1,
        dataIndex: 'empresa'
    },{
        header: "Emitida",
        flex: 1,
        dataIndex: 'emitida'
    },{
        header: "Semi Cumplida",
        flex: 1,
        dataIndex: 'semicumplida'
    },{
        header: "Cumplida",
        flex: 1,
        dataIndex: 'cumplida'
    },{
        header: "Contacto",
        flex: 1,
        dataIndex: 'nombre_contacto'
    },{
        header: "Telefono Contacto",
        flex: 1,
        dataIndex: 'telefono_contacto'
    },{
        header: "E-Mail Contacto",
        flex: 1,
        dataIndex: 'mail_contacto'
    },{
        header: "Total",
        flex: 1,
        dataIndex: 'total',
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
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
                text: 'Recepcionar',
                action: 'recepcionarordencompradef'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-export',
                text: 'Exportar',
                action: 'exportarordencomprarecepcion'
            },{
                xtype: 'button',
                iconCls : 'icon-pdf',
                text: 'Edita / Imprimir PDF',
                action:'generapdf'
            },
            '->',
            {
            
                xtype: 'combo',
                itemId: 'tipoSeleccionId',
                fieldLabel: '',
                forceSelection : true,
                editable : false,
                valueField : 'id',
                displayField : 'nombre',
                emptyText : "Seleccione",
                value: "Nombre",
                store : 'ordencompra.Selector'
            },{
                width: 250,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: ''
            },
            '-',
            {
                xtype: 'button',
                iconCls: 'icon-search',
                text : 'Buscar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrarordendecompra',
                text : 'Cerrar'
            }
            ] 
        },
        {
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Ordencomprarecepcion',
            displayInfo: true
        }];
        
        this.callParent(arguments);
        this.getStore().load();
    }
});
