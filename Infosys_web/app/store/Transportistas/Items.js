Ext.define('Infosys_web.store.Transportistas.Items', {
    extend: 'Ext.data.Store',
    model: 'Infosys_web.model.transportistas.Item',
    proxy: {
        type: 'memory',
        reader: {
            type: 'json',
            root: 'root'
        }
    }
});