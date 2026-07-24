Ext.define('Infosys_web.model.simulador.Log', {
    extend: 'Ext.data.Model',
    fields: [
        { name: 'id',                    type: 'int'    },
        { name: 'rut',                   type: 'string' },
        { name: 'nombre_cliente',        type: 'string' },
        { name: 'fecha_simulacion_fmt',  type: 'string' },
        { name: 'fecha_simulacion',      type: 'string' },
        { name: 'tasa_interes',          type: 'float'  },
        { name: 'dias_cobro',            type: 'int'    },
        { name: 'total_saldo',           type: 'float'  },
        { name: 'total_interes_neto',    type: 'float'  },
        { name: 'total_interes_con_iva', type: 'float'  },
        { name: 'total_pagar',           type: 'float'  },
        { name: 'ids_documentos',        type: 'string' },
        { name: 'tipo_exportacion',      type: 'string' },
        { name: 'fecha_ejecucion_fmt',   type: 'string' },
        { name: 'nombre_usuario',        type: 'string' }
    ]
});
