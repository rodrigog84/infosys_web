Ext.define('Infosys_web.store.Bitacora_aviso', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.bitacora_avisos',
    autoLoad: true,
    pageSize: 14,
    
    proxy: {
        type: 'ajax',
       
        api: {
            read: preurl + 'bitacora_aviso/getAll',
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