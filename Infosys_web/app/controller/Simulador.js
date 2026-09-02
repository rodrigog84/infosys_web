Ext.define('Infosys_web.controller.Simulador', {
    extend: 'Ext.app.Controller',

    // Cambiar a true cuando el cliente habilite facturación desde el simulador
    FACTURA_SIMULADOR_HABILITADA: false,

    MENSAJE_FACTURA_NO_DISPONIBLE:
        'La generación de facturas desde el Simulador de Intereses no está disponible en este momento.',

    facturaSimuladorHabilitada: function() {
        return this.FACTURA_SIMULADOR_HABILITADA === true;
    },

    avisarFacturaNoDisponible: function() {
        Ext.Msg.alert('Funcionalidad no disponible', this.MENSAJE_FACTURA_NO_DISPONIBLE);
    },

    bloquearFacturaSimulador: function() {
        if (this.facturaSimuladorHabilitada()) {
            return false;
        }
        this.avisarFacturaNoDisponible();
        return true;
    },

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
        'simulador.LogPanel',
        'simulador.FacturaDialog'
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
            'simuladorinteresesprincipal button[action=generarFacturaSimulador]': {
                click: this.generarFacturaSimulador
            },
            'simuladorfacturadialog button[itemId=btnConfirmarFactura]': {
                click: this.confirmarGenerarFactura
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
        view.down('#credAprobadoDisplay').setValue('');
        view.down('#credUtilizadoDisplay').setValue('');
        view.down('#fechaSimulacion').setValue(new Date());
        view.down('#tasaInteres').setValue(2.0);
        view.down('#diasCobro').setValue(0);

        // Limpiar totales
        me.actualizarTotales([]);

        // Deshabilitar botones contextuales y limpiar log id
        var btnHistorial = view.down('#btnHistorial');
        if (btnHistorial) { btnHistorial.setDisabled(true); }
        var btnFactura = view.down('#btnGenerarFactura');
        if (btnFactura) {
            btnFactura.setDisabled(true);
            btnFactura.setTooltip(me.facturaSimuladorHabilitada()
                ? 'Genera Factura Electrónica de Glosa por los intereses seleccionados'
                : 'Generación de facturas no disponible en este momento');
        }
        me._lastLogId = null;
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
                    var credAprob = obj.data.uf_cred || 0;
                    view.down('#credAprobadoDisplay').setValue(Ext.util.Format.number(credAprob, '0,000.') + ' UF');
                    view.down('#credUtilizadoDisplay').setValue('$ ' + Ext.util.Format.number(credUtil, '0,000.'));
                    // Habilitar historial; factura solo se habilita tras exportar
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

        // Guardar valores crudos (sin formato) para que el log siempre use lo que está en pantalla
        view.down('#rawTotalSaldo').setValue(totalSaldo);
        view.down('#rawTotalInteres').setValue(totalInteres);
        view.down('#rawTotalInteresConIva').setValue(totalInteresConIva);
        view.down('#rawTotalPagar').setValue(totalPagar);
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
        var numsDocPdf = Ext.Array.map(selected, function(r) { return r.get('numdocumento'); });
        me._guardarLogSimulacion(view, selected, fechaEnvio, tasa, diasCobro, ids, numsDocPdf, 'PDF');

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
        var numsDocXls = Ext.Array.map(selected, function(r) { return r.get('numdocumento'); });
        me._guardarLogSimulacion(view, selected, fechaEnvio, tasa2, diasCobro2, ids, numsDocXls, 'EXCEL');

        var url = preurl + 'simulador_intereses/exportarExcel'
            + '?rut='              + encodeURIComponent(rut)
            + '&fecha_simulacion=' + encodeURIComponent(fechaEnvio)
            + '&tasa_interes='     + encodeURIComponent(tasa2)
            + '&dias_cobro='       + encodeURIComponent(diasCobro2)
            + '&ids='              + encodeURIComponent(ids.join(','));

        window.open(url);
    },

    // ── Generar Factura desde la pantalla principal ────────────────────────────
    generarFacturaSimulador: function() {
        if (this.bloquearFacturaSimulador()) { return; }

        var me   = this;
        var view = me.getSimuladorinteresesprincipal();
        if (!view) { return; }

        var rut    = view.down('#rutConfirmado').getValue();
        var nombre = view.down('#nombreClienteDisplay').getValue();
        if (!rut) {
            Ext.Msg.alert('Atención', 'Primero debe buscar un cliente.');
            return;
        }

        var grid     = view.down('#documentosGrid');
        var selected = grid.getSelectionModel().getSelection();
        if (!selected || selected.length === 0) {
            Ext.Msg.alert('Atención', 'Debe seleccionar al menos un documento.');
            return;
        }

        // Calcular interés neto de los documentos seleccionados
        var netoInteres = 0;
        Ext.Array.each(selected, function(rec) {
            netoInteres += rec.get('interes') || 0;
        });
        netoInteres = Math.round(netoInteres);

        if (netoInteres <= 0) {
            Ext.Msg.alert('Atención', 'El interés calculado es cero. No es posible generar la factura.');
            return;
        }

        var ids         = Ext.Array.map(selected, function(r) { return r.get('id'); });
        var numsDoc     = Ext.Array.map(selected, function(r) { return r.get('numdocumento'); });
        var fechaSimRaw = view.down('#fechaSimulacion').getRawValue();

        me._abrirDialogoFactura({
            rut:              rut,
            rut_fmt:          me.formatRut(rut),
            nombre:           nombre,
            neto_interes:     netoInteres,
            ids_documentos:   numsDoc.join(', '),
            fecha_simulacion: fechaSimRaw,
            id_log:           me._lastLogId || null
        });
    },

    // ── Abrir diálogo de confirmación (reutilizable desde historial) ──────────
    _abrirDialogoFactura: function(data) {
        if (this.bloquearFacturaSimulador()) { return; }

        var me = this;

        // Primero obtener defaults del servidor
        Ext.Ajax.request({
            url:    preurl + 'simulador_intereses/prepararFactura',
            params: { rut: data.rut },
            success: function(response) {
                var obj = Ext.decode(response.responseText);
                if (!obj.success) {
                    Ext.Msg.alert('Error', obj.message || 'No se pudo obtener datos del cliente.');
                    return;
                }

                // Obtener folio electrónico
                var folioResp = Ext.Ajax.request({
                    async: false,
                    url: preurl + 'facturas/folio_documento_electronico/101'
                });
                var folioObj = Ext.decode(folioResp.responseText);

                if (folioObj.valida === 'SI') {
                    Ext.Msg.alert('Atención', 'Los folios electrónicos están vencidos. Renueve el CAF.');
                    return;
                }
                if (!folioObj.folio || folioObj.folio === 0) {
                    Ext.Msg.alert('Atención', 'No hay folios electrónicos disponibles para Factura (tipo 101).');
                    return;
                }

                // Guardar datos del servidor en la config del diálogo
                data._serverData = {
                    cliente:      obj.cliente,
                    id_bodega:    obj.id_bodega,
                    id_sucursal:  obj.id_sucursal,
                    id_tipo_gasto:obj.id_tipo_gasto,
                    folio:        folioObj.folio,
                    fecha_venc:   folioObj.fecha_venc,
                    id_folio:     folioObj.idfolio
                };

                var win = Ext.widget('simuladorfacturadialog', {
                    simulacionData: data
                });

                win.simulacionData = data;
                win.serverData   = data._serverData;

                // Handler explícito: el control() del controller no siempre enlaza botones del fbar
                var btn = win.down('#btnConfirmarFactura');
                btn._simulData  = data;
                btn._serverData = data._serverData;
                btn.setHandler(function() {
                    me.confirmarGenerarFactura(btn);
                });
                win.show();
            },
            failure: function() {
                Ext.Msg.alert('Error', 'No se pudo conectar con el servidor.');
            }
        });
    },

    // ── Confirmar y enviar la factura a facturaglosa/save ────────────────────
    confirmarGenerarFactura: function(btn) {
        if (this.bloquearFacturaSimulador()) { return; }

        var me  = this;
        var win = btn.up('window');
        var sd  = btn._simulData  || (win && win.simulacionData);
        var srv = btn._serverData || (win && win.serverData);

        if (!sd || !srv || !srv.cliente) {
            Ext.Msg.alert('Error', 'Faltan datos para generar la factura. Cierre el diálogo y vuelva a intentarlo.');
            return;
        }

        var glosa           = win.down('#glosaField').getValue();
        var fechaFacturaObj = win.down('#fechaFacturaField').getValue();
        var fechaVencObj    = win.down('#fechaVencField').getValue();

        if (!fechaFacturaObj || !fechaVencObj) {
            Ext.Msg.alert('Atención', 'Ingrese fecha de factura y fecha de vencimiento.');
            return;
        }

        var fechaFactura    = Ext.Date.format(fechaFacturaObj, 'Y-m-d');
        var fechaVenc       = Ext.Date.format(fechaVencObj,    'Y-m-d');
        var neto            = win.down('#netoHidden').getValue();
        var iva          = win.down('#ivaHidden').getValue();
        var total        = win.down('#totalHidden').getValue();

        if (!glosa) {
            Ext.Msg.alert('Atención', 'Ingrese el texto de la glosa.');
            return;
        }

        var cliente  = srv.cliente;
        var dataItems = [{
            glosa: glosa,
            neto:  neto,
            iva:   iva,
            total: total,
            id_producto: 0,
            kilos: 0
        }];

        var dataCliente = Ext.JSON.encode({
            id:       cliente.id,
            nombres:  cliente.nombres,
            rut:      cliente.rut,
            giro:     cliente.giro      || '',
            direccion:cliente.direccion  || '',
            ciudad:   cliente.ciudad     || '',
            comuna:   cliente.comuna     || ''
        });

        var mask = new Ext.LoadMask(Ext.getBody(), { msg: 'Generando Factura Electrónica...' });
        mask.show();

        Ext.Ajax.request({
            url:    preurl + 'facturaglosa/save',
            method: 'POST',
            params: {
                idcliente:     cliente.id,
                numdocumento:  srv.folio,
                idsucursal:    srv.id_sucursal,
                idcondventa:   cliente.id_pago || 1,
                idtipogasto:   srv.id_tipo_gasto,
                idbodega:      srv.id_bodega,
                ordencompra:   '',
                items:         Ext.JSON.encode(dataItems),
                vendedor:      cliente.id_vendedor || 1,
                fechafactura:  fechaFactura,
                fechavenc:     fechaVenc,
                tipodocumento: 101,
                netofactura:   neto,
                ivafactura:    iva,
                afectofactura: neto,
                totalfacturas: total,
                datacliente:   dataCliente,
                observacion:   '',
                idobserva:     ''
            },
            success: function(response) {
                mask.hide();
                var obj;
                try {
                    obj = Ext.decode(response.responseText);
                } catch (e) {
                    Ext.Msg.alert('Error', 'Respuesta inválida del servidor al generar la factura.');
                    return;
                }
                if (!obj || !obj.idfactura) {
                    Ext.Msg.alert('Error', 'No se pudo crear la factura. Revise los logs del servidor.');
                    return;
                }
                var idFactura  = obj.idfactura;
                var numFactura = srv.folio;

                // Vincular con el log si viene de historial (id_log conocido)
                if (sd.id_log) {
                    Ext.Ajax.request({
                        url:    preurl + 'simulador_intereses/vincularFactura',
                        method: 'POST',
                        params: { id_log: sd.id_log, id_factura: idFactura, num_factura: numFactura }
                    });

                    // Actualizar la fila en pantalla de inmediato, sin esperar recarga
                    var logStore = me.getSimuladorLogStore();
                    if (logStore) {
                        var rec = logStore.getById(sd.id_log);
                        if (rec) {
                            rec.set('id_factura_generada',  idFactura);
                            rec.set('num_factura_generada', numFactura);
                            rec.commit();
                        }
                    }
                }

                win.close();
                Ext.Msg.confirm('Factura Generada',
                    'Factura N° ' + numFactura + ' creada correctamente. ¿Desea ver el PDF?',
                    function(btn2) {
                        if (btn2 === 'yes') {
                            window.open(preurl + 'facturaglosa/exportPDF/?idfactura=' + idFactura);
                        }
                    }
                );
            },
            failure: function() {
                mask.hide();
                Ext.Msg.alert('Error', 'No se pudo generar la factura. Revise la consola del servidor.');
            }
        });
    },

    // ── Guardar log de simulación (silencioso) ─────────────────────────────────
    _guardarLogSimulacion: function(view, selected, fechaSimulacion, tasa, diasCobro, ids, numsDoc, tipo) {
        var me            = this;
        var rut           = view.down('#rutConfirmado').getValue();
        var nombreCliente = view.down('#nombreClienteDisplay').getValue();

        // Leer totales desde los hidden fields (actualizados en actualizarTotales)
        // para garantizar que coincidan exactamente con lo que se muestra en pantalla.
        // NO leer de rec.get('interes') del store porque puede estar desactualizado
        // si el usuario cambió tasa/fecha sin haber recalculado.
        var totalSaldo         = parseFloat(view.down('#rawTotalSaldo').getValue())        || 0;
        var totalInteres       = parseFloat(view.down('#rawTotalInteres').getValue())       || 0;
        var totalInteresConIva = parseFloat(view.down('#rawTotalInteresConIva').getValue()) || 0;
        var totalPagar         = parseFloat(view.down('#rawTotalPagar').getValue())         || 0;

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
                nums_documentos:       numsDoc.join(','),
                tipo_exportacion:      tipo
            },
            success: function(response) {
                // Habilitar botón siempre que el guardado sea exitoso (HTTP 200)
                var v = me.getSimuladorinteresesprincipal();
                if (v) {
                    var btnF = v.down('#btnGenerarFactura');
                    if (btnF) { btnF.setDisabled(false); }
                }
                // Intentar extraer el id del log para vincular la futura factura
                try {
                    var text = Ext.String.trim(response.responseText);
                    var obj  = Ext.decode(text);
                    if (obj && obj.id) { me._lastLogId = obj.id; }
                } catch(e) {}
            }
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
            logStore: logStore,
            // Callback para el botón de facturar en el historial
            onGenerarFactura: function(rec) {
                    me._abrirDialogoFactura({
                        rut:             rut,
                        rut_fmt:         me.formatRut(rut),
                        nombre:          nombre,
                        neto_interes:    rec.get('total_interes_neto'),
                        ids_documentos:  rec.get('nums_documentos') || rec.get('ids_documentos'),
                        fecha_simulacion: rec.get('fecha_simulacion_fmt'),
                        id_log:          rec.get('id')
                    });
            }
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
