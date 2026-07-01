Ext.define('Infosys_web.controller.Simulador', {
    extend: 'Ext.app.Controller',

    stores: [
        'simulador.Documentos'
    ],

    models: [
        'simulador.Documento'
    ],

    views: [
        'simulador.Principal'
    ],

    refs: [{
        ref: 'panelprincipal',
        selector: 'panelprincipal'
    },{
        ref: 'simuladorinteresesprincipal',
        selector: 'simuladorinteresesprincipal'
    }],

    init: function() {
        this.control({

            'simuladorinteresesprincipal': {
                afterrender: this.resetearModulo
            },
            'simuladorinteresesprincipal button[action=buscarClienteSimulador]': {
                click: this.buscarCliente
            },
            'simuladorinteresesprincipal #rutBusqueda': {
                specialkey: function(field, e) {
                    if (e.getKey() === e.ENTER) {
                        this.buscarCliente();
                    }
                }
            },
            'simuladorinteresesprincipal button[action=calcularIntereses]': {
                click: this.calcularIntereses
            },
            'simuladorinteresesprincipal button[action=seleccionarTodoSimulador]': {
                click: this.seleccionarTodo
            },
            'simuladorinteresesprincipal button[action=deseleccionarTodoSimulador]': {
                click: this.deseleccionarTodo
            },
            'simuladorinteresesprincipal button[action=exportarPDFSimulador]': {
                click: this.exportarPDFSimulador
            },
            'simuladorinteresesprincipal button[action=cerrarpantalla]': {
                click: this.cerrarpantalla
            },
            'simuladorinteresesprincipal #documentosGrid': {
                selectionchange: function(selModel, selected) {
                    this.actualizarTotales(selected);
                }
            }
        });
    },

    // ── Reset al abrir el módulo ────────────────────────────────────────────────
    resetearModulo: function() {
        var me   = this;
        var view = me.getSimuladorinteresesprincipal();
        if (!view) { return; }

        // Limpiar store
        me.getSimuladorDocumentosStore().removeAll();

        // Limpiar campos del formulario
        view.down('#rutBusqueda').setValue('');
        view.down('#rutConfirmado').setValue('');
        view.down('#nombreClienteDisplay').setValue('');
        view.down('#credUtilizadoDisplay').setValue('');
        view.down('#fechaSimulacion').setValue(new Date());
        view.down('#tasaInteres').setValue(2.0);
        view.down('#diasCobro').setValue(0);

        // Limpiar totales
        me.actualizarTotales([]);
    },

    // ── Buscar cliente por RUT ──────────────────────────────────────────────────
    buscarCliente: function() {
        var me  = this;
        var view = me.getSimuladorinteresesprincipal();
        if (!view) { return; }

        var rut = view.down('#rutBusqueda').getValue();
        if (!rut) {
            Ext.Msg.alert('Atención', 'Ingrese el RUT del cliente.');
            return;
        }

        view.down('#nombreClienteDisplay').setValue('Buscando...');
        view.down('#rutConfirmado').setValue('');

        Ext.Ajax.request({
            url: preurl + 'simulador_intereses/getClienteByRut',
            params: { rut: rut },
            success: function(response) {
                var obj = Ext.decode(response.responseText);
                if (obj.success && obj.data) {
                    view.down('#nombreClienteDisplay').setValue(obj.data.nombres);
                    view.down('#rutConfirmado').setValue(obj.data.rut);
                    var credUtil = obj.data.cred_util || 0;
                    view.down('#credUtilizadoDisplay').setValue('$ ' + Ext.util.Format.number(credUtil, '0,000.'));
                    // Lanzar cálculo automáticamente al encontrar el cliente
                    me.calcularIntereses();
                } else {
                    view.down('#nombreClienteDisplay').setValue('');
                    Ext.Msg.alert('Atención', obj.message || 'Cliente no encontrado.');
                    me.limpiarGrid();
                    me.actualizarTotales([]);
                }
            },
            failure: function() {
                Ext.Msg.alert('Error', 'No se pudo conectar con el servidor.');
                view.down('#nombreClienteDisplay').setValue('');
            }
        });
    },

    // ── Calcular intereses y cargar la grilla ──────────────────────────────────
    calcularIntereses: function() {
        var me   = this;
        var view = me.getSimuladorinteresesprincipal();
        if (!view) { return; }

        var rut  = view.down('#rutConfirmado').getValue();
        if (!rut) {
            Ext.Msg.alert('Atención', 'Primero debe buscar y seleccionar un cliente.');
            return;
        }

        var fechaField = view.down('#fechaSimulacion');
        var tasa       = view.down('#tasaInteres').getValue();
        var diasCobro  = view.down('#diasCobro').getValue();

        if (!fechaField.isValid()) {
            Ext.Msg.alert('Atención', 'Ingrese una fecha de simulación válida.');
            return;
        }

        var fechaStr = fechaField.getRawValue();
        // Convertir de d/m/Y a Y-m-d para PHP
        var partes   = fechaStr.split('/');
        var fechaEnvio = partes.length === 3
            ? partes[2] + '-' + partes[1] + '-' + partes[0]
            : fechaField.getValue();

        var store = me.getSimuladorDocumentosStore();

        store.getProxy().extraParams = {
            rut:              rut,
            fecha_simulacion: fechaEnvio,
            tasa_interes:     tasa,
            dias_cobro:       diasCobro || 0
        };

        view.setLoading(true);

        store.load({
            callback: function(records, operation, success) {
                view.setLoading(false);
                if (!success) {
                    Ext.Msg.alert('Error', 'No se pudo cargar los documentos.');
                    return;
                }
                // Seleccionar todos los registros por defecto
                var grid = view.down('#documentosGrid');
                if (grid) {
                    grid.getSelectionModel().selectAll(true);
                    me.actualizarTotales(grid.getSelectionModel().getSelection());
                }
            }
        });
    },

    // ── Actualizar totales en base a la selección ──────────────────────────────
    seleccionarTodo: function() {
        var me   = this;
        var view = me.getSimuladorinteresesprincipal();
        if (!view) { return; }
        var grid = view.down('#documentosGrid');
        if (grid) {
            grid.getSelectionModel().selectAll(true);
            me.actualizarTotales(grid.getSelectionModel().getSelection());
        }
    },

    deseleccionarTodo: function() {
        var me   = this;
        var view = me.getSimuladorinteresesprincipal();
        if (!view) { return; }
        var grid = view.down('#documentosGrid');
        if (grid) {
            grid.getSelectionModel().deselectAll();
            me.actualizarTotales([]);
        }
    },

    actualizarTotales: function(selected) {
        var me   = this;
        var view = me.getSimuladorinteresesprincipal();
        if (!view) { return; }

        var totalSaldo   = 0;
        var totalInteres = 0;

        Ext.Array.each(selected, function(rec) {
            totalSaldo   += rec.get('saldo')   || 0;
            totalInteres += rec.get('interes') || 0;
        });

        var totalPagar = totalSaldo + totalInteres;
        var fmt = function(n) { return '$ ' + Ext.util.Format.number(n, '0,000.'); };

        view.down('#totalSaldoDisplay').setValue(fmt(totalSaldo));
        view.down('#totalInteresDisplay').setValue(fmt(totalInteres));
        view.down('#totalPagarDisplay').setValue(fmt(totalPagar));
    },

    // ── Exportar PDF ───────────────────────────────────────────────────────────
    exportarPDFSimulador: function() {
        var me   = this;
        var view = me.getSimuladorinteresesprincipal();
        if (!view) { return; }

        var rut  = view.down('#rutConfirmado').getValue();
        if (!rut) {
            Ext.Msg.alert('Atención', 'Primero debe buscar un cliente y calcular los intereses.');
            return;
        }

        var grid     = view.down('#documentosGrid');
        var selected = grid.getSelectionModel().getSelection();

        if (!selected || selected.length === 0) {
            Ext.Msg.alert('Atención', 'Debe seleccionar al menos un documento para generar el PDF.');
            return;
        }

        // Recopilar IDs de los documentos seleccionados
        var ids = Ext.Array.map(selected, function(rec) { return rec.get('id'); });

        // Obtener fecha de simulación en formato Y-m-d
        var fechaField = view.down('#fechaSimulacion');
        var rawFecha   = fechaField.getRawValue();
        var partes     = rawFecha.split('/');
        var fechaEnvio = partes.length === 3
            ? partes[2] + '-' + partes[1] + '-' + partes[0]
            : rawFecha;

        var tasa      = view.down('#tasaInteres').getValue();
        var diasCobro = view.down('#diasCobro').getValue() || 0;

        // Abrir PDF en nueva pestaña usando GET (mismo patrón del sistema)
        var url = preurl + 'simulador_intereses/exportarPDF'
            + '?rut='              + encodeURIComponent(rut)
            + '&fecha_simulacion=' + encodeURIComponent(fechaEnvio)
            + '&tasa_interes='     + encodeURIComponent(tasa)
            + '&dias_cobro='       + encodeURIComponent(diasCobro)
            + '&ids='              + encodeURIComponent(ids.join(','));

        window.open(url);
    },

    // ── Helpers ────────────────────────────────────────────────────────────────
    limpiarGrid: function() {
        var store = this.getSimuladorDocumentosStore();
        if (store) { store.removeAll(); }
    },

    cerrarpantalla: function() {
        var viewport = this.getPanelprincipal();
        if (viewport) { viewport.removeAll(); }
    }
});
