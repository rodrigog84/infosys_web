Ext.define('Infosys_web.view.simulador.Principal', {
    extend: 'Ext.panel.Panel',
    alias:  'widget.simuladorinteresesprincipal',

    title:  'Simulador de Intereses',
    layout: 'border',
    iconCls:'icon-grid',

    initComponent: function() {
        var me = this;

        // ── Panel superior: búsqueda + parámetros ──────────────────────────────
        var topPanel = Ext.create('Ext.form.Panel', {
            region: 'north',
            height: 80,
            bodyPadding: '8 8 0 8',
            border: false,
            layout: 'anchor',
            defaults: { anchor: '100%' },
            items: [
                // Fila 1: RUT + Nombre + Línea de Crédito
                {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: '<b>RUT Cliente:</b>',
                        labelWidth: 90,
                        width: 255,
                        name: 'rut',
                        itemId: 'rutBusqueda',
                        emptyText: 'Ej: 123456789',
                        enableKeyEvents: true
                    },{
                        xtype: 'button',
                        text: 'Buscar',
                        iconCls: 'icon-search',
                        action: 'buscarClienteSimulador',
                        margin: '0 0 0 6'
                    },{
                        xtype: 'displayfield',
                        itemId: 'nombreClienteDisplay',
                        fieldLabel: '<b>Nombre:</b>',
                        labelWidth: 65,
                        value: '',
                        width: 320,
                        margin: '0 0 0 14'
                    },{
                        xtype: 'displayfield',
                        itemId: 'credUtilizadoDisplay',
                        fieldLabel: '<b>Línea de Crédito Utilizada:</b>',
                        labelWidth: 175,
                        value: '',
                        flex: 1,
                        margin: '0 0 0 14'
                    },{
                        xtype: 'hiddenfield',
                        itemId: 'rutConfirmado',
                        value: ''
                    }]
                },
                // Fila 2: Fecha + Tasa + Calcular (Días de Gracia oculto)
                {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    margin: '6 0 0 0',
                    items: [{
                        xtype: 'datefield',
                        fieldLabel: '<b>Fecha Simulación:</b>',
                        labelWidth: 125,
                        width: 255,
                        itemId: 'fechaSimulacion',
                        format: 'd/m/Y',
                        submitFormat: 'Y-m-d',
                        value: new Date(),
                        allowBlank: false
                    },{
                        xtype: 'numberfield',
                        fieldLabel: '<b>Tasa Mensual (%):</b>',
                        labelWidth: 125,
                        width: 255,
                        itemId: 'tasaInteres',
                        value: 2.0,
                        minValue: 0,
                        maxValue: 100,
                        decimalPrecision: 4,
                        allowBlank: false,
                        margin: '0 0 0 14'
                    },{
                        xtype: 'numberfield',
                        itemId: 'diasCobro',
                        value: 0,
                        hidden: true
                    },{
                        xtype: 'button',
                        text: 'Calcular',
                        iconCls: 'icon-reload',
                        action: 'calcularIntereses',
                        margin: '0 0 0 14'
                    }]
                }
            ]
        });

        // ── Grid: documentos impagos ──────────────────────────────────────────
        var grid = Ext.create('Ext.grid.Panel', {
            region: 'center',
            store: 'simulador.Documentos',
            itemId: 'documentosGrid',
            selType: 'checkboxmodel',
            selModel: { checkOnly: false, mode: 'MULTI' },
            viewConfig: { forceFit: true, stripeRows: true },
            columns: [{
                header: 'Documento',
                dataIndex: 'nombre_documento',
                flex: 2,
                sortable: true
            },{
                header: 'F. Emisión',
                dataIndex: 'fecha_emision_fmt',
                width: 90,
                align: 'center'
            },{
                header: 'F. Vencimiento',
                dataIndex: 'fecha_venc_fmt',
                width: 105,
                align: 'center'
            },{
                header: 'Saldo',
                dataIndex: 'saldo',
                width: 115,
                align: 'right',
                xtype: 'numbercolumn',
                format: '0,000.'
            },{
                header: 'Días Mora',
                dataIndex: 'dias_mora',
                width: 80,
                align: 'center',
                renderer: function(val) {
                    return val > 0
                        ? '<span style="color:#c0392b;font-weight:bold;">' + val + '</span>'
                        : val;
                }
            },{
                header: 'Interés s/IVA',
                dataIndex: 'interes',
                width: 115,
                align: 'right',
                renderer: function(val) {
                    return val > 0
                        ? '<span style="color:#c0392b;">$ ' + Ext.util.Format.number(val, '0,000.') + '</span>'
                        : '$ ' + Ext.util.Format.number(val, '0,000.');
                }
            },{
                xtype: 'actioncolumn',
                header: 'Doc.',
                width: 42,
                align: 'center',
                sortable: false,
                items: [{
                    icon: preurl_js + 'resources/images/icons/page_white_acrobat.png',
                    tooltip: 'Ver documento original',
                    handler: function(grid, rowIndex) {
                        var rec       = grid.getStore().getAt(rowIndex);
                        var idFactura = rec.get('id_factura');
                        var tipoDoc   = rec.get('tipodocumento');
                        if (!idFactura) {
                            Ext.Msg.alert('Atención', 'Este documento no tiene PDF asociado.');
                            return;
                        }
                        window.open(tipoDoc == 101
                            ? preurl + 'facturas/exportFePDF/' + idFactura
                            : preurl + 'facturas/exportPDF/?idfactura=' + idFactura);
                    },
                    getClass: function(val, meta, rec) {
                        return rec.get('id_factura') ? 'icon-pdf' : 'x-hide-display';
                    }
                }]
            }],
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [{
                    xtype: 'button',
                    text: 'Seleccionar Todo',
                    iconCls: 'icon-accept',
                    action: 'seleccionarTodoSimulador'
                },{
                    xtype: 'button',
                    text: 'Deseleccionar Todo',
                    iconCls: 'icon-delete',
                    action: 'deseleccionarTodoSimulador'
                },'-',{
                    xtype: 'button',
                    text: 'Exportar PDF',
                    iconCls: 'icon-pdf',
                    action: 'exportarPDFSimulador'
                },{
                    xtype: 'button',
                    text: 'Exportar Excel',
                    iconCls: 'icon-exel',
                    action: 'exportarExcelSimulador'
                },'-',{
                    xtype: 'button',
                    text: 'Historial',
                    iconCls: 'icon-grid',
                    action: 'verHistorialSimulador',
                    itemId: 'btnHistorial',
                    disabled: true
                },'->',{
                    xtype: 'button',
                    text: 'Cerrar',
                    iconCls: 'icon-delete',
                    action: 'cerrarpantalla'
                }]
            }]
        });

        // ── Barra de totales: región south ────────────────────────────────────
        var totalesPanel = Ext.create('Ext.panel.Panel', {
            region: 'south',
            height: 46,
            border: false,
            bodyStyle: 'background:#1d2d44; padding:0;',
            layout: { type: 'hbox', align: 'middle', pack: 'start' },
            defaults: { xtype: 'displayfield', labelStyle: 'color:#aac4e8; font-size:11px; font-weight:normal; padding:0;' },
            items: [
                // Separador izquierdo
                { xtype: 'component', width: 14 },
                {
                    itemId: 'totalSaldoDisplay',
                    fieldLabel: 'Total Saldo',
                    labelWidth: 75,
                    value: '$ 0',
                    fieldStyle: 'color:#ffffff; font-size:13px; font-weight:bold; padding:0 0 0 4px;',
                    width: 200
                },
                { xtype: 'component', html: '<div style="width:1px;height:26px;background:#3a5270;margin:0 10px;"></div>' },
                {
                    itemId: 'totalInteresDisplay',
                    fieldLabel: 'Interés s/IVA',
                    labelWidth: 82,
                    value: '$ 0',
                    fieldStyle: 'color:#f0c8c8; font-size:13px; font-weight:bold; padding:0 0 0 4px;',
                    width: 210
                },
                { xtype: 'component', html: '<div style="width:1px;height:26px;background:#3a5270;margin:0 10px;"></div>' },
                {
                    itemId: 'totalInteresIvaDisplay',
                    fieldLabel: 'Interés c/IVA',
                    labelWidth: 82,
                    value: '$ 0',
                    fieldStyle: 'color:#ff9090; font-size:13px; font-weight:bold; padding:0 0 0 4px;',
                    width: 210
                },
                // Empujar total a pagar hacia la derecha
                { xtype: 'component', flex: 1 },
                {
                    itemId: 'totalPagarDisplay',
                    fieldLabel: 'TOTAL A PAGAR',
                    labelWidth: 110,
                    labelStyle: 'color:#7ecfff; font-size:13px; font-weight:bold; padding:0;',
                    value: '$ 0',
                    fieldStyle: 'color:#ffffff; font-size:18px; font-weight:bold; padding:0 0 0 6px;',
                    width: 260
                },
                { xtype: 'component', width: 10 }
            ]
        });

        me.items = [ topPanel, grid, totalesPanel ];

        me.callParent(arguments);
    }
});
