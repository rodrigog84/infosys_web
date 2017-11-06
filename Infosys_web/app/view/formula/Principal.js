Ext.define('Infosys_web.view.formula.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.formulaprincipal',
    
    requires: ['Ext.toolbar.Paging'],
    
    iconCls: 'icon-grid',

    title : 'Formulario Formulas',
    store: 'Formulas',
    autoHeight: true,
    viewConfig: {
        forceFit: true

    },
    columns: [{
        header: "id",
        flex: 1,
        dataIndex: 'id',
        hidden: true
    },{
        header: "Numero",
        flex: 1,
        dataIndex: 'num_formula'
    },{
        header: "Id_Cliente",
        flex: 1,
        dataIndex: 'id_cliente',
        hidden: true
    },{
        header: "Nombre Cliente",
        flex: 1,
        dataIndex: 'nom_cliente'
    },{
        header: "Rut Cliente",
        flex: 1,
        dataIndex: 'rut_cliente'
    },{
        header: "Formula",
        flex: 1,
        dataIndex: 'nombre_formula'
    },{
        header: "Cantidad",
        flex: 1,
        dataIndex: 'cantidad'
    },{
        header: "Valor",
        flex: 1,
        dataIndex: 'valor'
    },{
        header: "Fecha",
        flex: 1,
        dataIndex: 'fecha_formula',
        type: 'date',
        renderer: Ext.util.Format.dateRenderer('d/m/Y'),
        align: 'center'
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
                action: 'agregarformulas',
                text : 'Agregar'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-edit',
                action: '',
                text : 'Editar'
            },'-',{
                xtype: 'button',
                iconCls : 'icon-pdf',
                text: 'Imprimir PDF',
                action:'exportarformula'
            },'->',{
                width: 250,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: 'Nombre'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscarformulas',
                text : 'Buscar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrarformulas',
                text : 'Cerrar'
            }]      
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Formulas',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
