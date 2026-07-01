Ext.define('Infosys_web.view.simulador.Principal', {
    extend: 'Ext.panel.Panel',
    alias:  'widget.simuladorinteresesprincipal',

    title:  'Simulador de Intereses',
    layout: 'border',
    iconCls:'icon-grid',

    initComponent: function() {
        var me = this;

        // ── Panel superior: búsqueda + datos cliente + parámetros ──────────────
        var topPanel = Ext.create('Ext.form.Panel', {
            region: 'north',
            height: 160,
            bodyPadding: 8,
            border: false,
            layout: 'anchor',
            defaults: { anchor: '100%' },
            items: [
                // Fila 1: búsqueda por RUT
                {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    defaultType: 'textfield',
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: '<b>RUT Cliente:</b>',
                        labelWidth: 90,
                        width: 260,
                        name: 'rut',
                        itemId: 'rutBusqueda',
                        emptyText: 'Ej: 12345678-9',
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
                        width: 340,
                        margin: '0 0 0 14'
                    },{
                        xtype: 'displayfield',
                        itemId: 'credUtilizadoDisplay',
                        fieldLabel: '<b>Cred. Utilizado:</b>',
                        labelWidth: 110,
                        value: '',
                        flex: 1,
                        margin: '0 0 0 14'
                    },{
                        xtype: 'hiddenfield',
                        itemId: 'rutConfirmado',
                        name: 'rutConfirmado',
                        value: ''
                    }]
                },
                // Fila 2: fecha de simulación + tasa + días de gracia
                {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    margin: '8 0 0 0',
                    items: [{
                        xtype: 'datefield',
                        fieldLabel: '<b>Fecha Simulación:</b>',
                        labelWidth: 130,
                        width: 260,
                        name: 'fecha_simulacion',
                        itemId: 'fechaSimulacion',
                        format: 'd/m/Y',
                        submitFormat: 'Y-m-d',
                        value: new Date(),
                        allowBlank: false
                    },{
                        xtype: 'numberfield',
                        fieldLabel: '<b>Tasa Mensual (%):</b>',
                        labelWidth: 130,
                        width: 260,
                        name: 'tasa_interes',
                        itemId: 'tasaInteres',
                        value: 2.0,
                        minValue: 0,
                        maxValue: 100,
                        decimalPrecision: 4,
                        allowBlank: false,
                        margin: '0 0 0 14'
                    },{
                        xtype: 'numberfield',
                        fieldLabel: '<b>Días de Gracia:</b>',
                        labelWidth: 100,
                        width: 220,
                        name: 'dias_cobro',
                        itemId: 'diasCobro',
                        value: 0,
                        minValue: 0,
                        allowBlank: false,
                        margin: '0 0 0 14'
                    },{
                        xtype: 'button',
                        text: 'Calcular',
                        iconCls: 'icon-reload',
                        action: 'calcularIntereses',
                        margin: '0 0 0 14'
                    }]
                },
                // Fila 3: totales seleccionados
                {
                    xtype: 'fieldcontainer',
                    layout: 'hbox',
                    margin: '8 0 0 0',
                    items: [{
                        xtype: 'displayfield',
                        itemId: 'totalSaldoDisplay',
                        fieldLabel: '<b>Total Saldo Seleccionado:</b>',
                        labelWidth: 175,
                        width: 310,
                        value: '$ 0'
                    },{
                        xtype: 'displayfield',
                        itemId: 'totalInteresDisplay',
                        fieldLabel: '<b>Total Interés Seleccionado:</b>',
                        labelWidth: 175,
                        width: 310,
                        value: '$ 0',
                        margin: '0 0 0 14'
                    },{
                        xtype: 'displayfield',
                        itemId: 'totalPagarDisplay',
                        fieldLabel: '<b>TOTAL A PAGAR:</b>',
                        labelWidth: 120,
                        flex: 1,
                        value: '$ 0',
                        margin: '0 0 0 14',
                        style: 'font-size:14px; font-weight:bold; color:#1a5276;'
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
            selModel: {
                checkOnly: false,
                mode: 'MULTI'
            },
            viewConfig: {
                forceFit: true,
                stripeRows: true
            },
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
                width: 110,
                align: 'right',
                xtype: 'numbercolumn',
                format: '0,000.'
            },{
                header: 'Días Mora',
                dataIndex: 'dias_mora',
                width: 80,
                align: 'center',
                renderer: function(val) {
                    if (val > 0) {
                        return '<span style="color:#c0392b; font-weight:bold;">' + val + '</span>';
                    }
                    return val;
                }
            },{
                header: 'Interés c/IVA',
                dataIndex: 'interes',
                width: 115,
                align: 'right',
                xtype: 'numbercolumn',
                format: '0,000.',
                renderer: function(val) {
                    if (val > 0) {
                        return '<span style="color:#c0392b;">' + Ext.util.Format.number(val, '0,000.') + '</span>';
                    }
                    return Ext.util.Format.number(val, '0,000.');
                }
            },{
                header: 'Total Documento',
                dataIndex: 'total_documento',
                width: 125,
                align: 'right',
                xtype: 'numbercolumn',
                format: '0,000.'
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
                },'->',{
                    xtype: 'button',
                    text: 'Cerrar',
                    iconCls: 'icon-delete',
                    action: 'cerrarpantalla'
                }]
            }]
        });

        me.items = [ topPanel, grid ];

        me.callParent(arguments);
    }
});
