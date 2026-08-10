/**
 * Ventana de confirmación para generar factura de intereses desde el simulador.
 * Se instancia pasando la config: { simulacionData: {...} }
 * simulacionData debe tener: rut, nombre, neto_interes, ids_documentos,
 *                            fecha_simulacion, id_log (opcional, para vincular)
 */
Ext.define('Infosys_web.view.simulador.FacturaDialog', {
    extend: 'Ext.window.Window',
    alias:  'widget.simuladorfacturadialog',

    title:       'Generar Factura de Intereses',
    iconCls:     'icon-factura',
    width:       620,
    modal:       true,
    resizable:   true,
    layout:      'form',
    bodyPadding: 15,
    closeAction: 'destroy',

    // Config inyectada desde el controller
    simulacionData: null,

    initComponent: function() {
        var me = this;
        var d  = me.simulacionData || {};

        var neto = Math.round(d.neto_interes || 0);
        var iva  = Math.round(neto * 0.19);
        var total = neto + iva;

        var hoy = Ext.Date.format(new Date(), 'd/m/Y');
        var glosaPropuesta = 'Intereses por mora al ' + (d.fecha_simulacion || hoy)
            + ' - ' + (d.nombre || '') + ' - Documentos: ' + (d.ids_documentos || '');

        me.items = [{
            xtype: 'displayfield',
            fieldLabel: 'Cliente',
            value: (d.nombre || '') + ' (' + (d.rut_fmt || d.rut || '') + ')',
            fieldStyle: 'font-weight:bold;'
        },{
            xtype: 'textarea',
            fieldLabel: 'Glosa',
            itemId: 'glosaField',
            value: glosaPropuesta,
            rows: 3,
            anchor: '100%',
            allowBlank: false
        },{
            xtype: 'datefield',
            fieldLabel: 'Fecha Factura',
            itemId: 'fechaFacturaField',
            format: 'd/m/Y',
            value: new Date(),
            allowBlank: false,
            anchor: '60%'
        },{
            xtype: 'datefield',
            fieldLabel: 'Fecha Vencimiento',
            itemId: 'fechaVencField',
            format: 'd/m/Y',
            value: new Date(),
            allowBlank: false,
            anchor: '60%'
        },{
            xtype: 'fieldcontainer',
            layout: 'hbox',
            fieldLabel: 'Montos',
            items: [{
                xtype: 'displayfield',
                fieldLabel: 'Neto',
                labelWidth: 40,
                value: '$ ' + Ext.util.Format.number(neto, '0,000.'),
                itemId: 'netoDisplay',
                flex: 1
            },{
                xtype: 'displayfield',
                fieldLabel: 'IVA',
                labelWidth: 30,
                value: '$ ' + Ext.util.Format.number(iva, '0,000.'),
                itemId: 'ivaDisplay',
                flex: 1
            },{
                xtype: 'displayfield',
                fieldLabel: 'Total',
                labelWidth: 40,
                value: '<b style="font-size:13px;color:#1a5276;">$ ' + Ext.util.Format.number(total, '0,000.') + '</b>',
                itemId: 'totalDisplay',
                flex: 1
            }]
        },{
            // Campos ocultos que usará el controller al enviar
            xtype: 'hiddenfield', itemId: 'netoHidden',  value: neto
        },{
            xtype: 'hiddenfield', itemId: 'ivaHidden',   value: iva
        },{
            xtype: 'hiddenfield', itemId: 'totalHidden', value: total
        }];

        me.buttons = [{
            text:    'Generar Factura',
            iconCls: 'icon-save',
            itemId:  'btnConfirmarFactura',
            formBind: true
        },{
            text:    'Cancelar',
            handler: function() { me.close(); }
        }];

        me.callParent(arguments);
    }
});
