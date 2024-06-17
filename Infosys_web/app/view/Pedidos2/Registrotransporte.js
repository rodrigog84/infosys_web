    Ext.define('Infosys_web.view.Pedidos2.Registrotransporte', {
        extend: 'Ext.window.Window',
        alias : 'widget.registrotransporte',


        requires: ['Ext.toolbar.Paging'],
        title : 'Registro de Transporte (en construccion)',
        layout: 'fit',
        autoShow: true,
        width: 980,
        height: 450,
        modal: true,
        iconCls: 'icon-sheet',
        y: 10,


        initComponent: function() {
            var me = this;
            var cliente = '1';
            var rut = '1';
            var num_pedido = '1';
            var idpedido = '1';


            this.dockedItems = [{
                xtype: 'toolbar',
                dock: 'top',
                items: [{
                                    xtype: 'textfield',
                                    fieldCls: 'required',
                                    maxHeight: 25,
                                    width: 450,
                                    labelWidth: 350,
                                    name: 'num_registro',
                                    itemId: 'ticketId',
                                    fieldLabel: '<b>NUMERO REGISTRO TRANSPORTE</b>',
                                    readOnly: true
                                }
                ]      
            }, {
            xtype: 'toolbar',
            dock: 'bottom',
            ui: 'footer',
            items: ['->', {
                xtype: 'button',
                text: 'Generar Registro de Transporte',
                iconCls: 'icon-add',
                handler: function() {


                    // Obtener el número de registro de transporte
                    var numRegistro = me.down('#ticketId').getValue();

                    // Obtener los registros seleccionados
                    var selectedRecords = [];
                    var store = me.down('grid').getStore();
                    store.each(function(record) {
                        if (record.get('selected')) {
                            selectedRecords.push(record.get('idguia'));
                        }
                    });

                    // Verificar si hay registros seleccionados
                    if (selectedRecords.length === 0) {
                        //Ext.Msg.alert('Error', 'Debe seleccionar al menos un registro.');

                        Ext.Msg.show({
                            title: 'Alerta',
                            msg: 'Debe seleccionar al menos un registro',
                            width: 400,
                            buttons: Ext.Msg.OK,
                            icon: Ext.Msg.WARNING
                        });

                        return;
                    }


                    // Enviar los datos al servidor mediante una solicitud AJAX
                    Ext.Ajax.request({
                        url: preurl +'pedidos2/saveRegistroTransporte', // Cambia esto a la URL de tu servidor
                        method: 'POST',
                        params: {
                            numRegistro: numRegistro,
                            selectedRecords: Ext.encode(selectedRecords)
                        },
                        success: function(response) {
                            var result = Ext.decode(response.responseText);
                            if (result.success) {


                                Ext.Msg.show({
                                    title: 'Éxito',
                                    msg: 'Registro de Transporte generado exitosamente',
                                    width: 400,
                                    buttons: Ext.Msg.OK,
                                    icon: Ext.Msg.INFO
                                });


                                // Actualizar el store de la otra vista
                                var principalTransporteView = Ext.ComponentQuery.query('pedidosprincipaltransporte')[0];
                                if (principalTransporteView) {
                                    principalTransporteView.getStore().load();
                                }                                

                                me.close();


                                // Puedes añadir cualquier lógica adicional aquí
                            } else {
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Hubo un problema al generar el Registro de Transporte',
                                    width: 400,
                                    buttons: Ext.Msg.OK,
                                    icon: Ext.Msg.ERROR
                                });

                            }
                        },
                        failure: function(response) {

                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Error en la solicitud al servidor.',
                                    width: 400,
                                    buttons: Ext.Msg.OK,
                                    icon: Ext.Msg.ERROR
                                });

                        }
                    });


                }
            }]
        }];

            this.items = {
                        xtype: 'grid',
                        store:Ext.create('Ext.data.Store', {
                        fields: ['numguia','idguia','idpedido','rut','nombres','direccion','comuna'],
                        proxy: {
                          type: 'ajax',
                            actionMethods:  {
                                read: 'POST'
                             },              
                            url : preurl +'pedidos2/getGuiassinRT',
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
                    columns: [ {
                    xtype: 'checkcolumn',
                    text: 'Seleccionar',
                    dataIndex: 'selected', // Campo que almacena el estado de selección
                    width: 100
                },{
                    header: "Num Guia",
                    width: 150,
                    dataIndex: 'numguia'
                    
                },{
                    header: "Rut",
                    width: 150,
                    dataIndex: 'rut'
                    
                },{
                    header: "Nombre",
                    width: 150,
                    dataIndex: 'nombres'
                    
                },{
                    header: "Direcci&oacute;n",
                    width: 150,
                    dataIndex: 'direccion'
                    
                },{
                    header: "Comuna",
                    width: 150,
                    dataIndex: 'comuna'
                    
                },{
                        header: "Ver Guia",
                        xtype:'actioncolumn',
                        width:110,
                        items: [{
                            iconCls: 'icon-search',  // Use a URL in the icon config
                            tooltip: 'Ver OC',
                            handler: function(grid, rowIndex, colIndex) {
                                var record = grid.getStore().getAt(rowIndex);
                                var idguia = record.data.idguia
                               window.open(preurl + 'facturas/exportPDF?idfactura='+idguia);
                            }
                        }]
                }




                ],
                    };


            me.callParent(arguments);
            
        }

    });
