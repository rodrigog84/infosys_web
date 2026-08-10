Ext.define('Infosys_web.view.simulador.LogPanel', {
    extend: 'Ext.window.Window',
    alias:  'widget.simuladorlogpanel',

    title:     'Historial de Simulaciones',
    iconCls:   'icon-grid',
    width:     1050,
    height:    500,
    maximizable: true,
    modal:     true,
    resizable: true,
    layout:    'fit',
    closeAction: 'destroy',

    // Se inyecta desde el controller antes de show()
    logStore: null,

    initComponent: function() {
        var me = this;

        me.items = [{
            xtype: 'grid',
            itemId: 'logGrid',
            store: me.logStore,
            viewConfig: { stripeRows: true },
            columns: [{
                header: 'Fecha Exportación',
                dataIndex: 'fecha_ejecucion_fmt',
                width: 135,
                align: 'center'
            },{
                header: 'F. Simulación',
                dataIndex: 'fecha_simulacion_fmt',
                width: 100,
                align: 'center'
            },{
                header: 'Tasa %',
                dataIndex: 'tasa_interes',
                width: 60,
                align: 'right',
                renderer: function(v) { return Ext.util.Format.number(v, '0.00'); }
            },{
                header: 'Total Saldo',
                dataIndex: 'total_saldo',
                width: 110,
                align: 'right',
                renderer: function(v) { return '$ ' + Ext.util.Format.number(v, '0,000.'); }
            },{
                header: 'Interés c/IVA',
                dataIndex: 'total_interes_con_iva',
                width: 110,
                align: 'right',
                renderer: function(v) {
                    return '<span style="color:#c0392b;">$ ' + Ext.util.Format.number(v, '0,000.') + '</span>';
                }
            },{
                header: 'Total a Pagar',
                dataIndex: 'total_pagar',
                width: 115,
                align: 'right',
                renderer: function(v) {
                    return '<b>$ ' + Ext.util.Format.number(v, '0,000.') + '</b>';
                }
            },{
                header: 'Tipo',
                dataIndex: 'tipo_exportacion',
                width: 50,
                align: 'center'
            },{
                header: 'Usuario',
                dataIndex: 'nombre_usuario',
                flex: 1,
                minWidth: 100
            },{
                header: 'Factura N°',
                dataIndex: 'num_factura_generada',
                width: 90,
                align: 'center',
                renderer: function(v, meta, rec) {
                    if (v) {
                        return '<span style="color:#27ae60;font-weight:bold;">' + v + '</span>';
                    }
                    return '<span style="color:#bbb;">—</span>';
                }
            },{
                xtype: 'actioncolumn',
                header: 'Acciones',
                width: 130,
                align: 'center',
                items: [{
                    icon: preurl_js + 'resources/images/icons/page_white_acrobat.png',
                    tooltip: 'Descargar PDF de simulación',
                    handler: function(grid, rowIndex) {
                        var rec = grid.getStore().getAt(rowIndex);
                        window.open(me._buildUrl('exportarPDF', rec));
                    }
                },{
                    icon: preurl_js + 'resources/images/icons/page_white_excel.png',
                    tooltip: 'Descargar Excel de simulación',
                    handler: function(grid, rowIndex) {
                        var rec = grid.getStore().getAt(rowIndex);
                        window.open(me._buildUrl('exportarExcel', rec));
                    }
                },{
                    icon: preurl_js + 'resources/images/icons/report_go.png',
                    tooltip: 'Ver Factura generada (PDF)',
                    getClass: function(v, meta, rec) {
                        return rec.get('id_factura_generada') ? '' : 'x-hide-display';
                    },
                    handler: function(grid, rowIndex) {
                        var rec = grid.getStore().getAt(rowIndex);
                        var idFactura = rec.get('id_factura_generada');
                        if (idFactura) {
                            window.open(preurl + 'facturaglosa/exportPDF/?idfactura=' + idFactura);
                        }
                    }
                },{
                    icon: preurl_js + 'resources/images/icons/coins_add.png',
                    tooltip: 'Generar Factura de Intereses',
                    getClass: function(v, meta, rec) {
                        return rec.get('id_factura_generada') ? 'x-hide-display' : '';
                    },
                    handler: function(grid, rowIndex) {
                        var rec = grid.getStore().getAt(rowIndex);
                        if (rec.get('id_factura_generada')) {
                            Ext.Msg.show({
                                title:   'Ya facturada',
                                msg:     'Factura N\u00b0 <b>' + rec.get('num_factura_generada') + '</b> ya fue generada para esta simulaci\u00f3n.',
                                buttons: Ext.Msg.OK,
                                icon:    Ext.Msg.INFO,
                                width:   320
                            });
                            return;
                        }
                        if (me.onGenerarFactura) {
                            me.onGenerarFactura(rec);
                        }
                    }
                }]
            }],

            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: ['->',{
                    xtype: 'button',
                    text: 'Cerrar',
                    iconCls: 'icon-delete',
                    handler: function() { me.close(); }
                }]
            },{
                xtype: 'pagingtoolbar',
                dock: 'bottom',
                store: me.logStore,
                displayInfo: true,
                displayMsg: 'Mostrando {0} - {1} de {2} simulaciones',
                emptyMsg: 'Sin simulaciones registradas'
            }]
        }];

        me.callParent(arguments);
    },

    // Construye la URL para re-descargar un exportable histórico
    _buildUrl: function(endpoint, rec) {
        return preurl + 'simulador_intereses/' + endpoint
            + '?rut='              + encodeURIComponent(rec.get('rut'))
            + '&fecha_simulacion=' + encodeURIComponent(rec.get('fecha_simulacion'))
            + '&tasa_interes='     + encodeURIComponent(rec.get('tasa_interes'))
            + '&dias_cobro='       + encodeURIComponent(rec.get('dias_cobro'))
            + '&ids='              + encodeURIComponent(rec.get('ids_documentos'));
    }
});
