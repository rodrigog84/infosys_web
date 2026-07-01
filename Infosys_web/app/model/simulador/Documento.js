Ext.define('Infosys_web.model.simulador.Documento', {
    extend: 'Ext.data.Model',
    fields: [
        { name: 'id',              type: 'int'    },
        { name: 'tipodocumento',   type: 'int'    },
        { name: 'tipo_desc',       type: 'string' },
        { name: 'numdocumento',    type: 'string' },
        { name: 'nombre_documento',type: 'string' },
        { name: 'fecha_emision_fmt',type: 'string' },
        { name: 'fecha_venc_fmt',  type: 'string' },
        { name: 'fecha_venc',      type: 'string' },
        { name: 'saldo',           type: 'float'  },
        { name: 'dias_mora',       type: 'int'    },
        { name: 'interes',         type: 'float'  },
        { name: 'total_documento', type: 'float'  }
    ]
});
