Ext.define('Infosys_web.view.ordencompra.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.ordencompraprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Ordenes de Compra ',
    store: 'Orden_compra',
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
        header: "Rut",
        flex: 1,
        dataIndex: 'rut'
    },{
        header: "Direccion",
        flex: 1,
        dataIndex: 'direccion',
        hidden: true
    },{
        header: "Giro",
        flex: 1,
        dataIndex: 'giro',
        hidden: true
    },{
        header: "Ciudad",
        flex: 1,
        dataIndex: 'ciudad',
        hidden: true
    },{
        header: "Comuna",
        flex: 1,
        dataIndex: 'comuna',
        hidden: true
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
        header: "Afecto",
        flex: 1,
        dataIndex: 'Afecto',
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")},
        hidden: true
    },{
        header: "Descuento",
        flex: 1,
        dataIndex: 'descuento',
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
    },{
        header: "Neto",
        flex: 1,
        dataIndex: 'neto',
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
    },{
        header: "Iva",
        flex: 1,
        dataIndex: 'iva',
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
    },{
        header: "Total",
        flex: 1,
        dataIndex: 'total',
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
    },{
        header: "Fecha",
        flex: 1,
        dataIndex: 'fecha',
        type: 'date',
        renderer:Ext.util.Format.dateRenderer('d/m/Y') 
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
                text: 'Agregar',
                action: 'agregarordencompra'
            },{
                xtype: 'button',
                iconCls: 'icon-add',
                text: 'Actualizar',
                action: 'actualizarOrden',
                hidden: true
            },{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'editarorden',
                text : 'Editar/Agregar'
            },{
                xtype: 'button',
                iconCls: 'icon-add',
                text: 'Recepcionar',
                action: 'recepcionarordencompra'
            },{
                xtype: 'button',
                iconCls: 'icon-add',
                text: 'Recep Forzada',
                action: 'recepcionforzada'
            },{
                xtype: 'button',
                iconCls : 'icon-pdf',
                text: 'Imprimir PDF',
                action: 'exportarordencompra'
            },{
                xtype: 'button',
                iconCls : 'icon-exel',
                text: 'EXCEL',
                action:'exportarexcelordencomprap'
            },'->',{
                xtype: 'button',
                iconCls: 'icon-email',
                text: 'E-mail',
                action: 'enviaremail'
            },{
                xtype: 'combo',
                itemId: 'tipoSeleccionId',
                fieldLabel: '',
                width: 130,
                forceSelection : true,
                editable : false,
                valueField : 'id',
                displayField : 'nombre',
                emptyText : "Seleccione",
                value: "Nombre",
                store : 'ordencompra.Selector'
            },{
                width: 230,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: ''
            },
            '-',
            {
                xtype: 'button',
                iconCls: 'icon-search',
                text : 'Buscar',
                action: 'buscarorden'                
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
            store: 'Orden_compra',
            displayInfo: true
        }];
        
        this.callParent(arguments);
        this.getStore().load();
    }
});
