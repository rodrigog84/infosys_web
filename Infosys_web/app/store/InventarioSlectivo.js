Ext.define('Infosys_web.store.InventarioSlectivo', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.existencias',
    autoLoad: true,
    pageSize: 14,
    
    proxy: {
        type: 'ajax',

        api: {
            read: preurl + 'existencias/getAllSlectivo',
           },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success',
        },
        writer: {
            type: 'json',
            writeAllFields: true,
            encode: true,
            root: 'data'
        }
    }
});