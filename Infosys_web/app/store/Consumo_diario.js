Ext.define('Infosys_web.store.Consumo_diario', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.Consumo_diario',
    autoLoad: true,
    pageSize: 14,
    
    proxy: {
        type: 'ajax',
        actionMethods:  {
            read: 'POST'
         },
        api: {
            read: preurl + 'tipo_movimientodiario/getAll2'
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        },
        writer: {
            type: 'json',
            writeAllFields: true,
            encode: true,
            root: 'data'
        }
    }
});