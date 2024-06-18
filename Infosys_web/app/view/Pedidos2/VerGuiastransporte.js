Ext.define('Infosys_web.view.Pedidos2.VerGuiastransporte' ,{
    extend: 'Ext.window.Window',
    alias : 'widget.verguiastransporte',
    
    requires: ['Ext.toolbar.Paging'],
    title : 'Ver Guias',
    layout: 'fit',
    autoShow: true,
    width: 850,
    height: 450,
    modal: true,
    iconCls: 'icon-sheet',
    y: 10,
    initComponent: function() {
        var me = this
        var cant_documentos = me.cant_documentos;
        var num_registro = me.num_registro;
        var idregistro = me.idregistro;


        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [
            {
                width: 250,
                xtype: 'displayfield',
                labelStyle: ' font-weight:bold',
                fieldStyle: 'font-weight:bold',
                fieldLabel: 'Num Registro',
                value : num_registro,
                align: 'right',
 
            },{
                xtype: 'displayfield',
                fieldLabel : 'Cantidad Documentos',
                labelStyle: ' font-weight:bold',
                fieldStyle: 'font-weight:bold',
                value : cant_documentos
            },{
                xtype: 'textfield',
                itemId: 'idregistro',
                name : 'idregistro',
                value : idregistro,
                hidden: true
            }
            ]      
        }];
        this.items = {
            xtype: 'grid',
            store:Ext.create('Ext.data.Store', {
            fields: ['idregistro','num_registro','idguia','numguia','rut','nombres','direccion'],
            proxy: {
              type: 'ajax',
                actionMethods:  {
                    read: 'POST'
                 },              
                url : preurl +'pedidos2/getGuiasRegistroTransporte?idregistro=' + idregistro,
                reader: {
                    type: 'json',
                    root: 'data'
                }
            },
            autoLoad: true            
            }),
            autoHeight: true,
            viewConfig: {
                forceFit: true,
            },
        columns: [{
        header: "Num Guia",
        width: 100,
        dataIndex: 'numguia'
        
    },{
        header: "Rut Cliente",
        width: 100,
        dataIndex: 'rut'
        
    },{
        header: "Nombre Cliente",
        width: 250,
        dataIndex: 'nombres'
        
    },{
        header: "Direccion",
        width: 250,
        dataIndex: 'direccion'
        
    },{
            header: "Descargar",
            xtype:'actioncolumn',
            width:100,
            items: [{
                icon: 'images/search_page.png',  // Use a URL in the icon config
                tooltip: 'Ver Guia',
                handler: function(grid, rowIndex, colIndex) {
                    var record = grid.getStore().getAt(rowIndex);
                    var idguia = record.data.idguia
                   window.open(preurl + 'facturas/exportPDF?idfactura='+idguia);
                }

            }]
    }




    ],
        };    
        
        this.callParent(arguments);
    }
});
