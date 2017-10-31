Ext.define('Infosys_web.store.facturacompra.Items', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.facturacompra.Item',
    proxy: {
        type: 'memory',
        reader: {
            type: 'json',
            root: 'root'
        }
    }
});