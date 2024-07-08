Ext.define('Infosys_web.view.Pedidos2.Principal' ,{
    extend: 'Ext.grid.Panel',
    alias : 'widget.pedidosprincipalformula',
    
    requires: ['Ext.toolbar.Paging'],     
    iconCls: 'icon-grid',

    title : 'Nota Pedido',
    store: 'Pedidos',
    height: 300,
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
        dataIndex: 'num_pedido'
               
    }/*,{
        header: "Fecha",
        flex: 1,
        dataIndex: 'fecha_doc',
        type: 'date',
        renderer:Ext.util.Format.dateRenderer('d/m/Y') 
    }*/,{
        header: "Fecha Pedido",
        flex: 1,
        dataIndex: 'fecha_pedido',
        type: 'date',
        renderer:Ext.util.Format.dateRenderer('d/m/Y')
    },{
        header: "Rut",
        flex: 1,
        align: 'right',
        dataIndex: 'rut_cliente'
    },{
        header: "Id_Cliente",
        flex: 1,
        align: 'right',
        dataIndex: 'id_cliente',
        hidden: true
    },{
        header: "Razon Social",
         width: 300,
        dataIndex: 'nombre_cliente'
    },{
        header: "Vendedor",
        flex: 1,
        dataIndex: 'nom_vendedor'
    },{
        header: "Cond Pago",
        flex: 1,
        dataIndex: 'id_pago',
        hidden: true
    },{
        header: "Neto",
        flex: 1,
        dataIndex: 'neto',
        hidden: true,
        aling: 'rigth',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
    },{
        header: "Descuento",
        flex: 1,
        dataIndex: 'descuento',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")},
        hidden: true
    },{
        header: "Total Venta",
        flex: 1,
        dataIndex: 'total',
        align: 'right',
        renderer: function(valor){return Ext.util.Format.number(parseInt(valor),"0,00")}
        
    },{
        header: "Situacion",
        flex: 1,
        dataIndex: 'situacionpedido',
    },{
            header: "Adj Receta",
            xtype:'actioncolumn',
            width:100,
            align: 'center',
            items: [{
                iconCls: 'icon-upload',  // Use a URL in the icon config
                tooltip: 'Adjuntar Receta',
                handler: function(grid, rowIndex, colIndex) {
                    var r = grid.getStore().getAt(rowIndex);
                    //salert("Edit " + rec.get('firstname'));
                    var vista = this.up('pedidosprincipalformula');
                    console.log('presiona adjuntar receta')
                   // vista.fireEvent('adjuntarReceta',rec)

                       Ext.Ajax.request({
                           //url: preurl + 'cuentacorriente/getCuentaCorriente/' + record.get('cuenta') + '/' + editor.value ,
                           url: preurl + 'pedidos2/getPedido?idpedido='+r.data.id,
                           success: function(response, opts) {                         
                                console.log(response)
                              var obj = Ext.decode(response.responseText);
                            console.log(obj)
                              

                                Ext.create('Infosys_web.view.Pedidos2.AdjuntarReceta', {  idpedido: r.data.id,
                                                                                            cliente: obj.data.nombre_cliente,
                                                                                            rut : obj.data.rut,
                                                                                            num_pedido: obj.data.num_pedido,
                                                                                            vistaPrincipal: grid});    

                           },
                           failure: function(response, opts) {
                              console.log('server-side failure with status code ' + response.status);
                           }
                        });  


                },
                isDisabled: function(view, rowIndex, colIndex, item, record) {
                    // Returns true if 'editable' is false (, null, or undefined)
                    console.log(record.get('cantidad_requiere_receta'))
                    if(record.get('cantidad_requiere_receta') > 0){
                        return false;
                    }else{
                        return true;
                    }
                }                
            }]     
        
    },{
            header: "OC Ext",
            xtype:'actioncolumn',
            width:80,
            align: 'center',
            items: [{
                iconCls: 'icon-upload',  // Use a URL in the icon config
                tooltip: 'Adjuntar OC',
                handler: function(grid, rowIndex, colIndex) {
                    var r = grid.getStore().getAt(rowIndex);
                    //salert("Edit " + rec.get('firstname'));
                    //var vista = this.up('pedidosprincipalformula');
                    //vista.fireEvent('adjuntarOc',rec,1)

                    Ext.Ajax.request({
                               //url: preurl + 'cuentacorriente/getCuentaCorriente/' + record.get('cuenta') + '/' + editor.value ,
                               url: preurl + 'pedidos2/getPedido?idpedido='+r.data.id,
                               success: function(response, opts) {                         
                                    console.log(response)
                                  var obj = Ext.decode(response.responseText);
                                console.log(obj)
                                  

                                Ext.create('Infosys_web.view.Pedidos2.AdjuntarOc', {  idpedido: r.data.id,
                                                                                            cliente: obj.data.nombre_cliente,
                                                                                            rut : obj.data.rut,
                                                                                            num_pedido: obj.data.num_pedido,
                                                                                            vistaPrincipal: grid}); 


                               },
                               failure: function(response, opts) {
                                  console.log('server-side failure with status code ' + response.status);
                               }
                            });  



                }            
            }]     
        
    },{
            header: "OC Int",
            xtype:'actioncolumn',
            width:80,
            align: 'center',
            items: [{
                iconCls: 'icon-note',  // Use a URL in the icon config
                tooltip: 'Generar OC',
                handler: function(grid, rowIndex, colIndex) {
                    var r = grid.getStore().getAt(rowIndex);
                    //salert("Edit " + rec.get('firstname'));
                    //var vista = this.up('pedidosprincipalformula');
                    //vista.fireEvent('adjuntarOc',rec,2)
                    Ext.Ajax.request({
                               //url: preurl + 'cuentacorriente/getCuentaCorriente/' + record.get('cuenta') + '/' + editor.value ,
                               url: preurl + 'pedidos2/getPedido?idpedido='+r.data.id,
                               success: function(response, opts) {                         
                                    console.log(response)
                                  var obj = Ext.decode(response.responseText);
                                console.log(obj)
                                  

                 
                                Ext.create('Infosys_web.view.Pedidos2.GenerarOcint', {  idpedido: r.data.id,
                                                                                            cliente: obj.data.nombre_cliente,
                                                                                            rut : obj.data.rut,
                                                                                            num_pedido: obj.data.num_pedido,
                                                                                            vistaPrincipal: grid}); 



                 
                               },
                               failure: function(response, opts) {
                                  console.log('server-side failure with status code ' + response.status);
                               }
                            });  


                }            
            }]     
        
    },{
            header: "Genera Guia",
            xtype:'actioncolumn',
            width:85,
            align: 'center',
            items: [{
                iconCls: 'icon-edit',  // Use a URL in the icon config
                tooltip: 'Generar Guia',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    var cumpleoc = rec.raw.cumpleoc
                    var cumplereceta = rec.raw.cumplereceta

                    if(cumpleoc == 1 && cumplereceta == 1){
                        var vista = this.up('pedidosprincipalformula');
                        vista.fireEvent('iguiasdespacho',rec)

                    }else if(cumpleoc == 1 && cumplereceta == 0){
                        Ext.Msg.show({
                            title: 'Alerta',
                            msg: 'Pedido con carga de receta pendiente',
                            width: 400,
                            buttons: Ext.Msg.OK,
                            icon: Ext.Msg.WARNING
                        });


                    }else if(cumpleoc == 0 && cumplereceta == 1){
                        Ext.Msg.show({
                            title: 'Alerta',
                            msg: 'Pedido con generacion de OC Pendiente',
                            width: 400,
                            buttons: Ext.Msg.OK,
                            icon: Ext.Msg.WARNING
                        });

                    }else{
                        Ext.Msg.show({
                            title: 'Alerta',
                            msg: 'Pedido con generacion de OC y Receta Pendiente',
                            width: 400,
                            buttons: Ext.Msg.OK,
                            icon: Ext.Msg.WARNING
                        });

                    }


                    //salert("Edit " + rec.get('firstname'));

                },
               /* isDisabled: function(view, rowIndex, colIndex, item, record) {
                    return true;
                }*/                              
            }]     
        
    },{
            header: "Ver Guias",
            xtype:'actioncolumn',
            width:85,
            align: 'center',
            items: [{
                iconCls: 'icon-search',  // Use a URL in the icon config
                tooltip: 'Ver Guias',
                handler: function(grid, rowIndex, colIndex) {
                    var rec = grid.getStore().getAt(rowIndex);
                    //salert("Edit " + rec.get('firstname'));
                    var vista = this.up('pedidosprincipalformula');
                    vista.fireEvent('iguiasdespachover',rec,1)
                },
                /*isDisabled: function(view, rowIndex, colIndex, item, record) {
                    return true;
                } */                             
            }]     
        
    },{
        header: "Ver",
        xtype:'actioncolumn',
        align: 'center',
        width:80,
        items: [{
            icon: 'images/search_page.png',  // Use a URL in the icon config
            tooltip: 'Ver Pedido',
            handler: function(grid, rowIndex, colIndex) {
                var rec = grid.getStore().getAt(rowIndex);
                window.open(preurl +'pedidos/exportPDF/?idpedidos=' + rec.raw.id)
              
            }
            
            }
        ],
    },{
        header: "Bodega",
        flex: 1,
        dataIndex: 'nom_bodega',
        hidden: true
    },{
        header: "Id Bodega",
        flex: 1,
        dataIndex: 'id_bodega',
        hidden: true
    },{
        header: "Estado",
        flex: 1,
        dataIndex: 'estado',
        hidden: true
    }],
    
    initComponent: function() {
        var me = this

        this.dockedItems = [{
            xtype: 'toolbar',
            dock: 'top',
            items: [{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'agregarpedido',
                text : 'Genera Pedido'
            },{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'editarpedidos',
                text : 'Editar / Agregar'
            },{
                xtype: 'button',
                iconCls : 'icon-pdf',
                text: 'Imprimir PDF',
                action:'exportarpedidos'
            },{
                xtype: 'button',
                width: 120,
                iconCls : 'icon-exel',
                text: 'Exportar EXCEL',
                action:'exportarexcelpedidos'
            },{
                xtype: 'button',
                iconCls: 'icon-add',
                action: 'estadopedidos',
                text : 'Estado Pedido'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'eliminar2',
                text : 'Elimina'
            },'->',{
                xtype: 'combo',
                width: 130,
                itemId: 'tipoSeleccionId',
                fieldLabel: '',
                forceSelection : true,
                editable : false,
                valueField : 'id',
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'clientes.Selector2'
            },{
                width: 240,
                xtype: 'textfield',
                itemId: 'nombreId',
                fieldLabel: ''
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscarpedido',
                text : 'Buscar'
            },{
                xtype: 'button',
                iconCls: 'icon-delete',
                action: 'cerrarpedidos',
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
                //value: "1",
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'Bodegas'
            },'-',{
                xtype: 'button',
                width: 160,
                iconCls : 'icon-pdf',
                text: 'Informe Produccion',
                action:'exportarpdf',
                hidden: true
            },'->',{
                xtype: 'combo',
                itemId: 'vendedorId',
                labelWidth: 80,
                width: 360,
                fieldCls: 'required',
                maxHeight: 25,
                fieldLabel: 'VENDEDOR',
                forceSelection : true,
                name : 'id_seleccion',
                valueField : 'id',
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'Vendedores'
            },'-',{
                xtype: 'combo',
                itemId: 'Seleccion2Id',
                labelWidth: 60,
                width: 205,
                fieldCls: 'required',
                maxHeight: 25,
                fieldLabel: '',
                forceSelection : true,
                name : 'id_seleccion',
                valueField : 'id',
                value: "1",
                displayField : 'nombre',
                emptyText : "Seleccione",
                store : 'Pedidos.Selector5'
            },'-',{
                xtype: 'button',
                iconCls: 'icon-search',
                action: 'buscarpedidos',
                text : 'Buscar',
                hidden: true
            }],
        },{
            xtype: 'pagingtoolbar',
            dock:'bottom',
            store: 'Pedidos',
            displayInfo: true
        }];
        
        this.callParent(arguments);
    }
});
