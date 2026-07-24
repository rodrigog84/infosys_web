Ext.define('Infosys_web.controller.Simulador', {
    extend: 'Ext.app.Controller',

    stores: [
        'simulador.Documentos',
        'simulador.Log'
    ],

    models: [
        'simulador.Documento',
        'simulador.Log'
    ],

    views: [
        'simulador.Principal',
        'simulador.LogPanel'
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
                        this.formatearRutDisplay(field);
                        this.buscarCliente();
                    }
                },
                blur: function(field) {
                    this.formatearRutDisplay(field);
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
            'simuladorinteresesprincipal button[action=exportarExcelSimulador]': {
                click: this.exportarExcelSimulador
            },
            'simuladorinteresesprincipal button[action=verHistorialSimulador]': {
                click: this.verHistorialSimulador
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

    // ── Utilidades RUT ─────────────────────────────────────────────────────────
    // Deja solo dígitos y K/k
    stripRut: function(rut) {
        return rut.replace(/[^0-9kK]/g, '');
    },

    // Formatea para mostrar: 12345678-9
    formatRut: function(rut) {
        rut = rut.replace(/[^0-9kK]/g, '');
        if (rut.length < 2) { return rut; }
        return rut.slice(0, -1) + '-' + rut.slice(-1).toUpperCase();
    },

    // Aplica el formato visual al campo sin alterar lo que se enviará al server
    formatearRutDisplay: function(field) {
        var raw = field.getValue();
        if (!raw) { return; }
        var formatted = this.formatRut(raw);
        // Actualiza solo la presentación visual del campo
        field.setRawValue(formatted);
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

        // Deshabilitar botón de historial
        var btnHistorial = view.down('#btnHistorial');
        if (btnHistorial) { btnHistorial.setDisabled(true); }
    },

    // ── Buscar cliente por RUT ──────────────────────────────────────────────────
    buscarCliente: function() {
        var me  = this;
        var view = me.getSimuladorinteresesprincipal();
        if (!view) { return; }

        var rutRaw = view.down('#rutBusqueda').getValue();
        if (!rutRaw) {
            Ext.Msg.alert('Atención', 'Ingrese el RUT del cliente.');
            return;
        }
        // Quitar guión y espacios antes de enviar al servidor
        var rut = this.stripRut(rutRaw);

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
                    // Habilitar botón historial para este cliente
                    var btnH = view.down('#btnHistorial');
                    if (btnH) { btnH.setDisabled(false); }
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

        var totalSaldo         = 0;
        var totalInteres       = 0;
        var totalInteresConIva = 0;

        Ext.Array.each(selected, function(rec) {
            totalSaldo         += rec.get('saldo')           || 0;
            totalInteres       += rec.get('interes')         || 0;
            totalInteresConIva += rec.get('interes_con_iva') || 0;
        });

        var totalPagar = totalSaldo + totalInteresConIva;
        var fmt = function(n) { return '$ ' + Ext.util.Format.number(n, '0,000.'); };

        view.down('#totalSaldoDisplay').setValue(fmt(totalSaldo));
        view.down('#totalInteresDisplay').setValue(fmt(totalInteres));
        view.down('#totalInteresIvaDisplay').setValue(fmt(totalInteresConIva));
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

        // Guardar log antes de abrir
        me._guardarLogSimulacion(view, selected, fechaEnvio, tasa, diasCobro, ids, 'PDF');

        // Abrir PDF en nueva pestaña usando GET (mismo patrón del sistema)
        var url = preurl + 'simulador_intereses/exportarPDF'
            + '?rut='              + encodeURIComponent(rut)
            + '&fecha_simulacion=' + encodeURIComponent(fechaEnvio)
            + '&tasa_interes='     + encodeURIComponent(tasa)
            + '&dias_cobro='       + encodeURIComponent(diasCobro)
            + '&ids='              + encodeURIComponent(ids.join(','));

        window.open(url);
    },

    // ── Exportar Excel ─────────────────────────────────────────────────────────
    exportarExcelSimulador: function() {
        var me   = this;
        var view = me.getSimuladorinteresesprincipal();
        if (!view) { return; }

        var rut = view.down('#rutConfirmado').getValue();
        if (!rut) {
            Ext.Msg.alert('Atención', 'Primero debe buscar un cliente y calcular los intereses.');
            return;
        }

        var grid     = view.down('#documentosGrid');
        var selected = grid.getSelectionModel().getSelection();

        if (!selected || selected.length === 0) {
            Ext.Msg.alert('Atención', 'Debe seleccionar al menos un documento para exportar.');
            return;
        }

        var ids    = Ext.Array.map(selected, function(rec) { return rec.get('id'); });
        var partes = view.down('#fechaSimulacion').getRawValue().split('/');
        var fechaEnvio = partes.length === 3
            ? partes[2] + '-' + partes[1] + '-' + partes[0]
            : view.down('#fechaSimulacion').getRawValue();

        var tasa2      = view.down('#tasaInteres').getValue();
        var diasCobro2 = view.down('#diasCobro').getValue() || 0;

        // Guardar log antes de abrir
        me._guardarLogSimulacion(view, selected, fechaEnvio, tasa2, diasCobro2, ids, 'EXCEL');

        var url = preurl + 'simulador_intereses/exportarExcel'
            + '?rut='              + encodeURIComponent(rut)
            + '&fecha_simulacion=' + encodeURIComponent(fechaEnvio)
            + '&tasa_interes='     + encodeURIComponent(tasa2)
            + '&dias_cobro='       + encodeURIComponent(diasCobro2)
            + '&ids='              + encodeURIComponent(ids.join(','));

        window.open(url);
    },

    // ── Guardar log de simulación (silencioso) ─────────────────────────────────
    _guardarLogSimulacion: function(view, selected, fechaSimulacion, tasa, diasCobro, ids, tipo) {
        var rut           = view.down('#rutConfirmado').getValue();
        var nombreCliente = view.down('#nombreClienteDisplay').getValue();

        var totalSaldo = 0, totalInteres = 0, totalInteresConIva = 0;
        Ext.Array.each(selected, function(rec) {
            totalSaldo         += rec.get('saldo')           || 0;
            totalInteres       += rec.get('interes')         || 0;
            totalInteresConIva += rec.get('interes_con_iva') || 0;
        });
        var totalPagar = totalSaldo + totalInteresConIva;

        Ext.Ajax.request({
            url:    preurl + 'simulador_intereses/guardarSimulacion',
            method: 'POST',
            params: {
                rut:                   rut,
                nombre_cliente:        nombreCliente,
                fecha_simulacion:      fechaSimulacion,
                tasa_interes:          tasa,
                dias_cobro:            diasCobro,
                total_saldo:           Math.round(totalSaldo),
                total_interes_neto:    Math.round(totalInteres),
                total_interes_con_iva: Math.round(totalInteresConIva),
                total_pagar:           Math.round(totalPagar),
                ids_documentos:        ids.join(','),
                tipo_exportacion:      tipo
            }
            // Sin handlers: guardado silencioso
        });
    },

    // ── Historial de simulaciones ──────────────────────────────────────────────
    verHistorialSimulador: function() {
        var me   = this;
        var view = me.getSimuladorinteresesprincipal();
        if (!view) { return; }

        var rut    = view.down('#rutConfirmado').getValue();
        var nombre = view.down('#nombreClienteDisplay').getValue();
        if (!rut) {
            Ext.Msg.alert('Atención', 'Primero debe buscar un cliente.');
            return;
        }

        var logStore = me.getSimuladorLogStore();
        logStore.getProxy().extraParams = { rut: rut };
        logStore.removeAll();
        logStore.load();

        var win = Ext.widget('simuladorlogpanel', {
            title:    'Historial de Simulaciones — ' + nombre + ' (' + me.formatRut(rut) + ')',
            logStore: logStore
        });
        win.show();
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
